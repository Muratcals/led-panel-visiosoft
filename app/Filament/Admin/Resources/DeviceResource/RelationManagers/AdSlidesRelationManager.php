<?php

namespace App\Filament\Admin\Resources\DeviceResource\RelationManagers;

use App\Models\AdSlide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AdSlidesRelationManager extends RelationManager
{
    protected static string $relationship = 'adSlides';

    protected static ?string $title = 'Reklam Slaytları';

    protected static ?string $modelLabel = 'Reklam Slaytı';

    protected static ?string $pluralModelLabel = 'Reklam Slaytları';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order')
                    ->label('Sıra')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('media_type')
                    ->label('Tip')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'text' => 'primary',
                        'image' => 'success',
                        'video' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'text' => '📝 Metin',
                        'image' => '🖼️ Görsel',
                        'video' => '🎬 Video',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Telefon'),
                Tables\Columns\ColorColumn::make('background_color')
                    ->label('Renk'),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Süre')
                    ->suffix(' sn'),
                Tables\Columns\TextColumn::make('pivot.order')
                    ->label('Sıra')
                    ->sortable(),
                Tables\Columns\IconColumn::make('pivot.is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('media_type')
                    ->label('Tip')
                    ->options([
                        'text' => 'Metin',
                        'image' => 'Görsel',
                        'video' => 'Video',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Slayt Ekle')
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query
                        ->where('ad_slides.is_active', true)
                        ->whereDoesntHave('devices', fn ($q) => $q->where('devices.id', $this->getOwnerRecord()->id))
                        ->orderBy('ad_slides.position')
                    )
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('order')
                            ->label('Sıra')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('order')
                            ->label('Sıra')
                            ->numeric()
                            ->required(),
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
            ->defaultSort('pivot.order', 'asc');
    }
}
