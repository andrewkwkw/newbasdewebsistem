@extends('admin.layouts.app')
@section('title', 'Edit Jenis Sampah')

@section('main')
    <div class="animate-fade-in space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <a href="{{ route('jenis_sampah.index') }}" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Jenis Sampah</h1>
                </div>
                <p class="text-sm text-gray-500">Ubah detail dan harga jenis sampah.</p>
            </div>
            <div class="flex items-center space-x-3 text-sm text-gray-500">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('jenis_sampah.index') }}" class="hover:text-indigo-600 font-medium">Jenis Sampah</a>
                <span>/</span>
                <span class="text-gray-800 font-medium">Edit</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-edit text-amber-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 tracking-tight">Form Edit Jenis Sampah</h3>
                    </div>

                    <form action="{{ route('jenis_sampah.update', $jenisSampah->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="nama_sampah" class="block text-sm font-bold text-gray-700 mb-2">Nama Sampah <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_sampah" id="nama_sampah" class="block w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 font-medium focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all @error('nama_sampah') border-rose-500 @enderror" value="{{ old('nama_sampah', $jenisSampah->nama_sampah) }}" required>
                            @error('nama_sampah')
                                <p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="harga_per_kg" class="block text-sm font-bold text-gray-700 mb-2">Harga per Kilogram (Kg) <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-bold">Rp</span>
                                </div>
                                <input type="number" name="harga_per_kg" id="harga_per_kg" class="block w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 font-bold focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all @error('harga_per_kg') border-rose-500 @enderror" value="{{ old('harga_per_kg', $jenisSampah->harga_per_kg) }}" required>
                            </div>
                            <p class="text-xs text-gray-400 mt-3 font-medium"><i class="fas fa-exclamation-triangle mr-1 text-amber-500"></i>Peringatan: Mengubah harga akan otomatis menyesuaikan semua transaksi penyetoran yang pernah dilakukan dengan jenis sampah ini.</p>
                            @error('harga_per_kg')
                                <p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="harga_per_poin" class="block text-sm font-bold text-emerald-600 mb-2">Harga per 1 Poin (Rp) - Smart Trash <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-emerald-500 font-bold">Rp</span>
                                </div>
                                <input type="number" name="harga_per_poin" id="harga_per_poin" class="block w-full pl-12 pr-4 py-3.5 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-900 font-bold focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('harga_per_poin') border-rose-500 @enderror" value="{{ old('harga_per_poin', $jenisSampah->harga_per_poin) }}" required>
                            </div>
                            <p class="text-xs text-emerald-600 mt-2 font-medium">Harga ini digunakan saat warga menukarkan poin dari Smart Trash.</p>
                            @error('harga_per_poin')
                                <p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-4 flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('jenis_sampah.index') }}" class="flex-1 inline-flex items-center justify-center px-6 py-4 bg-white border border-gray-200 text-sm font-bold rounded-xl text-gray-600 hover:bg-gray-50 focus:outline-none transition-all">
                                Batal
                            </a>
                            <button type="submit" class="flex-1 inline-flex items-center justify-center px-6 py-4 border border-transparent text-sm font-bold rounded-xl text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-lg shadow-amber-200 transition-all transform active:scale-95">
                                <i class="fas fa-save mr-2 text-lg"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl p-6 md:p-8 shadow-xl text-white relative overflow-hidden h-full flex flex-col group">
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-700"></div>
                    
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm mb-6">
                            <i class="fas fa-info-circle text-white text-xl"></i>
                        </div>
                        
                        <h3 class="text-white font-bold text-lg mb-3">Informasi Perubahan</h3>
                        <p class="text-indigo-100 text-sm leading-relaxed mb-6">
                            Jika Anda mengubah <strong>Harga per Kilogram</strong>, maka sistem akan secara otomatis menghitung ulang dan menyesuaikan:
                        </p>
                        
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-emerald-300 mt-1 mr-3"></i>
                                <span class="text-sm text-indigo-50 font-medium">Saldo setiap warga yang pernah menabung sampah ini.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-emerald-300 mt-1 mr-3"></i>
                                <span class="text-sm text-indigo-50 font-medium">Total Saldo Admin yang tercatat.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-emerald-300 mt-1 mr-3"></i>
                                <span class="text-sm text-indigo-50 font-medium">Nominal riwayat transaksi di masa lalu.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
@endsection
