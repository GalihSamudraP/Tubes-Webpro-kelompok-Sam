<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }} - TwoCoff</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #d97706;
        }

        /* Amber-600 */
        .header .details {
            text-align: right;
        }

        .info-table {
            width: 100%;
            margin-bottom: 40px;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #eee;
            padding: 12px;
            text-align: left;
        }

        .items-table th {
            background-color: #f9fafb;
            font-weight: bold;
        }

        .total-section {
            text-align: right;
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: flex-end;
            padding: 5px 0;
        }

        .total-row span {
            min-width: 150px;
        }

        .final-total {
            font-size: 1.25em;
            font-weight: bold;
            color: #d97706;
            border-top: 2px solid #eee;
            padding-top: 10px;
            margin-top: 10px;
        }

        .status-stamp {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
            border: 2px solid;
        }

        .paid {
            color: #059669;
            border-color: #059669;
        }

        .pending {
            color: #d97706;
            border-color: #d97706;
        }

        .print-btn {
            background: #d97706;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            margin-right: 10px;
        }

        .back-btn {
            background: #4b5563;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            display: inline-block;
        }

        @media print {

            .print-btn,
            .back-btn {
                display: none;
            }

            .invoice-box {
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <div>
                <h1>TwoCoff</h1>
                <p>Premium Coffee Experience</p>
            </div>
            <div class="details">
                <p><strong>Invoice #:</strong> {{ $order->id }}</p>
                <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}</p>
                <div class="status-stamp {{ $order->payment_status == 'paid' ? 'paid' : 'pending' }}">
                    {{ strtoupper($order->payment_status) }}
                </div>
            </div>
        </div>

        <table class="info-table">
            <tr>
                <td>
                    <strong>Ditagihkan kepada:</strong><br>
                    {{ $order->user->name }}<br>
                    {{ $order->user->email }}
                </td>
                <td style="text-align: right;">
                    <strong>Metode Pembayaran:</strong><br>
                    {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: right;">Harga Satuan</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->price * 1000, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp
                            {{ number_format($item->price * $item->quantity * 1000, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rp
                    {{ number_format(($order->total_price + $order->discount_amount - 15) * 1000, 0, ',', '.') }}</span>
            </div>
            @if($order->discount_amount > 0)
                <div class="total-row" style="color: #059669;">
                    <span>Diskon:</span>
                    <span>- Rp {{ number_format($order->discount_amount * 1000, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="total-row">
                <span>Pengiriman:</span>
                <span>Rp 15.000</span>
            </div>
            <div class="total-row final-total">
                <span>Total:</span>
                <span>Rp {{ number_format($order->total_price * 1000, 0, ',', '.') }}</span>
            </div>
        </div>

        <div style="text-align: center;">
            <button onclick="window.print()" class="print-btn">Cetak Invoice</button>
            <a href="{{ route('orders.index') }}" class="back-btn">Kembali ke Dashboard</a>
        </div>
    </div>
</body>

</html>