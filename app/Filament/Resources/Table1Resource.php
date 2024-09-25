<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Table1Resource\Pages;
use App\Filament\Resources\Table1Resource\RelationManagers;
use App\Models\Table as TableModel;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Table1Resource extends Resource
{
    protected static ?string $model = TableModel::class;
    protected static ?string $pluralModelLabel = 'Table 1';
    protected static ?string $navigationGroup = 'menu';
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTable1s::route('/'),
            'create' => Pages\CreateTable1::route('/create'),
            'edit' => Pages\EditTable1::route('/{record}/edit'),
        ];
    }
}
