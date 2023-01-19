<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpense extends EditRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['expense_amt'] = $data['expense_amt'] / 100;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['expense_amt'] = $data['expense_amt'] * 100;

        return $data;
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
