<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class OrdersStatusWidget extends ChartWidget
{
    protected static ?string $heading = 'Órdenes por Estado (Hoy)';
    
    protected static ?int $sort = 3;
    
    protected static ?string $pollingInterval = '15s';

    protected function getData(): array
    {
        $today = Carbon::today();

        $pending = Order::where('status', 'pending')
            ->whereDate('created_at', $today)
            ->count();

        $completed = Order::where('status', 'completed')
            ->whereDate('created_at', $today)
            ->count();

        $cancelled = Order::where('status', 'cancelled')
            ->whereDate('created_at', $today)
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Órdenes',
                    'data' => [$pending, $completed, $cancelled],
                    'backgroundColor' => [
                        'rgb(255, 205, 86)',  // Amarillo para pendientes
                        'rgb(75, 192, 192)',  // Verde para completadas
                        'rgb(255, 99, 132)',  // Rojo para canceladas
                    ],
                ],
            ],
            'labels' => ['Pendientes', 'Completadas', 'Canceladas'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

