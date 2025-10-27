<?php

namespace App\Services\Admin;

use App\Http\Requests\StoreCourseCategoryRequest;
use App\Http\Requests\UpdateCourseCategoryRequest;
use App\Models\CourseCategory;
use App\Traits\Auditable;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CourseCategoryService
{
    use Auditable;

    /**
     * store category
     *
     * @param StoreCourseCategoryRequest $request
     * @return \App\Models\CourseCategory|null
     */
    public function store(StoreCourseCategoryRequest $request): CourseCategory|null
    {
        try {
            $courseCategory = [
                'name' => $request->name,
                'status' => CourseCategory::STATUS_ENABLE,
                'created_by' => Auth::user()->id,
            ];

            $courseCategory = CourseCategory::create($courseCategory);

            $this->clearCache();

            return $courseCategory;
        } catch (Exception $exception) {
            Log::error("CourseCategoryService::store()", [$exception]);
            return null;
        }
    }

    /**
     * update category
     *
     * @param UpdateCourseCategoryRequest $request
     * @param CourseCategory $courseCategory
     * @return \App\Models\CourseCategory|null
     */
    public function update(UpdateCourseCategoryRequest $request, $courseCategory): CourseCategory|null
    {
        try {
            // dd($request->all());

            $courseCategory->name = $request->name;
            // $courseCategory->status = $request->status;
            $courseCategory->updated_by = Auth::user()->id;

            $courseCategory->save();

            $this->clearCache();

            return $courseCategory;
        } catch (Exception $exception) {
            Log::error("CourseCategoryService::update()", [$exception]);
            return null;
        }
    }

    /**
     * delete specific category
     *
     * @param CourseCategory $courseCategory
     * @return void
     */
    public function delete(CourseCategory $courseCategory)
    {
        try {
            DB::beginTransaction();

            // Update the deleted_by column with the current user's ID
            $courseCategory->deleted_by = Auth::user()->id;
            $courseCategory->name = $courseCategory->name . '-deleted:' . Carbon::now()->format('Y-m-d H:i:s');
            $courseCategory->save();

            $courseCategory->delete();
            CourseCategory::where('id', $courseCategory->id)->delete();

            DB::commit();

            $this->clearCache();

            $this->auditLogEntry("course-category:deleted", $courseCategory->id, 'course-category-deleted', $courseCategory);
        } catch (Exception $exception) {
            Log::error("CourseCategoryService::delete()", [$exception]);
            DB::rollback();
        }
    }

    private function clearCache()
    {
        try {
            Cache::forget('all-course-category-cache');
        } catch (Exception $exception) {
            Log::error("CourseCategoryService::clearCache()", [$exception]);
        }
    }
}
