<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Halcon System - @yield('title', 'Dashboard')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #f0f2f5; }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100%;
            background: linear-gradient(135deg, #1a1e2b 0%, #0f1119 100%);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }
        
        .sidebar-header p {
            font-size: 0.75rem;
            opacity: 0.6;
            margin-top: 5px;
        }
        
        .sidebar-nav {
            padding: 20px 0;
        }
        
        .nav-item {
            padding: 0 15px;
            margin-bottom: 5px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .nav-link i { font-size: 1.2rem; }
        .nav-link:hover { background: rgba(255,255,255,0.1); color: white; }
        .nav-link.active { background: #4e73df; color: white; }
        
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
        
        .top-bar {
            background: white;
            padding: 15px 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: #4e73df;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .stat-card:hover { transform: translateY(-5px); }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #4e73df, #224abe);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .stat-number { font-size: 1.8rem; font-weight: 700; color: #1a1e2b; }
        .stat-label { color: #6c757d; font-size: 0.85rem; }
        
        .table-custom {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .table-custom thead {
            background: linear-gradient(135deg, #1a1e2b, #0f1119);
            color: white;
        }
        
        .table-custom thead th {
            padding: 15px;
            border: none;
        }
        
        .table-custom tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }
        
        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-delivered { background: #d4edda; color: #155724; }
        .badge-in_route { background: #fff3cd; color: #856404; }
        .badge-in_process { background: #d1ecf1; color: #0c5460; }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .sidebar.show { transform: translateX(0); }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Halcon System</h3>
            <p>Seguimiento de Pedidos</p>
        </div>
        <div class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('orders.index') }}" class="nav-link">
                    <i class="bi bi-box-seam"></i>
                    <span>Pedidos</span>
                </a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
            <div class="user-info">
                <span>{{ Session::get('api_user.name', 'Admin') }}</span>
                <div class="user-avatar">
                    {{ substr(Session::get('api_user.name', 'A'), 0, 1) }}
                </div>
                <form method="POST" action="{{ route('logout.api') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-dark p-0">
                        <i class="bi bi-box-arrow-right fs-5"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>