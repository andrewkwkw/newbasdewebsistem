<footer class="bg-white border-t border-gray-100 mt-auto py-6">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
            <div class="flex items-center space-x-2 mb-4 md:mb-0">
                <span class="font-bold text-gray-700 tracking-tight">{{ env('APP_NAME') }}<span
                        class="text-xs ml-1 font-medium bg-gray-100 px-1.5 py-0.5 rounded text-gray-400">ADMIN</span></span>
                <span class="mx-2 text-gray-300">|</span>
                <span>Developed by PT Resik Prima Teknolojia.</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('privacy') }}" class="hover:text-emerald-600 transition-colors">Privacy Policy</a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('terms') }}" class="hover:text-emerald-600 transition-colors">Terms of Service</a>
                <div class="hidden md:block w-px h-4 bg-gray-300"></div>
                <p>&copy; {{ date('Y') }} PT Resik Prima Teknolojia. All rights reserved.</p>
                <div class="hidden md:block w-px h-4 bg-gray-300"></div>
                <span class="text-xs font-mono bg-gray-50 px-2 py-1 rounded text-gray-400">v1.0.0</span>
            </div>
        </div>
    </div>
</footer>