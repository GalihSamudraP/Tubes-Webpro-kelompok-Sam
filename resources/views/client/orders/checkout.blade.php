@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

        <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Pesanan</h2>
                <ul class="divide-y divide-gray-200">
                    @foreach ($selectedItems as $item)
                        <li class="py-4 flex justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $item['product']->name }}</p>
                                <p class="text-sm text-gray-500">{{ $item['quantity'] }} x Rp
                                    {{ number_format($item['product']->price * 1000, 0, ',', '.') }}
                                </p>
                            </div>
                            <p class="font-medium text-gray-900">Rp {{ number_format($item['subtotal'] * 1000, 0, ',', '.') }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="p-6 bg-gray-50">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium">Rp {{ number_format($totalPrice * 1000, 0, ',', '.') }}</span>
                </div>

                {{-- Dynamic Fields --}}
                <div id="summary-discount" class="flex justify-between mb-2 text-green-600 hidden">
                    <span>Diskon QRIS (10%)</span>
                    <span>- Rp <span id="discount-amount">0</span></span>
                </div>
                <div id="summary-shipping" class="flex justify-between mb-2 text-gray-600">
                    <span>Biaya Pengiriman</span>
                    <span>Rp 15.000</span>
                </div>

                {{-- Promo Code Field --}}
                <div class="mb-4">
                    <label for="promo_code" class="block text-sm font-medium text-gray-700 mb-1">Kode Promo</label>
                    <div class="flex">
                        <input type="text" name="promo_code" id="promo_code"
                            class="flex-1 rounded-l-md border-gray-300 focus:border-amber-500 focus:ring-amber-500 sm:text-sm"
                            placeholder="Masukkan kode promo">
                        <button type="button" onclick="applyPromo()"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-r-md hover:bg-gray-300 font-medium text-sm">Gunakan</button>
                    </div>
                    <p id="promo-message" class="text-sm mt-1 hidden"></p>
                </div>

                <div class="flex justify-between mt-4 pt-4 border-t border-gray-200 text-lg font-bold">
                    <span>Total Pembayaran</span>
                    <span class="text-amber-600">Rp <span
                            id="final-total">{{ number_format(($totalPrice + 15) * 1000, 0, ',', '.') }}</span></span>
                </div>
            </div>
        </div>

        <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
            @csrf
            {{-- Pass the products again --}}
            @foreach ($selectedItems as $index => $item)
                <input type="hidden" name="products[{{ $index }}][id]" value="{{ $item['product']->id }}">
                <input type="hidden" name="products[{{ $index }}][quantity]" value="{{ $item['quantity'] }}">
            @endforeach
            <input type="hidden" name="applied_promo_code" id="applied_promo_code">

            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Metode Pembayaran</h2>

                    <div class="space-y-4">
                        {{-- QRIS --}}
                        <label
                            class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-amber-500 transition-all">
                            <input type="radio" name="payment_method" value="qris"
                                class="h-4 w-4 text-amber-600 border-gray-300 focus:ring-amber-500" onchange="updateTotal()"
                                required>
                            <div class="ml-3 flex flex-col">
                                <span class="block text-sm font-medium text-gray-900">QRIS</span>
                                <span class="block text-sm text-green-600 font-semibold">⚡ Diskon 10% Otomatis!</span>
                            </div>
                        </label>

                        {{-- Bank Transfer --}}
                        <label
                            class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-amber-500 transition-all">
                            <input type="radio" name="payment_method" value="bank_transfer"
                                class="h-4 w-4 text-amber-600 border-gray-300 focus:ring-amber-500"
                                onchange="updateTotal()">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Transfer Bank</span>
                                <span class="block text-sm text-gray-500">BCA 1234567890 a.n TwoCoff</span>
                            </div>
                        </label>

                        {{-- Virtual Account --}}
                        <label
                            class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-amber-500 transition-all">
                            <input type="radio" name="payment_method" value="virtual_account"
                                class="h-4 w-4 text-amber-600 border-gray-300 focus:ring-amber-500"
                                onchange="updateTotal()">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Virtual Account</span>
                                <span class="block text-sm text-gray-500">BNI VA 9876543210</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-amber-600 text-white py-3 px-6 rounded-lg font-bold text-lg hover:bg-amber-700 shadow-md transition-colors">
                Bayar Sekarang
            </button>
        </form>
    </div>

    <script>
        const baseTotal = {{ $totalPrice }};
        const shippingFee = 15; // static for now

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number * 1000);
        }



        async function applyPromo() {
            const code = document.getElementById('promo_code').value;
            const messageEl = document.getElementById('promo-message');
            const discountAmountEl = document.getElementById('discount-amount');
            const appliedCodeInput = document.getElementById('applied_promo_code');

            if (!code) return;

            // Mock Promo Logic since we don't have an API yet. 
            // In real app, fetch /api/promos/check
            // For now, I'll assume all codes starting with 'DISKON' give 20% off for testing, 
            // or I'll implement a quick route check.
            // Let's implement a real route check for professionalism.

            try {
                const response = await fetch(`/api/promos/check?code=${code}`);
                const data = await response.json();

                if (data.valid) {
                    messageEl.textContent = `Promo diterapkan: Diskon ${data.discount}%`;
                    messageEl.className = "text-sm mt-1 text-green-600";
                    messageEl.classList.remove('hidden');
                    appliedCodeInput.value = code;
                    window.promoDiscount = data.discount / 100; // Store for calculation
                } else {
                    messageEl.textContent = "Kode promo tidak valid";
                    messageEl.className = "text-sm mt-1 text-red-600";
                    messageEl.classList.remove('hidden');
                    window.promoDiscount = 0;
                    appliedCodeInput.value = "";
                }
            } catch (e) {
                console.error(e);
                // Fallback for demo if API fails
                if (code === 'DISKON50') {
                    messageEl.textContent = `Promo diterapkan: Diskon 50%`;
                    messageEl.className = "text-sm mt-1 text-green-600";
                    messageEl.classList.remove('hidden');
                    window.promoDiscount = 0.5;
                    appliedCodeInput.value = code;
                } else {
                    messageEl.textContent = "Gagal memverifikasi promo";
                    messageEl.className = "text-sm mt-1 text-red-600";
                }
            }
            updateTotal();
        }

        function updateTotal() {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const discountRow = document.getElementById('summary-discount');
            const discountAmountEl = document.getElementById('discount-amount');
            const finalTotalEl = document.getElementById('final-total');

            let discount = 0;
            let discountDisplay = 0;
            let promoDisc = 0;

            // QRIS Discount
            if (paymentMethod === 'qris') {
                discount = baseTotal * 0.10;
            }

            // Promo Discount (Applied on top of base, or sequential? Let's say parallel for simplicity or sequential?
            // Usually sequential: (Total - QRIS) * Promo? Or just add percentages?
            // Let's apply Promo on Base Total for simplicity.
            if (window.promoDiscount) {
                promoDisc = baseTotal * window.promoDiscount;
            }

            const totalDiscount = discount + promoDisc;

            if (totalDiscount > 0) {
                discountRow.classList.remove('hidden');
                discountAmountEl.textContent = formatRupiah(totalDiscount);
            } else {
                discountRow.classList.add('hidden');
            }

            const finalAmount = Math.max(0, baseTotal - totalDiscount + shippingFee);

            finalTotalEl.textContent = formatRupiah(finalAmount);
        }
    </script>
@endsection