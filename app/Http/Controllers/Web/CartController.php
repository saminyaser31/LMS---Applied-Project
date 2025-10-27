<?php

namespace App\Http\Controllers\Web;

use Exception;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            $courses = Cart::with(['course:id,title,price'])->where('user_id', Auth::user()->id)->get();

            $totalPrice = $courses->sum(function ($cart) {
                return $cart->course->discounted_price ?? ($cart->course->price ?? 0);
            });

            $data = [
                "courses" => $courses,
                "totalPrice" => $totalPrice,
            ];

            return view('web.cart', $data);
        } catch (Exception $exception) {
            Log::error("CartController::index()", [$exception]);
            return redirect()->back()->with('error', "Something Went wrong!");
        }
    }

    /**
     * store
     *
     * @param integer $courseId
     */
    public function store(int $courseId)
    {
        try {
            $cartExists = Cart::where('user_id', Auth::user()->id)
            ->where('course_id', $courseId)
            ->exists();

            if ($cartExists) {
                return redirect()->route('wishlist')->with('success', "Course already exists in cart");
            }

            $params = [
                'user_id' => Auth::user()->id,
                'course_id' => $courseId,
            ];
            $addToCart = Cart::create($params);

            if ($addToCart) {
                return redirect()->route('cart')->with('success', "Course added to cart sucessfully");
            }

            return redirect()->back()->with('error', "Something Went wrong!");
        } catch (Exception $exception) {
            Log::error("CartController::store()", [$exception]);
            return redirect()->back()->with('error', "Something Went wrong!");
        }
    }

    /**
     * delete
     *
     * @param integer $id
     */
    public function delete(int $id)
    {
        try {
            $delete = Cart::where('id', $id)->delete();

            if ($delete) {
                return redirect()->route('cart')->with('success', "Course deleted from cart successfully.");
            }

            return redirect()->back()->with('error', "Something Went wrong!");
        } catch (Exception $exception) {
            Log::error("CartController::delete()", [$exception]);
            return redirect()->back()->with('error', "Something Went wrong!");
        }
    }
}
