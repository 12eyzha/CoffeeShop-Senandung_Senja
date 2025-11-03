@extends('layouts.app')

@section('title', 'Data Menu')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-amber-900">Data Menu</h1>
        <a href="{{ route('menus.create') }}" 
           class="bg-amber-800 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition">
            <i class="fas fa-plus mr-2"></i>Tambah Menu
        </a>
    </div>

    {{-- 🔍 Search --}}
    <form method="GET" class="mb-4 flex">
        <input type="text" name="search" placeholder="Cari menu..." value="{{ $search }}"
               class="border px-3 py-2 rounded-l w-1/3 focus:ring-amber-700 focus:border-amber-700">
        <button class="bg-amber-800 text-white px-4 py-2 rounded-r hover:bg-amber-700">
            <i class="fas fa-search"></i>
        </button>
    </form>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-amber-900 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Kategori</th>
                    <th class="px-4 py-2 text-left">Harga</th>
                    <th class="px-4 py-2 text-left">Deskripsi</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menus as $menu)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $menu->name }}</td>
                    <td class="px-4 py-2">{{ $menu->category->name ?? '-' }}</td>
                    <td class="px-4 py-2">Rp{{ number_format($menu->price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 text-sm text-gray-600">{{ $menu->description ?? '-' }}</td>
                    <td class="px-4 py-2 text-center">
                        <span class="px-2 py-1 rounded text-white {{ $menu->is_available ? 'bg-green-600' : 'bg-red-600' }}">
                            {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <a href="{{ route('menus.edit', $menu) }}" 
                           class="text-blue-600 hover:text-blue-800 mr-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('menus.destroy', $menu) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus menu ini?')" 
                                    class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $menus->links() }}
        </div>
    </div>
</div>
@endsection
