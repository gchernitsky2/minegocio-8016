<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Filament\Pages\Page;

class Reports extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Reportes';

    protected static ?string $title = 'Centro de Reportes';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.reports';

    public string $tab = 'mensual';

    /** @var array<string, array<string, mixed>> */
    protected $queryString = [
        'tab' => ['except' => 'mensual'],
    ];

    public ?int $selectedYear = null;

    public ?int $selectedMonth = null;

    public ?string $selectedDate = null;

    public bool $showChart = true;

    // Drill-down modal
    public bool $showDetailModal = false;

    public string $detailTitle = '';

    public string $detailType = '';

    public array $detailRows = [];

    public float $detailTotal = 0;

    public function mount(): void
    {
        $this->selectedYear = (int) now()->year;
        $this->selectedMonth = (int) now()->month;
        $this->selectedDate = now()->format('Y-m-d');
    }

    public function toggleChart(): void
    {
        $this->showChart = ! $this->showChart;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->detailRows = [];
    }

    public function loadMonthlyDetail(int $month, string $type): void
    {
        $monthName = Carbon::create($this->selectedYear, $month, 1)->translatedFormat('F');

        if ($type === 'sales') {
            $this->detailTitle = "Ventas — {$monthName} {$this->selectedYear}";
            $this->detailType = 'sales';
            $rows = Sale::whereMonth('date', $month)
                ->whereYear('date', $this->selectedYear)
                ->orderBy('date')
                ->get();
            $this->detailRows = $rows->map(fn ($r) => [
                'id' => $r->id,
                'date' => $r->date->format('d/m/Y'),
                'description' => $r->description ?? '-',
                'category' => null,
                'amount' => (float) $r->amount,
            ])->toArray();
        } else {
            $this->detailTitle = "Gastos — {$monthName} {$this->selectedYear}";
            $this->detailType = 'expenses';
            $rows = Expense::with('category')
                ->whereMonth('date', $month)
                ->whereYear('date', $this->selectedYear)
                ->orderBy('date')
                ->get();
            $this->detailRows = $rows->map(fn ($r) => [
                'id' => $r->id,
                'date' => $r->date->format('d/m/Y'),
                'description' => $r->description ?? '-',
                'category' => $r->category?->name ?? 'Sin categoria',
                'amount' => (float) $r->amount,
            ])->toArray();
        }

        $this->detailTotal = array_sum(array_column($this->detailRows, 'amount'));
        $this->showDetailModal = true;
    }

    public function loadAnnualDetail(int $year, string $type): void
    {
        if ($type === 'sales') {
            $this->detailTitle = "Ventas — {$year}";
            $this->detailType = 'sales';
            $rows = Sale::whereYear('date', $year)->orderBy('date')->get();
            $this->detailRows = $rows->map(fn ($r) => [
                'id' => $r->id,
                'date' => $r->date->format('d/m/Y'),
                'description' => $r->description ?? '-',
                'category' => null,
                'amount' => (float) $r->amount,
            ])->toArray();
        } else {
            $this->detailTitle = "Gastos — {$year}";
            $this->detailType = 'expenses';
            $rows = Expense::with('category')->whereYear('date', $year)->orderBy('date')->get();
            $this->detailRows = $rows->map(fn ($r) => [
                'id' => $r->id,
                'date' => $r->date->format('d/m/Y'),
                'description' => $r->description ?? '-',
                'category' => $r->category?->name ?? 'Sin categoria',
                'amount' => (float) $r->amount,
            ])->toArray();
        }

        $this->detailTotal = array_sum(array_column($this->detailRows, 'amount'));
        $this->showDetailModal = true;
    }

    public function loadDailyDetail(string $date, string $type): void
    {
        $carbonDate = Carbon::createFromFormat('d/m/Y', $date);
        $formatted = $carbonDate->translatedFormat('d M Y');

        if ($type === 'sales') {
            $this->detailTitle = "Ventas — {$formatted}";
            $this->detailType = 'sales';
            $rows = Sale::whereDate('date', $carbonDate)->orderBy('date')->get();
            $this->detailRows = $rows->map(fn ($r) => [
                'id' => $r->id,
                'date' => $r->date->format('d/m/Y'),
                'description' => $r->description ?? '-',
                'category' => null,
                'amount' => (float) $r->amount,
            ])->toArray();
        } else {
            $this->detailTitle = "Gastos — {$formatted}";
            $this->detailType = 'expenses';
            $rows = Expense::with('category')->whereDate('date', $carbonDate)->orderBy('date')->get();
            $this->detailRows = $rows->map(fn ($r) => [
                'id' => $r->id,
                'date' => $r->date->format('d/m/Y'),
                'description' => $r->description ?? '-',
                'category' => $r->category?->name ?? 'Sin categoria',
                'amount' => (float) $r->amount,
            ])->toArray();
        }

        $this->detailTotal = array_sum(array_column($this->detailRows, 'amount'));
        $this->showDetailModal = true;
    }

    public function loadCategoryDetail(string $categoryName): void
    {
        $this->detailTitle = "Gastos — {$categoryName}";
        $this->detailType = 'expenses';

        $query = Expense::with('category')
            ->whereHas('category', fn ($q) => $q->where('name', $categoryName));

        if ($this->selectedMonth && $this->selectedYear) {
            $query->whereMonth('date', $this->selectedMonth)
                ->whereYear('date', $this->selectedYear);
        }

        $rows = $query->orderBy('date')->get();
        $this->detailRows = $rows->map(fn ($r) => [
            'id' => $r->id,
            'date' => $r->date->format('d/m/Y'),
            'description' => $r->description ?? '-',
            'category' => $r->category?->name ?? 'Sin categoria',
            'amount' => (float) $r->amount,
        ])->toArray();

        $this->detailTotal = array_sum(array_column($this->detailRows, 'amount'));
        $this->showDetailModal = true;
    }

    public function loadProductDetail(string $productName): void
    {
        $this->detailTitle = "Movimientos — {$productName}";
        $this->detailType = 'inventory';

        $product = Product::where('name', $productName)->first();
        if (! $product) {
            return;
        }

        $rows = InventoryMovement::where('product_id', $product->id)
            ->orderByDesc('date')
            ->get();

        $this->detailRows = $rows->map(fn ($r) => [
            'id' => $r->id,
            'date' => $r->date->format('d/m/Y'),
            'description' => $r->reason ?? '-',
            'category' => $r->type === 'entrada' ? 'Entrada' : 'Salida',
            'amount' => (float) $r->quantity,
        ])->toArray();

        $this->detailTotal = $rows->sum('quantity');
        $this->showDetailModal = true;
    }

    public function getMonthlyData(): array
    {
        $rows = [];
        for ($month = 1; $month <= 12; $month++) {
            $sales = (float) Sale::whereMonth('date', $month)
                ->whereYear('date', $this->selectedYear)
                ->sum('amount');
            $expenses = (float) Expense::whereMonth('date', $month)
                ->whereYear('date', $this->selectedYear)
                ->sum('amount');
            $rows[] = [
                'month' => Carbon::create($this->selectedYear, $month, 1)->translatedFormat('F'),
                'month_num' => $month,
                'sales' => $sales,
                'expenses' => $expenses,
                'balance' => $sales - $expenses,
            ];
        }

        return $rows;
    }

    public function getMonthlyChartData(): array
    {
        $data = $this->getMonthlyData();

        return [
            'labels' => array_column($data, 'month'),
            'sales' => array_column($data, 'sales'),
            'expenses' => array_column($data, 'expenses'),
            'balance' => array_column($data, 'balance'),
        ];
    }

    public function getAnnualData(): array
    {
        $currentYear = (int) now()->year;
        $rows = [];
        for ($year = $currentYear - 4; $year <= $currentYear; $year++) {
            $sales = (float) Sale::whereYear('date', $year)->sum('amount');
            $expenses = (float) Expense::whereYear('date', $year)->sum('amount');
            $rows[] = [
                'year' => $year,
                'sales' => $sales,
                'expenses' => $expenses,
                'balance' => $sales - $expenses,
            ];
        }

        return $rows;
    }

    public function getCashFlowData(): array
    {
        $daysInMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->daysInMonth;
        $rows = [];
        $runningBalance = 0;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($this->selectedYear, $this->selectedMonth, $day);
            $sales = (float) Sale::whereDate('date', $date)->sum('amount');
            $expenses = (float) Expense::whereDate('date', $date)->sum('amount');
            $net = $sales - $expenses;
            $runningBalance += $net;

            if ($sales > 0 || $expenses > 0) {
                $rows[] = [
                    'date' => $date->format('d/m/Y'),
                    'day' => $date->format('d'),
                    'day_name' => $date->translatedFormat('D'),
                    'sales' => $sales,
                    'expenses' => $expenses,
                    'net' => $net,
                    'balance' => $runningBalance,
                ];
            }
        }

        return $rows;
    }

    public function getExpensesByCategoryData(): array
    {
        $query = Expense::with('category');

        if ($this->selectedMonth && $this->selectedYear) {
            $query->whereMonth('date', $this->selectedMonth)
                ->whereYear('date', $this->selectedYear);
        }

        $grouped = $query->get()->groupBy('expense_category_id');
        $rows = [];

        foreach ($grouped as $categoryExpenses) {
            $category = $categoryExpenses->first()->category;
            $total = (float) $categoryExpenses->sum('amount');
            $count = $categoryExpenses->count();
            $rows[] = [
                'category' => $category?->name ?? 'Sin categoria',
                'total' => $total,
                'count' => $count,
                'average' => $count > 0 ? $total / $count : 0,
            ];
        }

        usort($rows, fn ($a, $b) => $b['total'] <=> $a['total']);

        return $rows;
    }

    public function getTopSalesData(): array
    {
        $query = Sale::query();

        if ($this->selectedMonth && $this->selectedYear) {
            $query->whereMonth('date', $this->selectedMonth)
                ->whereYear('date', $this->selectedYear);
        }

        return $query->orderByDesc('amount')->limit(20)->get()->map(fn ($sale) => [
            'date' => $sale->date->format('d/m/Y'),
            'amount' => (float) $sale->amount,
            'description' => $sale->description ?? '-',
        ])->toArray();
    }

    public function getInventoryData(): array
    {
        return Product::orderByDesc('stock')->get()->map(fn ($p) => [
            'name' => $p->name,
            'sku' => $p->sku ?? '-',
            'stock' => $p->stock,
            'cost' => (float) $p->cost,
            'price' => (float) $p->price,
            'value' => $p->value,
            'margin' => $p->margin,
        ])->toArray();
    }

    public function getDailyFlowData(): array
    {
        $date = Carbon::parse($this->selectedDate);
        $rows = [];

        $sales = Sale::whereDate('date', $date)->orderBy('id')->get();
        foreach ($sales as $sale) {
            $rows[] = [
                'description' => $sale->description ?? 'Venta',
                'category' => 'Venta',
                'income' => (float) $sale->amount,
                'expense' => 0,
            ];
        }

        $expenses = Expense::with('category')->whereDate('date', $date)->orderBy('id')->get();
        foreach ($expenses as $expense) {
            $rows[] = [
                'description' => $expense->description ?? 'Gasto',
                'category' => $expense->category?->name ?? 'Sin categoria',
                'income' => 0,
                'expense' => (float) $expense->amount,
            ];
        }

        return $rows;
    }

    public function getYearOptions(): array
    {
        $current = (int) now()->year;
        $options = [];
        for ($y = $current - 4; $y <= $current; $y++) {
            $options[$y] = (string) $y;
        }

        return $options;
    }

    public function getMonthOptions(): array
    {
        $options = [];
        for ($m = 1; $m <= 12; $m++) {
            $options[$m] = Carbon::create(null, $m, 1)->translatedFormat('F');
        }

        return $options;
    }
}
