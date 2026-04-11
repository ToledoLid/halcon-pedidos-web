<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $pendingOrders = Order::whereIn('status', ['ordered', 'in_process'])->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $recentOrders = Order::with('creator')->latest()->take(5)->get();

        return view('dashboard', compact('totalOrders', 'totalUsers', 'pendingOrders', 'deliveredOrders', 'recentOrders'));
    }
}