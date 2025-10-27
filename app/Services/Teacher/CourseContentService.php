<?php

namespace App\Services\Teacher;

use App\Http\Requests\StoreCourseContentRequest;
use App\Http\Requests\UpdateCourseContentRequest;
use App\Models\CourseContents;
use App\Traits\Auditable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseContentService
{
    use Auditable;

    /**
     * filter content
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

            $courseContents = $query->orderBy($filterOption, $orderBy)->paginate($paginate);

            return [
                "courseContents" => $courseContents,
                "totalCourseContents" => $courseContents->total(),
            ];
        } catch (Exception $exception) {
            Log::error("CourseContentService::filter()", [$exception]);
            return [];
        }
    }

    /**
     * filter content by request params
     *
     * @param Request $request
     * @param $query
     * @return object
     */
    public function filterByRequest(Request $request, $query)
    {
        try {
            if ($request->filled('teacher_id')) {
                $query->whereHas('course', function ($query) use ($request) {
                    $query->where('courses.teacher_id', $request->teacher_id);
                });
            }

            if ($request->filled('course_category')) {
                $query->whereHas('course', function ($query) use ($request) {
                    $query->where('courses.category_id', $request->course_category);
                });
            }

            if ($request->filled('course_id')) {
                $query->whereHas('course', function ($query) use ($request) {
                    $query->where('courses.id', $request->course_id);
                });
            }

            // Filter by status
            if ($request->filled('content_status')) {
                $query->where('status', $request->content_status);
            }

            return $query;
        } catch (Exception $exception) {
            Log::error("CourseContentService::filterByRequest()", [$exception]);
            return [];
        }
    }

    /**
     * store content
     *
     * @param StoreCourseContentRequest $request
     * @return \App\Models\CourseContents|null
     */
    public function store(StoreCourseContentRequest $request): CourseContents|null
    {
        try {
            $courseContent = [
                'course_id' => $request->course_id,
                'content_no' => $request->content_no,
                'title' => $request->content_title,
                'description' => $request->content_description,
                'class_time' => $request->class_time,
                'class_link' => $request->class_link,
                'status' => CourseContents::STATUS_INCOMPLETE,
            ];

            $courseContent = CourseContents::create($courseContent);

            return $courseContent;
        } catch (Exception $exception) {
            Log::error("CourseContentService::store()", [$exception]);
            return null;
        }
    }

    /**
     * update content
     *
     * @param UpdateCourseContentRequest $request
     * @param CourseContents $courseContent
     * @return \App\Models\CourseContents|null
     */
    public function update(UpdateCourseContentRequest $request, $courseContent): CourseContents|null
    {
        try {
            // dd($request->all());

            $courseContent->course_id = $request->course_id;
            $courseContent->content_no = $request->content_no;
            $courseContent->title = $request->content_title;
            $courseContent->description = $request->content_description;
            $courseContent->class_time = $request->class_time;
            $courseContent->class_link = $request->class_link;
            $courseContent->status = $request->content_status;
            $courseContent->updated_by = Auth::user()->id;

            $courseContent->save();

            return $courseContent;
        } catch (Exception $exception) {
            Log::error("CourseContentService::update()", [$exception]);
            return null;
        }
    }

    /**
     * delete specific content
     *
     * @param CourseContents $courseContent
     * @return void
     */
    public function delete(CourseContents $courseContent)
    {
        try {
            DB::beginTransaction();

            // Update the deleted_by column with the current user's ID
            $courseContent->deleted_by = Auth::user()->id;
            $courseContent->title = $courseContent->title . '-deleted:' . Carbon::now()->format('Y-m-d H:i:s');
            $courseContent->save();

            $courseContent->delete();
            CourseContents::where('course_id', $courseContent->id)->delete();

            DB::commit();

            $this->auditLogEntry("course-content:deleted", $courseContent->id, 'course-content-deleted', $courseContent);
        } catch (Exception $exception) {
            Log::error("CourseContentService::delete()", [$exception]);
            DB::rollback();
        }
    }
}
