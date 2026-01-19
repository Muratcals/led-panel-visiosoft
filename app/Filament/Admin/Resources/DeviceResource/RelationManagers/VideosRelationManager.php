<?php

namespace App\Filament\Admin\Resources\DeviceResource\RelationManagers;

use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VideosRelationManager extends RelationManager
{
    protected static string $relationship = 'videos';

    protected static ?string $title = 'Videolar';

    protected static ?string $modelLabel = 'Video';

    protected static ?string $pluralModelLabel = 'Videolar';

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
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->label('Konum')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'top' => 'success',
                        'bottom' => 'info',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'top' => 'Üst Alan',
                        'bottom' => 'Alt Slider',
                    }),
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
                Tables\Filters\SelectFilter::make('position')
                    ->label('Konum')
                    ->options([
                        'top' => 'Üst Alan',
                        'bottom' => 'Alt Slider',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Video Ekle')
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->where('is_active', true))
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
