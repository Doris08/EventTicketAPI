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
use App\Services\Orders\OrderService;
use LDAP\Result;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected Order $order)
    {

    }

    public function index(Request $request)
    {
        return (new OrderService())->index($request->all());
    }

    public function store(CreateRequest $request)
    {
        return (new OrderService())->store($request);
    }
}
