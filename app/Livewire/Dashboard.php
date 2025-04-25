<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // total completed orders of user
        $user = auth()->user();
        $totalOrders = $user->orders()->where('status', 'completed')->count();

        // total completed orders of user for this month
        $totalOrdersThisMonth = $user->orders()
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->count();

        // total completed orders of user for this year
        $totalOrdersThisYear = $user->orders()
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->count();

        // the most popular products in shop
        $popularProducts = \App\Models\Product::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get();


        return view('livewire.dashboard',
            [
                'totalOrders' => $totalOrders,
                'totalOrdersThisMonth' => $totalOrdersThisMonth,
                'totalOrdersThisYear' => $totalOrdersThisYear,
                'popularProducts' => $popularProducts,
            ]
        )->layout('layouts.app');
    }
}
