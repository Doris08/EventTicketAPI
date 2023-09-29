<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\CreateRequest;
use App\Models\Order;
use App\Services\Orders\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        return $this->orderService->index($request->all());
    }

    public function show($id)
    {
        return $this->orderService->show($id);
    }

    public function store(CreateRequest $request)
    {
        return $this->orderService->store($request);
    }
}
