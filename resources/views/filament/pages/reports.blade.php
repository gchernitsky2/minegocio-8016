<x-filament-panels::page>
    @once
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
        <style>
            /* ══════════════════════════════════════
               REPORTS – Premium Dark Indigo Theme
               ══════════════════════════════════════ */

            .rpt-tab {
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }
            .rpt-tab:hover { transform: translateY(-2px); }
            .rpt-tab-active {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
                color: #fff !important;
                box-shadow: 0 4px 16px rgba(79, 70, 229, 0.35), 0 2px 4px rgba(79, 70, 229, 0.2);
            }
            .rpt-tab-inactive {
                background: #fff;
                color: #4b5563;
                box-shadow: 0 1px 3px rgba(0,0,0,0.04);
                border: 1px solid rgba(99, 102, 241, 0.08);
            }
            .rpt-tab-inactive:hover {
                background: #eef2ff;
                color: #4338ca;
                border-color: rgba(99, 102, 241, 0.2);
            }

            .rpt-card {
                border-radius: 1rem;
                overflow: hidden;
                background: white;
                border: 1px solid rgba(99, 102, 241, 0.08);
                box-shadow: 0 1px 3px rgba(0,0,0,0.03), 0 8px 24px rgba(99,102,241,0.04);
            }

            .rpt-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 0.875rem;
            }
            .rpt-table thead th {
                padding: 0.85rem 1.25rem;
                text-align: left;
                font-size: 0.68rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.07em;
                background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
                color: #c7d2fe;
                white-space: nowrap;
            }
            .rpt-table thead th.text-right { text-align: right; }
            .rpt-table tbody tr { transition: all 0.15s ease; }
            .rpt-table tbody tr:nth-child(odd) { background: #ffffff; }
            .rpt-table tbody tr:nth-child(even) { background: #fafaff; }
            .rpt-table tbody tr:hover { background: #eef2ff; }
            .rpt-table tbody td {
                padding: 0.7rem 1.25rem;
                color: #1e293b;
                border-bottom: 1px solid #f1f5f9;
            }
            .rpt-table tbody td.text-right { text-align: right; }
            .rpt-table tfoot td {
                padding: 0.85rem 1.25rem;
                background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
                color: #fff;
                font-weight: 700;
            }
            .rpt-table tfoot td.text-right { text-align: right; }

            /* Stat cards con gradiente y glass */
            .stat-gradient {
                border-radius: 1rem;
                padding: 1.5rem;
                position: relative;
                overflow: hidden;
                box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            }
            .stat-gradient::before {
                content: '';
                position: absolute;
                top: -30px;
                right: -30px;
                width: 100px;
                height: 100px;
                border-radius: 50%;
                background: rgba(255,255,255,0.12);
            }
            .stat-gradient::after {
                content: '';
                position: absolute;
                bottom: -20px;
                left: -20px;
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: rgba(255,255,255,0.06);
            }
            .stat-emerald { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
            .stat-rose { background: linear-gradient(135deg, #e11d48 0%, #f43f5e 100%); }
            .stat-indigo { background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); }
            .stat-violet { background: linear-gradient(135deg, #6d28d9 0%, #8b5cf6 100%); }
            .stat-slate { background: linear-gradient(135deg, #334155 0%, #475569 100%); }

            .chart-glass {
                background: white;
                border: 1px solid rgba(99, 102, 241, 0.08);
                border-radius: 1rem;
                padding: 1.5rem;
                box-shadow: 0 1px 3px rgba(0,0,0,0.03), 0 8px 24px rgba(99,102,241,0.04);
            }

            .filter-select {
                border-radius: 0.6rem;
                border: 1px solid rgba(99, 102, 241, 0.15);
                padding: 0.5rem 2rem 0.5rem 0.75rem;
                font-size: 0.875rem;
                font-weight: 500;
                color: #1e1b4b;
                background: white;
                box-shadow: 0 1px 2px rgba(0,0,0,0.04);
                transition: all 0.15s ease;
            }
            .filter-select:focus {
                border-color: #6366f1;
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
                outline: none;
            }
        </style>
    @endonce

    {{-- Tabs --}}
    <div class="flex flex-wrap gap-2.5 mb-6">
        @foreach ([
            'mensual' => ['Flujo Mensual', 'heroicon-o-calendar-days'],
            'anual' => ['Flujo Anual', 'heroicon-o-chart-bar'],
            'caja' => ['Flujo de Caja', 'heroicon-o-banknotes'],
            'categorias' => ['Gastos x Categoria', 'heroicon-o-tag'],
            'top_ventas' => ['Top Ventas', 'heroicon-o-arrow-trending-up'],
            'inventario' => ['Inventario', 'heroicon-o-cube'],
        ] as $key => [$label, $icon])
            <button wire:click="$set('tab', '{{ $key }}')"
                class="rpt-tab flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold
                {{ $tab === $key ? 'rpt-tab-active' : 'rpt-tab-inactive' }}">
                <x-filament::icon :icon="$icon" class="w-4 h-4" />
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="flex items-center justify-between mb-6 gap-3 flex-wrap">
        <div class="flex gap-2.5">
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
        </div>
        <button wire:click="toggleChart"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all
            {{ $showChart
                ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200 hover:bg-indigo-100'
                : 'bg-white text-gray-500 ring-1 ring-gray-200 hover:bg-gray-50' }}">
            <x-filament::icon icon="heroicon-o-chart-bar-square" class="w-4 h-4" />
            {{ $showChart ? 'Ocultar grafica' : 'Mostrar grafica' }}
        </button>
    </div>

    {{-- ═══ FLUJO MENSUAL ═══ --}}
    @if ($tab === 'mensual')
        @php
            $monthlyData = $this->getMonthlyData();
            $chartData = $this->getMonthlyChartData();
            $totalSales = array_sum(array_column($monthlyData, 'sales'));
            $totalExpenses = array_sum(array_column($monthlyData, 'expenses'));
            $totalBalance = $totalSales - $totalExpenses;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="stat-gradient stat-emerald text-white">
                <p class="text-emerald-200 text-xs font-bold uppercase tracking-wider">Ventas {{ $this->selectedYear }}</p>
                <p class="text-2xl font-extrabold mt-1.5 relative z-10">${{ number_format($totalSales, 2) }}</p>
            </div>
            <div class="stat-gradient stat-rose text-white">
                <p class="text-rose-200 text-xs font-bold uppercase tracking-wider">Gastos {{ $this->selectedYear }}</p>
                <p class="text-2xl font-extrabold mt-1.5 relative z-10">${{ number_format($totalExpenses, 2) }}</p>
            </div>
            <div class="stat-gradient {{ $totalBalance >= 0 ? 'stat-indigo' : 'stat-rose' }} text-white">
                <p class="{{ $totalBalance >= 0 ? 'text-indigo-200' : 'text-rose-200' }} text-xs font-bold uppercase tracking-wider">Balance Neto</p>
                <p class="text-2xl font-extrabold mt-1.5 relative z-10">${{ number_format($totalBalance, 2) }}</p>
            </div>
        </div>

        @if ($showChart)
            <div class="chart-glass mb-6">
                <canvas id="monthlyChart" height="85"></canvas>
            </div>
            <script>
                (function() {
                    function render() {
                        const el = document.getElementById('monthlyChart');
                        if (!el) return;
                        if (el._chart) el._chart.destroy();
                        el._chart = new Chart(el, {
                            type: 'bar',
                            data: {
                                labels: @json($chartData['labels']),
                                datasets: [
                                    { label: 'Ventas', data: @json($chartData['sales']), backgroundColor: 'rgba(16, 185, 129, 0.85)', borderRadius: 6, borderSkipped: false },
                                    { label: 'Gastos', data: @json($chartData['expenses']), backgroundColor: 'rgba(244, 63, 94, 0.85)', borderRadius: 6, borderSkipped: false },
                                    { label: 'Balance', data: @json($chartData['balance']), type: 'line', borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,0.06)', fill: true, tension: 0.4, borderWidth: 2.5, pointRadius: 4, pointBackgroundColor: '#6366f1', pointBorderColor: '#fff', pointBorderWidth: 2 }
                                ]
                            },
                            options: { responsive: true, interaction: { intersect: false, mode: 'index' }, plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 20, font: { size: 12, weight: '600' } } }, tooltip: { backgroundColor: '#1e1b4b', titleFont: { size: 13, weight: '600' }, bodyFont: { size: 12 }, padding: 14, cornerRadius: 10, boxPadding: 4 } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(99,102,241,0.06)' }, ticks: { font: { size: 11 } } }, x: { grid: { display: false }, ticks: { font: { size: 11 } } } } }
                        });
                    }
                    render();
                    document.addEventListener('livewire:navigated', render);
                })();
            </script>
        @endif

        <div class="rpt-card">
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th>Mes</th>
                        <th class="text-right" style="color:#6ee7b7">Ventas</th>
                        <th class="text-right" style="color:#fda4af">Gastos</th>
                        <th class="text-right">Balance</th>
                        <th class="text-right" style="width:140px">Proporcion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($monthlyData as $row)
                        <tr>
                            <td class="font-semibold">{{ $row['month'] }}</td>
                            <td class="text-right font-semibold text-emerald-600">${{ number_format($row['sales'], 2) }}</td>
                            <td class="text-right font-semibold text-rose-500">${{ number_format($row['expenses'], 2) }}</td>
                            <td class="text-right font-bold {{ $row['balance'] >= 0 ? 'text-indigo-600' : 'text-rose-600' }}">${{ number_format($row['balance'], 2) }}</td>
                            <td class="text-right">
                                @php $total = $row['sales'] + $row['expenses']; @endphp
                                <div class="flex gap-px h-2.5 rounded-full overflow-hidden bg-gray-100">
                                    @if ($total > 0)
                                        <div class="bg-emerald-500 rounded-l-full" style="width:{{ $row['sales']/$total*100 }}%"></div>
                                        <div class="bg-rose-400 rounded-r-full" style="width:{{ $row['expenses']/$total*100 }}%"></div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>TOTAL</td>
                        <td class="text-right" style="color:#6ee7b7">${{ number_format($totalSales, 2) }}</td>
                        <td class="text-right" style="color:#fda4af">${{ number_format($totalExpenses, 2) }}</td>
                        <td class="text-right" style="color:{{ $totalBalance >= 0 ? '#a5b4fc' : '#fda4af' }}">${{ number_format($totalBalance, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    {{-- ═══ FLUJO ANUAL ═══ --}}
    @if ($tab === 'anual')
        @php $annualData = $this->getAnnualData(); @endphp

        @if ($showChart)
            <div class="chart-glass mb-6">
                <canvas id="annualChart" height="85"></canvas>
            </div>
            <script>
                (function() {
                    function render() {
                        const el = document.getElementById('annualChart');
                        if (!el) return;
                        if (el._chart) el._chart.destroy();
                        el._chart = new Chart(el, {
                            type: 'bar',
                            data: {
                                labels: @json(array_column($annualData, 'year')),
                                datasets: [
                                    { label: 'Ventas', data: @json(array_column($annualData, 'sales')), backgroundColor: 'rgba(16, 185, 129, 0.85)', borderRadius: 8, borderSkipped: false, barPercentage: 0.5 },
                                    { label: 'Gastos', data: @json(array_column($annualData, 'expenses')), backgroundColor: 'rgba(244, 63, 94, 0.85)', borderRadius: 8, borderSkipped: false, barPercentage: 0.5 }
                                ]
                            },
                            options: { responsive: true, plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 20, font: { size: 12, weight: '600' } } }, tooltip: { backgroundColor: '#1e1b4b', padding: 14, cornerRadius: 10 } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(99,102,241,0.06)' } }, x: { grid: { display: false } } } }
                        });
                    }
                    render();
                    document.addEventListener('livewire:navigated', render);
                })();
            </script>
        @endif

        <div class="rpt-card">
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th>Ano</th>
                        <th class="text-right" style="color:#6ee7b7">Ventas</th>
                        <th class="text-right" style="color:#fda4af">Gastos</th>
                        <th class="text-right">Balance</th>
                        <th class="text-right">Margen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($annualData as $row)
                        @php $margin = $row['sales'] > 0 ? ($row['balance'] / $row['sales'] * 100) : 0; @endphp
                        <tr>
                            <td class="font-bold text-lg text-indigo-900">{{ $row['year'] }}</td>
                            <td class="text-right text-emerald-600 font-semibold">${{ number_format($row['sales'], 2) }}</td>
                            <td class="text-right text-rose-500 font-semibold">${{ number_format($row['expenses'], 2) }}</td>
                            <td class="text-right font-bold {{ $row['balance'] >= 0 ? 'text-indigo-600' : 'text-rose-600' }}">${{ number_format($row['balance'], 2) }}</td>
                            <td class="text-right">
                                <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold {{ $margin >= 0 ? 'bg-indigo-50 text-indigo-700' : 'bg-rose-50 text-rose-700' }}">
                                    {{ number_format($margin, 1) }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
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
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="stat-gradient stat-emerald text-white">
                <p class="text-emerald-200 text-xs font-bold uppercase tracking-wider">Total Ingresos</p>
                <p class="text-2xl font-extrabold mt-1.5 relative z-10">${{ number_format($totalIn, 2) }}</p>
            </div>
            <div class="stat-gradient stat-rose text-white">
                <p class="text-rose-200 text-xs font-bold uppercase tracking-wider">Total Egresos</p>
                <p class="text-2xl font-extrabold mt-1.5 relative z-10">${{ number_format($totalOut, 2) }}</p>
            </div>
            <div class="stat-gradient {{ $totalNet >= 0 ? 'stat-indigo' : 'stat-rose' }} text-white">
                <p class="{{ $totalNet >= 0 ? 'text-indigo-200' : 'text-rose-200' }} text-xs font-bold uppercase tracking-wider">Flujo Neto</p>
                <p class="text-2xl font-extrabold mt-1.5 relative z-10">${{ number_format($totalNet, 2) }}</p>
            </div>
        </div>

        @php
            $cashFlowExpensesNeg = [];
            foreach (array_column($cashFlowData, 'expenses') as $v) {
                $cashFlowExpensesNeg[] = $v * -1;
            }
        @endphp

        @if ($showChart && count($cashFlowData) > 0)
            <div class="chart-glass mb-6">
                <canvas id="cashFlowChart" height="85"></canvas>
            </div>
            <script>
                (function() {
                    function render() {
                        const el = document.getElementById('cashFlowChart');
                        if (!el) return;
                        if (el._chart) el._chart.destroy();
                        el._chart = new Chart(el, {
                            type: 'bar',
                            data: {
                                labels: @json(array_column($cashFlowData, 'day')),
                                datasets: [
                                    { label: 'Ingresos', data: @json(array_column($cashFlowData, 'sales')), backgroundColor: 'rgba(16, 185, 129, 0.85)', borderRadius: 5, borderSkipped: false },
                                    { label: 'Egresos', data: @json($cashFlowExpensesNeg), backgroundColor: 'rgba(244, 63, 94, 0.85)', borderRadius: 5, borderSkipped: false },
                                    { label: 'Saldo', data: @json(array_column($cashFlowData, 'balance')), type: 'line', borderColor: '#6366f1', borderWidth: 2.5, tension: 0.3, pointRadius: 3, pointBackgroundColor: '#6366f1', pointBorderColor: '#fff', pointBorderWidth: 2, fill: false }
                                ]
                            },
                            options: { responsive: true, interaction: { intersect: false, mode: 'index' }, plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 20, font: { size: 12, weight: '600' } } }, tooltip: { backgroundColor: '#1e1b4b', padding: 14, cornerRadius: 10 } }, scales: { y: { grid: { color: 'rgba(99,102,241,0.06)' } }, x: { grid: { display: false } } } }
                        });
                    }
                    render();
                    document.addEventListener('livewire:navigated', render);
                })();
            </script>
        @endif

        <div class="rpt-card">
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Dia</th>
                        <th class="text-right" style="color:#6ee7b7">Ingresos</th>
                        <th class="text-right" style="color:#fda4af">Egresos</th>
                        <th class="text-right">Neto</th>
                        <th class="text-right">Saldo Acum.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cashFlowData as $row)
                        <tr>
                            <td class="font-medium">{{ $row['date'] }}</td>
                            <td class="text-gray-500">{{ $row['day_name'] }}</td>
                            <td class="text-right text-emerald-600 font-semibold">${{ number_format($row['sales'], 2) }}</td>
                            <td class="text-right text-rose-500 font-semibold">${{ number_format($row['expenses'], 2) }}</td>
                            <td class="text-right">
                                <span class="inline-block px-2.5 py-0.5 rounded-lg text-xs font-bold {{ $row['net'] >= 0 ? 'bg-indigo-50 text-indigo-700' : 'bg-rose-50 text-rose-700' }}">
                                    {{ $row['net'] >= 0 ? '+' : '' }}${{ number_format($row['net'], 2) }}
                                </span>
                            </td>
                            <td class="text-right font-bold {{ $row['balance'] >= 0 ? 'text-indigo-600' : 'text-rose-600' }}">${{ number_format($row['balance'], 2) }}</td>
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
                            <td class="text-right" style="color:#6ee7b7">${{ number_format($totalIn, 2) }}</td>
                            <td class="text-right" style="color:#fda4af">${{ number_format($totalOut, 2) }}</td>
                            <td class="text-right" style="color:{{ $totalNet >= 0 ? '#a5b4fc' : '#fda4af' }}">{{ $totalNet >= 0 ? '+' : '' }}${{ number_format($totalNet, 2) }}</td>
                            <td class="text-right" style="color:{{ end($cashFlowData)['balance'] >= 0 ? '#a5b4fc' : '#fda4af' }}">${{ number_format(end($cashFlowData)['balance'], 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    @endif

    {{-- ═══ GASTOS POR CATEGORIA ═══ --}}
    @if ($tab === 'categorias')
        @php
            $catData = $this->getExpensesByCategoryData();
            $catTotal = array_sum(array_column($catData, 'total'));
            $colors = ['#6366f1','#f43f5e','#10b981','#f59e0b','#8b5cf6','#06b6d4','#ec4899','#84cc16'];
        @endphp

        @if ($showChart && count($catData) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <div class="chart-glass">
                    <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-4">Distribucion</p>
                    <canvas id="catDoughnut" height="220"></canvas>
                </div>
                <div class="chart-glass">
                    <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-4">Comparativa</p>
                    <canvas id="catBar" height="220"></canvas>
                </div>
            </div>
            <script>
                (function() {
                    function render() {
                        const labels = @json(array_column($catData, 'category'));
                        const data = @json(array_column($catData, 'total'));
                        const colors = @json(array_slice($colors, 0, count($catData)));
                        const d = document.getElementById('catDoughnut');
                        const b = document.getElementById('catBar');
                        if (d) { if (d._chart) d._chart.destroy(); d._chart = new Chart(d, { type: 'doughnut', data: { labels, datasets: [{ data, backgroundColor: colors, borderWidth: 3, borderColor: '#fff', hoverOffset: 10 }] }, options: { responsive: true, cutout: '65%', plugins: { legend: { position: 'right', labels: { usePointStyle: true, padding: 14, font: { size: 12, weight: '500' } } } } } }); }
                        if (b) { if (b._chart) b._chart.destroy(); b._chart = new Chart(b, { type: 'bar', data: { labels, datasets: [{ data, backgroundColor: colors, borderRadius: 8, borderSkipped: false }] }, options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e1b4b', padding: 14, cornerRadius: 10 } }, scales: { x: { grid: { color: 'rgba(99,102,241,0.06)' } }, y: { grid: { display: false } } } } }); }
                    }
                    render();
                    document.addEventListener('livewire:navigated', render);
                })();
            </script>
        @endif

        <div class="rpt-card">
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th>Categoria</th>
                        <th class="text-right">Transacciones</th>
                        <th class="text-right">Promedio</th>
                        <th class="text-right" style="color:#fda4af">Total</th>
                        <th class="text-right">% del Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($catData as $i => $row)
                        @php $pct = $catTotal > 0 ? ($row['total'] / $catTotal * 100) : 0; @endphp
                        <tr>
                            <td>
                                <span class="flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-md inline-block" style="background:{{ $colors[$i % count($colors)] }}"></span>
                                    <span class="font-semibold">{{ $row['category'] }}</span>
                                </span>
                            </td>
                            <td class="text-right text-gray-500">{{ $row['count'] }}</td>
                            <td class="text-right text-gray-500">${{ number_format($row['average'], 2) }}</td>
                            <td class="text-right text-rose-500 font-bold">${{ number_format($row['total'], 2) }}</td>
                            <td class="text-right">
                                <span class="flex items-center justify-end gap-2.5">
                                    <span class="w-16 h-2 rounded-full bg-gray-100 overflow-hidden inline-block">
                                        <span class="h-full rounded-full block" style="width:{{ $pct }}%; background:{{ $colors[$i % count($colors)] }}"></span>
                                    </span>
                                    <span class="text-xs font-bold text-gray-500 w-10 text-right">{{ number_format($pct, 1) }}%</span>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>TOTAL</td>
                        <td class="text-right" style="color:#94a3b8">{{ array_sum(array_column($catData, 'count')) }}</td>
                        <td></td>
                        <td class="text-right" style="color:#fda4af">${{ number_format($catTotal, 2) }}</td>
                        <td class="text-right" style="color:#94a3b8">100%</td>
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
            <div class="chart-glass mb-6">
                <canvas id="topSalesChart" height="80"></canvas>
            </div>
            <script>
                (function() {
                    function render() {
                        const el = document.getElementById('topSalesChart');
                        if (!el) return;
                        if (el._chart) el._chart.destroy();
                        const data = @json(array_column($topSales, 'amount'));
                        const labels = @json($topSalesLabels);
                        const gradient = el.getContext('2d');
                        const bg = gradient.createLinearGradient(0, 0, el.width, 0);
                        bg.addColorStop(0, '#6366f1');
                        bg.addColorStop(1, '#8b5cf6');
                        el._chart = new Chart(el, { type: 'bar', data: { labels, datasets: [{ data, backgroundColor: bg, borderRadius: 6, borderSkipped: false }] }, options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e1b4b', padding: 14, cornerRadius: 10 } }, scales: { x: { grid: { color: 'rgba(99,102,241,0.06)' } }, y: { grid: { display: false }, ticks: { font: { size: 11 } } } } } });
                    }
                    render();
                    document.addEventListener('livewire:navigated', render);
                })();
            </script>
        @endif

        <div class="rpt-card">
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Fecha</th>
                        <th>Descripcion</th>
                        <th class="text-right" style="color:#6ee7b7">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topSales as $i => $sale)
                        <tr>
                            <td>
                                @if ($i < 3)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold text-white {{ $i === 0 ? 'bg-gradient-to-br from-amber-400 to-amber-600' : ($i === 1 ? 'bg-gradient-to-br from-slate-300 to-slate-500' : 'bg-gradient-to-br from-amber-600 to-amber-800') }}">{{ $i + 1 }}</span>
                                @else
                                    <span class="text-gray-400 font-mono text-xs">{{ $i + 1 }}</span>
                                @endif
                            </td>
                            <td class="text-gray-500 font-medium">{{ $sale['date'] }}</td>
                            <td class="font-medium">{{ $sale['description'] }}</td>
                            <td class="text-right text-emerald-600 font-bold">${{ number_format($sale['amount'], 2) }}</td>
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="stat-gradient stat-slate text-white">
                <p class="text-slate-300 text-xs font-bold uppercase tracking-wider">Total Productos</p>
                <p class="text-2xl font-extrabold mt-1.5 relative z-10">{{ count($invData) }}</p>
            </div>
            <div class="stat-gradient stat-violet text-white">
                <p class="text-violet-200 text-xs font-bold uppercase tracking-wider">Unidades en Stock</p>
                <p class="text-2xl font-extrabold mt-1.5 relative z-10">{{ number_format($totalStock) }}</p>
            </div>
            <div class="stat-gradient stat-emerald text-white">
                <p class="text-emerald-200 text-xs font-bold uppercase tracking-wider">Valor del Inventario</p>
                <p class="text-2xl font-extrabold mt-1.5 relative z-10">${{ number_format($totalValue, 2) }}</p>
            </div>
        </div>

        @if ($showChart && count($invData) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <div class="chart-glass">
                    <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-4">Stock por producto</p>
                    <canvas id="invStock" height="200"></canvas>
                </div>
                <div class="chart-glass">
                    <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-4">Valor por producto</p>
                    <canvas id="invValue" height="200"></canvas>
                </div>
            </div>
            <script>
                (function() {
                    function render() {
                        const names = @json(array_column($invData, 'name'));
                        const stock = @json(array_column($invData, 'stock'));
                        const values = @json(array_column($invData, 'value'));
                        const colors = ['#6366f1','#f43f5e','#10b981','#f59e0b','#8b5cf6','#06b6d4','#ec4899','#84cc16','#14b8a6','#f97316'];
                        const s = document.getElementById('invStock');
                        const v = document.getElementById('invValue');
                        if (s) { if (s._chart) s._chart.destroy(); s._chart = new Chart(s, { type: 'bar', data: { labels: names, datasets: [{ data: stock, backgroundColor: colors.slice(0, names.length), borderRadius: 6, borderSkipped: false }] }, options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(99,102,241,0.06)' } }, x: { grid: { display: false } } } } }); }
                        if (v) { if (v._chart) v._chart.destroy(); v._chart = new Chart(v, { type: 'doughnut', data: { labels: names, datasets: [{ data: values, backgroundColor: colors.slice(0, names.length), borderWidth: 3, borderColor: '#fff', hoverOffset: 10 }] }, options: { responsive: true, cutout: '60%', plugins: { legend: { position: 'right', labels: { usePointStyle: true, padding: 12, font: { size: 11, weight: '500' } } } } } }); }
                    }
                    render();
                    document.addEventListener('livewire:navigated', render);
                })();
            </script>
        @endif

        <div class="rpt-card">
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Costo</th>
                        <th class="text-right">Precio</th>
                        <th class="text-right">Margen</th>
                        <th class="text-right">Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invData as $row)
                        <tr>
                            <td class="font-semibold">{{ $row['name'] }}</td>
                            <td><span class="font-mono text-xs text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded">{{ $row['sku'] }}</span></td>
                            <td class="text-right">
                                <span class="inline-block px-2.5 py-0.5 rounded-lg text-xs font-bold {{ $row['stock'] <= 0 ? 'bg-rose-50 text-rose-700' : ($row['stock'] <= 5 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') }}">{{ $row['stock'] }}</span>
                            </td>
                            <td class="text-right text-gray-500">${{ number_format($row['cost'], 2) }}</td>
                            <td class="text-right font-semibold">${{ number_format($row['price'], 2) }}</td>
                            <td class="text-right">
                                <span class="inline-block px-2.5 py-0.5 rounded-lg text-xs font-bold {{ $row['margin'] >= 30 ? 'bg-emerald-50 text-emerald-700' : ($row['margin'] >= 15 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">{{ number_format($row['margin'], 1) }}%</span>
                            </td>
                            <td class="text-right text-indigo-600 font-bold">${{ number_format($row['value'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">TOTAL</td>
                        <td class="text-right" style="color:#94a3b8">{{ number_format($totalStock) }}</td>
                        <td colspan="3"></td>
                        <td class="text-right" style="color:#a5b4fc">${{ number_format($totalValue, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</x-filament-panels::page>
