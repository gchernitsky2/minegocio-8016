<?php

declare(strict_types=1);

namespace App\Filament\Resources\CashFlowProjectionResource\Pages;

use App\Filament\Resources\CashFlowProjectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCashFlowProjection extends EditRecord
{
    protected static string $resource = CashFlowProjectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
