<?php

namespace App\Services\Events;

use App\Models\Event;
use App\Http\Resources\Events\EventResource;
use App\Services\BaseService;

class EventService extends BaseService
{
    public function index($request)
    {
        try {
            $paginate = null;
            if (isset($request['limit'])) {
                $paginate = $request['limit'];
            }

            $eventResources = EventResource::collection(Event::orderBy('name')->paginate($paginate));

            if (isset($request['search'])) {
                $search = $request['search'];

                $events = Event::select("*")
                                ->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('description', 'LIKE', "%{$search}%")
                                ->orWhere('start_date', 'LIKE', "%{$search}%")
                                ->orWhere('location', 'LIKE', "%{$search}%")->paginate($paginate);

                $eventResources = EventResource::collection($events);
            }

            return $this->successResponse($eventResources, 200, "Events founded successfully");

        } catch (\Throwable $th) {

            return $this->errorResponse(null, 500, "Something went wrong. Events could not be founded");
        }

    }

    public function myEvents($request)
    {

        $userId = auth()->user()->id;
        try {
            $paginate = null;
            if (isset($request['limit'])) {
                $paginate = $request['limit'];
            }

            $eventResources = EventResource::collection(Event::where('organizer_id', $userId)
                                                        ->orderBy('name')->paginate($paginate));

            if (isset($request['search'])) {
                $search = $request['search'];

                $events = Event::select("*")->where('organizer_id', $userId)
                                ->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('description', 'LIKE', "%{$search}%")
                                ->orWhere('start_date', 'LIKE', "%{$search}%")
                                ->orWhere('location', 'LIKE', "%{$search}%")->paginate($paginate);

                $eventResources = EventResource::collection($events);
            }

            return $this->successResponse($eventResources, 200, "Events founded successfully");

        } catch (\Throwable $th) {

            return $this->errorResponse(null, 500, "Something went wrong. Events could not be founded");
        }

    }

    public function store($request)
    {
        try {

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

            return $this->successResponse($eventResource, 201, "Event created successfully");

        } catch (\Throwable $th) {

            return $this->errorResponse(null, 500, "Something went wrong. Event could not be created");
        }

    }

    public function show($id)
    {
        try {

            $eventResource = new EventResource(Event::findOrFail($id));
            return $this->successResponse($eventResource, 200, "Event founded successfully");

        } catch (\Throwable $th) {

            return $this->errorResponse(null, 500, "Something went wrong. Event could not be founded");
        }

    }

    public function update($request, $id)
    {
        try {
            
            $userValid =  $this->validateUserEvents($id);

            if(!$userValid){
                return $this->errorResponse(null, 400, "This user is not related to this event");
            }

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

            return $this->successResponse($eventResource, 200, "Event updated successfully");

        } catch (\Throwable $th) {

            return $this->errorResponse(null, 500, "Something went wrong. Event could not be updated");
        }

    }

    public function destroy($id)
    {
        try {

            $userValid =  $this->validateUserEvents($id);

            if(!$userValid){
                return $this->errorResponse(null, 400, "This user is not related to this event");
            }

            $event = Event::findOrFail($id);

            if(!$event->hasOrders()) {
                Event::where('id', $id)->delete();
                return $this->successResponse(null, 200, "Event deleted successfully");
            } else {
                return $this->errorResponse(null, 400, "Cannot delete event, it has opened orders");
            }

        } catch (\Throwable $th) {

            return $this->errorResponse(null, 500, "Something went wrong. Event could not be deleted");

        }

    }

    public function publish($id)
    {
        try {

            $userValid =  $this->validateUserEvents($id);

            if(!$userValid){
                return $this->errorResponse(null, 400, "This user is not related to this event");
            }

            $event = Event::findOrFail($id);

            if($event->status == "Published")
            {
                return $this->errorResponse(null, 400, "Event is already published");
            }

            $publish = "Published";

            if($event->hasTickets()) {

                Event::where('id', $id)->update([
                    'status' => $publish
                ]);

                return $this->successResponse(null, 200, "Event was published successfully");
            }

            return $this->errorResponse(null, 400, "Event requires tickets associated for publishing");

        } catch (\Throwable $th) {

            return $this->errorResponse(null, 500, "Something went wrong. Event could not be published");

        }

    }

    

}
