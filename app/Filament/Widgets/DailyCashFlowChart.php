<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Sale;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class DailyCashFlowChart extends ChartWidget
{
    protected ?string $heading = 'Flujo de Caja Diario';

    protected ?string $description = 'Ultimos 14 dias';

    protected static ?int $sort = 4;

    protected ?string $maxHeight = '260px';

    protected static bool $isLazy = false;

    protected function getData(): array
    {
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d/m');

            $incomeData[] = (float) Sale::whereDate('date', $date)->sum('amount');
            $expenseData[] = (float) Expense::whereDate('date', $date)->sum('amount') * -1;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos',
                    'data' => $incomeData,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                    'borderColor' => '#10b981',
                    'borderWidth' => 0,
                    'borderRadius' => 4,
                    'borderSkipped' => false,
                ],
                [
                    'label' => 'Egresos',
                    'data' => $expenseData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.8)',
                    'borderColor' => '#ef4444',
                    'borderWidth' => 0,
                    'borderRadius' => 4,
                    'borderSkipped' => false,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
