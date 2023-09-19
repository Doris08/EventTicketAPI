<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Orders\CreateRequest;
use App\Http\Requests\OrderDetails\CreateRequest as OrderDetailRequest;
use App\Models\Event;
use App\Models\User;
use App\Http\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\TicketType;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(protected Order $order)
    {

    }

    public function index(){

        $orderResources = OrderResource::collection(Order::paginate(10));

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $orderResources,
        ], 200);
    }

    public function store(CreateRequest $request)
    {
        if(!empty($request->order_details)){
            $orderDetailRequest = new OrderDetailRequest();
            $this->validateOrderDetails($orderDetailRequest, $request->order_details);
        }

        $this->order = Order::create([
            'event_id' => $request->event_id,
            'user_id' => User::first()->id,
            'purchase_date' => $request->purchase_date,
            'status' => 'Saled'
        ]);

        $orderResources = new OrderResource($this->order);

        return response()->json([
            'status' => true,
            'code' => 201,
            'message' => 'Order Created Successfully',
            'data_created' => $orderResources,
        ], 201);
    }

    function validateOrderDetails($orderDetailRequest, $orderDetails){
        $orderDetailRequest = $orderDetails;
        return response()->json([
            'status' => true,
            'code' => 201,
            'message' => 'Order Created Successfully',
            'data_created' => $orderDetailRequest,
        ], 201);
    }

    public function show($id)
    {
        $ordersResource = new OrderResource(Order::findOrFail($id));

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $ordersResource,
        ], 200);
    }
}
