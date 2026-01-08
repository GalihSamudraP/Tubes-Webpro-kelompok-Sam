@if($orders->isEmpty())
    <div class="bg-white shadow rounded-lg p-6 text-center text-gray-500">
        Tidak ada pesanan masuk.
    </div>
@else
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        @foreach($orders as $order)
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Pesanan #{{ $order->id }}</h3>
                            <p class="text-sm text-gray-500">Pelanggan: <span
                                    class="font-semibold">{{ $order->user->name }}</span></p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    @if($order->status === 'completed') bg-green-100 text-green-800 
                                                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800 
                                                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800 
                                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 border-t border-gray-100 pt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Detail Pesanan:</h4>
                        <ul class="space-y-1 mb-4">
                            @foreach($order->items as $item)
                                <li class="flex justify-between text-sm text-gray-600">
                                    <span><span class="font-bold">{{ $item->quantity }}x</span>
                                        {{ $item->product->name }}</span>
                                </li>
                            @endforeach
                        </ul>

                        @if($order->payment_status === 'unpaid' && $order->payment_method !== 'qris')
                            <form action="{{ route('barista.orders.confirm-payment', $order) }}" method="POST" class="mb-4">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 text-center text-sm font-medium">
                                    Konfirmasi Pembayaran (Manual)
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('barista.orders.updateStatus', $order) }}" method="POST"
                            class="mt-4 bg-gray-50 p-4 rounded-md">
                            @csrf
                            @method('PATCH')
                            <label for="status-{{ $order->id }}" class="block text-sm font-medium text-gray-700 mb-2">Update
                                Status</label>
                            <div class="flex space-x-2">
                                <select name="status" id="status-{{ $order->id }}"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm rounded-md border">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                        Processing</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                                    </option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                    </option>
                                </select>
                                <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif