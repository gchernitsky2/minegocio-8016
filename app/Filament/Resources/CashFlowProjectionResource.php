<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CashFlowProjectionResource\Pages;
use App\Models\CashFlowProjection;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class CashFlowProjectionResource extends Resource
{
    protected static ?string $model = CashFlowProjection::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static ?string $navigationLabel = 'Proyecciones';

    protected static ?string $modelLabel = 'Proyección';

    protected static ?string $pluralModelLabel = 'Proyecciones';

    protected static ?int $navigationSort = 6;

    protected static string | \UnitEnum | null $navigationGroup = 'Finanzas';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos de la proyección')
                    ->icon('heroicon-o-arrow-trending-up')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Pago de renta, Cobro cliente...')
                            ->autofocus()
                            ->columnSpanFull(),

                        Forms\Components\DatePicker::make('date')
                            ->label('Fecha')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y'),

                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'planned' => 'Planeado',
                                'completed' => 'Completado',
                                'cancelled' => 'Cancelado',
                            ])
                            ->default('planned')
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('expected_income')
                            ->label('Ingreso Esperado')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->step(0.01)
                            ->maxValue(999999999.99),

                        Forms\Components\TextInput::make('expected_expense')
                            ->label('Gasto Esperado')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->step(0.01)
                            ->maxValue(999999999.99),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->maxLength(500)
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-m-calendar'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40),

                Tables\Columns\TextColumn::make('expected_income')
                    ->label('Ingreso')
                    ->money('MXN')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('expected_expense')
                    ->label('Gasto')
                    ->money('MXN')
                    ->sortable()
                    ->color('danger')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'planned' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'planned' => 'Planeado',
                        'completed' => 'Completado',
                        'cancelled' => 'Cancelado',
                    }),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Notas')
                    ->limit(30)
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'asc')
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'planned' => 'Planeado',
                        'completed' => 'Completado',
                        'cancelled' => 'Cancelado',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make()->iconButton(),
                Actions\DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Sin proyecciones')
            ->emptyStateDescription('Agrega ingresos y gastos futuros para proyectar tu flujo de caja.')
            ->emptyStateIcon('heroicon-o-arrow-trending-up')
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCashFlowProjections::route('/'),
            'create' => Pages\CreateCashFlowProjection::route('/create'),
            'edit' => Pages\EditCashFlowProjection::route('/{record}/edit'),
        ];
    }
}
