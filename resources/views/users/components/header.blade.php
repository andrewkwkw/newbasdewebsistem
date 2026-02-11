<nav id="navbar-main"
    class="fixed top-0 z-30 w-full right-0 bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
    <div class="px-6 py-3 lg:px-6 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <!-- Logo & Brand -->
                <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-2 mr-6">
                    <img src="{{ asset('img/logo.webp') }}" class="h-8 w-auto" alt="Logo">
                    <span class="text-xl font-bold text-gray-800 tracking-tight">BASADE</span>
                </a>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden md:flex space-x-1">
                    <a href="{{ route('user.dashboard') }}"
                        class="px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('user.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        Dashboard
                    </a>
                    <!-- Add more links here if needed -->
                </div>
            </div>

            <div class="flex items-center">
                {{-- Notifications / User Menu --}}
                <div class="flex items-center ml-3">
                    <div class="relative ml-3">
                        <button type="button"
                            class="flex items-center text-sm rounded-full focus:ring-4 focus:ring-gray-100 transition-all"
                            id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm"
                                src="{{ asset('img/avatar/avatar-1.png') }}" alt="User photo">
                            <span
                                class="hidden md:block ml-3 font-medium text-gray-700">{{ auth()->user()->fullname ?? 'User' }}</span>
                            <i class="fas fa-chevron-down ml-2 text-xs text-gray-400 hidden md:block transition-transform duration-200"
                                id="user-menu-chevron"></i>
                        </button>

                        <!-- Dropdown menu -->
                        <div class="hidden absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-lg bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none transform transition-all duration-200 ease-out scale-95 opacity-0"
                            id="user-dropdown" role="menu" aria-orientation="vertical"
                            aria-labelledby="user-menu-button" tabindex="-1">

                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm text-gray-900 font-bold truncate">{{ auth()->user()->fullname }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>

                            <div class="border-t border-gray-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                    role="menuitem" tabindex="-1">
                                    <i class="fas fa-sign-out-alt mr-2 w-4"></i> Sign out
                                </button>
                            </form>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const btn = document.getElementById('user-menu-button');
                            const dropdown = document.getElementById('user-dropdown');
                            const chevron = document.getElementById('user-menu-chevron');
                            let isOpen = false;

                            function toggleDropdown() {
                                isOpen = !isOpen;
                                if (isOpen) {
                                    dropdown.classList.remove('hidden');
                                    // Small delay to allow display:block to apply before transition
                                    setTimeout(() => {
                                        dropdown.classList.remove('scale-95', 'opacity-0');
                                        dropdown.classList.add('scale-100', 'opacity-100');
                                    }, 10);
                                    if (chevron) chevron.classList.add('rotate-180');
                                } else {
                                    dropdown.classList.remove('scale-100', 'opacity-100');
                                    dropdown.classList.add('scale-95', 'opacity-0');
                                    if (chevron) chevron.classList.remove('rotate-180');
                                    setTimeout(() => {
                                        dropdown.classList.add('hidden');
                                    }, 200); // Wait for transition
                                }
                            }

                            if (btn && dropdown) {
                                btn.addEventListener('click', (e) => {
                                    e.stopPropagation();
                                    toggleDropdown();
                                });

                                // Close on click outside
                                document.addEventListener('click', (e) => {
                                    if (isOpen && !btn.contains(e.target) && !dropdown.contains(e.target)) {
                                        toggleDropdown();
                                    }
                                });
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</nav>