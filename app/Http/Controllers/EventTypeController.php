<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventType\EventTypeCreateRequest;
use App\Http\Requests\EventType\EventTypeUpdateRequest;
use App\Http\Resources\EventTypeResource;
use App\Services\EventTypeService;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Support\Facades\Log;

class EventTypeController extends Controller
{
    use HttpResponses;

    protected EventTypeService $_eventTypeService;

    public function __construct(EventTypeService $eventTypeService)
    {
        $this->_eventTypeService = $eventTypeService;
    }

    public function index()
    {
        try {
            Log::info('Fetching event type list');

            $list = EventTypeResource::collection(
                $this->_eventTypeService->getAll()
            );

            return $this->success('success', $list, 'Event types retrieved successfully', 200);
        } catch (Exception $e) {
            Log::error('Failed to fetch event types', [
                'error' => $e->getMessage()
            ]);

            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function store(EventTypeCreateRequest $request)
    {
        try {
            $data = $request->validated();

            Log::info('Creating event type', $data);

            $eventType = $this->_eventTypeService->create($data);

            return $this->success(
                'success',
                EventTypeResource::make($eventType),
                'Event type created successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('Event type creation failed', [
                'error' => $e->getMessage()
            ]);

            return $this->fail('error', null, 'Event type creation failed', 500);
        }
    }

    public function show($id)
    {
        try {
            Log::info('Fetching event type', ['id' => $id]);

            $eventType = EventTypeResource::make(
                $this->_eventTypeService->getById($id)
            );

            return $this->success('success', $eventType, 'Event type retrieved successfully', 200);
        } catch (Exception $e) {
            Log::error('Failed to fetch event type', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function update(EventTypeUpdateRequest $request, $id)
    {
        try {
            $data = $request->validated();

            Log::info('Updating event type', [
                'id' => $id,
                'data' => $data
            ]);

            $updated = $this->_eventTypeService->update($data, $id);

            if (!$updated) {
                Log::error('Event type update failed', ['id' => $id]);
                return $this->fail(false, null, 'Update failed', 500);
            }

            $eventType = EventTypeResource::make(
                $this->_eventTypeService->getById($id)
            );

            return $this->success(true, $eventType, 'Event type updated successfully', 200);
        } catch (Exception $e) {
            Log::error('Exception during event type update', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Deleting event type', ['id' => $id]);

            $deleted = $this->_eventTypeService->destroy($id);

            if (!$deleted) {
                Log::error('Event type delete failed', ['id' => $id]);
                return $this->fail(false, null, 'Delete failed', 500);
            }

            return $this->success(true, null, 'Event type deleted successfully', 200);
        } catch (Exception $e) {
            Log::error('Exception during event type delete', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }
}