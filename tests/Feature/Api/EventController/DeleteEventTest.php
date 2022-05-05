<?php

namespace Tests\Feature\Api\EventController;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Laravel\Sanctum\Sanctum;
use App\Services\Contracts\Calendar\EventService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteEventTest extends TestCase
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
    public function guest_cannot_delete_an_event()
    {
        $this->deleteJson(route('api.events.destroy', rand()))
            ->assertUnauthorized();
    }

    /**
     * @test
     */
    public function user_can_delete_an_event()
    {
        $event = Event::factory()->make();

        $this->eventService
            ->shouldReceive('delete')
            ->with($event->object_id)
            ->once();

        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson(route('api.events.destroy', $event->object_id))
            ->assertNoContent();
    }
}
