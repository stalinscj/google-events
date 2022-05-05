<?php
namespace App\Services\Adapters\Support;

use Illuminate\Support\Collection;

abstract class ListAdapter
{
    /**
     * List's metadata.
     *
     * @var array<string, mixed>
     */
    protected $metadata;

    /**
     * List's items.
     *
     * @var array<mixed, mixed>
     */
    protected $items;

    /**
     * List's transformed items.
     *
     * @var \Illuminate\Support\Collection
     */
    public $transformedItems;

    /**
     * Create a new controller instance.
     *
     * @param array $metadata
     * @param array $items
     * @return void
     */
    public function __construct($metadata, $items) {
        $this->metadata         = $metadata;
        $this->items            = $items;
        $this->transformedItems = $this->transformItems();
    }

    /**
     * Get the first item from the list.
     *
     * @return mixed
     */
    public function first(): mixed
    {
        return $this->transformedItems->first();
    }

    /**
     * Returns the list's transformed items for mapping.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapInto(): Collection
    {
        return $this->transformedItems;
    }

    /**
     * Returns the next page token.
     *
     * @return string
     */
    abstract public function getNextPageToken(): string;

    /**
     * Transform service events into app events
     *
     * @return \Illuminate\Support\Collection
     */
    abstract public function transformItems(): Collection;
}
