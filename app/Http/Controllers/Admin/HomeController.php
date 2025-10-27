<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateHomeRequest;
use App\Models\AboutUsContents;
use App\Models\HomeContents;
use App\Traits\Auditable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    use Auditable;

    public $layoutFolder = "admin.home";

    public function __construct()
    {
    }

    /**
     * Get edit page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function heroSection()
    {
        try {
            abort_if(Gate::denies('edit_home'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $heroSection = HomeContents::where('section_id', HomeContents::HERO_SECTION)->first();

            $data = [
                "heroSection" => $heroSection,
            ];

            return view("{$this->layoutFolder}.hero-section", $data);
        } catch (Exception $exception) {
            Log::error("HomeController::heroSection()", [$exception]);
        }
    }

    /**
     * Update in DB
     *
     * @param UpdateHomeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateHeroSection(UpdateHomeRequest $request)
    {
        try {
            // Prepare the data array for the updateOrInsert
            $data = [
                "title"   => $request->title,
                "subtitle"   => $request->subtitle,
                "description"   => $request->description,
                "updated_by"    => Auth::user()->id ?? null,
            ];

            $bgImage = null;
            // Define base directory
            $directory = 'web/hero-section';

            // Ensure the new directory exists
            Storage::makeDirectory($directory);

            // Save image
            if ($request->hasFile('bg_image')) {
                $bgImage = $this->saveImage($request->file('bg_image'), $directory, 'bg-image.' . $request->file('bg_image')->getClientOriginalExtension());
            }

            if ($bgImage) {
                $data['image'] = $bgImage;
            }

            // Perform the update or insert
            $homeUpdate = HomeContents::updateOrCreate(
                ["section_id" => HomeContents::HERO_SECTION],
                $data
            );

            if ($homeUpdate) {
                $this->auditLogEntry("home-hero-section-:updated", $homeUpdate->id, 'home-hero-section--update', $homeUpdate);
                return redirect()->route('admin.home.hero-section')->with('success', "Home page hero section updated successfully");
            }

            return redirect()->route('admin.home.hero-section')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("HomeController::updateHeroSection()", [$exception]);
            return redirect()->route('admin.home.hero-section')->with('error', $exception->getMessage());
        }
    }

    /**
     * Get edit page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function campaignSection()
    {
        try {
            abort_if(Gate::denies('edit_home'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $campaignSection = HomeContents::where('section_id', HomeContents::CAMPAIGN_SECTION)->first();

            $data = [
                "campaignSection" => $campaignSection,
            ];

            return view("{$this->layoutFolder}.campaign-section", $data);
        } catch (Exception $exception) {
            Log::error("HomeController::campaignSection()", [$exception]);
        }
    }

    /**
     * Update in DB
     *
     * @param UpdateHomeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCampaignSection(UpdateHomeRequest $request)
    {
        try {
            // Prepare the data array for the updateOrInsert
            $data = [
                "title"   => $request->title,
                "subtitle"   => $request->subtitle,
                "updated_by"    => Auth::user()->id ?? null,
            ];

            // Perform the update or insert
            $homeUpdate = HomeContents::updateOrCreate(
                ["section_id" => HomeContents::CAMPAIGN_SECTION],
                $data
            );

            if ($homeUpdate) {
                $this->auditLogEntry("home-campaign-section-:updated", $homeUpdate->id, 'home-campaign-section--update', $homeUpdate);
                return redirect()->route('admin.home.campaign-section')->with('success', "Home page campaign section updated successfully");
            }

            return redirect()->route('admin.home.campaign-section')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("HomeController::updateCampaignSection()", [$exception]);
            return redirect()->route('admin.home.campaign-section')->with('error', $exception->getMessage());
        }
    }
}
