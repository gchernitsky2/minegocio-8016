<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Expense;
use App\Models\Product;
use App\Models\CashFlowProjection;
use Carbon\Carbon;
use Illuminate\Support\Number;

class BusinessMetricsService
{
    /**
     * Ganancia hoy = Ventas hoy - Gastos hoy
     */
    public function profitToday(): float
    {
        $sales = Sale::whereDate('date', Carbon::today())->sum('amount');
        $expenses = Expense::whereDate('date', Carbon::today())->sum('amount');

        return round((float) $sales - (float) $expenses, 2);
    }

    /**
     * Ganancia del mes actual = Ventas mes - Gastos mes
     */
    public function profitMonth(): float
    {
        $sales = Sale::whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->sum('amount');

        $expenses = Expense::whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->sum('amount');

        return round((float) $sales - (float) $expenses, 2);
    }

    /**
     * Ganancia del año actual = Ventas año - Gastos año
     */
    public function profitYear(): float
    {
        $sales = Sale::whereYear('date', Carbon::now()->year)->sum('amount');
        $expenses = Expense::whereYear('date', Carbon::now()->year)->sum('amount');

        return round((float) $sales - (float) $expenses, 2);
    }

    /**
     * Caja actual = Todas las ventas históricas - Todos los gastos históricos
     */
    public function cashCurrent(): float
    {
        $totalSales = (float) Sale::sum('amount');
        $totalExpenses = (float) Expense::sum('amount');

        return round($totalSales - $totalExpenses, 2);
    }

    /**
     * Valor total del inventario = SUM(stock * cost) de todos los productos
     */
    public function inventoryValue(): float
    {
        $products = Product::select('stock', 'cost')->get();

        $total = $products->sum(function ($product) {
            return $product->stock * $product->cost;
        });

        return round((float) $total, 2);
    }

    /**
     * Proyección 30 días:
     * Combina proyecciones manuales (CashFlowProjection) +
     * tendencia histórica para estimar balance futuro.
     */
    public function projection30Days(): float
    {
        $today = Carbon::today();
        $in30Days = Carbon::today()->addDays(30);

        // Proyecciones manuales registradas para los próximos 30 días
        $projectedIncome = (float) CashFlowProjection::where('status', 'planned')
            ->whereBetween('date', [$today, $in30Days])
            ->sum('expected_income');

        $projectedExpense = (float) CashFlowProjection::where('status', 'planned')
            ->whereBetween('date', [$today, $in30Days])
            ->sum('expected_expense');

        // Si hay proyecciones manuales, usarlas
        if ($projectedIncome > 0 || $projectedExpense > 0) {
            return round($this->cashCurrent() + $projectedIncome - $projectedExpense, 2);
        }

        // Si no hay proyecciones, estimar con promedio diario de últimos 30 días
        $thirtyDaysAgo = Carbon::today()->subDays(30);

        $avgDailySales = (float) Sale::where('date', '>=', $thirtyDaysAgo)->sum('amount') / 30;
        $avgDailyExpenses = (float) Expense::where('date', '>=', $thirtyDaysAgo)->sum('amount') / 30;

        $projectedNet = ($avgDailySales - $avgDailyExpenses) * 30;

        return round($this->cashCurrent() + $projectedNet, 2);
    }

    /**
     * Salud del negocio basada en métricas reales.
     *
     * @return array{status: string, reason: string, color: string}
     */
    public function healthStatus(): array
    {
        $profitMonth = $this->profitMonth();
        $cash = $this->cashCurrent();
        $inventoryValue = $this->inventoryValue();

        // Calcular margen promedio del mes
        $salesMonth = (float) Sale::whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->sum('amount');

        $marginPercent = $salesMonth > 0
            ? ($profitMonth / $salesMonth) * 100
            : 0;

        // Tendencia: comparar ganancia este mes vs mes pasado
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $salesLastMonth = (float) Sale::whereBetween('date', [$lastMonthStart, $lastMonthEnd])->sum('amount');
        $expensesLastMonth = (float) Expense::whereBetween('date', [$lastMonthStart, $lastMonthEnd])->sum('amount');
        $profitLastMonth = $salesLastMonth - $expensesLastMonth;

        // Evaluar salud
        $score = 0;

        // Factor 1: Ganancia mensual positiva (+2) o negativa (-2)
        if ($profitMonth > 0) {
            $score += 2;
        } elseif ($profitMonth < 0) {
            $score -= 2;
        }

        // Factor 2: Caja positiva (+1) o negativa (-2)
        if ($cash > 0) {
            $score += 1;
        } else {
            $score -= 2;
        }

        // Factor 3: Margen > 20% (+1), < 5% (-1)
        if ($marginPercent >= 20) {
            $score += 1;
        } elseif ($marginPercent < 5 && $salesMonth > 0) {
            $score -= 1;
        }

        // Factor 4: Tendencia creciente (+1), decreciente (-1)
        if ($profitMonth > $profitLastMonth && $profitLastMonth != 0) {
            $score += 1;
        } elseif ($profitMonth < $profitLastMonth && $profitLastMonth > 0) {
            $score -= 1;
        }

        // Determinar status
        if ($score >= 4) {
            return [
                'status' => 'Excellent',
                'reason' => 'Ganancias crecientes, caja sana y buen margen',
                'color' => 'success',
            ];
        }

        if ($score >= 2) {
            return [
                'status' => 'Good',
                'reason' => 'Negocio estable con ganancias positivas',
                'color' => 'success',
            ];
        }

        if ($score >= 0) {
            return [
                'status' => 'Good',
                'reason' => 'Negocio operando, atención al margen',
                'color' => 'warning',
            ];
        }

        return [
            'status' => 'Risk',
            'reason' => $cash < 0
                ? 'Caja negativa — revisar gastos urgente'
                : 'Ganancias bajas o negativas — revisar costos',
            'color' => 'danger',
        ];
    }

    /**
     * Formatear valor monetario para display.
     */
    public function formatMoney(float $value): string
    {
        $prefix = $value < 0 ? '-$' : '$';
        return $prefix . number_format(abs($value), 2, '.', ',');
    }
}
