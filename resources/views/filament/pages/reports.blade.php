<x-filament-panels::page>
    @once
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
        <style>
            /* ══════════════════════════════════════
               REPORTS – Clean Professional Theme v3
               ══════════════════════════════════════ */

            /* ── Tabs: dark pill nav ── */
            .rpt-tabs {
                display: flex;
                align-items: center;
                gap: 0.35rem;
                padding: 0.35rem;
                background: #1e293b;
                border-radius: 0.75rem;
                margin-bottom: 1.25rem;
                overflow-x: auto;
            }
            .rpt-tab {
                display: flex;
                align-items: center;
                gap: 0.4rem;
                padding: 0.55rem 0.9rem;
                font-size: 0.8rem;
                font-weight: 500;
                color: #94a3b8;
                background: transparent;
                border: none;
                border-radius: 0.5rem;
                cursor: pointer;
                transition: all 0.2s ease;
                white-space: nowrap;
            }
            .rpt-tab:hover {
                color: #e2e8f0;
                background: rgba(255, 255, 255, 0.07);
            }
            .rpt-tab-active {
                color: #ffffff !important;
                background: #3b82f6 !important;
                font-weight: 600;
                box-shadow: 0 2px 8px rgba(59, 130, 246, 0.35);
            }
            .rpt-tab-active:hover {
                background: #2563eb !important;
            }

            /* ── Toolbar: filter bar ── */
            .rpt-toolbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
                flex-wrap: wrap;
                padding: 0.5rem 0.75rem;
                background: #f1f5f9;
                border: 1px solid #e2e8f0;
                border-radius: 0.6rem;
                margin-bottom: 1.25rem;
            }

            /* ── Summary strip: dark bar inside table ── */
            .rpt-summary {
                display: flex;
                align-items: center;
                gap: 1.5rem;
                flex-wrap: wrap;
                padding: 0.75rem 1rem;
                background: linear-gradient(135deg, #1e293b, #334155);
                color: #e2e8f0;
                font-size: 0.82rem;
                border-radius: 0.75rem 0.75rem 0 0;
            }
            .rpt-summary-item {
                display: flex;
                align-items: center;
                gap: 0.35rem;
            }
            .rpt-summary-label {
                color: #94a3b8;
                font-weight: 500;
            }
            .rpt-summary-value {
                font-weight: 700;
            }
            .rpt-summary-divider {
                width: 1px;
                height: 1.2rem;
                background: rgba(255,255,255,0.15);
            }

            .rpt-card {
                border-radius: 0.75rem;
                overflow: visible;
                background: white;
                border: 1px solid #e2e8f0;
                position: relative;
            }
            .rpt-card-with-summary {
                border-top: none;
                border-radius: 0 0 0.75rem 0.75rem;
            }

            .rpt-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 0.875rem;
            }
            .rpt-table thead th {
                padding: 0.75rem 1rem;
                text-align: left;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                background: #f8fafc;
                color: #475569;
                white-space: nowrap;
                border-bottom: 1px solid #e2e8f0;
                position: relative;
            }
            .rpt-table thead th.text-right { text-align: right; }
            .rpt-table tbody tr { transition: background 0.15s ease; }
            .rpt-table tbody tr:nth-child(odd) { background: #ffffff; }
            .rpt-table tbody tr:nth-child(even) { background: #f8fafc; }
            .rpt-table tbody tr:hover { background: #f1f5f9; }
            .rpt-table tbody td {
                padding: 0.65rem 1rem;
                color: #334155;
                border-bottom: 1px solid #f1f5f9;
            }
            .rpt-table tbody td.text-right { text-align: right; }
            .rpt-table tfoot td {
                padding: 0.75rem 1rem;
                background: #f8fafc;
                color: #1e293b;
                font-weight: 700;
                border-top: 1px solid #e2e8f0;
            }
            .rpt-table tfoot td.text-right { text-align: right; }

            /* ── Clickable cells ── */
            .rpt-clickable {
                cursor: pointer;
                transition: all 0.15s ease;
            }
            .rpt-clickable:hover {
                text-decoration: underline;
                opacity: 0.85;
            }

            .chart-container {
                background: white;
                border: 1px solid #e2e8f0;
                border-radius: 0.75rem;
                padding: 1.25rem;
            }

            .filter-select {
                border-radius: 0.4rem;
                border: 1px solid #cbd5e1;
                padding: 0.4rem 1.8rem 0.4rem 0.6rem;
                font-size: 0.8rem;
                font-weight: 500;
                color: #334155;
                background: white;
                transition: all 0.15s ease;
            }
            .filter-select:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
                outline: none;
            }

            /* ── Sortable headers ── */
            .rpt-sortable {
                cursor: pointer;
                user-select: none;
                position: relative;
            }
            .rpt-sortable:hover {
                color: #1e293b;
                background: #eef2f7;
            }
            .rpt-sort-icon {
                display: inline-block;
                margin-left: 4px;
                font-size: 0.65rem;
                opacity: 0.5;
            }
            .rpt-sort-icon.active {
                opacity: 1;
                color: #3b82f6;
            }

            /* ── Column filter dropdown ── */
            .rpt-filter-wrapper {
                position: relative;
                display: inline-block;
            }
            .rpt-filter-btn {
                background: none;
                border: none;
                cursor: pointer;
                padding: 0 0 0 4px;
                color: #94a3b8;
                font-size: 0.7rem;
                vertical-align: middle;
            }
            .rpt-filter-btn:hover {
                color: #3b82f6;
            }
            .rpt-filter-dropdown {
                display: none;
                position: fixed;
                min-width: 200px;
                max-height: 260px;
                overflow-y: auto;
                background: #1e293b;
                border: 1px solid #334155;
                border-radius: 0.5rem;
                padding: 0.4rem 0;
                box-shadow: 0 10px 40px rgba(0,0,0,0.4);
                z-index: 999999;
            }
            .rpt-filter-dropdown.show {
                display: block;
            }
            .rpt-filter-dropdown label {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.4rem 0.75rem;
                font-size: 0.78rem;
                color: #ffffff;
                cursor: pointer;
                transition: background 0.1s;
                white-space: nowrap;
            }
            .rpt-filter-dropdown label:hover {
                background: rgba(59, 130, 246, 0.25);
            }
            .rpt-filter-dropdown input[type="checkbox"] {
                accent-color: #3b82f6;
                width: 14px;
                height: 14px;
            }
            .rpt-filter-dropdown .rpt-filter-header {
                padding: 0.4rem 0.75rem;
                font-size: 0.7rem;
                color: #94a3b8;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                font-weight: 600;
                border-bottom: 1px solid #334155;
                margin-bottom: 0.2rem;
            }
            .rpt-filter-clear {
                display: block;
                width: calc(100% - 1rem);
                margin: 0.3rem 0.5rem;
                padding: 0.35rem;
                background: rgba(59,130,246,0.15);
                color: #93c5fd;
                border: none;
                border-radius: 0.3rem;
                font-size: 0.72rem;
                cursor: pointer;
                text-align: center;
                font-weight: 500;
            }
            .rpt-filter-clear:hover {
                background: rgba(59,130,246,0.3);
            }

            /* ── Detail Modal ── */
            .rpt-modal-overlay {
                position: fixed;
                inset: 0;
                z-index: 50;
                background: rgba(15, 23, 42, 0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }
            .rpt-modal-panel {
                background: white;
                border-radius: 0.75rem;
                width: 100%;
                max-width: 56rem;
                max-height: 80vh;
                display: flex;
                flex-direction: column;
                overflow: hidden;
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            }
            .rpt-modal-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem 1.25rem;
                background: linear-gradient(135deg, #1e293b, #334155);
                color: white;
            }
            .rpt-modal-header h3 {
                font-size: 1rem;
                font-weight: 700;
                margin: 0;
            }
            .rpt-modal-close {
                background: rgba(255,255,255,0.1);
                border: none;
                color: white;
                width: 2rem;
                height: 2rem;
                border-radius: 0.375rem;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 1.1rem;
                transition: background 0.15s ease;
            }
            .rpt-modal-close:hover {
                background: rgba(255,255,255,0.2);
            }
            .rpt-modal-body {
                overflow-y: auto;
                flex: 1;
            }
            .rpt-modal-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0.75rem 1.25rem;
                background: #f8fafc;
                border-top: 1px solid #e2e8f0;
                font-size: 0.82rem;
                color: #475569;
                font-weight: 600;
            }
        </style>
    @endonce

    {{-- ── Global Alpine state for sorting & filtering ── --}}
    <div x-data="{
        sortCol: null,
        sortDir: 'asc',
        filters: {},
        openFilter: null,
        doSort(col) {
            if (this.sortCol === col) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortCol = col;
                this.sortDir = 'asc';
            }
        },
        toggleFilter(col, val) {
            if (!this.filters[col]) this.filters[col] = [];
            const idx = this.filters[col].indexOf(val);
            if (idx > -1) { this.filters[col].splice(idx, 1); } else { this.filters[col].push(val); }
            this.filters = {...this.filters};
        },
        clearFilter(col) {
            this.filters[col] = [];
            this.filters = {...this.filters};
        },
        isFiltered(col, val) {
            return this.filters[col] && this.filters[col].includes(val);
        },
        hasFilter(col) {
            return this.filters[col] && this.filters[col].length > 0;
        },
        passesFilter(col, val) {
            if (!this.filters[col] || this.filters[col].length === 0) return true;
            return this.filters[col].includes(val);
        },
        positionDropdown(event, col) {
            this.openFilter = this.openFilter === col ? null : col;
            if (this.openFilter === col) {
                this.$nextTick(() => {
                    const btn = event.target.closest('.rpt-filter-btn') || event.target;
                    const dd = document.getElementById('filter-dd-' + col);
                    if (!dd || !btn) return;
                    const rect = btn.getBoundingClientRect();
                    dd.style.top = (rect.bottom + 4) + 'px';
                    dd.style.left = Math.max(8, rect.left - 80) + 'px';
                });
            }
        },
        resetOnTabChange() {
            this.sortCol = null;
            this.sortDir = 'asc';
            this.filters = {};
            this.openFilter = null;
        }
    }" x-init="$watch('$wire.tab', () => resetOnTabChange())" @click.away="openFilter = null">

    {{-- Tabs --}}
    <div class="rpt-tabs">
        @foreach ([
            'mensual' => ['Flujo Mensual', 'heroicon-o-calendar-days'],
            'anual' => ['Flujo Anual', 'heroicon-o-chart-bar'],
            'caja' => ['Flujo de Caja', 'heroicon-o-banknotes'],
            'diario' => ['Flujo del Dia', 'heroicon-o-sun'],
            'categorias' => ['Gastos x Categoria', 'heroicon-o-tag'],
            'top_ventas' => ['Top Ventas', 'heroicon-o-arrow-trending-up'],
            'inventario' => ['Inventario', 'heroicon-o-cube'],
        ] as $key => [$label, $icon])
            <button wire:click="$set('tab', '{{ $key }}')"
                class="rpt-tab {{ $tab === $key ? 'rpt-tab-active' : '' }}">
                <x-filament::icon :icon="$icon" class="w-4 h-4" />
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Filters toolbar --}}
    @if (in_array($tab, ['mensual', 'anual', 'caja', 'diario', 'categorias', 'top_ventas', 'inventario']))
        <div class="rpt-toolbar">
            <div class="flex gap-2 items-center">
                @if (in_array($tab, ['mensual', 'caja', 'categorias', 'top_ventas']))
                    <select wire:model.live="selectedYear" class="filter-select">
                        @foreach ($this->getYearOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                @endif
                @if (in_array($tab, ['caja', 'categorias', 'top_ventas']))
                    <select wire:model.live="selectedMonth" class="filter-select">
                        @foreach ($this->getMonthOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                @endif
                @if ($tab === 'diario')
                    <input type="date" wire:model.live="selectedDate" class="filter-select" max="{{ now()->format('Y-m-d') }}" />
                @endif
            </div>
            <button wire:click="toggleChart"
                class="flex items-center gap-1.5 px-2.5 py-1.5 rounded text-xs font-medium transition-all border
                {{ $showChart
                    ? 'bg-blue-50 text-blue-600 border-blue-200 hover:bg-blue-100'
                    : 'bg-white text-gray-500 border-gray-300 hover:bg-gray-50' }}">
                <x-filament::icon icon="heroicon-o-chart-bar-square" class="w-3.5 h-3.5" />
                {{ $showChart ? 'Ocultar grafica' : 'Mostrar grafica' }}
            </button>
        </div>
    @endif

    {{-- ═══ FLUJO MENSUAL ═══ --}}
    @if ($tab === 'mensual')
        @php
            $monthlyData = $this->getMonthlyData();
            $chartData = $this->getMonthlyChartData();
            $totalSales = array_sum(array_column($monthlyData, 'sales'));
            $totalExpenses = array_sum(array_column($monthlyData, 'expenses'));
            $totalBalance = $totalSales - $totalExpenses;
        @endphp

        @if ($showChart)
            <div class="chart-container mb-6">
                <canvas id="monthlyChart" height="85"></canvas>
            </div>
            <script>
                (function() {
                    function renderMonthly() {
                        const el = document.getElementById('monthlyChart');
                        if (!el) return;
                        if (el._chart) el._chart.destroy();
                        el._chart = new Chart(el, {
                            type: 'bar',
                            data: {
                                labels: @json($chartData['labels']),
                                datasets: [
                                    { label: 'Ventas', data: @json($chartData['sales']), backgroundColor: '#10b981', borderRadius: 4, borderSkipped: false },
                                    { label: 'Gastos', data: @json($chartData['expenses']), backgroundColor: '#ef4444', borderRadius: 4, borderSkipped: false },
                                    { label: 'Balance', data: @json($chartData['balance']), type: 'line', borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.05)', fill: true, tension: 0.4, borderWidth: 2, pointRadius: 3, pointBackgroundColor: '#3b82f6', pointBorderColor: '#fff', pointBorderWidth: 2 }
                                ]
                            },
                            options: { responsive: true, interaction: { intersect: false, mode: 'index' }, plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 20, font: { size: 12, weight: '500' } } }, tooltip: { backgroundColor: '#1e293b', titleFont: { size: 13 }, bodyFont: { size: 12 }, padding: 12, cornerRadius: 8 } }, scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 } } }, x: { grid: { display: false }, ticks: { font: { size: 11 } } } } }
                        });
                    }
                    requestAnimationFrame(renderMonthly);
                })();
            </script>
        @endif

        {{-- Summary strip --}}
        <div class="rpt-summary">
            <div class="rpt-summary-item">
                <span class="rpt-summary-label">Ventas {{ $this->selectedYear }}</span>
                <span class="rpt-summary-value text-emerald-400">${{ number_format($totalSales, 2) }}</span>
            </div>
            <div class="rpt-summary-divider"></div>
            <div class="rpt-summary-item">
                <span class="rpt-summary-label">Gastos {{ $this->selectedYear }}</span>
                <span class="rpt-summary-value text-red-400">${{ number_format($totalExpenses, 2) }}</span>
            </div>
            <div class="rpt-summary-divider"></div>
            <div class="rpt-summary-item">
                <span class="rpt-summary-label">Balance Neto</span>
                <span class="rpt-summary-value {{ $totalBalance >= 0 ? 'text-blue-400' : 'text-red-400' }}">${{ number_format($totalBalance, 2) }}</span>
            </div>
        </div>
        <div class="rpt-card rpt-card-with-summary">
            <table class="rpt-table" x-ref="tblMensual">
                <thead>
                    <tr>
                        <th class="rpt-sortable" @click="doSort('month')" :class="sortCol === 'month' && 'text-blue-600'">
                            Mes <span class="rpt-sort-icon" :class="sortCol === 'month' && 'active'" x-text="sortCol === 'month' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('sales')" :class="sortCol === 'sales' && 'text-blue-600'">
                            Ventas <span class="rpt-sort-icon" :class="sortCol === 'sales' && 'active'" x-text="sortCol === 'sales' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('expenses')" :class="sortCol === 'expenses' && 'text-blue-600'">
                            Gastos <span class="rpt-sort-icon" :class="sortCol === 'expenses' && 'active'" x-text="sortCol === 'expenses' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('balance')" :class="sortCol === 'balance' && 'text-blue-600'">
                            Balance <span class="rpt-sort-icon" :class="sortCol === 'balance' && 'active'" x-text="sortCol === 'balance' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right" style="width:140px">Proporcion</th>
                    </tr>
                </thead>
                <tbody>
                    @php $monthlyJson = json_encode($monthlyData); @endphp
                    <template x-data="{ rows: {{ $monthlyJson }} }" x-for="row in (function() {
                        let r = [...rows];
                        if (sortCol === 'month') r.sort((a,b) => sortDir === 'asc' ? a.month.localeCompare(b.month) : b.month.localeCompare(a.month));
                        else if (sortCol) r.sort((a,b) => sortDir === 'asc' ? a[sortCol] - b[sortCol] : b[sortCol] - a[sortCol]);
                        return r;
                    })()" :key="row.month_num">
                        <tr>
                            <td class="font-medium" x-text="row.month"></td>
                            <td class="text-right">
                                <span class="rpt-clickable font-medium text-emerald-600" x-text="'$' + row.sales.toLocaleString('en', {minimumFractionDigits:2})" @click="$wire.loadMonthlyDetail(row.month_num, 'sales')"></span>
                            </td>
                            <td class="text-right">
                                <span class="rpt-clickable font-medium text-red-500" x-text="'$' + row.expenses.toLocaleString('en', {minimumFractionDigits:2})" @click="$wire.loadMonthlyDetail(row.month_num, 'expenses')"></span>
                            </td>
                            <td class="text-right font-semibold" :class="row.balance >= 0 ? 'text-blue-600' : 'text-red-500'" x-text="'$' + row.balance.toLocaleString('en', {minimumFractionDigits:2})"></td>
                            <td class="text-right">
                                <div class="flex gap-px h-2 rounded-full overflow-hidden bg-gray-100">
                                    <template x-if="(row.sales + row.expenses) > 0">
                                        <div class="flex w-full">
                                            <div class="bg-emerald-400 rounded-l-full" :style="'width:' + (row.sales/(row.sales+row.expenses)*100) + '%'"></div>
                                            <div class="bg-red-400 rounded-r-full" :style="'width:' + (row.expenses/(row.sales+row.expenses)*100) + '%'"></div>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr>
                        <td>TOTAL</td>
                        <td class="text-right text-emerald-600">${{ number_format($totalSales, 2) }}</td>
                        <td class="text-right text-red-500">${{ number_format($totalExpenses, 2) }}</td>
                        <td class="text-right {{ $totalBalance >= 0 ? 'text-blue-600' : 'text-red-500' }}">${{ number_format($totalBalance, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    {{-- ═══ FLUJO ANUAL ═══ --}}
    @if ($tab === 'anual')
        @php
            $annualData = $this->getAnnualData();
            $annualTotalSales = array_sum(array_column($annualData, 'sales'));
            $annualTotalExpenses = array_sum(array_column($annualData, 'expenses'));
            $annualTotalBalance = $annualTotalSales - $annualTotalExpenses;
            $annualTotalMargin = $annualTotalSales > 0 ? ($annualTotalBalance / $annualTotalSales * 100) : 0;
        @endphp

        @if ($showChart)
            <div class="chart-container mb-6">
                <canvas id="annualChart" height="85"></canvas>
            </div>
            <script>
                (function() {
                    function renderAnnual() {
                        const el = document.getElementById('annualChart');
                        if (!el) return;
                        if (el._chart) el._chart.destroy();
                        el._chart = new Chart(el, {
                            type: 'bar',
                            data: {
                                labels: @json(array_column($annualData, 'year')),
                                datasets: [
                                    { label: 'Ventas', data: @json(array_column($annualData, 'sales')), backgroundColor: '#10b981', borderRadius: 4, borderSkipped: false, barPercentage: 0.5 },
                                    { label: 'Gastos', data: @json(array_column($annualData, 'expenses')), backgroundColor: '#ef4444', borderRadius: 4, borderSkipped: false, barPercentage: 0.5 }
                                ]
                            },
                            options: { responsive: true, plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 20, font: { size: 12, weight: '500' } } }, tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 8 } }, scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } } }
                        });
                    }
                    requestAnimationFrame(renderAnnual);
                })();
            </script>
        @endif

        <div class="rpt-card">
            @php $annualJson = json_encode($annualData); @endphp
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th class="rpt-sortable" @click="doSort('year')">
                            Ano <span class="rpt-sort-icon" :class="sortCol === 'year' && 'active'" x-text="sortCol === 'year' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('sales')">
                            Ventas <span class="rpt-sort-icon" :class="sortCol === 'sales' && 'active'" x-text="sortCol === 'sales' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('expenses')">
                            Gastos <span class="rpt-sort-icon" :class="sortCol === 'expenses' && 'active'" x-text="sortCol === 'expenses' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('balance')">
                            Balance <span class="rpt-sort-icon" :class="sortCol === 'balance' && 'active'" x-text="sortCol === 'balance' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right">Margen</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-data="{ rows: {{ $annualJson }} }" x-for="row in (function() {
                        let r = [...rows];
                        if (sortCol) r.sort((a,b) => sortDir === 'asc' ? a[sortCol] - b[sortCol] : b[sortCol] - a[sortCol]);
                        return r;
                    })()" :key="row.year">
                        <tr>
                            <td class="font-semibold text-gray-800" x-text="row.year"></td>
                            <td class="text-right">
                                <span class="rpt-clickable text-emerald-600 font-medium" x-text="'$' + row.sales.toLocaleString('en', {minimumFractionDigits:2})" @click="$wire.loadAnnualDetail(row.year, 'sales')"></span>
                            </td>
                            <td class="text-right">
                                <span class="rpt-clickable text-red-500 font-medium" x-text="'$' + row.expenses.toLocaleString('en', {minimumFractionDigits:2})" @click="$wire.loadAnnualDetail(row.year, 'expenses')"></span>
                            </td>
                            <td class="text-right font-semibold" :class="row.balance >= 0 ? 'text-blue-600' : 'text-red-500'" x-text="'$' + row.balance.toLocaleString('en', {minimumFractionDigits:2})"></td>
                            <td class="text-right">
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold" :class="row.sales > 0 && (row.balance/row.sales*100) >= 0 ? 'bg-blue-50 text-blue-700' : 'bg-red-50 text-red-700'" x-text="(row.sales > 0 ? (row.balance/row.sales*100).toFixed(1) : '0.0') + '%'"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr>
                        <td>TOTAL</td>
                        <td class="text-right text-emerald-600">${{ number_format($annualTotalSales, 2) }}</td>
                        <td class="text-right text-red-500">${{ number_format($annualTotalExpenses, 2) }}</td>
                        <td class="text-right {{ $annualTotalBalance >= 0 ? 'text-blue-600' : 'text-red-500' }}">${{ number_format($annualTotalBalance, 2) }}</td>
                        <td class="text-right">
                            <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold {{ $annualTotalMargin >= 0 ? 'bg-blue-50 text-blue-700' : 'bg-red-50 text-red-700' }}">
                                {{ number_format($annualTotalMargin, 1) }}%
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    {{-- ═══ FLUJO DE CAJA ═══ --}}
    @if ($tab === 'caja')
        @php
            $cashFlowData = $this->getCashFlowData();
            $totalIn = array_sum(array_column($cashFlowData, 'sales'));
            $totalOut = array_sum(array_column($cashFlowData, 'expenses'));
            $totalNet = $totalIn - $totalOut;
            $cashFlowExpensesNeg = [];
            foreach (array_column($cashFlowData, 'expenses') as $v) {
                $cashFlowExpensesNeg[] = $v * -1;
            }
        @endphp

        @if ($showChart && count($cashFlowData) > 0)
            <div class="chart-container mb-6">
                <canvas id="cashFlowChart" height="85"></canvas>
            </div>
            <script>
                (function() {
                    function renderCashFlow() {
                        const el = document.getElementById('cashFlowChart');
                        if (!el) return;
                        if (el._chart) el._chart.destroy();
                        el._chart = new Chart(el, {
                            type: 'bar',
                            data: {
                                labels: @json(array_column($cashFlowData, 'day')),
                                datasets: [
                                    { label: 'Ingresos', data: @json(array_column($cashFlowData, 'sales')), backgroundColor: '#10b981', borderRadius: 4, borderSkipped: false },
                                    { label: 'Egresos', data: @json($cashFlowExpensesNeg), backgroundColor: '#ef4444', borderRadius: 4, borderSkipped: false },
                                    { label: 'Saldo', data: @json(array_column($cashFlowData, 'balance')), type: 'line', borderColor: '#3b82f6', borderWidth: 2, tension: 0.3, pointRadius: 3, pointBackgroundColor: '#3b82f6', pointBorderColor: '#fff', pointBorderWidth: 2, fill: false }
                                ]
                            },
                            options: { responsive: true, interaction: { intersect: false, mode: 'index' }, plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 20, font: { size: 12, weight: '500' } } }, tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 8 } }, scales: { y: { grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } } }
                        });
                    }
                    requestAnimationFrame(renderCashFlow);
                })();
            </script>
        @endif

        {{-- Summary strip --}}
        <div class="rpt-summary">
            <div class="rpt-summary-item">
                <span class="rpt-summary-label">Total Ingresos</span>
                <span class="rpt-summary-value text-emerald-400">${{ number_format($totalIn, 2) }}</span>
            </div>
            <div class="rpt-summary-divider"></div>
            <div class="rpt-summary-item">
                <span class="rpt-summary-label">Total Egresos</span>
                <span class="rpt-summary-value text-red-400">${{ number_format($totalOut, 2) }}</span>
            </div>
            <div class="rpt-summary-divider"></div>
            <div class="rpt-summary-item">
                <span class="rpt-summary-label">Flujo Neto</span>
                <span class="rpt-summary-value {{ $totalNet >= 0 ? 'text-blue-400' : 'text-red-400' }}">${{ number_format($totalNet, 2) }}</span>
            </div>
        </div>
        <div class="rpt-card rpt-card-with-summary">
            @php $cashJson = json_encode($cashFlowData); @endphp
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th class="rpt-sortable" @click="doSort('date')">
                            Fecha <span class="rpt-sort-icon" :class="sortCol === 'date' && 'active'" x-text="sortCol === 'date' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th>Dia</th>
                        <th class="text-right rpt-sortable" @click="doSort('sales')">
                            Ingresos <span class="rpt-sort-icon" :class="sortCol === 'sales' && 'active'" x-text="sortCol === 'sales' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('expenses')">
                            Egresos <span class="rpt-sort-icon" :class="sortCol === 'expenses' && 'active'" x-text="sortCol === 'expenses' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('net')">
                            Neto <span class="rpt-sort-icon" :class="sortCol === 'net' && 'active'" x-text="sortCol === 'net' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('balance')">
                            Saldo Acum. <span class="rpt-sort-icon" :class="sortCol === 'balance' && 'active'" x-text="sortCol === 'balance' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cashFlowData as $row)
                        <tr>
                            <td class="font-medium">{{ $row['date'] }}</td>
                            <td class="text-gray-500">{{ $row['day_name'] }}</td>
                            <td class="text-right">
                                <span wire:click="loadDailyDetail('{{ $row['date'] }}', 'sales')" class="rpt-clickable font-medium text-emerald-600">${{ number_format($row['sales'], 2) }}</span>
                            </td>
                            <td class="text-right">
                                <span wire:click="loadDailyDetail('{{ $row['date'] }}', 'expenses')" class="rpt-clickable font-medium text-red-500">${{ number_format($row['expenses'], 2) }}</span>
                            </td>
                            <td class="text-right">
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold {{ $row['net'] >= 0 ? 'bg-blue-50 text-blue-700' : 'bg-red-50 text-red-700' }}">
                                    {{ $row['net'] >= 0 ? '+' : '' }}${{ number_format($row['net'], 2) }}
                                </span>
                            </td>
                            <td class="text-right font-semibold {{ $row['balance'] >= 0 ? 'text-blue-600' : 'text-red-500' }}">${{ number_format($row['balance'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-400">
                                <x-filament::icon icon="heroicon-o-inbox" class="w-10 h-10 mx-auto mb-2 text-gray-300" />
                                <p class="font-medium">Sin movimientos en este periodo</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if (count($cashFlowData) > 0)
                    <tfoot>
                        <tr>
                            <td colspan="2">TOTAL</td>
                            <td class="text-right text-emerald-600">${{ number_format($totalIn, 2) }}</td>
                            <td class="text-right text-red-500">${{ number_format($totalOut, 2) }}</td>
                            <td class="text-right {{ $totalNet >= 0 ? 'text-blue-600' : 'text-red-500' }}">{{ $totalNet >= 0 ? '+' : '' }}${{ number_format($totalNet, 2) }}</td>
                            <td class="text-right {{ end($cashFlowData)['balance'] >= 0 ? 'text-blue-600' : 'text-red-500' }}">${{ number_format(end($cashFlowData)['balance'], 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    @endif

    {{-- ═══ FLUJO DEL DIA ═══ --}}
    @if ($tab === 'diario')
        @php
            $dailyData = $this->getDailyFlowData();
            $dailyTotalIncome = array_sum(array_column($dailyData, 'income'));
            $dailyTotalExpense = array_sum(array_column($dailyData, 'expense'));
            $dailyNet = $dailyTotalIncome - $dailyTotalExpense;
            $dailyCategories = array_values(array_unique(array_column($dailyData, 'category')));
        @endphp

        {{-- Summary strip --}}
        <div class="rpt-summary">
            <div class="rpt-summary-item">
                <span class="rpt-summary-label">Fecha</span>
                <span class="rpt-summary-value text-slate-200">{{ \Carbon\Carbon::parse($this->selectedDate)->format('d/m/Y') }}</span>
            </div>
            <div class="rpt-summary-divider"></div>
            <div class="rpt-summary-item">
                <span class="rpt-summary-label">Total Ingresos</span>
                <span class="rpt-summary-value text-emerald-400">${{ number_format($dailyTotalIncome, 2) }}</span>
            </div>
            <div class="rpt-summary-divider"></div>
            <div class="rpt-summary-item">
                <span class="rpt-summary-label">Total Egresos</span>
                <span class="rpt-summary-value text-red-400">${{ number_format($dailyTotalExpense, 2) }}</span>
            </div>
            <div class="rpt-summary-divider"></div>
            <div class="rpt-summary-item">
                <span class="rpt-summary-label">Flujo Neto</span>
                <span class="rpt-summary-value {{ $dailyNet >= 0 ? 'text-blue-400' : 'text-red-400' }}">${{ number_format($dailyNet, 2) }}</span>
            </div>
        </div>
        <div class="rpt-card rpt-card-with-summary">
            @php $dailyJson = json_encode($dailyData); $dailyCatsJson = json_encode($dailyCategories); @endphp
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th class="rpt-sortable" @click="doSort('description')">
                            Descripcion <span class="rpt-sort-icon" :class="sortCol === 'description' && 'active'" x-text="sortCol === 'description' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th>
                            <span class="rpt-sortable" @click="doSort('category')" style="display:inline">
                                Categoria <span class="rpt-sort-icon" :class="sortCol === 'category' && 'active'" x-text="sortCol === 'category' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                            </span>
                            <span class="rpt-filter-wrapper">
                                <button class="rpt-filter-btn" @click.stop="positionDropdown($event, 'daily_cat')" :style="hasFilter('daily_cat') ? 'color:#3b82f6' : ''">&#9662;</button>
                                <div class="rpt-filter-dropdown" :class="openFilter === 'daily_cat' && 'show'" id="filter-dd-daily_cat" @click.stop>
                                    <div class="rpt-filter-header">Filtrar por Categoria</div>
                                    @foreach ($dailyCategories as $cat)
                                        <label>
                                            <input type="checkbox" :checked="isFiltered('daily_cat', '{{ addslashes($cat) }}')" @change="toggleFilter('daily_cat', '{{ addslashes($cat) }}')">
                                            {{ $cat }}
                                        </label>
                                    @endforeach
                                    <button class="rpt-filter-clear" @click="clearFilter('daily_cat')">Limpiar filtro</button>
                                </div>
                            </span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('income')">
                            Ingresos <span class="rpt-sort-icon" :class="sortCol === 'income' && 'active'" x-text="sortCol === 'income' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('expense')">
                            Egresos <span class="rpt-sort-icon" :class="sortCol === 'expense' && 'active'" x-text="sortCol === 'expense' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template x-data="{ rows: {{ $dailyJson }} }" x-for="(row, idx) in (function() {
                        let r = rows.filter(r => passesFilter('daily_cat', r.category));
                        if (sortCol === 'description' || sortCol === 'category') r.sort((a,b) => sortDir === 'asc' ? a[sortCol].localeCompare(b[sortCol]) : b[sortCol].localeCompare(a[sortCol]));
                        else if (sortCol) r.sort((a,b) => sortDir === 'asc' ? a[sortCol] - b[sortCol] : b[sortCol] - a[sortCol]);
                        return r;
                    })()" :key="idx">
                        <tr>
                            <td class="font-medium" x-text="row.description"></td>
                            <td x-text="row.category"></td>
                            <td class="text-right font-semibold" :class="row.income > 0 ? 'text-emerald-600' : 'text-gray-300'" x-text="row.income > 0 ? '$' + row.income.toLocaleString('en', {minimumFractionDigits:2}) : '-'"></td>
                            <td class="text-right font-semibold" :class="row.expense > 0 ? 'text-red-500' : 'text-gray-300'" x-text="row.expense > 0 ? '$' + row.expense.toLocaleString('en', {minimumFractionDigits:2}) : '-'"></td>
                        </tr>
                    </template>
                </tbody>
                @if (count($dailyData) > 0)
                    <tfoot>
                        <tr>
                            <td colspan="2">TOTAL</td>
                            <td class="text-right text-emerald-600">${{ number_format($dailyTotalIncome, 2) }}</td>
                            <td class="text-right text-red-500">${{ number_format($dailyTotalExpense, 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
            @if (count($dailyData) === 0)
                <div class="text-center py-12 text-gray-400">
                    <x-filament::icon icon="heroicon-o-inbox" class="w-10 h-10 mx-auto mb-2 text-gray-300" />
                    <p class="font-medium">Sin movimientos para esta fecha</p>
                </div>
            @endif
        </div>
    @endif

    {{-- ═══ GASTOS POR CATEGORIA ═══ --}}
    @if ($tab === 'categorias')
        @php
            $catData = $this->getExpensesByCategoryData();
            $catTotal = array_sum(array_column($catData, 'total'));
            $colors = ['#3b82f6','#ef4444','#10b981','#f59e0b','#8b5cf6','#06b6d4','#ec4899','#84cc16'];
        @endphp

        @if ($showChart && count($catData) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <div class="chart-container">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Distribucion</p>
                    <canvas id="catDoughnut" height="220"></canvas>
                </div>
                <div class="chart-container">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Comparativa</p>
                    <canvas id="catBar" height="220"></canvas>
                </div>
            </div>
            <script>
                (function() {
                    function renderCat() {
                        const labels = @json(array_column($catData, 'category'));
                        const data = @json(array_column($catData, 'total'));
                        const colors = @json(array_slice($colors, 0, count($catData)));
                        const d = document.getElementById('catDoughnut');
                        const b = document.getElementById('catBar');
                        if (d) { if (d._chart) d._chart.destroy(); d._chart = new Chart(d, { type: 'doughnut', data: { labels, datasets: [{ data, backgroundColor: colors, borderWidth: 2, borderColor: '#fff', hoverOffset: 8 }] }, options: { responsive: true, cutout: '65%', plugins: { legend: { position: 'right', labels: { usePointStyle: true, padding: 14, font: { size: 12, weight: '500' } } } } } }); }
                        if (b) { if (b._chart) b._chart.destroy(); b._chart = new Chart(b, { type: 'bar', data: { labels, datasets: [{ data, backgroundColor: colors, borderRadius: 4, borderSkipped: false }] }, options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 8 } }, scales: { x: { grid: { color: '#f1f5f9' } }, y: { grid: { display: false } } } } }); }
                    }
                    requestAnimationFrame(renderCat);
                })();
            </script>
        @endif

        <div class="rpt-card">
            @php $catJson = json_encode($catData); @endphp
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th class="rpt-sortable" @click="doSort('category')">
                            Categoria <span class="rpt-sort-icon" :class="sortCol === 'category' && 'active'" x-text="sortCol === 'category' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('count')">
                            Transacciones <span class="rpt-sort-icon" :class="sortCol === 'count' && 'active'" x-text="sortCol === 'count' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('average')">
                            Promedio <span class="rpt-sort-icon" :class="sortCol === 'average' && 'active'" x-text="sortCol === 'average' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('total')">
                            Total <span class="rpt-sort-icon" :class="sortCol === 'total' && 'active'" x-text="sortCol === 'total' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right">% del Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($catData as $i => $row)
                        @php $pct = $catTotal > 0 ? ($row['total'] / $catTotal * 100) : 0; @endphp
                        <tr>
                            <td>
                                <span class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-sm inline-block" style="background:{{ $colors[$i % count($colors)] }}"></span>
                                    <span wire:click="loadCategoryDetail('{{ addslashes($row['category']) }}')" class="rpt-clickable font-medium">{{ $row['category'] }}</span>
                                </span>
                            </td>
                            <td class="text-right text-gray-500">{{ $row['count'] }}</td>
                            <td class="text-right text-gray-500">${{ number_format($row['average'], 2) }}</td>
                            <td class="text-right">
                                <span wire:click="loadCategoryDetail('{{ addslashes($row['category']) }}')" class="rpt-clickable text-red-500 font-semibold">${{ number_format($row['total'], 2) }}</span>
                            </td>
                            <td class="text-right">
                                <span class="flex items-center justify-end gap-2">
                                    <span class="w-14 h-1.5 rounded-full bg-gray-100 overflow-hidden inline-block">
                                        <span class="h-full rounded-full block" style="width:{{ $pct }}%; background:{{ $colors[$i % count($colors)] }}"></span>
                                    </span>
                                    <span class="text-xs font-medium text-gray-500 w-10 text-right">{{ number_format($pct, 1) }}%</span>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>TOTAL</td>
                        <td class="text-right text-gray-600">{{ array_sum(array_column($catData, 'count')) }}</td>
                        <td></td>
                        <td class="text-right text-red-500">${{ number_format($catTotal, 2) }}</td>
                        <td class="text-right text-gray-600">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    {{-- ═══ TOP VENTAS ═══ --}}
    @if ($tab === 'top_ventas')
        @php
            $topSales = $this->getTopSalesData();
            $topSalesLabels = [];
            foreach ($topSales as $s) {
                $topSalesLabels[] = mb_substr($s['description'], 0, 25) . ' (' . $s['date'] . ')';
            }
        @endphp

        @if ($showChart && count($topSales) > 0)
            <div class="chart-container mb-6">
                <canvas id="topSalesChart" height="80"></canvas>
            </div>
            <script>
                (function() {
                    function renderTopSales() {
                        const el = document.getElementById('topSalesChart');
                        if (!el) return;
                        if (el._chart) el._chart.destroy();
                        const data = @json(array_column($topSales, 'amount'));
                        const labels = @json($topSalesLabels);
                        el._chart = new Chart(el, { type: 'bar', data: { labels, datasets: [{ data, backgroundColor: '#3b82f6', borderRadius: 4, borderSkipped: false }] }, options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 8 } }, scales: { x: { grid: { color: '#f1f5f9' } }, y: { grid: { display: false }, ticks: { font: { size: 11 } } } } } });
                    }
                    requestAnimationFrame(renderTopSales);
                })();
            </script>
        @endif

        <div class="rpt-card">
            @php $topJson = json_encode($topSales); @endphp
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th class="rpt-sortable" @click="doSort('date')">
                            Fecha <span class="rpt-sort-icon" :class="sortCol === 'date' && 'active'" x-text="sortCol === 'date' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="rpt-sortable" @click="doSort('description')">
                            Descripcion <span class="rpt-sort-icon" :class="sortCol === 'description' && 'active'" x-text="sortCol === 'description' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('amount')">
                            Monto <span class="rpt-sort-icon" :class="sortCol === 'amount' && 'active'" x-text="sortCol === 'amount' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topSales as $i => $sale)
                        <tr>
                            <td>
                                @if ($i < 3)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded text-xs font-bold text-white {{ $i === 0 ? 'bg-amber-500' : ($i === 1 ? 'bg-gray-400' : 'bg-amber-700') }}">{{ $i + 1 }}</span>
                                @else
                                    <span class="text-gray-400 text-xs">{{ $i + 1 }}</span>
                                @endif
                            </td>
                            <td class="text-gray-500">{{ $sale['date'] }}</td>
                            <td class="font-medium">{{ $sale['description'] }}</td>
                            <td class="text-right text-emerald-600 font-semibold">${{ number_format($sale['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- ═══ INVENTARIO ═══ --}}
    @if ($tab === 'inventario')
        @php
            $invData = $this->getInventoryData();
            $totalValue = array_sum(array_column($invData, 'value'));
            $totalStock = array_sum(array_column($invData, 'stock'));
        @endphp

        @if ($showChart && count($invData) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <div class="chart-container">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Stock por producto</p>
                    <canvas id="invStock" height="200"></canvas>
                </div>
                <div class="chart-container">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Valor por producto</p>
                    <canvas id="invValue" height="200"></canvas>
                </div>
            </div>
            <script>
                (function() {
                    function renderInv() {
                        const names = @json(array_column($invData, 'name'));
                        const stock = @json(array_column($invData, 'stock'));
                        const values = @json(array_column($invData, 'value'));
                        const colors = ['#3b82f6','#ef4444','#10b981','#f59e0b','#8b5cf6','#06b6d4','#ec4899','#84cc16','#14b8a6','#f97316'];
                        const s = document.getElementById('invStock');
                        const v = document.getElementById('invValue');
                        if (s) { if (s._chart) s._chart.destroy(); s._chart = new Chart(s, { type: 'bar', data: { labels: names, datasets: [{ data: stock, backgroundColor: colors.slice(0, names.length), borderRadius: 4, borderSkipped: false }] }, options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } } } }); }
                        if (v) { if (v._chart) v._chart.destroy(); v._chart = new Chart(v, { type: 'doughnut', data: { labels: names, datasets: [{ data: values, backgroundColor: colors.slice(0, names.length), borderWidth: 2, borderColor: '#fff', hoverOffset: 8 }] }, options: { responsive: true, cutout: '60%', plugins: { legend: { position: 'right', labels: { usePointStyle: true, padding: 12, font: { size: 11, weight: '500' } } } } } }); }
                    }
                    requestAnimationFrame(renderInv);
                })();
            </script>
        @endif

        {{-- Summary strip --}}
        <div class="rpt-summary">
            <div class="rpt-summary-item">
                <span class="rpt-summary-label">Total Productos</span>
                <span class="rpt-summary-value text-slate-200">{{ count($invData) }}</span>
            </div>
            <div class="rpt-summary-divider"></div>
            <div class="rpt-summary-item">
                <span class="rpt-summary-label">Unidades en Stock</span>
                <span class="rpt-summary-value text-violet-400">{{ number_format($totalStock) }}</span>
            </div>
            <div class="rpt-summary-divider"></div>
            <div class="rpt-summary-item">
                <span class="rpt-summary-label">Valor del Inventario</span>
                <span class="rpt-summary-value text-emerald-400">${{ number_format($totalValue, 2) }}</span>
            </div>
        </div>
        <div class="rpt-card rpt-card-with-summary">
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th class="rpt-sortable" @click="doSort('name')">
                            Producto <span class="rpt-sort-icon" :class="sortCol === 'name' && 'active'" x-text="sortCol === 'name' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th>SKU</th>
                        <th class="text-right rpt-sortable" @click="doSort('stock')">
                            Stock <span class="rpt-sort-icon" :class="sortCol === 'stock' && 'active'" x-text="sortCol === 'stock' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('cost')">
                            Costo <span class="rpt-sort-icon" :class="sortCol === 'cost' && 'active'" x-text="sortCol === 'cost' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('price')">
                            Precio <span class="rpt-sort-icon" :class="sortCol === 'price' && 'active'" x-text="sortCol === 'price' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('margin')">
                            Margen <span class="rpt-sort-icon" :class="sortCol === 'margin' && 'active'" x-text="sortCol === 'margin' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                        <th class="text-right rpt-sortable" @click="doSort('value')">
                            Valor Total <span class="rpt-sort-icon" :class="sortCol === 'value' && 'active'" x-text="sortCol === 'value' ? (sortDir === 'asc' ? '▲' : '▼') : '⇅'"></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invData as $row)
                        <tr>
                            <td>
                                <span wire:click="loadProductDetail('{{ addslashes($row['name']) }}')" class="rpt-clickable font-medium">{{ $row['name'] }}</span>
                            </td>
                            <td><span class="text-xs text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded font-mono">{{ $row['sku'] }}</span></td>
                            <td class="text-right">
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold {{ $row['stock'] <= 0 ? 'bg-red-50 text-red-600' : ($row['stock'] <= 5 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600') }}">{{ $row['stock'] }}</span>
                            </td>
                            <td class="text-right text-gray-500">${{ number_format($row['cost'], 2) }}</td>
                            <td class="text-right font-medium">${{ number_format($row['price'], 2) }}</td>
                            <td class="text-right">
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold {{ $row['margin'] >= 30 ? 'bg-emerald-50 text-emerald-600' : ($row['margin'] >= 15 ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600') }}">{{ number_format($row['margin'], 1) }}%</span>
                            </td>
                            <td class="text-right text-blue-600 font-semibold">${{ number_format($row['value'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">TOTAL</td>
                        <td class="text-right text-gray-600">{{ number_format($totalStock) }}</td>
                        <td colspan="3"></td>
                        <td class="text-right text-blue-600">${{ number_format($totalValue, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    {{-- ═══ DETAIL MODAL ═══ --}}
    @if ($showDetailModal)
        @php
            $detailCount = count($detailRows);
            $detailAvg = $detailCount > 0 ? $detailTotal / $detailCount : 0;
            $detailMax = $detailCount > 0 ? max(array_column($detailRows, 'amount')) : 0;
            $detailMin = $detailCount > 0 ? min(array_column($detailRows, 'amount')) : 0;
        @endphp
        <div class="rpt-modal-overlay" wire:click.self="closeDetailModal"
             x-data="{ rowDetail: null }" @keydown.escape.window="$wire.closeDetailModal()">
            <div class="rpt-modal-panel" style="max-width:64rem;">
                {{-- Header --}}
                <div class="rpt-modal-header" style="padding:0;">
                    <div style="width:100%;">
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:0.85rem 1.25rem;">
                            <div style="display:flex;align-items:center;gap:0.6rem;">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:2rem;height:2rem;border-radius:0.5rem;background:rgba(255,255,255,0.12);">
                                    @if($detailType === 'sales')
                                        <x-filament::icon icon="heroicon-o-banknotes" class="w-4 h-4" style="color:#6ee7b7" />
                                    @elseif($detailType === 'expenses')
                                        <x-filament::icon icon="heroicon-o-credit-card" class="w-4 h-4" style="color:#fca5a5" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-arrows-right-left" class="w-4 h-4" style="color:#93c5fd" />
                                    @endif
                                </span>
                                <div>
                                    <h3 style="font-size:0.95rem;font-weight:700;margin:0;">{{ $detailTitle }}</h3>
                                    <p style="font-size:0.72rem;color:#94a3b8;margin:0;">{{ $detailCount }} registro{{ $detailCount !== 1 ? 's' : '' }}</p>
                                </div>
                            </div>
                            <button wire:click="closeDetailModal" class="rpt-modal-close">&times;</button>
                        </div>
                        {{-- Summary cards --}}
                        @if ($detailCount > 0 && $detailType !== 'inventory')
                            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0;border-top:1px solid rgba(255,255,255,0.08);">
                                @foreach([
                                    ['Total', $detailTotal, $detailType === 'sales' ? '#6ee7b7' : '#fca5a5'],
                                    ['Promedio', $detailAvg, '#93c5fd'],
                                    ['Mayor', $detailMax, '#fbbf24'],
                                    ['Menor', $detailMin, '#c4b5fd'],
                                ] as [$statLabel, $statValue, $statColor])
                                    <div style="padding:0.6rem 1rem;{{ !$loop->last ? 'border-right:1px solid rgba(255,255,255,0.08);' : '' }}">
                                        <div style="font-size:0.65rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;font-weight:600;">{{ $statLabel }}</div>
                                        <div style="font-size:0.95rem;font-weight:700;color:{{ $statColor }};margin-top:0.1rem;">${{ number_format($statValue, 2) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                {{-- Body --}}
                <div class="rpt-modal-body">
                    <table class="rpt-table">
                        <thead>
                            <tr>
                                <th style="width:35px">#</th>
                                <th>Fecha</th>
                                <th>Descripcion</th>
                                @if ($detailType === 'expenses')
                                    <th>Categoria</th>
                                @endif
                                @if ($detailType === 'inventory')
                                    <th>Tipo</th>
                                @endif
                                <th class="text-right">{{ $detailType === 'inventory' ? 'Cantidad' : 'Monto' }}</th>
                                <th style="width:35px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($detailRows as $i => $row)
                                <tr style="cursor:pointer;" @click="rowDetail = {{ json_encode($row) }}">
                                    <td class="text-gray-400 text-xs">{{ $i + 1 }}</td>
                                    <td class="text-gray-500">{{ $row['date'] }}</td>
                                    <td class="font-medium">{{ $row['description'] }}</td>
                                    @if ($detailType === 'expenses')
                                        <td>
                                            <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold bg-blue-50 text-blue-700">{{ $row['category'] }}</span>
                                        </td>
                                    @endif
                                    @if ($detailType === 'inventory')
                                        <td>
                                            <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold {{ $row['category'] === 'Entrada' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                                                {{ $row['category'] }}
                                            </span>
                                        </td>
                                    @endif
                                    <td class="text-right font-semibold {{ $detailType === 'sales' ? 'text-emerald-600' : ($detailType === 'expenses' ? 'text-red-500' : 'text-blue-600') }}">
                                        {{ $detailType === 'inventory' ? '' : '$' }}{{ number_format($row['amount'], $detailType === 'inventory' ? 0 : 2) }}
                                    </td>
                                    <td class="text-gray-300 text-center" style="font-size:0.75rem;">
                                        <x-filament::icon icon="heroicon-m-eye" class="w-3.5 h-3.5 inline" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $detailType === 'sales' ? 5 : 6 }}" class="text-center py-8 text-gray-400">
                                        <x-filament::icon icon="heroicon-o-inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300" />
                                        <p class="font-medium">Sin registros</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Footer --}}
                <div class="rpt-modal-footer">
                    <span>{{ $detailCount }} registro{{ $detailCount !== 1 ? 's' : '' }}</span>
                    <span class="font-bold {{ $detailType === 'sales' ? 'text-emerald-600' : ($detailType === 'expenses' ? 'text-red-500' : 'text-blue-600') }}" style="font-size:0.95rem;">
                        Total: {{ $detailType === 'inventory' ? '' : '$' }}{{ number_format($detailTotal, $detailType === 'inventory' ? 0 : 2) }}
                    </span>
                </div>
            </div>

            {{-- ── Row Detail Sub-Modal ── --}}
            <template x-if="rowDetail">
                <div style="position:fixed;inset:0;z-index:60;display:flex;align-items:center;justify-content:center;background:rgba(15,23,42,0.5);padding:1rem;" @click.self="rowDetail = null" @keydown.escape.window="rowDetail = null">
                    <div style="background:white;border-radius:0.75rem;width:100%;max-width:26rem;box-shadow:0 25px 50px rgba(0,0,0,0.3);overflow:hidden;" @click.stop>
                        <div style="background:linear-gradient(135deg,#1e293b,#334155);padding:0.85rem 1.25rem;display:flex;align-items:center;justify-content:space-between;">
                            <h4 style="color:white;font-size:0.88rem;font-weight:700;margin:0;">Detalle del Registro</h4>
                            <button @click="rowDetail = null" style="background:rgba(255,255,255,0.1);border:none;color:white;width:1.75rem;height:1.75rem;border-radius:0.3rem;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:1rem;">&times;</button>
                        </div>
                        <div style="padding:1rem 1.25rem;">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.8rem;">
                                <div>
                                    <div style="font-size:0.68rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;font-weight:600;margin-bottom:0.15rem;">Fecha</div>
                                    <div style="font-size:0.85rem;color:#334155;font-weight:500;" x-text="rowDetail.date"></div>
                                </div>
                                <div>
                                    <div style="font-size:0.68rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;font-weight:600;margin-bottom:0.15rem;">{{ $detailType === 'inventory' ? 'Cantidad' : 'Monto' }}</div>
                                    <div style="font-size:1.1rem;font-weight:800;" :class="'{{ $detailType === 'sales' ? 'text-emerald-600' : ($detailType === 'expenses' ? 'text-red-500' : 'text-blue-600') }}'" x-text="'{{ $detailType === 'inventory' ? '' : '$' }}' + (rowDetail.amount{{ $detailType !== 'inventory' ? ".toLocaleString('en',{minimumFractionDigits:2})" : '' }})"></div>
                                </div>
                                <div style="grid-column:span 2;">
                                    <div style="font-size:0.68rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;font-weight:600;margin-bottom:0.15rem;">Descripcion</div>
                                    <div style="font-size:0.85rem;color:#334155;font-weight:500;" x-text="rowDetail.description || 'Sin descripcion'"></div>
                                </div>
                                <template x-if="rowDetail.category">
                                    <div style="grid-column:span 2;">
                                        <div style="font-size:0.68rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;font-weight:600;margin-bottom:0.15rem;">Categoria</div>
                                        <span style="display:inline-block;padding:0.2rem 0.6rem;border-radius:9999px;font-size:0.75rem;font-weight:600;background:#EFF6FF;color:#1D4ED8;" x-text="rowDetail.category"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div style="padding:0.65rem 1.25rem;background:#f8fafc;border-top:1px solid #e2e8f0;text-align:right;">
                            <button @click="rowDetail = null" style="padding:0.4rem 1rem;background:#3b82f6;color:white;border:none;border-radius:0.4rem;font-size:0.78rem;font-weight:600;cursor:pointer;">Cerrar</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    @endif

    </div>{{-- end Alpine x-data --}}

    {{-- ── Global click handler to close filter dropdowns ── --}}
    <script>
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.rpt-filter-wrapper') && !e.target.closest('.rpt-filter-dropdown')) {
                document.querySelectorAll('.rpt-filter-dropdown.show').forEach(dd => dd.classList.remove('show'));
            }
        });
    </script>
</x-filament-panels::page>
