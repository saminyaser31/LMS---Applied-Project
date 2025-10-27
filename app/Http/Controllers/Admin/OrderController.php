<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Course;
use App\Models\Order;
use App\Models\CourseEnrollment;
use App\Models\User;
use App\Services\Admin\OrderService;
use App\Services\ResponseService;
use App\Traits\Auditable;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    use Auditable;

    /**
     * @var OrderService
     */
    public $orderService;

    public $layoutFolder = "admin.order";

    public $routePrefix = "";

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function setRoutePrefix()
    {
        if (isset(app('admin')->id)) {
            $this->routePrefix = "admin";
        }
        else if (isset(app('teacher')->id)) {
            $this->routePrefix = "teacher";
        }
    }

    /**
     * Get page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $this->setRoutePrefix();
            $query = Order::query()
            ->with(['student','createdBy'])
            ->select('id','student_id','order_type','transaction_id','total','status','created_by')
            ->whereNull('deleted_at');

            $result = (new OrderService)->filter($request, $query);

            $students = User::select('id','name','email')
            ->where('user_type', User::STUDENT)
            ->whereNotNull('email_verified_at')
            ->get();

            $data = [
                "orders" => isset($result['orders']) ? $result['orders'] : [],
                "totalOrders" => isset($result['totalOrders']) ? $result['totalOrders'] : 0,
                "orderStatus" => Order::STATUS_SELECT,
                "orderType" => Order::ORDER_TYPE_SELECT,
                "students" => $students,
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("OrderController::index()", [$exception]);
        }
    }

    /**
     * Get create page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            if (Gate::denies('create_order')) {
                return redirect()->back()->with('error', 'You are not authorized to access this page.');
            }

            $this->setRoutePrefix();

            $students = User::select('id','name','email')
            ->where('user_type', User::STUDENT)
            ->whereNotNull('email_verified_at')
            ->get();

            $courses = Course::select('id','title','price','course_start_date')
            ->where('status', Course::STATUS_ENABLE)
            // ->where('course_start_date', '<', Carbon::now())
            ->get();

            $data = [
                "orderType" => Order::ORDER_TYPE_SELECT,
                "students" => $students,
                "courses" => $courses,
            ];

            return view("admin.order.create", $data);
        } catch (Exception $exception) {
            Log::error("OrderController::create()", [$exception]);
        }
    }

    /**
     * Store in DB
     *
     * @param StoreOrderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $this->setRoutePrefix();
            // dd($request);
            $order = $this->orderService->store($request);

            if ($order) {
                $this->auditLogEntry("order:created", $order->id, 'order-create', $order);
                return redirect()->route($this->routePrefix . '.orders.index')->with('success', "Order created successfully!");
            }

            return redirect()->route($this->routePrefix . '.orders.index')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("OrderController::store()", [$exception]);
            return redirect()->route($this->routePrefix . '.orders.index')->with('error', [$exception->getMessage()]);
        }
    }

    /**
     * Get edit page
     *
     * @param integer $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            if (Gate::denies('edit_order')) {
                return redirect()->back()->with('error', 'You are not authorized to access this page.');
            }

            $this->setRoutePrefix();
            $order = Order::findOrFail($id);
            $enrollments = CourseEnrollment::where('order_id', $order->id)->pluck('course_id')->toArray();

            $students = User::select('id','name','email')
            ->where('user_type', User::STUDENT)
            ->whereNotNull('email_verified_at')
            ->get();

            $courses = Course::select('id','title','price','course_start_date')
            ->where('status', Course::STATUS_ENABLE)
            // ->where('course_start_date', '<', Carbon::now())
            ->get();

            $data = [
                "orderType" => Order::ORDER_TYPE_SELECT,
                "students" => $students,
                "courses" => $courses,
                "order" => $order,
                "enrollments" => $enrollments,
                "orderStatus" => Order::STATUS_SELECT,
            ];

            return view("{$this->layoutFolder}.edit", $data);
        } catch (Exception $exception) {
            Log::error("OrderController::edit()", [$exception]);
        }
    }

    /**
     * Update order
     *
     * @param UpdateOrderRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateOrderRequest $request, int $id)
    {
        try {
            $this->setRoutePrefix();
            $order = Order::findOrFail($id);
            $order = $this->orderService->update($request, $order);

            if ($order) {
                $this->auditLogEntry("order:updated", $order->id, 'order-update', $order);
                return redirect()->route($this->routePrefix . '.orders.index')->with('success', "Order updated successfully!");
            }

            return redirect()->route($this->routePrefix . '.orders.index')->with('error', "Something went wrong!");
        } catch (Exception $exception) {
            Log::error("OrderController::update()", [$exception]);
            return redirect()->route($this->routePrefix . '.orders.index')->with('error', [$exception->getMessage()]);
        }
    }

    /**
     * Delete
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id)
    {
        try {
            if (Gate::denies('delete_order')) {
                return redirect()->back()->with('error', 'You are not authorized to access this page.');
            }

            $this->setRoutePrefix();

            $order = Order::findOrFail($id);
            $this->orderService->delete($order);

            return redirect()->route($this->routePrefix . '.orders.index')->with('success', "Order deleted successfully");
        } catch (ModelNotFoundException $exception) {
            Log::error("OrderController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.orders.index')->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            Log::error("OrderController::delete()", [$exception]);
            return redirect()->route($this->routePrefix . '.orders.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Calculate Total Cost
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function calculateTotalCost(Request $request)
    {
        try {
            $courseIds = $request->input('course_ids', []);
            $totalCost = Course::whereIn('id', $courseIds)->sum('price');

            return response()->json([
                "status" => 200,
                "totalCost" => $totalCost,
            ]);
        } catch (Exception $exception) {
            Log::error("OrderController::calculateTotalCost()", [$exception]);
            return response()->json([
                "status" => 500,
                "totalCost" => 0,
                "message" => $exception->getMessage(),
            ]);
        }
    }
}
