<?php

namespace App\Http\Controllers;

use App\Http\Requests\VenueTypeCreateRequest;
use App\Http\Requests\VenueTypeUpdateRequest;
use App\Http\Resources\VenueTypeResource;
use App\Models\Tbl_VenueType;
use App\Services\VenueTypeService;
use Exception;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class VenueTypeController extends Controller
{
    use HttpResponses;
    protected $_venueTypeService;

    public function __construct(VenueTypeService $venueTypeService)
    {
        $this->_venueTypeService = $venueTypeService;
    }

    /**
     * Display a listing of venue types.
     */
    public function index()
    {
        try {
            $list = VenueTypeResource::collection($this->_venueTypeService->getAll());
            return $this->success('success', $list, 'Venue types retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created venue type.
     */
    public function store(VenueTypeCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $data["VenueTypeCode"] = $this->_venueTypeService->generateVenueTypeCode();
            $data['CreatedBy'] = 'admin';
            $data['CreatedAt'] = now();

            $result = VenueTypeResource::make($this->_venueTypeService->create($data));
            return $this->success('success', $result, 'Venue type created successfully.', 200);
        } catch (Exception $e) {
            return $this->fail('error', null, 'Venue type creation failed', 500);
        }
    }

    /**
     * Display the specified venue type.
     */
    public function show($id)
    {
        try {
            $venueType = VenueTypeResource::make($this->_venueTypeService->getById($id));
            return $this->success('success', $venueType, 'Venue type retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified venue type.
     */
    public function update(VenueTypeUpdateRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['ModifiedAt'] = now();
            $validatedData['ModifiedBy'] = 'admin';

            $update = $this->_venueTypeService->update($validatedData, $id);
            $resVenueType = VenueTypeResource::make($this->_venueTypeService->getById($id));

            if ($update) {
                return $this->success(true, $resVenueType, 'Venue type updated successfully', 200);
            } else {
                return $this->fail(false, null, 'Update failed', 500);
            }
        } catch (Exception $e) {
            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified venue type (soft delete).
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->_venueTypeService->destroy($id);
            if ($deleted) {
                return $this->success(true, null, 'Venue type deleted successfully', 200);
            } else {
                return $this->fail(false, null, 'Delete failed', 500);
            }
        } catch (Exception $e) {
            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }
}