<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    use HttpResponses;

    protected DashboardService $_dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->_dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        try {
            Log::info('Fetching dashboard metrics');

            $data = $this->_dashboardService->getMetrics();

            return $this->success(
                true,
                $data,
                'Dashboard data retrieved successfully',
                200
            );

        } catch (Exception $e) {
            Log::error('Failed to fetch dashboard data', [
                'error' => $e->getMessage()
            ]);

            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }

}