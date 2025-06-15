<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;

class VisitorController extends Controller
{
    public function recordVisit(Request $request){
        $ip = $request->ip();
        $visitorToken = $request->input('visitor_token');
        $date = now()->toDateString();

        $lastVisit = Visitor::where(function ($query) use ($ip, $visitorToken) {
            $query->where('ip', $ip)
                ->orWhere('visitor_token', $visitorToken);
        })
        ->where('created_at', '>', now()->subHour())
        ->first();

        if (!$lastVisit) {
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

    protected function updateAggregates($date){
        $weekStart = now()->startOfWeek()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();
        $yearStart = now()->startOfYear()->toDateString();

        Visitor::updateOrCreate(['period' => 'weekly', 'date' => $weekStart], ['count' => 0])->increment('count');
        Visitor::updateOrCreate(['period' => 'monthly', 'date' => $monthStart], ['count' => 0])->increment('count');
        Visitor::updateOrCreate(['period' => 'yearly', 'date' => $yearStart], ['count' => 0])->increment('count');
    }

    public function getCounts(){
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

    public function getWeekly(){
        $startOfWeek = now()->startOfWeek(); 
        $endOfWeek = now()->endOfWeek();     

        $weeklyData = Visitor::where('period', 'daily')
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->selectRaw('date, SUM(count) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $days = collect();
        foreach (range(0, 6) as $i) {
            $date = $startOfWeek->copy()->addDays($i);
            $days->put($date->format('l'), [
                'date' => $date->toDateString(),
                'count' => 0
            ]);
        }

        foreach ($weeklyData as $item) {
            $dayName = \Carbon\Carbon::parse($item->date)->format('l');

            $old = $days->get($dayName);

            $days->put($dayName, [
                'date' => $old['date'],
                'count' => $item->total
            ]);
        }

        return response()->json([
            'message' => 'Pengunjung mingguan:',
            'data' => $days
        ]);
    }

    public function getMonthly(){
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $monthlyData = Visitor::where('period', 'daily')
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get();

        $weeks = collect();

        foreach ($monthlyData as $item) {
            $date = \Carbon\Carbon::parse($item->date);
            $weekOfMonth = intval(ceil($date->day / 7));

            $key = 'Week ' . $weekOfMonth;
            if (!isset($weeks[$key])) {
                $weeks[$key] = 0;
            }

            $weeks[$key] += $item->count;
        }

        for ($i = 1; $i <= 5; $i++) {
            $key = 'Week ' . $i;
            if (!isset($weeks[$key])) {
                $weeks[$key] = 0;
            }
        }

        $weeks = collect($weeks)->sortKeys();

        return response()->json([
            'message' => 'Pengunjung bulanan per minggu:',
            'data' => $weeks
        ]);
    }


    public function getYearly(){
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        $yearlyData = Visitor::where('period', 'daily')
            ->whereBetween('date', [$startOfYear->toDateString(), $endOfYear->toDateString()])
            ->get();

        $months = collect();

        foreach ($yearlyData as $item) {
            $date = \Carbon\Carbon::parse($item->date);
            $monthNumber = $date->month; // 1 - 12
            $monthName = $date->locale('id')->translatedFormat('F'); // Nama bulan Indonesia, misal: Januari

            if (!isset($months[$monthName])) {
                $months[$monthName] = 0;
            }

            $months[$monthName] += $item->count;
        }

        // Pastikan semua bulan ada, meskipun 0
        $allMonths = collect([
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ]);

        $result = $allMonths->mapWithKeys(function ($month) use ($months) {
            return [$month => $months[$month] ?? 0];
        });

        return response()->json([
            'message' => 'Pengunjung per bulan dalam tahun ini:',
            'data' => $result
        ]);
    }



}