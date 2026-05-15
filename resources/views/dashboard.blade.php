@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $total ?? 0 }}</div>
                    <div class="stat-label">Total Pedidos</div>
                </div>
                <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $delivered ?? 0 }}</div>
                    <div class="stat-label">Entregados</div>
                </div>
                <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $inRoute ?? 0 }}</div>
                    <div class="stat-label">En Ruta</div>
                </div>
                <div class="stat-icon"><i class="bi bi-truck"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $inProcess ?? 0 }}</div>
                    <div class="stat-label">En Proceso</div>
                </div>
                <div class="stat-icon"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="table-custom">
    <table class="table mb-0">
        <thead>
            <tr><th>Factura</th><th>Cliente</th><th>Dirección</th><th>Estado</th><th>Foto</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            @forelse($orders ?? [] as $order)
            <tr>
                <td>{{ $order['invoice'] }}</td>
                <td>{{ $order['customer'] }}</td>
                <td>{{ $order['address'] }}</td>
                <td><span class="badge-status badge-{{ $order['status'] }}">
                    @if($order['status']=='delivered') Entregado
                    @elseif($order['status']=='in_route') En Ruta
                    @else En Proceso @endif
                </span></td>
                <td>@if($order['photo'])<img src="http://localhost:3000{{ $order['photo'] }}" width="50">@endif</td>
                <td>
                    <select onchange="updateStatus({{ $order['id'] }}, this.value)" class="form-select form-select-sm">
                        <option value="in_process" {{ $order['status']=='in_process' ? 'selected' : '' }}>En Proceso</option>
                        <option value="in_route" {{ $order['status']=='in_route' ? 'selected' : '' }}>En Ruta</option>
                        <option value="delivered" {{ $order['status']=='delivered' ? 'selected' : '' }}>Entregado</option>
                    </select>
                    <form method="POST" action="{{ route('orders.photo', $order['id']) }}" enctype="multipart/form-data" class="mt-2">
                        @csrf
                        <input type="file" name="photo" class="form-control form-control-sm mb-1">
                        <button type="submit" class="btn btn-sm btn-primary w-100">Subir Foto</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">No hay órdenes</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function updateStatus(orderId, status) {
    fetch('/orders/' + orderId + '/status', {
        method: 'PUT',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({status: status})
    }).then(() => location.reload());
}
</script>
@endsection