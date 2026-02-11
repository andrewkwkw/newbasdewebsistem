<aside id="sidebar-wrapper"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full md:translate-x-0 bg-white border-r border-gray-200 shadow-sm">
    <div class="h-full flex flex-col">
        <!-- Brand -->
        <div class="h-16 flex items-center justify-center border-b border-gray-100 bg-white px-6">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                <img src="{{ asset('img/logo.webp') }}" class="h-10 w-auto" alt="Logo">
                <span class="text-xl font-bold text-gray-800 tracking-tight">BASADE<span
                        class="text-primary text-xs ml-1 font-medium bg-primary/10 px-1.5 py-0.5 rounded">ADMIN</span></span>
            </a>
        </div>

        <!-- Menu Section -->
        <div class="flex-1 overflow-y-auto py-6 px-4">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 px-3">Main Navigation</p>
            <ul class="space-y-1.5">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center p-3 text-sm font-semibold rounded-xl group transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i
                            class="fas fa-th-large w-5 h-5 flex items-center justify-center transition duration-75 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-gray-900' }}"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('jenis_sampah') }}"
                        class="flex items-center p-3 text-sm font-semibold rounded-xl group transition-all duration-200 {{ request()->routeIs('jenis_sampah') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i
                            class="fas fa-gift w-5 h-5 flex items-center justify-center transition duration-75 {{ request()->routeIs('jenis_sampah') ? 'text-white' : 'text-gray-400 group-hover:text-gray-900' }}"></i>
                        <span class="ml-3">Jenis Sampah</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.laporan') }}"
                        class="flex items-center p-3 text-sm font-semibold rounded-xl group transition-all duration-200 {{ request()->routeIs('admin.laporan') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i
                            class="fas fa-file-invoice w-5 h-5 flex items-center justify-center transition duration-75 {{ request()->routeIs('admin.laporan') ? 'text-white' : 'text-gray-400 group-hover:text-gray-900' }}"></i>
                        <span class="ml-3">Laporan</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Logout Button Section -->
        <div class="p-4 border-t border-gray-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center w-full p-3 text-sm font-bold text-red-600 rounded-xl hover:bg-red-50 transition-all duration-200 group">
                    <i
                        class="fas fa-sign-out-alt w-5 h-5 flex items-center justify-center group-hover:scale-110 transition-transform"></i>
                    <span class="ml-3">Sign Out</span>
                </button>
            </form>
        </div>
    </div>
</aside>