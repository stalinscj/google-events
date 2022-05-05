<?php

namespace App\Services\Google\Calendar;

use App\Models\Event;
use App\Services\Adapters\Calendar\EventAdapter;

class GoogleEventAdapter extends EventAdapter
{
    /**
     * Google Event
     *
     * @var \Google\Service\Calendar\Event
     */
    protected $event;

    /**
     * Create a new adapter instance
     *
     * @param \Google\Service\Calendar\Event $event
     * @return \App\Services\Google\Calendar\GoogleEventAdapter
     */
    public static function make($event)
    {
        return new GoogleEventAdapter($event);
    }

    /**
     * Transform google event into app event
     *
     * @return \App\Models\Event
     */
    public function transform(): Event
    {
        $event = new Event([
            'object_id'   => $this->event->getId(),
            'summary'     => $this->event->getSummary(),
            'location'    => $this->event->getLocation(),
            'description' => $this->event->getDescription(),
            'start'       => $this->event->getStart(),
            'end'         => $this->event->getEnd(),
            'status'      => $this->event->getStatus(),
        ]);

        return $event;
    }
}
