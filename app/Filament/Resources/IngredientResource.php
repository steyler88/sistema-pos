<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngredientResource\Pages;
use App\Filament\Resources\IngredientResource\RelationManagers;
use App\Models\Ingredient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IngredientResource extends Resource
{
    protected static ?string $model = Ingredient::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name')
                ->required()
                ->label('Nombre del Insumo'),

            Forms\Components\Select::make('unit')
                ->options([
                    'kg' => 'Kilogramos (kg)',
                    'g' => 'Gramos (g)',
                    'lt' => 'Litros (lt)',
                    'und' => 'Unidades (und)',
                ])
                ->required()
                ->label('Unidad de Medida'),

            Forms\Components\TextInput::make('cost')
                ->numeric()
                ->prefix('S/')
                ->label('Costo por Unidad'),

            Forms\Components\TextInput::make('stock')
                ->numeric()
                ->label('Stock Actual'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // AQUÍ EMPIEZAN LAS COLUMNAS
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Insumo'),

                Tables\Columns\TextColumn::make('stock')
                    ->sortable()
                    ->label('Stock')
                    ->suffix(fn ($record) => ' ' . $record->unit), 

                Tables\Columns\TextColumn::make('cost')
                    ->money('PEN')
                    ->label('Costo Unit.'),
                // AQUÍ TERMINAN LAS COLUMNAS
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListIngredients::route('/'),
            'create' => Pages\CreateIngredient::route('/create'),
            'edit' => Pages\EditIngredient::route('/{record}/edit'),
        ];
    }
}
