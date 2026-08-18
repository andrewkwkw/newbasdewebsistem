@extends('users.layouts.app')

@section('title', 'Dashboard')

@section('main')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Dashboard</h1>
                <p class="text-gray-500 mt-1 text-lg">Selamat datang kembali, <span
                        class="font-bold text-indigo-600">{{ auth()->user()->fullname ?? auth()->user()->name }}</span>!</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div
                    class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm text-sm font-medium text-gray-600 transition-shadow hover:shadow-md">
                    <i class="far fa-calendar-alt mr-2 text-indigo-500"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Column -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Hero Card -->
                <div
                    class="relative overflow-hidden bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl shadow-xl text-white transform transition duration-500 hover:scale-[1.02]">
                    <div
                        class="absolute inset-0 bg-[url('https://img.freepik.com/free-vector/white-abstract-background-design_23-2148825582.jpg')] opacity-10 mix-blend-overlay bg-cover bg-center">
                    </div>
                    <div class="relative p-8 flex flex-col items-center text-center">
                        <div class="p-4 bg-white/10 rounded-2xl backdrop-blur-sm mb-5 shadow-inner">
                            <i class="fas fa-microchip text-4xl text-white/90"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2 tracking-tight">Smart Trash Controller</h3>
                        <p class="text-indigo-100 text-sm mb-8 leading-relaxed max-w-xs mx-auto">Tekan tombol di bawah untuk
                            membuka tutup tempat sampah secara otomatis.</p>

                        <div id="trigger-area" class="w-full">
                            <button onclick="triggerDevice()" id="btn-trigger"
                                class="w-full group relative flex items-center justify-center px-6 py-4 bg-white text-indigo-600 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 active:scale-95">
                                <span
                                    class="absolute inset-0 rounded-xl bg-indigo-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                <i
                                    class="fas fa-power-off mr-3 relative z-10 group-hover:rotate-12 transition-transform duration-300"></i>
                                <span class="relative z-10">AKTIFKAN ALAT</span>
                            </button>
                            <div id="loading-text"
                                class="hidden mt-4 text-sm font-medium animate-pulse text-indigo-100 bg-white/20 px-4 py-2 rounded-full backdrop-blur-sm">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Menghubungkan...
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Points Card -->
                <div
                    class="bg-gradient-to-br from-orange-400 to-pink-500 rounded-3xl p-6 shadow-xl text-white relative overflow-hidden group">
                    <div
                        class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10 transition-transform duration-700 group-hover:scale-150">
                    </div>

                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-white/80 mb-1">Total Poin Anda</p>
                            <div class="flex items-baseline">
                                <span
                                    class="text-4xl font-extrabold tracking-tight">{{ number_format($points ?? 0, 0, ',', '.') }}</span>
                                <span class="ml-1 text-lg font-medium text-white/80">PTS</span>
                            </div>
                        </div>
                        <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md shadow-lg">
                            <i class="fas fa-crown text-xl text-yellow-300"></i>
                        </div>
                    </div>
                    <div
                        class="mt-8 flex items-center text-sm text-white/90 font-medium bg-white/10 p-3 rounded-xl backdrop-blur-sm">
                        <i class="fas fa-info-circle mr-2 text-white/80"></i>
                        <span>Tukarkan poin dengan hadiah menarik!</span>
                    </div>
                </div>
            </div>

            <!-- Right Column (History) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 h-full flex flex-col overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <div
                                class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center mr-3 text-indigo-600">
                                <i class="fas fa-history text-sm"></i>
                            </div>
                            Riwayat Aktivitas
                        </h3>
                        <a href="{{ route('user.history') }}"
                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">
                            Lihat Semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                    <div class="p-0 flex-1 overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu
                                    </th>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Aktivitas</th>
                                    <th
                                        class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                                        Poin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($trashLogs as $log)
                                    <tr class="group hover:bg-gray-50/80 transition-colors">
                                        <td class="px-6 py-4 align-middle whitespace-nowrap w-1/4">
                                            <div class="font-bold text-gray-800">
                                                {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d F, H:i') }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 flex items-center">
                                                <i class="far fa-clock mr-1 text-gray-400"></i>
                                                {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 align-middle">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-green-50 rounded-xl flex items-center justify-center text-green-600 mr-4 border border-green-100 shadow-sm">
                                                    <i class="fas fa-recycle text-lg"></i>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-800">Setor Botol</div>
                                                    <div class="text-xs text-gray-500 mt-0.5">Smart Trash #01</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 align-middle text-right">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200 shadow-sm">
                                                +{{ $log->points }} Poin
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-16 text-center text-gray-400">
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
                </div>
            </div>

        </div>
    </div>

    <!-- Real-time Status Modal -->
    <div id="status-modal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all">
            <div class="flex flex-col items-center text-center">
                <!-- Video Stream Area -->
                <div class="w-full bg-gray-100 rounded-xl overflow-hidden mb-6 relative aspect-video shadow-inner">
                    <img id="live-camera-feed" src="" alt="Kamera Live" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://placehold.co/600x400?text=Kamera+Mati';">
                    <!-- Indikator merah kecil (REC) -->
                    <div class="absolute top-3 right-3 flex items-center bg-black/50 px-2 py-1 rounded-md backdrop-blur-sm">
                        <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse mr-1"></div>
                        <span class="text-[10px] text-white font-bold">LIVE</span>
                    </div>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-2">Kamera Aktif</h3>
                <p class="text-gray-500 mb-4 text-sm">Harap letakkan sampah Anda di depan kamera.</p>
                
                <!-- Live Status Text Area -->
                <div class="w-full bg-gray-50 rounded-xl p-4 border border-gray-100 text-left">
                    <div class="flex items-center mb-2">
                        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse mr-2"></div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Live Status dari Alat</span>
                    </div>
                    <p id="live-status-text" class="text-gray-700 font-medium font-mono text-sm break-words">Menunggu respon Raspberry Pi...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- SweetAlert CDN --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    {{-- SCRIPT TRIGGER DEVICE --}}
    <script>
        let pollingInterval;
        const RASPBERRY_PI_IP = "192.168.249.169"; // Ganti jika IP Raspberry Pi berubah

        function pollDeviceStatus() {
            fetch("{{ url('/api/device-status') }}")
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'idle') {
                        // Alat sudah selesai
                        clearInterval(pollingInterval);
                        $('#status-modal').removeClass('flex').addClass('hidden');
                        $('#live-camera-feed').attr('src', ''); // Matikan stream video
                        
                        swal({
                            title: "Selesai!",
                            text: "Proses selesai. Poin Anda telah ditambahkan jika valid.",
                            icon: "success",
                            button: "Ok",
                        }).then(() => {
                            window.location.reload(); // Refresh untuk melihat poin baru
                        });
                    } else if (data.message) {
                        // Update log text
                        $('#live-status-text').text(data.message);
                    }
                })
                .catch(err => console.error("Polling error:", err));
        }

        function triggerDevice() {
            // 1. Ubah UI jadi Loading
            const btn = $('#btn-trigger');

            btn.prop('disabled', true);
            btn.find('span:last-child').text('Memproses...');
            btn.find('i').removeClass('fa-power-off').addClass('fa-circle-notch fa-spin');

            $('#loading-text').removeClass('hidden').addClass('flex');

            // 2. Kirim Request ke API Laravel
            const apiUrl = "{{ url('/api/trigger-device') }}";

            fetch(apiUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    user_id: "{{ auth()->user()->id }}"
                })
            })
                .then(async response => {
                    const isJson = response.headers.get('content-type')?.includes('application/json');
                    const data = isJson ? await response.json() : null;

                    if (!response.ok) {
                        const errorMsg = (data && data.message) || response.statusText;
                        throw new Error(errorMsg);
                    }
                    return data;
                })
                .then(data => {
                    // === SUKSES ===
                    if (data.status === 'success') {
                        // Buka modal real-time status dan set sumber video
                        $('#live-status-text').text("Menunggu respon Raspberry Pi...");
                        $('#live-camera-feed').attr('src', `http://${RASPBERRY_PI_IP}:5000/video_feed?t=` + new Date().getTime());
                        $('#status-modal').removeClass('hidden').addClass('flex');
                        
                        // Mulai polling setiap 1 detik
                        pollingInterval = setInterval(pollDeviceStatus, 1000);
                    }
                })
                .catch(error => {
                    // === GAGAL ===
                    console.error('Error Trigger:', error);

                    let pesanError = "Gagal menghubungkan ke alat.";
                    if (error.message.includes('sedang digunakan')) {
                        pesanError = "Alat sedang sibuk. Mohon tunggu sebentar.";
                    }

                    swal({
                        title: "Oops...",
                        text: pesanError,
                        icon: "error",
                        button: "Ok",
                    });
                })
                .finally(() => {
                    // 3. Kembalikan UI button seperti semula
                    setTimeout(() => {
                        btn.prop('disabled', false);
                        btn.find('span:last-child').text('AKTIFKAN ALAT');
                        btn.find('i').removeClass('fa-circle-notch fa-spin').addClass('fa-power-off');

                        $('#loading-text').removeClass('flex').addClass('hidden');
                    }, 1000);
                });
        }
    </script>
@endpush