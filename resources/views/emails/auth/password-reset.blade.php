<!DOCTYPE html>
<html>
<head>
    <title>{{ env('MAIL_FROM_NAME') }}</title>
</head>
<body style="background: #DDDDDD; padding-top: 20px">
<div class="main" style="width: 600px; margin: 0 auto; background: #FFF; padding: 36px">
    <div>
        You are receiving this email because we received a password reset request for your account at&nbsp;
        <b style="color: #2585B2">
            <a href="{{url('')}}">{{ env('MAIL_FROM_NAME') }}</a>
        </b>.&nbsp;
        Please click the button below to reset your password.
    </div>
    <div style="text-align: center; margin: 10px 0">
        <a
            style="background: #2585B2; color: #FFF; padding: 8px 16px; display: inline-block; text-decoration: none"
            href="{{ get_app_url($type) . '/password/reset/' . $token . '?email=' . $email }}"
        >
            Reset password
        </a>
    </div>
    <div>
        The password reset link is only valid for 1 hour. If you did not request a password reset, no further action is
        required.
    </div>
</div>
</body>
</html>
