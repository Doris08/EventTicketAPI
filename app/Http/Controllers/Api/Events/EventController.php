<?php

namespace App\Http\Controllers\Api\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\Events\CreateRequest;
use App\Http\Requests\Events\UpdateRequest;
use App\Models\Event;
use App\Models\User;
use App\Http\Resources\Events\EventResource;

class EventController extends Controller
{
    public function __construct(protected Event $event)
    {

    }

    public function index()
    {

        $eventResources = EventResource::collection(Event::paginate(10));

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $eventResources,
        ], 200);
    }

    public function store(CreateRequest $request)
    {
        $this->event = Event::create([
            'organizer_id' => User::first()->id,
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_date' => $request->end_date,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'image_header_url' => $request->image_header_url
        ]);

        $event = Event::findOrFail($this->event->id);
        $eventResource = new EventResource($event);

        return response()->json([
            'status' => true,
            'code' => 201,
            'message' => 'Event Created Successfully',
            'data_created' => $eventResource,
        ], 201);
    }

    public function show($id)
    {
        $eventResource = new EventResource(Event::findOrFail($id));

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $eventResource,
        ], 200);
    }

    public function update(UpdateRequest $request, $id)
    {
        Event::where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_date' => $request->end_date,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'image_header_url' => $request->image_header_url
        ]);

        $eventResource = new EventResource(Event::findOrFail($id));

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Event Updated Successfully",
            'data_updated' => $eventResource,
        ], 200);
    }

    public function destroy($id)
    {
        if(!$this->event->hasOrders()){
            Event::where('id', $id)->delete();

            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "Event Deleted Successfully",
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Cannot Delete Event, it has opened orders",
            ], 400);
        }

    }

    public function publish($id)
    {
        $this->event = Event::findOrFail($id);

        $publish = "Published";

        if($this->event->hasTickets()){
            Event::where('id', $id)->update([
                'status' => $publish
            ]);

        
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "Event was published successfully",
            ], 200);
        }

        return response()->json([
            'status' => false,
            'code' => 400,
            'message' => "Event requires tickets associated for publishing",
        ], 400);

    }
}
