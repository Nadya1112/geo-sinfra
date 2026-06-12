<!DOCTYPE html>
<html>
<head>
    <style>
        .button {
            background-color: #5c56e1;
            border: none;
            color: white;
            padding: 12px 25px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
            font-weight: bold;
        }
    </style>
</head>
<body style="font-family: 'Inter', sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #1e1b4b;">Halo, {{ $name }}!</h2>
        <p>Kami menerima permintaan untuk mengatur ulang kata sandi akun **GEO-SINFRA** Anda.</p>
        <p>Silakan klik tombol di bawah ini untuk membuat sandi baru:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/reset-password/'.$token) }}" class="button" style="color: #ffffff;">
                ATUR ULANG KATA SANDI
            </a>
        </div>

        <p>Tautan ini akan kedaluwarsa dalam 5 menit. Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini.</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin-top: 40px;">
        <p style="font-size: 12px; color: #999; text-align: center;">
            &copy; 2026 Dinas Perumahan dan Kawasan Permukiman Kota Banjarmasin
        </p>
    </div>
</body>
</html>
