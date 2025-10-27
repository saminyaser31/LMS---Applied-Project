<?php

namespace App\Http\Controllers\Student;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseContents;
use App\Models\CourseEnrollment;
use App\Models\CourseMaterials;
use App\Models\User;
use App\Services\Student\CourseService;
use App\Traits\Auditable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class CourseController extends Controller
{
    use Auditable;

    /**
     * @var CourseService
     */
    public $courseService;

    public $layoutFolder = "student.courses";

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
        else if (isset(app('student')->id)) {
            $this->routePrefix = "student";
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
            $this->setRoutePrefix();
            $query = CourseEnrollment::query()
            ->with('courses','order','courses.courseTeacher')
            ->where('status', CourseEnrollment::STATUS_ENABLE)
            ->whereHas('order', function ($query) {
                if (Auth::user()->user_type == User::STUDENT) {
                    $query->where('student_id', Auth::user()->id)
                    ->where('status', Order::STATUS_ENABLE);
                }
            });

            $result = (new CourseService)->filter($request, $query);

            $courseCategory = Helper::getAllCourseCategory();
            // dd($result);

            $data = [
                "enrolledCourses" => isset($result['enrolledCourses']) ? $result['enrolledCourses'] : [],
                "totalCourses" => isset($result['totalEnrolledCourses']) ? $result['totalEnrolledCourses'] : 0,
                "courseCategory" => $courseCategory,
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("CourseController::index()", [$exception]);
        }
    }

    /**
     * Get details
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
            $completedClass = $courseContents->where('status', CourseContents::STATUS_COMPLETE); // Filter by status
            $completedClassCount = $completedClass->count();
            $incompleteClass = $courseContents->where('status', CourseContents::STATUS_INCOMPLETE); // Filter by status
            $incompleteClassCount = $incompleteClass->count();
            $courseMaterials = CourseMaterials::where('course_id', $id)->get();

            $data = [
                "enrolledCourse" => $course,
                "courseContents" => $courseContents,
                "completedClass" => $completedClass,
                "completedClassCount" => $completedClassCount,
                "incompleteClass" => $incompleteClass,
                "incompleteClassCount" => $incompleteClassCount,
                "courseMaterials" => $courseMaterials,
            ];

            return view("{$this->layoutFolder}.show", $data);
        } catch (ModelNotFoundException $e) {
            Log::error("CourseController::show()", [$e]);
            return redirect()->route($this->routePrefix . '.courses.index')->with('error', $e->getMessage());
        } catch (Exception $exception) {
            Log::error("courseDetails::show()", [$exception]);
        }
    }
}
