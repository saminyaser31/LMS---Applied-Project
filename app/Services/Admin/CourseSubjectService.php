<?php

namespace App\Services\Admin;

use App\Http\Requests\StoreCourseSubjectRequest;
use App\Http\Requests\UpdateCourseSubjectRequest;
use App\Models\CourseSubject;
use App\Traits\Auditable;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CourseSubjectService
{
    use Auditable;

    /**
     * store category
     *
     * @param StoreCourseSubjectRequest $request
     * @return \App\Models\CourseSubject|null
     */
    public function store(StoreCourseSubjectRequest $request): CourseSubject|null
    {
        try {
            $courseSubject = [
                'name' => $request->name,
                'status' => CourseSubject::STATUS_ENABLE,
                'created_by' => Auth::user()->id,
            ];

            $courseSubject = CourseSubject::create($courseSubject);
            $this->clearCache();

            return $courseSubject;
        } catch (Exception $exception) {
            Log::error("CourseSubjectService::store()", [$exception]);
            return null;
        }
    }

    /**
     * update category
     *
     * @param UpdateCourseCategoryRequest $request
     * @param CourseSubject $courseSubject
     * @return \App\Models\CourseSubject|null
     */
    public function update(UpdateCourseSubjectRequest $request, $courseSubject): CourseSubject|null
    {
        try {
            // dd($request->all());

            $courseSubject->name = $request->name;
            // $courseSubject->status = $request->status;
            $courseSubject->updated_by = Auth::user()->id;

            $courseSubject->save();
            $this->clearCache();

            return $courseSubject;
        } catch (Exception $exception) {
            Log::error("CourseCategoryService::update()", [$exception]);
            return null;
        }
    }

    /**
     * delete specific category
     *
     * @param CourseSubject $courseSubject
     * @return void
     */
    public function delete(CourseSubject $courseSubject)
    {
        try {
            DB::beginTransaction();

            // Update the deleted_by column with the current user's ID
            $courseSubject->deleted_by = Auth::user()->id;
            $courseSubject->name = $courseSubject->name . '-deleted:' . Carbon::now()->format('Y-m-d H:i:s');
            $courseSubject->save();

            $courseSubject->delete();
            CourseSubject::where('id', $courseSubject->id)->delete();

            DB::commit();

            $this->clearCache();

            $this->auditLogEntry("course-subject:deleted", $courseSubject->id, 'course-subject-deleted', $courseSubject);
        } catch (Exception $exception) {
            Log::error("CourseSubjectService::delete()", [$exception]);
            DB::rollback();
        }
    }

    private function clearCache()
    {
        try {
            Cache::forget('all-course-subject-cache');
        } catch (Exception $exception) {
            Log::error("CourseSubjectService::clearCache()", [$exception]);
        }
    }
}
