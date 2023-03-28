@extends('admin.layouts.login_master')
@section('page_title')
@if(isset($page_title))
{{ $page_title }}
@else
{{ config('constants.default_page_title') }}
@endif
@endsection
@section('section')
<div class="form-box" id="forgot_form">
    <div class="header">Forgot Password?</div>
    <form id="form_id" onSubmit="return false;" autocomplete="off">
        @csrf
        <div class="body bg-gray">
            <div class="form-group col-lg-8 row">
                <select class="form-control" id="code" name="code">
                <!-- <option data-countryCode="" value="">Select Country Code</option> -->
        
                    <option data-countryCode="1" value="1">United States
                        (+1)</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="userMobile" id="userMobile" onKeyPress="if(this.value.length==10) return false;" class="form-control" placeholder="Phone Number" />
            </div>
            <div class="form-group">
                {{-- <input type="checkbox" name="remember_me"/> Remember me --}}
            </div>
        </div>
        <div class="footer">
            <button type="button" id="form_id_btn" onclick="forget_password('form_id')"
                class="btn bg-olive btn-block">Forgot Password</button>
            <a href="{{ url('admin') }}">Back To Sign-In</a>
            <div id="recaptcha-container"></div>
        </div>
    </form>

</div>
<div class="form-box" id="otp_form" style="display:none">
    <div class="header">Verify OTP </div>

    <div class="body bg-gray">
        <div class="form-group">
            <input type="text" name="verificationcode" id="verificationcode" class="form-control"
                placeholder="Enter OTP" />
            <input type="hidden" name="token" id="token" class="form-control" value="" />
        </div>
        <div class="form-group text-center">
            <button id="verify_id_btn" class="btn bg-olive btn-block" onclick="otp_verify('verify_id')">Submit</button>

        </div>
    </div>
    <!-- <div class="footer">
                <button type="button" id="form_id_btn"  onclick="forget_password('form_id')" class="btn bg-olive btn-block">Forgot Password</button>
                <a href="{{ url('admin') }}">Back To Login</a>
            </div> -->
    </form>

</div>
@endsection
@section('script')
<script src="https://www.gstatic.com/firebasejs/4.8.1/firebase.js"></script>
<script>
$('#form_id').keydown(function(e) {
    //  e.preventDefault();
    var key = e.which;
    if (key == 13) {
        forget_password('form_id');
    }
});

function forget_password(form_id) {
    var postData = {};
    postData.userMobile = $('#userMobile').val();
    postData.code = $('#code').val();

    var mobile = $('#userMobile').val();
    var code = $('#code').val();
    $('#' + form_id + '_btn').buttonLoader('start');
    $('#' + form_id + '_btn').prop('disabled', true);

    $.ajax({
        header: api_header,
        url: api_base_url + 'forget-password',
        type: 'POST',
        dataType: 'JSON',
        data: postData,
        success: function(response) {
            var responseJSON = response;
            firebase.initializeApp(responseJSON.config);
            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');


            firebase.auth().signInWithPhoneNumber('+' + code + '' + mobile + '', window.recaptchaVerifier)
                .then(function(confirmationResult) {
                    window.confirmationResult = confirmationResult;
                    // a(confirmationResult);
                    success_message_without_reload(responseJSON.msg);
                    $('#forgot_form').hide();
                    $('#otp_form').show();

                }, function(error) {
                    error_message(error.message);
                });
            $('#' + form_id + '_btn').prop('disabled', false);
            $('#' + form_id + '_btn').buttonLoader('stop');
            $(document).find('#token').val(responseJSON.token);


        },
        error: function(response) {
            $('#' + form_id + '_btn').prop('disabled', false);
            $('#' + form_id + '_btn').buttonLoader('stop');
            var responseJSON = response.responseJSON;
            error_message(responseJSON.msg);
            hide_loader();
        }
    });

}

function otp_verify(form_id) {
    $('#' + form_id + '_btn').buttonLoader('start');
    $('#' + form_id + '_btn').prop('disabled', true);
    var code = document.getElementById("verificationcode").value;
    if(code){
        window.confirmationResult.confirm(code)
            .then(function(result) {
                $('#' + form_id + '_btn').prop('disabled', false);
                $('#' + form_id + '_btn').buttonLoader('stop');
                success_message_without_reload('Successfull');
                var token = $(document).find('#token').val();
                var url = "{{ url('/admin/password-reset') }}";
                window.location.href = url + '?token=' + token;
            }, function(error) {
                $('#' + form_id + '_btn').prop('disabled', false);
                $('#' + form_id + '_btn').buttonLoader('stop');
                error_message('Invalid OTP.');
            });
           
    } else {
        error_message('OTP is required.');
        $('#' + form_id + '_btn').prop('disabled', false);
        $('#' + form_id + '_btn').buttonLoader('stop');
    }
   
};
</script>
@endsection