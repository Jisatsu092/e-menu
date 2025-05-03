<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Toping;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // try {
        //     // Status Meja
        //     $mejaKosong = Table::where('status', 'available')->count();
        //     $mejaTerisi = Table::where('status', 'occupied')->count();

        //     // User Statistics
        //     $totalUser = User::count();
        //     $activeUser = User::where('is_active', true)->count();

        //     // Revenue Calculations
        //     $dailyRevenue = Transaction::where('status', 'paid')
        //         ->whereDate('created_at', today())
        //         ->sum('total_price');

        //     $weeklyRevenue = Transaction::where('status', 'paid')
        //         ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
        //         ->sum('total_price');

        //     $monthlyRevenue = Transaction::where('status', 'paid')
        //         ->whereMonth('created_at', now()->month)
        //         ->sum('total_price');

        //     // Top Toppings
        //     $topToppings = Toping::withCount(['transactions' => function($query) {
        //             $query->where('status', 'paid');
        //         }])
        //         ->orderByDesc('transactions_count')
        //         ->limit(5)
        //         ->get();

        //     // Weekly Revenue Chart
        //     $weeklyRevenueChart = Transaction::selectRaw('
        //             DATE(created_at) as date, 
        //             SUM(total_price) as total')
        //         ->where('status', 'paid')
        //         ->whereBetween('created_at', [now()->subWeek(), now()])
        //         ->groupBy('date')
        //         ->get();

        //     return view('dashboard', compact(
        //         'mejaKosong', 'mejaTerisi',
        //         'totalUser', 'activeUser',
        //         'dailyRevenue', 'weeklyRevenue', 'monthlyRevenue',
        //         'topToppings', 'weeklyRevenueChart'
        //     ));

        // } catch (Exception $e) {
        //     Log::error('Dashboard Error: ' . $e->getMessage());
        //     return redirect()->route('error.index')->with('error', 'Gagal memuat dashboard');
        // }
    }
}