@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Seguimiento de Pedidos - Halcon</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('track') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="invoice_number" class="form-label">Número de Factura</label>
                            <input type="text" class="form-control" id="invoice_number" name="invoice_number" placeholder="Ej: FAC-000001" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_number" class="form-label">Número de Cliente</label>
                            <input type="text" class="form-control" id="customer_number" name="customer_number" placeholder="Ej: CLI-0001" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Consultar Pedido</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection