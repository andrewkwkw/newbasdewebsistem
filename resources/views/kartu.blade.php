<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Sampah Pintar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .card-member { width: 350px; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background-color: #0d6efd; color: white; padding: 20px; text-align: center; }
        .qr-area { background: white; padding: 30px; text-align: center; }
        .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 14px; color: #6c757d; }
    </style>
</head>
<body>

    <div class="card card-member">
        <div class="header">
            <h4 class="m-0">Smart Trash Member</h4>
            <small>Scan untuk Buang Sampah</small>
        </div>
        
        <div class="qr-area">
            @if(Auth::user()->qr_code)
                
                <div class="mb-3">
                    {!! QrCode::size(200)->generate(Auth::user()->qr_code) !!}
                </div>
                
                <h5 class="fw-bold">{{ Auth::user()->fullname }}</h5>
                <p class="text-muted mb-0">ID: {{ Auth::user()->qr_code }}</p>

            @else
                <div class="alert alert-warning">
                    Anda belum punya Kode QR.<br>
                    Silakan hubungi Admin.
                </div>
            @endif
        </div>

        <div class="footer">
            Saldo Poin: <strong>{{ Auth::user()->points }} Poin</strong>
        </div>
    </div>

</body>
</html>