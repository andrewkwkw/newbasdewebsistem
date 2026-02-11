<nav id="navbar-main"
    class="fixed top-0 z-30 w-full md:w-[calc(100%-16rem)] right-0 bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
    <div class="px-6 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <button data-toggle="sidebar" type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div class="hidden md:flex flex-col ml-4">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Admin Panel</h2>
                    <p class="text-[10px] text-gray-400 font-medium">Monitoring & Management</p>
                </div>
            </div>

            <div class="flex items-center">
                <div class="flex items-center ml-3">
                    <div class="relative ml-3">
                        <button type="button"
                            class="flex items-center text-sm rounded-full focus:ring-4 focus:ring-gray-100 transition-all group"
                            id="user-menu-button" aria-expanded="false">
                            <span class="sr-only">Open user menu</span>
                            <div
                                class="flex items-center space-x-3 p-1 pr-3 rounded-full hover:bg-gray-50 transition-colors">
                                <img class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm"
                                    src="{{ asset('img/avatar/avatar-4.png') }}" alt="User photo">
                                <span class="hidden md:block text-sm font-bold text-gray-700">Hi,
                                    {{ auth()->user()->fullname }}</span>
                                <i class="fas fa-chevron-down text-[10px] text-gray-400 transition-transform duration-200 group-hover:text-gray-600"
                                    id="user-menu-chevron"></i>
                            </div>
                        </button>

                        <!-- Dropdown menu -->
                        <div class="hidden absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-2xl bg-white p-2 shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none transform transition-all duration-200 ease-out scale-95 opacity-0"
                            id="user-dropdown">

                            <div class="px-3 py-3 mb-2 border-b border-gray-50">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Signed in as
                                </p>
                                <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->fullname }}</p>
                                <p class="text-[10px] font-medium text-gray-400 truncate">{{ auth()->user()->email }}
                                </p>
                            </div>


                            <div class="border-t border-gray-50 my-2"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center px-4 py-2.5 text-sm font-bold text-red-600 rounded-xl hover:bg-red-50 transition-all group">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center mr-3 group-hover:bg-red-100 transition-colors">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </div>
                                    Sign Out
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
                                    }, 200);
                                }
                            }

                            if (btn && dropdown) {
                                btn.addEventListener('click', (e) => {
                                    e.stopPropagation();
                                    toggleDropdown();
                                });
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