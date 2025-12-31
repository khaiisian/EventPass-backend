<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organizer\CreateOrganizerRequest;
use App\Http\Requests\Organizer\UpdateOrganizerRequest;
use App\Http\Resources\OrganizerResource;
use App\Services\OrganizerService;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Support\Facades\Log;

class OrganizerController extends Controller
{
    use HttpResponses;

    protected OrganizerService $_organizerService;

    public function __construct(OrganizerService $organizerService)
    {
        $this->_organizerService = $organizerService;
    }

    public function index()
    {
        try {
            Log::info('Fetching organizers');
            $list = OrganizerResource::collection($this->_organizerService->getAll());
            return $this->success('success', $list, 'Organizers retrieved successfully', 200);
        } catch (Exception $e) {
            Log::error('Failed to fetch organizers', ['error' => $e->getMessage()]);
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function store(CreateOrganizerRequest $request)
    {
        try {
            $data = $request->validated();
            Log::info('Creating organizer', $data);

            $organizer = $this->_organizerService->create($data);

            return $this->success(
                'success',
                OrganizerResource::make($organizer),
                'Organizer created successfully.',
                200
            );
        } catch (Exception $e) {
            Log::error('Organizer creation failed', ['error' => $e->getMessage()]);
            return $this->fail('error', null, 'Organizer creation failed', 500);
        }
    }

    public function show($id)
    {
        try {
            Log::info('Fetching organizer', ['id' => $id]);
            $organizer = OrganizerResource::make($this->_organizerService->getById($id));
            return $this->success('success', $organizer, 'Organizer retrieved successfully', 200);
        } catch (Exception $e) {
            Log::error('Failed to fetch organizer', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function update(UpdateOrganizerRequest $request, $id)
    {
        try {
            $data = $request->validated();
            Log::info('Updating organizer', ['id' => $id, 'data' => $data]);

            $updated = $this->_organizerService->update($data, $id);

            if (!$updated) {
                Log::error('Organizer update failed', ['id' => $id]);
                return $this->fail(false, null, 'Update failed', 500);
            }

            $organizer = OrganizerResource::make($this->_organizerService->getById($id));

            return $this->success(true, $organizer, 'Organizer updated successfully', 200);
        } catch (Exception $e) {
            Log::error('Exception during organizer update', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Deleting organizer', ['id' => $id]);

            $deleted = $this->_organizerService->destroy($id);

            if (!$deleted) {
                Log::error('Organizer delete failed', ['id' => $id]);
                return $this->fail(false, null, 'Delete failed', 500);
            }

            return $this->success(true, null, 'Organizer deleted successfully', 200);
        } catch (Exception $e) {
            Log::error('Exception during organizer delete', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }
}