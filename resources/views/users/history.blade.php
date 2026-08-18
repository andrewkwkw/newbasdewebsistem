@extends('users.layouts.app')

@section('title', 'Riwayat Aktivitas')

@section('main')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Riwayat Aktivitas</h1>
                <p class="text-gray-500 mt-1 text-lg">Catatan lengkap seluruh aktivitas penyetoran sampah Anda.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('user.dashboard') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm text-sm font-medium text-gray-600 transition-shadow hover:shadow-md hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2 text-indigo-500"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aktivitas</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Poin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($trashLogs as $index => $log)
                            <tr class="group hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 align-middle text-gray-500 font-medium">
                                    {{ $trashLogs->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 align-middle whitespace-nowrap">
                                    <div class="font-bold text-gray-800">
                                        {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d F Y, H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex items-center">
                                        <i class="far fa-clock mr-1 text-gray-400"></i>
                                        {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-green-50 rounded-xl flex items-center justify-center text-green-600 mr-4 border border-green-100 shadow-sm">
                                            <i class="fas fa-recycle text-lg"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">Setor Botol</div>
                                            <div class="text-xs text-gray-500 mt-0.5">Smart Trash #01</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200 shadow-sm">
                                        +{{ $log->points }} Poin
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 bg-gray-50 rounded-full mb-4">
                                            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png"
                                                alt="Empty" class="w-20 h-20 opacity-40 grayscale">
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada aktivitas apapun.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($trashLogs->hasPages())
            <div class="p-6 border-t border-gray-50">
                {{ $trashLogs->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection
