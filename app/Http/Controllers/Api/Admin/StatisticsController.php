<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatisticsService;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function dashboard(Request $request)
    {
        $data = $this->statisticsService->getDashboardData($request->all());

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function revenueReport(Request $request)
    {
        $data = $this->statisticsService->getRevenueReport($request->all());

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function productReport(Request $request)
    {
        $data = $this->statisticsService->getProductReport($request->all());

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function categoryReport(Request $request)
    {
        $data = $this->statisticsService->getCategoryReport($request->all());

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function dashboardStats()
    {
        $stats = $this->statisticsService->getOverallStats();
        return response()->json($stats);
    }

    public function revenueByMonth(Request $request)
    {
        $data = $this->statisticsService->getRevenueByMonth($request->all());

        return response()->json($data);
    }

    public function ordersStatus()
    {
        $stats = $this->statisticsService->getOrderStatusStats();
        return response()->json($stats);
    }
}
