<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherContentRequest;
use App\Http\Requests\UpdateTeacherContentRequest;
use App\Models\Teacher\Teachers;
use App\Models\TeacherContents;
use App\Models\User;
use App\Services\Teacher\MyContentsService;
use App\Traits\Auditable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class MyContentsController extends Controller
{
    use Auditable;

    /**
     * @var MyContentsService
     */
    public $myContentsService;

    public $layoutFolder = "teacher.my-contents";

    public $routePrefix = "";

    public function __construct(MyContentsService $myContentsService)
    {
        $this->myContentsService = $myContentsService;
    }

    public function setRoutePrefix()
    {
        if (isset(app('admin')->id)) {
            $this->routePrefix = "admin";
        }
        else if (isset(app('teacher')->id)) {
            $this->routePrefix = "teacher.my-contents";
        }
    }

    /**
     * Get page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN && Gate::denies('view_teacher_content')) {
                return redirect()->back()->with('error', 'You are not authorized to access this page.');
            }

            $this->setRoutePrefix();
            $query = TeacherContents::query()
            ->with('teacher')
            ->whereNull('deleted_at');

            $result = (new MyContentsService)->filter($request, $query);
            $teachers = Teachers::select('id','first_name','last_name','user_id')->get();

            $data = [
                "teacherContents" => isset($result['teacherContents']) ? $result['teacherContents'] : [],
                "totalTeacherContents" => isset($result['totalTeacherContents']) ? $result['totalTeacherContents'] : 0,
                "teachers" => $teachers,
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("MyContentsController::index()", [$exception]);
        }
    }

    /**
     * Get create page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN && Gate::denies('create_teacher_content')) {
                return redirect()->back()->with('error', 'You are not authorized to access this page.');
            }

            $this->setRoutePrefix();

            $data = [];

            return view("{$this->layoutFolder}.create", $data);
        } catch (Exception $exception) {
            Log::error("MyContentsController::create()", [$exception]);
        }
    }

    /**
     * Store in DB
     *
     * @param StoreTeacherContentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTeacherContentRequest $request)
    {
        try {
            $this->setRoutePrefix();
            $teacherContent = $this->myContentsService->store($request);

            if ($teacherContent) {
                $this->auditLogEntry("teacher-content:created", $teacherContent->id, 'teacher-content-create', $teacherContent);
                return redirect()->route($this->routePrefix . '.index')->with('success', "Content added successfully");
            }

            return redirect()->route($this->routePrefix . '.index')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("MyContentsController::store()", [$exception]);
            return redirect()->route($this->routePrefix . '.index')->with('error', [$exception->getMessage()]);
        }
    }

    /**
     * Get edit page.
     *
     * @param integer $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN && Gate::denies('edit_teacher_content')) {
                return redirect()->back()->with('error', 'You are not authorized to edit this page.');
            }

            $this->setRoutePrefix();
            $teacherContent = TeacherContents::findOrFail($id);

            $data = [
                "teacherContent" => $teacherContent,
            ];

            return view("{$this->layoutFolder}.edit", $data);
        } catch (ModelNotFoundException $exception) {
            Log::error("MyContentsController::edit()", [$exception]);
            return redirect()->route($this->routePrefix . '.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("MyContentsController::edit()", [$exception]);
        }
    }

    /**
     * Update in DB
     *
     * @param UpdateTeacherContentRequest $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTeacherContentRequest $request, int $id)
    {
        try {
            $this->setRoutePrefix();
            $teacherContent = TeacherContents::findOrFail($id);
            $teacherContentUpdate = $this->myContentsService->update($request, $teacherContent);
            if ($teacherContentUpdate) {
                $this->auditLogEntry("teacher-content:updated", $teacherContent->id, 'teacher-content-update', $teacherContentUpdate);
                return redirect()->route($this->routePrefix . '.index')->with('success', "Content updated successfully");
            }

            return redirect()->route($this->routePrefix . '.index')->with('error', "Something went wrong");
        } catch (ModelNotFoundException $exception) {
            Log::error("MyContentsController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("MyContentsController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Delete specific data.
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id)
    {
        try {
            if (Auth::check() && Auth::user()->user_type == User::ADMIN) {
                if (Gate::denies('delete_teacher_content')) {
                    return redirect()->back()->with('error', 'You are not authorized for this action.');
                }
            }

            $this->setRoutePrefix();
            $teacherContents = TeacherContents::findOrFail($id);
            $this->myContentsService->delete($teacherContents);

            return redirect()->route($this->routePrefix . '.index')->with('success', "Content deleted successfully");
        } catch (ModelNotFoundException $exception) {
            Log::error("MyContentsController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("MyContentsController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.index')->with('error', $exception->getMessage());
        }
    }
}
