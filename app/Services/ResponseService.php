<?php

namespace App\Services;

use Illuminate\Http\Response;

class ResponseService
{

    /**
     * basic response
     *
     * @param int $code
     * @param string $message
     * @param mixed $errors
     * @param mixed $data
     * @return array
     */
    public static function basicResponse($code, $message = "", $errors = null, $data = null): array
    {
        if (!is_null($errors)) {
            if (!is_object($errors)) {
                $errors = (object) $errors;
            }
        }
        return
            [
                'message' => $message,
                'errors'  => $errors,
                'data'    => $data,
                'code'    => $code,
            ];
    }

    /**
     * @param int $code
     * @param string $status
     * @param string $message
     * @param mixed $data
     * @param string $details
     * @param string $local
     *
     * @return array
     */
    public static function jlBasicResponse(int $code, string $status, string $message = "", mixed $data, string $details = "", string $local = "en")
    {
        return response()->json([
            'status' => $status,
            'code'    => $code,
            'message' => $message,
            'details'  => $details,
            'locale'  => $local,
            'data'    => $data,
        ]);
    }


    /**
     * api response
     *
     * @param integer $code
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\Response
     */
    public static function apiResponse(int $code, string $message = "", $data = [])
    {
        return response()->json([
            'message' => $message,
            'data'    => $data,
        ], $code);

    }

    /**
     * api response with paginate
     *
     * @param integer $code
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\Response
     */
    public static function apiPaginateResponse(int $code, string $message = "", $data = [])
    {
        $data = $data->toArray();
        $data['message'] = $message;
        return response()->json($data, $code);
    }
}
