<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseCategoryRequest;
use App\Http\Requests\UpdateCourseCategoryRequest;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use App\Services\Admin\CourseCategoryService;
use App\Traits\Auditable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CourseCategoryController extends Controller
{
    use Auditable;

    /**
     * @var CourseCategoryService
     */
    public $courseCategoryService;

    public $layoutFolder = "admin.course-category";

    public $routePrefix = "";

    public function __construct(CourseCategoryService $courseCategoryService)
    {
        $this->courseCategoryService = $courseCategoryService;
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
     * Get category page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            if (Gate::denies('view_course_category')) {
                return redirect()->back()->with('error', 'You are not authorized to access this page.');
            }

            $this->setRoutePrefix();
            $courseCategories = CourseCategory::query()
            ->with(['createdBy','updatedBy'])
            ->select('id','name','created_by','updated_by')
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->paginate(10);

            $data = [
                "courseCategories" => $courseCategories,
                "totalCourseCategories" => $courseCategories->total(),
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("CourseCategoryController::index()", [$exception]);
        }
    }

    /**
     * Get category create page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            if (Gate::denies('create_course_category')) {
                return redirect()->back()->with('error', 'You are not authorized to create course category.');
            }

            $this->setRoutePrefix();
            $data = [];

            return view("{$this->layoutFolder}.create", $data);
        } catch (Exception $exception) {
            Log::error("CourseCategoryController::create()", [$exception]);
        }
    }

    /**
     * Store category in DB
     *
     * @param StoreCourseCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCourseCategoryRequest $request)
    {
        try {
            $this->setRoutePrefix();
            $courseCategory = $this->courseCategoryService->store($request);

            if ($courseCategory) {
                $this->auditLogEntry("course-category:created", $courseCategory->id, 'course-category-create', $courseCategory);
                return redirect()->route($this->routePrefix . '.course-category.index')->with('success', "Category added successfully");
            }

            return redirect()->route($this->routePrefix . '.course-category.index')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("CourseCategoryController::store()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-category.index')->with('error', [$exception->getMessage()]);
        }
    }

    /**
     * Get category edit page.
     *
     * @param integer $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            if (Gate::denies('edit_course_category')) {
                return redirect()->back()->with('error', 'You are not authorized to edit course category.');
            }

            $this->setRoutePrefix();
            $courseCategory = CourseCategory::findOrFail($id);

            $data = [
                "courseCategory" => $courseCategory,
            ];

            return view("{$this->layoutFolder}.edit", $data);
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseCategoryController::edit()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-category.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseCategoryController::edit()", [$exception]);
        }
    }

    /**
     * Update category in DB
     *
     * @param UpdateCourseCategoryRequest $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCourseCategoryRequest $request, int $id)
    {
        try {
            $this->setRoutePrefix();
            $courseCategory = CourseCategory::findOrFail($id);
            $courseCategoryUpdate = $this->courseCategoryService->update($request, $courseCategory);
            if ($courseCategoryUpdate) {
                $this->auditLogEntry("course-category:updated", $courseCategory->id, 'course-category-update', $courseCategoryUpdate);
                return redirect()->route($this->routePrefix . '.course-category.index')->with('success', "Category updated successfully");
            }

            return redirect()->route($this->routePrefix . '.course-category.index')->with('error', "Something went wrong");
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseCategoryController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-category.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseCategoryController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-category.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Delete specific category.
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN) {
                if (Gate::denies('delete_course_category')) {
                    return redirect()->back()->with('error', 'You are not authorized to delete course category.');
                }
            }

            $this->setRoutePrefix();
            $courseCategory = CourseCategory::findOrFail($id);
            $this->courseCategoryService->delete($courseCategory);

            return redirect()->route($this->routePrefix . '.course-category.index')->with('success', "Category deleted successfully");
        } catch (ModelNotFoundException $exception) {
            Log::error("CourseCategoryController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-category.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("CourseCategoryController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.course-category.index')->with('error', $exception->getMessage());
        }
    }
}
