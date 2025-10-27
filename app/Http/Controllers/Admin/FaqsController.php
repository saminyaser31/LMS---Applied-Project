<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFaqsRequest;
use App\Http\Requests\UpdateFaqsRequest;
use App\Models\Faq;
use App\Services\Admin\FaqsService;
use App\Traits\Auditable;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FaqsController extends Controller
{
    use Auditable;

    /**
     * @var FaqsService
     */
    public $faqsService;

    public $layoutFolder = "admin.faqs";

    public function __construct(FaqsService $faqsService)
    {
        $this->faqsService = $faqsService;
    }

    /**
     * Get faqs page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            abort_if(Gate::denies('access_faq'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $query = Faq::query()->whereNull('faqs.deleted_at');
            $result = (new FaqsService)->filter($request, $query);

            $data = [
                "faqs" => isset($result['faqs']) ? $result['faqs'] : [],
                "totalFaqs" => isset($result['totalFaqs']) ? $result['totalFaqs'] : 0,
                "faqStatus" => Faq::STATUS_SELECT,
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (AuthorizationException $exception) {
            // Log the error if needed
            Log::error("FaqsController::index() - Unauthorized access", [$exception]);

            // Redirect to the 'home' route with an optional error message
            return redirect()->route('home')->with('error', 'You do not have access to this page.');
        } catch (Exception $exception) {
            Log::error("FaqsController::index()", [$exception]);
        }
    }

    /**
     * Get specific faqs page.
     *
     * @param integer $id
     * @return \Illuminate\Http\View|\Illuminate\Http\RedirectResponse
     */
    public function show(int $id)
    {
        try {
            abort_if(Gate::denies('show_faq'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $faq = Faq::findOrFail($id);

            $data = [
                "faq" => $faq,
                "faqStatus" => Faq::STATUS_SELECT,
            ];

            return view("{$this->layoutFolder}.show", $data);
        } catch (ModelNotFoundException $e) {
            Log::error("FaqsController::show()", [$e]);
            return redirect()->route('admin.faqs.index')->with('error', $e->getMessage());
        } catch (Exception $exception) {
            Log::error("FaqsController::show()", [$exception]);
        }
    }

    /**
     * Get faqs create page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            abort_if(Gate::denies('create_faq'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            return view("{$this->layoutFolder}.create");
        } catch (Exception $exception) {
            Log::error("FaqsController::create()", [$exception]);
        }
    }

    /**
     * Store faqs in DB
     *
     * @param StoreFaqsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreFaqsRequest $request)
    {
        try {
            // dd($request);
            $faq = $this->faqsService->store($request);

            if ($faq) {
                $this->auditLogEntry("faq:created", $faq->id, 'faq-create', $faq);
                return redirect()->route('admin.faqs.index')->with('success', "FAQ added successfully");
            }

            return redirect()->route('admin.faqs.index')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("FaqsController::store()", [$exception]);
            return redirect()->route('admin.faqs.index')->with('error', [$exception->getMessage()]);
        }
    }

    /**
     * Get faqs edit page.
     *
     * @param integer $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            abort_if(Gate::denies('edit_faq'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $faq = Faq::findOrFail($id);

            $data = [
                "faq" => $faq,
                "faqStatus" => Faq::STATUS_SELECT,
            ];

            return view("{$this->layoutFolder}.edit", $data);
        } catch (ModelNotFoundException $exception) {
            Log::error("FaqsController::edit()", [$exception]);
            return redirect()->route('admin.faqs.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("FaqsController::edit()", [$exception]);
        }
    }

    /**
     * Update faqs in DB
     *
     * @param UpdateFaqsRequest $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateFaqsRequest $request, int $id)
    {
        try {
            $faq = Faq::findOrFail($id);
            $faqUpdate = $this->faqsService->update($request, $faq);
            if ($faqUpdate) {
                $this->auditLogEntry("faq:updated", $faq->id, 'faq-update', $faqUpdate);
                return redirect()->route('admin.faqs.index')->with('success', "FAQ updated successfully");
            }

            return redirect()->route('admin.faqs.index')->with('error', "Something went wrong");
        } catch (ModelNotFoundException $exception) {
            Log::error("FaqsController::update()", [$exception]);
            return redirect()->route('admin.faqs.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("FaqsController::update()", [$exception]);
            return redirect()->route('admin.faqs.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Delete specific faq.
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id)
    {
        try {
            abort_if(Gate::denies('delete_faq'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $faq = Faq::findOrFail($id);
            $this->faqsService->delete($faq);

            return redirect()->route('admin.faqs.index')->with('success', "FAQ deleted successfully");
        } catch (ModelNotFoundException $exception) {
            Log::error("FaqsController::delete()", [$exception]);
            return redirect()->route('admin.faqs.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("FaqsController::delete()", [$exception]);
            return redirect()->route('admin.faqs.index')->with('error', $exception->getMessage());
        }
    }
}
