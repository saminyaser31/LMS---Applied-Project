<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AboutUsContents;
use App\Models\Course;
use App\Models\HomeContents;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            $heroSection = HomeContents::where("section_id", HomeContents::HERO_SECTION)->first();
            $campaignSection = HomeContents::where("section_id", HomeContents::CAMPAIGN_SECTION)->first();
            $aboutSection = AboutUsContents::where("section_id", AboutUsContents::ABOUT_DESCRIPTION)->first();
            $latestCourses = Course::where('status', Course::STATUS_ENABLE)->limit(6)->orderby("id", "DESC")->get();

            $data = [
                "heroSection" => $heroSection,
                "campaignSection" => $campaignSection,
                "aboutSection" => $aboutSection,
                "latestCourses" => $latestCourses,
            ];

            return view('web.home', $data);
        } catch (Exception $exception) {
            Log::error("HomeController::index()", [$exception]);
            return redirect()->route('home')->with('error', "Something Went wrong!");
        }
    }
}
