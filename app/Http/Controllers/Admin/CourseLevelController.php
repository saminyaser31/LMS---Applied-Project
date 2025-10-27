<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseLevelRequest;
use App\Http\Requests\UpdateCourseLevelRequest;
use App\Models\CourseLevel;
use App\Models\User;
use App\Services\Admin\CourseLevelService;
use App\Traits\Auditable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CourseLevelController extends Controller
{
    use Auditable;

    /**
     * @var CourseLevelService
     */
    public $courseLevelService;

    public $layoutFolder = "admin.course-level";

    public $routePrefix = "";

    public function __construct(CourseLevelService $courseLevelService)
    {
        $this->courseLevelService = $courseLevelService;
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
     * Get level page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            if (Gate::denies('view_course_level')) {
                return redirect()->back()->with('error', 'You are not authorized to access this page.');
            }

            $this->setRoutePrefix();
            $courseLevels = CourseLevel::query()
            ->with(['createdBy','updatedBy'])
            ->select('id','name','created_by','updated_by')
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->paginate(10);

            $data = [
                "courseLevels" => $courseLevels,
                "totalCourseLevels" => $courseLevels->total(),
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("CourseLevelController::index()", [$exception]);
        }
    }

    /**
     * Get level create page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            if (Gate::denies('create_course_level')) {
                return redirect()->back()->with('error', 'You are not authorized to create course level.');
            }

            $this->setRoutePrefix();
            $data = [];

            return view("{$this->layoutFolder}.create", $data);
        } catch (Exception $exception) {
            Log::error("CourseLevelController::create()", [$exception]);
        }
    }

    /**
     * Store level in DB
     *
     * @param StoreCourseLevelRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCourseLevelRequest $request)
    {
        try {
            $this->setRoutePrefix();
            $courseLevel = $this->courseLevelService->store($request);

            if ($courseLevel) {
                $this->auditLogEntry("course-level:created", $courseLevel->id, 'course-level-create', $courseLevel);
                return redirect()->route($this->routePrefix . '.course-level.index')->with('success', "Level added successfully");
            }

            return redirect()->route($this->routePrefix . '.course-level.index')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("CourseLevelController::store()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-level.index')->with('error', [$exception->getMessage()]);
        }
    }

    /**
     * Get level edit page.
     *
     * @param integer $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            if (Gate::denies('edit_course_level')) {
                return redirect()->back()->with('error', 'You are not authorized to edit course level.');
            }

            $this->setRoutePrefix();
            $courseLevel = CourseLevel::findOrFail($id);

            $data = [
                "courseLevel" => $courseLevel,
            ];

            return view("{$this->layoutFolder}.edit", $data);
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseLevelController::edit()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-level.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseLevelController::edit()", [$exception]);
        }
    }

    /**
     * Update level in DB
     *
     * @param UpdateCourseLevelRequest $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCourseLevelRequest $request, int $id)
    {
        try {
            $this->setRoutePrefix();
            $courseLevel = CourseLevel::findOrFail($id);
            $courseLevelUpdate = $this->courseLevelService->update($request, $courseLevel);
            if ($courseLevelUpdate) {
                $this->auditLogEntry("course-level:updated", $courseLevel->id, 'course-level-update', $courseLevelUpdate);
                return redirect()->route($this->routePrefix . '.course-level.index')->with('success', "Level updated successfully");
            }

            return redirect()->route($this->routePrefix . '.course-level.index')->with('error', "Something went wrong");
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseLevelController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-level.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseLevelController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-level.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Delete specific level.
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN) {
                if (Gate::denies('delete_course_level')) {
                    return redirect()->back()->with('error', 'You are not authorized to delete course level.');
                }
            }

            $this->setRoutePrefix();
            $courseLevel = CourseLevel::findOrFail($id);
            $this->courseLevelService->delete($courseLevel);

            return redirect()->route($this->routePrefix . '.course-level.index')->with('success', "Level deleted successfully");
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseLevelController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-level.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseLevelController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-level.index')->with('error', $exception->getMessage());
        }
    }
}
