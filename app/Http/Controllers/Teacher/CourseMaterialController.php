<?php

namespace App\Http\Controllers\Teacher;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseMaterialRequest;
use App\Http\Requests\UpdateCourseMaterialRequest;
use App\Models\Course;
use App\Models\CourseMaterials;
use App\Models\Teacher\Teachers;
use App\Models\User;
use App\Services\Teacher\CourseMaterialService;
use App\Traits\Auditable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CourseMaterialController extends Controller
{
    use Auditable;

    /**
     * @var CourseMaterialService
     */
    public $courseMaterialService;

    public $layoutFolder = "teacher.courses.materials";

    public $routePrefix = "";

    public function __construct(CourseMaterialService $courseMaterialService)
    {
        $this->courseMaterialService = $courseMaterialService;
    }

    public function setRoutePrefix()
    {
        if (isset(app('admin')->id)) {
            $this->routePrefix = "admin";
        }
        else if (isset(app('teacher')->id)) {
            $this->routePrefix = "teacher";
        }
    }

    /**
     * Get materials page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN && Gate::denies('view_course_material')) {
                return redirect()->back()->with('error', 'You are not authorized to access this page.');
            }

            $this->setRoutePrefix();
            $query = CourseMaterials::query()
            ->with('course')
            ->whereNull('deleted_at');

            $result = (new CourseMaterialService)->filter($request, $query);

            $courseCategory = Helper::getAllCourseCategory();
            $teachers = Teachers::select('id','first_name','last_name','user_id')->get();

            $courses = Course::query()
            ->with('courseTeacher')
            ->select('id','title','teacher_id')
            ->where(function ($query) {
                if (Auth::user()->user_type == User::TEACHER) {
                    $query->where('teacher_id', Auth::user()->id);
                }
            })
            ->whereNull('courses.deleted_at')
            ->where('courses.status', Course::STATUS_ENABLE)
            ->get();

            $data = [
                "courses" => $courses,
                "courseMaterials" => isset($result['courseMaterials']) ? $result['courseMaterials'] : [],
                "totalCourseMaterials" => isset($result['totalCourseMaterials']) ? $result['totalCourseMaterials'] : 0,
                "courseCategory" => $courseCategory,
                "teachers" => $teachers,
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("CourseMaterialController::index()", [$exception]);
        }
    }

    /**
     * Get material create page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN && Gate::denies('create_course_material')) {
                return redirect()->back()->with('error', 'You are not authorized to create course material.');
            }

            $this->setRoutePrefix();
            $courses = Course::query()
            ->with('courseTeacher')
            ->select('id','title','teacher_id')
            ->where(function ($query) {
                if (Auth::user()->user_type == User::TEACHER) {
                    $query->where('teacher_id', Auth::user()->id);
                }
            })
            ->whereNull('courses.deleted_at')
            ->where('courses.status', Course::STATUS_ENABLE)
            ->get();

            $data = [
                "courses" => $courses,
            ];

            return view("{$this->layoutFolder}.create", $data);
        } catch (Exception $exception) {
            Log::error("CourseMaterialController::create()", [$exception]);
        }
    }

    /**
     * Store material in DB
     *
     * @param StoreCourseMaterialRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCourseMaterialRequest $request)
    {
        try {
            $this->setRoutePrefix();
            $courseMaterial = $this->courseMaterialService->store($request);

            if ($courseMaterial) {
                $this->auditLogEntry("course-material:created", $courseMaterial->id, 'course-material-create', $courseMaterial);
                return redirect()->route($this->routePrefix . '.course-materials.index')->with('success', "Material added successfully");
            }

            return redirect()->route($this->routePrefix . '.course-materials.index')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("CourseMaterialController::store()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-materials.index')->with('error', [$exception->getMessage()]);
        }
    }

    /**
     * Get material edit page.
     *
     * @param integer $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN && Gate::denies('edit_course_material')) {
                return redirect()->back()->with('error', 'You are not authorized to edit course material.');
            }

            $this->setRoutePrefix();
            $courseMaterial = CourseMaterials::findOrFail($id);
            $courses = Course::query()
            ->with('courseTeacher')
            ->select('id','title','teacher_id')
            ->where(function ($query) {
                if (Auth::user()->user_type == User::TEACHER) {
                    $query->where('teacher_id', Auth::user()->id);
                }
            })
            ->whereNull('courses.deleted_at')
            ->where('courses.status', Course::STATUS_ENABLE)
            ->get();

            $data = [
                "courseMaterial" => $courseMaterial,
                "courses" => $courses,
            ];

            return view("{$this->layoutFolder}.edit", $data);
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseMaterialController::edit()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-materials.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseMaterialController::edit()", [$exception]);
        }
    }

    /**
     * Update material in DB
     *
     * @param UpdateCourseMaterialRequest $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCourseMaterialRequest $request, int $id)
    {
        try {
            $this->setRoutePrefix();
            $courseMaterial = CourseMaterials::findOrFail($id);
            $courseMaterialUpdate = $this->courseMaterialService->update($request, $courseMaterial);
            if ($courseMaterialUpdate) {
                $this->auditLogEntry("course-material:updated", $courseMaterial->id, 'course-material-update', $courseMaterialUpdate);
                return redirect()->route($this->routePrefix . '.course-materials.index')->with('success', "Material updated successfully");
            }

            return redirect()->route($this->routePrefix . '.course-materials.index')->with('error', "Something went wrong");
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseMaterialController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-materials.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseMaterialController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-materials.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Delete specific material.
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN) {
                if (Gate::denies('delete_course_material')) {
                    return redirect()->back()->with('error', 'You are not authorized to delete course material.');
                }
            }

            $this->setRoutePrefix();
            $courseMaterial = CourseMaterials::findOrFail($id);
            $this->courseMaterialService->delete($courseMaterial);

            return redirect()->route($this->routePrefix . '.course-materials.index')->with('success', "Material deleted successfully");
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseMaterialController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-materials.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseMaterialController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-materials.index')->with('error', $exception->getMessage());
        }
    }
}
