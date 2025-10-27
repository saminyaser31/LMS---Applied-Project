<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AboutUsContents;
use App\Models\Teacher\Teachers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AboutUsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            $sectionOneContent = AboutUsContents::where("section_id", AboutUsContents::ABOUT_DESCRIPTION)->first();
            $sectionTwoContent = AboutUsContents::where("section_id", AboutUsContents::APPLICATION_PROCEDURE)->get();
            $sectionThreeContent = AboutUsContents::where("section_id", AboutUsContents::COURSE_FEATURES)->get();
            // $teachers = Teachers::limit(4)->get();
            $teachers = Teachers::select('id','user_id','image','first_name','last_name')->get();

            $data = [
                "sectionOneContent" => $sectionOneContent,
                "sectionTwoContent" => $sectionTwoContent,
                "sectionThreeContent" => $sectionThreeContent,
                "teachers" => $teachers,
            ];

            return view('web.about-us', $data);
        } catch (Exception $exception) {
            Log::error("AboutUsController::index()", [$exception]);
            return redirect()->route('about-us')->with('error', "Something Went wrong!");
        }
    }
}
