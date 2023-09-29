<?php

namespace App\Services;

use App\Models\Event;

class BaseService
{
    public function successResponse($data, $statusCode, $message)
    {
        return response()->json([
            'status' => true,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public function errorResponse($data, $statusCode, $message)
    {
        return response()->json([
            'status' => false,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public function validateUserEvents($id){

        $userValid = true;

        $event = Event::where('organizer_id', auth()->user()->id)->where('id', $id)->get();

        if(count($event) == 0 ){
            $userValid = false;
        }

        return $userValid;
    }
}
