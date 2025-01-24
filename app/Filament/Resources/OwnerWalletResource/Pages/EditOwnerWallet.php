<?php

namespace App\Filament\Resources\OwnerWalletResource\Pages;

use App\Filament\Resources\OwnerWalletResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOwnerWallet extends EditRecord
{
    protected static string $resource = OwnerWalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
