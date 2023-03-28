@extends('admin.layouts.login_master')
@section('page_title')
  @if(isset($page_title))
    {{ $page_title }}
    @else
    {{ config('constants.default_page_title') }}
    @endif
@endsection
@section('section')
    <div class="form-box" id="login-box">
        <div class="header">Sign In</div>
        <form id="form_id" onSubmit="return false;">
            @csrf
            <div class="body bg-gray">
                <div class="form-group">
                    <input type="text" name="userMobile" id="userMobile" class="form-control" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Phone Number"/>
                </div>
                <div class="form-group">
                    <div class="input-group width-100">
                        <input type="password" name="password" id="password" value="" class="form-control" placeholder="Password">
                        <div class="input-group-append form-password-view">
                            <span toggle="#password" class="fa fa-fw field-icon toggle-password  fa-eye-slash"></span>
                        </div>
                    </div>


                </div>
                <div class="form-group">
                    {{-- <input type="checkbox" name="remember_me"/> Remember me --}}
                </div>
            </div>
            <div class="footer">
                <button id="form_id_btn" type="button" onclick="login('form_id')"  class="btn bg-olive btn-block">Sign in</button>
                <p><a href="{{ url('admin/forget-password') }}">I forgot my password</a></p>
            </div>
        </form>

    </div>
    @endsection
@section('script')
    <script>
        $('#form_id').keydown(function (e) {
            var key = e.which;
            if (key == 13) {
                login('form_id');
            }
        });

        function login(form_id)
        {
            var postData = {};
             postData.userMobile = $('#userMobile').val();
             postData.password = $('#password').val();

            $('#'+form_id+'_btn').buttonLoader('start');
            $('#'+form_id+'_btn').prop('disabled', true);

            $.ajax({
                header:api_header,
                url: api_base_url+'login',
                type: 'POST',
                dataType: 'JSON',
                data: postData,
                success: function (response)
                {

                    var responseJSON = response;

                    if(responseJSON.status == 200)
                    {
                        $.ajax({
                            header:api_header,
                            url: "{{ url('/admin/set-auth-session') }}",
                            type: 'POST',
                            dataType: 'JSON',
                            data: {
                                web_token:responseJSON.web_token
                            },
                            success: function (response)
                            {
                                $('#'+form_id+'_btn').prop('disabled', false);
                                $('#'+form_id+'_btn').buttonLoader('stop');
                                window.localStorage.setItem('auth_api_token', responseJSON.token);
                                success_message(responseJSON.msg);
                            }

                    });
                    }
                    else
                    {
                        error_message(responseJSON.msg);
                    }

                },
                error:function (response) {
                    $('#'+form_id+'_btn').prop('disabled', false);
                    $('#'+form_id+'_btn').buttonLoader('stop');
                    var responseJSON = response.responseJSON;
                    error_message(responseJSON.msg);

                }
            });

        }
    </script>
    @endsection
