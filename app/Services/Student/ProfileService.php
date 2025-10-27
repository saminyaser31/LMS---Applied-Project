<?php

namespace App\Services\Student;

use App\Constants\AppConstants;
use App\Http\Requests\UpdateChangePasswordRequest;
use App\Http\Requests\UpdateStudentProfileRequest;
use App\Models\Student\Students;
use App\Models\User;
use App\Traits\Auditable;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    use Auditable;
    private $user = null;
    private string $token = '';

    /**
     * update profile
     *
     * @param UpdateStudentProfileRequest $request
     * @param Students $student
     * @return \App\Models\Student\Students|null
     */
    public function update(UpdateStudentProfileRequest $request, $student): Students|null
    {
        try {
            // dd($request->all());
            DB::beginTransaction();

            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->phone_no = $request->phone_no;
            $student->dob = $request->dob;
            $student->marital_status = $request->marital_status;
            $student->religion = $request->religion;
            $student->address = $request->address;
            $student->updated_by = Auth::user()->id;

            // Define base directory for student images
            $studentDir = 'student/' . $student->user_id;

            // Ensure the directory exists
            Storage::makeDirectory($studentDir);

            // Save the profile image
            if ($request->hasFile('image')) {
                $student->image = $this->saveImage($request->file('image'), $studentDir, 'profile-picture.' . $request->file('image')->getClientOriginalExtension());
            }

            $student->save();

            DB::commit();

            return $student;
        } catch (Exception $exception) {
            Log::error("ProfileService::update()", [$exception]);
            DB::rollback();
            return null;
        }
    }

    /**
     * Save the uploaded image to the specified directory and return the URL.
     *
     * @param \Illuminate\Http\UploadedFile $imageRequest The uploaded image file.
     * @param string $directory The directory where the image should be stored.
     * @param string $imageName The name of the image file to store.
     * @return string|null The URL of the saved image or null if there was an error.
     */
    private function saveImage($imageRequest, $directory, $imageName)
    {
        try {
            $imagePath = $imageRequest->storeAs($directory, $imageName, 'public');
            return Storage::url($imagePath);
        } catch (Exception $exception) {
            Log::error("ProfileService::saveImage()", [$exception]);
            return null;
        }
    }

    /**
     * update password
     *
     * @param UpdateChangePasswordRequest $request
     * @param User $user
     * @return \App\Models\User|null
     */
    public function changePassword(UpdateChangePasswordRequest $request, $user): User|null
    {
        try {
            $user->password = Hash::make($request->password);
            $user->updated_by = Auth::user()->id;

            $user->save();

            return $user;
        } catch (Exception $exception) {
            Log::error("ProfileService::changePassword()", [$exception]);
            return null;
        }
    }
}
