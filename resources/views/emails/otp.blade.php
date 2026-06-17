<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .header {
            background-color: #d70018;
            padding: 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }
        .otp-box {
            background-color: #f8f9fa;
            border: 2px dashed #d70018;
            border-radius: 6px;
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #d70018;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #777777;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>XanhStore</h1>
        </div>
        <div class="content">
            <p>Xin chào,</p>
            <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản XanhStore của mình. Dưới đây là mã OTP xác thực của bạn:</p>
            <div class="otp-box">
                {{ $otp }}
            </div>
            <p>Mã này có hiệu lực trong vòng <strong>10 phút</strong>. Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
            <p>Trân trọng,<br>Đội ngũ hỗ trợ XanhStore</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} XanhStore. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
