<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventTypeCreateRequest;
use App\Http\Requests\EventTypeUpdateRequest;
use App\Http\Resources\EventTypeResource;
use App\Services\EventTypeService;
use Exception;
use App\Traits\HttpResponses;

class EventTypeController extends Controller
{
    use HttpResponses;
    protected $_eventTypeService;

    public function __construct(EventTypeService $eventTypeService)
    {
        $this->_eventTypeService = $eventTypeService;
    }

    public function index()
    {
        try {
            $list = EventTypeResource::collection($this->_eventTypeService->getAll());
            return $this->success('success', $list, 'Event types retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function store(EventTypeCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $data['EventTypeCode'] = $this->_eventTypeService->generateEventTypeCode();
            $data['CreatedBy'] = 'admin';
            $data['CreatedAt'] = now();

            $result = EventTypeResource::make($this->_eventTypeService->create($data));
            return $this->success('success', $result, 'Event type created successfully.', 200);
        } catch (Exception $e) {
            return $this->fail('error', null, 'Event type creation failed', 500);
        }
    }

    public function show($id)
    {
        try {
            $eventType = EventTypeResource::make($this->_eventTypeService->getById($id));
            return $this->success('success', $eventType, 'Event type retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function update(EventTypeUpdateRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['ModifiedAt'] = now();
            $validatedData['ModifiedBy'] = 'admin';

            $update = $this->_eventTypeService->update($validatedData, $id);
            $resEventType = EventTypeResource::make($this->_eventTypeService->getById($id));

            if ($update) {
                return $this->success(true, $resEventType, 'Event type updated successfully', 200);
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
            $deleted = $this->_eventTypeService->destroy($id);
            if ($deleted) {
                return $this->success(true, null, 'Event type deleted successfully', 200);
            } else {
                return $this->fail(false, null, 'Delete failed', 500);
            }
        } catch (Exception $e) {
            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }
}