<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Requests\Api\ListEventRequest;
use App\Http\Requests\Api\UpsertEventRequest;
use App\Services\Contracts\Calendar\EventService;

class EventController extends Controller
{
    /**
     * The event service
     *
     * @var \App\Services\Contracts\Calendar\EventService
     */
    protected $eventService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\Api\ListEventRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ListEventRequest $request)
    {
        $filters = $request->query();

        $events = $this->eventService->fetchAll($filters);

        return EventResource::collection($events)->additional([
            'meta' => ['nextPageToken' => $events->getNextPageToken()]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\UpsertEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UpsertEventRequest $request)
    {
        $event = new Event($request->validated());

        $event = $this->eventService->create($event);

        return EventResource::make($event);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = $this->eventService->findById($id);

        return EventResource::make($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string  $id
     * @param  \App\Http\Requests\Api\UpsertEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpsertEventRequest $request)
    {
        $event = new Event($request->validated());

        $event = $this->eventService->update($id, $event);

        return EventResource::make($event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->eventService->delete($id);

        return response()->json([], 204);
    }
}
