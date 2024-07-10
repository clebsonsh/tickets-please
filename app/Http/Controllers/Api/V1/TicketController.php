<?php

namespace App\Http\Controllers\Api\V1;

use App\Data\TicketData;
use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Traits\ApiIncludes;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketController extends Controller
{
    use ApiIncludes, ApiResponses;

    public function index(TicketFilter $filters): AnonymousResourceCollection
    {
        return TicketResource::collection(
            Ticket::query()
                ->filter($filters)
                ->paginate()
        );
    }

    public function store(StoreTicketRequest $request): JsonResponse|JsonResource
    {
        $validated = new TicketData(
            $request->string('data.attributes.title'),
            $request->string('data.attributes.description'),
            $request->string('data.attributes.status'),
            $request->integer('data.relationships.author.data.id'),
        );

        $ticket = Ticket::create((array) $validated);

        return new TicketResource($ticket);
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
