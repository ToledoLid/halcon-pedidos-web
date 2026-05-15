<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Halcon System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h1 class="text-2xl font-bold text-center mb-2">🚚 Halcon System</h1>
            <p class="text-center text-gray-600 mb-6">Sistema de seguimiento de pedidos</p>
            
            @if($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.api') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Correo electrónico</label>
                    <input type="email" name="email" value="admin@example.com" required
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Contraseña</label>
                    <input type="password" name="password" value="admin123" required
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" 
                        class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                    Iniciar Sesión
                </button>
            </form>
        </div>
    </div>
</body>
</html>