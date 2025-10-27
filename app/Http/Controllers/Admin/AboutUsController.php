<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAboutUsRequest;
use App\Http\Requests\UpdateAboutUsRequest;
use App\Models\AboutUsContents;
use App\Traits\Auditable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AboutUsController extends Controller
{
    use Auditable;

    public $layoutFolder = "admin.about-us";

    public function __construct()
    {
    }

    /**
     * Get edit page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function section1()
    {
        try {
            abort_if(Gate::denies('edit_about_us'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $section1 = AboutUsContents::where('section_id', AboutUsContents::ABOUT_DESCRIPTION)->first();

            $data = [
                "section1" => $section1,
            ];

            return view("{$this->layoutFolder}.section-one.one", $data);
        } catch (Exception $exception) {
            Log::error("AboutUsController::section1()", [$exception]);
        }
    }

    /**
     * Update in DB
     *
     * @param UpdateAboutUsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSection1(UpdateAboutUsRequest $request)
    {
        try {
            $image1 = $image2 = $image3 = null;
            // Define base directory
            $aboutUsDir = 'web/about-us/section-one';

            // Ensure the new directory exists
            Storage::makeDirectory($aboutUsDir);

            // Save image
            if ($request->hasFile('image_1')) {
                $image1 = $this->saveImage($request->file('image_1'), $aboutUsDir, 'image-1.' . $request->file('image_1')->getClientOriginalExtension());
            }

            if ($request->hasFile('image_2')) {
                $image2 = $this->saveImage($request->file('image_2'), $aboutUsDir, 'image-2.' . $request->file('image_2')->getClientOriginalExtension());
            }

            if ($request->hasFile('image_3')) {
                $image3 = $this->saveImage($request->file('image_3'), $aboutUsDir, 'image-3.' . $request->file('image_3')->getClientOriginalExtension());
            }

            // Prepare the data array for the updateOrInsert
            $data = [
                "section_id"   => AboutUsContents::ABOUT_DESCRIPTION,
                // "section_title"   => $request->section_title,
                // "section_subtitle"   => $request->section_subtitle,
                "title"   => $request->title,
                "subtitle"   => $request->subtitle,
                "description"   => $request->description,
                "updated_by"    => Auth::user()->id ?? null,
            ];

            if ($image1 || $image2 || $image3) {
                if ($image1) {
                    $data['image_1'] = $image1;
                }

                if ($image2) {
                    $data['image_2'] = $image2;
                }

                if ($image3) {
                    $data['image_3'] = $image3;
                }
            }

            // Perform the update or insert
            $aboutUsUpdate = AboutUsContents::updateOrCreate(
                ["section_id" => AboutUsContents::ABOUT_DESCRIPTION],
                $data
            );

            if ($aboutUsUpdate) {
                $this->auditLogEntry("about-us-section-1:updated", $aboutUsUpdate->id, 'about-us-section-1-update', $aboutUsUpdate);
                return redirect()->route('admin.about-us.section-1')->with('success', "About Us section updated successfully");
            }

            return redirect()->route('admin.about-us.section-1')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("AboutUsController::updateSection1()", [$exception]);
            return redirect()->route('admin.about-us.section-1')->with('error', $exception->getMessage());
        }
    }

    /**
     * Get edit page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function section2()
    {
        try {
            abort_if(Gate::denies('edit_about_us'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $section2 = AboutUsContents::where('section_id', AboutUsContents::APPLICATION_PROCEDURE)->paginate(10);

            $data = [
                "sectionContents" => $section2,
            ];

            return view("{$this->layoutFolder}.section-two.index", $data);
        } catch (Exception $exception) {
            Log::error("AboutUsController::section2()", [$exception]);
        }
    }

    /**
     * Get create page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function createSection2()
    {
        try {
            abort_if(Gate::denies('create_about_us'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $data = [];

            return view("{$this->layoutFolder}.section-two.create", $data);
        } catch (Exception $exception) {
            Log::error("AboutUsController::createSection2()", [$exception]);
        }
    }

    /**
     * Store in DB
     *
     * @param StoreAboutUsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSection2(StoreAboutUsRequest $request)
    {
        try {
            $sectionCreate = [
                'section_id' => AboutUsContents::APPLICATION_PROCEDURE,
                'title' => $request->title,
                'description' => $request->description,
                'updated_by' => Auth::user()->id ?? null,
            ];

            $iconImage = null;
            // Define base directory
            $aboutUsDir = 'web/about-us/section-two';

            // Ensure the new directory exists
            Storage::makeDirectory($aboutUsDir);

            // Conditionally add image if it exists
            if ($request->hasFile('icon_image')) {
                $iconImage = $this->saveImage($request->file('icon_image'), $aboutUsDir, 'icon-image-' . time() . '.' . $request->file('icon_image')->getClientOriginalExtension());
                $sectionCreate['icon_image'] = Storage::url($iconImage);
            }

            $aboutUsCreate = AboutUsContents::create($sectionCreate);

            if ($aboutUsCreate) {
                $this->auditLogEntry("about-us-section-2:created", $aboutUsCreate->id, 'about-us-section-2-create', $aboutUsCreate);
                return redirect()->route('admin.about-us.section-2')->with('success', "About Us section data created successfully");
            }

            return redirect()->route('admin.about-us.section-2')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("AboutUsService::storeSection2()", [$exception]);
            return redirect()->route('admin.about-us.section-2')->with('error', $exception->getMessage());
        }
    }

    /**
     * Get edit page.
     * @param integer $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editSection2(int $id)
    {
        try {
            abort_if(Gate::denies('edit_about_us'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $section2 = AboutUsContents::where("id", $id)->first();

            $data = [
                "section2" => $section2,
            ];

            return view("{$this->layoutFolder}.section-two.edit", $data);
        } catch (Exception $exception) {
            Log::error("AboutUsController::editSection2()", [$exception]);
        }
    }

    /**
     * Update in DB
     *
     * @param integer $id
     * @param UpdateAboutUsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSection2(UpdateAboutUsRequest $request, int $id)
    {
        try {
            $sectionContent = AboutUsContents::find($id);

            $iconImage = null;
            // Define base directory
            $aboutUsDir = 'web/about-us/section-two';

            // Ensure the new directory exists
            Storage::makeDirectory($aboutUsDir);

            // Save image
            if ($request->hasFile('icon_image')) {
                $iconImage = $this->saveImage($request->file('icon_image'), $aboutUsDir, 'icon-image-' . time() . '.' . $request->file('icon_image')->getClientOriginalExtension());
            }

            // Prepare the data array for the update
            $sectionContent->title = $request->title;
            $sectionContent->description = $request->description;
            $sectionContent->updated_by = Auth::user()->id ?? null;

            // Conditionally add image if it exists
            if ($iconImage) {
                $sectionContent->icon_image = $iconImage;
            }

            // Perform the update or insert
            $aboutUsUpdate = $sectionContent->save();

            if ($aboutUsUpdate) {
                $this->auditLogEntry("about-us-section-2:updated", $sectionContent->id, 'about-us-section-2-update', $sectionContent);
                return redirect()->route('admin.about-us.section-2')->with('success', "About Us section updated successfully");
            }

            return redirect()->route('admin.about-us.section-2')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("AboutUsController::updateSection2()", [$exception]);
            return redirect()->route('admin.about-us.section-2')->with('error', $exception->getMessage());
        }
    }

    /**
     * Get edit page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function section3()
    {
        try {
            abort_if(Gate::denies('edit_about_us'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $section3 = AboutUsContents::where('section_id', AboutUsContents::COURSE_FEATURES)->paginate(10);

            $data = [
                "sectionContents" => $section3,
            ];

            return view("{$this->layoutFolder}.section-three.index", $data);
        } catch (Exception $exception) {
            Log::error("AboutUsController::section3()", [$exception]);
        }
    }

    /**
     * Get create page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function createSection3()
    {
        try {
            abort_if(Gate::denies('create_about_us'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $data = [];

            return view("{$this->layoutFolder}.section-three.create", $data);
        } catch (Exception $exception) {
            Log::error("AboutUsController::createSection3()", [$exception]);
        }
    }

    /**
     * Store in DB
     *
     * @param StoreAboutUsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSection3(StoreAboutUsRequest $request)
    {
        try {
            $sectionCreate = [
                'section_id' => AboutUsContents::COURSE_FEATURES,
                'title' => $request->title,
                'description' => $request->description,
                'updated_by' => Auth::user()->id ?? null,
            ];

            $iconImage = null;
            // Define base directory
            $aboutUsDir = 'web/about-us/section-two';

            // Ensure the new directory exists
            Storage::makeDirectory($aboutUsDir);

            // Conditionally add image if it exists
            if ($request->hasFile('icon_image')) {
                $iconImage = $this->saveImage($request->file('icon_image'), $aboutUsDir, 'icon-image-' . time() . '.' . $request->file('icon_image')->getClientOriginalExtension());
                $sectionCreate['icon_image'] = Storage::url($iconImage);
            }

            $aboutUsCreate = AboutUsContents::create($sectionCreate);

            if ($aboutUsCreate) {
                $this->auditLogEntry("about-us-section-3:created", $aboutUsCreate->id, 'about-us-section-3-create', $aboutUsCreate);
                return redirect()->route('admin.about-us.section-3')->with('success', "About Us section data created successfully");
            }

            return redirect()->route('admin.about-us.section-3')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("AboutUsService::storeSection3()", [$exception]);
            return redirect()->route('admin.about-us.section-3')->with('error', $exception->getMessage());
        }
    }

    /**
     * Get edit page.
     * @param integer $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editSection3(int $id)
    {
        try {
            abort_if(Gate::denies('edit_about_us'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $section3 = AboutUsContents::where("section_id", $id)->first();

            $data = [
                "section3" => $section3,
            ];

            return view("{$this->layoutFolder}.section-three.edit", $data);
        } catch (Exception $exception) {
            Log::error("AboutUsController::editSection3()", [$exception]);
        }
    }

    /**
     * Update in DB
     *
     * @param integer $id
     * @param UpdateAboutUsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSection3(UpdateAboutUsRequest $request, int $id)
    {
        try {
            $sectionContent = AboutUsContents::find($id);

            $iconImage = null;
            // Define base directory
            $aboutUsDir = 'web/about-us/section-three';

            // Ensure the new directory exists
            Storage::makeDirectory($aboutUsDir);

            // Save image
            if ($request->hasFile('icon_image')) {
                $iconImage = $this->saveImage(
                    $request->file('icon_image'),
                    $aboutUsDir,
                    'icon-image-' . time() . '.' . $request->file('icon_image')->getClientOriginalExtension()
                );
            }

            // Prepare the data array for the update
            $sectionContent->title = $request->title;
            $sectionContent->description = $request->description;
            $sectionContent->updated_by = Auth::user()->id ?? null;

            // Conditionally add image if it exists
            if ($iconImage) {
                $sectionContent->icon_image = $iconImage;
            }

            // Perform the update or insert
            $aboutUsUpdate = $sectionContent->save();

            if ($aboutUsUpdate) {
                $this->auditLogEntry("about-us-section-3:updated", $sectionContent->id, 'about-us-section-3-update', $sectionContent);
                return redirect()->route('admin.about-us.section-3')->with('success', "About Us section updated successfully");
            }

            return redirect()->route('admin.about-us.section-3')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("AboutUsController::updateSection3()", [$exception]);
            return redirect()->route('admin.about-us.section-3')->with('error', $exception->getMessage());
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
            Log::error("AboutUsController::saveImage()", [$exception]);
            return null;
        }
    }

    /**
     * Delete.
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id)
    {
        try {
            abort_if(Gate::denies('delete_about_us'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $aboutUsContents = AboutUsContents::findOrFail($id);
            DB::beginTransaction();

            $aboutUsContents->deleted_by = Auth::user()->id;
            $aboutUsContents->save();

            $aboutUsContents->delete();
            DB::commit();

            return redirect()->back()->with('success', "Content deleted successfully");
        } catch (ModelNotFoundException $exception) {
            Log::error("AboutUsController::delete()", [$exception]);
            return redirect()->back()->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("AboutUsController::delete()", [$exception]);
            DB::rollback();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
