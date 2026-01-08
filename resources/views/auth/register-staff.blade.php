@extends('layouts.app')

@section('content')
    <div class="min-h-[calc(100vh-200px)] flex flex-col items-center justify-center">
        <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md border-t-4 border-amber-600">
            <h2 class="text-3xl font-bold mb-2 text-center text-gray-800">Staff Registration</h2>
            <p class="text-center text-gray-500 mb-6 text-sm">Authorized Personnel Only</p>

            <form method="POST" action="{{ route('register.staff.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" id="name" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-3 border">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" id="email" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-3 border">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-3 border">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-3 border">
                    </div>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Staff Role</label>
                    <select name="role" id="role"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-3 border">
                        <option value="barista">Barista (Employee)</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div>
                    <label for="secret_key" class="block text-sm font-medium text-gray-700">Secret Key</label>
                    <input type="password" name="secret_key" id="secret_key" required
                        placeholder="Required for staff access"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm p-3 border bg-red-50">
                </div>

                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700 transition duration-150">
                    Create Staff Account
                </button>
            </form>
            <p class="mt-4 text-center text-sm text-gray-600">
                <a href="{{ route('home') }}" class="font-medium text-amber-600 hover:text-amber-500">Back to Home</a>
            </p>
        </div>
    </div>
@endsection