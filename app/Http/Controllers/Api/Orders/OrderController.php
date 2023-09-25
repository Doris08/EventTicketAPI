<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\CreateRequest;
use App\Models\Order;
use App\Services\Orders\OrderService;
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

    public function show($id)
    {
        return (new OrderService())->show($id);
    }

    public function store(CreateRequest $request)
    {
        return (new OrderService())->store($request);
    }
}
