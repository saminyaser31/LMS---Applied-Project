<?php

namespace App\Http\Controllers\Web;

use App\Constants\AppConstants;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherRequest;
use App\Mail\VerifyEmail;
use App\Models\Teacher\Teachers;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeacherAuthController extends Controller
{
    private $user = null;
    private $teacher = null;
    private string $token = '';

    /**
     * Show the dedicated page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function loginPage()
    {
        return view('web.auth.teacher.login');
    }
    /**
     * Show the dedicated page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function registerPage()
    {
        return view('web.auth.teacher.register');
    }

    /**
     * Write code on Method
     * @param StoreTeacherRequest $request
     * @return response()
     */
    public function register(StoreTeacherRequest $request)
    {
        try {
            $data = $request->all();
            $createUser = $this->createUser($request);

            if ($createUser) {
                $this->sendMail($data, $request);
                return Redirect()->back()->with('postRegistrationMessage', 'A verification mail has been sent to your email address, please confirm your mail account.');

                return Redirect()->route('teacher.login-page')->with('signinPageMessage', 'You have been registered, please verify your email to signin your account.');
                // dd("Email is sent successfully.");

                // return redirect("dashboard")->withSuccess('Great! You have Successfully loggedin');
            }
            // dd($data);
        } catch (Exception $exception) {
            Log::error("TeacherAuthController::register() ", [$exception]);
            return Redirect()->back()->with('registrationMessage', 'Something went wrong!');
        }
    }

    public function sendMail($data, $request)
    {
        try {
            $mailData = [
                'token' => $this->token,
                'first_name' => $data['first_name'],
                'route' => 'teacher.verify',
                'user_type' => User::TEACHER,
                'email' => AppConstants::HELP_MAIL,
            ];

            Mail::to($request->email)->send(new VerifyEmail($mailData));
        } catch (Exception $exception) {
            Log::error("TeacherAuthController::sendMail() ", [$exception]);
            return null;
        }
    }

    /**
     * Write code on Method
     *
     * @return string
     */
    public function createUser(StoreTeacherRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->token = Helper::generateUniqueToken();
                // First query: Create a new user
                $this->user = User::create([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                    'phone_no' => $request->phone_no,
                    'password' => Hash::make($request->password),
                    'user_type' => User::TEACHER,
                    // 'email_verified_at' => Carbon::now(),
                    'token' => $this->token,
                    'approved' => 0
                ]);
                // dd($this->user);

                // Define base directory for teacher images
                $teacherDir = 'teacher/' . $this->user->id;

                // Ensure the directory exists
                Storage::makeDirectory($teacherDir);

                // Save the profile image
                $profilePath = null;
                if ($request->hasFile('image')) {
                    $profileImage = $request->file('image');
                    $profilePath = $profileImage->storeAs($teacherDir, 'profile-picture.' . $profileImage->getClientOriginalExtension(), 'public');
                }

                // Save the cover image
                $coverPath = null;
                if ($request->hasFile('cover_image')) {
                    $coverImage = $request->file('cover_image');
                    $coverPath = $coverImage->storeAs($teacherDir, 'cover-picture.' . $coverImage->getClientOriginalExtension(), 'public');
                }

                // Save the NID front image
                $nidFrontPath = null;
                if ($request->hasFile('nid_front_image')) {
                    $nidFrontImage = $request->file('nid_front_image');
                    $nidFrontPath = $nidFrontImage->storeAs($teacherDir, 'nid-front.' . $nidFrontImage->getClientOriginalExtension(), 'public');
                }

                // Save the NID back image
                $nidBackPath = null;
                if ($request->hasFile('nid_back_image')) {
                    $nidBackImage = $request->file('nid_back_image');
                    $nidBackPath = $nidBackImage->storeAs($teacherDir, 'nid-back.' . $nidBackImage->getClientOriginalExtension(), 'public');
                }

                // dump(Storage::url($profilePath));
                // dd($profilePath, $nidFrontPath, $nidBackPath);

                // Second query: Create a new teacher record
                $this->teacher = Teachers::create([
                    'user_id' => $this->user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone_no' => $request->phone_no,
                    'password' => $this->user->password,
                    'dob' => $request->dob,
                    'gender' => $request->gender,
                    'marital_status' => $request->marital_status,
                    'religion' => $request->religion,
                    'nid_no' => $request->nid_no,
                    'address' => $request->address,
                    'image' => Storage::url($profilePath),
                    'cover_image' => Storage::url($coverPath),
                    'nid_front_image' => Storage::url($nidFrontPath),
                    'nid_back_image' => Storage::url($nidBackPath),
                    'bio' => $request->bio ?? null,
                    'detailed_info' => $request->detailed_info ?? null,
                    'created_by' => (Auth::user() && Auth::user()->id) ? Auth::user()->id : null,
                ]);
                // dd($teacher);
            });

            DB::commit();

            return $this->teacher;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error("TeacherAuthController::createUser() ", [$exception]);
            return null;
        }
    }

    private function deleteFile($folderPath, $filePath)
    {
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Check if the directory is empty before deleting
        if (File::isDirectory($folderPath) && count(File::files($folderPath)) === 0) {
            File::deleteDirectory($folderPath);
        }
    }

    private function createDirectory($tempFolderPath)
    {
        if (!File::isDirectory($tempFolderPath)) {
            File::makeDirectory($tempFolderPath, 0777, true, true);
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function verifyAccount($token)
    {
        try {
            $verifyUser = User::where('token', $token)->first();

            $message = 'Sorry your email cannot be identified';

            if ($verifyUser) {
                $user = User::find($verifyUser->id);
                $user = $verifyUser;
                // dd($user);

                if (!$verifyUser->email_verified_at) {
                    $verifyUser->email_verified_at = Carbon::now();
                    $verifyUser->save();
                    $message = "Your e-mail is verified. You can now login";

                    Auth::login($user);

                    return redirect('/teacher')->withSuccess('Signed in');
                } else {
                    $message = "Your e-mail is already verified. You can now login.";

                    Auth::login($user);

                    return redirect('/teacher')->withSuccess('Signed in');
                }
            }
            // dd($message);

            return redirect()->route('teacher.register-page')->with('message', $message);
        } catch (Exception $exception) {
            Log::error("TeacherAuthController::verifyAccount() ", [$exception]);
        }
    }
}
