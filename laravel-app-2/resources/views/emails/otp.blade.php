<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi ART-HUB</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #faf9f6; padding: 30px 0; margin: 0; width: 100%;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #faf9f6; padding: 20px;">
        <tr>
            <td align="center">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; max-width: 500px; width: 100%; box-shadow: 0 15px 35px rgba(139, 26, 42, 0.05); border: 1px solid rgba(197, 160, 40, 0.2);">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #8B1A2A, #5C0E19); padding: 45px 20px; border-bottom: 3px solid #C5A028;">
                            <h1 style="color: #fcd400; font-family: 'Georgia', serif; font-size: 26px; font-weight: bold; margin: 0; letter-spacing: 2px;">ART-HUB</h1>
                            <p style="color: rgba(255,255,255,0.8); font-size: 11px; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 4px;">Sanggar Cahaya Gumilang</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 35px;">
                            <h2 style="color: #1A1817; font-family: 'Georgia', serif; font-size: 20px; font-weight: bold; margin: 0 0 25px 0; text-align: center;">Otentikasi Keamanan</h2>
                            
                            <p style="font-size: 14px; color: #504442; line-height: 1.7; margin: 0 0 25px 0; text-align: center;">
                                Salam hangat,<br><br>
                                Kami menerima permintaan untuk kode verifikasi satu kali (OTP) guna mengakses sistem informasi ART-HUB. Silakan gunakan sandi berikut untuk melanjutkan:
                            </p>
                            
                            <!-- OTP Box -->
                            <div style="text-align: center; margin: 30px 0;">
                                <div style="display: inline-block; background-color: rgba(197, 160, 40, 0.04); padding: 20px 45px; border-radius: 6px; border: 1px solid rgba(197, 160, 40, 0.3);">
                                    <span style="font-family: 'Courier New', Courier, monospace; font-size: 34px; font-weight: bold; letter-spacing: 12px; color: #8B1A2A; margin-left: 12px;">
                                        {{ $otpCode }}
                                    </span>
                                </div>
                            </div>
                            
                            <p style="font-size: 13px; color: #847B78; line-height: 1.6; margin: 0 0 20px 0; text-align: center;">
                                Kode otentikasi ini bersifat rahasia dan hanya berlaku selama <strong style="color: #8B1A2A;">15 menit</strong>. Demi menjaga privasi dan keamanan Anda, jangan pernah membagikan kode ini kepada siapa pun.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: rgba(197, 160, 40, 0.03); padding: 25px 30px; text-align: center; border-top: 1px solid rgba(197, 160, 40, 0.1);">
                            <p style="font-size: 11px; color: #847B78; line-height: 1.5; margin: 0;">
                                Apabila Anda merasa tidak melakukan permintaan ini, mohon abaikan surel ini.
                            </p>
                            <p style="font-size: 11px; color: #1A1817; font-weight: bold; margin: 15px 0 0 0; text-transform: uppercase; letter-spacing: 1px;">
                                &copy; {{ date('Y') }} ART-HUB Sanggar Seni
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
