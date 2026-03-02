<x-filament-widgets::widget>
    <x-filament::section>
        <div style="border-radius: 0.75rem; overflow: hidden; border: 1px solid #BFDBFE;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead>
                    <tr style="background: #1E40AF; color: #ffffff;">
                        <th style="padding: 0.65rem 1rem; text-align: left; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Fecha</th>
                        <th style="padding: 0.65rem 1rem; text-align: left; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Descripción</th>
                        <th style="padding: 0.65rem 1rem; text-align: left; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Categoría</th>
                        <th style="padding: 0.65rem 1rem; text-align: right; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Ingresos</th>
                        <th style="padding: 0.65rem 1rem; text-align: right; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Egresos</th>
                        <th style="padding: 0.65rem 1rem; text-align: right; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->getEntries() as $index => $entry)
                        <tr style="background: {{ $index % 2 === 0 ? '#EFF6FF' : '#DBEAFE' }}; transition: background 0.15s ease;"
                            onmouseover="this.style.background='#BFDBFE'" onmouseout="this.style.background='{{ $index % 2 === 0 ? '#EFF6FF' : '#DBEAFE' }}'">
                            <td style="padding: 0.55rem 1rem; color: #1e3a5f; white-space: nowrap;">
                                {{ $entry['date']->format('d/m/Y') }}
                            </td>
                            <td style="padding: 0.55rem 1rem; color: #1e3a5f;">
                                {{ $entry['description'] }}
                            </td>
                            <td style="padding: 0.55rem 1rem; color: #1e3a5f;">
                                {{ $entry['category'] }}
                            </td>
                            <td style="padding: 0.55rem 1rem; text-align: right; font-weight: 700; color: {{ $entry['income'] > 0 ? '#16a34a' : '#94a3b8' }};">
                                {{ $entry['income'] > 0 ? '$' . number_format($entry['income'], 2, ',', '.') : '-' }}
                            </td>
                            <td style="padding: 0.55rem 1rem; text-align: right; font-weight: 700; color: {{ $entry['expense'] > 0 ? '#dc2626' : '#94a3b8' }};">
                                {{ $entry['expense'] > 0 ? '$' . number_format($entry['expense'], 2, ',', '.') : '-' }}
                            </td>
                            <td style="padding: 0.55rem 1rem; text-align: right; font-weight: 700; color: {{ $entry['balance'] >= 0 ? '#16a34a' : '#dc2626' }};">
                                ${{ number_format($entry['balance'], 2, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr style="background: #EFF6FF;">
                            <td colspan="6" style="padding: 2rem 1rem; text-align: center; color: #64748b;">
                                No hay movimientos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
