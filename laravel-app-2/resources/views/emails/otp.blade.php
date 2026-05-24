<!DOCTYPE html>
<html>
<head>
    <title>Kode Verifikasi</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="background-color: #fff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #333; text-align: center;">ART-HUB Sanggar Seni</h2>
        <p style="font-size: 16px; color: #555;">Halo,</p>
        <p style="font-size: 16px; color: #555;">Berikut adalah kode verifikasi Anda. Kode ini berlaku selama 15 menit.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span style="display: inline-block; font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #4F46E5; background-color: #F3F4F6; padding: 15px 30px; border-radius: 8px;">
                {{ $otpCode }}
            </span>
        </div>

        <p style="font-size: 14px; color: #777;">Jika Anda tidak meminta kode ini, abaikan saja email ini.</p>
        <p style="font-size: 14px; color: #777; margin-top: 30px;">Salam,<br>Tim ART-HUB Sanggar Seni</p>
    </div>
</body>
</html>
