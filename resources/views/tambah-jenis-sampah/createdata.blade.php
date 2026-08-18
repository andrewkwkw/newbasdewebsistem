@extends('admin.layouts.app')

@section('title', 'Setor & Tukar Poin')

@section('main')
    <div class="animate-fade-in space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <a href="{{ route('jenis_sampah.index') }}" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Setor & Tukar Poin</h1>
                </div>
                <p class="text-sm text-gray-500">Pilih mode setoran karungan (Kg) atau penukaran poin dari Smart Trash.</p>
            </div>
            <div class="flex items-center space-x-3 text-sm text-gray-500">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('jenis_sampah.index') }}" class="hover:text-indigo-600 font-medium">Jenis Sampah</a>
                <span>/</span>
                <span class="text-gray-800 font-medium">Setor & Tukar</span>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl flex items-center justify-between shadow-sm animate-slide-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-emerald-500 mr-3 text-lg"></i>
                    <p class="text-sm text-emerald-800 font-medium">{{ session('success') }}</p>
                </div>
                <button type="button" class="text-emerald-500 hover:text-emerald-700 transition-colors" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl flex items-center justify-between shadow-sm animate-slide-in">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-rose-500 mr-3 text-lg"></i>
                    <p class="text-sm text-rose-800 font-medium">{{ session('error') }}</p>
                </div>
                <button type="button" class="text-rose-500 hover:text-rose-700 transition-colors" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm animate-slide-in">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-rose-500 mr-3 text-lg mt-0.5"></i>
                    <div>
                        <h3 class="text-sm text-rose-800 font-bold mb-1">Terdapat Kesalahan:</h3>
                        <ul class="list-disc list-inside text-sm text-rose-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Card -->
            <div class="{{ isset($latestTransaction) ? 'lg:col-span-2' : 'lg:col-span-3' }}">
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
                    
                    <!-- TAB BUTTONS -->
                    <div class="flex bg-gray-100 p-1 rounded-2xl mb-8">
                        <button type="button" id="tabPoin" class="flex-1 py-3 text-sm font-bold rounded-xl transition-all shadow-sm bg-white text-indigo-600">
                            <i class="fas fa-gift mr-2"></i> Tukar Poin Smart Trash
                        </button>
                        <button type="button" id="tabKg" class="flex-1 py-3 text-sm font-bold rounded-xl transition-all text-gray-500 hover:text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-balance-scale mr-2"></i> Setor Manual (Kg)
                        </button>
                    </div>

                    <form method="POST" action="{{ route('makedata') }}" id="formTransaksi" class="space-y-5">
                        @csrf
                        <input type="hidden" name="tipe_setoran" id="tipe_setoran" value="poin">

                        <!-- 1. PILIH USER -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Warga <span class="text-rose-500">*</span></label>
                            <select name="user_ids[]" id="user_id" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium" required>
                                <option value="" disabled selected>-- Cari Warga --</option>
                                @foreach($user as $u)
                                    <option value="{{ $u->id }}" data-poin="{{ $u->points ?? 0 }}">{{ $u->username }} - {{ $u->fullname }}</option>
                                @endforeach
                            </select>
                            
                            <!-- Sisa Poin Indicator (Only show on Poin Tab) -->
                            <div id="sisa_poin_container" class="mt-2 text-sm text-gray-500 hidden">
                                Sisa Poin Warga Ini: <strong id="sisa_poin_label" class="text-indigo-600 text-lg">0 Poin</strong>
                            </div>
                        </div>

                        <!-- 2. PILIH JENIS SAMPAH -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Sampah <span class="text-rose-500">*</span></label>
                            <select name="jenis_sampah_id" id="jenis_sampah_id" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium" required>
                                <option value="" disabled selected>-- Pilih Jenis --</option>
                                @foreach ($jenisSampah as $sampah)
                                    <option value="{{ $sampah->id }}" 
                                            data-harga-kg="{{ $sampah->harga_per_kg }}"
                                            data-harga-poin="{{ $sampah->harga_per_poin }}">
                                        {{ $sampah->nama_sampah }} (Rp {{ number_format($sampah->harga_per_poin, 0, ',', '.') }}/Poin | Rp {{ number_format($sampah->harga_per_kg, 0, ',', '.') }}/Kg)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 3. INPUT JUMLAH -->
                        <div>
                            <label id="label_input_jumlah" class="block text-sm font-bold text-gray-700 mb-2">Poin yang Ditukar <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="number" step="0.01" name="jml_sampah" id="jml_sampah" class="block w-full pl-4 pr-16 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="0" required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span id="satuan_input_jumlah" class="text-gray-500 font-bold">Poin</span>
                                </div>
                            </div>
                            <button type="button" id="btnMaxPoin" class="mt-2 text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2 py-1 rounded hidden">Tukar Semua Poin</button>
                        </div>

                        <!-- 4. HASIL NOMINAL -->
                        <div class="pt-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Total Nominal yang Ditambahkan ke Saldo</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <span class="text-emerald-600 font-bold text-lg">Rp</span>
                                </div>
                                <input type="text" id="nominal" class="block w-full pl-14 pr-4 py-4 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 font-extrabold text-xl focus:outline-none" readonly placeholder="0">
                            </div>
                            <p id="keterangan_nominal" class="text-xs text-gray-400 mt-2 font-medium"><i class="fas fa-info-circle mr-1"></i>Otomatis dihitung: (Jumlah Poin) x (Harga per 1 Poin).</p>
                        </div>

                        <div class="pt-4">
                            <button type="submit" id="btnSubmit" class="w-full inline-flex items-center justify-center px-6 py-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-200 transition-all transform active:scale-[0.98]">
                                <i class="fas fa-check-circle mr-2 text-lg"></i> Proses Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card Transaksi Terakhir -->
            @if(isset($latestTransaction))
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-3xl p-6 md:p-8 shadow-xl text-white relative overflow-hidden h-full flex flex-col justify-between group">
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-700"></div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-6 relative z-10">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-history text-white text-xl"></i>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-widest bg-white/20 px-3 py-1.5 rounded-full backdrop-blur-sm">Terbaru</span>
                        </div>
                        
                        <h3 class="text-white/90 text-sm font-bold mb-5 tracking-wide">Riwayat Setoran Warga Ini</h3>
                        
                        <div class="space-y-4 relative z-10">
                            <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/10">
                                <p class="text-xs text-white/70 uppercase tracking-wider mb-1">User</p>
                                <p class="font-bold text-lg">{{ $latestTransaction->user->fullname ?? 'Unknown' }}</p>
                            </div>
                            <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/10">
                                <p class="text-xs text-white/70 uppercase tracking-wider mb-1">Jenis Sampah</p>
                                <p class="font-bold text-lg">{{ $latestTransaction->jenisSampah->nama_sampah ?? '-' }}</p>
                            </div>
                            <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/10">
                                <p class="text-xs text-white/70 uppercase tracking-wider mb-1">Kuantitas</p>
                                <p class="font-bold text-lg">{{ $latestTransaction->berat }}</p>
                            </div>
                            <div class="bg-white/20 p-5 rounded-2xl backdrop-blur-md border border-white/20 shadow-inner mt-2">
                                <p class="text-xs text-white/90 uppercase tracking-wider mb-1">Total Diberikan</p>
                                <div class="flex items-baseline">
                                    <span class="text-white/80 font-bold mr-1">Rp</span>
                                    <span class="font-extrabold text-3xl tracking-tight">{{ number_format($latestTransaction->amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .animate-slide-in { animation: slideIn 0.4s ease-out forwards; }
        @keyframes slideIn { from { transform: translateY(-10px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let mode = 'poin'; // default mode
            let maxPoin = 0;

            // --- UI TAB LOGIC ---
            function setMode(newMode) {
                mode = newMode;
                $('#tipe_setoran').val(mode);
                $('#jml_sampah').val('');
                hitungTotal();

                if (mode === 'poin') {
                    // Styling Tabs
                    $('#tabPoin').removeClass('text-gray-500 hover:text-gray-700 hover:bg-gray-50').addClass('bg-white text-indigo-600 shadow-sm');
                    $('#tabKg').removeClass('bg-white text-indigo-600 shadow-sm').addClass('text-gray-500 hover:text-gray-700 hover:bg-gray-50');
                    
                    // UI Elements
                    $('#label_input_jumlah').html('Poin yang Ditukar <span class="text-rose-500">*</span>');
                    $('#satuan_input_jumlah').text('Poin');
                    $('#keterangan_nominal').html('<i class="fas fa-info-circle mr-1"></i>Otomatis dihitung: (Jumlah Poin) x (Harga per 1 Poin).');
                    $('#sisa_poin_container').removeClass('hidden');
                    $('#btnMaxPoin').removeClass('hidden');
                } else {
                    // Styling Tabs
                    $('#tabKg').removeClass('text-gray-500 hover:text-gray-700 hover:bg-gray-50').addClass('bg-white text-indigo-600 shadow-sm');
                    $('#tabPoin').removeClass('bg-white text-indigo-600 shadow-sm').addClass('text-gray-500 hover:text-gray-700 hover:bg-gray-50');
                    
                    // UI Elements
                    $('#label_input_jumlah').html('Berat Timbangan <span class="text-rose-500">*</span>');
                    $('#satuan_input_jumlah').text('Kg');
                    $('#keterangan_nominal').html('<i class="fas fa-info-circle mr-1"></i>Otomatis dihitung: (Berat Kg) x (Harga per 1 Kg) x 96%. (4% potongan Desa)');
                    $('#sisa_poin_container').addClass('hidden');
                    $('#btnMaxPoin').addClass('hidden');
                }
            }

            $('#tabPoin').on('click', () => setMode('poin'));
            $('#tabKg').on('click', () => setMode('kg'));

            // Initial UI setup
            setMode('poin');

            // --- USER SELECTION & POIN MAX ---
            $('#user_id').on('change', function() {
                let selectedOption = $(this).find('option:selected');
                maxPoin = parseFloat(selectedOption.data('poin')) || 0;
                
                let hasilFormat = new Intl.NumberFormat('id-ID').format(maxPoin);
                $('#sisa_poin_label').text(hasilFormat + " Poin");

                // Restrict max poin if mode is poin
                if(mode === 'poin') {
                    let inputPoin = parseFloat($('#jml_sampah').val()) || 0;
                    if (inputPoin > maxPoin) {
                        $('#jml_sampah').val(maxPoin);
                    }
                }
                hitungTotal();
            });

            $('#btnMaxPoin').on('click', function() {
                if(maxPoin > 0 && mode === 'poin') {
                    $('#jml_sampah').val(maxPoin);
                    hitungTotal();
                }
            });

            // --- KALKULASI TOTAL ---
            function hitungTotal() {
                let selectedOption = $('#jenis_sampah_id option:selected');
                
                // Get right price
                let rawHarga = mode === 'poin' ? selectedOption.data('harga-poin') : selectedOption.data('harga-kg');
                let hargaAcuan = parseFloat(rawHarga);
                
                let rawInput = $('#jml_sampah').val();
                let jumlah = parseFloat(rawInput);

                // Validation Poin
                if (mode === 'poin' && jumlah > maxPoin) {
                    jumlah = maxPoin;
                    $('#jml_sampah').val(jumlah);
                    alert("Jumlah poin yang ditukar tidak boleh melebihi sisa poin warga.");
                }

                if (!isNaN(hargaAcuan) && !isNaN(jumlah) && jumlah > 0) {
                    let total = 0;
                    if (mode === 'poin') {
                        total = jumlah * hargaAcuan; // Poin tidak kena potongan 96%? Atau kena? Di instruksi: poin * harga. Let's make it 100% full.
                    } else {
                        total = (jumlah * hargaAcuan) * 0.96; // Setor manual kena potongan 96%
                    }
                    
                    let hasilFormat = new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(total);
                    $('#nominal').val(hasilFormat);
                } else {
                    $('#nominal').val('');
                }
            }

            $('#jenis_sampah_id').on('change', hitungTotal);
            $('#jml_sampah').on('input keyup', hitungTotal);

            // --- FORM SUBMIT ---
            $('#formTransaksi').on('submit', function(e) {
                if (this.checkValidity()) {
                    if (mode === 'poin') {
                        let jumlah = parseFloat($('#jml_sampah').val());
                        if (jumlah > maxPoin) {
                            alert("Jumlah poin melebihi sisa poin warga!");
                            e.preventDefault();
                            return false;
                        }
                    }
                    let btn = $('#btnSubmit');
                    btn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Memproses Transaksi...');
                    btn.prop('disabled', true);
                    return true;
                }
            });
        });
    </script>
@endpush
