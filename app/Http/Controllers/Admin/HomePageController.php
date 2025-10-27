<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePrivacyPolicyRequest;
use App\Models\PrivacyPolicy;
use App\Traits\Auditable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomePageController extends Controller
{
    use Auditable;

    public $layoutFolder = "admin.privacy-policy";

    public function __construct()
    {
    }

    /**
     * Get privacy-policy page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $privacyPolicy = PrivacyPolicy::first();

            $data = [
                "privacyPolicy" => $privacyPolicy,
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("PrivacyPolicyController::index()", [$exception]);
        }
    }

    /**
     * Update privacy-policy in DB
     *
     * @param UpdatePrivacyPolicyRequest $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePrivacyPolicyRequest $request, int $id)
    {
        try {
            $privacyPolicyUpdate = PrivacyPolicy::updateOrInsert(
                [
                    "id" => 1,
                ],
                [
                    "title" => $request->title,
                    "description" => $request->description,
                ]
            );

            if ($privacyPolicyUpdate) {
                $this->auditLogEntry("privacy-policy:updated", $privacyPolicyUpdate->id, 'privacy-policy-update', $privacyPolicyUpdate);
                return redirect()->route('admin.privacy-policy.index')->with('success', "Privacy Policy Info updated successfully");
            }

            return redirect()->route('admin.privacy-policy.index')->with('error', "Something went wrong!");
        } catch (ModelNotFoundException $exception) {
            Log::error("PrivacyPolicyController::update()", [$exception]);
            return redirect()->route('admin.privacy-policy.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("PrivacyPolicyController::update()", [$exception]);
            return redirect()->route('admin.privacy-policy.index')->with('error', $exception->getMessage());
        }
    }
}
