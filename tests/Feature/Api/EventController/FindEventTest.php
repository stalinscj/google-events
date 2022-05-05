<?php

namespace Tests\Feature\Api\EventController;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Laravel\Sanctum\Sanctum;
use App\Services\Contracts\Calendar\EventService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FindEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \Mockery\Mock|\App\Services\Contracts\Calendar\EventService
     */
    protected $eventService;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->eventService = $this->mock(EventService::class);
    }

    /**
     * @test
     */
    public function guest_cannot_fetch_a_single_event_by_id()
    {
        $this->getJson(route('api.events.show', rand()))
            ->assertUnauthorized();
    }

    /**
     * @test
     */
    public function user_can_fetch_a_single_event_by_id()
    {
        $event = Event::factory()->make();

        $this->eventService
            ->shouldReceive('findById')
            ->with($event->object_id)
            ->once()
            ->andReturn($event);

        Sanctum::actingAs(User::factory()->create());

        $this->getJson(route('api.events.show', $event->object_id))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'object_id'   => $event->object_id,
                    'summary'     => $event->summary,
                    'location'    => $event->location,
                    'description' => $event->description,
                    'start'       => [
                        'dateTime' => $event->start['dateTime'],
                        'timeZone' => $event->start['timeZone'],
                    ],
                    'end'         => [
                        'dateTime' => $event->end['dateTime'],
                        'timeZone' => $event->end['timeZone'],
                    ],
                    'status'      => $event->status,
                ]
            ]);
    }
}
