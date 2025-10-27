<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrivacyPolicyController extends Controller
{
    public $layoutFolder = "web";

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        try {
            $privacyPolicy = PrivacyPolicy::first();

            $data = [
                "privacyPolicy" => $privacyPolicy,
            ];

            return view("{$this->layoutFolder}.privacy-policy", $data);
        } catch (Exception $exception) {
            Log::error("PrivacyPolicyController::index()", [$exception]);
        }
    }
}
