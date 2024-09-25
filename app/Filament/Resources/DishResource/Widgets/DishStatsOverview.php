<?php

namespace App\Filament\Resources\DishResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Dish;
class DishStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Meals' , Dish::all()->count()),
        ];
    }
}
