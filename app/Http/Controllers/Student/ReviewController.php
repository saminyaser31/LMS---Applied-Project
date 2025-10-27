<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Traits\Auditable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    use Auditable;

    public $layoutFolder = "student.reviews";

    /**
     * Get review page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $reviews = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);

            $data = [
                "reviews" => $reviews,
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("ProfileController::index()", [$exception]);
        }
    }
}
