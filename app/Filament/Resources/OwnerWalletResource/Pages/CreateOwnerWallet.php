<?php

namespace App\Filament\Resources\OwnerWalletResource\Pages;

use App\Filament\Resources\OwnerWalletResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOwnerWallet extends CreateRecord
{
    protected static string $resource = OwnerWalletResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
