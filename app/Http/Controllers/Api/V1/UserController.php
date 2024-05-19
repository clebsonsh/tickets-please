<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Traits\ApiIncludes;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    use ApiIncludes;

    public function index(): AnonymousResourceCollection
    {
        $query = User::query();

        if ($this->include('tickets')) {
            $query->with('tickets');
        }

        return UserResource::collection($query->paginate());
    }

    public function store(StoreUserRequest $request): void
    {
        //
    }

    public function show(User $user): JsonResource
    {
        if ($this->include('tickets')) {
            $user->load('tickets');
        }

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user): void
    {
        //
    }

    public function destroy(User $user): void
    {
        //
    }
}
