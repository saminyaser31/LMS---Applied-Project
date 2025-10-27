<?php

namespace App\Services\Student;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\CourseEnrollment;
use App\Models\Course;
use App\Models\CourseContents;
use App\Traits\Auditable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CourseService
{
    use Auditable;

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
            $orderBy = $request->order_by ?? 'DESC';
            $filterOption = $request->filter_option ?? 'id';
            $paginate = $request->paginate ?? 10;

            $enrolledCourses = $query->orderBy($filterOption, $orderBy)->paginate($paginate);

            return [
                "enrolledCourses" => $enrolledCourses,
                "totalEnrolledCourses" => $enrolledCourses->total(),
            ];
        } catch (Exception $exception) {
            Log::error("CourseService::filter()", [$exception]);
            return [
                "enrolledCourses" => CourseEnrollment::whereRaw('1!=1')->paginate(),
                "totalEnrolledCourses" => 0,
            ];
        }
    }

    /**
     * store course
     *
     * @param StoreCourseRequest $request
     * @return \App\Models\Course|null
     */
    public function store(StoreCourseRequest $request): Course|null
    {
        try {
            $courseCreate = [
                'student_id' => $request->student_id ?? Auth::user()->id,
                'category_id' => $request->course_category,
                'title' => $request->title,
                'short_description' => $request->short_description,
                'long_description' => $request->long_description,
                'course_start_date' => $request->course_start_date,
                'requirments' => $request->requirments,
                'total_class' => $request->total_class,
                'certificate' => $request->certificate ?? null,
                'quizes' => $request->quizes ?? null,
                'qa' => $request->qa ?? null,
                'study_tips' => $request->study_tips ?? null,
                'career_guidance' => $request->career_guidance ?? null,
                'progress_tracking' => $request->progress_tracking ?? null,
                'flex_learning_pace' => $request->flex_learning_pace ?? null,
                'price' => $request->price,
                'discount_type' => $request->discount_type ?? null,
                'discount_amount' => $request->discount_amount ?? null,
                'discount_start_date' => $request->discount_start_date ?? null,
                'discount_expiry_date' => $request->discount_expiry_date ?? null,
                'promotional_video' => $request->promo_video,
                'status' => Course::STATUS_ENABLE, // Set default status or from request
            ];

            $studentId = $request->student_id ?? Auth::user()->id;
            $courseNo = Course::where('student_id', $studentId)->count();
            $courseNo++;

            // Define base directory for student images
            $studentDir = 'student/' . $studentId . '/course/' . $courseNo;

            // Ensure the directory exists
            Storage::makeDirectory($studentDir);

            // Save course card image
            if ($request->hasFile('course_card_image')) {
                $courseCardImage = $request->file('course_card_image');
                $courseCardPath = $courseCardImage->storeAs($studentDir, 'course-card.' . $courseCardImage->getClientOriginalExtension(), 'public');
                $courseCreate['card_image'] = Storage::url($courseCardPath);
            }

            // Save promotional image
            if ($request->hasFile('promo_image')) {
                $promoImage = $request->file('promo_image');
                $promoPath = $promoImage->storeAs($studentDir, 'promo-image.' . $promoImage->getClientOriginalExtension(), 'public');
                $courseCreate['promotional_image'] = Storage::url($promoPath);
            }

            DB::beginTransaction();
            $course = Course::create($courseCreate);

            $this->storeCourseContents($request, $course->id);

            DB::commit();

            return $course;
        } catch (Exception $exception) {
            Log::error("CourseService::store()", [$exception]);
            DB::rollback();
            return null;
        }
    }

    /**
     * store course contents
     *
     * @param object $request
     * @param integer $courseId
     */
    private function storeCourseContents($request, $courseId)
    {
        try {
            $courseContents = [];
            foreach ($request->content_title as $key => $value) {
                $courseContents[] = [
                    'course_id' => $courseId,
                    'title' => $value,
                    'description' => $request->content_description[$key],
                    'status' => CourseContents::STATUS_INCOMPLETE,
                ];
            }

            CourseContents::insert($courseContents);
        } catch (Exception $exception) {
            Log::error("CourseService::storeCourseContents()", [$exception]);
            throw $exception;
        }
    }

    /**
     * update course
     *
     * @param UpdateCourseRequest $request
     * @param Course $course
     * @return \App\Models\Course|null
     */
    public function update(UpdateCourseRequest $request, $course): Course|null
    {
        try {
            // dd($request->all());
            DB::beginTransaction();

            $course->student_id = $request->student_id ?? Auth::user()->id;
            $course->category_id = $request->course_category;
            $course->title = $request->title;
            $course->short_description = $request->short_description;
            $course->long_description = $request->long_description;
            $course->course_start_date = $request->course_start_date;
            $course->requirments = $request->requirments;
            $course->total_class = $request->total_class;
            $course->certificate = $request->certificate ?? null;
            $course->quizes = $request->quizes ?? null;
            $course->qa = $request->qa ?? null;
            $course->study_tips = $request->study_tips ?? null;
            $course->career_guidance = $request->career_guidance ?? null;
            $course->progress_tracking = $request->progress_tracking ?? null;
            $course->flex_learning_pace = $request->flex_learning_pace ?? null;
            $course->price = $request->price;
            $course->discount_type = $request->discount_type ?? null;
            $course->discount_amount = $request->discount_amount ?? null;
            $course->discount_start_date = $request->discount_start_date ?? null;
            $course->discount_expiry_date = $request->discount_expiry_date ?? null;
            $course->promotional_video = $request->promo_video;
            $course->status = $request->course_status;
            $course->updated_by = Auth::user()->id;

            $studentId = $request->student_id ?? Auth::user()->id;
            $courseNo = Course::where('student_id', $studentId)->count();

            // Define base directory for student images
            $studentDir = 'student/' . $studentId . '/course/' . $courseNo;

            // Ensure the new directory exists
            Storage::makeDirectory($studentDir);

            // Save course card image
            if ($request->hasFile('course_card_image')) {
                $course->card_image = $this->saveImage($request->file('course_card_image'), $studentDir, 'course-card.' . $request->file('course_card_image')->getClientOriginalExtension());
            }

            // Save promotional image
            if ($request->hasFile('promo_image')) {
                $course->promotional_image = $this->saveImage($request->file('course_card_image'), $studentDir, 'promo-image.' . $request->file('promo_image')->getClientOriginalExtension());
            }

            $course->save();

            $this->deleteAndUpdateCourseInfos($course->id, $request);

            DB::commit();

            return $course;
        } catch (Exception $exception) {
            Log::error("CourseService::update()", [$exception]);
            DB::rollback();
            return null;
        }
    }

    /**
     * Save the uploaded image to the specified directory and return the URL.
     *
     * @param \Illuminate\Http\UploadedFile $imageRequest The uploaded image file.
     * @param string $directory The directory where the image should be stored.
     * @param string $imageName The name of the image file to store.
     * @return string|null The URL of the saved image or null if there was an error.
     */
    private function saveImage($imageRequest, $directory, $imageName)
    {
        try {
            $imagePath = $imageRequest->storeAs($directory, $imageName, 'public');
            return Storage::url($imagePath);
        } catch (Exception $exception) {
            Log::error("CourseService::saveImage()", [$exception]);
            return null;
        }
    }

    /**
     * delete existing record and than update specific course
     *
     * @param UpdateCourseRequest $request
     * @param integer $courseId
     * @return void
     */
    private function deleteAndUpdateCourseInfos($courseId, $request)
    {
        try {
            CourseContents::where('course_id', $courseId)->delete();
            $this->storeCourseContents($request, $courseId);
        } catch (Exception $exception) {
            Log::error("CourseService::deleteAndUpdateCourseInfos()", [$exception]);
            DB::rollback();
        }
    }

    /**
     * delete specific Course
     *
     * @param Course $course
     * @return void
     */
    public function delete(Course $course)
    {
        try {
            DB::beginTransaction();

            // Update the deleted_by column with the current user's ID
            $course->deleted_by = Auth::user()->id;
            $course->title = $course->title . '-deleted:' . Carbon::now()->format('Y-m-d H:i:s');
            $course->save();

            $course->delete();
            CourseContents::where('course_id', $course->id)->delete();

            DB::commit();

            // Clear the relevant cache

            $this->auditLogEntry("course:deleted", $course->id, 'course-deleted', $course);
        } catch (Exception $exception) {
            Log::error("CourseService::delete()", [$exception]);
            DB::rollback();
        }
    }
}
