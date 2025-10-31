@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    {{-- ✅ Alert Notification --}}
    @if(session('success'))
        <div class="bg-amber-100 border-l-4 border-amber-700 text-amber-900 p-4 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-700 text-red-900 p-4 mb-4 rounded">
            Periksa kembali inputan kamu.
        </div>
    @endif

    {{-- ✅ Form Tambah Karyawan --}}
    <form action="{{ route('employees.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            {{-- Nama --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
            </div>

            {{-- Telepon --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Telepon</label>
                <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                       class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
            </div>

            {{-- Email --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
            </div>

            {{-- Jabatan --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Jabatan</label>
                <input type="text" name="position" value="{{ old('position', 'admin') }}"
                       class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
            </div>

            {{-- Tanggal Lahir --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Tanggal Lahir</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                       class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
            </div>

            {{-- Tanggal Bergabung --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Tanggal Bergabung</label>
                <input type="date" name="join_date" value="{{ old('join_date') }}"
                       class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
            </div>

            {{-- Alamat --}}
            <div class="md:col-span-2">
                <label class="block mb-1 font-medium text-gray-700">Alamat</label>
                <textarea name="address" rows="3" class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">{{ old('address') }}</textarea>
            </div>

            {{-- Gaji --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Gaji</label>
                <input type="number" step="0.01" name="salary" value="{{ old('salary') }}"
                       class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700" placeholder="Opsional">
            </div>

            {{-- Status --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Status</label>
                <select name="status" class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
        </div>

        {{-- ✅ Password --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium text-gray-700">Password</label>
            <input
                type="password"
                name="password"
                autocomplete="new-password"
                class="w-full border rounded p-2 focus:ring-amber-700 focus:border-amber-700"
                placeholder="Masukkan password (min. 6 karakter)"
                @if(!isset($employee)) required @endif>
            <small class="text-sm text-gray-500">
                @if(isset($employee))
                    Biarkan kosong jika tidak ingin mengganti password
                @else
                    Wajib diisi untuk akun baru
                @endif
            </small>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end">
            <a href="{{ route('employees.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded mr-2">
                Batal
            </a>
            <button type="submit" class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded">
                Simpan
            </button>
        </div>
    </form>
</div>

{{-- ✅ Auto Fade Alert --}}
@push('scripts')
<script>
    setTimeout(() => {
        document.querySelectorAll('.bg-amber-100, .bg-red-100').forEach(el => {
            el.style.transition = "opacity 0.8s ease";
            el.style.opacity = "0";
            setTimeout(() => el.remove(), 800);
        });
    }, 3000);
</script>
@endpush
@endsection
