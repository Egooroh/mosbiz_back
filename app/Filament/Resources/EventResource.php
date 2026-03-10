<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Мероприятие';
    protected static ?string $pluralModelLabel = 'Мероприятия';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Название мероприятия')
                    ->required()
                    ->maxLength(255),

                DatePicker::make('start_date')
                    ->label('Дата начала')
                    ->required(),
                    
                DatePicker::make('end_date')
                    ->label('Дата окончания'),
                    
                Select::make('category')
                    ->label('Категория')
                    ->options([
                        'IT' => 'Информационные технологии (IT)',
                        'Мода' => 'Индустрия моды',
                        'Инновации' => 'Инновации и технологии',
                        'Экспорт' => 'Экспорт',
                        'Бизнес' => 'Развитие бизнеса',
                    ])
                    ->searchable()
                    ->preload(),
                    
                FileUpload::make('image_path')
                    ->label('Обложка мероприятия')
                    ->image()
                    ->directory('events'),

                Textarea::make('description')
                    ->label('Описание')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Обложка'),
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label('Начало')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Категория'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
