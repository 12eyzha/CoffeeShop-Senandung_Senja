@extends('layouts.app')

@section('title', 'Tambah Menu')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4 text-amber-900">Tambah Menu</h1>

    <form action="{{ route('menus.store') }}" method="POST">
        @csrf

        {{-- Nama Menu --}}
        <div class="mb-4">
            <label class="block text-gray-700 mb-1">Nama Menu</label>
            <input type="text" name="name" value="{{ old('name') }}" required 
                   class="w-full border rounded px-3 py-2 focus:ring-amber-700 focus:border-amber-700">
        </div>

        {{-- Kategori --}}
        <div class="mb-4">
            <label class="block text-gray-700 mb-1">Kategori</label>
            <select name="category_id" required class="w-full border rounded px-3 py-2">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Harga --}}
        <div class="mb-4">
            <label class="block text-gray-700 mb-1">Harga</label>
            <input type="number" name="price" value="{{ old('price') }}" required 
                   class="w-full border rounded px-3 py-2">
        </div>

        {{-- Deskripsi --}}
        <div class="mb-4">
            <label class="block text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="3" 
                      class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_available" value="1" 
                       {{ old('is_available') ? 'checked' : '' }} class="mr-2">
                <span>Tersedia</span>
            </label>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-between">
            <a href="{{ route('menus.index') }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</a>
            <button type="submit" class="bg-amber-800 text-white px-4 py-2 rounded hover:bg-amber-700">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
