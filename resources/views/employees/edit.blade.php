@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">

    {{-- ✅ ALERT SUKSES & ERROR --}}
    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
            <i class="fas fa-times-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- ✅ FORM EDIT --}}
    <form action="{{ route('employees.update', $employee->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            {{-- Nama --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Nama</label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name', $employee->name) }}" 
                    required
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
            </div>

            {{-- Telepon --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Telepon</label>
                <input 
                    type="text" 
                    name="phone_number" 
                    value="{{ old('phone_number', $employee->phone_number) }}" 
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
            </div>

            {{-- Email --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email', $employee->email) }}" 
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
            </div>

            {{-- Tanggal Lahir --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Tanggal Lahir</label>
                <input 
                    type="date" 
                    name="date_of_birth" 
                    value="{{ old('date_of_birth', $employee->date_of_birth) }}" 
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
            </div>

            {{-- Jabatan --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Jabatan</label>
                <input 
                    type="text" 
                    name="position" 
                    value="{{ old('position', $employee->position) }}" 
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
            </div>

            {{-- Tanggal Bergabung --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Tanggal Bergabung</label>
                <input 
                    type="date" 
                    name="join_date" 
                    value="{{ old('join_date', $employee->join_date) }}" 
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
            </div>

            {{-- Gaji --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Gaji</label>
                <input 
                    type="number" 
                    step="0.01"
                    name="salary" 
                    value="{{ old('salary', $employee->salary) }}" 
                    class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
            </div>

            {{-- Status --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Status</label>
                <select name="status" class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
                    <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
        </div>

        {{-- Alamat --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium text-gray-700">Alamat</label>
            <textarea 
                name="address" 
                rows="3" 
                class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">{{ old('address', $employee->address) }}</textarea>
        </div>

        {{-- ✅ Password --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium text-gray-700">Password</label>
            <input
                type="password"
                name="password"
                class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700"
                placeholder="Masukkan password baru (opsional)">
            <small class="text-sm text-gray-500">
                Biarkan kosong jika tidak ingin mengganti password
            </small>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end">
            <a href="{{ route('employees.index') }}" 
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

{{-- ✅ Auto-hide alert --}}
@push('scripts')
<script>
    setTimeout(() => {
        document.querySelectorAll('.bg-green-100, .bg-red-100').forEach(el => {
            el.style.transition = "opacity 0.8s ease";
            el.style.opacity = "0";
            setTimeout(() => el.remove(), 800);
        });
    }, 3000);
</script>
@endpush
@endsection
