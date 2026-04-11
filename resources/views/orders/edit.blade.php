@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Editar Pedido #{{ ->invoice_number }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('orders.update', ) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="invoice_number" class="form-label">Número de Factura *</label>
                    <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" 
                           id="invoice_number" name="invoice_number" value="{{ old('invoice_number', ->invoice_number) }}" required>
                    @error('invoice_number')
                        <div class="invalid-feedback">{{  }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="customer_number" class="form-label">Número de Cliente *</label>
                    <input type="text" class="form-control @error('customer_number') is-invalid @enderror" 
                           id="customer_number" name="customer_number" value="{{ old('customer_number', ->customer_number) }}" required>
                    @error('customer_number')
                        <div class="invalid-feedback">{{  }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="customer_name" class="form-label">Nombre/Razón Social *</label>
                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                       id="customer_name" name="customer_name" value="{{ old('customer_name', ->customer_name) }}" required>
                @error('customer_name')
                    <div class="invalid-feedback">{{  }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="fiscal_data" class="form-label">Datos Fiscales</label>
                <textarea class="form-control @error('fiscal_data') is-invalid @enderror" 
                          id="fiscal_data" name="fiscal_data" rows="2">{{ old('fiscal_data', ->fiscal_data) }}</textarea>
                @error('fiscal_data')
                    <div class="invalid-feedback">{{  }}</div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="order_date" class="form-label">Fecha del Pedido *</label>
                    <input type="datetime-local" class="form-control @error('order_date') is-invalid @enderror" 
                           id="order_date" name="order_date" value="{{ old('order_date', ->order_date->format('Y-m-d\TH:i')) }}" required>
                    @error('order_date')
                        <div class="invalid-feedback">{{  }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="delivery_address" class="form-label">Dirección de Entrega *</label>
                    <input type="text" class="form-control @error('delivery_address') is-invalid @enderror" 
                           id="delivery_address" name="delivery_address" value="{{ old('delivery_address', ->delivery_address) }}" required>
                    @error('delivery_address')
                        <div class="invalid-feedback">{{  }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">Notas Adicionales</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" name="notes" rows="3">{{ old('notes', ->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{  }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Actualizar Pedido</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
