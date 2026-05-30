<?php

namespace App\Filament\Resources\DailyReflectionResource\Pages;

use App\Filament\Resources\DailyReflectionResource;
use App\Services\DailyReflectionService;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyReflection extends CreateRecord
{
    protected static string $resource = DailyReflectionResource::class;

    protected function handleRecordCreation(array $data): \App\Models\DailyReflection
    {
        return app(DailyReflectionService::class)->create($data, auth()->user());
    }
}
