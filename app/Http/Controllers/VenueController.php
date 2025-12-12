<?php

namespace App\Http\Controllers;

use App\Http\Requests\VenueCreateRequest;
use App\Http\Requests\VenueUpdateRequest;
use App\Http\Resources\VenueResource;
use App\Services\VenueService;
use Exception;
use App\Traits\HttpResponses;

class VenueController extends Controller
{
    use HttpResponses;

    protected $_venueService;

    public function __construct(VenueService $venueService)
    {
        $this->_venueService = $venueService;
    }

    public function index()
    {
        try {
            $venues = VenueResource::collection($this->_venueService->getAll());
            return $this->success('success', $venues, 'Venues retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function store(VenueCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $data['CreatedBy'] = 'admin';
            $data['CreatedAt'] = now();

            $venue = VenueResource::make($this->_venueService->create($data));
            return $this->success('success', $venue, 'Venue created successfully', 200);
        } catch (Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $venue = VenueResource::make($this->_venueService->getById($id));
            return $this->success('success', $venue, 'Venue retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function update(VenueUpdateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $data['ModifiedBy'] = 'admin';
            $data['ModifiedAt'] = now();

            $this->_venueService->update($data, $id);
            $venue = VenueResource::make($this->_venueService->getById($id));

            return $this->success(true, $venue, 'Venue updated successfully', 200);
        } catch (Exception $e) {
            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->_venueService->destroy($id);
            if ($deleted) {
                return $this->success(true, null, 'Venue deleted successfully', 200);
            } else {
                return $this->fail(false, null, 'Delete failed', 500);
            }
        } catch (Exception $e) {
            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }
}