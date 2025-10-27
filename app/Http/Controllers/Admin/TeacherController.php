<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherRequest;
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

class TeacherController extends Controller
{
    use Auditable;

    /**
     * @var ProfileService
     */
    public $profileService;

    public $layoutFolder = "admin.teacher";

    public $routePrefix = "";

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function setRoutePrefix()
    {
        if (isset(app('admin')->id)) {
            $this->routePrefix = "admin";
        }
        else if (isset(app('teacher')->id)) {
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
            $this->setRoutePrefix();
            $query = Teachers::query()
            ->with(['user','createdBy'])
            ->select('id','first_name','last_name','email','phone_no','status','user_id')
            ->whereNull('deleted_at');

            $result = (new ProfileService)->filter($request, $query);

            $data = [
                "teachers" => isset($result['teachers']) ? $result['teachers'] : [],
                "totalTeachers" => isset($result['totalTeachers']) ? $result['totalTeachers'] : 0,
                "userStatus" => User::STATUS_SELECT,
                "teacherStatus" => Teachers::STATUS_SELECT,
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("TeacherController::index()", [$exception]);
        }
    }

    /**
     * Get specific page.
     *
     * @param integer $id
     * @return \Illuminate\Http\View|\Illuminate\Http\RedirectResponse
     */
    public function show(int $id)
    {
        try {
            abort_if(Gate::denies('show_teacher'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $this->setRoutePrefix();

            $teacher = Teachers::findOrFail($id);

            $data = [
                "teacher" => $teacher,
            ];

            return view("teacher.profile.show", $data);
        } catch (ModelNotFoundException $e) {
            Log::error("TeacherController::show()", [$e]);
            return redirect()->route($this->routePrefix . '.teachers.index')->with('error', $e->getMessage());
        } catch (Exception $exception) {
            Log::error("TeacherController::show()", [$exception]);
        }
    }

    /**
     * Get create page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            abort_if(Gate::denies('create_teacher'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $this->setRoutePrefix();

            $data = [];

            return view("teacher.profile.create", $data);
        } catch (Exception $exception) {
            Log::error("TeacherController::create()", [$exception]);
        }
    }

    /**
     * Store in DB
     *
     * @param StoreTeacherRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTeacherRequest $request)
    {
        try {
            $this->setRoutePrefix();
            // dd($request);
            $teacher = $this->profileService->store($request);

            if ($teacher) {
                $this->auditLogEntry("teacher:created", $teacher->id, 'teacher-create', $teacher);
                return redirect()->route($this->routePrefix . '.teachers.index')->with('success', "Teacher added successfully & A verificatio mail has been sent to the teacher.");
            }

            return redirect()->route($this->routePrefix . '.teachers.index')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("TeacherController::store()", [$exception]);
            return redirect()->route($this->routePrefix . '.teachers.index')->with('error', [$exception->getMessage()]);
        }
    }

    /**
     * Get edit page.
     *
     * @param integer $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            abort_if(Gate::denies('edit_teacher'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $this->setRoutePrefix();

            $teacher = Teachers::findOrFail($id);

            $data = [
                "teacher" => $teacher,
            ];

            return view("teacher.profile.edit", $data);
        } catch (ModelNotFoundException $exception) {
            Log::error("TeacherController::edit()", [$exception]);
            return redirect()->route($this->routePrefix . '.teachers.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("TeacherController::edit()", [$exception]);
        }
    }

    /**
     * Delete
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id)
    {
        try {
            abort_if(Gate::denies('delete_teacher'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $this->setRoutePrefix();

            $teacher = Teachers::findOrFail($id);
            $this->profileService->delete($teacher);

            return redirect()->route($this->routePrefix . '.teachers.index')->with('success', "Teacher deleted successfully");
        } catch (ModelNotFoundException $exception) {
            Log::error("TeacherController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.teachers.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("TeacherController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.teachers.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Get page.
     *
     * @param integer $id
     * @return \Illuminate\View\View
     */
    public function changePassword(int $id)
    {
        try {
            abort_if(Gate::denies('access_change_password'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $this->setRoutePrefix();

            $user = User::findOrFail($id);

            $data = [
                "teacher" => $user,
            ];

            return view("teacher.change-password.index", $data);
        } catch (ModelNotFoundException $exception) {
            Log::error("TeacherController::changePassword()", [$exception]);
            return redirect()->route($this->routePrefix . '.teachers.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("TeacherController::changePassword()", [$exception]);
            return redirect()->route($this->routePrefix . '.teachers.index')->with('error', $exception->getMessage());
        }
    }
}
