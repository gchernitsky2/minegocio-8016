<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Models\Sale;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Ventas';

    protected static ?string $modelLabel = 'Venta';

    protected static ?string $pluralModelLabel = 'Ventas';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(3)
                    ->compact()
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->label('Monto')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('0.00')
                            ->maxValue(999999999.99)
                            ->step(0.01)
                            ->autofocus(),
                        Forms\Components\DatePicker::make('date')
                            ->label('Fecha')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(now()),
                        Forms\Components\TextInput::make('description')
                            ->label('Descripcion')
                            ->placeholder('Ej: Venta mostrador...')
                            ->maxLength(255),
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
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->money('MXN')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripcion')
                    ->limit(60)
                    ->searchable()
                    ->placeholder('Sin descripcion')
                    ->color('gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Desde')
                            ->native(false),
                        Forms\Components\DatePicker::make('until')
                            ->label('Hasta')
                            ->native(false),
                    ])
                    ->columns(2)
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->where('date', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->where('date', '<=', $date));
                    }),
            ])
            ->actions([
                Actions\ViewAction::make()
                    ->iconButton()
                    ->modalHeading('Detalle de Venta')
                    ->infolist([
                        Group::make([
                            Infolists\Components\TextEntry::make('date')
                                ->label('Fecha')
                                ->date('d/m/Y')
                                ->icon('heroicon-m-calendar')
                                ->iconColor('primary'),
                            Infolists\Components\TextEntry::make('amount')
                                ->label('Monto')
                                ->money('MXN')
                                ->icon('heroicon-m-banknotes')
                                ->iconColor('success')
                                ->size('lg')
                                ->weight('bold')
                                ->color('success'),
                            Infolists\Components\TextEntry::make('description')
                                ->label('Descripcion')
                                ->placeholder('Sin descripcion')
                                ->icon('heroicon-m-document-text')
                                ->iconColor('gray')
                                ->columnSpanFull(),
                            Infolists\Components\TextEntry::make('created_at')
                                ->label('Registrado')
                                ->dateTime('d/m/Y H:i')
                                ->icon('heroicon-m-clock')
                                ->iconColor('gray'),
                        ])->columns(2),
                    ]),
                Actions\EditAction::make()
                    ->iconButton()
                    ->modalHeading('Editar Venta'),
                Actions\DeleteAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Sin ventas registradas')
            ->emptyStateDescription('Comienza registrando tu primera venta.')
            ->emptyStateIcon('heroicon-o-banknotes')
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
