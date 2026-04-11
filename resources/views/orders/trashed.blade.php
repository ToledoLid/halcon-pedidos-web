@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Pedidos Eliminados</h1>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Volver a Pedidos Activos</a>
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
                <th>Eliminado el</th>
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
                <td>{{ $order->deleted_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form action="{{ route('orders.restore', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('¿Restaurar este pedido?')">Restaurar</button>
                    </form>
                    <form action="{{ route('orders.force-delete', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar permanentemente este pedido? Esta acción no se puede deshacer.')">Eliminar Permanentemente</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No hay pedidos eliminados</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $orders->links() }}
@endsection