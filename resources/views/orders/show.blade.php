@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Pedido #{{ $order->invoice_number }}</h1>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Volver</a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Información del Pedido</h4>
            </div>
            <div class="card-body">
                <p><strong>Factura:</strong> {{ $order->invoice_number }}</p>
                <p><strong>Cliente:</strong> {{ $order->customer_name }}</p>
                <p><strong>N° Cliente:</strong> {{ $order->customer_number }}</p>
                <p><strong>Fecha:</strong> {{ $order->order_date->format('d/m/Y H:i') }}</p>
                <p><strong>Dirección:</strong> {{ $order->delivery_address }}</p>
                <p><strong>Estado:</strong> 
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
                </p>
                @if($order->fiscal_data)
                    <p><strong>Datos Fiscales:</strong><br>{{ $order->fiscal_data }}</p>
                @endif
                @if($order->notes)
                    <p><strong>Notas:</strong><br>{{ $order->notes }}</p>
                @endif
                <p><strong>Creado por:</strong> {{ $order->creator->name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <!-- Cambiar Estado -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Cambiar Estado</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-8">
                            <select name="status" class="form-control" required>
                                <option value="ordered" {{ $order->status == 'ordered' ? 'selected' : '' }}>Pedido</option>
                                <option value="in_process" {{ $order->status == 'in_process' ? 'selected' : '' }}>En Proceso</option>
                                <option value="in_route" {{ $order->status == 'in_route' ? 'selected' : '' }}>En Ruta</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Entregado</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">Actualizar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Subir Fotos -->
        @if(in_array(auth()->user()->role->name, ['admin', 'route']))
        <div class="card mb-4">
            <div class="card-header">
                <h4>Subir Evidencia</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.upload-photo', $order) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tipo de Foto</label>
                        <select name="photo_type" class="form-control" required>
                            <option value="loading">Foto de Carga</option>
                            <option value="delivery">Foto de Entrega</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Seleccionar Foto</label>
                        <input type="file" name="photo" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Subir Foto</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Fotos de Evidencia -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Foto de Carga</h4>
            </div>
            <div class="card-body text-center">
                @if($order->loading_photo)
                    <img src="{{ asset('storage/' . $order->loading_photo->photo_path) }}" class="img-fluid" style="max-height: 300px;">
                    <p class="mt-2 text-muted">Subida por: {{ $order->loading_photo->uploader->name ?? 'N/A' }}</p>
                @else
                    <p class="text-muted">No hay foto de carga disponible</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Foto de Entrega</h4>
            </div>
            <div class="card-body text-center">
                @if($order->delivery_photo)
                    <img src="{{ asset('storage/' . $order->delivery_photo->photo_path) }}" class="img-fluid" style="max-height: 300px;">
                    <p class="mt-2 text-muted">Subida por: {{ $order->delivery_photo->uploader->name ?? 'N/A' }}</p>
                @else
                    <p class="text-muted">No hay foto de entrega disponible</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection