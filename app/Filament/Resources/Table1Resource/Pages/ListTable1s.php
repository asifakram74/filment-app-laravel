<?php

namespace App\Filament\Resources\Table1Resource\Pages;

use App\Filament\Resources\Table1Resource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTable1s extends ListRecords
{
    protected static string $resource = Table1Resource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
