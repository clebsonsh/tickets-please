<?php

namespace App\Http\Controllers\Api\V1;

use App\Data\TicketData;
use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorTicketsController extends Controller
{
    public function index(int $author_id, TicketFilter $filters): AnonymousResourceCollection
    {
        return TicketResource::collection(
            Ticket::query()
                ->where('user_id', $author_id)
                ->filter($filters)
                ->paginate()
        );
    }

    public function store($author_id, StoreTicketRequest $request): JsonResponse|JsonResource
    {
        $validated = new TicketData(
            $request->string('data.attributes.title'),
            $request->string('data.attributes.description'),
            $request->string('data.attributes.status'),
            $author_id,
        );

        $ticket = Ticket::create((array) $validated);

        return new TicketResource($ticket);
    }
}
