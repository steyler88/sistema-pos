<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    // Esto hace que el widget se actualice cada 15 segundos si dejas la pantalla abierta
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // 1. Calcular Ventas de ESTE MES (Solo las pagadas/completadas)
        $ventasMes = Order::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        // 2. Calcular Gastos de ESTE MES
        $gastosMes = Expense::whereMonth('expense_date', Carbon::now()->month)
            ->whereYear('expense_date', Carbon::now()->year)
            ->sum('amount');

        // 3. Calcular la Ganancia (Ventas - Gastos)
        $balance = $ventasMes - $gastosMes;

        return [
            // TARJETA 1: VENTAS
            Stat::make('Ventas del Mes', 'S/ ' . number_format($ventasMes, 2))
                ->description('Ingresos confirmados')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'), // Verde

            // TARJETA 2: GASTOS
            Stat::make('Gastos del Mes', 'S/ ' . number_format($gastosMes, 2))
                ->description('Compras y Pagos')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'), // Rojo

            // TARJETA 3: BALANCE FINAL
            Stat::make('Ganancia Neta', 'S/ ' . number_format($balance, 2))
                ->description($balance >= 0 ? '¡Vas ganando dinero!' : 'Cuidado, estás en pérdida')
                ->descriptionIcon($balance >= 0 ? 'heroicon-m-face-smile' : 'heroicon-m-face-frown')
                ->color($balance >= 0 ? 'primary' : 'danger'),
        ];
    }
}