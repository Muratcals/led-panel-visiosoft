<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DeviceResource\Pages;
use App\Filament\Admin\Resources\DeviceResource\RelationManagers;
use App\Models\Device;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static ?string $navigationIcon = 'heroicon-o-tv';

    protected static ?string $navigationLabel = 'Cihazlar';

    protected static ?string $modelLabel = 'Cihaz';

    protected static ?string $pluralModelLabel = 'Cihazlar';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Cihaz Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Cihaz Adı')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('örn: AVM Giriş Paneli')
                            ->helperText('Cihazı tanımlayıcı bir ad verin'),
                        
                        Forms\Components\TextInput::make('device_code')
                            ->label('Cihaz Kodu')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('örn: LED-001')
                            ->helperText('Benzersiz cihaz kodu (otomatik oluşturulacak)')
                            ->default(fn() => 'LED-' . strtoupper(substr(uniqid(), -6))),
                        
                        Forms\Components\TextInput::make('location')
                            ->label('Konum')
                            ->maxLength(255)
                            ->placeholder('örn: İstanbul - Kadıköy AVM Giriş')
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('ip_address')
                            ->label('IP Adresi')
                            ->maxLength(255)
                            ->placeholder('192.168.1.100')
                            ->helperText('Cihazın ağdaki IP adresi'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Durum ve Ayarlar')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'active' => '🟢 Aktif',
                                'inactive' => '🔴 Pasif',
                                'maintenance' => '🟡 Bakımda',
                            ])
                            ->default('active')
                            ->required(),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Notlar')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Cihaz hakkında notlar...'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device_code')
                    ->label('Cihaz Kodu')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Cihaz Adı')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('location')
                    ->label('Konum')
                    ->searchable()
                    ->icon('heroicon-o-map-pin')
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'maintenance' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'inactive' => 'Pasif',
                        'maintenance' => 'Bakımda',
                    }),
                
                Tables\Columns\TextColumn::make('videos_count')
                    ->label('Video')
                    ->counts('videos')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('adSlides_count')
                    ->label('Reklam')
                    ->counts('adSlides')
                    ->badge()
                    ->color('warning'),
                
                Tables\Columns\TextColumn::make('last_sync_at')
                    ->label('Son Senkronizasyon')
                    ->dateTime('d/m/Y H:i')
                    ->since()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Pasif',
                        'maintenance' => 'Bakımda',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('sync')
                    ->label('Senkronize Et')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->action(function (Device $record) {
                        $record->update(['last_sync_at' => now()]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Cihazı Senkronize Et')
                    ->modalDescription('Bu işlem cihaza güncel içerikleri gönderecektir.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\VideosRelationManager::class,
            RelationManagers\AdSlidesRelationManager::class,
            RelationManagers\PriceTariffsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDevices::route('/'),
            'create' => Pages\CreateDevice::route('/create'),
            'edit' => Pages\EditDevice::route('/{record}/edit'),
        ];
    }
}
