<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AdSlideResource\Pages;
use App\Filament\Admin\Resources\AdSlideResource\RelationManagers;
use App\Models\AdSlide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdSlideResource extends Resource
{
    protected static ?string $model = AdSlide::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Reklam Slayları';

    protected static ?string $modelLabel = 'Reklam Slaytı';

    protected static ?string $pluralModelLabel = 'Reklam Slayları';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Medya Tipi')
                    ->description('Slayt tipini seçin: Metin, Görsel veya Video')
                    ->schema([
                        Forms\Components\Select::make('media_type')
                            ->label('Slayt Tipi')
                            ->options([
                                'text' => '📝 Metin (Başlık + Telefon)',
                                'image' => '🖼️ Görsel',
                                'video' => '🎬 Video',
                            ])
                            ->default('text')
                            ->required()
                            ->live()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Metin İçeriği')
                    ->description('Metin tipinde slayt için içerik')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Örn: Bu Alana Reklam Vermek İçin Arayınız'),
                        Forms\Components\Textarea::make('subtitle')
                            ->label('Alt Başlık')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Telefon Numarası')
                            ->tel()
                            ->regex('/^[+0-9\s\(\)]*$/')
                            ->placeholder('0212 909 56 76'),
                        Forms\Components\TextInput::make('icon')
                            ->label('İkon (Emoji)')
                            ->default('📢')
                            ->maxLength(10),
                    ])
                    ->columns(2)
                    ->visible(fn (Forms\Get $get): bool => $get('media_type') === 'text'),

                Forms\Components\Section::make('Görsel Yükleme')
                    ->description('Görüntülenecek görseli yükleyin')
                    ->schema([
                        Forms\Components\FileUpload::make('media_path')
                            ->label('Görsel')
                            ->image()
                            ->disk('public')
                            ->directory('ad-slides')
                            ->visibility('public')
                            ->imagePreviewHeight('200')
                            ->required()
                            ->helperText('Önerilen boyut: 1920x576 piksel (üst alan için)'),
                    ])
                    ->visible(fn (Forms\Get $get): bool => $get('media_type') === 'image'),

                Forms\Components\Section::make('Video Yükleme')
                    ->description('Görüntülenecek videoyu yükleyin')
                    ->schema([
                        Forms\Components\FileUpload::make('media_path')
                            ->label('Video')
                            ->disk('public')
                            ->directory('ad-slides')
                            ->visibility('public')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                            ->maxSize(102400) // 100MB
                            ->required()
                            ->helperText('MP4, WebM veya OGG formatında video yükleyin'),
                    ])
                    ->visible(fn (Forms\Get $get): bool => $get('media_type') === 'video'),

                Forms\Components\Section::make('Görünüm')
                    ->schema([
                        Forms\Components\ColorPicker::make('background_color')
                            ->label('Arka Plan Rengi')
                            ->default('#0055ff')
                            ->required()
                            ->helperText('Metin tipi slaytlar için arka plan rengi'),
                        Forms\Components\TextInput::make('position')
                            ->label('Sıra')
                            ->numeric()
                            ->default(0)
                            ->helperText('Reklam slaytlarının gösterim sırası')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Ayarlar')
                    ->schema([
                        Forms\Components\TextInput::make('duration')
                            ->label('Süre (saniye)')
                            ->numeric()
                            ->default(15)
                            ->required()
                            ->minValue(5)
                            ->maxValue(60),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->limit(40),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Telefon')
                    ->searchable(),
                Tables\Columns\ColorColumn::make('background_color')
                    ->label('Renk'),
                Tables\Columns\TextColumn::make('position')
                    ->label('Sıra')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Süre')
                    ->suffix(' sn')
                    ->numeric(),
                Tables\Columns\IconColumn::make('is_active')
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
            ->defaultSort('position', 'asc');
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
            'index' => Pages\ListAdSlides::route('/'),
            'create' => Pages\CreateAdSlide::route('/create'),
            'edit' => Pages\EditAdSlide::route('/{record}/edit'),
        ];
    }
}
