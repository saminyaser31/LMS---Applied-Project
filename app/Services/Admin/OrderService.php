<?php

namespace App\Services\Admin;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Order;
use App\Models\User;
use App\Traits\Auditable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    use Auditable;

    /**
     * filter order
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

            $orders = $query->orderBy($filterOption, $orderBy)->paginate($paginate);

            return [
                "orders" => $orders,
                "totalOrders" => $orders->total(),
            ];
        } catch (Exception $exception) {
            Log::error("OrderService::filter()", [$exception]);
            return [];
        }
    }

    /**
     * filter order by request params
     *
     * @param Request $request
     * @param $query
     * @return object
     */
    public function filterByRequest(Request $request, $query)
    {
        try {
            if ($request->filled('student_id')) {
                $query->where('student_id', $request->student_id);
            }

            if ($request->filled('order_type')) {
                $query->where('order_type', $request->order_type);
            }

            if ($request->filled('order_status')) {
                $query->where('orders.status', $request->order_status);
            }

            if (isset($request->created_time_from) || isset($request->created_time_to)) {
                $fromDate = $request->created_time_from;
                $toDate = $request->created_time_to;

                $query->where(function ($query) use ($fromDate, $toDate) {
                    if ($fromDate && $toDate) {
                        $query->whereBetween('created_at', [$fromDate, $toDate]);
                    } else {
                        if ($fromDate) {
                            $query->whereDate('created_at', '>=', $fromDate);
                        }

                        if ($toDate) {
                            $query->whereDate('created_at', '<=', $toDate);
                        }
                    }
                });
            }

            return $query;
        } catch (Exception $exception) {
            Log::error("OrderService::filterByRequest()", [$exception]);
            return [];
        }
    }

    /**
     * store order
     *
     * @param StoreOrderRequest $request
     * @return \App\Models\Order|null
     */
    public function store(StoreOrderRequest $request): Order|null
    {
        try {
            $orderCreate = [
                'student_id' => $request->student_id,
                'order_type' => $request->order_type,
                'transaction_id' => $request->transaction_id ?? null,
                'total' => $this->calculateTotalCost($request),
                'grand_total' => $this->calculateTotalCost($request),
                'remarks' => $request->remarks,
                'status' => Order::STATUS_ENABLE, // Set default status or from request
                'created_by' => (Auth::user() && Auth::user()->user_type == User::ADMIN) ? Auth::user()->id : null,
            ];

            DB::beginTransaction();
            $order = Order::create($orderCreate);

            $this->storeOrderCourses($request, $order->id);

            DB::commit();

            return $order;
        } catch (Exception $exception) {
            Log::error("OrderService::store()", [$exception]);
            DB::rollback();
            return null;
        }
    }

    /**
     * store order courses
     *
     * @param object $request
     * @param integer $orderId
     */
    private function storeOrderCourses($request, $orderId)
    {
        try {
            $orderCourses = [];
            $teachers = Course::whereIn('id', $request->course_id)
            ->pluck('teacher_id', 'id')
            ->toArray();

            foreach ($request->course_id as $key => $value) {
                $orderCourses[] = [
                    'order_id' => $orderId,
                    'course_id' => $value,
                    'teacher_id' => $teachers[$value],
                    'status' => CourseEnrollment::STATUS_ENABLE,
                ];
            }

            CourseEnrollment::insert($orderCourses);
        } catch (Exception $exception) {
            Log::error("OrderService::storeOrderCourses()", [$exception]);
            throw $exception;
        }
    }

    /**
     * update order
     *
     * @param UpdateOrderRequest $request
     * @param Order $order
     * @return Order|null
     */
    public function update(UpdateOrderRequest $request, Order $order): Order|null
    {
        try {
            DB::beginTransaction();
            $order->status = $request->status;
            $order->remarks = $request->remarks;
            $order->updated_by = Auth::user()->id;
            $order->save();

            CourseEnrollment::where('order_id', $order->id)->update(['status' => $request->status]);

            DB::commit();

            return $order;
        } catch (Exception $exception) {
            Log::error("OrderService::update()", [$exception]);
            DB::rollback();
            return null;
        }
    }

    /**
     * delete specific Order
     *
     * @param Order $order
     * @return void
     */
    public function delete(Order $order)
    {
        try {
            DB::beginTransaction();

            // Update the deleted_by column with the current user's ID
            $order->deleted_by = Auth::user()->id;
            $order->save();

            $order->delete();
            CourseEnrollment::where('order_id', $order->id)->delete();

            DB::commit();

            // Clear the relevant cache

            $this->auditLogEntry("order:deleted", $order->id, 'order-deleted', $order);
        } catch (Exception $exception) {
            Log::error("OrderService::delete()", [$exception]);
            DB::rollback();
        }
    }

    public function calculateTotalCost(Request $request)
    {
        try {
            $courseIds = $request->input('course_ids', []);
            $totalCost = Course::whereIn('id', $courseIds)->sum('price');

            return $totalCost;
        } catch (Exception $exception) {
            Log::error("OrderService::calculateTotalCost()", [$exception]);
            return null;
        }
    }
}
