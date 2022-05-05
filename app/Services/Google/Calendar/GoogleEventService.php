<?php

namespace App\Services\Google\Calendar;

use App\Models\Event;
use Google\Service\Calendar;
use App\Services\Google\Google;
use App\Services\Contracts\Calendar\EventService;
use Google\Service\Calendar\Event as GoogleEvent;

class GoogleEventService extends Google implements EventService
{
    /**
     * Returns all events.
     *
     * @return array
     */
    public function fetchAll(array $filters): GoogleEventListAdapter
    {
        $filters = $this->sanitizeFilters($filters);

        $events = $this->getEventService()->listEvents('primary', $filters);

        return GoogleEventListAdapter::make($events);
    }

    /**
     * Find an event by id.
     *
     * @param string $id
     * @return \App\Models\Event
     */
    public function findById(string $id): Event
    {
        $event = $this->getEventService()->get('primary', $id);

        return GoogleEventAdapter::make($event)->transformedEvent;
    }

    /**
     * Create an event.
     *
     * @param \App\Models\Event $event
     * @return \App\Models\Event
     */
    public function create(Event $event): Event
    {
        $googleEvent = new GoogleEvent($event->toArray());

        $googleEvent = $this->getEventService()->insert('primary', $googleEvent);

        return GoogleEventAdapter::make($googleEvent)->transformedEvent;
    }

    /**
     * Update an event.
     *
     * @param string $id
     * @param \App\Models\Event $event
     * @return \App\Models\Event
     */
    public function update(string $id, Event $event): Event
    {
        $googleEvent = new GoogleEvent($event->toArray());

        $googleEvent = $this->getEventService()->update('primary', $id, $googleEvent);

        return GoogleEventAdapter::make($googleEvent)->transformedEvent;
    }

    /**
     * Delete an event.
     *
     * @param int $id
     */
    public function delete(string $id): bool
    {
        $response = $this->getEventService()->delete('primary', $id);

        return $response->getStatusCode() == 204;
    }

    /**
     * Returns a google event service
     *
     * @return \Google\Service\Calendar\Resource\Events
     */
    protected function getEventService()
    {
        $user   = auth()->user();
        $client = $this->getUserClient($user);

        $calendarService = new Calendar($client);

        return $calendarService->events;
    }

    /**
     * Remove unsupported filters
     *
     * @param array $filters
     * @return array
     */
    public function sanitizeFilters($filters)
    {
        $allowedFilters = [
            'timeMin',
            'timeMax',
            'timeZone',
            'pageToken',
            'showDeleted',
        ];

        return array_filter($filters, fn($key) => in_array($key, $allowedFilters), ARRAY_FILTER_USE_KEY);
    }
}
