<?php

namespace App\Filament\Resources\RifaResource\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Resources\RifaResource\Pages\ViewRifa;
use App\Models\Order;
use App\Models\Rifa;
use App\Services\RifaService;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    public Rifa $rifa;

    protected function getStats(): array
    {
        $rifaService = app(RifaService::class);

        $paid = $rifaService->countOrdersByStatus($this->rifa, OrderStatus::PAID);
        $reserved = $rifaService->countOrdersByStatus($this->rifa, OrderStatus::RESERVED);
        $diff = $this->rifa->total_numbers_available - ($paid->total + $reserved->total);

        return [
            Stat::make('Total de bilhetes pagos', $this->formatNumber($paid->total)),
            Stat::make('Total de bilhetes pendentes', $this->formatNumber($reserved->total)),
            Stat::make('Total de bilhetes disponíveis', $this->formatNumber($diff)),
        ];
    }

    /**
     * Formata os números de acordo com o tipo
     *
     * @param int|float $value
     *
     * @return string
     */
    private function formatNumber($value)
    {
        return Number::format(
            number: $value ?? 0,
            locale: config('app.locale'),
        );
    }
}
