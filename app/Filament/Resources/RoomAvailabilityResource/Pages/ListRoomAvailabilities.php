<?php

namespace App\Filament\Resources\RoomAvailabilityResource\Pages;

use App\Filament\Resources\RoomAvailabilityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoomAvailabilities extends ListRecords
{
    protected static string $resource = RoomAvailabilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
