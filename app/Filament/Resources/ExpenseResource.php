<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes'; // Icono de billetes
    protected static ?string $navigationLabel = 'Gastos / Compras';
    protected static ?int $navigationSort = 3; // Para que salga debajo de Ventas

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detalle del Gasto')
                    ->schema([
                        Forms\Components\TextInput::make('description')
                            ->label('Descripción')
                            ->placeholder('Ej: Recibo de Luz del Sur')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('amount')
                            ->label('Monto')
                            ->numeric()
                            ->prefix('S/')
                            ->required(),

                        Forms\Components\Select::make('category')
                            ->label('Categoría')
                            ->options([
                                'supplies' => 'Compra de Insumos',
                                'services' => 'Servicios (Luz/Agua/Net)',
                                'salaries' => 'Sueldos / Personal',
                                'rent' => 'Alquiler',
                                'marketing' => 'Publicidad / Ads',
                                'maintenance' => 'Mantenimiento / Reparaciones',
                                'other' => 'Otros',
                            ])
                            ->required(),

                        Forms\Components\DatePicker::make('expense_date')
                            ->label('Fecha del Gasto')
                            ->default(now()) // Por defecto pone la fecha de hoy
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Categoría')
                    ->badge() // Lo pone como etiqueta de color
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'supplies' => 'Insumos',
                        'services' => 'Servicios',
                        'salaries' => 'Sueldos',
                        'rent' => 'Alquiler',
                        'marketing' => 'Publicidad',
                        'maintenance' => 'Mantenimiento',
                        'other' => 'Otros',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'supplies' => 'info',
                        'services' => 'warning',
                        'salaries' => 'success',
                        'rent' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->money('PEN')
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make() // ¡MAGIA! Suma el total al pie de la tabla
                            ->money('PEN')
                            ->label('Total Gastado'),
                    ]),

                Tables\Columns\TextColumn::make('expense_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('expense_date', 'desc')
            ->filters([
                // Filtro para buscar gastos por rango de fechas
                Tables\Filters\Filter::make('expense_date')
                    ->form([
                        Forms\Components\DatePicker::make('desde'),
                        Forms\Components\DatePicker::make('hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn ($query, $date) => $query->whereDate('expense_date', '>=', $date))
                            ->when($data['hasta'], fn ($query, $date) => $query->whereDate('expense_date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}