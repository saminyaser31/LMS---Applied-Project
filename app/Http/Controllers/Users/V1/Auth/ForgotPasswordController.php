<?php

namespace App\Http\Controllers\Users\V1\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\CustomerAuthService;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Log, Validator};

class ForgotPasswordController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $email = $request->email;
            $data = (new CustomerAuthService)->handleForgotPassword($email);
            $link = (new CustomerAuthService)->generateForgotPasswordLink($data);

            (new CustomerAuthService)->dispatchForgotPasswordEmailJob($data->name, $email, $link);

            return ResponseService::apiResponse(
                200,
                "An instruction to reset your password has been sent to $email. Please check your email.",
                []
            );
        } catch (Exception $exception) {
            Log::error("confirmForgotPassword", [$exception]);
            return ResponseService::apiResponse(
                500,
                "Internal Server Error",
                []
            );
        }
    }

    public function resetPassword(Request $request)
    {
        if (Auth::check()) {
            try {
                $user = Auth::user();
                $requestEmail = $request->email;
                if ($user->email == $requestEmail) {
                    $user->update([
                        "password" => Hash::make($request->password)
                    ]);
                    return ResponseService::apiResponse(
                        200,
                        "Your password has been reset successfully!",
                        []
                    );
                }
            } catch (Exception $exception) {
                Log::error("confirmForgotPassword", [$exception]);
                return ResponseService::apiResponse(
                    500,
                    "Internal Server Error",
                    []
                );
            }
        }
    }

    /**
     * Method changePassword
     *
     * @param Request $request [explicite description]
     *
     * @return
     */
    public function changePassword(Request $request)
    {
        $old_password = Auth::user()->password;
        if (!Hash::check($request->old_password, $old_password)) {
            return ResponseService::apiResponse(
                400,
                "Old password does not match",
            );
        }
        // Run the validation
        $validator = Validator::make($request->all(), [
            'password' => [
                "min:8",
                "required",
            ],
            'password_confirm' => 'required|same:password',
        ], [
            'size' => 'The :attribute must be 8 characters long.',
            'same' => 'The password does not match.',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return ResponseService::apiResponse(422, "Validation errors", $validator->errors());
        }

        try {
            Auth::user()->update([
                "password" => Hash::make($request->password)
            ]);
            return ResponseService::apiResponse(200, "Password updated successfully.");
        } catch (Exception $exception) {
            Log::error("v1-changePassword", [$exception]);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }
}
