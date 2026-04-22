@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📦 Inventario</h1>
    @if(auth()->user()->role->name == 'admin')
    <a href="{{ route('inventory.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Producto
    </a>
    @endif
</div>

@if(isset($lowStockCount) && $lowStockCount > 0)
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <strong>¡Atención!</strong> Hay {{ $lowStockCount }} producto(s) con stock bajo.
</div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('inventory.index') }}" class="row g-3">
            <div class="col-md-10">
                <input type="text" class="form-control" name="search" placeholder="Buscar por código o nombre..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Stock</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr class="{{ $product->isLowStock() ? 'table-warning' : '' }}">
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td class="{{ $product->stock <= $product->min_stock ? 'text-danger fw-bold' : '' }}">
                    {{ $product->stock }} {{ $product->unit }}
                </td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>
                    @if($product->is_active)
                        <span class="badge bg-success">Activo</span>
                    @else
                        <span class="badge bg-danger">Inactivo</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('inventory.show', $product) }}" class="btn btn-info btn-sm">Ver</a>
                    @if(auth()->user()->role->name == 'admin')
                    <a href="{{ route('inventory.edit', $product) }}" class="btn btn-warning btn-sm">Editar</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No hay productos registrados</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $products->links() }}
@endsection