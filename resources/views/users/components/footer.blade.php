<footer class="bg-white border-t border-gray-100 mt-12 py-8">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-center md:text-left mb-4 md:mb-0">
                <div class="flex items-center justify-center md:justify-start mb-2">
                    <img src="{{ asset('img/logo.webp') }}" alt="Logo" class="h-6 w-auto mr-2 opacity-80">
                    <span class="font-bold text-gray-700 text-lg tracking-tight">{{ env('APP_NAME') }}</span>
                    <span class="mx-2 text-gray-300">|</span>
                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">PT Resik Prima Teknolojia</span>
                </div>
                <div class="text-sm text-gray-500">
                    <p>&copy; {{ date('Y') }} PT Resik Prima Teknolojia. All rights reserved.<br>Developed by PT Resik Prima Teknolojia.</p>

                </div>
            </div>

            <div
                class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-6 text-sm text-gray-500 mt-4 md:mt-0">
                <a href="{{ route('privacy') }}" class="hover:text-indigo-600 transition-colors duration-200">Kebijakan Privasi</a>
                <a href="{{ route('terms') }}" class="hover:text-indigo-600 transition-colors duration-200">Syarat & Ketentuan</a>
                <div class="hidden md:block w-px h-4 bg-gray-300"></div>
                <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded text-gray-400">v1.0.0</span>
            </div>
        </div>
    </div>
</footer>