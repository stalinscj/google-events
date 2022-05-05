<?php

namespace App\Services\Google\Calendar;

use Illuminate\Support\Collection;
use App\Services\Adapters\Calendar\EventListAdapter;

class GoogleEventListAdapter extends EventListAdapter
{
    /**
     * Create a new adapter instance
     *
     * @param \Google\Service\Calendar\Events $events
     * @return \App\Services\Google\Calendar\GoogleEventListAdapter
     */
    public static function make($events)
    {
        $items = $events->getItems();

        $metadata = ['nextPageToken' => $events->getNextPageToken()];

        return new GoogleEventListAdapter($metadata, $items);
    }

    /**
     * Returns the next page token.
     *
     * @return string
     */
    public function getNextPageToken(): string
    {
        return $this->metadata['nextPageToken'] ?? '';
    }

    /**
     * Transform google events into app events
     *
     * @return \Illuminate\Support\Collection
     */
    public function transformItems(): Collection
    {
        $items = collect($this->items)
            ->map(fn ($item) => GoogleEventAdapter::make($item)->transformedEvent);

        return $items;
    }
}
