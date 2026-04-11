@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Estado del Pedido</h4>
                </div>
                <div class="card-body">
                    @if(isset($order))
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Número de Factura:</strong> {{ $order->invoice_number }}
                        </div>
                        <div class="col-md-6">
                            <strong>Fecha del Pedido:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Cliente:</strong> {{ $order->customer_name }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Dirección de Entrega:</strong> {{ $order->delivery_address }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Estado Actual:</strong>
                            @php
                                $badgeClass = match($order->status) {
                                    'ordered' => 'bg-secondary',
                                    'in_process' => 'bg-warning',
                                    'in_route' => 'bg-info',
                                    'delivered' => 'bg-success',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} fs-6">{{ $order->status_label }}</span>
                        </div>
                    </div>
                    
                    @if($order->notes)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Notas:</strong> {{ $order->notes }}
                        </div>
                    </div>
                    @endif
                    
                    @if($order->status == 'delivered' && isset($order->delivery_photo))
                    <div class="alert alert-success mt-3">
                        <h5>¡Pedido Entregado!</h5>
                        <img src="{{ asset('storage/' . $order->delivery_photo->photo_path) }}" class="img-fluid mt-2" style="max-width: 300px;">
                    </div>
                    @endif
                    
                    <a href="{{ route('track.form') }}" class="btn btn-secondary mt-3">Nueva Consulta</a>
                    @else
                    <div class="alert alert-danger">
                        No se encontró el pedido solicitado. Verifique el número de factura y cliente.
                    </div>
                    <a href="{{ route('track.form') }}" class="btn btn-primary mt-3">Volver a buscar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection