<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStudentProfileRequest;
use App\Models\Student\Students;
use App\Models\User;
use App\Services\Student\ProfileService;
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

    public $layoutFolder = "student.profile";

    public $routePrefix = "";

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function setRoutePrefix()
    {
        if (isset(app('admin')->id)) {
            $this->routePrefix = "admin.teachers";
        } else if (isset(app('student')->id)) {
            $this->routePrefix = "student.profile";
        }
    }

    /**
     * Get page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $studentId = null)
    {
        try {
            $userId = (Auth::user()->user_type == User::ADMIN) ? $studentId : Auth::user()->id;
            $teacher = Students::where('user_id', $userId)->first();

            $data = [
                "student" => $teacher,
            ];

            return view("{$this->layoutFolder}.edit", $data);
        } catch (Exception $exception) {
            Log::error("ProfileController::index()", [$exception]);
        }
    }

    /**
     * Update in DB
     *
     * @param UpdateStudentProfileRequest $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateStudentProfileRequest $request, int $id)
    {
        try {
            $this->setRoutePrefix();
            $student = Students::findOrFail($id);
            $studentUpdate = $this->profileService->update($request, $student);
            if ($studentUpdate) {
                $this->auditLogEntry("student:updated", $student->id, 'student-update', $studentUpdate);
                return redirect()->route($this->routePrefix . '.index')->with('success', "Student Info updated successfully");
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
