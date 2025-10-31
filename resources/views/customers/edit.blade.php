@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">

    {{-- ✅ ALERT SUKSES & ERROR --}}
    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded alert-message">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded alert-message">
            <i class="fas fa-times-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block mb-1 font-medium text-gray-700">Nama</label>
                <input 
                    type="text" 
                    name="name" 
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700" 
                    value="{{ $customer->name }}" 
                    required>
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">Telepon</label>
                <input 
                    type="text" 
                    name="phone_number" 
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700" 
                    value="{{ $customer->phone_number }}">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700" 
                    value="{{ $customer->email }}">
            </div>
<div>
    <label class="block mb-1 font-medium text-gray-700">Password (isi jika ingin ubah)</label>
    <input type="password" name="password" class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
</div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">Tanggal Lahir</label>
                <input 
                    type="date" 
                    name="date_of_birth" 
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700" 
                    value="{{ $customer->date_of_birth }}">
            </div>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium text-gray-700">Alamat</label>
            <textarea 
                name="address" 
                rows="3" 
                class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">{{ $customer->address }}</textarea>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('customers.index') }}" 
               class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded mr-2">
                Batal
            </a>

            <button 
                type="submit" 
                class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded">
                Perbarui
            </button>
        </div>
    </form>
</div>

{{-- 🔸 Fade Out Alert --}}
@push('scripts')
<script>
    setTimeout(() => {
        document.querySelectorAll('.alert-message').forEach(el => {
            el.style.transition = 'opacity 0.8s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 800);
        });
    }, 3000);
</script>
@endpush

@endsection
