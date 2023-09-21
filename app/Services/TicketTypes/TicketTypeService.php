<?php
namespace App\Services\TicketTypes;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketTypes\CreateRequest;
use App\Http\Requests\TicketTypes\UpdateRequest;
use App\Http\Resources\TicketType\TicketTypeResource;
use App\Models\TicketType;
use App\Services\BaseService;

class TicketTypeService extends BaseService
{
    public function index($request)
    {
        $paginate = null;
        if (isset($request['limit'])) {
            $paginate = $request['limit'];  
        } 

        $ticketTypeResources = TicketTypeResource::collection(TicketType::orderBy('name')->paginate($paginate));
        return $this->successResponse($ticketTypeResources, 201, "TicketTypes founded Successfully");
    }

    public function store($request)
    {
        $ticketType = new TicketType();

        if (!$ticketType->ticketsLimit($request->event_id)){
            
            $ticketType = TicketType::create([
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

            $ticketTypeResource = new TicketTypeResource($ticketType);
            return $this->successResponse($ticketTypeResource, 201, "Ticket Type Created Successfully");

        }else{
            return $this->errorResponse(null, 400, "Ticket Type Could not be created. Limit of 10 for this event has been reached");
        }
    }

    public function show($id)
    {
        $ticketTypeResource = new TicketTypeResource(TicketType::findOrFail($id));

        return $this->successResponse($ticketTypeResource, 200, "Ticket Type Founded Successfully");
    }

    public function update($request, $id)
    {
        $ticketType = TicketType::findOrFail($id);

        if(!$ticketType->quantityAvailableLimit($request->quantity_available))
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
            return $this->successResponse($ticketTypeResource, 200, "Ticket Type Updated Successfully");

        }else{
            return $this->errorResponse(null, 400, "Quantity Available sent cannot be lower than Quantity already sold");
        } 
    }
    
    public function destroy($id)
    {
        $ticketType = TicketType::findOrFail($id);
    
        if(!$ticketType->hasOrders()){
            TicketType::where('id', $id)->delete();

            return $this->successResponse(null, 200, "Event Deleted Successfully");

        }else{
            
            return $this->errorResponse(null, 400, "Cannot Delete TicketType, it is in opened orders");
        }
       
    }
}