<?php

namespace App\Services\Web;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TeacherService
{
    /**
     * filter teacher
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
            $paginate = $request->paginate ?? 8;

            $teachers = $query->orderBy($filterOption, $orderBy)->paginate($paginate);
            // dd($courses->toArray());

            return [
                "teachers" => $teachers,
                "totalTeachers" => $teachers->total(),
            ];
        } catch (Exception $exception) {
            Log::error("TeacherService::filter()", [$exception]);
            return [];
        }
    }

    /**
     * filter teacher by request params
     *
     * @param Request $request
     * @param $query
     * @return object
     */
    public function filterByRequest(Request $request, $query)
    {
        try {
            if ($request->filled('course_categories')) {
                $courseCategories = array_filter($request->course_categories, function($value) {
                    return !is_null($value) && $value !== ''; // Remove null or empty values
                });

                if (!empty($courseCategories)) {
                    $query->whereHas('courses', function ($query) use ($courseCategories) {
                        $query->whereIn('category_id', $courseCategories);
                    });
                }
            }

            if ($request->filled('course_subjects')) {
                $courseSubjects = array_filter($request->course_subjects, function($value) {
                    return !is_null($value) && $value !== ''; // Remove null or empty values
                });

                if (!empty($courseSubjects)) {
                    $query->whereHas('courses', function ($query) use ($courseSubjects) {
                        $query->whereIn('subject_id', $courseSubjects);
                    });
                }
            }

            if ($request->filled('course_levels')) {
                $courseLevels = array_filter($request->course_levels, function($value) {
                    return !is_null($value) && $value !== ''; // Remove null or empty values
                });

                if (!empty($courseLevels)) {
                    $query->whereHas('courses', function ($query) use ($courseLevels) {
                        $query->whereIn('level_id', $courseLevels);
                    });
                }
            }

            return $query;
        } catch (Exception $exception) {
            Log::error("TeacherService::filterByRequest()", [$exception]);
            return [];
        }
    }
}
