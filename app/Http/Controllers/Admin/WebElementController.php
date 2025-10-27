<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateColorRequest;
use App\Http\Requests\UpdateContactUsRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Models\WebColor;
use App\Models\WebImage;
use App\Models\WebMetaContents;
use App\Models\WebTypography;
use App\Traits\Auditable;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class WebElementController extends Controller
{
    use Auditable;

    public $layoutFolder = "admin.web";

    public function __construct()
    {
    }

    /**
     * Get edit page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function image()
    {
        try {
            abort_if(Gate::denies('edit_image'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $webImage = WebImage::first();

            $data = [
                "webImage" => $webImage,
            ];

            return view("{$this->layoutFolder}.image", $data);
        } catch (AuthorizationException $exception) {
            // Log the error if needed
            Log::error("WebElementController::image() - Unauthorized access", [$exception]);

            // Redirect to the 'home' route with an optional error message
            return redirect()->route('home')->with('error', 'You do not have access to this page.');
        } catch (Exception $exception) {
            Log::error("WebElementController::image()", [$exception]);
        }
    }

    /**
     * Update in DB
     *
     * @param UpdateImageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateImage(UpdateImageRequest $request)
    {
        try {
            $logo = $dashboardLogo = $favicon = null;
            // Define base directory
            $directory = 'web/images';

            // Ensure the new directory exists
            Storage::makeDirectory($directory);

            // Save image
            if ($request->hasFile('logo')) {
                $logo = $this->saveImage($request->file('logo'), $directory, 'logo.' . $request->file('logo')->getClientOriginalExtension());
            }

            if ($request->hasFile('dashboard_logo')) {
                $dashboardLogo = $this->saveImage($request->file('dashboard_logo'), $directory, 'dashboard-logo.' . $request->file('dashboard_logo')->getClientOriginalExtension());
            }

            if ($request->hasFile('favicon')) {
                $favicon = $this->saveImage($request->file('favicon'), $directory, 'favicon.' . $request->file('favicon')->getClientOriginalExtension());
            }

            if ($request->hasFile('dashboard_favicon')) {
                $dashboardFavicon = $this->saveImage($request->file('dashboard_favicon'), $directory, 'dashboard-favicon.' . $request->file('dashboard_favicon')->getClientOriginalExtension());
            }

            // Prepare the data array for the updateOrInsert
            $data = [
                "updated_by"    => Auth::user()->id ?? null,
            ];

            // Only proceed if at least one of logo or favicon exists
            if ($logo || $dashboardLogo || $favicon || $dashboardFavicon) {
                // Prepare the data array
                $data = [
                    "updated_by"    => Auth::user()->id ?? null,
                ];

                // Add logo if it exists
                if ($logo) {
                    $data['logo'] = $logo;
                }

                if ($dashboardLogo) {
                    $data['dashboard_logo'] = $dashboardLogo;
                }

                // Add favicon if it exists
                if ($favicon) {
                    $data['favicon'] = $favicon;
                }

                if ($dashboardFavicon) {
                    $data['dashboard_favicon'] = $dashboardFavicon;
                }

                // Perform the update or insert
                $webImageUpdate = WebImage::updateOrCreate(
                    ["id" => 1],
                    $data
                );

                if ($webImageUpdate) {
                    Cache::forget("web-image-cache");
                    $this->auditLogEntry("web-image:updated", $webImageUpdate->id, 'web-image-update', $webImageUpdate);
                    return redirect()->route('admin.web.image')->with('success', "Web Images updated successfully");
                }
            }

            return redirect()->route('admin.web.image')->with('error', "Something went wrong!");
        }  catch (Exception $exception) {
            Log::error("WebElementController::updateImage()", [$exception]);
            return redirect()->route('admin.web.image')->with('error', $exception->getMessage());
        }
    }

    /**
     * Get edit page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function color()
    {
        try {
            abort_if(Gate::denies('edit_color'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $webColor = WebColor::first();

            $data = [
                "webColor" => $webColor,
            ];

            return view("{$this->layoutFolder}.color", $data);
        } catch (Exception $exception) {
            Log::error("WebElementController::color()", [$exception]);
        }
    }

    /**
     * Update in DB
     *
     * @param UpdateColorRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateColor(UpdateColorRequest $request)
    {
        try {
            // Prepare the data array for the updateOrInsert
            $data = [
                "primary_color"   => $request->primary_color,
                "secondary_color"   => $request->secondary_color,
                "updated_by"    => Auth::user()->id ?? null,
            ];

            // Perform the update or insert
            $webColorUpdate = WebColor::updateOrCreate(
                ["id" => 1],
                $data
            );

            if ($webColorUpdate) {
                Cache::forget("web-color-cache");
                $this->auditLogEntry("web-color:updated", $webColorUpdate->id, 'web-color-update', $webColorUpdate);
                return redirect()->route('admin.web.color')->with('success', "Web Color updated successfully");
            }

            return redirect()->route('admin.web.color')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("WebElementController::updateColor()", [$exception]);
            return redirect()->route('admin.web.color')->with('error', $exception->getMessage());
        }
    }

    /**
     * Get edit page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function typography()
    {
        try {
            abort_if(Gate::denies('edit_typography'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $webTypography = WebTypography::first();

            $data = [
                "webTypography" => $webTypography,
            ];

            return view("{$this->layoutFolder}.typography", $data);
        } catch (Exception $exception) {
            Log::error("WebElementController::typography()", [$exception]);
        }
    }

    /**
     * Update in DB
     *
     * @param UpdateContactUsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTypography(UpdateContactUsRequest $request)
    {
        try {
            // Prepare the data array for the updateOrInsert
            $data = [
                "title"   => $request->title,
                "updated_by"    => Auth::user()->id ?? null,
            ];

            // Perform the update or insert
            $typographyUpdate = WebTypography::updateOrCreate(
                ["id" => 1],
                $data
            );

            if ($typographyUpdate) {
                $this->auditLogEntry("typography:updated", $typographyUpdate->id, 'typography-update', $typographyUpdate);
                return redirect()->route('admin.web.typography')->with('success', "Typography updated successfully");
            }

            return redirect()->route('admin.web.typography')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("WebElementController::updateTypography()", [$exception]);
            return redirect()->route('admin.web.typography')->with('error', $exception->getMessage());
        }
    }

    /**
     * Get edit page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function metaContents()
    {
        try {
            abort_if(Gate::denies('edit_meta_contents'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $webMetaContents = WebMetaContents::first();

            $data = [
                "webMetaContents" => $webMetaContents,
            ];

            return view("{$this->layoutFolder}.meta-contents", $data);
        } catch (Exception $exception) {
            Log::error("WebElementController::metaContents()", [$exception]);
        }
    }

    /**
     * Update in DB
     *
     * @param UpdateContactUsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateMetaContents(UpdateContactUsRequest $request)
    {
        try {
            // Prepare the data array for the updateOrInsert
            $data = [
                "title"   => $request->title,
                "updated_by"    => Auth::user()->id ?? null,
            ];

            // Perform the update or insert
            $metaContentsUpdate = WebMetaContents::updateOrCreate(
                ["id" => 1],
                $data
            );

            if ($metaContentsUpdate) {
                $this->auditLogEntry("meta-contents:updated", $metaContentsUpdate->id, 'meta-contents-update', $metaContentsUpdate);
                return redirect()->route('admin.web.meta-contents')->with('success', "Meta Contents updated successfully");
            }

            return redirect()->route('admin.web.meta-contents')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("WebElementController::updateMetaContents()", [$exception]);
            return redirect()->route('admin.web.meta-contents')->with('error', $exception->getMessage());
        }
    }

    /**
     * Save the uploaded image to the specified directory and return the URL.
     *
     * @param \Illuminate\Http\UploadedFile $imageRequest The uploaded image file.
     * @param string $directory The directory where the image should be stored.
     * @param string $imageName The name of the image file to store.
     * @return string|null The URL of the saved image or null if there was an error.
     */
    private function saveImage($imageRequest, $directory, $imageName)
    {
        try {
            $imagePath = $imageRequest->storeAs($directory, $imageName, 'public');
            return Storage::url($imagePath);
        } catch (Exception $exception) {
            Log::error("WebElementController::saveImage()", [$exception]);
            return null;
        }
    }
}
