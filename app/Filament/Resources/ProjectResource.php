<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers\RequestsRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\TransactionsRelationManager;
use App\Models\Project;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $label = 'Проект';
    protected static ?string $pluralLabel = 'Проект';

    protected static ?string $slug = 'projects';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name')->label('Назва проекта:'),
                    TextInput::make('wallet')->label('Кошелек:'),
                    TextInput::make('webhook')->label('Вебхук:'),
                    TextInput::make('token')->label('токен:'),
                    Select::make('method')->label('Метод:')->options([
                        'post' => 'post',
                        'get' => 'get',
                    ])->default('get'),
                    Select::make('network')->options([
                        'sol' => 'sol',
                        'gmg' => 'gmg',
                        'ton' => 'ton',
                    ])->default('sol'),

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('user_id'),
                TextColumn::make('wallet'),
                TextColumn::make('webhook'),
                TextColumn::make('method'),
                TextColumn::make('network'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make()
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'view' => Pages\Project::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function getRelations(): array
    {
        return [
            TransactionsRelationManager::class,
            RequestsRelationManager::class,
        ];
    }

}
