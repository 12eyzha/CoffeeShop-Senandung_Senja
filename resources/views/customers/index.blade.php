@extends('layouts.app')

@section('title', 'Data Pelanggan')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    {{-- Alert Notification --}}
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

    <div class="flex justify-between items-center mb-4">
        <form method="GET" action="{{ route('customers.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari pelanggan..."
                class="border border-gray-300 rounded px-3 py-2 focus:ring-amber-700 focus:border-amber-700">
            <button type="submit" class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <a href="{{ route('customers.create') }}" 
           class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded-md font-medium flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Pelanggan
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700 border border-gray-300 rounded-lg">
            <thead class="bg-amber-900 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">No</th>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Telepon</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Alamat</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $index => $customer)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                        <td class="px-4 py-2">{{ $customer->name }}</td>
                        <td class="px-4 py-2">{{ $customer->phone_number ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $customer->email ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $customer->address ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('customers.edit', $customer->id) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">
                            Tidak ada data pelanggan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $customers->links('pagination::tailwind') }}
    </div>
</div>

@push('scripts')
<script>
    // 🔸 Auto hide alert setelah 3 detik (fade-out halus)
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
