<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Constants\EmailConstants;
use App\Jobs\EmailJob;
use App\Models\Country;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

class CustomerAuthService
{
    public const SOCIAL_AUTH_PROVIDERS = ['facebook', 'google'];

    /**
     * @return array
     */
    public function login($email, $password)
    {
        $customer = Customer::with('customerCountry')->where('email', $email)->first();
        if (!$customer) throw new HttpException(402, "Customer not found!");

        if (!Hash::check($password, $customer->password)){
            throw new HttpException(402, "Password is incorrect!");
        }
        $customer->country = $customer->customerCountry;
        return $customer;
    }

    /**
     * @throws Exception
     */
    public function signup($fname, $lname, $countryId, $email, $password)
    {
        $country = Country::find($countryId);
        $data = [
            'name' => $fname . " " . $lname,
            'first_name' => $fname,
            'last_name' => $lname,
            'full_name' => $fname . " " . $lname,
            'country_id' => $country->id,
            'country' => $country->name,
            'email' => $email,
            'password' => bcrypt($password),
            'preference' => AppConstants::CUSTOMER_PREFERENCE_DATA,
            'otp' => self::randomNumberGenerate(),
            'remember_token' => self::randomNumber(),
            'otp_expire_date' => self::otpExpireDate()
        ];

        return Customer::create($data);
    }

    public function socialSignup($email, $name = null)
    {
        if (is_null($name) || empty($name)) {
            $name = explode('@', $email)[0];
        }

        $nameDevider = explode(' ', $name);
        $fname = (isset($nameDevider[0]) ? $nameDevider[0] : null);
        $lname = (isset($nameDevider[1]) ? $nameDevider[1] : null);

        $data = [
            'name' => $name,
            'first_name' => $fname,
            'last_name' => $lname,
            'full_name' => $name,
            'country_id' => null,
            'country' => null,
            'email' => $email,
            'password' => null,
            'email_verified_at' => Carbon::now(),
            'preference' => AppConstants::CUSTOMER_PREFERENCE_DATA
        ];

        $customer = Customer::create($data);
        $token  = $customer->createToken($customer->email);
        return [
            'user' => $customer,
            'token' => [
                'accessToken' => $token->plainTextToken,
                'accessTokenJl' => null,
                'meta' => $token->accessToken
            ]
        ];
    }

    public function isValidSocialAuthProvider(string $provider): bool
    {
        return in_array($provider, self::SOCIAL_AUTH_PROVIDERS);
    }

    public function isValidSocialUser(string $email, string $token, string $provider, ?string $redirectUri = null)
    {
        $user = $this->getSocialUserByToken($token, $provider, $redirectUri);

        if ($email == $user->email) {
            return [
                'email' => $user->email,
                'name' => $user->name,
            ];
        } else {
            return false;
        }
    }

    public function generateAuthorizeUrlFor(string $provider, ?string $redirectUri = null): array
    {
        if ($redirectUri) {
            $data = Socialite::driver($provider)
                ->redirectUrl($redirectUri)
                ->stateless()
                ->redirect()
                ->getTargetUrl();
        } else {
            $data = Socialite::driver($provider)
                ->stateless()
                ->redirect()
                ->getTargetUrl();
        }

        return [
            'authorization_url' => $data
        ];
    }

    public function getSocialUserByToken(string $token, string $provider, ?string $redirectUri = null): SocialiteUser
    {
        try {
            if ($redirectUri) {
                $user = Socialite::driver($provider)
                    ->redirectUrl($redirectUri)
                    ->stateless()
                    ->userFromToken($token);
            } else {
                $user = Socialite::driver($provider)
                    ->stateless()
                    ->userFromToken($token);
            }

            if (!$user) {
                throw new HttpException(404, "User not found!");
            }

            return $user;
        } catch (Exception) {
            throw new HttpException(404, "User not found!");
        }
    }

    public function getSocialUserOfProvider(string $provider, ?string $redirectUri = null): SocialiteUser
    {
        try {
            if ($redirectUri) {
                $user = Socialite::driver($provider)
                    ->redirectUrl($redirectUri)
                    ->stateless()
                    ->user();
            } else {
                $user = Socialite::driver($provider)
                    ->stateless()
                    ->user();
            }

            if (!$user) {
                throw new HttpException(404, "User not found!");
            }
        } catch (Exception $e) {
            throw new HttpException(404, "User not found!");
        }

        return $user;
    }

    public function findActiveByEmail($email): Customer
    {
        $user = Customer::where('email', $email)->first();

        if (blank($user)) {
            throw new HttpException(404, 'There\'s no account for this email');
        }

        return $user;
    }

    /**
     * @param User $user
     * @return void
     * @throws \Exception
     */
    public function requestOtpVerification(Customer $customer): void
    {
        $customer->otp             = $this->randomNumberGenerate();
        $customer->otp_expire_date = $this->otpExpireDate();
        $customer->remember_token  = $this->randomNumber();
        $customer->country         = null;
        $customer->save();

        $details = [
            'template_id' => EmailConstants::OTP_VERIFICATION,
            'to_name'     => $customer->name,
            'to_email'    => $customer->email,
            'email_body'  => ["name" => $customer->name, "email" => $customer->email, "verify_email_otp" => $customer->otp],
        ];
        EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
    }


    /**
     * @param Customer $customer
     * @return void
     */
    public function afterOtpVerificationUpdate(Customer $customer): void
    {
        $customer->email_verified_at = Carbon::now();
        $customer->remember_token    = null;
        $customer->otp               = null;
        $customer->otp_expire_date   = null;
        $customer->save();
    }

    /**
     * @return int
     * @throws \Exception
     */
    private function randomNumberGenerate(): int
    {
        $otp = random_int(100000, 999999);
        if (env('APP_ENV') == 'local') {
            $otp = 123456; // for testing staging
        }
        return $otp;
    }

    /**
     * @return Carbon
     */
    private function otpExpireDate(): Carbon
    {
        return Carbon::now()->addMinutes(3);
    }

    /**
     * @return string
     */
    private function randomNumber(): string
    {
        return Str::random(40);
    }

    public function handleForgotPassword($email)
    {
        $user = Customer::where('email', $email)->firstOrFail();
        $user->token = $user->createToken($user->email, ['expires_at' => 900]);

        return $user;
    }

    public function generateForgotPasswordLink($user)
    {
        $token = $user->token->plainTextToken;
        if ($token) {
            $url = env('CLIENT_URL') . '/password/reset/' . base64_encode($token) . '?email=' . $user->email;
            return $url;
        }
    }

    public function dispatchForgotPasswordEmailJob($name, $email, $link)
    {
        $details = [
            'template_id' => EmailConstants::RESET_PASSWORD_EMAIL,
            'to_name'     => $name,
            'to_email'    => $email,
            'email_body'  => [
                "name" => $name,
                "email" => $email,
                "reset_link" => $link
            ],
        ];
        EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
    }

    public function handleResetPassword($request)
    {
        $user = Customer::where('email', $request->email)->firstOrFail();
        $user->password         = Hash::make($request->password);
        if ($user) {
            $this->dispatchResetPasswordEmailJob($user->name, $user->email);
        };
    }

    public function dispatchResetPasswordEmailJob($name, $email)
    {
        $details = [
            'template_id' => EmailConstants::RESET_PASSWORD_SUCCESSFUL_EMAIL,
            'to_name'     => $name,
            'to_email'    => $email,
            'email_body'  => [
                "name" => $name,
                // TODO :: change redirect url after instruction
                // "redirect_url" => 'https://www.fundednext.com/'
            ],
        ];
        EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
    }
}
