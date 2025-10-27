<?php

namespace App\Http\Controllers\Web;

use Exception;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseEnrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CheckoutRequest;

class CheckoutController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            $cartCourses = Cart::with(['course:id,title,price'])->where('user_id', Auth::user()->id)->get();
            $data = [
                "cartCourses" => $cartCourses,
                "totalPrice" => $cartCourses->sum(function ($course) {
                    return $course->course->discounted_price ?? $course->course->price;
                }),
            ];

            return view('web.checkout', $data);
        } catch (Exception $exception) {
            Log::error("CheckoutController::index()", [$exception]);
            return redirect()->back()->with('error', "Something Went wrong!");
        }
    }

    /**
     * store order
     *
     * @param object CheckoutRequest $request
     */
    public function store(CheckoutRequest $request)
    {
        try {
            $orderCreate = [
                'student_id' => Auth::user()->id,
                'order_type' => Order::NEW_PAID_ORDER,
                'transaction_id' => $request->transaction_id ?? null,
                'bkash_no' => $request->bkash_no ?? null,
                'total' => $request->total,
                'grand_total' => $request->total,
                'status' => Order::STATUS_PENDING,
                'created_at' => Carbon::now(),
            ];

            DB::beginTransaction();
            $order = Order::create($orderCreate);

            if (!$order) {
                DB::rollBack();
                return redirect()->back()->withInput()->with('error', "Order added failed");
            }

            $this->storeOrderCourses($request, $order->id);
            $this->clearCart();

            DB::commit();

            return view('web.checkout-success');
            // return redirect()->route('checkout.success');
        } catch (Exception $exception) {
            Log::error("CheckoutController::store()", [$exception]);
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', "Something Went wrong!");
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
                    'status' => CourseEnrollment::STATUS_PENDING,
                    'created_at' => Carbon::now(),
                ];
            }

            CourseEnrollment::insert($orderCourses);
        } catch (Exception $exception) {
            Log::error("OrderService::storeOrderCourses()", [$exception]);
            throw $exception;
        }
    }

    /**
     * clear cart
     */
    private function clearCart()
    {
        try {
            Cart::where('user_id', Auth::user()->id)->delete();
        } catch (Exception $exception) {
            Log::error("CheckoutController::clearCart()", [$exception]);
            throw $exception;
        }
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function success()
    {
        try {
            return view('web.checkout-success');
        } catch (Exception $exception) {
            Log::error("CheckoutController::success()", [$exception]);
            return redirect()->back()->with('error', "Something Went wrong!");
        }
    }
}
