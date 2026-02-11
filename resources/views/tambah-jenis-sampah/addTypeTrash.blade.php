@extends('admin.layouts.app')

@section('title', 'Daftar Jenis Sampah')

@section('main')
    <div class="animate-fade-in space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Jenis Sampah</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola kategori sampah dan harga beli per kilogram.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('create_jenis') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all transform active:scale-95">
                    <i class="fas fa-plus mr-2"></i> Tambah Jenis
                </a>
                <a href="{{ route('viewcreate') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-100 transition-all transform active:scale-95">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Data Setoran
                </a>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div
                class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl flex items-center justify-between shadow-sm animate-slide-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-emerald-500 mr-3 text-lg"></i>
                    <p class="text-sm text-emerald-800 font-medium">{{ session('success') }}</p>
                </div>
                <button type="button" class="text-emerald-500 hover:text-emerald-700 transition-colors"
                    onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Content Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-trash-alt text-indigo-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 tracking-tight">Data Sampah Tersedia</h3>
                </div>
                <div class="relative">
                    <input type="text" id="tableSearch" placeholder="Cari jenis sampah..."
                        class="pl-10 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-xs focus:ring-indigo-500 focus:border-indigo-500 w-full md:w-64 transition-all">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 w-20">
                                No</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                Nama Jenis Sampah</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($jenisSampah as $index => $sampah)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4 text-sm font-bold text-gray-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 font-bold text-lg">
                                            <i class="fas fa-box-open text-sm"></i>
                                        </div>
                                        <div>
                                            <p
                                                class="text-sm font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">
                                                {{ $sampah->nama_sampah }}</p>
                                            <p class="text-[10px] text-gray-400 font-medium">Kategori Aktif</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('jenis-sampah.edit', $sampah->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 text-xs font-bold rounded-lg transition-colors border border-amber-100">
                                            <i class="fas fa-edit mr-1.5"></i> Edit
                                        </a>
                                        <form action="{{ route('jenis_sampah.destroy', $sampah->id) }}" method="POST"
                                            class="inline" onsubmit="return confirmDelete('{{ $sampah->nama_sampah }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold rounded-lg transition-colors border border-rose-100">
                                                <i class="fas fa-trash-alt mr-1.5"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-trash-restore-alt text-gray-300 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium italic">Belum ada jenis sampah yang ditambahkan.</p>
                                        <a href="{{ route('create_jenis') }}"
                                            class="mt-4 text-indigo-600 font-bold text-sm hover:underline">Tambah Sekarang</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-slide-in {
            animation: slideIn 0.4s ease-out forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
@endsection

@push('scripts')
    <script>
        function confirmDelete(namaSampah) {
            return confirm(`Yakin ingin menghapus jenis sampah "${namaSampah}"? Data ini tidak bisa dikembalikan.`);
        }

        // Table Search
        document.getElementById('tableSearch')?.addEventListener('keyup', function () {
            let value = this.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
            });
        });
    </script>
@endpush