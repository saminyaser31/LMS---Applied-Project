<?php

namespace App\Helper;

use App\Models\Cart;
use App\Models\CourseCategory;
use App\Models\CourseSubject;
use App\Models\CourseLevel;
use App\Models\User;
use App\Models\WebColor;
use App\Models\WebImage;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Helper
{
    /**
     * @return array
     */
    public static function getAllReligion(): array
    {
        return [
            'Islam',
            'Hinduism',
            'Buddhism',
            'Christianity',
            'Other',
        ];
    }

    /**
     * @return array
     */
    public static function getAllMaritalStatus(): array
    {
        return [
            'Single',
            'Married',
            'Divorced',
            'Widowed',
            'In a relationship',
        ];
    }

    /**
     * @return array
     */
    public static function getAllGender(): array
    {
        return [
            'Male',
            'Female',
            'Transgender',
            'Other',
            'Prefer not to say',
        ];
    }

    /**
     * @return array
     */
    public static function getAllCourseCategory(): array
    {
        return Cache::remember("all-course-category-cache", 60*60*24, function () {
            return CourseCategory::all()->pluck('name', 'id')->toArray();
        });
    }

    /**
     * @return array
     */
    public static function getAllCourseSubject()
    {
        return Cache::remember("all-course-subject-cache", 60*60*24, function () {
            return CourseSubject::all()->pluck('name', 'id')->toArray();
        });
    }

    /**
     * @return array
     */
    public static function getAllCourseLevel()
    {
        return Cache::remember("all-course-level-cache", 60*60*24, function () {
            return CourseLevel::all()->pluck('name', 'id')->toArray();
        });
    }

    /**
     * @return collection
     */
    public static function getWebColor()
    {
        // return Cache::remember("web-color-cache", 60*60*24, function () {
            return WebColor::first();
        // });
    }

    /**
     * @return collection
     */
    public static function getWebImages()
    {
        return Cache::remember("web-image-cache", 60*60*24, function () {
            return WebImage::first();
        });
    }

    /**
     * Save the uploaded image to the specified directory and return the URL.
     *
     * @param \Illuminate\Http\UploadedFile $imageRequest The uploaded image file.
     * @param string $directory The directory where the image should be stored.
     * @param string $fileName The name of the image file to store.
     * @return string|null The URL of the saved image or null if there was an error.
     */
    public static function saveFile($fileRequest, $directory, $fileName)
    {
        try {
            $filePath = $fileRequest->storeAs($directory, $fileName, 'public');
            return Storage::url($filePath);
        } catch (Exception $exception) {
            Log::error("Helper::saveFile()", [$exception]);
            return null;
        }
    }

    /**
     * @return string|null
     */
    public static function generateUniqueToken()
    {
        try {
            // Generate a unique identifier using uniqid with added entropy
            $uniqidPart = uniqid('token_', true); // You can prefix with any string

            // Generate a random UUID for added randomness
            $uuidPart = (string) Str::uuid(); // Or use `uniqid()` and then combine them in a different way if preferred

            // Combine the two parts
            $token = $uniqidPart . '-' . $uuidPart;

            // Ensure the token is unique in the database
            while (User::where('token', $token)->exists()) {
                // If the token already exists, regenerate it
                self::generateUniqueToken();
            }

            return $token;
        } catch (Exception $exception) {
            Log::error("Helper::generateUniqueToken()", [$exception]);
            return null;
        }
    }

    public static function setRoutePrefix()
    {
        $routePrefix = "";
        if (isset(app('admin')->id)) {
            $routePrefix = "admin";
        }
        else if (isset(app('teacher')->id)) {
            $routePrefix = "teacher";
        }
    }

    public static function getCartCount()
    {
        try {
            if (Auth::check()) {
                $count = Cart::where('user_id', Auth::user()->id)->count();
            } else {
                $count = 0;
            }

            return $count;
        } catch (Exception $exception) {
            Log::error("Helper::getCartCount()", [$exception]);
            return 0;
        }
    }
}
