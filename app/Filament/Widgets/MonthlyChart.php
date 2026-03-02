<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Sale;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class MonthlyChart extends ChartWidget
{
    protected ?string $heading = 'Ventas vs Gastos';

    protected ?string $description = 'Ultimos 6 meses';

    protected static ?int $sort = 2;

    protected ?string $maxHeight = '300px';

    protected static bool $isLazy = false;

    protected function getData(): array
    {
        $months = collect();
        $salesData = collect();
        $expensesData = collect();
        $balanceData = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->translatedFormat('M Y'));

            $sales = (float) Sale::whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');

            $expenses = (float) Expense::whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');

            $salesData->push($sales);
            $expensesData->push($expenses);
            $balanceData->push($sales - $expenses);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ventas',
                    'data' => $salesData->toArray(),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'borderWidth' => 3,
                    'pointBackgroundColor' => '#10b981',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 5,
                    'pointHoverRadius' => 7,
                ],
                [
                    'label' => 'Gastos',
                    'data' => $expensesData->toArray(),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.08)',
                    'fill' => true,
                    'tension' => 0.4,
                    'borderWidth' => 3,
                    'pointBackgroundColor' => '#ef4444',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 5,
                    'pointHoverRadius' => 7,
                ],
                [
                    'label' => 'Balance',
                    'data' => $balanceData->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.06)',
                    'fill' => true,
                    'tension' => 0.4,
                    'borderWidth' => 2,
                    'borderDash' => [6, 4],
                    'pointBackgroundColor' => '#3b82f6',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                ],
            ],
            'labels' => $months->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
