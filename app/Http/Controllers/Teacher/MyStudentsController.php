<?php

namespace App\Http\Controllers\Teacher;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Teacher\Teachers;
use App\Models\User;
use App\Services\Teacher\MyStudentsService;
use App\Traits\Auditable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MyStudentsController extends Controller
{
    use Auditable;

    /**
     * @var MyStudentsService
     */
    public $myStudentsService;

    public $layoutFolder = "teacher.my-students";

    public $routePrefix = "";

    public function __construct(MyStudentsService $myStudentsService)
    {
        $this->myStudentsService = $myStudentsService;
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
     * Get materials page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $this->setRoutePrefix();
            $query = CourseEnrollment::query()
            ->with(['courses:id,teacher_id,category_id,title','teacher:id,user_id,first_name,last_name,email,phone_no','student'])
            ->whereNull('deleted_at');

            $result = (new MyStudentsService)->filter($request, $query);
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
            ->get();

            $data = [
                "courses" => $courses,
                "students" => isset($result['students']) ? $result['students'] : [],
                "totalStudents" => isset($result['totalStudents']) ? $result['totalStudents'] : 0,
                "teachers" => $teachers,
                "courseCategory" => Helper::getAllCourseCategory(),
            ];
            // dd($data["students"][0]);

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("MyStudentsController::index()", [$exception]);
        }
    }
}
