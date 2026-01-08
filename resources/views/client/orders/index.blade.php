@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-900">Riwayat Pesanan Saya</h1>

        <div id="orders-container">
            @include('client.orders.partials.list')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setInterval(function () {
                fetch('{{ route('orders.index') }}?partial=true')
                    .then(response => response.text())
                    .then(html => {
                        if (html.trim()) {
                            document.getElementById('orders-container').innerHTML = html;
                        }
                    })
                    .catch(error => console.error('Error fetching orders:', error));
            }, 5000); // Poll every 5 seconds
        });
    </script>
@endsection