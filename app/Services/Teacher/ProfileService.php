<?php

namespace App\Services\Teacher;

use App\Constants\AppConstants;
use App\Http\Controllers\Web\TeacherAuthController;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateChangePasswordRequest;
use App\Http\Requests\UpdateTeacherProfileRequest;
use App\Mail\TeacherIsApproved;
use App\Models\Course;
use App\Models\Teacher\Teachers;
use App\Models\User;
use App\Traits\Auditable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    use Auditable;
    private $user = null;
    private string $token = '';

    /**
     * filter
     *
     * @param Request $request
     * @param $query
     * @return array
     */
    public function filter(Request $request, $query)
    {
        try {
            $query = $this->filterByRequest($request, $query);

            $orderBy = $request->order_by ?? 'DESC';
            $filterOption = $request->filter_option ?? 'id';
            $paginate = $request->paginate ?? 10;

            $teachers = $query->orderBy($filterOption, $orderBy)->paginate($paginate);

            return [
                "teachers" => $teachers,
                "totalTeachers" => $teachers->total(),
            ];
        } catch (Exception $exception) {
            Log::error("ProfileService::filter()", [$exception]);
            return [];
        }
    }

    /**
     * filter by request params
     *
     * @param Request $request
     * @param $query
     * @return object
     */
    public function filterByRequest(Request $request, $query)
    {
        try {
            if ($request->filled('email')) {
                $query->where('email', $request->email);
            }

            if ($request->filled('phone_no')) {
                $query->where('phone_no', $request->phone_no);
            }

            // Filter by status
            if ($request->filled('teacher_status')) {
                $query->where('status', $request->teacher_status);
            }

            if ($request->filled('approved')) {
                $query->where(function ($query) use ($request) {
                    $query->whereHas('user', function ($subQuery) use ($request) {
                        $subQuery->where('approved', $request->approved);
                    });
                });
            }

            return $query;
        } catch (Exception $exception) {
            Log::error("ProfileService::filterByRequest()", [$exception]);
            return [];
        }
    }

    /**
     * store
     *
     * @param StoreTeacherRequest $request
     * @return \App\Models\Teacher\Teachers|null
     */
    public function store(StoreTeacherRequest $request): Teachers|null
    {
        try {
            $data = $request->all();
            $teacher = (new TeacherAuthController())->createUser($request);

            if ($teacher) {
                (new TeacherAuthController())->sendMail($data, $request);
            }

            return $teacher;
        } catch (Exception $exception) {
            Log::error("ProfileService::store()", [$exception]);
            return null;
        }
    }

    /**
     * update teacher profile
     *
     * @param UpdateTeacherProfileRequest $request
     * @param Teachers $teacher
     * @return \App\Models\Teacher\Teachers|null
     */
    public function update(UpdateTeacherProfileRequest $request, $teacher): Teachers|null
    {
        try {
            // dd($request->all());
            DB::beginTransaction();

            $teacher->first_name = $request->first_name;
            $teacher->last_name = $request->last_name;
            $teacher->phone_no = $request->phone_no;
            $teacher->dob = $request->dob;
            $teacher->marital_status = $request->marital_status;
            $teacher->religion = $request->religion;
            $teacher->nid_no = $request->nid_no;
            $teacher->address = $request->address;
            $teacher->experience = $request->experience;
            $teacher->bio = $request->bio;
            $teacher->detailed_info = $request->detailed_info;
            $teacher->updated_by = Auth::user()->id;

            // Define base directory for teacher images
            $teacherDir = 'teacher/' . $teacher->user_id;

            // Ensure the directory exists
            Storage::makeDirectory($teacherDir);

            // Save the profile image
            if ($request->hasFile('image')) {
                $teacher->image = $this->saveImage($request->file('image'), $teacherDir, 'profile-picture.' . $request->file('image')->getClientOriginalExtension());
            }

            // Save the cover image
            if ($request->hasFile('cover_image')) {
                $teacher->cover_image = $this->saveImage($request->file('cover_image'), $teacherDir, 'cover-picture.' . $request->file('cover_image')->getClientOriginalExtension());
            }

            // Save the NID front image
            if ($request->hasFile('nid_front_image')) {
                $teacher->nid_front_image = $this->saveImage($request->file('nid_front_image'), $teacherDir, 'nid-front.' . $request->file('nid_front_image')->getClientOriginalExtension());
            }

            // Save the NID back image
            if ($request->hasFile('nid_back_image')) {
                $teacher->nid_back_image = $this->saveImage($request->file('nid_back_image'), $teacherDir, 'nid-back.' . $request->file('nid_back_image')->getClientOriginalExtension());
            }

            $teacher->save();


            DB::commit();

            return $teacher;
        } catch (Exception $exception) {
            Log::error("ProfileService::update()", [$exception]);
            DB::rollback();
            return null;
        }
    }

    /**
     * delete specific entry
     *
     * @param Teachers $teacher
     * @return void
     */
    public function delete(Teachers $teacher)
    {
        try {
            DB::beginTransaction();

            $userId = $teacher->user_id;
            $teacher->deleted_by = Auth::user()->id;
            $teacher->save();

            User::where('id', $userId)->delete();
            Course::where('teacher_id', $userId)->delete();
            $teacher->delete();
            DB::commit();

            // Clear the relevant cache

            $this->auditLogEntry("teacher:deleted", $teacher->id, 'course-deleted', $teacher);
        } catch (Exception $exception) {
            Log::error("ProfileService::delete()", [$exception]);
            DB::rollback();
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

    /**
     * approve teacher
     *
     * @param User $teacherUser
     * @return \App\Models\User|null
     */
    public function approveTeacherUser(User $teacherUser)
    {
        try {
            $teacherUser->approved = User::STATUS_APPROVED;
            $teacherUser->save();

            // $mailData = [
            //     'name' => $teacherUser->name ?? '',
            //     'email' => AppConstants::HELP_MAIL,
            // ];

            // Mail::to($teacherUser->email)->send(new TeacherIsApproved($mailData));

            return $teacherUser;
        } catch (Exception $exception) {
            Log::error("ProfileService::approveTeacherUser()", [$exception]);
            return null;
        }
    }
}
