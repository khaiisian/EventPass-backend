<?php

namespace App\Services;

use App\Models\User;
use App\Models\Event;
use App\Models\organizer;
use App\Models\Venue;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getMetrics()
    {
        $dailyRevenue = Transaction::where('DeleteFlag', false)
            ->where('Status', true)
            ->select(
                DB::raw('DATE(TransactionDate) as date'),
                DB::raw('SUM(TotalAmount) as revenue')
            )
            ->groupBy(DB::raw('DATE(TransactionDate)'))
            ->orderBy('date')
            ->get();

        return [
            'total_events' => Event::where('DeleteFlag', false)->count(),
            'total_active_users' => User::where('DeleteFlag', false)
                ->where('Role', 'CUSTOMER')
                ->count(),
            'total_organizers' => organizer::where('DeleteFlag', false)->count(),
            'total_venues' => Venue::where('DeleteFlag', false)->count(),
            'daily_revenue' => $dailyRevenue
        ];
    }
}