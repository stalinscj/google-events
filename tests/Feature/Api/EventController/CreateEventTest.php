<?php

namespace Tests\Feature\Api\EventController;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Laravel\Sanctum\Sanctum;
use App\Services\Contracts\Calendar\EventService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateEventTest extends TestCase
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
    public function guest_cannot_create_an_event()
    {
        $this->postJson(route('api.events.store', rand()))
            ->assertUnauthorized();
    }

    /**
     * @test
     */
    public function user_can_create_an_event()
    {
        $event = Event::factory()->make();

        $this->eventService
            ->shouldReceive('create')
            ->once()
            ->andReturn($event);

        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.events.store'), $event->toArray())
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

    /**
     * @test
     */
    public function the_summary_attribute_is_validated()
    {
        $this->eventService
            ->shouldReceive('create');

        Sanctum::actingAs(User::factory()->create());

        // The summary attribute is required
        $this->postJson(route('api.events.store'), Event::factory()->raw(['summary' => null]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['summary']
            ]);

        // The summary attribute must be a string
        $this->postJson(route('api.events.store'), Event::factory()->raw(['summary' => ['Not a string' => 8]]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['summary']
            ]);
    }

    /**
     * @test
     */
    public function the_location_attribute_is_validated()
    {
        $this->eventService
            ->shouldReceive('create');

        Sanctum::actingAs(User::factory()->create());

        // The location attribute is required
        $this->postJson(route('api.events.store'), Event::factory()->raw(['location' => null]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['location']
            ]);

        // The location attribute must be a string
        $this->postJson(route('api.events.store'), Event::factory()->raw(['location' => ['Not a string' => 8]]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['location']
            ]);
    }

    /**
     * @test
     */
    public function the_description_attribute_is_validated()
    {
        $this->eventService
            ->shouldReceive('create');

        Sanctum::actingAs(User::factory()->create());

        // The description attribute is required
        $this->postJson(route('api.events.store'), Event::factory()->raw(['description' => null]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['description']
            ]);

        // The description attribute must be a string
        $this->postJson(route('api.events.store'), Event::factory()->raw(['description' => ['Not a string' => 8]]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['description']
            ]);
    }

    /**
     * @test
     */
    public function the_start_attribute_is_validated()
    {
        $this->eventService
            ->shouldReceive('create');

        Sanctum::actingAs(User::factory()->create());

        // The start attribute is required
        $this->postJson(route('api.events.store'), Event::factory()->raw(['start' => null]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['start']
            ]);

        // The start.dateTime attribute is required
        $this->postJson(route('api.events.store'), Event::factory()->raw(['start' => ['dateTime' => null]]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['start.dateTime']
            ]);

        // The start.dateTime attribute must be an RFC3339 timestamp with mandatory time zone offset
        $this->postJson(route('api.events.store'), Event::factory()->raw(['start' => ['dateTime' => '01/01/2022']]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['start.dateTime']
            ]);

        // The start.timeZone attribute is required
        $this->postJson(route('api.events.store'), Event::factory()->raw(['start' => ['timeZone' => null]]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['start.timeZone']
            ]);

        // The start.timeZone attribute must be a valid timezone
        $this->postJson(route('api.events.store'), Event::factory()->raw(['start' => ['timeZone' => 'InvalidTimeZone']]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['start.timeZone']
            ]);
    }

    /**
     * @test
     */
    public function the_end_attribute_is_validated()
    {
        $this->eventService
            ->shouldReceive('create');

        Sanctum::actingAs(User::factory()->create());

        // The end attribute is required
        $this->postJson(route('api.events.store'), Event::factory()->raw(['end' => null]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['end']
            ]);

        // The end.dateTime attribute is required
        $this->postJson(route('api.events.store'), Event::factory()->raw(['end' => ['dateTime' => null]]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['end.dateTime']
            ]);

        // The end.dateTime attribute must be an RFC3339 timestamp with mandatory time zone offset
        $this->postJson(route('api.events.store'), Event::factory()->raw(['end' => ['dateTime' => '01/01/2022']]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['end.dateTime']
            ]);

        // The end.timeZone attribute is required
        $this->postJson(route('api.events.store'), Event::factory()->raw(['end' => ['timeZone' => null]]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['end.timeZone']
            ]);

        // The end.timeZone attribute must be a valid timezone
        $this->postJson(route('api.events.store'), Event::factory()->raw(['end' => ['timeZone' => 'InvalidTimeZone']]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['end.timeZone']
            ]);
    }

    /**
     * @test
     */
    public function the_status_attribute_is_validated()
    {
        $this->eventService
            ->shouldReceive('create');

        Sanctum::actingAs(User::factory()->create());

        // The status attribute is required
        $this->postJson(route('api.events.store'), Event::factory()->raw(['status' => null]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['status']
            ]);

        // The status attribute must be a string
        $this->postJson(route('api.events.store'), Event::factory()->raw(['status' => ['Not a string' => 8]]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['status']
            ]);

        // The status attribute must be a valid status
        $this->postJson(route('api.events.store'), Event::factory()->raw(['status' => 'InvalidStatus']))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['status']
            ]);
    }
}
