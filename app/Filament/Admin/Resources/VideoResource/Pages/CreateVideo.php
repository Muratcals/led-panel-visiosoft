<?php

namespace App\Filament\Admin\Resources\VideoResource\Pages;

use App\Filament\Admin\Resources\VideoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVideo extends CreateRecord
{
    protected static string $resource = VideoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // file_path null ise boş string yap
        if (!isset($data['file_path']) || $data['file_path'] === null) {
            $data['file_path'] = '';
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
