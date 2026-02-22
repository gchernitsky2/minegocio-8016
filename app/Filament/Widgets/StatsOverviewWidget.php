<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $salesThisMonth = (float) Sale::whereMonth('date', $currentMonth->month)
            ->whereYear('date', $currentMonth->year)
            ->sum('amount');

        $salesLastMonth = (float) Sale::whereMonth('date', $lastMonth->month)
            ->whereYear('date', $lastMonth->year)
            ->sum('amount');

        $expensesThisMonth = (float) Expense::whereMonth('date', $currentMonth->month)
            ->whereYear('date', $currentMonth->year)
            ->sum('amount');

        $expensesLastMonth = (float) Expense::whereMonth('date', $lastMonth->month)
            ->whereYear('date', $lastMonth->year)
            ->sum('amount');

        $balanceThisMonth = $salesThisMonth - $expensesThisMonth;
        $balanceLastMonth = $salesLastMonth - $expensesLastMonth;

        $totalProducts = Product::count();
        $lowStockProducts = Product::lowStock()->count();
        $inventoryValue = (float) (Product::selectRaw('SUM(stock * cost) as total')->value('total') ?? 0);

        return [
            Stat::make('Ventas del mes', '$' . number_format($salesThisMonth, 2))
                ->description($this->getChangeDescription($salesThisMonth, $salesLastMonth))
                ->descriptionIcon($this->getChangeIcon($salesThisMonth, $salesLastMonth))
                ->color('success')
                ->chart($this->getDailyTrend(Sale::class)),

            Stat::make('Gastos del mes', '$' . number_format($expensesThisMonth, 2))
                ->description($this->getChangeDescription($expensesThisMonth, $expensesLastMonth))
                ->descriptionIcon($this->getChangeIcon($expensesThisMonth, $expensesLastMonth))
                ->color('danger')
                ->chart($this->getDailyTrend(Expense::class)),

            Stat::make('Balance', '$' . number_format($balanceThisMonth, 2))
                ->description($this->getChangeDescription($balanceThisMonth, $balanceLastMonth))
                ->descriptionIcon($this->getChangeIcon($balanceThisMonth, $balanceLastMonth))
                ->color($balanceThisMonth >= 0 ? 'success' : 'danger')
                ->chart($this->getBalanceTrend()),

            Stat::make('Productos', (string) $totalProducts)
                ->description($lowStockProducts > 0 ? $lowStockProducts . ' con stock bajo' : 'Stock saludable')
                ->descriptionIcon($lowStockProducts > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($lowStockProducts > 0 ? 'warning' : 'info'),

            Stat::make('Valor inventario', '$' . number_format($inventoryValue, 2))
                ->description($totalProducts . ' productos')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),
        ];
    }

    /** @return array<int> */
    private function getDailyTrend(string $model): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $data[] = (int) $model::whereDate('date', $date)->sum('amount');
        }

        return $data;
    }

    /** @return array<int> */
    private function getBalanceTrend(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = (int) Sale::whereDate('date', $date)->sum('amount');
            $expenses = (int) Expense::whereDate('date', $date)->sum('amount');
            $data[] = $sales - $expenses;
        }

        return $data;
    }

    private function getChangeDescription(float $current, float $previous): string
    {
        if ($previous == 0) {
            return $current > 0 ? 'Nuevo este mes' : 'Sin movimientos';
        }

        $change = (($current - $previous) / abs($previous)) * 100;

        return number_format(abs($change), 1) . '% vs mes anterior';
    }

    private function getChangeIcon(float $current, float $previous): string
    {
        return $current >= $previous
            ? 'heroicon-m-arrow-trending-up'
            : 'heroicon-m-arrow-trending-down';
    }
}
