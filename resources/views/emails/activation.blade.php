<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f7f8fc; padding: 40px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .header { background-color: #1a1f36; padding: 30px; text-align: center; color: #ffffff; }
        .content { padding: 40px 30px; color: #12172a; line-height: 1.6; }
        .button { display: inline-block; padding: 14px 32px; background-color: #e8457a; color: #ffffff; text-decoration: none; border-radius: 50px; font-weight: bold; margin-top: 20px; }
        .footer { background-color: #f7f8fc; padding: 20px; text-align: center; font-size: 12px; color: #a0a8c0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Welcome to SimpleNote!</h2>
        </div>
        <div class="content">
            <p>Xin chào <strong>{{ $user->display_name }}</strong>,</p>
            <p>Cảm ơn bạn đã đăng ký tài khoản tại SimpleNote. Để hoàn tất việc đăng ký và bảo vệ tài khoản của bạn, vui lòng xác nhận địa chỉ email bằng cách nhấn vào nút dưới đây:</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/activate/' . $user->activation_token) }}" class="button">Kích hoạt tài khoản ngay</a>
            </div>
            
            <p style="margin-top: 30px; font-size: 13px; color: #7a8099;">Nếu bạn không tạo tài khoản này, xin vui lòng bỏ qua email.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SimpleNote. All rights reserved.
        </div>
    </div>
</body>
</html>