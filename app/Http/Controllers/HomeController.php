<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function track(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string',
            'customer_number' => 'required|string',
        ]);

        $order = Order::where('invoice_number', $request->invoice_number)
            ->where('customer_number', $request->customer_number)
            ->first();

        if (!$order) {
            return back()->with('error', 'No se encontró el pedido. Verifique los datos ingresados.');
        }

        return view('track-result', compact('order'));
    }
}