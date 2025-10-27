<?php

namespace App\Http\Controllers\Api\V1;

use App\Constants\AppConstants;
use App\Constants\EmailConstants;
use App\Http\Controllers\Api\V1\Admin\CompetitionController;
use App\Jobs\EmailJob;
use App\Models\Account;
use App\Models\Customer;
use App\Models\TempCompetitionRegister;
use App\Services\Competitions\CompetitionService;
use App\Services\CustomerSuspensionService;
use App\Services\NotificationService;
use App\Traits\Auditable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\CustomerAuthService;
use App\Http\Requests\CustomerApiLoginRequest;
use App\Http\Requests\CustomerApiSignupRequest;
use App\Http\Requests\GetProfileUpdateRequest;
use App\Jobs\AffiliateApiCallJob;
use App\Models\Country;
use App\Models\CustomerPreference;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CustomerAuthController extends Controller
{
    use Auditable;
    private $customerAuth;
    public const USER_SIGN_UP = 'user_sign_up';
    public function __construct()
    {
        $this->customerAuth = new CustomerAuthService();
    }

    public function login(CustomerApiLoginRequest $request)
    {
        try {
            if ($request->filled('token')) {
                $socialiteUser = $this->customerAuth->getSocialUserByToken(
                    $request->input('token'),
                    $request->input('provider'),
                    $request->input('redirect_uri')
                );

                $email    = $socialiteUser->email;
                $customer = $this->customerAuth->findActiveByEmail($email);
                $token    = $customer->createToken($customer->email);

                $this->auditLogEntry('customer:social-login', $customer->id, 'customer-social-login', $customer);
                return ResponseService::apiResponse(
                    200,
                    "Successfully logged in!",
                    [
                        'user'  => $customer,
                        'token' => [
                            'accessToken'   => $token->plainTextToken,
                            'accessTokenJl' => null,
                        ]
                    ]
                );
            }

            $customer = $this->customerAuth->login($request->email, $request->password);
            if (!$request->filled('token')) {
                if ($customer->isNotVerifyEmail()) {
                    $this->customerAuth->requestOtpVerification($customer);
                    return ResponseService::apiResponse(
                        200,
                        "waiting for verification",
                        [
                            'email_verify' => false,
                            'user'         => [
                                'email' => $customer->email,
                                'remember_token'  => $customer->remember_token,
                                'otp_expire_date' => $customer->otp_expire_date
                            ],
                        ]
                    );
                }
            }

            $token = $customer->createToken($customer->email);
            $this->auditLogEntry('customer:login', $customer->id, 'customer-login', $customer);
            return ResponseService::apiResponse(
                200,
                "Successfully logged in!",
                [
                    'user'  => $customer,
                    'token' => [
                        'accessToken'   => $token->plainTextToken,
                        'accessTokenJl' => $response['data']['token']['accessToken'] ?? null,
                    ]
                ]
            );
        } catch (HttpException $exception) {
            return ResponseService::apiResponse(400, "Email or password incorrect");
        } catch (Exception $exception) {
            Log::error('login error', [$exception]);
            return ResponseService::apiResponse(400, "Internal Server Error");
        }
    }

    public function logout(Request $request)
    {
        $token = request()->user()->tokens()->delete();
        $this->auditLogEntry('customer:logout', request()->user()->id, 'customer-logout', request()->user());
        return ResponseService::apiResponse(200, "SUCCESS");
    }

    public function signup(CustomerApiSignupRequest $request)
    {
        try {
            if ($request->filled('token')) {
                $socialEmail = $this->customerAuth->isValidSocialUser(
                    $request->input('email'),
                    $request->input('token'),
                    $request->input('provider'),
                    $request->input('redirect_uri')
                );
                if (!$socialEmail) {
                    return ResponseService::apiResponse(402,  "You are not authorized to make this action!");
                } else {
                    return ResponseService::apiResponse(
                        200,
                        "Successfully logged in!",
                        $this->customerAuth->socialSignup($socialEmail['email'], $socialEmail['name'])
                    );
                }
            }

            $customer = $this->customerAuth->signup(
                $request->first_name,
                $request->last_name,
                $request->country_id,
                $request->email,
                $request->password
            );

            // Create default preferences with email_notifications always set to true
            CustomerPreference::create([
                'customer_id' => $customer->id,
                'email_notifications' => true,
            ]);

            if($request->filled('competition_id')){
                TempCompetitionRegister::create([
                    'competition_id' => $request->competition_id,
                    'user_id' => $customer->id,
                ]);
            }
            $details = [
                'template_id' => EmailConstants::OTP_VERIFICATION,
                'to_name'     => $customer->name,
                'to_email'    => $customer->email,
                'email_body'  => ["name" => $customer->name, "email" => $customer->email, "verify_email_otp" => $customer->otp],
            ];
            EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
            self::commonAnalytics(self::USER_SIGN_UP, $customer, $request);
            return ResponseService::apiResponse(
                200,
                "Registration successful! ",
                [
                    'email_verify' => false,
                    'user'         => [
                        'email'             => $customer->email,
                        'remember_token'    => $customer->remember_token,
                        'otp_expire_date'   => $customer->otp_expire_date,
                    ]
                ]
            );
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Integrity constraint violation
                return ResponseService::apiResponse(400, 'Email already exists.');
            }
            throw $e;
        }
         catch (Exception $exception) {
            Log::error("Signup error", [$exception]);
            return ResponseService::apiResponse(500, "Internal Server Error");
        }
    }

    public function getAuthorizeUrl(Request $request, $provider)
    {
        if (!$this->customerAuth->isValidSocialAuthProvider($provider)) {
            return ResponseService::apiResponse(402, "The given resource is invalid!");
        }

        return ResponseService::apiResponse(
            200,
            "Success!",
            $this->customerAuth->generateAuthorizeUrlFor($provider, $request->input('redirect_uri'))
        );
    }

    public function callback(Request $request, $provider)
    {
        if (!$this->customerAuth->isValidSocialAuthProvider($provider)) {
            return ResponseService::apiResponse(402, "The given resource is invalid!");
        }

        $user = $this->customerAuth->getSocialUserOfProvider($provider, $request->input('redirect_uri'));

        return ResponseService::apiResponse(
            200,
            "Success!",
            [
                'token' => $user->token,
                'user'  => [
                    'email' => $user->email ?? null
                ]
            ]
        );
    }


    /**
     * @throws Exception
     */
    public function otpResend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        if ($validator->fails()) {
            return ResponseService::apiResponse(422, 'Invalid Input', $validator->errors());
        }

        $customer = Customer::where("email", $request->email)->first();
        if ($customer) {
            if ($customer->email_verified_at == null) {
                (new CustomerAuthService)->requestOtpVerification($customer);
                return ResponseService::apiResponse(200, "Registration successful!", [
                    'email_verify' => false,
                    'user'         => [
                        'email'           => $customer->email,
                        'remember_token'  => $customer->remember_token,
                        'otp_expire_date' => $customer->otp_expire_date,
                    ]
                ]);
            }
            return ResponseService::apiResponse(409, "Your email already verified");
        }
        return ResponseService::apiResponse(404, "Your email is invalid");
    }

    public function emailVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp'            => 'required|min:6|max:6',
            'token'          => 'string|required|max:250'
        ]);
        if ($validator->fails()) {
            return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
        }
        try {
            $customer = Customer::where('remember_token', $request->token)->where('otp', $request->otp)->first();
            if ($customer) {
                if ($customer->otp_expire_date >= Carbon::now()) {
                    (new CustomerAuthService)->afterOtpVerificationUpdate($customer);
                    $this->registerForCompetition($customer);

                    $token = $customer->createToken($customer->email)->plainTextToken;
                    $this->auditLogEntry('customer:login', $customer->id, 'customer-login', $customer);
                    return ResponseService::apiResponse(
                        200,
                        "Successfully logged in!",
                        [
                            'user'  => $customer,
                            'token' => [
                                'accessToken'   => $token,
                            ]
                        ]
                    );
                } else {

                    return ResponseService::apiResponse(410, 'OTP time is expire. please resend otp');
                }
            }
            return ResponseService::apiResponse(404, "Uh-oh! Incorrect OTP code.");
        } catch (Exception $exception) {
            Log::error("email verification error", [$exception]);
            return ResponseService::apiResponse(500, 'Internal server error');
        }
    }


    public function getProfile()
    {
        try {
            $customer = Auth::user();
            $customer->load(['customerCountry', 'customerMobileCountry', 'preferences']);
            $isPremium = Cache::get("customer_account_count_".$customer->id);
            if(!$isPremium){
                $isPremium = Cache::remember('customer_account_count_' . $customer->id, 4320, function () use ($customer) { //72 hours cache
                    return Account::where('customer_id', $customer->id)->exists();
                });
            }

            $array = [
                'id'               => $customer->id,
                'first_name'       => $customer->first_name,
                'last_name'        => $customer->last_name,
                'full_name'        => $customer->full_name,
                'mobile'           => $customer->phone,
                'email'            => $customer->email,
                'address'          => $customer->address,
                'avatar'           => $customer->avatar,
                'city'             => $customer->city,
                'state'            => $customer->state,
                'postcode'         => $customer->zip,
                'gender'           => $customer->gender,
                'mobile_country'   => $customer->customerMobileCountry,
                'country'          => $customer->customerCountry,
                'is_updated'       => $customer->is_updated,
                'is_premium'       => $isPremium ? 1 : 0,
                'preference'   => [
                    "completed_web_help_tour"            => true,
                    "enabled_telegram_notification"      => true,
                    "completed_mobile_ios_help_tour"     => false,
                    "enabled_mobile_push_notification"   => true,
                    "completed_mobile_android_help_tour" => false,
                    "email_notifications"                => ($customer->preferences->email_notifications ?? 1) == 1 ? true : false,
                ],
                'suspension_info' => CustomerSuspensionService::customerSuspension($customer)
            ];

            return ResponseService::apiResponse(
                200,
                "Success",
                [
                    'user'     => $array,
                    'settings' => [
                        'app_preview' => false
                    ]
                ]
            );
        } catch (Exception $exception) {
            Log::error('profile error', [$exception]);
            return ResponseService::apiResponse(500, "Internal Server Error");
        }
    }

    public function getProfileUpdate(GetProfileUpdateRequest $request)
    {
        try {
            $user = Customer::with('customerCountry')->find(Auth::user()->id);

            $data = [
                "phone"                 => $request->input('mobile', $user->phone),
                "gender"                => $request->input('gender', $user->gender),
                "country_id"            => $request->input('country_id', $user->country_id),
                "mobile_country_id"     => $request->input('mobile_country_id', $user->mobile_country_id),
                "city"                  => $request->input('city', $user->city),
                "address"               => $request->input('address', $user->address),
                "zip"                   => $request->input('postcode', $user->zip),
            ];

            $user->update($data);
            $this->auditLogEntry('customer:profile-update', $user->id, 'customer:profile-update', $data);

            $mobile = $user->phone;
            $country = $user->customerCountry;
            unset($user->phone);
            unset($user->customerCountry);
            $user->mobile = $mobile;
            $user->country = $country;
            $user->mobile_country = $country;

            return ResponseService::apiResponse(200, "Successfully updated!", $user);
        } catch (Exception $exception) {
            Log::error('getProfileUpdate', [$exception]);
            return ResponseService::apiResponse(500, "Internal Server Error");
        }
    }

    /**
     * check for competition registration
     * @param $customer
     * @return void
     */
    public function registerForCompetition($customer){
        try {
            $competitionRegistration = TempCompetitionRegister::where('user_id', $customer->id)->first();
            if($competitionRegistration){
                $competition = new CompetitionController(new CompetitionService());
                $requestMaker = new Request();
                $requestMaker->merge(['competition_id' => $competitionRegistration->competition_id]);
                $competition->register($requestMaker, $customer);
                $competitionRegistration->delete();
            }
        }catch (Exception $exception) {
            Log::error('registerForCompetition() error', [$exception]);
        }
    }

    private static function commonAnalytics($type, $customer, $request)
    {
        //firstpromoter signUp call
        if ($type == self::USER_SIGN_UP && isset($request->__fpr__token)) {
            $refId = (isset($request->__fpr__token)) ? base64_decode($request->__fpr__token) : null;
            AffiliateApiCallJob::dispatch(null, $refId, null,  $request->email, AffiliateApiCallJob::SIGN_UP)->onQueue(AppConstants::QUEUE_ANALYTICS_JOB);
        }

    }
    /**
     * Method emailCheck
     *
     * @param Request $request [email]
     *
     * @return \Illuminate\Http\Response
     */
    public function emailCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:customers,email'
        ]);
        if ($validator->fails()) {
            return ResponseService::apiResponse(422, 'The given data was invalid.', $validator->errors());
        }
        return ResponseService::apiResponse(200, "This is a new user email");
    }
}
