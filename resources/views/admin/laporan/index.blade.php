@extends('admin.layouts.app')

@section('title', 'Laporan Laporan Keuangan Warga')

@section('main')
    <div class="animate-fade-in space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Laporan Bulanan</h1>
                <p class="text-sm text-gray-500 mt-1">Pantau akumulasi poin dan aktivitas setor sampah warga.</p>
            </div>
            <div class="flex items-center space-x-3">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                    <i class="far fa-calendar-alt mr-2"></i> Periode:
                    {{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}
                </span>
            </div>
        </div>

        {{-- FILTER & EXPORT SECTION --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
            <form method="GET" action="" class="flex flex-col md:flex-row items-end gap-4">
                <div class="w-full md:w-64 space-y-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Pilih Periode
                        Bulan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <input type="month" name="bulan"
                            class="block w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            value="{{ $bulan }}">
                    </div>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button type="submit"
                        class="flex-1 md:flex-none inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-indigo-100 transform active:scale-95">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <a href="{{ route('admin.laporan.pdf', ['bulan' => $bulan]) }}"
                        class="flex-1 md:flex-none inline-flex items-center justify-center px-5 py-2.5 bg-rose-50 text-rose-600 hover:bg-rose-100 text-sm font-bold rounded-xl transition-all border border-rose-100 transform active:scale-95">
                        <i class="fas fa-file-pdf mr-2"></i> Export PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Total Transaksi Card -->
            <div
                class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl p-6 shadow-xl shadow-amber-100 relative overflow-hidden group">
                <div
                    class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-700">
                </div>
                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between">
                    <div>
                        <h3 class="text-white/80 text-xs font-bold uppercase tracking-widest">Total Poin Terkumpul</h3>
                        <div class="flex items-baseline mt-2 space-x-6">
                            <!-- Berat/Poin -->
                            <div>
                                <span class="text-white text-3xl font-bold tracking-tight">
                                    {{ number_format($totalPoin ?? 0, 0, ',', '.') }}
                                </span>
                                <span class="text-white/70 text-sm font-bold ml-1">Poin</span>
                            </div>
                            
                            <!-- Equivalent Rp -->
                            <div>
                                <span class="text-white/70 text-sm font-bold mr-1">= Rp</span>
                                <span class="text-white text-3xl font-bold tracking-tight">
                                    {{ number_format($totalRp ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/20 mt-4 sm:mt-0">
                        <i class="fas fa-coins text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Laporan Per Jenis -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 flex items-center space-x-3 bg-gray-50/50">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-layer-group text-indigo-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 tracking-tight">Per Jenis Sampah</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/30">
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                    Kategori</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 text-right">
                                    Poin & Uang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($perJenis as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-gray-800">{{ $item['nama_sampah'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex flex-col items-end">
                                            <span class="text-sm font-bold text-amber-600">{{ number_format($item['total_poin'], 0, ',', '.') }} Poin</span>
                                            <span class="text-xs text-gray-500 font-medium">Rp {{ number_format($item['total_uang'], 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-gray-400 italic text-sm">Tidak ada data.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Laporan Per User -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 flex items-center space-x-3 bg-gray-50/50">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-amber-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 tracking-tight">Per Nasabah</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/30">
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                    Nama Nasabah</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 text-right">
                                    Total Poin & Uang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($perUser as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-8 h-8 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center text-xs font-bold">
                                                {{ substr($item['user'], 0, 1) }}
                                            </div>
                                            <span class="text-sm font-bold text-gray-800">{{ $item['user'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex flex-col items-end">
                                            <span class="text-sm font-bold text-amber-600">{{ number_format($item['total_poin'], 0, ',', '.') }} Poin</span>
                                            <span class="text-xs text-gray-500 font-medium">Rp {{ number_format($item['total_uang'], 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-gray-400 italic text-sm">Tidak ada data.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Daily Recaps Section -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-history text-gray-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 tracking-tight">Rekap Transaksi Harian</h3>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">Nasabah</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">Jenis Sampah</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 text-right">Jumlah Setor</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 text-right">Uang Diterima</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($transaksi as $t)
                            <tr class="hover:bg-gray-50/30 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-500">
                                    {{ $t->created_at->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-800">{{ $t->user->fullname ?? $t->user->name ?? 'Warga' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-1.5 items-start">
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold rounded uppercase">
                                            {{ $t->jenisSampah->nama_sampah ?? '-' }}
                                        </span>
                                        @if(str_contains(strtolower($t->description), 'tukar'))
                                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded uppercase flex items-center">
                                                <i class="fas fa-gift mr-1"></i> Tukar Poin
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded uppercase flex items-center">
                                                <i class="fas fa-balance-scale mr-1"></i> Setor Manual
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center text-sm font-bold text-gray-800">
                                        {{ $t->berat }}
                                        <span class="ml-1 text-xs text-gray-500 font-medium">
                                            {{ str_contains(strtolower($t->description), 'tukar') ? 'Poin' : 'Kg' }}
                                        </span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600">
                                        Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic text-sm">Belum ada transaksi harian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
@endsection