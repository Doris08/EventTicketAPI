<?php

namespace App\Services\TicketTypes;

use App\Http\Resources\TicketType\TicketTypeResource;
use App\Models\TicketType;
use App\Services\BaseService;

class TicketTypeService extends BaseService
{
    public function index($request)
    {
        try {

            $paginate = null;
            if (isset($request['limit'])) {
                $paginate = $request['limit'];
            }

            $ticketTypeResources = TicketTypeResource::collection(TicketType::orderBy('name')->paginate($paginate));
            return $this->successResponse($ticketTypeResources, 200, "Ticket types founded successfully");

        } catch (\Throwable $th) {

            return $this->errorResponse(null, 500, "Something went wrong. TicketTypes could not be founded");
        }

    }

    public function store($request)
    {
        try {

            $userValid = $this->validateUserEvents($request->event_id);

            if(!$userValid){
                return $this->errorResponse(null, 400, "This user is not related to the event");
            }

            $ticketType = new TicketType();

            if (!$ticketType->ticketsLimit($request->event_id)) {

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

            } else {
                return $this->errorResponse(null, 400, "Ticket type could not be created. Limit of 10 for this event has been reached");
            }

        } catch (\Throwable $th) {

            return $this->errorResponse(null, 500, "Something went wrong. Ticket type could not be created");

        }

    }

    public function show($id)
    {
        try {

            $ticketType = TicketType::findOrFail($id);

            $userValid = $this->validateUserEvents($ticketType->event_id);

            if(!$userValid){
                return $this->errorResponse(null, 400, "This user is not related to the event");
            }

            $ticketTypeResource = new TicketTypeResource(TicketType::findOrFail($id));

            return $this->successResponse($ticketTypeResource, 200, "Ticket type founded successfully");

        } catch (\Throwable $th) {

            return $this->errorResponse(null, 500, "Something went wrong. Ticket type could not be founded");
        }

    }

    public function update($request, $id)
    {
        try {

            $ticketType = TicketType::findOrFail($id);

            $userValid = $this->validateUserEvents($ticketType->event_id);

            if(!$userValid){
                return $this->errorResponse(null, 400, "This user is not related to the event");
            }

            if(!$ticketType->quantityAvailableLimit($request->quantity_available)) {
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
                return $this->successResponse($ticketTypeResource, 200, "Ticket type updated successfully");

            } else {
                return $this->errorResponse(null, 400, "Quantity available sent cannot be lower than quantity already sold");
            }

        } catch (\Throwable $th) {

            return $this->errorResponse($th, 500, "Something went wrong. Ticket type could not be updated");
        }

    }

    public function destroy($id)
    {
        try {

            $ticketType = TicketType::findOrFail($id);

            $userValid = $this->validateUserEvents($ticketType->event_id);

            if(!$userValid){
                return $this->errorResponse(null, 400, "This user is not related to the event");
            }

            if(!$ticketType->hasOrders()) {
                TicketType::where('id', $id)->delete();

                return $this->successResponse(null, 200, "Ticket type deleted successfully");

            } else {

                return $this->errorResponse(null, 400, "Cannot delete ticket type, it is in opened orders");
            }

        } catch (\Throwable $th) {

            return $this->errorResponse($th, 500, "Something went wrong. Ticket type could not be deleted");
        }

    }
}
