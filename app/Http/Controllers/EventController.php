<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\EventCreateRequest;
use App\Http\Requests\Event\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    use HttpResponses;

    protected EventService $_eventService;

    public function __construct(EventService $eventService)
    {
        $this->_eventService = $eventService;
    }

    public function index()
    {
        try {
            Log::info('Fetching events');

            $list = EventResource::collection($this->_eventService->getAll());
            return $this->success('success', $list, 'Events retrieved successfully', 200);
        } catch (Exception $e) {
            Log::error('Failed to fetch events', [
                'error' => $e->getMessage()
            ]);

            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function store(EventCreateRequest $request)
    {
        try {
            $data = $request->validated();

            Log::info('Creating event', $data);

            $event = $this->_eventService->create($data);

            return $this->success(
                'success',
                EventResource::make($event),
                'Event created successfully.',
                200
            );
        } catch (Exception $e) {
            Log::error('Event creation failed', [
                'error' => $e->getMessage()
            ]);

            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function getTopEvents()
    {
        try {
            Log::info('Fetching top events');

            $list = EventResource::collection($this->_eventService->getTopEvents());
            return $this->success('success', $list, 'Top events retrieved successfully', 200);
        } catch (Exception $e) {
            Log::error('Failed to fetch top events', [
                'error' => $e->getMessage()
            ]);

            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            Log::info('Fetching event', ['id' => $id]);

            $event = EventResource::make($this->_eventService->getById($id));
            return $this->success('success', $event, 'Event retrieved successfully', 200);
        } catch (Exception $e) {
            Log::error('Failed to fetch event', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function update(EventUpdateRequest $request, $id)
    {
        try {
            $data = $request->validated();

            Log::info('Updating event', [
                'id' => $id,
                'data' => $data
            ]);

            $updated = $this->_eventService->update($data, $id);

            if (!$updated) {
                Log::error('Event update failed', ['id' => $id]);
                return $this->fail(false, null, 'Update failed', 500);
            }

            $event = EventResource::make($this->_eventService->getById($id));

            return $this->success(true, $event, 'Event updated successfully', 200);
        } catch (Exception $e) {
            Log::error('Exception during event update', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Deleting event', ['id' => $id]);

            $deleted = $this->_eventService->destroy($id);

            if (!$deleted) {
                Log::error('Event delete failed', ['id' => $id]);
                return $this->fail(false, null, 'Delete failed', 500);
            }

            return $this->success(true, null, 'Event deleted successfully', 200);
        } catch (Exception $e) {
            Log::error('Exception during event delete', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }
}