<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\AuthorResource;
use App\Models\User;
use App\Traits\ApiIncludes;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorController extends Controller
{
    use ApiIncludes;

    public function index(AuthorFilter $filters): AnonymousResourceCollection
    {
        return AuthorResource::collection(
            User::query()
                ->filter($filters)
                ->paginate()
        );
    }

    public function store(StoreUserRequest $request): void
    {
        //
    }

    public function show(User $author): JsonResource
    {
        if ($this->include('tickets')) {
            $author->load('tickets');
        }

        return new AuthorResource($author);
    }

    public function update(UpdateUserRequest $request, User $author): void
    {
        //
    }

    public function destroy(User $author): void
    {
        //
    }
}
