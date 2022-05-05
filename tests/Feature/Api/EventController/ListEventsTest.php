<?php

namespace Tests\Feature\Api\EventController;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\Contracts\Calendar\EventService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Adapters\Calendar\EventListAdapter;

class ListEventsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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
    public function guest_cannot_fetch_the_event_list()
    {
        $this->getJson(route('api.events.index'))
            ->assertUnauthorized();
    }

    /**
     * @test
     */
    public function user_can_fetch_the_event_list()
    {
        $filters = $this->getValidFilters();
        $eventList = EventListAdapterFake::make();

        $this->eventService
            ->shouldReceive('fetchAll')
            ->with($filters)
            ->once()
            ->andReturn($eventList);

        Sanctum::actingAs(User::factory()->create());

        $this->getJson(route('api.events.index', $filters))
            ->assertOk()
            ->assertJson([
                'data' => $eventList->transformedItems->toArray(),
                'meta' => ['nextPageToken' => $eventList->getNextPageToken()],
            ]);;
    }

    /**
     * @test
     */
    public function the_time_min_filter_is_validated()
    {
        $this->eventService
            ->shouldReceive('fetchAll')
            ->andReturn(EventListAdapterFake::make());

        Sanctum::actingAs(User::factory()->create());

        // The timeMin filter is optional
        $this->getJson(route('api.events.index', $this->getValidFilters(['timeMin' => null])))
            ->assertOk();

        // The timeMin filter must be an RFC3339 timestamp with mandatory time zone offset
        $this->getJson(route('api.events.index', $this->getValidFilters(['timeMin' => '01/01/2022'])))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['timeMin']
            ]);
    }

    /**
     * @test
     */
    public function the_time_max_filter_is_validated()
    {
        $this->eventService
            ->shouldReceive('fetchAll')
            ->andReturn(EventListAdapterFake::make());

        Sanctum::actingAs(User::factory()->create());

        // The timeMax filter is optional
        $this->getJson(route('api.events.index', $this->getValidFilters(['timeMax' => null])))
            ->assertOk();

        // The timeMax filter must be an RFC3339 timestamp with mandatory time zone offset
        $this->getJson(route('api.events.index', $this->getValidFilters(['timeMax' => '01/01/2022'])))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['timeMax']
            ]);
    }

    /**
     * @test
     */
    public function the_time_zone_filter_is_validated()
    {
        $this->eventService
            ->shouldReceive('fetchAll')
            ->andReturn(EventListAdapterFake::make());

        Sanctum::actingAs(User::factory()->create());

        // The timeZone filter is optional
        $this->getJson(route('api.events.index', $this->getValidFilters(['timeZone' => null])))
            ->assertOk();

        // The timeZone filter must be a valid timezone
        $this->getJson(route('api.events.index', $this->getValidFilters(['timeZone' => 'InvalidTimeZone'])))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['timeZone']
            ]);
    }

    /**
     * @test
     */
    public function the_page_token_filter_is_validated()
    {
        $this->eventService
            ->shouldReceive('fetchAll')
            ->andReturn(EventListAdapterFake::make());

        Sanctum::actingAs(User::factory()->create());

        // The pageToken filter is optional
        $this->getJson(route('api.events.index', $this->getValidFilters(['pageToken' => null])))
            ->assertOk();

        // The pageToken filter must be a string
        $this->getJson(route('api.events.index', $this->getValidFilters(['pageToken' => ['not a string' => 1]])))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['pageToken']
            ]);
    }

    /**
     * @test
     */
    public function the_show_deleted_filter_is_validated()
    {
        $this->eventService
            ->shouldReceive('fetchAll')
            ->andReturn(EventListAdapterFake::make());

        Sanctum::actingAs(User::factory()->create());

        // The showDeleted filter is optional
        $this->getJson(route('api.events.index', $this->getValidFilters(['showDeleted' => null])))
            ->assertOk();

        // The showDeleted filter must be a boolean
        $this->getJson(route('api.events.index', $this->getValidFilters(['showDeleted' => 'not a boolean'])))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['showDeleted']
            ]);
    }

    /**
     * Returns valid filters for list events.
     *
     * @param $filters
     * @return array
     */
    protected function getValidFilters($filters = [])
    {
        $validFilters = [
            'timeMin'     => now()->toIso8601String(),
            'timeMax'     => now()->addMonth()->toIso8601String(),
            'timeZone'    => $this->faker->timezone(),
            'pageToken'   => $this->faker->bothify('?##??#??'),
            'showDeleted' => $this->faker->boolean(),
        ];

        return array_merge($validFilters, $filters);
    }
}

class EventListAdapterFake extends EventListAdapter {

    public function getNextPageToken(): string
    {
        return $this->metadata['nextPageToken'];
    }

    public function transformItems(): Collection
    {
        return collect($this->items);
    }

    public static function make()
    {
        $metadata = ['nextPageToken' => Str::random(8)];
        $items    = Event::factory(3)->make();

        return new EventListAdapterFake($metadata, $items);
    }
}
