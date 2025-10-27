<?php

namespace App\Http\Controllers\Web;

use Exception;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\TeacherContents;
use App\Models\Teacher\Teachers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Web\TeacherService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TeacherController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        try {
            $query = Teachers::query()
            ->select('id','user_id','first_name','last_name','email','phone_no','image')
            ->where('status', Teachers::STATUS_ACTIVE)
            ->whereHas('user', function ($query) {
                $query->where('approved', User::STATUS_APPROVED);
            });
            $result = (new TeacherService)->filter($request, $query);

            $courseCategories = DB::table('course_categories')
            ->whereNull('course_categories.deleted_at')
            ->leftJoin('courses', function ($join) {
                $join->on('course_categories.id', '=', 'courses.category_id')
                ->whereNull('courses.deleted_at')
                ->where('courses.status', Course::STATUS_ENABLE);
            })
            ->select('course_categories.id', 'course_categories.name', DB::raw('COUNT(courses.id) as courses_count'))
            ->groupBy('course_categories.id', 'course_categories.name')
            ->get();
            // dd($courseCategories);

            $courseSubjects = DB::table('course_subjects')
            ->whereNull('course_subjects.deleted_at')
            ->leftJoin('courses', function ($join) {
                $join->on('course_subjects.id', '=', 'courses.subject_id')
                ->whereNull('courses.deleted_at')
                ->where('courses.status', Course::STATUS_ENABLE);
            })
            ->select('course_subjects.id', 'course_subjects.name', DB::raw('COUNT(courses.id) as courses_count'))
            ->groupBy('course_subjects.id', 'course_subjects.name')
            ->get();

            $courseLevels = DB::table('course_levels')
            ->whereNull('course_levels.deleted_at')
            ->leftJoin('courses', function ($join) {
                $join->on('course_levels.id', '=', 'courses.level_id')
                ->whereNull('courses.deleted_at')
                ->where('courses.status', Course::STATUS_ENABLE);
            })
            ->select('course_levels.id', 'course_levels.name', DB::raw('COUNT(courses.id) as courses_count'))
            ->groupBy('course_levels.id', 'course_levels.name')
            ->get();

            $data = [
                "teachers" => isset($result['teachers']) ? $result['teachers'] : [],
                "totalTeachers" => isset($result['totalTeachers']) ? $result['totalTeachers'] : 0,
                "courseCategories" => $courseCategories,
                "courseSubjects" => $courseSubjects,
                "courseLevels" => $courseLevels,
            ];

            return view("web.teacher.instructors", $data);
        } catch (Exception $exception) {
            Log::error("TeacherController::index()", [$exception]);
            return redirect()->back()->with('error', "Something went wrong");
        }
    }

    /**
     * @param integer $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile(int $id)
    {
        try {
            $teacher = Teachers::where('user_id', $id)->first();

            $teacherCourses = Course::query()
            ->with('courseTeacher','courseCategory','courseStudents','courseRatings')
            ->select('id','card_image','title','total_class','short_description','teacher_id','price','discount_type','discount_amount','discount_start_date','discount_expiry_date')
            ->where("teacher_id", $id)
            ->where('courses.status', Course::STATUS_ENABLE)
            ->orderBy("id", "DESC")
            ->paginate(6);

            $contents = TeacherContents::query()
            ->where('status', TeacherContents::STATUS_ENABLE)
            ->orderBy('id', 'desc')
            ->limit(4)
            ->get();

            $data = [
                "teacher" => $teacher,
                "contents" => $contents,
                "teacherCourses" => $teacherCourses,
            ];

            return view('web.teacher.profile', $data);
        } catch (ModelNotFoundException $exception) {
            Log::error("TeacherController::profile()", [$exception]);
            return redirect()->back()->with('error', "Something went wrong");
        }  catch (Exception $exception) {
            Log::error("TeacherController::profile()", [$exception]);
            return redirect()->back()->with('error', "Something went wrong");
        }
    }

    /**
     * @param integer $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contents(int $id)
    {
        try {
            $contents = TeacherContents::query()
            ->where('teacher_id', $id)
            ->where('status', TeacherContents::STATUS_ENABLE)
            ->orderBy('id', 'desc')
            ->paginate(8);

            $data = [
                "contents" => $contents,
            ];

            return view("web.teacher.contents", $data);
        } catch (Exception $exception) {
            Log::error("TeacherController::contents()", [$exception]);
            return redirect()->back()->with('error', "Something went wrong");
        }
    }
}
