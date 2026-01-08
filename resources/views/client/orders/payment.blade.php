@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="px-6 py-8">
                <div class="text-center mb-8">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/1200px-Logo_QRIS.svg.png"
                        alt="QRIS" class="h-12 mx-auto mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">Menunggu Pembayaran</h2>
                    <p class="text-sm text-gray-500 mt-1">Selesaikan pembayaran sebelum waktu habis</p>
                </div>

                <!-- Timer -->
                <div class="bg-amber-50 rounded-lg p-4 mb-8 text-center border border-amber-100">
                    <span class="text-gray-600 text-sm">Sisa Waktu Pembayaran</span>
                    <div class="text-3xl font-mono font-bold text-amber-600 mt-1" id="timer">15:00</div>
                </div>

                <!-- Amount -->
                <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-6">
                    <span class="text-gray-600">Total Pembayaran</span>
                    <span class="text-xl font-bold text-gray-900">Rp
                        {{ number_format($order->total_price * 1000, 0, ',', '.') }}</span>
                </div>

                <!-- QR Code Area -->
                <div class="flex justify-center mb-8">
                    <div id="qrcode" class="p-4 bg-white border-2 border-dashed border-gray-300 rounded-lg"></div>
                </div>

                <div class="text-center text-xs text-gray-400 mb-8">
                    ID Pemesanan: #{{ $order->id }}<br>
                    Silakan scan QR Code di atas menggunakan aplikasi pembayaran pendukung QRIS.
                </div>

                <!-- Simulation Button (Hidden in Prod usually, but visible for this demo) -->
                <form action="{{ route('orders.pay', $order) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition duration-150 ease-in-out">
                        Simulasikan Pembayaran Berhasil
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <a href="{{ route('orders.index') }}" class="text-sm text-gray-500 hover:text-gray-900">Batalkan &
                        Kembali ke Pesanan</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Generate QR Code
        // Format: 00020101021126570014ID.CO.QRIS.WWW011893600915300000000005204600053033605802ID5916TwoCoff Merchant6013Jakarta Pusat61051011062070703A0163046B7C
        // Just using a mockup string + amount + orderID for "realism"
        const orderId = "{{ $order->id }}";
        const amount = "{{ $order->total_price }}";
        const qrData = `00020101021226590013ID.CO.QRIS.WWW01189360091530000000000520460005303360540${amount.length}${amount}5802ID5916TwoCoff Merchant6013Jakarta Pusat62180114ORDER${orderId}6304`;

        new QRCode(document.getElementById("qrcode"), {
            text: qrData,
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        // Timer Logic
        let timeLeft = 15 * 60; // 15 minutes
        const timerElement = document.getElementById('timer');

        const countdown = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;

            seconds = seconds < 10 ? '0' + seconds : seconds;

            timerElement.innerHTML = `${minutes}:${seconds}`;

            if (timeLeft <= 0) {
                clearInterval(countdown);
                timerElement.innerHTML = "EXPIRED";
                timerElement.classList.add('text-red-600');
            }

            timeLeft--;
        }, 1000);
    </script>
@endsection