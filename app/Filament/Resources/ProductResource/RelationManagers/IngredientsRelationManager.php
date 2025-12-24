<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Ingredient; 

class IngredientsRelationManager extends RelationManager
{
    protected static string $relationship = 'ingredients';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->label('Cantidad'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Insumo')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->suffix(fn ($record) => ' ' . $record->unit),

                Tables\Columns\TextColumn::make('unit')
                    ->label('Unidad Original')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    // AQUÍ ESTABA EL ERROR, ELIMINÉ ->preload() DE ESTA LÍNEA
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        Forms\Components\Select::make('recordId')
                            ->label('Insumo')
                            ->options(Ingredient::query()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(), // Lo moví aquí adentro, que es donde funciona
                        
                        Forms\Components\TextInput::make('quantity')
                            ->label('Cantidad a descontar')
                            ->required()
                            ->numeric(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}