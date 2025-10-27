<?php

namespace App\Services\Teacher;

use Exception;
use App\Traits\Auditable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MyStudentsService
{
    use Auditable;

    /**
     * filter
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

            $students = $query->orderBy($filterOption, $orderBy)->paginate($paginate);

            return [
                "students" => $students,
                "totalStudents" => $students->total(),
            ];
        } catch (Exception $exception) {
            Log::error("MyStudentsService::filter()", [$exception]);
            return [];
        }
    }

    /**
     * filter by request params
     *
     * @param Request $request
     * @param $query
     * @return object
     */
    public function filterByRequest(Request $request, $query)
    {
        try {
            if ($request->filled('teacher_id')) {
                $query->whereHas('courses', function ($query) use ($request) {
                    $query->where('courses.teacher_id', $request->teacher_id);
                });
            }

            if ($request->filled('course_category')) {
                $query->whereHas('courses', function ($query) use ($request) {
                    $query->where('courses.category_id', $request->course_category);
                });
            }

            if ($request->filled('course_id')) {
                $query->whereHas('courses', function ($query) use ($request) {
                    $query->where('courses.id', $request->course_id);
                });
            }

            return $query;
        } catch (Exception $exception) {
            Log::error("MyStudentsService::filterByRequest()", [$exception]);
            return [];
        }
    }
}
