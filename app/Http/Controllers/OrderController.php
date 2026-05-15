<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL', 'http://localhost:3000');
    }

    public function index(Request $request)
    {
        $token = Session::get('api_token');
        $response = Http::withToken($token)->get($this->apiUrl . '/orders');
        $orders = $response->successful() ? $response->json() : [];
        
        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $token = Session::get('api_token');
        $response = Http::withToken($token)->get($this->apiUrl . '/orders/' . $id);
        $order = $response->successful() ? $response->json() : null;
        
        return view('orders.show', compact('order'));
    }

    public function edit($id)
    {
        $token = Session::get('api_token');
        $response = Http::withToken($token)->get($this->apiUrl . '/orders/' . $id);
        $order = $response->successful() ? $response->json() : null;
        
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $token = Session::get('api_token');
        Http::withToken($token)->put($this->apiUrl . '/orders/' . $id, [
            'customer' => $request->customer,
            'address' => $request->address,
            'status' => $request->status
        ]);
        
        return redirect()->route('orders.index')->with('success', 'Pedido actualizado');
    }

    public function updateStatus(Request $request, $id)
    {
        $token = Session::get('api_token');
        Http::withToken($token)->put($this->apiUrl . '/orders/' . $id, [
            'status' => $request->status
        ]);
        
        return response()->json(['success' => true]);
    }

    public function uploadPhoto(Request $request, $id)
    {
        $token = Session::get('api_token');
        $photo = $request->file('photo');
        
        $response = Http::withToken($token)
            ->attach('photo', file_get_contents($photo), $photo->getClientOriginalName())
            ->post($this->apiUrl . '/orders/' . $id . '/photo');
        
        if ($response->successful()) {
            return back()->with('success', 'Foto subida correctamente');
        }
        
        return back()->with('error', 'Error al subir la foto');
    }

    public function destroy($id)
    {
        // API Node.js no tiene delete implementado
        return redirect()->route('orders.index')->with('error', 'Eliminación no disponible en API');
    }
}