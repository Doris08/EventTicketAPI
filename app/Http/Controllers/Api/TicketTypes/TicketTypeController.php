<?php

namespace App\Http\Controllers\Api\TicketTypes;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketTypes\CreateRequest;
use App\Http\Requests\TicketTypes\UpdateRequest;
use App\Http\Resources\TicketType\TicketTypeResource;
use App\Models\TicketType;

class TicketTypeController extends Controller
{
    public function __construct(protected TicketType $ticketType)
    {

    }

    public function index()
    {

        $ticketTypeResources = TicketTypeResource::collection(TicketType::paginate(10));

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $ticketTypeResources,
        ], 200);
    }

    public function store(CreateRequest $request)
    {
        $this->ticketType = TicketType::create([
            'event_id' => $request->event_id,
            'name' => $request->name,
            'description' => $request->description,
            'quantity_available' => $request->quantity_available,
            'price' => $request->price,
            'sale_start_date' => $request->sale_start_date,
            'sale_start_time' => $request->sale_start_time,
            'sale_end_date' => $request->sale_end_date,
            'sale_end_time' => $request->sale_end_time,
            'purchase_limit' => $request->purchase_limit
        ]);

        $ticketTypeResource = new TicketTypeResource($this->ticketType);

        return response()->json([
            'status' => true,
            'code' => 201,
            'message' => 'Ticket Type Created Successfully',
            'data_created' => $ticketTypeResource,
        ], 201);
    }

    public function show($id)
    {
        $ticketTypeResource = new TicketTypeResource(TicketType::findOrFail($id));

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $ticketTypeResource,
        ], 200);
    }

    public function update(UpdateRequest $request, $id)
    {
        TicketType::where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'quantity_available' => $request->quantity_available,
            'price' => $request->price,
            'sale_start_date' => $request->sale_start_date,
            'sale_start_time' => $request->sale_start_time,
            'sale_end_date' => $request->sale_end_date,
            'sale_end_time' => $request->sale_end_time,
            'purchase_limit' => $request->purchase_limit
        ]);

        $ticketTypeResource = new TicketTypeResource(TicketType::findOrFail($id));

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Ticket Type Updated Successfully",
            'data_updated' => $ticketTypeResource,
        ], 200);
    }

    public function destroy($id)
    {
        TicketType::where('id', $id)->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Ticket Type Deleted Successfully",
        ], 200);

    }
}
