<?php

namespace App\Http\Controllers\Web;

use Exception;
use App\Models\User;
use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Constants\AppConstants;
use App\Mail\ForgotPasswordEmail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        try {
            return view('web.auth.password.generate-token');
        } catch (Exception $exception) {
            Log::error('ForgotPasswordController::index() Error: ', [$exception]);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Generate token for forgot password
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => [
                    'required',
                    'email',
                    'exists:users,email',
                ],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return redirect()->back()->with('error', 'User not found');
            }

            $token = Helper::generateUniqueToken();
            $user->update(['token' => $token]);

            $mailData = [
                'token' => $token,
                'first_name' => $user->name,
                'route' => 'forgot-password.reset',
                'user_type' => $user->user_type,
                'email' => AppConstants::HELP_MAIL,
            ];

            Mail::to($request->email)->send(new ForgotPasswordEmail($mailData));

            return redirect()->back()->with('success', 'A password reset link has been sent to your email');
        } catch (Exception $exception) {
            Log::error('ForgotPasswordController::generateToken() Error: ', ["exception" => $exception, "request" => $request->all()]);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Change password form
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword()
    {
        try {
            return view('web.auth.password.change-password');
        } catch (Exception $exception) {
            Log::error('ForgotPasswordController::changePassword() Error: ', [$exception]);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Change password for forgot password
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => [
                    'required',
                    'email',
                    'exists:users,email',
                ],
                'token' => [
                    'required',
                    'string',
                    'exists:users,token',
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8', // Minimum length
                    'regex:/[a-z]/', // At least one lowercase letter
                    'regex:/[A-Z]/', // At least one uppercase letter
                    'regex:/[0-9]/', // At least one number
                    'regex:/[\W_]/', // At least one special character
                ],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = User::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

            if (!$user) {
                return redirect()->back()->with('error', 'User not found');
            }
            $user->update(['password' => Hash::make($request->password), 'token' => null]);

            $route = '';
            if ($user->user_type == User::STUDENT) {
                $route = 'student.login-page';
            } elseif ($user->user_type == User::TEACHER) {
                $route = 'teacher.login-page';
            }

            return redirect()->back()->with('success', 'Password changed successfully')->with('route', $route);
        } catch (Exception $exception) {
            Log::error('ForgotPasswordController::updatePassword() Error: ', ["exception" => $exception, "request" => $request->all()]);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
