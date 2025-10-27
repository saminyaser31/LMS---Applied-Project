<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessages;
use App\Models\ContactUs;
use Exception;
use Illuminate\Support\Facades\Log;

class ContactUsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            $contactUs = ContactUs::first();

            $data = [
                "contactUs" => $contactUs,
            ];

            return view('web.contact-us', $data);
        } catch (Exception $exception) {
            Log::error("ContactUsController::index()", [$exception]);
            return redirect()->route('contact-us')->with('error', "Something Went wrong!");
        }
    }

    /**
     * store
     *
     * @param StoreContactMessageRequest $request
     */
    public function store(StoreContactMessageRequest $request)
    {
        try {
            $contactMessagesCreate = [
                'name' => $request->name,
                'email' => $request->email,
                'phone_no' => $request->phone_no,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => ContactMessages::STATUS_NOT_REPLIED, // Set default status or from request
            ];
            $contactMessages = ContactMessages::create($contactMessagesCreate);

            if ($contactMessages) {
                return redirect()->route('contact-us')->with('success', "Contact Message Info updated successfully");
            }

            return redirect()->route('contact-us')->with('error', "Something Went wrong!");
        } catch (Exception $exception) {
            Log::error("ContactUsController::store()", [$exception]);
            return redirect()->route('contact-us')->with('error', "Something Went wrong!");
        }
    }
}
