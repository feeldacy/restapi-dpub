<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;

class VisitorController extends Controller
{
    public function recordVisit(Request $request)
    {
        $ip = $request->ip();
        $visitorToken = $request->input('visitor_token');
        $date = now()->toDateString();

        // Check if this visitor (by IP or token) has visited in the last hour
        $lastVisit = Visitor::where(function ($query) use ($ip, $visitorToken) {
            $query->where('ip', $ip)
                ->orWhere('visitor_token', $visitorToken);
        })
        ->where('created_at', '>', now()->subHour())
        ->first();

        if (!$lastVisit) {
            // Find or create a daily record for this visitor
            $visit = Visitor::firstOrCreate(
                [
                    'period' => 'daily',
                    'date' => $date,
                    'ip' => $ip,
                    'visitor_token' => $visitorToken
                ],
                ['count' => 0]
            );

            $visit->increment('count');
            $this->updateAggregates($date);

            return response()->json(['message' => 'Visit recorded', 'count' => $visit->count]);
        }

        return response()->json(['message' => 'Visit already recorded within the last hour']);
    }

    protected function updateAggregates($date)
    {
        $weekStart = now()->startOfWeek()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();
        $yearStart = now()->startOfYear()->toDateString();

        Visitor::updateOrCreate(['period' => 'weekly', 'date' => $weekStart], ['count' => 0])->increment('count');
        Visitor::updateOrCreate(['period' => 'monthly', 'date' => $monthStart], ['count' => 0])->increment('count');
        Visitor::updateOrCreate(['period' => 'yearly', 'date' => $yearStart], ['count' => 0])->increment('count');
    }

    public function getCounts()
    {
        $today = now()->toDateString();
        $weekStart = now()->startOfWeek();
        $monthStart = now()->startOfMonth();
        $yearStart = now()->startOfYear();

        $daily = Visitor::where('period', 'daily')
            ->whereDate('date', $today)
            ->sum('count');

        $weekly = Visitor::where('period', 'daily')
            ->whereDate('date', '>=', $weekStart)
            ->sum('count');

        $monthly = Visitor::where('period', 'daily')
            ->whereDate('date', '>=', $monthStart)
            ->sum('count');

        $yearly = Visitor::where('period', 'daily')
            ->whereDate('date', '>=', $yearStart)
            ->sum('count');

        $total = Visitor::where('period', 'daily')->sum('count');

        return response()->json([
            'message' => 'Success',
            'data' => [
                'daily' => $daily,
                'weekly' => $weekly,
                'monthly' => $monthly,
                'yearly' => $yearly,
                'total' => $total
            ]
        ]);
    }
}