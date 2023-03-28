
<style>
.pac-container {
    z-index: 999999 !important;
}

.mt-10 {
    margin-top: 10px;
}

.mb-10 {
    margin-bottom: 10px;
}

.custom-file-upload input[type="file"] {
    display: none;
}

.custom-file-upload {
    /* border: 1px solid #ccc;
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer; */
}

.form-password-view {
    position: absolute;
    right: 10px;
    top: 7px;
    z-index: 9;
}

.width-100 {
    width: 100%;
}
</style>

<form id="add_edit_form_data" role="form" method="Post" autocomplete="off" role="form" enctype="multipart/form-data">

    @csrf
    <div class="form-group row">
        <div class="col-md-6">
            <label for="userName">User Name</label>
            <input type="text" name="userName" class="form-control" id="firstName" placeholder="User Name"
                value="@if(isset($data->userName)){{ $data->userName }}@endif">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label for="firsName">First Name</label>
            <input type="text" name="firstName" class="form-control" id="firstName" placeholder="First Name"
                value="@if(isset($data->firstName)){{ $data->firstName }}@endif">
        </div>
        <div class="col-md-6">
            <label for="lastName">Last Name</label>
            <input type="text" name="lastName" class="form-control" id="lastName" placeholder="Last Name"
                value="@if(isset($data->lastName)){{ $data->lastName }}@endif">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label for="email">Email</label>
            <input type="email" name="userEmail" class="form-control" id="userEmail"
            placeholder="Email"
            value="@if(isset($data->userEmail)){{ $data->userEmail }}@endif">
        </div>
        <div class="col-md-6">
            <label for="userMobile">Phone Number</label>
            <input type="text" name="userMobile" class="form-control" id="userMobile"
                onKeyPress="if(this.value.length==10) return false;" @if(isset($data->userMobile) &&
            !empty($data->userMobile)) @endif
            placeholder="Phone Number"
            value="@if(isset($data->userMobile)){{ $data->userMobile }}@endif">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label for="password">Password</label>
            <div class="input-group width-100">
                <input type="password" autocomplete="off" name="password" class="form-control" id="password"
                    placeholder="Password" value="">
                <div class="input-group-append form-password-view">
                <span toggle="#password"   class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                </div>

            </div>
        </div>
        <div class="col-md-6">
            <label for="password">Confirm Password</label>
            <div class="input-group width-100">
                <input type="password" autocomplete="off" name="confirmPassword" class="form-control"
                    id="password_confirmation" placeholder="Confirm Password" value="">
                <div class="input-group-append form-password-view">
                    <span toggle="#password_confirmation"
                        class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                </div>
            </div>
        </div>
    </div>
    {{--<div class="form-group row">
        <div class="col-md-6">
            <label for="image_file">Image</label>
            <div class="">
                <img id="userProfilePicture_preview" src="@if(isset($data->ownerProfilePicture) && !empty($data->ownerProfilePicture)){{ config('constants.image_url').$data->ownerProfilePicture }}@else{{ config('constants.default_user_image') }}@endif"
    style="width: 100px;" >

    <div class="mt-10">
        <label class="custom-file-upload btn btn-primary ">
            <input type="file" name="ownerProfilePicture_file" id="ownerProfilePicture_file"
                onchange="upload_owner_image('ownerProfilePicture_file','ownerProfilePicture','ownerProfilePicture_preview')">
            Select File
        </label>
    </div>
    </div>
    </div>

    </div>
    --}}

    <input type="hidden" name="ownerProfilePicture" id="ownerProfilePicture"
        value="@if(isset($data->ownerProfilePicture)){{ $data->ownerProfilePicture }}@endif">

    <input type="hidden" name="userId" id="userId" value="@if(isset($data->userId)){{ $data->userId }}@endif">
    <!-- <div class="mb-10"><button type="button" id="form_id_btn" onclick="add_update_provider_details('form_id')" class="btn btn-primary form-custom-btn">Submit</button></div> -->
    <div class="mb-10">
        <button type="submit" id="subbutton" submit="submit" class="btn btn-primary form-custom-btn">Submit
        </button>
</form>
