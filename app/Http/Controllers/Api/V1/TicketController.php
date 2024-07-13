<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Policies\V1\TicketPolicy;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

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
        return new TicketResource(Ticket::create($request->mappedAttribues()));
    }

    public function show(Ticket $ticket): JsonResource
    {
        if ($this->include('author')) {
            $ticket->load('author');
        }

        return new TicketResource($ticket);
    }

    public function update(UpdateTicketRequest $request, int $ticket_id): TicketResource|JsonResponse
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            $this->isAble('update', $ticket);

            $ticket->update($request->mappedAttribues());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException) {
            return $this->error('Ticket cannot be found', 404);
        }
    }

    public function replace(ReplaceTicketRequest $request, int $ticket_id): TicketResource|JsonResponse
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            $ticket->update($request->mappedAttribues());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException) {
            return $this->error('Ticket cannot be found', 404);
        }
    }

    public function destroy(int $ticket_id): JsonResponse
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $ticket->delete();

            return $this->ok('Ticket successfully deleted');
        } catch (ModelNotFoundException) {
            return $this->error('Ticket cannot be found', 404);
        }
    }
}
