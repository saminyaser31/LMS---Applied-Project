<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTeacherProfileRequest;
use App\Models\Teacher\Teachers;
use App\Models\User;
use App\Services\Teacher\ProfileService;
use App\Traits\Auditable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TeacherApprovalController extends Controller
{
    use Auditable;

    /**
     * @var ProfileService
     */
    public $profileService;

    public $layoutFolder = "admin.teacher.approve-requests";

    public $routePrefix = "";

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;

        if (isset(app('admin')->id)) {
            $this->routePrefix = "admin";
        } else if (isset(app('teacher')->id)) {
            $this->routePrefix = "teacher";
        }
    }

    /**
     * Get page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $query = Teachers::with(['user'])
            ->whereHas('user', function ($query) {
                $query->where('approved', User::STATUS_PENDING);
            })
            ->whereNull('deleted_at');

            $query = $this->profileService->filterByRequest($request, $query);

            $teachers = $query->orderBy('id','desc')->paginate(10);

            $data = [
                "teachers" => $teachers,
                "totalRequests" => $teachers->total(),
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("TeacherApprovalController::index()", [$exception]);
            return redirect()->route('admin.teachers.approve-requests.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Update in DB
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id)
    {
        try {
            if (Gate::denies('approve_new_teacher_requests')) {
                return redirect()->back()->with('error', 'You are not authorized to access.');
            }

            $teacherUser = User::findOrFail($id);
            $teacherUpdate = $this->profileService->approveTeacherUser($teacherUser);
            if ($teacherUpdate) {
                $this->auditLogEntry("teacher-approve-request:updated", $teacherUser->id, 'teacher-approve-request-update', $teacherUpdate);
                return redirect()->route('admin.teachers.approve-requests.index')->with('success', "Teacher approved successfully");
            }

            return redirect()->route('admin.teachers.approve-requests.index')->with('error', "Something went wrong");
        } catch (ModelNotFoundException $exception) {
            Log::error("TeacherApprovalController::update()", [$exception]);
            return redirect()->route('admin.teachers.approve-requests.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("TeacherApprovalController::update()", [$exception]);
            return redirect()->route('admin.teachers.approve-requests.index')->with('error', $exception->getMessage());
        }
    }
}
