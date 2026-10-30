<?php

namespace App\Filament\Resources\CompletedRejectedLoanResource\Pages;

use App\Filament\Resources\CompletedRejectedLoanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompletedRejectedLoan extends EditRecord
{
    protected static string $resource = CompletedRejectedLoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
