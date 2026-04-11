@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Seguimiento de Pedidos</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('track') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="invoice_number">Número de Factura</label>
                            <input type="text" 
                                   class="form-control @error('invoice_number') is-invalid @enderror" 
                                   id="invoice_number" 
                                   name="invoice_number" 
                                   value="{{ old('invoice_number') }}" 
                                   required>
                            @error('invoice_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="customer_number">Número de Cliente</label>
                            <input type="text" 
                                   class="form-control @error('customer_number') is-invalid @enderror" 
                                   id="customer_number" 
                                   name="customer_number" 
                                   value="{{ old('customer_number') }}" 
                                   required>
                            @error('customer_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Buscar Pedido</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection