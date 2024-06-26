<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Traits\ApiIncludes;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketController extends Controller
{
    use ApiIncludes;

    public function index(): AnonymousResourceCollection
    {
        $query = Ticket::query();

        if ($this->include('user')) {
            $query->with('user');
        }

        return TicketResource::collection($query->paginate());
    }

    public function store(StoreTicketRequest $request): void
    {
        //
    }

    public function show(Ticket $ticket): JsonResource
    {
        if ($this->include('user')) {
            $ticket->load('user');
        }

        return new TicketResource($ticket);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket): void
    {
        //
    }

    public function destroy(Ticket $ticket): void
    {
        //
    }
}
