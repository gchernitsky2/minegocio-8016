<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Pages\Reports;
use App\Services\BusinessMetricsService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BusinessSummaryWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $metrics = new BusinessMetricsService();

        $profitToday = $metrics->profitToday();
        $profitMonth = $metrics->profitMonth();
        $profitYear = $metrics->profitYear();
        $cash = $metrics->cashCurrent();
        $inventory = $metrics->inventoryValue();
        $projection = $metrics->projection30Days();
        $health = $metrics->healthStatus();

        $reportsUrl = Reports::getUrl();

        return [
            Stat::make('Ganancia Hoy', $metrics->formatMoney($profitToday))
                ->description($profitToday >= 0 ? 'Positivo' : 'Negativo')
                ->descriptionIcon($profitToday >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($profitToday >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-currency-dollar')
                ->url($reportsUrl . '?tab=diario'),

            Stat::make('Ganancia Mes', $metrics->formatMoney($profitMonth))
                ->description($profitMonth >= 0 ? 'Positivo' : 'Negativo')
                ->descriptionIcon($profitMonth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($profitMonth >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-calendar')
                ->url($reportsUrl . '?tab=mensual'),

            Stat::make('Ganancia Año', $metrics->formatMoney($profitYear))
                ->description($profitYear >= 0 ? 'Positivo' : 'Negativo')
                ->descriptionIcon($profitYear >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($profitYear >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-chart-bar')
                ->url($reportsUrl . '?tab=anual'),

            Stat::make('Caja Actual', $metrics->formatMoney($cash))
                ->description('Balance total acumulado')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($cash >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-banknotes')
                ->url($reportsUrl . '?tab=caja'),

            Stat::make('Inventario Valor', $metrics->formatMoney($inventory))
                ->description('Stock x Costo')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info')
                ->icon('heroicon-o-cube')
                ->url($reportsUrl . '?tab=inventario'),

            Stat::make('Proyección 30 Días', $metrics->formatMoney($projection))
                ->description('Estimado próximo mes')
                ->descriptionIcon('heroicon-m-arrow-long-right')
                ->color($projection >= $cash ? 'success' : 'warning')
                ->icon('heroicon-o-arrow-trending-up')
                ->url($reportsUrl . '?tab=mensual'),

            Stat::make('Salud del Negocio', $health['status'])
                ->description($health['reason'])
                ->descriptionIcon($health['status'] === 'Risk' ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($health['color'])
                ->icon('heroicon-o-heart')
                ->url($reportsUrl . '?tab=mensual'),
        ];
    }
}
