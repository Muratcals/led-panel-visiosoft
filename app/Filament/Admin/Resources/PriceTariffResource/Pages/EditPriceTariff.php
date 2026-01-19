<?php

namespace App\Filament\Admin\Resources\PriceTariffResource\Pages;

use App\Filament\Admin\Resources\PriceTariffResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPriceTariff extends EditRecord
{
    protected static string $resource = PriceTariffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
