<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Photo;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    // Listar todas las órdenes
    public function index()
    {
        $orders = Order::with('user', 'photos')->latest()->get();
        return response()->json($orders);
    }

    // Ver una orden específica
    public function show($id)
    {
        $order = Order::with('user', 'photos', 'statusHistories.changer')->findOrFail($id);
        return response()->json($order);
    }

    // Crear nueva orden
    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|unique:orders',
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable',
            'address' => 'required',
            'total_amount' => 'required|numeric',
        ]);

        $order = Order::create([
            'invoice_number' => $request->invoice_number,
            'user_id' => auth()->id(),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'address' => $request->address,
            'total_amount' => $request->total_amount,
            'status' => 'pending',
            'is_deleted' => false,
        ]);

        return response()->json($order, 201);
    }

    // Actualizar orden
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'address' => 'required',
            'total_amount' => 'required|numeric',
        ]);

        $order->update($request->only([
            'customer_name', 'customer_email', 'customer_phone', 
            'address', 'total_amount'
        ]));

        return response()->json($order);
    }

    // Cambiar estado de la orden
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,processing,in_route,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update(['status' => $newStatus]);

        if ($request->filled('process_name')) {
            $order->update(['process_name' => $request->process_name]);
        }

        if ($request->filled('process_date')) {
            $order->update(['process_date' => $request->process_date]);
        }

        // Registrar historial
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'notes' => $request->notes,
            'changed_by' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Estado actualizado',
            'order' => $order
        ]);
    }

    // Subir foto (evidencia)
    public function uploadPhoto(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'photo' => 'required|image|max:2048',
            'photo_type' => 'required|in:delivery,route',
        ]);

        $path = $request->file('photo')->store('photos', 'public');

        $photo = Photo::create([
            'order_id' => $order->id,
            'photo_path' => $path,
            'photo_type' => $request->photo_type,
        ]);

        return response()->json([
            'message' => 'Foto subida',
            'photo_url' => asset('storage/' . $path)
        ]);
    }

    // Eliminar (archivar) orden
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['is_deleted' => true, 'deleted_at' => now()]);
        
        return response()->json(['message' => 'Orden archivada']);
    }

    // Buscar orden por número de factura (público)
    public function track($invoice_number)
    {
        $order = Order::where('invoice_number', $invoice_number)
            ->where('is_deleted', false)
            ->with('photos')
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Orden no encontrada'], 404);
        }

        return response()->json($order);
    }
}