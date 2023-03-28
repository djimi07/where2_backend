<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
</head>

<body>
    <div style="width:100%;max-width:600px;margin:0 auto;font-family: lato,trebuchet ms,helvetica,arial, sans-serif;">

        <div style="padding:30px 20px;text-align: center">
            <img src="{{ config('constants.logo_image') }}" alt=""
                style="width:150px;max-width: 250px;display: inline-block;">
        </div>
        <div style="padding:15px 15px 0px 15px">
            <p
                style="text-align: center;margin: 0;padding: 0;display: block;font-size: 22px;font-style: normal;font-weight: normal;line-height: 100%;letter-spacing: normal;color:#000;">
                @if(isset($title)){{ $title }}@endif
            </p>
        </div>
        <div style="padding:0 15px 15px 15px">
            @if(isset($user_name) && !empty($user_name))
            <p style="font-weight: 500;margin-bottom: 5px">Hey {{ $user_name }},</p>
            @endif

            <p style="color:#000;font-size:14px">
                @if(isset($text) && !empty($text)){{ $text }}@endif
            </p>

            <p style="color:#000;font-size:14px">
                Mobile Number : @if(isset($mobile) && !empty($mobile)){{ $mobile }}@endif 
            </p>
            <p style="color:#000;font-size:14px">
                Password : @if(isset($password) && !empty($password)){{ $password }}@endif
            </p>


            <p style="color:#000;font-size:14px">
                Click on the link below to sign in into the platform
            </p>
        </div>
        @if(isset($link) && !empty($link))
        <div style="text-align: center;padding: 0 15px">
            <a href="{{ $link }}" target="_blank"
                style="background-color:#3caa46;border: 0px solid #fff;border-radius: 5px;color: #fff;display: inline-block;font-weight: bold;text-align: center;text-decoration: none;width: auto;margin: 0 auto;padding: 10px 10px;width: 100%;font-size: 20px;">
                Click
            </a>
        </div>
        @endif
        <div style="padding:0 15px 15px 15px">
            <p style="color:#000;font-size:14px">
                If you need any assistance, please write us back on (client id)
            </p>
        </div>
        <div style="padding:25px 15px 25px 15px;border-top:2px solid #3caa46">
            <p style="width:100%;max-width:500px;margin:0 auto 25px auto;font-size:12px;text-align:center;color:#000">
                {{ config('constants.copyright_text') }}</p>

        </div>
    </div>
</body>

</html>