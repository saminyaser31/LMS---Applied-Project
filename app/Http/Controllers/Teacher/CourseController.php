<?php

namespace App\Http\Controllers\Teacher;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseContents;
use App\Models\Teacher\Teachers;
use App\Models\User;
use App\Services\Teacher\CourseService;
use App\Traits\Auditable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    use Auditable;

    /**
     * @var CourseService
     */
    public $courseService;

    public $layoutFolder = "teacher.courses";

    public $routePrefix = "";

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
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
     * Get course page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN && Gate::denies('access_course')) {
                return redirect()->back()->with('error', 'You are not authorized to access this page.');
            }

            $this->setRoutePrefix();
            $query = Course::query()
            ->with('courseTeacher','courseCategory','createdBy','courseSubject','courseLevel')
            ->select('id','price','title','category_id','teacher_id','course_start_date','status','created_by')
            ->where(function ($query) {
                if (Auth::user()->user_type == User::TEACHER) {
                    $query->where('teacher_id', Auth::user()->id);
                }
            })
            ->whereNull('courses.deleted_at');

            $result = (new CourseService)->filter($request, $query);

            $courseCategory = Helper::getAllCourseCategory();
            $courseSubject = Helper::getAllCourseSubject();
            $courseLevel = Helper::getAllCourseLevel();
            $teachers = Teachers::select('id','first_name','last_name','user_id')->get();

            $data = [
                "courses" => isset($result['courses']) ? $result['courses'] : [],
                "totalCourses" => isset($result['totalCourses']) ? $result['totalCourses'] : 0,
                "courseCategory" => $courseCategory,
                "courseSubject" => $courseSubject,
                "courseLevel" => $courseLevel,
                "courseStatus" => Course::STATUS_SELECT,
                "teachers" => $teachers,
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("CourseController::index()", [$exception]);
        }
    }

    /**
     * Get specific course page.
     *
     * @param integer $id
     * @return \Illuminate\Http\View|\Illuminate\Http\RedirectResponse
     */
    public function show(int $id)
    {
        try {
            $this->setRoutePrefix();
            $course = Course::findOrFail($id);
            $courseContents = CourseContents::where('course_id', $id)->get();
            $contentTitles = $courseContents->pluck('title')->toArray();
            $contentDescriptions = $courseContents->pluck('description')->toArray();
            $teachers = Teachers::select('id','first_name','last_name','user_id')->get();

            $data = [
                "course" => $course,
                "contentTitles" => $contentTitles,
                "contentDescriptions" => $contentDescriptions,
                "courseCategory" => Helper::getAllCourseCategory(),
                "courseSubject" => Helper::getAllCourseSubject(),
                "courseLevel" => Helper::getAllCourseLevel(),
                "courseStatus" => Course::STATUS_SELECT,
                "teachers" => $teachers,
            ];

            return view("{$this->layoutFolder}.show", $data);
        } catch (ModelNotFoundException $e) {
            Log::error("CourseController::show()", [$e]);
            return redirect()->route($this->routePrefix . '.courses.index')->with('error', $e->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseController::show()", [$exception]);
        }
    }

    /**
     * Get course create page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN) {
                if (Gate::denies('create_course')) {
                    return redirect()->back()->with('error', 'You are not authorized to create course.');
                }
            }

            $this->setRoutePrefix();
            $courseCategory = Helper::getAllCourseCategory();
            $courseSubject = Helper::getAllCourseSubject();
            $courseLevel = Helper::getAllCourseLevel();
            $teachers = Teachers::select('id','first_name','last_name','user_id')->get();
            $data = [
                "courseCategory" => $courseCategory,
                "courseSubject" => $courseSubject,
                "courseLevel" => $courseLevel,
                "teachers" => $teachers,
            ];

            return view("{$this->layoutFolder}.create", $data);
        } catch (Exception $exception) {
            Log::error("CourseController::create()", [$exception]);
        }
    }

    /**
     * Store course in DB
     *
     * @param StoreCourseRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCourseRequest $request)
    {
        try {
            $this->setRoutePrefix();
            // dd($request);
            $course = $this->courseService->store($request);

            if ($course) {
                $this->auditLogEntry("course:created", $course->id, 'course-create', $course);
                return redirect()->route($this->routePrefix . '.courses.index')->with('success', "Course added successfully");
            }

            return redirect()->route($this->routePrefix . '.courses.index')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("CourseController::store()", [$exception]);
            return redirect()->route($this->routePrefix . '.courses.index')->with('error', [$exception->getMessage()]);
        }
    }

    /**
     * Get course edit page.
     *
     * @param integer $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN) {
                if (Gate::denies('edit_course')) {
                    return redirect()->back()->with('error', 'You are not authorized to edit course.');
                }
            }

            $this->setRoutePrefix();
            $course = Course::findOrFail($id);
            $courseContents = CourseContents::where('course_id', $id)->get();
            $contentNumbers = $courseContents->pluck('content_no')->toArray();
            $contentTitles = $courseContents->pluck('title')->toArray();
            $contentDescriptions = $courseContents->pluck('description')->toArray();
            $teachers = Teachers::select('id','first_name','last_name','user_id')->get();

            $data = [
                "course" => $course,
                "contentNumbers" => $contentNumbers,
                "contentTitles" => $contentTitles,
                "contentDescriptions" => $contentDescriptions,
                "courseCategory" => Helper::getAllCourseCategory(),
                "courseSubject" => Helper::getAllCourseSubject(),
                "courseLevel" => Helper::getAllCourseLevel(),
                "courseStatus" => Course::STATUS_SELECT,
                "teachers" => $teachers,
            ];

            return view("{$this->layoutFolder}.edit", $data);
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseController::edit()", [$exception]);
            return redirect()->route($this->routePrefix . '.courses.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseController::edit()", [$exception]);
        }
    }

    /**
     * Update course in DB
     *
     * @param UpdateCourseRequest $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCourseRequest $request, int $id)
    {
        try {
            $this->setRoutePrefix();
            $course = Course::findOrFail($id);
            $courseUpdate = $this->courseService->update($request, $course);
            if ($courseUpdate) {
                $this->auditLogEntry("course:updated", $course->id, 'course-update', $courseUpdate);
                return redirect()->route($this->routePrefix . '.courses.index')->with('success', "Course updated successfully");
            }

            return redirect()->route($this->routePrefix . '.courses.index')->with('error', "Something went wrong");
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.courses.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.courses.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Delete specific course.
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN) {
                if (Gate::denies('delete_course')) {
                    return redirect()->back()->with('error', 'You are not authorized to delete course.');
                }
            }

            $this->setRoutePrefix();
            $course = Course::findOrFail($id);
            $this->courseService->delete($course);

            return redirect()->route($this->routePrefix . '.courses.index')->with('success', "Course deleted successfully");
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.courses.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.courses.index')->with('error', $exception->getMessage());
        }
    }
}
