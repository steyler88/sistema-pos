<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class TopProductsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Productos Más Vendidos (Últimos 7 días)';

    public function table(Table $table): Table
    {
        // Obtener los productos más vendidos en los últimos 7 días
        $topProducts = OrderItem::query()
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(quantity * unit_price) as total_sales')
            ->whereHas('order', function (Builder $query) {
                $query->where('status', 'completed')
                    ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        $productIds = $topProducts->pluck('product_id');
        $salesData = $topProducts->keyBy('product_id');

        // Si no hay productos vendidos, retornar query vacío
        $query = $productIds->isEmpty() 
            ? Product::query()->whereRaw('1 = 0')
            : Product::query()
                ->whereIn('id', $productIds)
                ->orderByRaw("FIELD(id, " . $productIds->implode(',') . ")");

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad Vendida')
                    ->getStateUsing(function (Product $record) use ($salesData) {
                        return $salesData[$record->id]->total_quantity ?? 0;
                    })
                    ->sortable()
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('total_sales')
                    ->label('Ventas Totales')
                    ->getStateUsing(function (Product $record) use ($salesData) {
                        return 'S/ ' . number_format($salesData[$record->id]->total_sales ?? 0, 2);
                    })
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio Unitario')
                    ->money('PEN')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}

