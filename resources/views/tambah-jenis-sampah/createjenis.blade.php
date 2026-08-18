@extends('admin.layouts.app')

@section('title', 'Tambah Jenis Sampah')

@section('main')
<div class="animate-fade-in max-w-2xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('jenis_sampah.index') }}" 
           class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Tambah Jenis Sampah</h1>
            <p class="text-sm text-gray-500 mt-1">Buat kategori baru untuk pengelompokan sampah warga.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-50"></div>

        <form method="POST" action="{{ route('jenis_sampah.store') }}" class="relative z-10 space-y-6">
            @csrf

            {{-- Input Nama Jenis Sampah --}}
            <div class="space-y-2">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Nama Jenis Sampah</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-tag text-gray-400"></i>
                    </div>
                    <input type="text" 
                           name="nama_sampah" 
                           class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border @error('nama_sampah') border-rose-500 @else border-gray-100 @enderror rounded-2xl text-gray-900 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                           placeholder="Contoh: Plastik Botol PET" 
                           value="{{ old('nama_sampah') }}" 
                           required>
                </div>
                @error('nama_sampah')
                    <p class="text-[11px] text-rose-500 font-bold mt-1 px-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input Harga per Kilogram --}}
            <div class="space-y-2">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Harga per Kilogram (Kg)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-gray-400 font-bold text-sm">Rp</span>
                    </div>
                    <input type="number" 
                           name="harga_per_kg" 
                           class="block w-full pl-12 pr-4 py-3.5 bg-gray-50 border @error('harga_per_kg') border-rose-500 @else border-gray-100 @enderror rounded-2xl text-gray-900 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                           placeholder="Contoh: 6000" 
                           value="{{ old('harga_per_kg') }}" 
                           required>
                </div>
                @error('harga_per_kg')
                    <p class="text-[11px] text-rose-500 font-bold mt-1 px-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input Harga per 1 Poin --}}
            <div class="space-y-2">
                <label class="block text-xs font-bold text-emerald-600 uppercase tracking-widest px-1">Harga per 1 Poin (Rp) - Smart Trash</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-emerald-500 font-bold text-sm">Rp</span>
                    </div>
                    <input type="number" 
                           name="harga_per_poin" 
                           class="block w-full pl-12 pr-4 py-3.5 bg-emerald-50 border @error('harga_per_poin') border-rose-500 @else border-emerald-100 @enderror rounded-2xl text-emerald-900 font-bold focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" 
                           placeholder="Contoh: 20" 
                           value="{{ old('harga_per_poin') }}" 
                           required>
                </div>
                @error('harga_per_poin')
                    <p class="text-[11px] text-rose-500 font-bold mt-1 px-1">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-emerald-600 px-1 italic">Harga ini digunakan saat warga menukarkan poin dari Smart Trash.</p>
            </div>

            {{-- Input Admin --}}
            <div class="space-y-2">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Petugas Admin Pengelola</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-user-shield text-gray-400"></i>
                    </div>
                    <select name="admin_id" 
                            class="block w-full pl-11 pr-10 py-3.5 bg-gray-50 border @error('admin_id') border-rose-500 @else border-gray-100 @enderror rounded-2xl text-gray-900 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none" 
                            required>
                        <option value="">-- Pilih Admin --</option>
                        @foreach ($admins as $admin)
                            <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                {{ $admin->fullname ?? $admin->username }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                @error('admin_id')
                    <p class="text-[11px] text-rose-500 font-bold mt-1 px-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="submit" 
                        class="flex-1 inline-flex items-center justify-center px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 transition-all transform active:scale-[0.98]">
                    <i class="fas fa-save mr-2"></i> Simpan Jenis Sampah
                </button>
                <a href="{{ route('jenis_sampah.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-4 bg-white border border-gray-200 text-gray-600 font-bold rounded-2xl hover:bg-gray-50 transition-all">
                    Batalkan
                </a>
            </div>
        </form>
    </div>

    <!-- Info Section -->
    <div class="mt-8 bg-indigo-50/50 rounded-2xl p-6 border border-indigo-100/50">
        <div class="flex items-start space-x-3 text-indigo-800">
            <i class="fas fa-info-circle mt-1"></i>
            <div>
                <p class="text-sm font-bold">Catatan Penting</p>
                <p class="text-xs mt-1 text-indigo-600 leading-relaxed">
                    Pastikan harga per kilogram sudah sesuai dengan harga pasar terbaru. Perubahan harga pada kategori yang sudah ada akan memengaruhi kalkulasi transaksi di masa mendatang.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
@endsection

