<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Photo;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // CAMBIADO: Agregado ->active() para mostrar solo pedidos NO archivados
        $query = Order::active()->with('creator')->orderBy('created_at', 'desc');

        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }
        if ($request->filled('customer_number')) {
            $query->where('customer_number', 'like', '%' . $request->customer_number . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $orders = $query->paginate(15);
        $statuses = Order::$statuses;

        return view('orders.index', compact('orders', 'statuses'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->role->name, ['admin', 'sales'])) {
            abort(403, 'No tienes permiso para crear pedidos.');
        }
        return view('orders.create');
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role->name, ['admin', 'sales'])) {
            abort(403, 'No tienes permiso para crear pedidos.');
        }

        $request->validate([
            'invoice_number' => 'required|unique:orders',
            'customer_name' => 'required|string|max:255',
            'customer_number' => 'required|string|max:50',
            'fiscal_data' => 'nullable|string',
            'order_date' => 'required|date',
            'delivery_address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        Order::create([
            'invoice_number' => $request->invoice_number,
            'customer_name' => $request->customer_name,
            'customer_number' => $request->customer_number,
            'fiscal_data' => $request->fiscal_data,
            'order_date' => $request->order_date,
            'delivery_address' => $request->delivery_address,
            'notes' => $request->notes,
            'status' => Order::STATUS_ORDERED,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('orders.index')->with('success', 'Pedido creado exitosamente.');
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        if (!in_array(auth()->user()->role->name, ['admin', 'sales'])) {
            abort(403, 'No tienes permiso para editar pedidos.');
        }
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        if (!in_array(auth()->user()->role->name, ['admin', 'sales'])) {
            abort(403, 'No tienes permiso para editar pedidos.');
        }

        $request->validate([
            'invoice_number' => 'required|unique:orders,invoice_number,' . $order->id,
            'customer_name' => 'required|string|max:255',
            'customer_number' => 'required|string|max:50',
            'fiscal_data' => 'nullable|string',
            'order_date' => 'required|date',
            'delivery_address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $order->update($request->all());

        return redirect()->route('orders.index')->with('success', 'Pedido actualizado exitosamente.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::$statuses)),
        ]);

        $oldStatus = $order->status_label;
        $order->update(['status' => $request->status]);

        return redirect()->route('orders.show', $order)->with('success', "Estado cambiado de {$oldStatus} a {$order->status_label}");
    }

    public function uploadPhoto(Request $request, Order $order)
    {
        if (!in_array(auth()->user()->role->name, ['admin', 'route'])) {
            abort(403, 'No tienes permiso para subir fotos.');
        }

        $request->validate([
            'photo' => 'required|image|max:5120',
            'photo_type' => 'required|in:loading,delivery',
        ]);

        $path = $request->file('photo')->store('orders/' . $order->id, 'public');

        Photo::create([
            'order_id' => $order->id,
            'photo_path' => $path,
            'photo_type' => $request->photo_type,
            'uploaded_by' => auth()->id(),
        ]);

        if ($request->photo_type === 'delivery') {
            $order->update(['status' => Order::STATUS_DELIVERED]);
        }

        return redirect()->route('orders.show', $order)->with('success', 'Foto subida exitosamente.');
    }

    public function destroy(Order $order)
    {
        if (auth()->user()->role->name !== 'admin') {
            abort(403, 'No tienes permiso para eliminar pedidos.');
        }
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Pedido eliminado correctamente.');
    }

    public function trashed()
    {
        if (auth()->user()->role->name !== 'admin') {
            abort(403, 'No tienes permiso para ver pedidos eliminados.');
        }
        $orders = Order::onlyTrashed()->with('creator')->orderBy('deleted_at', 'desc')->paginate(15);
        return view('orders.trashed', compact('orders'));
    }

    public function restore($id)
    {
        if (auth()->user()->role->name !== 'admin') {
            abort(403, 'No tienes permiso para restaurar pedidos.');
        }
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();
        return redirect()->route('orders.trashed')->with('success', 'Pedido restaurado exitosamente.');
    }

    public function forceDelete($id)
    {
        if (auth()->user()->role->name !== 'admin') {
            abort(403, 'No tienes permiso para eliminar permanentemente pedidos.');
        }
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->forceDelete();
        return redirect()->route('orders.trashed')->with('success', 'Pedido eliminado permanentemente.');
    }

    // ========== NUEVOS MÉTODOS PARA ARCHIVADO ==========

    // Archivar pedido
    public function archive(Order $order)
    {
        if (!in_array(auth()->user()->role->name, ['admin', 'sales'])) {
            abort(403, 'No tienes permiso para archivar pedidos.');
        }
        
        $order->archive();
        return redirect()->route('orders.index')->with('success', 'Pedido archivado correctamente.');
    }

    // Listar pedidos archivados
    public function archived()
    {
        if (!in_array(auth()->user()->role->name, ['admin', 'sales'])) {
            abort(403, 'No tienes permiso para ver pedidos archivados.');
        }
        
        $orders = Order::archived()->with('creator')->orderBy('created_at', 'desc')->paginate(15);
        return view('orders.archived', compact('orders'));
    }

    // Restaurar pedido (desarchivar)
    public function restoreArchived($id)
    {
        if (!in_array(auth()->user()->role->name, ['admin', 'sales'])) {
            abort(403, 'No tienes permiso para restaurar pedidos.');
        }
        
        $order = Order::findOrFail($id);
        $order->unarchive();
        return redirect()->route('orders.archived')->with('success', 'Pedido restaurado correctamente.');
    }
}