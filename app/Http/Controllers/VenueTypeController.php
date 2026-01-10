<?php

namespace App\Http\Controllers;

use App\Http\Requests\VenueType\VenueTypeCreateRequest;
use App\Http\Requests\VenueType\VenueTypeUpdateRequest;
use App\Http\Resources\VenueTypeResource;
use App\Services\VenueTypeService;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VenueTypeController extends Controller
{
    use HttpResponses;

    protected VenueTypeService $venueTypeService;

    public function __construct(VenueTypeService $venueTypeService)
    {
        $this->venueTypeService = $venueTypeService;
    }

    public function index(Request $request)
    {
        try {
            // Optional per_page parameter
            $perPage = $request->get('per_page', 10);

            $paginator = $this->venueTypeService->getAll($perPage);

            return VenueTypeResource::collection($paginator)
                ->additional([
                    'status' => true,
                    'message' => 'Venue types retrieved successfully'
                ]);

        } catch (Exception $e) {
            Log::error('VenueType index error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Failed to retrieve venue types'
            ], 500);
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
            $data['ModifiedBy'] = auth()->user()?->UserCode ?? 'admin';

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