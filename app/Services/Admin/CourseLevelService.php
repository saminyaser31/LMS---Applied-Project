<?php

namespace App\Services\Admin;

use App\Http\Requests\StoreCourseLevelRequest;
use App\Http\Requests\UpdateCourseLevelRequest;
use App\Models\CourseLevel;
use App\Traits\Auditable;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
class CourseLevelService
{
    use Auditable;

    /**
     * store category
     *
     * @param StoreCourseLevelRequest $request
     * @return \App\Models\CourseLevel|null
     */
    public function store(StoreCourseLevelRequest $request): CourseLevel|null
    {
        try {
            $courseLevel = [
                'name' => $request->name,
                'status' => CourseLevel::STATUS_ENABLE,
                'created_by' => Auth::user()->id,
            ];

            $courseLevel = CourseLevel::create($courseLevel);

            $this->clearCache();

            return $courseLevel;
        } catch (Exception $exception) {
            Log::error("CourseCategoryService::store()", [$exception]);
            return null;
        }
    }

    /**
     * update category
     *
     * @param UpdateCourseLevelRequest $request
     * @param CourseLevel $courseLevel
     * @return \App\Models\CourseLevel|null
     */
    public function update(UpdateCourseLevelRequest $request, $courseLevel): CourseLevel|null
    {
        try {
            // dd($request->all());

            $courseLevel->name = $request->name;
            // $courseLevel->status = $request->status;
            $courseLevel->updated_by = Auth::user()->id;

            $courseLevel->save();

            $this->clearCache();

            return $courseLevel;
        } catch (Exception $exception) {
            Log::error("CourseCategoryService::update()", [$exception]);
            return null;
        }
    }

    /**
     * delete specific category
     *
     * @param CourseLevel $courseLevel
     * @return void
     */
    public function delete(CourseLevel $courseLevel)
    {
        try {
            DB::beginTransaction();

            // Update the deleted_by column with the current user's ID
            $courseLevel->deleted_by = Auth::user()->id;
            $courseLevel->name = $courseLevel->name . '-deleted:' . Carbon::now()->format('Y-m-d H:i:s');
            $courseLevel->save();

            $courseLevel->delete();
            CourseLevel::where('id', $courseLevel->id)->delete();

            DB::commit();

            $this->clearCache();

            $this->auditLogEntry("course-level:deleted", $courseLevel->id, 'course-level-deleted', $courseLevel);
        } catch (Exception $exception) {
            Log::error("CourseLevelService::delete()", [$exception]);
            DB::rollback();
        }
    }

    private function clearCache()
    {
        try {
            Cache::forget('all-course-level-cache');
        } catch (Exception $exception) {
            Log::error("CourseLevelService::clearCache()", [$exception]);
        }
    }
}
