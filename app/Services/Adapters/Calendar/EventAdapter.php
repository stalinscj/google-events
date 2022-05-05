<?php

namespace App\Services\Adapters\Calendar;

use App\Models\Event;

abstract class EventAdapter
{
    /**
     * Service Event
     *
     * @var mixed
     */
    protected $event;

    /**
     * Transformed Event
     *
     * @var \App\Models\Event
     */
    public $transformedEvent;

    /**
     * Create a new adapter instance
     *
     * @param mixed $event
     */
    public function __construct($event) {
        $this->event            = $event;
        $this->transformedEvent = $this->transform();
    }

    /**
     * Transform service event into app event
     *
     * @return \App\Models\Event
     */
    abstract public function transform(): Event;
}
