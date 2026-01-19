<?php

namespace App\Filament\Admin\Resources\PriceTariffResource\Pages;

use App\Filament\Admin\Resources\PriceTariffResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPriceTariffs extends ListRecords
{
    protected static string $resource = PriceTariffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
