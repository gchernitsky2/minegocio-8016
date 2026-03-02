<?php

declare(strict_types=1);

namespace App\Filament\Resources\CashFlowProjectionResource\Pages;

use App\Filament\Resources\CashFlowProjectionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCashFlowProjection extends CreateRecord
{
    protected static string $resource = CashFlowProjectionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
