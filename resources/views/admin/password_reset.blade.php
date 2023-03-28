@extends('admin.layouts.login_master')
@section('page_title')
    @if(isset($page_title))
        {{ $page_title }}
    @else
        {{ config('constants.default_page_title') }}
    @endif
@endsection
@section('section')
    <div class="form-box" id="form_id" >
        <div class="header">Reset Password</div>
        <form id="form_id" onSubmit="return false;" autocomplete="off">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">
            <div class="body bg-gray">
                <div class="form-group">
                    <div class="input-group width-100">
                        <input type="password" name="password" id="password" class="form-control" placeholder="New Password"/>
                        <div class="input-group-append form-password-view">
                            <span toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group width-100">
                        <input type="password" name="password_confirmation" id="password_confirmation"  class="form-control" placeholder="Confirm New Password"/>
                        <div class="input-group-append form-password-view">
                            <span toggle="#password_confirmation" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="footer">
                <button id="form_id_btn" type="button" onclick="update_password_reset('form_id')" class="form-custom-btn btn bg-olive btn-block">Reset Password</button>
                <a href="{{ url('admin') }}">Back To Login</a>
            </div>
        </form>

    </div>
@endsection
@section('script')
    <script>
        function update_password_reset(form_id)
        {
            var postData = {};
            postData.token = '{{ $token }}';
            postData.password = $('#password').val();
            postData.password_confirmation = $('#password_confirmation').val();


            $('#'+form_id+'_btn').buttonLoader('start');
            $('#'+form_id+'_btn').prop('disabled', true);

            $.ajax({
                header:api_header,
                url: api_base_url+'update-reset-password',
                type: 'POST',
                dataType: 'JSON',
                data: postData,
                success: function (response)
                {

                    $('#'+form_id+'_btn').prop('disabled', false);
                    $('#'+form_id+'_btn').buttonLoader('stop');
                    var responseJSON = response;

                    success_message(responseJSON.msg,'{{ url('/admin') }}/');

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
