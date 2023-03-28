@extends('owner.layouts.master')
@section('page_title')
    @if(isset($page_title) && !empty($page_title))
        {{ $page_title }}
    @else
        {{ config('constants.default_admin_page_title') }}
    @endif
@endsection
@section('content')
    <style>
        .form-password-view {
            position: absolute;
            right: 10px;
            top: 7px;
            z-index: 9;
        }
        .width-100{
            width: 100%;
        }
    </style>
    <section class="content-header">
        <h1>
            Change Password
        </h1>
    </section>
    <section class="content">

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-body">
                        <form role="form" id="form_id">
                            @csrf
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="old_password">Old Password</label>
                                    <div class="input-group width-100">
                                        <input id="old_password" type="password" class="form-control" name="old_password" placeholder="Enter Old Password" value="{{ old('old_password')}}" required>
                                        <div class="input-group-append form-password-view">
                                            <span toggle="#old_password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <div class="input-group width-100">
                                        <input id="password" type="password" class="form-control" name="password" placeholder="Enter New Password" value="{{ old('password')}}" required>
                                        <div class="input-group-append form-password-view">
                                            <span toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                        </div>
                                    </div>


                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password</label>

                                    <div class="input-group width-100">
                                        <input id="confirm_password" type="password" class="form-control" name="confirm_password" placeholder="Enter Confirm Password" value="{{ old('confirm_password')}}" required>
                                        <div class="input-group-append form-password-view">
                                            <span toggle="#confirm_password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" id="form_id_btn" onclick="update_password('form_id_btn')" class="btn btn-primary form-custom-btn">Submit</button>
                                </div>
                            </div>


                        </form>
                    </div><!-- /.box -->
                </div>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
@endsection
@section('script')
    <script>
        function update_password(form_id)
        {
            var postData = {};
            postData.old_password = $('#old_password').val();
            postData.password = $('#password').val();
            postData._token =$('input[name =_token]').val();
            postData.password_confirmation = $('#confirm_password').val();
            var url = api_url+'change-password';
            add_update_details(url,form_id,postData);


        }
    </script>

@endsection

