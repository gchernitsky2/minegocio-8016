<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Expense;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ExpensesByCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Gastos por Categoria';

    protected ?string $description = 'Distribucion del mes actual';

    protected static ?int $sort = 3;

    protected ?string $maxHeight = '300px';

    protected static bool $isLazy = false;

    protected function getData(): array
    {
        $now = Carbon::now();

        $expenses = Expense::with('category')
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->get()
            ->groupBy('expense_category_id');

        $labels = [];
        $data = [];
        $colors = [
            '#3b82f6', '#ef4444', '#10b981', '#f59e0b',
            '#8b5cf6', '#06b6d4', '#ec4899', '#84cc16',
        ];

        foreach ($expenses as $categoryExpenses) {
            $category = $categoryExpenses->first()->category;
            $labels[] = $category?->name ?? 'Sin categoria';
            $data[] = (float) $categoryExpenses->sum('amount');
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderWidth' => 3,
                    'borderColor' => '#ffffff',
                    'hoverOffset' => 8,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
