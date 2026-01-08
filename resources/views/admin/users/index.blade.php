@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-900">Kelola Akun Pengguna</h1>

        <div class="bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peran
                            (Role)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($user->role === 'admin') bg-purple-100 text-purple-800 
                                            @elseif($user->role === 'barista') bg-blue-100 text-blue-800 
                                            @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                                @if($user->is_suspended)
                                    <span
                                        class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Suspended</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2">
                                @if(auth()->id() !== $user->id)
                                    {{-- Suspend/Unsuspend Button (Not for Admins) --}}
                                    @if($user->role !== 'admin')
                                        <form action="{{ route('admin.users.toggle-suspend', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="text-{{ $user->is_suspended ? 'green' : 'yellow' }}-600 hover:text-{{ $user->is_suspended ? 'green' : 'yellow' }}-900">
                                                {{ $user->is_suspended ? 'Aktifkan' : 'Suspend' }}
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Delete Button --}}
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection