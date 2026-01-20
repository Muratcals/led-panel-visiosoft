<?php

namespace App\Filament\Admin\Resources\DeviceResource\RelationManagers;

use App\Models\PriceTariff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PriceTariffsRelationManager extends RelationManager
{
    protected static string $relationship = 'priceTariffs';

    protected static ?string $title = 'Fiyat Tarifeleri';

    protected static ?string $modelLabel = 'Fiyat Tarifesi';

    protected static ?string $pluralModelLabel = 'Fiyat Tarifeleri';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('time_range')
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('Sıra')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time_range')
                    ->label('Zaman Aralığı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Ücret')
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_free')
                    ->label('Ücretsiz')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_highlighted')
                    ->label('Vurgulu')
                    ->boolean(),
                Tables\Columns\IconColumn::make('pivot.is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_free')
                    ->label('Ücretsiz'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Tarife Ekle')
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->where('price_tariffs.is_active', true)->select('price_tariffs.*'))
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif'),
                    ]),
                Tables\Actions\DetachAction::make()
                    ->label('Kaldır'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc');
    }
}
