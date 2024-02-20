<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HttpResponses{
    protected function success($message = null, $data = [], $code = 200):JsonResponse{
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error($message = null, $data = [], $code = 400):JsonResponse{
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function isnotcompleted($data,$message,$code = 422):JsonResponse{
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data
        ],$code)->header("Content-Type","application/json");
    }
}