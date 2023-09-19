<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\CreateRequest;
use App\Models\User;
use App\Http\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\TicketType;
use App\Models\Ticket;

class OrderController extends Controller
{
    public function __construct(protected Order $order)
    {

    }

    public function index()
    {

        $orderResources = OrderResource::collection(Order::paginate(10));

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $orderResources,
        ], 200);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->order = Order::create([
                'event_id' => $request->event_id,
                'user_id' => User::first()->id,
                'purchase_date' => $request->purchase_date,
                'status' => 'Saled'
            ]);
            if ($this->order->exists) {
                for ($i = 0; $i < count($request->order_details); $i++) {
                    $detail = $request->order_details[$i];
                    $ticketType = TicketType::findOrFail($detail['ticket_type_id']);

                    $orderDetail = OrderDetail::create([
                        'order_id' => $this->order->id,
                        'ticket_type_id' => $ticketType->id,
                        'quantity' => $detail['quantity'],
                        'sale_price' => $ticketType->price,
                        'total' => number_format($detail['quantity'] * $ticketType->price, 2)
                    ]);

                    $this->createTickets($orderDetail);
                }
            }
            $orderResources = new OrderResource($this->order);

            return response()->json([
                'status' => true,
                'code' => 201,
                'message' => 'Order Created Successfully',
                'data_created' => $orderResources,
            ], 201);
        } catch (\Throwable $th) {

            foreach ($this->order->orderDetails as $detail) {
                foreach ($detail->tickets as $ticket) {
                    $ticket->delete();
                }
                $detail->delete();
            }
            $this->order->delete();

            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => 'Something went wrong. Order could not be saved.'
            ], 500);
        }
    }

    function createTickets($orderDetail){
        for ($i=0; $i < $orderDetail->quantity; $i++) { 
            Ticket::create([
                'order_detail_id' => $orderDetail->id,
                'ticket_type_id' => $orderDetail->ticket_type_id,
                'status' => 'Saled',
            ]);
        }
    }

}
