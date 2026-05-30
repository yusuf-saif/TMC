<?php

namespace App\Filament\Resources\DailyReflectionResource\Pages;

use App\Filament\Resources\DailyReflectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailyReflections extends ListRecords
{
    protected static string $resource = DailyReflectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
