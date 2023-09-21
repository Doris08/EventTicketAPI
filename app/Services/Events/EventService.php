<?php

namespace App\Services\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\Events\CreateRequest;
use App\Http\Requests\Events\UpdateRequest;
use App\Models\Event;
use App\Models\User;
use App\Http\Resources\Events\EventResource;
use App\Services\BaseService;

class EventService extends BaseService
{

    public function index($request)
    {
        $paginate = null;
        if (isset($request['limit'])) {
            $paginate = $request['limit'];  
        } 
        
       $eventResources = EventResource::collection(Event::orderBy('name')->paginate($paginate));
       return $this->successResponse($eventResources, 201, "Events founded Successfully");
    }

    public function store($request)
    {
        $event = Event::create([
            'organizer_id' => auth()->user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_date' => $request->end_date,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'image_header_url' => $request->image_header_url
        ]);

        $eventResource = new EventResource($event);

        return $this->successResponse($eventResource, 201, "Event Created Successfully");
    }

    public function show($id)
    {
        $eventResource = new EventResource(Event::findOrFail($id));
        return $this->successResponse($eventResource, 201, "Event Founded Successfully");
    }

    public function update($request, $id)
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

        return $this->successResponse($eventResource, 201, "Event Updated Successfully");
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
    
        if(!$event->hasOrders()){
            Event::where('id', $id)->delete();
            return $this->successResponse(null, 201, "Event Deleted Successfully");
        }else{
            return $this->errorResponse(null, 400, "Cannot Delete Event, it has opened orders");
        }
    }

    public function publish($id)
    {
        $event = Event::findOrFail($id);

        $publish = "Published";

        if($event->hasTickets()){
            
            Event::where('id', $id)->update([
                'status' => $publish
            ]);

            return $this->successResponse(null, 200, "Event was published successfully");
        }

        return $this->errorResponse(null, 400, "Event requires tickets associated for publishing");
    }

}