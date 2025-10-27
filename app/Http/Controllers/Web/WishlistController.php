<?php

namespace App\Http\Controllers\Web;

use Exception;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class WishlistController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            $courses = Wishlist::where('user_id', Auth::user()->id)->get();

            $data = [
                "courses" => $courses,
            ];

            return view('web.wishlist', $data);
        } catch (Exception $exception) {
            Log::error("WishlistController::index()", [$exception]);
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
            $wishlistExists = Wishlist::where('user_id', Auth::user()->id)
            ->where('course_id', $courseId)
            ->exists();

            if ($wishlistExists) {
                return redirect()->route('wishlist')->with('success', "Course already exists in wishlist");
            }

            $params = [
                'user_id' => Auth::user()->id,
                'course_id' => $courseId,
            ];
            $addToWishlist = Wishlist::create($params);

            if ($addToWishlist) {
                return redirect()->route('wishlist')->with('success', "Course added to cart sucessfully");
            }

            return redirect()->back()->with('error', "Something Went wrong!");
        } catch (Exception $exception) {
            Log::error("WishlistController::store()", [$exception]);
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
            $delete = Wishlist::where('id', $id)->delete();

            if ($delete) {
                return redirect()->route('wishlist')->with('success', "Course deleted from wishlist successfully.");
            }

            return redirect()->back()->with('error', "Something Went wrong!");
        } catch (Exception $exception) {
            Log::error("WishlistController::delete()", [$exception]);
            return redirect()->back()->with('error', "Something Went wrong!");
        }
    }
}
