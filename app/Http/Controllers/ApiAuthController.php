<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ApiAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');  // ← CAMBIADO: antes era 'auth.api-login'
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $response = Http::post('http://localhost:3000/login', [
                'email' => $request->email,
                'password' => $request->password
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Session::put('api_token', $data['token']);
                Session::put('api_user', $data['user']);
                return redirect()->route('dashboard');
            }

            return back()->withErrors(['email' => 'Credenciales inválidas']);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Error de conexión con el servidor']);
        }
    }

    public function dashboard()
    {
        $token = Session::get('api_token');
        if (!$token) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withToken($token)->get('http://localhost:3000/orders');
            $orders = $response->successful() ? $response->json() : [];
            
            // Calcular estadísticas
            $total = count($orders);
            $delivered = count(array_filter($orders, fn($o) => $o['status'] == 'delivered'));
            $inRoute = count(array_filter($orders, fn($o) => $o['status'] == 'in_route'));
            $inProcess = count(array_filter($orders, fn($o) => $o['status'] == 'in_process'));
            
            return view('dashboard', compact('orders', 'total', 'delivered', 'inRoute', 'inProcess'));
        } catch (\Exception $e) {
            return view('dashboard', ['orders' => [], 'total' => 0, 'delivered' => 0, 'inRoute' => 0, 'inProcess' => 0]);
        }
    }

    public function logout()
    {
        Session::forget('api_token');
        Session::forget('api_user');
        return redirect()->route('login');
    }
}