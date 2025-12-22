<?php

namespace App\Http\Controllers;

use App\Http\Requests\VenueType\VenueTypeCreateRequest;
use App\Http\Requests\VenueType\VenueTypeUpdateRequest;
use App\Http\Resources\VenueTypeResource;
use App\Services\VenueTypeService;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Support\Facades\Log;

class VenueTypeController extends Controller
{
    use HttpResponses;

    protected VenueTypeService $venueTypeService;

    public function __construct(VenueTypeService $venueTypeService)
    {
        $this->venueTypeService = $venueTypeService;
    }

    public function index()
    {
        try {
            $list = VenueTypeResource::collection(
                $this->venueTypeService->getAll()
            );

            return $this->success(true, $list, 'Venue types retrieved successfully', 200);
        } catch (Exception $e) {
            Log::error('VenueType index error: ' . $e->getMessage());

            return $this->fail(false, null, 'Failed to retrieve venue types', 500);
        }
    }

    public function store(VenueTypeCreateRequest $request)
    {
        try {
            $venueType = $this->venueTypeService->create(
                $request->validated()
            );

            return $this->success(
                true,
                VenueTypeResource::make($venueType),
                'Venue type created successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('VenueType store error: ' . $e->getMessage());

            return $this->fail(false, null, 'Venue type creation failed', 500);
        }
    }

    public function show($id)
    {
        try {
            $venueType = $this->venueTypeService->getById($id);

            return $this->success(
                true,
                VenueTypeResource::make($venueType),
                'Venue type retrieved successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('VenueType show error: ' . $e->getMessage());

            return $this->fail(false, null, 'Venue type not found', 404);
        }
    }

    public function update(VenueTypeUpdateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $data['ModifiedAt'] = now();
            $data['ModifiedBy'] = 'admin';

            $this->venueTypeService->update($data, $id);

            $venueType = $this->venueTypeService->getById($id);

            return $this->success(
                true,
                VenueTypeResource::make($venueType),
                'Venue type updated successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('VenueType update error: ' . $e->getMessage());

            return $this->fail(false, null, 'Venue type update failed', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->venueTypeService->destroy($id);

            return $this->success(
                true,
                null,
                'Venue type deleted successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('VenueType delete error: ' . $e->getMessage());

            return $this->fail(false, null, 'Venue type delete failed', 500);
        }
    }
}