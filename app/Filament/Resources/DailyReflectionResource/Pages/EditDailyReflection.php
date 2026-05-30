<?php

namespace App\Filament\Resources\DailyReflectionResource\Pages;

use App\Filament\Resources\DailyReflectionResource;
use App\Services\DailyReflectionService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDailyReflection extends EditRecord
{
    protected static string $resource = DailyReflectionResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // we will let service set updated_by; just return data
        return $data;
    }

    protected function handleRecordUpdate($record, array $data): \App\Models\DailyReflection
    {
        return app(DailyReflectionService::class)->update($record, $data, auth()->user());
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
