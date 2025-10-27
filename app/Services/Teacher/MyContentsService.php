<?php

namespace App\Services\Teacher;

use App\Helper\Helper;
use App\Http\Requests\StoreTeacherContentRequest;
use App\Http\Requests\UpdateTeacherContentRequest;
use App\Models\TeacherContents;
use App\Traits\Auditable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MyContentsService
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

            $teacherContents = $query->orderBy($filterOption, $orderBy)->paginate($paginate);

            return [
                "teacherContents" => $teacherContents,
                "totalTeacherContents" => $teacherContents->total(),
            ];
        } catch (Exception $exception) {
            Log::error("MyContentsService::filter()", [$exception]);
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
                $query->where('teacher_id', $request->teacher_id);
            }

            if ($request->filled('content_type')) {
                $query->where('content_type', $request->content_type);
            }

            if ($request->filled('file_type')) {
                $query->where('file_type', $request->file_type);
            }

            return $query;
        } catch (Exception $exception) {
            Log::error("MyContentsService::filterByRequest()", [$exception]);
            return [];
        }
    }

    /**
     * store content
     *
     * @param StoreTeacherContentRequest $request
     * @return \App\Models\TeacherContents|null
     */
    public function store(StoreTeacherContentRequest $request): TeacherContents|null
    {
        try {
            $teacherContent = [
                'teacher_id' => $request->teacher_id ?? Auth::user()->id,
                'content_type' => $request->content_type,
                'file_type' => $request->upload_type,
            ];

            if ($request->upload_type == TeacherContents::TYPE_URL) {
                $teacherContent['file_path'] = $request->url;
            }

            $teacherId = $request->teacher_id ?? Auth::user()->id;
            $teacherDir = 'teacher/' . $teacherId . '/contents';
            Storage::makeDirectory($teacherDir);

            if ($request->upload_type == TeacherContents::TYPE_FILE) {
                $filename = 'content-' . date('YmdHis') . '.' . $request->file('file')->getClientOriginalExtension();
                $contentFile = Helper::saveFile($request->file('file'), $teacherDir, $filename);
                $teacherContent['file_path'] = $contentFile;
            }

            if ($request->thumbnail) {
                $filename = 'thumbnail-' . date('YmdHis') . '.' . $request->file('thumbnail')->getClientOriginalExtension();
                $thumbnail = Helper::saveFile($request->file('thumbnail'), $teacherDir, $filename);
                $teacherContent['thumbnail'] = $thumbnail;
            }

            $teacherContent = TeacherContents::create($teacherContent);

            return $teacherContent;
        } catch (Exception $exception) {
            Log::error("MyContentsService::store()", [$exception]);
            return null;
        }
    }

    /**
     * update content
     *
     * @param UpdateTeacherContentRequest $request
     * @param TeacherContents $teacherContent
     * @return \App\Models\TeacherContents|null
     */
    public function update(UpdateTeacherContentRequest $request, $teacherContent): TeacherContents|null
    {
        try {
            // dd($request->all());

            $teacherContent->teacher_id = $request->teacher_id ?? Auth::user()->id;
            $teacherContent->content_type = $request->content_type;
            $teacherContent->file_type = $request->upload_type;
            $teacherContent->status = $request->content_status;
            $teacherContent->updated_by = Auth::user()->id;

            if ($request->upload_type == TeacherContents::TYPE_URL) {
                $teacherContent->file_path = $request->url;
            }

            $teacherId = $request->teacher_id ?? Auth::user()->id;
            $teacherDir = 'teacher/' . $teacherId . '/contents';
            Storage::makeDirectory($teacherDir);

            if ($request->upload_type == TeacherContents::TYPE_FILE) {
                $filename = 'content-' . date('YmdHis') . '.' . $request->file('file')->getClientOriginalExtension();
                $contentFile = Helper::saveFile($request->file('file'), $teacherDir, $filename);
                $teacherContent->file_path = $contentFile;
            }

            if ($request->thumbnail) {
                $filename = 'thumbnail-' . date('YmdHis') . '.' . $request->file('thumbnail')->getClientOriginalExtension();
                $thumbnail = Helper::saveFile($request->file('thumbnail'), $teacherDir, $filename);
                $teacherContent->thumbnail = $thumbnail;
            }

            $teacherContent->save();

            return $teacherContent;
        } catch (Exception $exception) {
            Log::error("MyContentsService::update()", [$exception]);
            return null;
        }
    }

    /**
     * delete specific content
     *
     * @param TeacherContents $teacherContent
     * @return void
     */
    public function delete(TeacherContents $teacherContent)
    {
        try {
            DB::beginTransaction();

            // Update the deleted_by column with the current user's ID
            $teacherContent->deleted_by = Auth::user()->id;
            $teacherContent->save();

            $teacherContent->delete();
            TeacherContents::where('id', $teacherContent->id)->delete();

            DB::commit();

            $this->auditLogEntry("teacher-content:deleted", $teacherContent->id, 'teacher-content-deleted', $teacherContent);
        } catch (Exception $exception) {
            Log::error("MyContentsService::delete()", [$exception]);
            DB::rollback();
        }
    }
}
