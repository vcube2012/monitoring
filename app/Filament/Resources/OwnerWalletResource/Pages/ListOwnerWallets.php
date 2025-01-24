<?php

namespace App\Filament\Resources\OwnerWalletResource\Pages;

use App\Filament\Resources\OwnerWalletResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOwnerWallets extends ListRecords
{
    protected static string $resource = OwnerWalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
