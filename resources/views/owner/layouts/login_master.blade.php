<!DOCTYPE html>
<html class="bg-black">
<head>
    <meta charset="UTF-8">
    <title>@yield("page_title")</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/custom.css') }}" rel="stylesheet" type="text/css" />
    <link rel="icon" type="text/css" href="{{ config('constants.favicon_icon') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('my_assets/iziToast.min.css') }}">
    <link href="{{ asset('my_assets/buttonLoader.css')}}" rel="stylesheet" type="text/css">
    <style>
        .bg-olive,
        .form-box .header
        {
            background:#d39a0f !important;
        }
        .form-box {
            width: 360px;
            margin: 90px auto 0 auto;
        }
        .form-password-view {
            position: absolute;
            right: 10px;
            top: 7px;
            z-index: 9;
        }
        .width-100{
            width: 100%;
        }
        .input-group .form-control{
            border: none;
        }
    </style>
   @include('admin.layouts.loader_css')

</head>
<body class="bg-black">

@yield('section')
    <script>
       var api_base_url = "{{ url('/api/admin') }}/";
        var owner_url = "{{url('/owner')}}/";
        var api_header = {
            "Content-Type": "application/json",
        };
    </script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('my_assets/jquery.buttonLoader.js') }}"></script>
        <script src="{{ asset('my_assets/common.js') }}"></script>
        <script src="{{ asset('my_assets/iziToast.min.js') }}"></script>
@yield('script')
<!-- @include('admin.layouts.loader_js') -->

</body>
</html>
