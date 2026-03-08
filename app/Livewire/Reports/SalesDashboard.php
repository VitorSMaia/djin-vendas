<?php

namespace App\Livewire\Reports;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SalesDashboard extends Component
{
    public function render()
    {
        $today = now()->startOfDay();
        $startOfWeek = now()->startOfWeek();

        $totalToday = Sale::query()
            ->where('sale_date', '>=', $today)
            ->sum('total');

        $totalWeek = Sale::query()
            ->where('sale_date', '>=', $startOfWeek)
            ->sum('total');

        $lowStockItems = Product::query()
            ->where('stock', '<', 5)
            ->orderBy('stock')
            ->get();

        $salesByProduct = SaleItem::query()
            ->with('product')
            ->select('product_id', DB::raw('SUM(total_price) as total'), DB::raw('SUM(quantity) as qty'))
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->get();

        return view('livewire.reports.sales-dashboard', [
            'totalToday' => $totalToday,
            'totalWeek' => $totalWeek,
            'lowStockItems' => $lowStockItems,
            'salesByProduct' => $salesByProduct,
        ])->layout('layouts.app');
    }
}
