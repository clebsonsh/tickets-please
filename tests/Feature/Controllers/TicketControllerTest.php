<?php

namespace Tests\Feature\Controllers;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class TicketControllerTest extends BaseTest
{
    use RefreshDatabase;

    public function testGuestCanNotSeeTickets(): void
    {
        $response = $this
            ->getJson(route('tickets.index'));

        $response
            ->assertJson([
                'message' => 'Unauthenticated.',
            ])
            ->assertStatus(401);
    }

    public function testAuthenticatedUsersCanSeeTickets(): void
    {
        $this->setAuthenticatedUserWithTickets();

        $response = $this
            ->withToken($this->token)
            ->getJson(route('tickets.index'));

        $response
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'title',
                            'status',
                            'createdAt',
                            'updatedAt',
                        ],
                        'relationships' => [
                            'author' => [
                                'data' => [
                                    'type',
                                    'id',
                                ],
                                'links' => [
                                    'self',
                                ],
                            ],
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                ],
                'links',
                'meta',
            ])
            ->assertJsonFragment([
                'data' => [
                    'type' => 'user',
                    'id' => $this->user->id,
                ],
                'type' => 'user',
                'id' => 1,
                'path' => route('tickets.index'),
                'per_page' => 15,
                'to' => 1,
                'total' => 1,
            ])
            ->assertStatus(200);
    }

    public function testAuthenticatedUsersIncludeTicketAuthor(): void
    {
        $this->setAuthenticatedUserWithTickets();

        $response = $this
            ->withToken($this->token)
            ->getJson(route('tickets.index').'?include=author');

        $response
            ->assertJsonFragment([
                'includes' => [
                    'author' => [
                        'type' => 'user',
                        'id' => $this->user->id,
                        'attributes' => [
                            'name' => $this->user->name,
                            'email' => $this->user->email,
                        ],
                        'links' => [
                            'self' => route('authors.show', $this->user->id),
                        ],
                    ],
                ],
            ])
            ->assertStatus(200);
    }

    public function testAuthenticatedUsersCanSeeFilterTicketsByStatus(): void
    {
        $ticketsCount = 3;
        $ticketsData = ['status' => 'X'];

        $this->setAuthenticatedUserWithTickets($ticketsCount, $ticketsData);

        Ticket::factory([
            'user_id' => $this->user->id,
            'status' => 'A',
        ])->create();

        $response = $this
            ->withToken($this->token)
            ->getJson(route('tickets.index').'?filter[status]=A');

        $response
            ->assertJsonFragment([
                'status' => 'A',
                'data' => [
                    'type' => 'user',
                    'id' => $this->user->id,
                ],
                'type' => 'user',
                'id' => 1,
                'path' => route('tickets.index'),
                'per_page' => 15,
                'to' => 1,
                'total' => 1,
            ])
            ->assertJsonPath('data.0.attributes.status', 'A')
            ->assertJsonCount(1, 'data')
            ->assertStatus(200);

        $response = $this
            ->withToken($this->token)
            ->getJson(route('tickets.index').'?filter[status]=H');

        $response
            ->assertJsonFragment([
                'total' => 0,
            ])
            ->assertJsonMissingPath('data.*')
            ->assertJsonCount(0, 'data')
            ->assertStatus(200);

        $response = $this
            ->withToken($this->token)
            ->getJson(route('tickets.index'));

        $response
            ->assertJsonFragment([
                'total' => 4,
            ])
            ->assertJsonCount(4, 'data')
            ->assertStatus(200);
    }

    /**
     * @param  array <string, string>  $userData
     * @param  array <string, string>  $ticketsData
     * @return Collection <int, Ticket>
     */
    private function setAuthenticatedUserWithTickets(int $ticketsCount = 1, array $ticketsData = [], array $userData = []): Collection
    {
        $this->loginAs($userData ?: $this->validUser);

        return Ticket::factory([...$ticketsData, 'user_id' => $this->user->id])
            ->count($ticketsCount)
            ->create();
    }
}
