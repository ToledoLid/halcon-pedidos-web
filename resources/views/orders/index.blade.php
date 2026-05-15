@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Lista de Pedidos</h1>
    <a href="#" class="btn btn-primary" disabled>Nuevo Pedido (Próximamente)</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Factura</th>
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th>Foto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td><strong>{{ $order['invoice'] }}</strong></td>
                <td>{{ $order['customer'] }}</td>
                <td>{{ $order['address'] }}</td>
                <td>
                    <select onchange="updateStatus({{ $order['id'] }}, this.value)" class="form-select form-select-sm">
                        <option value="in_process" {{ $order['status'] == 'in_process' ? 'selected' : '' }}>⏳ En Proceso</option>
                        <option value="in_route" {{ $order['status'] == 'in_route' ? 'selected' : '' }}>🚚 En Ruta</option>
                        <option value="delivered" {{ $order['status'] == 'delivered' ? 'selected' : '' }}>✅ Entregado</option>
                    </select>
                </td>
                <td>
                    @if($order['photo'])
                        <img src="http://localhost:3000{{ $order['photo'] }}" width="50" class="rounded">
                    @else
                        <span class="text-muted">Sin foto</span>
                    @endif
                    <form action="{{ route('orders.photo', $order['id']) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                        @csrf
                        <input type="file" name="photo" class="form-control form-control-sm" accept="image/*">
                        <button type="submit" class="btn btn-sm btn-primary mt-1">📷 Subir</button>
                    </form>
                </td>
                <td>
                    <a href="{{ route('orders.edit', $order['id']) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No hay pedidos registrados</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function updateStatus(orderId, status) {
    fetch('/orders/' + orderId + '/status', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status })
    }).then(() => location.reload());
}
</script>
@endsection