<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\EventCreateRequest;
use App\Http\Requests\Event\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use Exception;
use App\Traits\HttpResponses;

class EventController extends Controller
{
    use HttpResponses;
    protected $_eventService;

    public function __construct(EventService $eventService)
    {
        $this->_eventService = $eventService;
    }

    public function index()
    {
        try {
            $list = EventResource::collection($this->_eventService->getAll());
            return $this->success('success', $list, 'Events retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function store(EventCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $data['EventCode'] = $this->_eventService->generateEventCode();
            $data['CreatedBy'] = 'admin';
            $data['CreatedAt'] = now();

            $result = EventResource::make($this->_eventService->create($data));
            return $this->success('success', $result, 'Event created successfully.', 200);
        } catch (Exception $e) {
            return $this->fail('error', null, 'Event creation failed', 500);
        }
    }

    public function show($id)
    {
        try {
            $event = EventResource::make($this->_eventService->getById($id));
            return $this->success('success', $event, 'Event retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function update(EventUpdateRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['ModifiedAt'] = now();
            $validatedData['ModifiedBy'] = 'admin';

            $update = $this->_eventService->update($validatedData, $id);
            $resEvent = EventResource::make($this->_eventService->getById($id));

            if ($update) {
                return $this->success(true, $resEvent, 'Event updated successfully', 200);
            } else {
                return $this->fail(false, null, 'Update failed', 500);
            }
        } catch (Exception $e) {
            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->_eventService->destroy($id);
            if ($deleted) {
                return $this->success(true, null, 'Event deleted successfully', 200);
            } else {
                return $this->fail(false, null, 'Delete failed', 500);
            }
        } catch (Exception $e) {
            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }
}