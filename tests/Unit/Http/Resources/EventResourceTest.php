<?php

namespace Tests\Unit\Http\Resources;

use Tests\TestCase;
use App\Models\Event;
use App\Http\Resources\EventResource;

class EventResourceTest extends TestCase
{
    /**
     * @test
     */
    public function an_event_resource_must_have_the_necesary_fields()
    {
        $event = Event::factory()->make();

        $eventResource = EventResource::make($event)->resolve();

        $this->assertEquals($event->object_id, $eventResource['object_id']);

        $this->assertEquals($event->summary, $eventResource['summary']);

        $this->assertEquals($event->location, $eventResource['location']);

        $this->assertEquals($event->description, $eventResource['description']);

        $this->assertEquals($event->start, $eventResource['start']);

        $this->assertEquals($event->end, $eventResource['end']);

        $this->assertEquals($event->status, $eventResource['status']);
    }
}
