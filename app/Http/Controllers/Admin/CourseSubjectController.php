<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseSubjectRequest;
use App\Http\Requests\UpdateCourseSubjectRequest;
use App\Models\Course;
use App\Models\CourseSubject;
use App\Models\User;
use App\Services\Admin\CourseSubjectService;
use App\Traits\Auditable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CourseSubjectController extends Controller
{
    use Auditable;

    /**
     * @var CourseSubjectService
     */
    public $courseSubjectService;

    public $layoutFolder = "admin.course-subject";

    public $routePrefix = "";

    public function __construct(CourseSubjectService $courseSubjectService)
    {
        $this->courseSubjectService = $courseSubjectService;
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
     * Get subject page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            if (Gate::denies('view_course_subject')) {
                return redirect()->back()->with('error', 'You are not authorized to access this page.');
            }

            $this->setRoutePrefix();
            $courseSubjects = CourseSubject::query()
            ->with(['createdBy','updatedBy'])
            ->select('id','name','created_by','updated_by')
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->paginate(10);

            $data = [
                "courseSubjects" => $courseSubjects,
                "totalCourseSubjects" => $courseSubjects->total(),
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("CourseSubjectController::index()", [$exception]);
        }
    }

    /**
     * Get subject create page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            if (Gate::denies('create_course_subject')) {
                return redirect()->back()->with('error', 'You are not authorized to create course subject.');
            }

            $this->setRoutePrefix();
            $data = [];

            return view("{$this->layoutFolder}.create", $data);
        } catch (Exception $exception) {
            Log::error("CourseSubjectController::create()", [$exception]);
        }
    }

    /**
     * Store subject in DB
     *
     * @param StoreCourseSubjectRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCourseSubjectRequest $request)
    {
        try {
            $this->setRoutePrefix();
            $courseSubject = $this->courseSubjectService->store($request);

            if ($courseSubject) {
                $this->auditLogEntry("course-subject:created", $courseSubject->id, 'course-subject-create', $courseSubject);
                return redirect()->route($this->routePrefix . '.course-subject.index')->with('success', "Subject added successfully");
            }

            return redirect()->route($this->routePrefix . '.course-subject.index')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("CourseSubjectController::store()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-subject.index')->with('error', [$exception->getMessage()]);
        }
    }

    /**
     * Get subject edit page.
     *
     * @param integer $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            if (Gate::denies('edit_course_subject')) {
                return redirect()->back()->with('error', 'You are not authorized to edit course subject.');
            }

            $this->setRoutePrefix();
            $courseSubject = CourseSubject::findOrFail($id);

            $data = [
                "courseSubject" => $courseSubject,
            ];

            return view("{$this->layoutFolder}.edit", $data);
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseSubjectController::edit()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-subject.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseSubjectController::edit()", [$exception]);
        }
    }

    /**
     * Update subject in DB
     *
     * @param UpdateCourseSubjectRequest $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCourseSubjectRequest $request, int $id)
    {
        try {
            $this->setRoutePrefix();
            $courseSubject = CourseSubject::findOrFail($id);
            $courseSubjectUpdate = $this->courseSubjectService->update($request, $courseSubject);
            if ($courseSubjectUpdate) {
                $this->auditLogEntry("course-subject:updated", $courseSubject->id, 'course-subject-update', $courseSubjectUpdate);
                return redirect()->route($this->routePrefix . '.course-subject.index')->with('success', "Subject updated successfully");
            }

            return redirect()->route($this->routePrefix . '.course-subject.index')->with('error', "Something went wrong");
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseSubjectController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-subject.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseSubjectController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-subject.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Delete specific subject.
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN) {
                if (Gate::denies('delete_course_subject')) {
                    return redirect()->back()->with('error', 'You are not authorized to delete course subject.');
                }
            }

            $this->setRoutePrefix();
            $courseSubject = CourseSubject::findOrFail($id);
            $this->courseSubjectService->delete($courseSubject);

            return redirect()->route($this->routePrefix . '.course-subject.index')->with('success', "Subject deleted successfully");
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseSubjectController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-subject.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseSubjectController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-subject.index')->with('error', $exception->getMessage());
        }
    }
}
