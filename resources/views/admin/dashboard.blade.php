@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('main')
    <div class="animate-fade-in space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Admin Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola saldo dan pantau aktivitas pengguna Bank Sampah Desa.</p>
            </div>
            <div class="flex items-center space-x-3">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                    <i class="far fa-calendar-alt mr-2"></i> {{ now()->translatedFormat('l, d F Y') }}
                </span>
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

        <!-- Stats Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Saldo Admin Card -->
            <div class="lg:col-span-1">
                <div
                    class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl p-6 shadow-xl shadow-indigo-100 relative overflow-hidden group h-full">
                    <!-- Abstract Pattern -->
                    <div
                        class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-700">
                    </div>

                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <div
                                class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-4 backdrop-blur-md">
                                <i class="fas fa-wallet text-white text-xl"></i>
                            </div>
                            <h3 class="text-white/80 text-sm font-bold uppercase tracking-widest">Saldo Utama Admin</h3>
                            <div class="flex items-baseline mt-2">
                                <span class="text-white/60 text-lg font-bold mr-1">Rp</span>
                                <span class="text-white text-3xl font-bold tracking-tight">
                                    {{ number_format((float) str_replace('.', '', $adminSaldo), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <button
                            class="mt-8 w-full py-3 px-4 bg-white/10 hover:bg-white/20 text-white rounded-xl font-bold text-sm transition-all flex items-center justify-center backdrop-blur-sm border border-white/20 group"
                            data-toggle="modal" data-target="#updateSaldoModal">
                            <i class="fas fa-edit mr-2 group-hover:rotate-12 transition-transform"></i> Update Saldo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tambah Saldo Form Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 h-full">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-plus text-indigo-600"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 tracking-tight">Tambah Saldo Admin</h3>
                        </div>
                    </div>

                    <form action="{{ route('admin.tambah-saldo') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Jumlah
                                Nominal</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-400 font-bold text-sm">Rp</span>
                                </div>
                                <input type="text" name="jumlah" id="jumlah"
                                    class="block w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-gray-900 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                    placeholder="Contoh: 2.000.000" required>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-6 py-4 border border-transparent text-sm font-bold rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-100 transition-all transform active:scale-[0.98]">
                            <i class="fas fa-plus-circle mr-2 text-lg"></i> Tambah ke Saldo Utama
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Users Table Section -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-amber-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 tracking-tight">Daftar Pengguna & Keuangan</h3>
                </div>
                <div class="relative">
                    <input type="text" id="tableSearch" placeholder="Cari pengguna..."
                        class="pl-10 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-xs focus:ring-indigo-500 focus:border-indigo-500 w-full md:w-64 transition-all">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                No</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                Nama & Kontak</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                Username</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 text-center">
                                Alamat</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 text-right">
                                Total Masuk</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 text-right">
                                Saldo Saat Ini</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4 text-sm font-bold text-gray-400">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-9 h-9 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 font-bold text-sm">
                                            {{ substr($user->fullname, 0, 1) }}
                                        </div>
                                        <div>
                                            <p
                                                class="text-sm font-bold text-gray-800 leading-tight group-hover:text-indigo-600 transition-colors">
                                                {{ $user->fullname }}</p>
                                            <p class="text-[10px] text-gray-500 mt-0.5">{{ $user->email }} |
                                                {{ $user->no_telpon }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-600">{{ $user->username }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 text-center">{{ $user->tempat }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 text-right">
                                    <span
                                        class="text-gray-400 text-[10px] mr-1">Rp</span>{{ number_format($user->total_masuk, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $user->saldo >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                        Rp {{ number_format($user->saldo, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-user-slash text-gray-300 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium italic">Belum ada data pengguna terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL UPDATE SALDO --}}
    <div class="hidden fixed inset-0 z-[60] overflow-y-auto" id="updateSaldoModal" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal-enter">
                <div class="px-6 py-6 sm:px-8 border-b border-gray-50 flex items-center justify-between bg-amber-50/30">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-edit text-amber-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800" id="modal-title">Update Saldo Admin</h3>
                    </div>
                    <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                        onclick="closeModal()">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('admin.update-saldo', auth()->id()) }}" method="POST">
                    @csrf
                    <div class="px-6 py-8 sm:px-8">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 px-1">Saldo Baru
                            (Nominal Akhir)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-bold text-sm">Rp</span>
                            </div>
                            <input type="text" name="saldo_baru" id="saldo_baru"
                                class="block w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-900 font-bold focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all text-lg"
                                value="{{ $adminSaldo }}" required>
                        </div>
                        <p class="mt-3 text-[10px] text-gray-400 italic px-1">*Mengubah nominal ini akan menimpa saldo saat
                            ini secara permanen.</p>
                    </div>

                    <div class="px-6 py-6 sm:px-8 bg-gray-50/50 flex flex-col sm:flex-row gap-3">
                        <button type="button"
                            class="flex-1 px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all active:scale-95"
                            onclick="closeModal()">
                            Batalkan
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-amber-100 transition-all transform active:scale-95 flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes modalEnter {
            from {
                transform: scale(0.9) translateY(20px);
                opacity: 0;
            }

            to {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        .animate-modal-enter {
            animation: modalEnter 0.3s ease-out forwards;
        }

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

@section('scripts')
    <script>
        function closeModal() {
            document.getElementById('updateSaldoModal').classList.add('hidden');
        }

        // Toggle Modal manually for Tailwind compatibility
        document.querySelectorAll('[data-toggle="modal"]').forEach(el => {
            el.addEventListener('click', () => {
                const target = document.querySelector(el.dataset.target);
                if (target) target.classList.remove('hidden');
            });
        });

        // Format angka otomatis saat mengetik
        function formatRupiah(inputId) {
            document.getElementById(inputId).addEventListener('input', function (e) {
                let value = this.value.replace(/\D/g, "");
                this.value = new Intl.NumberFormat('id-ID').format(value);
            });
        }

        formatRupiah('jumlah');
        formatRupiah('saldo_baru');

        // Table Search
        document.getElementById('tableSearch').addEventListener('keyup', function () {
            let value = this.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
            });
        });
    </script>
@endsection