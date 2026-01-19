<?php

namespace App\Filament\Admin\Resources\AdSlideResource\Pages;

use App\Filament\Admin\Resources\AdSlideResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdSlide extends EditRecord
{
    protected static string $resource = AdSlideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
