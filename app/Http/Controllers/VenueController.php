<?php

namespace App\Http\Controllers;

use App\Http\Requests\Venue\VenueCreateRequest;
use App\Http\Requests\Venue\VenueUpdateRequest;
use App\Http\Resources\VenueResource;
use App\Services\VenueService;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Support\Facades\Log;

class VenueController extends Controller
{
    use HttpResponses;

    protected VenueService $venueService;

    public function __construct(VenueService $venueService)
    {
        $this->venueService = $venueService;
    }

    public function index()
    {
        try {
            $venues = VenueResource::collection(
                $this->venueService->getAll()
            );

            return $this->success(true, $venues, 'Venues retrieved successfully', 200);
        } catch (Exception $e) {
            Log::error('Venue index error: ' . $e->getMessage());

            return $this->fail(false, null, 'Failed to retrieve venues', 500);
        }
    }

    public function getTopVenues()
    {
        try {
            $topVenues = VenueResource::collection(
                $this->venueService->getTopVenues()
            );

            return $this->success(true, $topVenues, 'Venues retrieved successfully', 200);
        } catch (Exception $e) {
            Log::error('Venue index error: ' . $e->getMessage());

            return $this->fail(false, null, 'Failed to retrieve venues', 500);
        }
    }

    public function store(VenueCreateRequest $request)
    {
        try {
            $venue = $this->venueService->create(
                $request->validated()
            );

            return $this->success(
                true,
                VenueResource::make($venue),
                'Venue created successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('Venue store error: ' . $e->getMessage());

            return $this->fail(false, null, 'Venue creation failed', 500);
        }
    }

    public function show($id)
    {
        try {
            $venue = $this->venueService->getById($id);

            return $this->success(
                true,
                VenueResource::make($venue),
                'Venue retrieved successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('Venue show error (ID ' . $id . '): ' . $e->getMessage());

            return $this->fail(false, null, 'Venue not found', 404);
        }
    }

    public function update(VenueUpdateRequest $request, $id)
    {
        try {
            $this->venueService->update(
                $request->validated(),
                $id
            );

            $venue = $this->venueService->getById($id);

            return $this->success(
                true,
                VenueResource::make($venue),
                'Venue updated successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('Venue update error (ID ' . $id . '): ' . $e->getMessage());

            return $this->fail(false, null, 'Venue update failed', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->venueService->destroy($id);

            return $this->success(
                true,
                null,
                'Venue deleted successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('Venue delete error (ID ' . $id . '): ' . $e->getMessage());

            return $this->fail(false, null, 'Venue delete failed', 500);
        }
    }
}