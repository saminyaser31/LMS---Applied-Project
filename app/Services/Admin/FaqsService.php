<?php

namespace App\Services\Admin;

use App\Http\Requests\StoreFaqsRequest;
use App\Http\Requests\UpdateFaqsRequest;
use App\Models\Faq;
use App\Traits\Auditable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FaqsService
{
    use Auditable;

    /**
     * filter faq
     *
     * @param Request $request
     * @param $query
     * @return array
     */
    public function filter(Request $request, $query)
    {
        try {
            $query = $this->filterByRequest($request, $query);

            $orderBy = $request->order_by ?? 'DESC';
            $filterOption = $request->filter_option ?? 'id';
            $paginate = $request->paginate ?? 10;

            $faqs = $query->orderBy($filterOption, $orderBy)->paginate($paginate);

            return [
                "faqs" => $faqs,
                "totalFaqs" => $faqs->total(),
            ];
        } catch (Exception $exception) {
            Log::error("FaqsService::filter()", [$exception]);
            return [];
        }
    }

    /**
     * filter faq by request params
     *
     * @param Request $request
     * @param $query
     * @return object
     */
    public function filterByRequest(Request $request, $query)
    {
        try {
            if ($request->filled('title')) {
                $query->where('title', 'LIKE', '%' . $request->title . '%');
            }

            // Filter by status
            if ($request->filled('faqs_status')) {
                $query->where('status', $request->faqs_status);
            }

            return $query;
        } catch (Exception $exception) {
            Log::error("FaqsService::filterByRequest()", [$exception]);
            return [];
        }
    }

    /**
     * store faq
     *
     * @param StoreFaqsRequest $request
     * @return \App\Models\Faq|null
     */
    public function store(StoreFaqsRequest $request): Faq|null
    {
        try {
            $faqCreate = [
                'title' => $request->title,
                'description' => $request->description,
                'status' => Faq::STATUS_ENABLE, // Set default status or from request
            ];

            $faq = Faq::create($faqCreate);

            return $faq;
        } catch (Exception $exception) {
            Log::error("FaqsService::store()", [$exception]);
            return null;
        }
    }

    /**
     * update faq
     *
     * @param UpdateFaqsRequest $request
     * @param Faq $faq
     * @return \App\Models\Faq|null
     */
    public function update(UpdateFaqsRequest $request, $faq): Faq|null
    {
        try {
            $faq->title = $request->title;
            $faq->description = $request->description;
            $faq->status = $request->faq_status;
            $faq->updated_by = Auth::user()->id;

            $faq->save();

            return $faq;
        } catch (Exception $exception) {
            Log::error("FaqsService::update()", [$exception]);
            return null;
        }
    }

    /**
     * delete specific Faq
     *
     * @param Faq $faq
     * @return void
     */
    public function delete(Faq $faq)
    {
        try {
            DB::beginTransaction();

            // Update the deleted_by column with the current user's ID
            $faq->deleted_by = Auth::user()->id;
            $faq->title = $faq->title . '-deleted:' . Carbon::now()->format('Y-m-d H:i:s');
            $faq->save();

            $faq->delete();
            DB::commit();

            // Clear the relevant cache

            $this->auditLogEntry("faq:deleted", $faq->id, 'faq-deleted', $faq);
        } catch (Exception $exception) {
            Log::error("FaqsService::delete()", [$exception]);
            DB::rollback();
        }
    }
}
