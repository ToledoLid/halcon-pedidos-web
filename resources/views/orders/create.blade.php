@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Crear Nuevo Pedido</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="invoice_number" class="form-label">Número de Factura *</label>
                    <input type="text" class="form-control" id="invoice_number" name="invoice_number" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="customer_number" class="form-label">Número de Cliente *</label>
                    <input type="text" class="form-control" id="customer_number" name="customer_number" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="customer_name" class="form-label">Nombre/Razón Social *</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
            </div>
            <div class="mb-3">
                <label for="fiscal_data" class="form-label">Datos Fiscales</label>
                <textarea class="form-control" id="fiscal_data" name="fiscal_data" rows="2"></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="order_date" class="form-label">Fecha del Pedido *</label>
                    <input type="datetime-local" class="form-control" id="order_date" name="order_date" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="delivery_address" class="form-label">Dirección de Entrega *</label>
                    <input type="text" class="form-control" id="delivery_address" name="delivery_address" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="notes" class="form-label">Notas Adicionales</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Pedido</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
