<?php

namespace App\Http\Controllers\Teacher;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseContentRequest;
use App\Http\Requests\UpdateCourseContentRequest;
use App\Models\Course;
use App\Models\CourseContents;
use App\Models\Teacher\Teachers;
use App\Models\User;
use App\Services\Teacher\CourseContentService;
use App\Traits\Auditable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CourseContentController extends Controller
{
    use Auditable;

    /**
     * @var CourseContentService
     */
    public $courseContentService;

    public $layoutFolder = "teacher.courses.contents";

    public $routePrefix = "";

    public function __construct(CourseContentService $courseContentService)
    {
        $this->courseContentService = $courseContentService;
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
     * Get contents page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN && Gate::denies('view_course_content')) {
                return redirect()->back()->with('error', 'You are not authorized to access this page.');
            }

            $this->setRoutePrefix();
            $query = CourseContents::query()
            ->with('course')
            ->whereNull('deleted_at');

            $result = (new CourseContentService)->filter($request, $query);

            $courseCategory = Helper::getAllCourseCategory();
            $teachers = Teachers::select('id','first_name','last_name','user_id')->get();

            $courses = Course::query()
            ->with('courseTeacher')
            ->select('id','title','teacher_id')
            ->where(function ($query) {
                if (Auth::user()->user_type == User::TEACHER) {
                    $query->where('teacher_id', Auth::user()->id);
                }
            })
            ->whereNull('courses.deleted_at')
            ->where('courses.status', Course::STATUS_ENABLE)
            ->get();

            $data = [
                "courses" => $courses,
                "courseContents" => isset($result['courseContents']) ? $result['courseContents'] : [],
                "totalCourseContents" => isset($result['totalCourseContents']) ? $result['totalCourseContents'] : 0,
                "courseCategory" => $courseCategory,
                "contentStatus" => CourseContents::STATUS_SELECT,
                "teachers" => $teachers,
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("CourseContentController::index()", [$exception]);
        }
    }

    /**
     * Get content create page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN && Gate::denies('create_course_content')) {
                return redirect()->back()->with('error', 'You are not authorized to create course content.');
            }

            $this->setRoutePrefix();
            $courses = Course::query()
            ->with('courseTeacher')
            ->select('id','title','teacher_id')
            ->where(function ($query) {
                if (Auth::user()->user_type == User::TEACHER) {
                    $query->where('teacher_id', Auth::user()->id);
                }
            })
            ->whereNull('courses.deleted_at')
            ->where('courses.status', Course::STATUS_ENABLE)
            ->get();

            $data = [
                "courses" => $courses,
            ];

            return view("{$this->layoutFolder}.create", $data);
        } catch (Exception $exception) {
            Log::error("CourseContentController::create()", [$exception]);
        }
    }

    /**
     * Store content in DB
     *
     * @param StoreCourseContentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCourseContentRequest $request)
    {
        try {
            $this->setRoutePrefix();
            $courseContent = $this->courseContentService->store($request);

            if ($courseContent) {
                $this->auditLogEntry("course-content:created", $courseContent->id, 'course-content-create', $courseContent);
                return redirect()->route($this->routePrefix . '.course-contents.index')->with('success', "Content added successfully");
            }

            return redirect()->route($this->routePrefix . '.course-contents.index')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("CourseContentController::store()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-contents.index')->with('error', [$exception->getMessage()]);
        }
    }

    /**
     * Get content edit page.
     *
     * @param integer $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN && Gate::denies('edit_course_content')) {
                return redirect()->back()->with('error', 'You are not authorized to edit course content.');
            }

            $this->setRoutePrefix();
            $courseContent = CourseContents::findOrFail($id);
            $courses = Course::query()
            ->with('courseTeacher')
            ->select('id','title','teacher_id')
            ->where(function ($query) {
                if (Auth::user()->user_type == User::TEACHER) {
                    $query->where('teacher_id', Auth::user()->id);
                }
            })
            ->whereNull('courses.deleted_at')
            ->where('courses.status', Course::STATUS_ENABLE)
            ->get();

            $data = [
                "courseContent" => $courseContent,
                "courses" => $courses,
                "contentStatus" => CourseContents::STATUS_SELECT,
            ];

            return view("{$this->layoutFolder}.edit", $data);
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseContentController::edit()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-contents.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseContentController::edit()", [$exception]);
        }
    }

    /**
     * Update content in DB
     *
     * @param UpdateCourseContentRequest $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCourseContentRequest $request, int $id)
    {
        try {
            $this->setRoutePrefix();
            $courseContent = CourseContents::findOrFail($id);
            $courseContentUpdate = $this->courseContentService->update($request, $courseContent);
            if ($courseContentUpdate) {
                $this->auditLogEntry("course-content:updated", $courseContent->id, 'course-content-update', $courseContentUpdate);
                return redirect()->route($this->routePrefix . '.course-contents.index')->with('success', "Content updated successfully");
            }

            return redirect()->route($this->routePrefix . '.course-contents.index')->with('error', "Something went wrong");
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseContentController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-contents.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseContentController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-contents.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Delete specific content.
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN && Gate::denies('delete_course_content')) {
                return redirect()->back()->with('error', 'You are not authorized to delete course content.');
            }

            $this->setRoutePrefix();
            $courseContent = CourseContents::findOrFail($id);
            $this->courseContentService->delete($courseContent);

            return redirect()->route($this->routePrefix . '.course-contents.index')->with('success', "Content deleted successfully");
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseContentController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-contents.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseContentController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-contents.index')->with('error', $exception->getMessage());
        }
    }
}
