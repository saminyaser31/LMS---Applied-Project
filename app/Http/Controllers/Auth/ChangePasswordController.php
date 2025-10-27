<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateChangePasswordRequest;
use App\Models\User;
use App\Services\Teacher\ProfileService;
use App\Traits\Auditable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChangePasswordController extends Controller
{
    use Auditable;

    public $routePrefix = "";

    /**
     * @var ProfileService
     */
    public $profileService;

    public $layoutFolder = "teacher.change-password";

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;

        if (isset(app('admin')->id)) {
            $this->routePrefix = "admin.teachers";
        } else if (isset(app('teacher')->id)) {
            $this->routePrefix = "teacher.change-password";
        } else if (isset(app('student')->id)) {
            $this->routePrefix = "student.change-password";
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
            $user = User::findOrFail(Auth::user()->id);

            $data = [
                "user" => $user,
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (ModelNotFoundException $exception) {
            Log::error("ChangePasswordController::index()", [$exception]);
            return redirect()->route($this->routePrefix . '.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("ChangePasswordController::index()", [$exception]);
            return redirect()->route($this->routePrefix . '.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Update password in DB
     *
     * @param UpdateChangePasswordRequest $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateChangePasswordRequest $request, int $id)
    {
        try {
            $user = User::findOrFail(Auth::user()->id);
            $passwordUpdate = $this->profileService->changePassword($request, $user);
            if ($passwordUpdate) {
                $this->auditLogEntry("password:updated", $user->id, 'password-update', $passwordUpdate);
                return redirect()->route($this->routePrefix . '.index')->with('success', "Password updated successfully");
            }

            return redirect()->route($this->routePrefix . '.index')->with('error', "Something went wrong!");
        } catch (ModelNotFoundException $exception) {
            Log::error("ChangePasswordController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("ChangePasswordController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.index')->with('error', $exception->getMessage());
        }
    }
}
