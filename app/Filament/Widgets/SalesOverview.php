<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class SalesOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    
    protected function getStats(): array
    {
        // Ventas de HOY
        $ventasHoy = Order::where('status', 'completed')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_price');

        // Ventas de AYER
        $ventasAyer = Order::where('status', 'completed')
            ->whereDate('created_at', Carbon::yesterday())
            ->sum('total_price');

        // Ventas de los ÚLTIMOS 7 DÍAS
        $ventasSemana = Order::where('status', 'completed')
            ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->sum('total_price');

        // Calcular cambio porcentual comparado con ayer
        $cambioAyer = $ventasAyer > 0 
            ? (($ventasHoy - $ventasAyer) / $ventasAyer) * 100 
            : 0;

        // Número de órdenes HOY
        $ordenesHoy = Order::whereDate('created_at', Carbon::today())->count();

        // Ticket promedio HOY
        $ticketPromedio = $ordenesHoy > 0 ? $ventasHoy / $ordenesHoy : 0;

        return [
            // Ventas de HOY
            Stat::make('Ventas de Hoy', 'S/ ' . number_format($ventasHoy, 2))
                ->description(
                    $cambioAyer >= 0 
                        ? '+' . number_format($cambioAyer, 1) . '% vs ayer'
                        : number_format($cambioAyer, 1) . '% vs ayer'
                )
                ->descriptionIcon($cambioAyer >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($cambioAyer >= 0 ? 'success' : 'danger')
                ->chart([
                    $ventasAyer, 
                    $ventasHoy
                ]),

            // Ventas de AYER
            Stat::make('Ventas de Ayer', 'S/ ' . number_format($ventasAyer, 2))
                ->description('Día anterior')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            // Ventas de la SEMANA
            Stat::make('Ventas (7 días)', 'S/ ' . number_format($ventasSemana, 2))
                ->description('Últimos 7 días')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),

            // Ticket Promedio HOY
            Stat::make('Ticket Promedio Hoy', 'S/ ' . number_format($ticketPromedio, 2))
                ->description($ordenesHoy . ' órdenes hoy')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),
        ];
    }
}

