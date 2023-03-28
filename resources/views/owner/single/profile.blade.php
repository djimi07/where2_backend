@extends('owner.layouts.master')
@section('page_title')
    @if(isset($page_title) && !empty($page_title))
        {{ $page_title }}
    @else
        {{ config('constants.default_admin_page_title') }}
    @endif
@endsection
@section('content')
    <section class="content-header">
        <h1>
            View Profile
        </h1>
    </section>
    <section class="content">

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="profile-page">
                        <form id="form_id">
                            @csrf
                            <div class="profile-body editable">
                                <div class="profile-img">
                                    <div class="form-group">
                                    @php $url = config('constants.default_user_image') @endphp
                                    @if(isset($auth_user->userProfilePicture) && !empty($auth_user->userProfilePicture))
                                    @php $url = config('constants.image_url').$auth_user->userProfilePicture @endphp
                                    @endif
                                        <img id="userProfilePicturePreview" src="{{ $url }}">
                                        <label class="upld-label">
                                            <input type="file" onchange="update_profile_image()" name="ownerProfilePicture" id="userProfilePicture">
                                            <span>
                                                <i class="fa fa-camera"></i>
                                            </span>
                                        </label>
                                        <input type="hidden" name="image" id="image" value="@if(isset($auth_user->image)){{ $auth_user->image }}@endif">
                                    </div>
                                </div>
                                <div class="profile-text">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <i class="fa fa-user fc-icon fc-icon-name"></i>
                                                <label for="username" class="f-label">First Name</label>
                                                <input type="text" name="firstName" class="form-control" id="firstName"
                                                       placeholder="Enter First Name"
                                                       value="@if(isset($auth_user->firstName)){{ $auth_user->firstName }}@endif"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <i class="fa fa-user fc-icon fc-icon-name"></i>
                                                <label for="username" class="f-label">Last Name</label>
                                                <input type="text" name="lastName" class="form-control" id="lastName"
                                                       placeholder="Enter Last Name" value="@if(isset($auth_user->lastName)){{ $auth_user->lastName }}@endif"
                                                       required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <i class="fa fa-envelope fc-icon"></i>
                                            <label for="email" class="f-label">Email</label>
                                            <input type="email" class="form-control" placeholder="Enter Email"
                                                id="userEmail" name="userEmail"
                                                value="@if(isset($auth_user->userEmail)){{ $auth_user->userEmail }}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <i class="fa fa-envelope fc-icon"></i>
                                            <label for="userMobile" class="f-label">Phone Number</label>
                                            <input type="text" class="form-control" placeholder="Enter Phone Number"
                                                id="userMobile" name="userMobile" onKeyPress="if(this.value.length==10) return false;"
                                                value="@if(isset($auth_user->userMobile)){{ $auth_user->userMobile }}@endif">
                                        </div>
                                    </div>
                                </div>


                                    <div>
                                        <button type="button" id="form_id_btn" onclick="update_profile('form_id_btn')" class="btn btn-primary update-btn margin-r-5 form-custom-btn">Submit
                                        </button>
                                        <a href="{{ url('owner/dashboard') }}" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                        </form>
                    </div>
                </div><!--/.col (left) -->
            </div>   <!-- /.row -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection

@section('script')

    <script type="text/javascript">
        function update_profile(form_id)
        {
            var postData = {};
            postData.firstName = $('#firstName').val();
            postData.lastName = $('#lastName').val();
            postData.userEmail = $('#userEmail').val();
            postData.userMobile = $('#userMobile').val();
            postData._token = $("input[name=_token]").val();
            var url = api_url+'update_profile';
            add_update_details(url,form_id,postData);

        }
    </script>
    <script>
        function update_profile_image()
        {
            var image_url = '{{config('constants.image_url')}}';
            var image = document.getElementById('userProfilePicture');
            var fd = new FormData();
            fd.append('ownerProfilePicture', image.files[0]);
            fd.append('_token', $("input[name=_token]").val());
            var url = api_url+"update-profile-image";
            $.ajax({
                url: url,
                type: 'POST',
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'JSON',
                data: fd,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader ("Authorization", __token);
                },
                success: function (response)
                {
                    if (response.status == 200)
                    {
                        $("#userProfilePicturePreview").attr("src", image_url+response.response.userProfilePicture);
                        $("#userProfilePictureHeaderPreview").attr("src", image_url+response.response.userProfilePicture);
                        success_message(response.msg,'',2);
                    }
                },
                error:function (response) {
                    if(response.status == 413)
                    {
                        error_message('Allow jpeg,png,jpg,gif,svg Image type');
                    }
                    else{
                        var responseJSON = response.responseJSON;
                        error_message(responseJSON.msg);
                    }
                }
            });
        }
    </script>
@endsection


