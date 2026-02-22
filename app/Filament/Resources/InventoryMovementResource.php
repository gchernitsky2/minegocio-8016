<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryMovementResource\Pages;
use App\Models\InventoryMovement;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class InventoryMovementResource extends Resource
{
    protected static ?string $model = InventoryMovement::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationLabel = 'Movimientos';

    protected static ?string $modelLabel = 'Movimiento';

    protected static ?string $pluralModelLabel = 'Movimientos';

    protected static ?int $navigationSort = 5;

    protected static string | \UnitEnum | null $navigationGroup = 'Inventario';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Section::make('Movimiento de inventario')
                    ->icon('heroicon-o-arrows-right-left')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Producto')
                            ->relationship('product', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->helperText('Selecciona el producto'),
                        Forms\Components\Select::make('type')
                            ->label('Tipo de movimiento')
                            ->options([
                                'entrada' => 'Entrada (compra/reposicion)',
                                'salida' => 'Salida (venta/merma)',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Cantidad')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('0'),
                        Forms\Components\DatePicker::make('date')
                            ->label('Fecha')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(now()),
                        Forms\Components\Textarea::make('reason')
                            ->label('Motivo')
                            ->placeholder('Ej: Compra proveedor, venta cliente, merma...')
                            ->maxLength(255)
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
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'entrada' => 'success',
                        'salida' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'entrada' => 'heroicon-m-arrow-down-tray',
                        'salida' => 'heroicon-m-arrow-up-tray',
                        default => 'heroicon-m-minus',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->sortable()
                    ->weight('bold')
                    ->color(fn (InventoryMovement $record): string => $record->type === 'entrada' ? 'success' : 'danger')
                    ->formatStateUsing(fn (InventoryMovement $record): string => ($record->type === 'entrada' ? '+' : '-') . $record->quantity),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Motivo')
                    ->limit(40)
                    ->placeholder('Sin motivo')
                    ->color('gray'),
            ])
            ->defaultSort('date', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'entrada' => 'Entrada',
                        'salida' => 'Salida',
                    ]),
                Tables\Filters\SelectFilter::make('product_id')
                    ->label('Producto')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Actions\DeleteAction::make()
                    ->iconButton(),
            ])
            ->emptyStateHeading('Sin movimientos')
            ->emptyStateDescription('Registra el primer movimiento de inventario.')
            ->emptyStateIcon('heroicon-o-arrows-right-left')
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryMovements::route('/'),
            'create' => Pages\CreateInventoryMovement::route('/create'),
        ];
    }
}
