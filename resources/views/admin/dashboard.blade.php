@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-sm font-medium text-gray-500">Total Penjualan</h3>
                <p class="text-2xl font-bold text-gray-900">Rp
                    {{ number_format($stats['total_sales'] * 1000, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-sm font-medium text-gray-500">Total Pesanan</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-sm font-medium text-gray-500">Total Menu</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-sm font-medium text-gray-500">Total Pengguna</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <a href="{{ route('admin.menu.index') }}"
                class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">☕ Kelola Menu</h5>
                <p class="font-normal text-gray-700">Tambah, edit, atau hapus menu makanan dan minuman.</p>
            </a>
            <a href="{{ route('admin.promos.index') }}"
                class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">🏷️ Kelola Promo</h5>
                <p class="font-normal text-gray-700">Buat kode promo baru dan atur diskon.</p>
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">👥 Kelola Akun</h5>
                <p class="font-normal text-gray-700">Lihat semua pengguna yang terdaftar.</p>
            </a>
        </div>
    </div>
@endsection