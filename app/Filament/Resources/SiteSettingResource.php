<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Filament\Resources\SiteSettingResource\RelationManagers;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Настройки сайта';
    protected static ?string $pluralModelLabel = 'Настройки сайта';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Шапка сайта')
                    ->schema([
                        FileUpload::make('header_logo')->label('Логотип')->image()->directory('settings'),
                        TextInput::make('header_button_text')->label('Текст кнопки')->default('Написать нам'),
                        TextInput::make('header_phone')->label('Номер телефона'),

                        Repeater::make('social_links')
                            ->label('Социальные сети')
                            ->schema([
                                FileUpload::make('icon')->label('Иконка (SVG/PNG)')->image()->directory('setting/icons'),
                                TextInput::make('url')->label('Ссылка')->url(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Добавить соцсеть'),
                    ]),

                Section::make('Главный экран (Hero)')
                    ->schema([
                        FileUpload::make('hero_image')->label('Фоновое изображение')->image()->directory('settings'),
                        TextInput::make('hero_title')->label('Заголовок'),
                        TextInput::make('hero_subtitle')->label('Подзаголовок'),
                    ]),

                Section::make('Блоки статистики')
                    ->schema([
                        Repeater::make('statistics')
                            ->label('Карточки статистики')
                            ->schema([
                                TextInput::make('value')->label('Цифра (например: 125 млрд ₽)')->required(),
                                TextInput::make('description')->label('Описание например: Направлено на поддержку')->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Добавить статистику'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('hero_title')->label('Главный заголовок'),
                TextColumn::make('header_phone')->label('Телефон'),
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
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
