@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">

    {{-- 🔸 Alert Notification --}}
    @if(session('success'))
        <div class="bg-amber-100 border-l-4 border-amber-700 text-amber-900 p-4 mb-4 rounded">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-700 text-red-900 p-4 mb-4 rounded">
            <i class="fas fa-exclamation-triangle mr-2"></i>Periksa kembali inputan kamu.
        </div>
    @endif

    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block mb-1 font-medium text-gray-700">Nama</label>
                <input 
                    type="text" 
                    name="name" 
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700" 
                    value="{{ old('name') }}" 
                    required>
            </div>
            <div>
                <label class="block mb-1 font-medium text-gray-700">Telepon</label>
                <input 
                    type="text" 
                    name="phone_number" 
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700" 
                    value="{{ old('phone_number') }}">
            </div>
            <div>
                <label class="block mb-1 font-medium text-gray-700">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700" 
                    value="{{ old('email') }}">
            </div>
            <div>
    <label class="block mb-1 font-medium text-gray-700">Password</label>
    <input type="password" name="password" class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700" required>
</div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">Tanggal Lahir</label>
                <input 
                    type="date" 
                    name="date_of_birth" 
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700" 
                    value="{{ old('date_of_birth') }}">
            </div>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium text-gray-700">Alamat</label>
            <textarea 
                name="address" 
                rows="3" 
                class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">{{ old('address') }}</textarea>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('customers.index') }}" 
               class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded mr-2">
                Batal
            </a>
            <button 
                type="submit" 
                class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded">
                Simpan
            </button>
        </div>
    </form>
</div>

{{-- 🔸 Alert fade-out script --}}
@push('scripts')
<script>
    setTimeout(() => {
        const alerts = document.querySelectorAll('.bg-amber-100, .bg-red-100');
        alerts.forEach(el => {
            el.style.transition = "opacity 0.8s ease";
            el.style.opacity = "0";
            setTimeout(() => el.remove(), 800);
        });
    }, 3000);
</script>
@endpush
@endsection
