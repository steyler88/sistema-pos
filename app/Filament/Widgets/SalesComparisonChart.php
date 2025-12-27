<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class SalesComparisonChart extends ChartWidget
{
    protected static ?string $heading = 'Ventas de los Últimos 7 Días';
    
    protected static ?int $sort = 2;
    
    protected static ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $salesData = [];
        $labels = [];

        // Generar datos de los últimos 7 días
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            $sales = Order::where('status', 'completed')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_price');
            
            $salesData[] = (float) $sales;
            $labels[] = $date->format('d/m');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ventas (S/)',
                    'data' => $salesData,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "S/ " + value.toFixed(2); }',
                    ],
                ],
            ],
        ];
    }
}

