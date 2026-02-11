<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            background-color:whitesmoke; /* Latar belakang hitam */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .splash-container {
            text-align: center;
            animation: fadeIn 1.5s ease-in-out;
        }
        .logo {
            max-width: 150px; /* Sesuaikan ukuran logo Anda */
            height: auto;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>

    <div class="splash-container">
        {{-- Ganti 'assets/logo.png' dengan path ke logo Anda --}}
        <img src="{{ asset('assets/maroon-removebg-preview.png') }}" alt="Logo Aplikasi" class="logo">
        {{-- Tulisan di bawah logo --}}
        <p class="hosted-text">Colaboration & Hosted By Maroon Labkom</p>
    </div>

    <script>
        // Setelah 3 detik (3000 milidetik), arahkan ke halaman login
        setTimeout(function() {
            window.location.href = "{{ route('login') }}";
        }, 3000);
    </script>

</body>
</html>