@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📦 Pedidos Archivados</h1>
    <div>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Volver a Pedidos Activos</a>
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
                <th>Fecha Archivo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->invoice_number }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->customer_number }}</td>
                <td>{{ $order->order_date->format('d/m/Y') }}</td>
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
                <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form action="{{ route('orders.restore-archived', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('¿Restaurar este pedido?')">
                            🔄 Restaurar
                        </button>
                    </form>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-info btn-sm">👁️ Ver</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No hay pedidos archivados</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $orders->links() }}
@endsection