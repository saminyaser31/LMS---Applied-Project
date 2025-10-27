<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTeacherProfileRequest;
use App\Models\Teacher\Teachers;
use App\Models\User;
use App\Services\Teacher\ProfileService;
use App\Traits\Auditable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    use Auditable;

    /**
     * @var ProfileService
     */
    public $profileService;

    public $layoutFolder = "teacher.profile";

    public $routePrefix = "";

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function setRoutePrefix()
    {
        if (isset(app('admin')->id)) {
            $this->routePrefix = "admin.teachers";
        }
        else if (isset(app('teacher')->id)) {
            $this->routePrefix = "teacher.profile";
        }
    }

    /**
     * Get page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $teacherId = null)
    {
        try {
            $userId = (Auth::user()->user_type == User::ADMIN) ? $teacherId : Auth::user()->id;
            $teacher = Teachers::where('user_id', $userId)->first();

            $data = [
                "teacher" => $teacher,
            ];

            return view("{$this->layoutFolder}.edit", $data);
        } catch (Exception $exception) {
            Log::error("ProfileController::index()", [$exception]);
        }
    }

    /**
     * Update in DB
     *
     * @param UpdateTeacherProfileRequest $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTeacherProfileRequest $request, int $id)
    {
        try {
            $this->setRoutePrefix();
            $teacher = Teachers::findOrFail($id);
            $teacherUpdate = $this->profileService->update($request, $teacher);
            if ($teacherUpdate) {
                $this->auditLogEntry("teacher:updated", $teacher->id, 'teacher-update', $teacherUpdate);
                return redirect()->route($this->routePrefix . '.index')->with('success', "Teacher Info updated successfully");
            }

            return redirect()->route($this->routePrefix . '.index')->with('error', "Something went wrong!");
        } catch (ModelNotFoundException $exception) {
            Log::error("ProfileController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("ProfileController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.index')->with('error', $exception->getMessage());
        }
    }
}
