<?php

namespace App\Http\Controllers\Api\TicketTypes;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketTypes\CreateRequest;
use App\Http\Requests\TicketTypes\UpdateRequest;
use App\Models\TicketType;
use Illuminate\Http\Request;
use App\Services\TicketTypes\TicketTypeService;

class TicketTypeController extends Controller
{
    public function __construct(TicketTypeService $ticketTypeService)
    {
        $this->ticketTypeService = $ticketTypeService;
    }

    public function index(Request $request)
    {
        return $this->ticketTypeService->index($request->all());
    }

    public function store(CreateRequest $request)
    {
        return $this->ticketTypeService->store($request);
    }

    public function show($id)
    {
        return $this->ticketTypeService->show($id);
    }

    public function update(UpdateRequest $request, $id)
    {
        return $this->ticketTypeService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->ticketTypeService->destroy($id);
    }
}
