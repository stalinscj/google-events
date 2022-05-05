<?php

namespace App\Services\Contracts\Calendar;

use App\Models\Event;
use App\Services\Adapters\Calendar\EventListAdapter;

interface EventService
{
    /**
     * Returns all events.
     *
     * @param array $filters
     * @return \App\Services\Adapters\Calendar\EventListAdapter
     */
    public function fetchAll(array $filters): EventListAdapter;

    /**
     * Find an event by id.
     *
     * @param string $id
     * @return \App\Models\Event
     */
    public function findById(string $id): Event;

    /**
     * Create an event.
     *
     * @param \App\Models\Event $event
     * @return \App\Models\Event
     */
    public function create(Event $event): Event;

    /**
     * Update an event.
     *
     * @param string $id
     * @param \App\Models\Event $event
     * @return \App\Models\Event
     */
    public function update(string $id, Event $event): Event;

    /**
     * Delete an event.
     *
     * @param string $id
     */
    public function delete(string $id): bool;
}
