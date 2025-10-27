<?php

namespace App\Services\Teacher;

use App\Helper\Helper;
use App\Http\Requests\StoreCourseMaterialRequest;
use App\Http\Requests\UpdateCourseMaterialRequest;
use App\Models\CourseMaterials;
use App\Traits\Auditable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CourseMaterialService
{
    use Auditable;

    /**
     * filter material
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

            $courseMaterials = $query->orderBy($filterOption, $orderBy)->paginate($paginate);

            return [
                "courseMaterials" => $courseMaterials,
                "totalCourseMaterials" => $courseMaterials->total(),
            ];
        } catch (Exception $exception) {
            Log::error("CourseMaterialService::filter()", [$exception]);
            return [];
        }
    }

    /**
     * filter material by request params
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

            return $query;
        } catch (Exception $exception) {
            Log::error("CourseMaterialService::filterByRequest()", [$exception]);
            return [];
        }
    }

    /**
     * store material
     *
     * @param StoreCourseMaterialRequest $request
     * @return \App\Models\CourseMaterials|null
     */
    public function store(StoreCourseMaterialRequest $request): CourseMaterials|null
    {
        try {
            $courseMaterial = [
                'course_id' => $request->course_id,
                'title' => $request->title,
                'material_type' => $request->material_type,
                'upload_type' => $request->upload_type,
                'remarks' => $request->remarks,
                'created_by' => Auth::user()->id,
            ];

            if ($request->upload_type == CourseMaterials::TYPE_URL) {
                $courseMaterial['url'] = $request->url;
            }

            if ($request->upload_type == CourseMaterials::TYPE_FILE) {
                $teacherId = $request->teacher_id ?? Auth::user()->id;
                // Define base directory
                $teacherDir = 'teacher/' . $teacherId . '/course/' . $request->course_id . '/materials';
                // Ensure the directory exists
                Storage::makeDirectory($teacherDir);
                $filename = 'material-' . date('YmdHis') . '.' . $request->file('file')->getClientOriginalExtension();
                $materialFile = Helper::saveFile($request->file('file'), $teacherDir, $filename);
                $courseMaterial['file'] = $materialFile;
            }

            $courseMaterial = CourseMaterials::create($courseMaterial);

            return $courseMaterial;
        } catch (Exception $exception) {
            Log::error("CourseMaterialService::store()", [$exception]);
            return null;
        }
    }

    /**
     * update material
     *
     * @param UpdateCourseMaterialRequest $request
     * @param CourseMaterials $courseMaterial
     * @return \App\Models\CourseMaterials|null
     */
    public function update(UpdateCourseMaterialRequest $request, $courseMaterial): CourseMaterials|null
    {
        try {
            // dd($request->all());

            $courseMaterial->course_id = $request->course_id;
            $courseMaterial->title = $request->title;
            $courseMaterial->material_type = $request->material_type;
            $courseMaterial->upload_type = $request->upload_type;
            $courseMaterial->remarks = $request->remarks;
            $courseMaterial->updated_by = Auth::user()->id;

            if ($request->upload_type == CourseMaterials::TYPE_URL) {
                $courseMaterial->url = $request->url;
                $courseMaterial->file = null;
            }

            if ($request->upload_type == CourseMaterials::TYPE_FILE) {
                $teacherId = $request->teacher_id ?? Auth::user()->id;
                // Define base directory
                $teacherDir = 'teacher/' . $teacherId . '/course/' . $request->course_id . '/materials';
                // Ensure the directory exists
                Storage::makeDirectory($teacherDir);
                $filename = 'material-' . date('YmdHis') . '.' . $request->file('file')->getClientOriginalExtension();
                $materialFile = Helper::saveFile($request->file('file'), $teacherDir, $filename);
                $courseMaterial->file = $materialFile;
                $courseMaterial->url = null;
            }

            $courseMaterial->save();

            return $courseMaterial;
        } catch (Exception $exception) {
            Log::error("CourseMaterialService::update()", [$exception]);
            return null;
        }
    }

    /**
     * delete specific material
     *
     * @param CourseMaterials $courseMaterial
     * @return void
     */
    public function delete(CourseMaterials $courseMaterial)
    {
        try {
            DB::beginTransaction();

            // Update the deleted_by column with the current user's ID
            $courseMaterial->deleted_by = Auth::user()->id;
            $courseMaterial->title = $courseMaterial->title . '-deleted:' . Carbon::now()->format('Y-m-d H:i:s');
            $courseMaterial->save();

            $courseMaterial->delete();
            CourseMaterials::where('course_id', $courseMaterial->id)->delete();

            DB::commit();

            $this->auditLogEntry("course-material:deleted", $courseMaterial->id, 'course-material-deleted', $courseMaterial);
        } catch (Exception $exception) {
            Log::error("CourseMaterialService::delete()", [$exception]);
            DB::rollback();
        }
    }
}
