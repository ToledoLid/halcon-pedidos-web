@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Editar Pedido #{{ $order['invoice'] }}</h3>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('orders.update', $order['id']) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="invoice" class="form-label">Número de Factura</label>
                <input type="text" class="form-control" value="{{ $order['invoice'] }}" disabled>
            </div>
            
            <div class="mb-3">
                <label for="customer" class="form-label">Nombre del Cliente *</label>
                <input type="text" class="form-control" id="customer" name="customer" value="{{ $order['customer'] }}" required>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Dirección de Entrega *</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ $order['address'] }}" required>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Estado</label>
                <select class="form-control" id="status" name="status">
                    <option value="in_process" {{ $order['status'] == 'in_process' ? 'selected' : '' }}>⏳ En Proceso</option>
                    <option value="in_route" {{ $order['status'] == 'in_route' ? 'selected' : '' }}>🚚 En Ruta</option>
                    <option value="delivered" {{ $order['status'] == 'delivered' ? 'selected' : '' }}>✅ Entregado</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Actualizar Pedido</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection