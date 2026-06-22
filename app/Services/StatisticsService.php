<?php

namespace App\Services;

use App\Repositories\StatisticsRepository;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    protected $statisticsRepository;

    public function __construct(StatisticsRepository $statisticsRepository)
    {
        $this->statisticsRepository = $statisticsRepository;
    }

    public function getDashboardData(array $params): array
    {
        $startDate = $params['start_date'] ?? now()->startOfMonth()->toDateTimeString();
        $endDate = $params['end_date'] ?? now()->endOfMonth()->toDateTimeString();

        $totalRevenue = $this->statisticsRepository->getTotalRevenue($startDate, $endDate);
        $totalOrders = $this->statisticsRepository->getTotalOrders($startDate, $endDate);
        $totalUsers = $this->statisticsRepository->getTotalUsers();
        $newUsers = $this->statisticsRepository->getNewUsers($startDate, $endDate);

        $bestSellingProducts = $this->statisticsRepository->getBestSellingProducts($startDate, $endDate);
        $revenueByDay = $this->statisticsRepository->getRevenueByDay($startDate, $endDate);
        $orderStats = $this->statisticsRepository->getOrderStatusStats($startDate, $endDate);

        return [
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'total_users' => $totalUsers,
                'new_users' => $newUsers,
            ],
            'best_selling_products' => $bestSellingProducts,
            'revenue_by_day' => $revenueByDay,
            'order_statistics' => $orderStats,
        ];
    }

    public function getRevenueReport(array $params)
    {
        $startDate = $params['start_date'] ?? now()->startOfMonth()->toDateTimeString();
        $endDate = $params['end_date'] ?? now()->endOfMonth()->toDateTimeString();
        $groupBy = $params['group_by'] ?? 'day'; // day, week, month

        $query = $this->statisticsRepository->getRevenueQuery($startDate, $endDate);

        switch ($groupBy) {
            case 'week':
                $query->select(
                    DB::raw('SUM(total_amount) as revenue'),
                    DB::raw('COUNT(*) as orders'),
                    DB::raw('YEARWEEK(created_at) as period')
                )->groupBy('period');
                break;
            case 'month':
                $query->select(
                    DB::raw('SUM(total_amount) as revenue'),
                    DB::raw('COUNT(*) as orders'),
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as period')
                )->groupBy('period');
                break;
            default: // day
                $query->select(
                    DB::raw('SUM(total_amount) as revenue'),
                    DB::raw('COUNT(*) as orders'),
                    DB::raw('DATE(created_at) as period')
                )->groupBy('period');
        }

        return $query->orderBy('period', 'asc')->get();
    }

    public function getProductReport(array $params)
    {
        $startDate = $params['start_date'] ?? now()->startOfMonth()->toDateTimeString();
        $endDate = $params['end_date'] ?? now()->endOfMonth()->toDateTimeString();

        return $this->statisticsRepository->getProductReport($startDate, $endDate);
    }

    public function getCategoryReport(array $params)
    {
        $startDate = $params['start_date'] ?? now()->startOfMonth()->toDateTimeString();
        $endDate = $params['end_date'] ?? now()->endOfMonth()->toDateTimeString();

        return $this->statisticsRepository->getCategoryReport($startDate, $endDate);
    }

    public function getOverallStats(): array
    {
        return $this->statisticsRepository->getDashboardOverallStats();
    }

    public function getRevenueByMonth(array $params): array
    {
        $year = $params['year'] ?? date('Y');
        
        $monthlyRevenue = $this->statisticsRepository->getMonthlyRevenueByYear($year);

        $labels = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
        $values = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $values[] = $monthlyRevenue->get($i)?->revenue ?? 0;
        }

        return [
            'labels' => $labels,
            'values' => $values
        ];
    }

    public function getOrderStatusStats(): array
    {
        $statusCounts = $this->statisticsRepository->getOrderStatusCounts();

        return [
            'pending' => $statusCounts['pending'] ?? 0,
            'confirmed' => $statusCounts['confirmed'] ?? 0,
            'processing' => $statusCounts['processing'] ?? 0,
            'shipping' => $statusCounts['shipping'] ?? 0,
            'completed' => $statusCounts['completed'] ?? 0,
            'cancelled' => $statusCounts['cancelled'] ?? 0
        ];
    }
}
