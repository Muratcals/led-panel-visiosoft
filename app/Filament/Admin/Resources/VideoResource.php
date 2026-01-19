<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VideoResource\Pages;
use App\Filament\Admin\Resources\VideoResource\RelationManagers;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    protected static ?string $navigationLabel = 'Videolar';

    protected static ?string $modelLabel = 'Video';

    protected static ?string $pluralModelLabel = 'Videolar';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Video Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Video Dosyası')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Video Dosyası')
                            ->disk('public')
                            ->directory('videos')
                            ->visibility('public')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime', 'video/x-msvideo', 'video/avi'])
                            ->maxSize(102400) // 100MB (PHP ve Livewire limiti ile uyumlu)
                            ->downloadable()
                            ->openable()
                            ->previewable(false)
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadProgressIndicatorPosition('left')
                            ->uploadingMessage('Video yükleniyor...')
                            ->helperText('📹 MP4, WebM veya OGG formatında video yükleyin. Çözünürlük: 1280x720 veya 1920x1080 önerilir.')
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Ayarlar')
                    ->schema([
                        Forms\Components\Select::make('position')
                            ->label('Konum')
                            ->options([
                                'top' => 'Üst Alan',
                                'bottom' => 'Alt Slider',
                            ])
                            ->default('top')
                            ->required(),
                        Forms\Components\TextInput::make('duration')
                            ->label('Süre (saniye)')
                            ->numeric()
                            ->default(15)
                            ->required()
                            ->minValue(1)
                            ->maxValue(300),
                        Forms\Components\TextInput::make('order')
                            ->label('Sıra')
                            ->numeric()
                            ->default(0)
                            ->required(),
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
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Süre')
                    ->suffix(' sn')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Sıra')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('position')
                    ->label('Konum')
                    ->options([
                        'top' => 'Üst Alan',
                        'bottom' => 'Alt Slider',
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
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
