@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
            {{-- Image Gallery --}}
            <div class="flex flex-col-reverse">
                <div
                    class="w-full aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden sm:aspect-w-2 sm:aspect-h-3">
                    @if($product->image)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                            class="w-full h-full object-center object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                            <span class="text-6xl">☕</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Product Info --}}
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $product->name }}</h1>

                <div class="mt-3">
                    <h2 class="sr-only">Product information</h2>
                    <p class="text-3xl text-amber-600">Rp {{ number_format($product->price * 1000, 0, ',', '.') }}</p>
                </div>

                {{-- Rating --}}
                <div class="mt-3">
                    <h3 class="sr-only">Reviews</h3>
                    <div class="flex items-center">
                        <div class="flex items-center">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="{{ ($product->ratings_avg_rating ?? 0) > $i ? 'text-amber-500' : 'text-gray-300' }} h-5 w-5 flex-shrink-0"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="sr-only">{{ $product->ratings_avg_rating }} out of 5 stars</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">Description</h3>
                    <div class="text-base text-gray-700 space-y-6">
                        <p>{{ $product->description }}</p>
                    </div>
                </div>

                <div class="mt-10 flex sm:flex-col1">
                    <a href="{{ route('home') }}"
                        class="max-w-xs flex-1 bg-gray-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-gray-500 sm:w-full">
                        Kembali ke Menu
                    </a>
                </div>
            </div>
        </div>

        {{-- Reviews Section --}}
        <div class="mt-16 border-t border-gray-200 pt-10">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Ulasan Pelanggan</h3>

            @if($product->ratings->isEmpty())
                <p class="text-gray-500 italic">Belum ada ulasan untuk produk ini.</p>
            @else
                <div class="space-y-8">
                    @foreach($product->ratings as $rating)
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <div class="flex items-center mb-2">
                                <div class="flex items-center">
                                    @for ($i = 0; $i < 5; $i++)
                                        <svg class="{{ $rating->rating > $i ? 'text-amber-500' : 'text-gray-300' }} h-5 w-5 flex-shrink-0"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span class="ml-2 text-sm font-bold text-gray-900">{{ $rating->user->name }}</span>
                                <span class="ml-2 text-sm text-gray-500">- {{ $rating->created_at->diffForHumans() }}</span>
                            </div>
                            @if($rating->review)
                                <p class="text-gray-600">{{ $rating->review }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection