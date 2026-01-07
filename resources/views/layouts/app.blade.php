<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TwoCoff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        amber: {
                            50: '#f0f4f8',
                            100: '#dce6f2',
                            200: '#bccce6',
                            300: '#90aed5',
                            400: '#608ac1',
                            500: '#3c6ba9',
                            600: '#1C4D8D', // Primary Brand Color
                            700: '#15396B',
                            800: '#1a365d',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(to bottom right, #f8fafc, #e2e8f0);
            min-height: 100vh;
        }
    </style>
</head>

<body class="text-gray-800">
    <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <a href="{{ route('home') }}"
                        class="flex-shrink-0 flex items-center text-2xl font-bold text-amber-600 hover:text-amber-700 transition-colors duration-300">
                        ☕ TwoCoff
                    </a>
                </div>
                <div class="flex items-center space-x-2">
                    @auth
                        @if(Auth::user()->role === 'barista')
                            <a href="{{ route('barista.dashboard') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-amber-600 transition-colors">Dashboard</a>
                        @elseif(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-amber-600 transition-colors">Admin
                                Panel</a>
                        @else
                            <a href="{{ route('home') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-amber-600 transition-colors">Menu</a>
                            <a href="{{ route('orders.index') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-amber-600 transition-colors">Pesanan
                                Saya</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="ml-4">
                            @csrf
                            <button type="submit"
                                class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-amber-600 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="ml-2 px-4 py-2 rounded-md text-sm font-medium bg-amber-600 text-white hover:bg-amber-700 transform hover:scale-105 transition-all duration-200 shadow-md">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="bg-gray-100 border-t mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">&copy; 2024 CoffeeShop. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>