<?php

namespace App\Http\Controllers\Api\Tickets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Tickets\TicketService;
use App\Http\Requests\Refunds\CreateRequest;

class TicketController extends Controller
{

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function refund(CreateRequest $request)
    {
        return $this->ticketService->refund($request);
    }

    public function checkIn(Request $request)
    {
        return $this->ticketService->checkIn($request);
    }

}
