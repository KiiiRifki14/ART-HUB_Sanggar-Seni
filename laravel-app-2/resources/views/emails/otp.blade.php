<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi ART-HUB</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f3f1; padding: 20px 0; margin: 0; width: 100%;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f4f3f1; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; max-width: 600px; width: 100%; box-shadow: 0 10px 25px rgba(54,31,26,0.08);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #361f1a; padding: 40px 20px; border-bottom: 4px solid #fcd400;">
                            <h1 style="color: #fcd400; font-family: 'Georgia', serif; font-size: 28px; font-weight: bold; margin: 0; letter-spacing: 2px;">ART-HUB</h1>
                            <p style="color: #d4c3bf; font-size: 14px; margin: 5px 0 0 0; text-transform: uppercase; letter-spacing: 3px;">Sanggar Cahaya Gumilang</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #361f1a; font-size: 20px; font-weight: 600; margin: 0 0 20px 0; text-align: center;">Kode Verifikasi Keamanan</h2>
                            
                            <p style="font-size: 15px; color: #504442; line-height: 1.6; margin: 0 0 25px 0; text-align: center;">
                                Halo! Anda baru saja meminta kode verifikasi untuk mengakses akun ART-HUB. Silakan gunakan kode otentikasi di bawah ini:
                            </p>
                            
                            <!-- OTP Box -->
                            <div style="text-align: center; margin: 35px 0;">
                                <div style="display: inline-block; background-color: #fff8d6; padding: 20px 40px; border-radius: 12px; border: 1px dashed #fcd400;">
                                    <span style="font-family: 'Courier New', Courier, monospace; font-size: 38px; font-weight: 800; letter-spacing: 12px; color: #361f1a; margin-left: 12px;">
                                        {{ $otpCode }}
                                    </span>
                                </div>
                            </div>
                            
                            <p style="font-size: 14px; color: #827471; line-height: 1.6; margin: 0 0 20px 0; text-align: center;">
                                Kode ini hanya berlaku selama <strong style="color: #361f1a;">15 menit</strong>. Jangan bagikan kode ini kepada siapa pun demi keamanan akun Anda.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #faf9f6; padding: 25px 30px; text-align: center; border-top: 1px solid #efeeeb;">
                            <p style="font-size: 12px; color: #827471; line-height: 1.5; margin: 0;">
                                Jika Anda tidak merasa meminta kode ini, mohon abaikan email ini atau hubungi pengurus sanggar.
                            </p>
                            <p style="font-size: 12px; color: #827471; font-weight: bold; margin: 15px 0 0 0;">
                                &copy; {{ date('Y') }} ART-HUB Sanggar Seni.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
