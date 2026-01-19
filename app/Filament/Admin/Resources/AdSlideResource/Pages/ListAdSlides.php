<?php

namespace App\Filament\Admin\Resources\AdSlideResource\Pages;

use App\Filament\Admin\Resources\AdSlideResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdSlides extends ListRecords
{
    protected static string $resource = AdSlideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
