<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Product::distinct()->pluck('category');
        $lowStockCount = Product::lowStock()->count();

        return view('inventory.index', compact('products', 'categories', 'lowStockCount'));
    }

    public function create()
    {
        if (auth()->user()->role->name !== 'admin') {
            abort(403, 'No tienes permiso para crear productos.');
        }
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role->name !== 'admin') {
            abort(403, 'No tienes permiso para crear productos.');
        }

        $request->validate([
            'code' => 'required|unique:products',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('inventory.index')->with('success', 'Producto creado exitosamente.');
    }

    public function show(Product $product)
    {
        return view('inventory.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if (auth()->user()->role->name !== 'admin') {
            abort(403, 'No tienes permiso para editar productos.');
        }
        return view('inventory.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if (auth()->user()->role->name !== 'admin') {
            abort(403, 'No tienes permiso para editar productos.');
        }

        $request->validate([
            'code' => 'required|unique:products,code,' . $product->id,
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('inventory.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product)
    {
        if (auth()->user()->role->name !== 'admin') {
            abort(403, 'No tienes permiso para eliminar productos.');
        }
        $product->delete();
        return redirect()->route('inventory.index')->with('success', 'Producto eliminado correctamente.');
    }

    public function adjustStock(Request $request, Product $product)
    {
        if (!in_array(auth()->user()->role->name, ['admin', 'sales'])) {
            abort(403, 'No tienes permiso para ajustar stock.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:in,out',
            'reason' => 'nullable|string'
        ]);

        if ($request->type === 'in') {
            $product->stock += $request->quantity;
        } else {
            if ($product->stock < $request->quantity) {
                return back()->with('error', 'Stock insuficiente.');
            }
            $product->stock -= $request->quantity;
        }

        $product->save();

        return redirect()->route('inventory.index')->with('success', 'Stock actualizado correctamente.');
    }
}