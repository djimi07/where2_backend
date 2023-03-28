
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield("page_title")</title>
    <link rel="icon" type="text/css" href="{{ config('constants.logo_image') }}" type="image/x-icon">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('adminLteTheme/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminLteTheme/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminLteTheme/bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminLteTheme/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminLteTheme/dist/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminLteTheme/bower_components/morris.js/morris.css') }}">
    <link rel="stylesheet" href="{{ asset('adminLteTheme/bower_components/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('adminLteTheme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminLteTheme/plugins/iCheck/all.css')}}">
    <link rel="stylesheet" href="{{ asset('adminLteTheme/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('adminLteTheme/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.css"/>
    <script src="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.css"/>
    <link rel="stylesheet" href="{{ asset('adminLteTheme/dist/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('my_assets/iziToast.min.css') }}">
    <link href="{{ asset('my_assets/buttonLoader.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    @yield('style')
    <!-- @include('admin.layouts.loader_css') -->
    <link rel="stylesheet" href="{{ asset('assets/css/style-custom.css') }}">

    <style>
        .select2-dropdown {
            z-index: 99999;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #00c0ef!important;
        }
        .select2-results__option[aria-selected] {
            font-size: 14px;
        }
    </style>

</head>
<body class="hold-transition skin-blue sidebar-mini @if(isset($auth_user->sidebar_toggle) && $auth_user->sidebar_toggle == 0) sidebar-collapse @endif" >
<div class="wrapper">
    @include('admin.layouts.header')
    @include('admin.layouts.sidebar')
    <div class="content-wrapper">
        @yield('content')
    </div>
    <footer class="main-footer">
        <a id="go-to-top-btn" href="javascript:void(0);" class="btn btn-primary go-to-top-btn"><i class="fa fa-chevron-up"></i></a>
        <strong>{{ config('constants.copyright_text') }}</strong> All rights
        reserved.
    </footer>
</div>

<script src="{{ asset('adminLteTheme/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('adminLteTheme/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button);

    var base_url = '{{ url('/') }}/';
    var api_url = "{{ url('/api/admin') }}/";
    var __token = "Bearer "+window.localStorage.getItem('auth_api_token');
//    console.log(__token);
  //  var __token = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiOGQ2NTNiOWYzZDRhYThjNzA3MWI0ZTI4M2MxYWM2YzU4ZDJhZWI3N2MzNzhjZGZhZGU4OTRhOWU0NjE2ODgxOTdkYjhmNjZjMjQ0MWMzMzUiLCJpYXQiOjE1ODQ1MjIzOTUsIm5iZiI6MTU4NDUyMjM5NSwiZXhwIjoxNjE2MDU4Mzk0LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.aoTGt5iZfvaAMkZcM_SU3rDq8b99IBJhZ3ZGTKyK2k04wx9IlevmA45lMxLDuTEPZ-qKtezjgKZEra_mDoRMUUw18GwU5EhHqsn02EflkjjbzUQpYZbFCNEX7ei-FGrVoSfhX5V6Lhv692V9FpiNKWSQaQG8-uQYGLcIfsclbB02BTzQTlkjm7UKmhemRQiTsxJlnKcbv-1oIegiyYCzlAFhhgSDgPc2UBg93gsOiTOEMqLJSy-rvwTdZUGGzQSK_RPFM8vtuRSbt_Psd2O8zysFN8e5OtLDTiC-KaxXgJGj_aixTkJygH8qDp2dzQrMaRTLh571jGO9s8bxYQ8BVxD5KoNabe1Tqmgbv0o4tMTYu_LcsQ6G1lSmf7NdWQC3-t8I_2BaG070mfrZl1w5AXDD8SpDLNSoZyZmeWsMRWqUrWbWkrC_VKyJNuNPin75GldseAO8-F3FOlwUJvTyryt-VuolytF7KBS8DS5JS0JeJyfz9zQAjTteiwT17s5h9mFrrsCo34LV-DEOsvjlnH6GfkF9xokOw0GhVb30zoTlVclz1m9wcORsbrnwXR2uj-ycPfgNvPdZGDglwzSmbTNFHHnTGyAiv4JhBTyVeOSfOrH58Zx44gzGnaKxNnCKIvinq3bIO-9dMNvzJCZl4JHQXgLzIfCAxVjcq21uEwk";
    var api_auth_header  = {
        "Content-Type": "application/json",
        "Authorization": __token
    };
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
<script src="{{ asset('adminLteTheme/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('adminLteTheme/bower_components/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('adminLteTheme/bower_components/morris.js/morris.min.js') }}"></script>
<script src="{{ asset('adminLteTheme/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('adminLteTheme/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('adminLteTheme/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('adminLteTheme/bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
<script src="{{ asset('adminLteTheme/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('adminLteTheme/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('adminLteTheme/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('adminLteTheme/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<script src="{{ asset('adminLteTheme/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('adminLteTheme/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('adminLteTheme/bower_components/fastclick/lib/fastclick.js') }}"></script>
<script src="{{ asset('adminLteTheme/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('adminLteTheme/dist/js/pages/dashboard.js') }}"></script>
{{--<script src="{{ asset('assets/js/swal.js')}}" type="text/javascript"></script>--}}
<script src="{{ asset('adminLteTheme/dist/js/demo.js') }}"></script>
<script src="https://unpkg.com/simplebar@latest/dist/simplebar.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.js"></script>
<script src="{{ asset('my_assets/jquery.buttonLoader.js') }}"></script>
<script src="{{ asset('my_assets/common.js') }}"></script>
<script src="{{ asset('my_assets/iziToast.min.js') }}"></script>
<script type="text/javascript">
    $(function () {
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        })
        //for Pop Over
        $('[data-toggle="popover"]').popover();

        $(document).scroll(function() {
            var _scroll = $(this).scrollTop();
            if (_scroll >= 500) {
                setTimeout('$("#go-to-top-btn").addClass("show")',200);
            }
            else{
                setTimeout('$("#go-to-top-btn").removeClass("show")',200);
            }
        });
        $('#go-to-top-btn').click(function () {
            $("html, body").animate({
                scrollTop: 0
            }, 600);
            return false;
        });
      

    });
</script>
@yield('script')
<!-- @include('admin.layouts.loader_js') -->
</body>
</html>


