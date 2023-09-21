<?php

namespace App\Http\Controllers\Api\TicketTypes;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketTypes\CreateRequest;
use App\Http\Requests\TicketTypes\UpdateRequest;
use App\Http\Resources\TicketType\TicketTypeResource;
use App\Models\TicketType;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\TicketTypes\TicketTypeService;

class TicketTypeController extends Controller
{
    public function __construct(protected TicketType $ticketType)
    {

    }

    public function index(Request $request)
    {
        return (new TicketTypeService())->index($request->all());
    }

    public function store(CreateRequest $request)
    {
        return (new TicketTypeService())->store($request);
    }

    public function show($id)
    {
        return (new TicketTypeService())->show($id);
    }

    public function update(UpdateRequest $request, $id)
    {
        return (new TicketTypeService())->update($request, $id);
    }

    public function destroy($id)
    {
        return (new TicketTypeService())->destroy($id);
    }
}
