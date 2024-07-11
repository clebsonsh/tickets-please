<?php

namespace App\Http\Controllers\Api\V1;

use App\Data\TicketData;
use App\Http\Controllers\Api\ApiController;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorTicketsController extends ApiController
{
    public function index(int $author_id, TicketFilter $filters): JsonResponse|AnonymousResourceCollection
    {
        if (! User::find($author_id)) {
            return $this->error('Author cannot be found', 404);
        }

        return TicketResource::collection(
            Ticket::query()
                ->where('user_id', $author_id)
                ->filter($filters)
                ->paginate()
        );
    }

    public function store(int $author_id, StoreTicketRequest $request): JsonResponse|JsonResource
    {
        if (! User::find($author_id)) {
            return $this->error('Author cannot be found', 404);
        }

        $validated = new TicketData(
            $request->string('data.attributes.title'),
            $request->string('data.attributes.description'),
            $request->string('data.attributes.status'),
            $author_id,
        );

        $ticket = Ticket::create((array) $validated);

        return new TicketResource($ticket);
    }

    public function replace(ReplaceTicketRequest $request, int $author_id, int $ticket_id): TicketResource|JsonResponse
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($ticket->user_id != $author_id) {
                throw new ModelNotFoundException();
            }

            $validated = new TicketData(
                $request->string('data.attributes.title'),
                $request->string('data.attributes.description'),
                $request->string('data.attributes.status'),
                $request->integer('data.relationships.author.data.id'),
            );

            $ticket->update((array) $validated);

            return new TicketResource($ticket);
        } catch (ModelNotFoundException) {
            return $this->error('Ticket cannot be found', 404);
        }
    }

    public function destroy(int $author_id, int $ticket_id): JsonResponse
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($ticket->user_id != $author_id) {
                throw new ModelNotFoundException();
            }

            $ticket->delete();

            return $this->ok('Ticket successfully deleted');
        } catch (ModelNotFoundException) {
            return $this->error('Ticket cannot be found', 404);
        }
    }
}
