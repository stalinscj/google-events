<?php

namespace Tests\Unit\Services\Adapters\Support;

use Tests\TestCase;
use Illuminate\Support\Collection;
use App\Services\Adapters\Support\ListAdapter as ListAdapterAbstract;

class ListAdapterTest extends TestCase
{
    /**
     * @test
     */
    public function a_list_adapter_can_return_the_first_item_of_the_list()
    {
        $items = ['a', 'b', 'c'];

        shuffle($items);

        $listAdapter = new ListAdapter([], $items);

        $this->assertEquals($items[0], $listAdapter->first());
    }

    /**
     * @test
     */
    public function a_list_adapter_can_return_the_lists_transformed_items_for_mapping()
    {
        $items = ['a', 'b', 'c'];

        $listAdapter = new ListAdapter([], $items);

        $this->assertEquals($listAdapter->transformedItems, $listAdapter->mapInto());
    }
}

class ListAdapter extends ListAdapterAbstract
{
    function getNextPageToken(): string { return '';}

    function transformItems(): Collection { return collect($this->items); }
}
