<?php

namespace App\Filament\Resources\CompletedRejectedLoanResource\Pages;

use App\Filament\Resources\CompletedRejectedLoanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompletedRejectedLoans extends ListRecords
{
    protected static string $resource = CompletedRejectedLoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
