<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PriceTariffResource\Pages;
use App\Filament\Admin\Resources\PriceTariffResource\RelationManagers;
use App\Models\PriceTariff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PriceTariffResource extends Resource
{
    protected static ?string $model = PriceTariff::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Fiyat Tarifeleri';

    protected static ?string $modelLabel = 'Fiyat Tarifesi';

    protected static ?string $pluralModelLabel = 'Fiyat Tarifeleri';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Tarife Bilgileri')
                    ->schema([
                        Forms\Components\Select::make('time_range')
                            ->label('Zaman Aralığı')
                            ->options([
                                '0-1 Saat' => '0-1 Saat',
                                '1-2 Saat' => '1-2 Saat',
                                '2-3 Saat' => '2-3 Saat',
                                '3-4 Saat' => '3-4 Saat',
                                '4-5 Saat' => '4-5 Saat',
                                '5-6 Saat' => '5-6 Saat',
                                '6-7 Saat' => '6-7 Saat',
                                '7-8 Saat' => '7-8 Saat',
                                '8-12 Saat' => '8-12 Saat',
                                '12-24 Saat' => '12-24 Saat',
                                'TAM GÜN' => 'TAM GÜN',
                                'HAFTALIK' => 'HAFTALIK',
                                'AYLIK' => 'AYLIK',
                            ])
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'Bu zaman aralığı için zaten bir tarife mevcut.',
                            ]),
                        Forms\Components\TextInput::make('price')
                            ->label('Ücret')
                            ->numeric()
                            ->prefix('₺')
                            ->default(0)
                            ->required(),
                        Forms\Components\TextInput::make('order')
                            ->label('Sıra')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ]),
                
                Forms\Components\Section::make('Özellikler')
                    ->schema([
                        Forms\Components\Toggle::make('is_free')
                            ->label('Ücretsiz')
                            ->default(false),
                        Forms\Components\Toggle::make('is_highlighted')
                            ->label('Vurgulu')
                            ->helperText('Yeşil renkle vurgulanır')
                            ->default(false),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_free')
                    ->label('Ücretsiz'),
                Tables\Filters\TernaryFilter::make('is_highlighted')
                    ->label('Vurgulu'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPriceTariffs::route('/'),
            'create' => Pages\CreatePriceTariff::route('/create'),
            'edit' => Pages\EditPriceTariff::route('/{record}/edit'),
        ];
    }
}
