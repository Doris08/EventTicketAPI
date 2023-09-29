<?php

namespace App\Http\Controllers\Api\Events;

use App\Services\Events\EventService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\CreateRequest;
use App\Http\Requests\Events\UpdateRequest;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(Request $request)
    {
        return $this->eventService->index($request->all());
    }

    public function myEvents(Request $request)
    {
        return $this->eventService->myEvents($request->all());
    }

    public function store(CreateRequest $request)
    {
        return $this->eventService->store($request);
    }

    public function show($id)
    {
        return $this->eventService->show($id);
    }

    public function update(UpdateRequest $request, $id)
    {
        return $this->eventService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->eventService->destroy($id);
    }

    public function publish($id)
    {
        return $this->eventService->publish($id);
    }

}
