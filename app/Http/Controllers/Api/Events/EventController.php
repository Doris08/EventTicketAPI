<?php

namespace App\Http\Controllers\Api\Events;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Events\CreateRequest;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function __construct(protected Event $event)
    {

    }

    public function store(CreateRequest $request)
    {
        try {
            $this->event = Event::create([
                'organizer_id' => Auth::user()->id,
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'start_time' => $request->start_time,
                'end_date' => $request->end_date,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'image_header_url' => $request->image_header_url
            ]);

            return response()->json([
                'status' => true,
                'code' => 201,
                'message' => 'Event Created Successfully',
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function index(){

    }

    public function show(){

    }

    public function update(){

    }

    public function delete(){

    }
}
