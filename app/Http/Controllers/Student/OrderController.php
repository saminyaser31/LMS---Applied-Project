<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Traits\Auditable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    use Auditable;

    public $layoutFolder = "student.orders";

    public $routePrefix = "";

    public function __construct()
    {
    }

    public function setRoutePrefix()
    {
        if (isset(app('admin')->id)) {
            $this->routePrefix = "admin";
        }
        else if (isset(app('student')->id)) {
            $this->routePrefix = "student";
        }
    }

    /**
     * Get order page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $this->setRoutePrefix();
            $orders = Order::query()
            ->with('courseEnrollment','courseEnrollment.courses','courseEnrollment.courses.courseTeacher')
            ->where(function ($query) {
                if (Auth::user()->user_type == User::STUDENT) {
                    $query->where('student_id', Auth::user()->id);
                }
            })
            ->whereNull('orders.deleted_at')
            ->orderBy('id', 'desc')
            ->paginate(10);

            // dd($orders);

            $data = [
                "orders" => $orders,
                "totalOrders" => $orders->total(),
            ];

            return view("{$this->layoutFolder}.index", $data);
        } catch (Exception $exception) {
            Log::error("OrderController::index()", [$exception]);
        }
    }
}
