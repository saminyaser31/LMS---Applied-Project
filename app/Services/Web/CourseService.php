<?php

namespace App\Services\Web;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseService
{
    /**
     * filter course
     *
     * @param Request $request
     * @param $query
     * @return array
     */
    public function filter(Request $request, $query)
    {
        try {
            $query = $this->filterByRequest($request, $query);

            $orderBy = $request->order_by ?? 'DESC';
            $filterOption = $request->filter_option ?? 'id';
            $paginate = $request->paginate ?? 10;

            $courses = $query->orderBy($filterOption, $orderBy)->paginate($paginate);
            // dd($courses->toArray());

            return [
                "courses" => $courses,
                "totalCourses" => $courses->total(),
            ];
        } catch (Exception $exception) {
            Log::error("CourseService::filter()", [$exception]);
            return [];
        }
    }

    /**
     * filter course by request params
     *
     * @param Request $request
     * @param $query
     * @return object
     */
    public function filterByRequest(Request $request, $query)
    {
        try {
            if ($request->filled('course_title')) {
                $query->where('title', 'LIKE', '%' . $request->course_title . '%');
            }

            if ($request->filled('course_categories')) {
                $courseCategories = array_filter($request->course_categories, function($value) {
                    return !is_null($value) && $value !== ''; // Remove null or empty values
                });

                if (!empty($courseCategories)) {
                    $query->whereIn('category_id', $courseCategories);
                }
            }

            if ($request->filled('course_subjects')) {
                $courseSubjects = array_filter($request->course_subjects, function($value) {
                    return !is_null($value) && $value !== ''; // Remove null or empty values
                });

                if (!empty($courseSubjects)) {
                    $query->whereIn('subject_id', $courseSubjects);
                }
            }

            if ($request->filled('course_levels')) {
                $courseLevels = array_filter($request->course_levels, function($value) {
                    return !is_null($value) && $value !== ''; // Remove null or empty values
                });

                if (!empty($courseLevels)) {
                    $query->whereIn('level_id', $courseLevels);
                }
            }

            if ($request->filled('course_instructors')) {
                $query->whereIn('teacher_id', $request->course_instructors);
            }

            // if ($request->filled('course_rating')) {
            //     $query->where('title', 'LIKE', '%' . $request->course_rating . '%');
            // }

            return $query;
        } catch (Exception $exception) {
            Log::error("CourseService::filterByRequest()", [$exception]);
            return [];
        }
    }
}
