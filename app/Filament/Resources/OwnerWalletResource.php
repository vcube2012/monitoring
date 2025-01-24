<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OwnerWalletResource\Pages;
use App\Models\OwnerWallet;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OwnerWalletResource extends Resource
{
    protected static ?string $model = OwnerWallet::class;

    protected static ?string $slug = 'owner-wallets';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                Actions::make([
                    Actions\Action::make('123')->form([
                        TextInput::make('owner_name')
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('wallet'),
                TextColumn::make('secret_key')
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListOwnerWallets::route('/'),
            'create' => Pages\CreateOwnerWallet::route('/create'),
            'edit' => Pages\EditOwnerWallet::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
