<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketType\TicketTypeCreateRequest;
use App\Http\Requests\TicketType\TicketTypeUpdateRequest;
use App\Http\Resources\TicketTypeResource;
use App\Services\TicketTypeService;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TicketTypeController extends Controller
{
    use HttpResponses;

    protected TicketTypeService $_ticketTypeService;

    public function __construct(TicketTypeService $ticketTypeService)
    {
        $this->_ticketTypeService = $ticketTypeService;
    }

    public function index(Request $request)
    {
        try {
            Log::info('Fetching ticket type list');

            // Optional: per_page query parameter, default 10
            $perPage = $request->get('per_page', 10);

            // Call the service
            $paginator = $this->_ticketTypeService->getAll($perPage);

            // Return a resource collection with additional metadata
            return TicketTypeResource::collection($paginator)
                ->additional([
                    'status' => true,
                    'message' => 'Ticket types retrieved successfully'
                ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch ticket types', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(TicketTypeCreateRequest $request)
    {
        try {
            $data = $request->validated();

            Log::info('Creating ticket type', $data);

            $ticketType = $this->_ticketTypeService->create($data);

            return $this->success(
                'success',
                TicketTypeResource::make($ticketType),
                'Ticket type created successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('Ticket type creation failed', [
                'error' => $e->getMessage()
            ]);

            return $this->fail('error', null, 'Ticket type creation failed', 500);
        }
    }

    public function show($id)
    {
        try {
            Log::info('Fetching ticket type', ['id' => $id]);

            $ticketType = TicketTypeResource::make(
                $this->_ticketTypeService->getById($id)
            );

            return $this->success('success', $ticketType, 'Ticket type retrieved successfully', 200);
        } catch (Exception $e) {
            Log::error('Failed to fetch ticket type', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function update(TicketTypeUpdateRequest $request, $id)
    {
        try {
            $data = $request->validated();

            Log::info('Updating ticket type', [
                'id' => $id,
                'data' => $data
            ]);

            $updated = $this->_ticketTypeService->update($data, $id);

            if (!$updated) {
                Log::error('Ticket type update failed', ['id' => $id]);
                return $this->fail(false, null, 'Update failed', 500);
            }

            $ticketType = TicketTypeResource::make(
                $this->_ticketTypeService->getById($id)
            );

            return $this->success(true, $ticketType, 'Ticket type updated successfully', 200);
        } catch (Exception $e) {
            Log::error('Exception during ticket type update', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Deleting ticket type', ['id' => $id]);

            $deleted = $this->_ticketTypeService->destroy($id);

            if (!$deleted) {
                Log::error('Ticket type delete failed', ['id' => $id]);
                return $this->fail(false, null, 'Delete failed', 500);
            }

            return $this->success(true, null, 'Ticket type deleted successfully', 200);
        } catch (Exception $e) {
            Log::error('Exception during ticket type delete', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }
}