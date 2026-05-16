<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kích hoạt tài khoản</title>
</head>

<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 20px;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff;border-radius:10px;overflow:hidden;">

                    <!-- Header -->
                    <tr>
                        <td align="center"
                            style="background:#4f46e5;padding:30px;color:white;">
                            
                            <h1 style="margin:0;font-size:28px;">
                                SimpleNote
                            </h1>

                            <p style="margin-top:8px;font-size:14px;">
                                Welcome to SimpleNote
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:40px 30px;color:#333;">

                            <h2 style="margin-top:0;">
                                Xin chào {{ $user->display_name }},
                            </h2>

                            <p style="line-height:1.7;">
                                Cảm ơn bạn đã đăng ký tài khoản tại
                                <strong>SimpleNote</strong>.
                            </p>

                            <p style="line-height:1.7;">
                                Vui lòng nhấn nút bên dưới để kích hoạt tài khoản.
                            </p>

                            <!-- Button -->
                            <div style="text-align:center;margin:35px 0;">

                                <a href="{{ url('/activate/' . $user->activation_token) }}"
                                    style="
                                        background:#4f46e5;
                                        color:white;
                                        text-decoration:none;
                                        padding:14px 30px;
                                        border-radius:6px;
                                        display:inline-block;
                                        font-weight:bold;
                                    ">
                                    Kích hoạt tài khoản
                                </a>

                            </div>

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>