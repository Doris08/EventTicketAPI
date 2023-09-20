<?php

namespace App\Services;

class BaseService
{
    public function successResponse($data, $statusCode, $message){
        return response()->json([
            'status' => true,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public function errorResponse($data, $statusCode, $message){
        return response()->json([
            'status' => false,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}