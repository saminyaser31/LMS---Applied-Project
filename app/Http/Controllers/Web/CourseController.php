<?php

namespace App\Http\Controllers\Web;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseContents;
use App\Models\Teacher\Teachers;
use App\Services\Web\CourseService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public $layoutFolder = "web";

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        try {
            $now = Carbon::now()->format('Y-m-d H:i:s');

            $query = Course::query()
            ->with('courseTeacher','courseCategory',
            'courseStudents','courseRatings')
            ->select('id','card_image','title','total_class','short_description','teacher_id','price','discount_type','discount_amount','discount_start_date','discount_expiry_date')
            // ->selectRaw("
            //     IF(
            //         discount_type IS NOT NULL
            //         AND discount_amount IS NOT NULL
            //         AND discount_start_date IS NOT NULL
            //         AND discount_expiry_date IS NOT NULL
            //         AND STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s') > discount_start_date
            //         AND STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s') < discount_expiry_date,
            //         CASE
            //             WHEN discount_type = 1 THEN GREATEST(0, price - discount_amount)
            //             WHEN discount_type = 2 THEN GREATEST(0, price - (price * discount_amount / 100))
            //             ELSE NULL
            //         END,
            //         NULL
            //     ) AS discounted_price
            //     ", [$now, $now]
            // )
            ->where('status', Course::STATUS_ENABLE)
            ->whereNull('courses.deleted_at');
            $result = (new CourseService)->filter($request, $query);

            $courseCategory = Helper::getAllCourseCategory();

            // $teachers = Course::join('teachers', 'courses.teacher_id', '=', 'teachers.user_id')
            // ->select('courses.teacher_id', 'teachers.first_name', 'teachers.last_name')
            // ->distinct()
            // ->get()
            // ->map(function($teacher) {
            //     return [
            //         'teacher_id' => $teacher->teacher_id,
            //         'teacher_name' => $teacher->first_name . ' ' . $teacher->last_name
            //     ];
            // });

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

            $teachers = DB::table('teachers')
            ->whereNull('teachers.deleted_at')
            ->leftJoin('courses', function ($join) {
                $join->on('teachers.user_id', '=', 'courses.teacher_id')
                ->whereNull('courses.deleted_at')
                ->where('courses.status', Course::STATUS_ENABLE);
            })
            ->select('teachers.id', 'teachers.user_id', 'teachers.first_name', 'teachers.last_name', DB::raw('COUNT(courses.id) as courses_count'))
            ->groupBy('teachers.id', 'teachers.user_id', 'teachers.first_name', 'teachers.last_name')
            ->get();
            // dd($teachers);

            $ratings = DB::table('ratings')
            ->leftJoin('course_ratings', 'ratings.star', '=', 'course_ratings.rating')
            ->select('ratings.id', 'ratings.star', DB::raw('COUNT(course_ratings.id) as ratings_count'))
            ->groupBy('ratings.id', 'ratings.star')
            ->get();
            // dd($ratings);

            $data = [
                "courses" => isset($result['courses']) ? $result['courses'] : [],
                "totalCourses" => isset($result['totalCourses']) ? $result['totalCourses'] : 0,
                "courseCategories" => $courseCategories,
                "courseSubjects" => $courseSubjects,
                "courseLevels" => $courseLevels,
                "teachers" => $teachers,
                "ratings" => $ratings,
            ];

            return view("{$this->layoutFolder}.courses", $data);
        } catch (Exception $exception) {
            Log::error("CourseController::index()", [$exception]);
        }
    }

    /**
     * @param string $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function courseDetails(int $id)
    {
        try {
            $course = Course::query()
            ->with('courseTeacher','courseCategory',
            'courseStudents','courseRatings')
            ->select('courses.*')
            ->whereNull('courses.deleted_at')
            ->findOrFail($id);

            $courseContents = CourseContents::select('title', 'description')
            ->where('course_id', $id)
            ->whereNull('deleted_at')
            ->get();

            $teacher = Teachers::with(['courses:id,teacher_id'])
            ->select('teachers.id','teachers.user_id','bio')
            ->where("user_id", $course->teacher_id)
            ->whereNull('deleted_at')
            ->first();

            $teacherCourses = Course::query()
            ->with('courseTeacher','courseCategory','courseStudents','courseRatings')
            ->select('id','card_image','title','total_class','short_description','teacher_id','price','discount_type','discount_amount','discount_start_date','discount_expiry_date')
            ->where("teacher_id", $course->teacher_id)
            ->whereNot("id", $id)
            ->whereNull('courses.deleted_at')
            ->where('courses.status', Course::STATUS_ENABLE)
            ->orderBy("id", "DESC")
            ->limit(2)
            ->get();

            $relatedCourses = Course::query()
            ->with('courseTeacher','courseCategory','courseStudents','courseRatings')
            ->select('id','card_image','title','total_class','short_description','teacher_id','price','discount_type','discount_amount','discount_start_date','discount_expiry_date')
            ->where("category_id", $course->category_id)
            ->whereNot("id", $id)
            ->whereNull('courses.deleted_at')
            ->where('courses.status', Course::STATUS_ENABLE)
            ->orderBy("id", "DESC")
            ->limit(3)
            ->get();

            $data = [
                "course" => $course,
                "courseContents" => $courseContents,
                "teacher" => $teacher,
                "teacherCourses" => $teacherCourses,
                "relatedCourses" => $relatedCourses,
            ];

            return view('web.course-details', $data);
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseController::courseDetails()", [$exception]);
            return redirect()->route('courses')->with('error', $exception->getMessage());
        }  catch (Exception $exception) {
            Log::error("CourseController::courseDetails()", [$exception]);
        }
    }
}
