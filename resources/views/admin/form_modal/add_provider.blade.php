<style>
    .pac-container {
        z-index: 999999 !important;
    }
    .mt-10{
        margin-top: 10px;
    }
    .mb-10{
        margin-bottom: 10px;
    }
    .custom-file-upload input[type="file"]{
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
    .width-100{
        width: 100%;
    }


</style>
<form id="form_id"  role="form" autocomplete="off">
    @csrf
    <div class="form-group row">
        <div class="col-md-6">
            <label for="first_name">First Name</label>
            <input type="text"
                   name="first_name"
                   class="form-control"
                   id="firstName"
                   placeholder="First Name"
                   value="@if(isset($data->firstName)){{ $data->firstName }}@endif">
        </div>
        <div class="col-md-6">
            <label for="last_name">Last Name</label>
            <input type="text"
                   name="last_name"
                   class="form-control"
                   id="lastName"
                   placeholder="Last Name"
                   value="@if(isset($data->lastName)){{ $data->lastName }}@endif">
        </div>

    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label for="email">Email</label>
            <input type="email"
                   name="userEmail"
                   class="form-control"
                   id="userEmail"
                   @if(isset($data->userEmail) && !empty($data->userEmail)) readonly @endif

                   placeholder="Email"
                   value="@if(isset($data->userEmail)){{ $data->userEmail }}@endif">
        </div>
        <div class="col-md-6">
            <label for="userMobile">Phone Number</label>
            <input type="text"
                   name="userMobile"
                   class="form-control"
                   id="userMobile"
                   onKeyPress="if(this.value.length==14) return false;"
                   @if(isset($data->userMobile) && !empty($data->userMobile))  @endif
                   placeholder="Phone Number"
                   value="@if(isset($data->userMobile)){{ $data->userMobile }}@endif">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label for="userAddress">Address</label>
            <input type="text"
                   name="userAddress"
                   class="form-control"
                   id="userAddress"
                   placeholder="Address"
                   value="@if(isset($data->userAddress)){{ $data->userAddress }}@endif">

        </div>
        <div class="col-md-6">
            <label for="city">City</label>
            <input type="text"
                   name="city"
                   class="form-control"
                   id="city"
                   placeholder="city"
                   value="@if(isset($data->city)){{ $data->city }}@endif">

        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label for="userFullAddress">State</label>
            <input type="text"
                   name="state"
                   class="form-control"
                   id="state"
                   placeholder="state"
                   value="@if(isset($data->state)){{ $data->state }}@endif">

        </div>
        <div class="col-md-6">
            <label for="zip_code">Zip code</label>
            <input type="text"
                   name="zip_code"
                   class="form-control"
                   id="zip_code"
                   placeholder="zip_code"
                   value="@if(isset($data->zip_code)){{ $data->zip_code }}@endif">

        </div>
    </div>
    {{--<div class="form-group row">
        <div class="col-md-6">
            <label for="userAddress">userAddress</label>
            <input type="text"
                   name="city"
                   class="form-control"
                   id="userAddress"
                   placeholder="userAddress"
                   value="@if(isset($data->userAddress)){{ $data->userAddress }}@endif">

        </div>

        <div class="col-md-6">
            <label for="zip_code">Zip Code</label>
            <input type="text"
                   name="zip_code"
                   class="form-control"
                   id="zip_code"
                   placeholder="zip code"
                   value="@if(isset($data->zip_code)){{ $data->zip_code }}@endif">
        </div>
    </div>--}}
    <div class="form-group row">
        <div class="col-md-6">
            <label for="password">Password</label>
            <div class="input-group width-100">
                <input type="password" autocomplete="off" name="password" class="form-control" id="password" placeholder="Password" value="">
                <div class="input-group-append form-password-view">
                    <span toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <label for="password">Confirm Password</label>
            <div class="input-group width-100">
                <input type="password" autocomplete="off" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm Password" value="">
                <div class="input-group-append form-password-view">
                    <span toggle="#password_confirmation" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label for="image_file">Image</label>
            <div class="">
                <img id="userProfilePicture_preview" src="@if(isset($data->userProfilePicture) && !empty($data->userProfilePicture)){{ config('constants.image_url').$data->userProfilePicture }}@else{{ config('constants.default_user_image') }}@endif" style="width: 100px;" >

                <div class="mt-10">
                    <label class="custom-file-upload btn btn-primary ">
                        <input type="file" name="userProfilePicture_file" id="userProfilePicture_file" onchange="upload_user_image('userProfilePicture_file','userProfilePicture','userProfilePicture_preview')">
                        Select File
                    </label>
                </div>
            </div>
        </div>

    </div>

    <input type="hidden" name="userProfilePicture" id="userProfilePicture" value="@if(isset($data->userProfilePicture)){{ $data->userProfilePicture }}@endif">

    <input type="hidden" name="userId" id="userId"  value="@if(isset($data->userId)){{ $data->userId }}@endif">
    <div class="mb-10"><button type="button" id="form_id_btn" onclick="add_update_provider_details('form_id')" class="btn btn-primary form-custom-btn">Submit</button></div>
</form>
