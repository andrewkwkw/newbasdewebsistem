<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title') &mdash; {{ env('APP_NAME') }}</title>
    <link rel="shortcut icon" href="{{asset('img/logo.webp')}}" type="image/x-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @stack('style')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans antialiased text-gray-800">
    <div id="app" class="flex min-h-screen">
        <!-- Sidebar -->
        @include('admin.components.sidebar')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col transition-all duration-300 ease-in-out ml-0 md:ml-64 space-y-6"
            id="main-content">
            <!-- Header -->
            @include('admin.components.header')

            <!-- Main Content -->
            <main class="flex-1 px-6 pt-24 pb-6">
                @yield('main')
            </main>

            <!-- Footer -->
            @include('admin.components.footer')
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/popper.js/dist/umd/popper.js') }}"></script>
    <script src="{{ asset('library/tooltip.js/dist/umd/tooltip.js') }}"></script>

    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.querySelector('[data-toggle="sidebar"]');
            const sidebar = document.getElementById('sidebar-wrapper');
            const mainContent = document.getElementById('main-content');
            const navbar = document.getElementById('navbar-main');

            if (toggleButton && sidebar) {
                toggleButton.addEventListener('click', function (e) {
                    e.preventDefault();

                    const isDesktop = window.innerWidth >= 768;

                    if (isDesktop) {
                        sidebar.classList.toggle('md:translate-x-0');
                        if (mainContent) mainContent.classList.toggle('md:ml-64');
                        if (navbar) navbar.classList.toggle('md:w-[calc(100%-16rem)]');
                    } else {
                        sidebar.classList.toggle('-translate-x-full');
                    }
                });

                document.addEventListener('click', function (e) {
                    const isDesktop = window.innerWidth >= 768;
                    if (!isDesktop && !sidebar.contains(e.target) && !toggleButton.contains(e.target)) {
                        if (!sidebar.classList.contains('-translate-x-full')) {
                            sidebar.classList.add('-translate-x-full');
                        }
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>