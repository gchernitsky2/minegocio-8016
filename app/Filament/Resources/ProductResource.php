<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Productos';

    protected static ?string $modelLabel = 'Producto';

    protected static ?string $pluralModelLabel = 'Productos';

    protected static ?int $navigationSort = 4;

    protected static string | \UnitEnum | null $navigationGroup = 'Inventario';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informacion del producto')
                    ->icon('heroicon-o-cube')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nombre del producto')
                            ->autofocus()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej: SKU-001'),
                        Forms\Components\TextInput::make('stock')
                            ->label('Stock actual')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false)
                            ->visibleOn('edit')
                            ->helperText('Se actualiza con movimientos de inventario'),
                    ]),
                Section::make('Precios')
                    ->icon('heroicon-o-currency-dollar')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('cost')
                            ->label('Costo de compra')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('0.00')
                            ->step(0.01),
                        Forms\Components\TextInput::make('price')
                            ->label('Precio de venta')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('0.00')
                            ->step(0.01),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->color('gray')
                    ->badge(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\TextColumn::make('cost')
                    ->label('Costo')
                    ->money('MXN')
                    ->sortable()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money('MXN')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                Tables\Columns\TextColumn::make('margin')
                    ->label('Margen')
                    ->getStateUsing(fn (Product $record): string => number_format($record->margin, 1) . '%')
                    ->badge()
                    ->color(fn (Product $record): string => match (true) {
                        $record->margin >= 30 => 'success',
                        $record->margin >= 15 => 'warning',
                        default => 'danger',
                    }),
            ])
            ->defaultSort('name')
            ->striped()
            ->filters([
                Tables\Filters\Filter::make('low_stock')
                    ->label('Stock bajo (<=5)')
                    ->query(fn ($query) => $query->lowStock()),
            ])
            ->actions([
                Actions\ViewAction::make()
                    ->iconButton()
                    ->modalHeading('Detalle de Producto')
                    ->infolist([
                        Group::make([
                            Infolists\Components\TextEntry::make('name')
                                ->label('Producto')
                                ->icon('heroicon-m-cube')
                                ->iconColor('primary')
                                ->weight('bold')
                                ->columnSpanFull(),
                            Infolists\Components\TextEntry::make('sku')
                                ->label('SKU')
                                ->badge()
                                ->color('gray')
                                ->placeholder('-'),
                            Infolists\Components\TextEntry::make('stock')
                                ->label('Stock')
                                ->badge()
                                ->color(fn (Product $record): string => match (true) {
                                    $record->stock <= 0 => 'danger',
                                    $record->stock <= 5 => 'warning',
                                    default => 'success',
                                }),
                            Infolists\Components\TextEntry::make('cost')
                                ->label('Costo')
                                ->money('MXN')
                                ->icon('heroicon-m-arrow-down-circle')
                                ->iconColor('gray'),
                            Infolists\Components\TextEntry::make('price')
                                ->label('Precio')
                                ->money('MXN')
                                ->icon('heroicon-m-arrow-up-circle')
                                ->iconColor('success')
                                ->weight('bold')
                                ->color('success'),
                            Infolists\Components\TextEntry::make('margin')
                                ->label('Margen')
                                ->getStateUsing(fn (Product $record): string => number_format($record->margin, 1) . '%')
                                ->badge()
                                ->color(fn (Product $record): string => match (true) {
                                    $record->margin >= 30 => 'success',
                                    $record->margin >= 15 => 'warning',
                                    default => 'danger',
                                }),
                            Infolists\Components\TextEntry::make('value')
                                ->label('Valor Total')
                                ->getStateUsing(fn (Product $record): string => '$' . number_format($record->value, 2))
                                ->icon('heroicon-m-banknotes')
                                ->iconColor('primary')
                                ->weight('bold')
                                ->color('primary'),
                        ])->columns(2),
                    ]),
                Actions\EditAction::make()
                    ->iconButton(),
                Actions\DeleteAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Sin productos')
            ->emptyStateDescription('Registra tu primer producto.')
            ->emptyStateIcon('heroicon-o-cube')
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
