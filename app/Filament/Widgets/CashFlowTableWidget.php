<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Sale;
use Filament\Widgets\Widget;

class CashFlowTableWidget extends Widget
{
    protected string $view = 'filament.widgets.cash-flow-table-widget';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function getEntries(): array
    {
        $sales = Sale::orderBy('date', 'desc')
            ->limit(50)
            ->get()
            ->map(fn ($sale) => [
                'date' => $sale->date,
                'description' => $sale->description ?? 'Venta',
                'category' => 'Venta',
                'income' => (float) $sale->amount,
                'expense' => 0,
            ]);

        $expenses = Expense::with('category')
            ->orderBy('date', 'desc')
            ->limit(50)
            ->get()
            ->map(fn ($expense) => [
                'date' => $expense->date,
                'description' => $expense->description ?? 'Gasto',
                'category' => $expense->category?->name ?? 'Sin categoría',
                'income' => 0,
                'expense' => (float) $expense->amount,
            ]);

        $entries = $sales->concat($expenses)
            ->sortByDesc('date')
            ->take(30)
            ->values()
            ->toArray();

        // Calculate running balance (from oldest to newest, then reverse)
        $reversed = array_reverse($entries);
        $balance = 0;
        foreach ($reversed as &$entry) {
            $balance += $entry['income'] - $entry['expense'];
            $entry['balance'] = $balance;
        }
        unset($entry);

        return array_reverse($reversed);
    }

    protected function formatMoney(float $amount): string
    {
        return '$' . number_format($amount, 2, ',', '.');
    }
}
