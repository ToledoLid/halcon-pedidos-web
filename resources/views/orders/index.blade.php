@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Lista de Pedidos</h1>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">Nuevo Pedido</a>
</div>

<!-- Formulario de búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('orders.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="invoice_number" class="form-label">Número Factura</label>
                <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ request('invoice_number') }}">
            </div>
            <div class="col-md-3">
                <label for="customer_number" class="form-label">Número Cliente</label>
                <input type="text" class="form-control" id="customer_number" name="customer_number" value="{{ request('customer_number') }}">
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Estado</label>
                <select class="form-control" id="status" name="status">
                    <option value="">Todos</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">Fecha Desde</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">Fecha Hasta</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Factura</th>
                <th>Cliente</th>
                <th>N° Cliente</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Creado por</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->invoice_number }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->customer_number }}</td>
                <td>{{ $order->order_date instanceof \DateTime ? $order->order_date->format('d/m/Y') : \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                <td>
                    @php
                        $badgeClass = match($order->status) {
                            'ordered' => 'bg-secondary',
                            'in_process' => 'bg-warning',
                            'in_route' => 'bg-info',
                            'delivered' => 'bg-success',
                            default => 'bg-secondary'
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $order->status_label }}</span>
                </td>
                <td>{{ $order->creator->name ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning btn-sm">Editar</a>
                    
                    <!-- Botón Archivar -->
                    @if(in_array(auth()->user()->role->name, ['admin', 'sales']))
                    <form action="{{ route('orders.archive', $order) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('¿Archivar este pedido?')">
                            📦 Archivar
                        </button>
                    </form>
                    @endif
                    
                    <!-- Botón Eliminar (solo admin) -->
                    @if(auth()->user()->role->name == 'admin')
                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este pedido?')">
                            🗑️ Eliminar
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No hay pedidos registrados</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $orders->links() }}
@endsection