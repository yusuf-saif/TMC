<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use App\Services\AnnouncementService;
use Filament\Resources\Pages\CreateRecord;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function handleRecordCreation(array $data): \App\Models\Announcement
    {
        return app(AnnouncementService::class)->create($data, auth()->user());
    }
}
