<?php

namespace App\Http\Controllers\Api\Events;

use App\Services\Events\EventService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\CreateRequest;
use App\Http\Requests\Events\UpdateRequest;
use App\Models\Event;
use App\Models\User;
use App\Http\Resources\Events\EventResource;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(protected Event $event)
    {

    }

    public function index(Request $request)
    {
       return (new EventService())->index($request->all());
    }

    public function store(CreateRequest $request)
    {
        return (new EventService())->store($request);
    }

    public function show($id)
    {
        return (new EventService())->show($id);
    }

    public function update(UpdateRequest $request, $id)
    {
        return (new EventService())->update($request, $id);
    }

    public function destroy($id)
    {
        return (new EventService())->destroy($id);
    }

    public function publish($id)
    {
        return (new EventService())->publish($id);
    }

}
