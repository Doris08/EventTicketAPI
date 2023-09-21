<?php
namespace App\Services\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\CreateRequest;
use App\Models\User;
use App\Http\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\TicketType;
use App\Models\Ticket;
use App\Services\BaseService;

class OrderService extends BaseService
{

    public function index($request)
    {
        $paginate = null;
        if (isset($request['limit'])) {
            $paginate = $request['limit'];  
        } 
        
       $orderResources = OrderResource::collection(Order::orderBy('id')->paginate($paginate));
       return $this->successResponse($orderResources, 201, "Orders founded Successfully");
    }

    public function store(CreateRequest $request)
    {
        try {
            $order = Order::create([
                'event_id' => $request->event_id,
                'user_id' => User::first()->id,
                'purchase_date' => $request->purchase_date,
                'status' => 'Sold'
            ]);
            if ($order->exists) {
                for ($i = 0; $i < count($request->order_details); $i++) {
                    $detail = $request->order_details[$i];
                    $ticketType = TicketType::findOrFail($detail['ticket_type_id']);

                    $orderDetail = OrderDetail::create([
                        'order_id' => $order->id,
                        'ticket_type_id' => $ticketType->id,
                        'quantity' => $detail['quantity'],
                        'sale_price' => $ticketType->price,
                        'total' => number_format($detail['quantity'] * $ticketType->price, 2)
                    ]);

                    $this->createTickets($orderDetail);

                    $this->updateTicketsQuantity($orderDetail->ticket_type_id, $detail['quantity']);
                }
            }
            $orderResources = new OrderResource($order);

            return $this->successResponse($orderResources, 201, "Order Created Successfully");

        } catch (\Throwable $th) {
            foreach ($order->orderDetails as $detail) {
                foreach ($detail->tickets as $ticket) {
                    $ticket->delete();
                }
                $detail->delete();
            }
            $order->delete();

            return $this->errorResponse(null, 500, "Something went wrong. Order could not be saved.");
        }
    }
    
    function createTickets($orderDetail){
        
        for ($i=0; $i < $orderDetail->quantity; $i++) { 
            Ticket::create([
                'order_detail_id' => $orderDetail->id,
                'ticket_type_id' => $orderDetail->ticket_type_id,
                'status' => 'Sold',
            ]);
        }
    }

    function updateTicketsQuantity($ticketTypeId, $quantity){

        $ticketType = TicketType::findOrFail($ticketTypeId);

        TicketType::where('id', $ticketTypeId)->update([    
            'quantity_available' => $ticketType->quantity_available - $quantity,
            'quantity_sold' => $ticketType->quantity_sold + $quantity
        ]);
    }
}