@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-900">Menu Kami</h1>
            <p class="mt-4 text-xl text-gray-500">Nikmati kopi segar dan camilan lezat.</p>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500">Belum ada menu yang tersedia saat ini.</p>
            </div>
        @else
            @if(Auth::check() && Auth::user()->role !== 'client')
                {{-- Admin/Barista View (No Ordering) --}}
                <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                    @foreach($products as $product)
                        <div
                            class="group relative bg-white border border-gray-200 rounded-lg flex flex-col overflow-hidden hover:shadow-lg transition-shadow duration-200">
                            {{-- Link to Detail Page --}}
                            <a href="{{ route('menu.show', $product->id) }}" class="block flex-1">
                                <div class="aspect-w-3 aspect-h-4 bg-gray-200 group-hover:opacity-75 sm:aspect-none sm:h-52">
                                    @if($product->image)
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-center object-cover sm:w-full sm:h-full">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                            <span class="text-4xl">☕</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</p>
                                    <p class="mt-2 text-xl font-semibold text-amber-600">Rp
                                        {{ number_format($product->price * 1000, 0, ',', '.') }}
                                    </p>
                                </div>
                            </a>
                            <div class="p-4 bg-gray-50 text-center text-sm text-gray-500">
                                View Only ({{ ucfirst(Auth::user()->role) }})
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Customer/Guest View (Ordering Enabled) --}}
                <form action="{{ route('orders.checkout') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                        @foreach($products as $product)
                            <div
                                class="group relative bg-white border border-gray-200 rounded-lg flex flex-col overflow-hidden hover:shadow-lg transition-shadow duration-200">
                                {{-- Link to Detail Page --}}
                                <a href="{{ route('menu.show', $product->id) }}" class="block flex-1">
                                    <div class="aspect-w-3 aspect-h-4 bg-gray-200 group-hover:opacity-75 sm:aspect-none sm:h-52">
                                        @if($product->image)
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                                class="w-full h-full object-center object-cover sm:w-full sm:h-full">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                                <span class="text-4xl">☕</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</p>
                                        <p class="mt-2 text-xl font-semibold text-amber-600">Rp
                                            {{ number_format($product->price * 1000, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </a>

                                <div class="p-4 border-t pt-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="products[{{ $loop->index }}][id]" value="{{ $product->id }}"
                                                onchange="toggleQuantity(this, 'qty-{{ $product->id }}')"
                                                class="h-5 w-5 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700">Pilih</label>
                                        </div>
                                        <div class="flex-1">
                                            <input type="number" id="qty-{{ $product->id }}"
                                                name="products[{{ $loop->index }}][quantity]" value="1" min="1"
                                                class="block w-full sm:text-sm border-gray-300 rounded-md border p-1 bg-gray-100"
                                                placeholder="Jml" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <script>
                        function toggleQuantity(checkbox, inputId) {
                            const input = document.getElementById(inputId);
                            if (checkbox.checked) {
                                input.disabled = false;
                                input.classList.remove('bg-gray-100');
                                input.classList.add('bg-white');
                            } else {
                                input.disabled = true;
                                input.classList.remove('bg-white');
                                input.classList.add('bg-gray-100');
                            }
                        }
                    </script>

                    {{-- Static Order Bar (Now above Footer) --}}
                    <div class="mt-12 bg-white border rounded-lg shadow-lg p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                            <span class="text-gray-600 text-lg">Sudah selesai memilih?</span>
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                Pesan Sekarang
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        @endif
    </div>
@endsection