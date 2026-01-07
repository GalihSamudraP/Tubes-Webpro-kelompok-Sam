@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-900">Riwayat Pesanan Saya</h1>

        @if($orders->isEmpty())
            <div class="bg-white shadow rounded-lg p-6 text-center text-gray-500">
                Anda belum pernah memesan. <a href="{{ route('home') }}" class="text-amber-600 hover:underline">Lihat Menu</a>
            </div>
        @else
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <ul role="list" class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <li class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Pesanan #{{ $order->id }}</h3>
                                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Dipesan pada
                                        {{ $order->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                    @if($order->status === 'completed') bg-green-100 text-green-800 
                                                                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800 
                                                                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800 
                                                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    <span class="text-lg font-bold text-gray-900">Rp
                                        {{ number_format($order->total_price * 1000, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 mt-4 pt-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Item</h4>
                                <ul class="space-y-2">
                                    @foreach($order->items as $item)
                                        <li class="flex justify-between text-sm text-gray-600">
                                            <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                            <span>Rp {{ number_format($item->price * $item->quantity * 1000, 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                @if($order->status === 'completed')
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <h4 class="text-sm font-bold text-amber-700 mb-2">Berikan Penilaian</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Product Ratings -->
                                            @foreach($order->items as $item)
                                                @php
                                                    $existingRating = \App\Models\ProductRating::where('user_id', auth()->id())
                                                        ->where('product_id', $item->product_id)->first();
                                                @endphp
                                                @if(!$existingRating)
                                                    <form action="{{ route('rating.product') }}" method="POST"
                                                        class="bg-gray-50 p-3 rounded text-sm">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                        <p class="font-medium">{{ $item->product->name }}</p>
                                                        <select name="rating" class="mt-1 block w-full text-xs rounded-md border-gray-300"
                                                            required>
                                                            <option value="">Pilih Bintang...</option>
                                                            <option value="5">⭐⭐⭐⭐⭐ (Sempurna)</option>
                                                            <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                                                            <option value="3">⭐⭐⭐ (Cukup)</option>
                                                            <option value="2">⭐⭐ (Kurang)</option>
                                                            <option value="1">⭐ (Buruk)</option>
                                                        </select>
                                                        <button type="submit"
                                                            class="mt-2 w-full bg-amber-600 text-white py-1 px-2 rounded text-xs hover:bg-amber-700">Kirim</button>
                                                    </form>
                                                @else
                                                    <div class="bg-gray-50 p-3 rounded text-sm text-gray-500">
                                                        <p class="font-medium">{{ $item->product->name }}</p>
                                                        <p class="text-yellow-500">{{ str_repeat('⭐', $existingRating->rating) }}</p>
                                                    </div>
                                                @endif
                                            @endforeach

                                            <!-- Barista Rating -->
                                            @if($order->barista_id)
                                                @php
                                                    $baristaRating = \App\Models\BaristaRating::where('user_id', auth()->id())
                                                        ->where('barista_id', $order->barista_id)->first();
                                                @endphp
                                                @if(!$baristaRating)
                                                    <form action="{{ route('rating.barista') }}" method="POST"
                                                        class="bg-blue-50 p-3 rounded text-sm">
                                                        @csrf
                                                        <input type="hidden" name="barista_id" value="{{ $order->barista_id }}">
                                                        <p class="font-medium text-blue-800">Nilai Barista</p>
                                                        <select name="rating" class="mt-1 block w-full text-xs rounded-md border-gray-300"
                                                            required>
                                                            <option value="">Pilih Bintang...</option>
                                                            <option value="5">⭐⭐⭐⭐⭐ (Ramah & Cepat)</option>
                                                            <option value="4">⭐⭐⭐⭐ (Baik)</option>
                                                            <option value="3">⭐⭐⭐ (Biasa)</option>
                                                            <option value="2">⭐⭐ (Kurang)</option>
                                                            <option value="1">⭐ (Buruk)</option>
                                                        </select>
                                                        <button type="submit"
                                                            class="mt-2 w-full bg-blue-600 text-white py-1 px-2 rounded text-xs hover:bg-blue-700">Kirim</button>
                                                    </form>
                                                @else
                                                    <div class="bg-blue-50 p-3 rounded text-sm text-gray-500">
                                                        <p class="font-medium text-blue-800">Nilai Barista</p>
                                                        <p class="text-yellow-500">{{ str_repeat('⭐', $baristaRating->rating) }}</p>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection