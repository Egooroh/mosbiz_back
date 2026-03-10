<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamMemberResource\Pages;
use App\Models\TeamMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Select;

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Департамент';
    protected static ?string $pluralModelLabel = 'Департамент';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('name')
                ->label('ФИО')
                ->required()
                ->maxLength(255),
            Select::make('position')
                ->label('Направление')
                ->options([
                    'Малый и средний бизнес' => 'Малый и средний бизнес',
                    'Инновации' => 'Инновации',
                    'Продвижение' => 'Продвижение',
                    'Развитие команды' => 'Развитие команды',
                    'Экспорт' => 'Экспорт',
                ])
                ->required(),
            Toggle::make('is_head')
                ->label('Руководитель?')
                ->default(false),
            FileUpload::make('photo_path')
                ->label('Фотография')
                ->image()
                ->directory('team'),
            Textarea::make('description')
                ->label('Описание')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo_path')->label('Фото')->circular(),
                TextColumn::make('name')->label('ФИО')->searchable(),
                TextColumn::make('position')->label('Должность'),
                IconColumn::make('is_head')
                    ->label('Главный')
                    ->boolean(),
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
            'index' => Pages\ListTeamMembers::route('/'),
            'create' => Pages\CreateTeamMember::route('/create'),
            'edit' => Pages\EditTeamMember::route('/{record}/edit'),
        ];
    }
}
