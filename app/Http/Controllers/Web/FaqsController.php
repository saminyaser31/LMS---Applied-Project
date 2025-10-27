<?php

namespace App\Http\Controllers\Web;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseContents;
use App\Models\Faq;
use App\Models\Teacher\Teachers;
use App\Services\Web\CourseService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FaqsController extends Controller
{
    public $layoutFolder = "web";

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        try {
            $faqs = Faq::where("status", Faq::STATUS_ENABLE)->get();

            $data = [
                "faqs" => $faqs,
            ];

            return view("{$this->layoutFolder}.faqs", $data);
        } catch (Exception $exception) {
            Log::error("FaqsController::index()", [$exception]);
        }
    }
}
