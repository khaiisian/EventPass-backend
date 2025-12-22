<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketType\TicketTypeCreateRequest;
use App\Http\Requests\TicketType\TicketTypeUpdateRequest;
use App\Http\Resources\TicketTypeResource;
use App\Services\TicketTypeService;
use Exception;
use App\Traits\HttpResponses;

class TicketTypeController extends Controller
{

    use HttpResponses;

    protected $_ticketTypeService;

    public function __construct(TicketTypeService $ticketTypeService)
    {
        $this->_ticketTypeService = $ticketTypeService;
    }

    public function index()
    {
        try {
            $list = TicketTypeResource::collection($this->_ticketTypeService->getAll());
            return $this->success('success', $list, 'Ticket types retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function store(TicketTypeCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $data['TicketTypeCode'] = $this->_ticketTypeService->generateTicketTypeCode();
            $data['CreatedBy'] = 'admin';
            $data['CreatedAt'] = now();

            $result = TicketTypeResource::make($this->_ticketTypeService->create($data));
            return $this->success('success', $result, 'Ticket type created successfully.', 200);
        } catch (Exception $e) {
            return $this->fail('error', null, 'Ticket type creation failed', 500);
        }
    }

    public function show($id)
    {
        try {
            $ticketType = TicketTypeResource::make($this->_ticketTypeService->getById($id));
            return $this->success('success', $ticketType, 'Ticket type retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function update(TicketTypeUpdateRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['ModifiedAt'] = now();
            $validatedData['ModifiedBy'] = 'admin';

            $update = $this->_ticketTypeService->update($validatedData, $id);
            $resTicketType = TicketTypeResource::make($this->_ticketTypeService->getById($id));

            if ($update) {
                return $this->success(true, $resTicketType, 'Ticket type updated successfully', 200);
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
            $deleted = $this->_ticketTypeService->destroy($id);
            if ($deleted) {
                return $this->success(true, null, 'Ticket type deleted successfully', 200);
            } else {
                return $this->fail(false, null, 'Delete failed', 500);
            }
        } catch (Exception $e) {
            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }
}