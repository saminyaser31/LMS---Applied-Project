<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateContactUsRequest;
use App\Models\ContactMessages;
use App\Models\ContactUs;
use App\Traits\Auditable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ContactUsController extends Controller
{
    use Auditable;

    public $layoutFolder = "admin.contact-us";

    public function __construct()
    {
    }

    /**
     * Get contact-us page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $contactMessages = ContactMessages::paginate(10);

            $data = [
                "contactMessages" => $contactMessages,
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("ContactUsController::index()", [$exception]);
        }
    }

    /**
     * Get edit page.
     *
     * @param integer $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit()
    {
        try {
            abort_if(Gate::denies('edit_contact_us'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $contactUs = ContactUs::first();

            $data = [
                "contactUs" => $contactUs,
            ];

            return view("{$this->layoutFolder}.section-contents", $data);
        } catch (Exception $exception) {
            Log::error("ContactUsController::edit()", [$exception]);
        }
    }

    /**
     * Update contact-us in DB
     *
     * @param UpdateContactUsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateContactUsRequest $request)
    {
        try {
            $formImage = null;
            // Define base directory
            $contactUsDir = 'web/contact-us';

            // Ensure the new directory exists
            Storage::makeDirectory($contactUsDir);

            // Save image
            if ($request->hasFile('form_image')) {
                $formImage = $this->saveImage($request->file('form_image'), $contactUsDir, 'form-image.' . $request->file('form_image')->getClientOriginalExtension());
            }

            // Prepare the data array for the updateOrInsert
            $data = [
                "title"   => $request->title,
                "form_title"   => $request->form_title,
                "form_subtitle" => $request->form_subtitle,
                "phone_no_1"    => $request->phone_no_1,
                "phone_no_2"    => $request->phone_no_2,
                "email_1"       => $request->email_1,
                "email_2"       => $request->email_2,
                "location_1"    => $request->location_1,
                "location_2"    => $request->location_2,
                "updated_by"    => Auth::user()->id ?? null,
            ];

            // Conditionally add form_image if it exists
            if ($formImage) {
                $data['form_image'] = $formImage;
            }

            // Perform the update or insert
            $contactUsUpdate = ContactUs::updateOrCreate(
                ["id" => 1],
                $data
            );

            // $contactUsUpdate = DB::table('contact_us')->updateOrInsert(
            //     ["id" => 1], // Condition to find the record
            //     $data // Data to insert or update
            // );

            if ($contactUsUpdate) {
                $this->auditLogEntry("contact-us:updated", $contactUsUpdate->id, 'contact-us-update', $contactUsUpdate);
                return redirect()->route('admin.contact-us.index')->with('success', "Contact Us Info updated successfully");
            }

            return redirect()->route('admin.contact-us.index')->with('error', "Something went wrong!");
        } catch (ModelNotFoundException $exception) {
            Log::error("ContactUsController::update()", [$exception]);
            return redirect()->route('admin.contact-us.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("ContactUsController::update()", [$exception]);
            return redirect()->route('admin.contact-us.index')->with('error', $exception->getMessage());
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
            Log::error("ContactUsController::saveImage()", [$exception]);
            return null;
        }
    }
}
