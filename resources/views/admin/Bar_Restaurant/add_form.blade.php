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

.img-wrap {
    position: relative;
}

.img-wrap .close {
    position: absolute;
    top: 2px;
    right: 5px;
}
</style>
<form id="add_edit_form_data" role="form" method="Post" autocomplete="off" role="form" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <div class="col-md-6">
            <label for="first_name">Bar Name</label>
            <input type="text" name="name" class="form-control" id="firstName" placeholder="Enter Bar Name"
                value="@if(isset($data->name)){{ $data->name }}@endif">
        </div>

        <div class="col-md-6">
            <label for="phone">Phone Number</label>
            <input type="text" name="phone" class="form-control" id="phone"
                onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Phone Number"
                value="@if(isset($data->phone)){{ $data->phone }}@endif">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label for="userAddress">Address</label>
            <input type="text" name="address" class="form-control" id="Address" placeholder="Enter Address"
                value="@if(isset($data->address)){{ $data->address }}@endif">

        </div>

        <div class="col-md-6">
            <label for="zip_code">Zip Code</label>
            <input type="text" name="zipCode" class="form-control" id="zip_code" placeholder="Enter zip code"
                value="@if(isset($data->zipCode)){{ $data->zipCode }}@endif">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <label for="country">Country</label>
            <input type="text" name="country" class="form-control" id="country" placeholder="Enter Country"
                value="@if(isset($data->country)){{ $data->country }}@endif">

        </div>
        <div class="col-md-4">
            <label for="userFullAddress">State</label>
            <input type="text" name="state" class="form-control" id="state" placeholder="Enter State"
                value="@if(isset($data->state)){{ $data->state }}@endif">

        </div>
        <div class="col-md-4">
            <label for="city">City</label>
            <input type="text" name="city" class="form-control" id="city" placeholder="Enter City"
                value="@if(isset($data->city)){{ $data->city }}@endif">

        </div>
    </div>
    @if(!empty($data->latitude)&&!empty($data->longitude))
    <div class="form-group row">
        <div class="col-md-6">
            <label for="userAddress">Latitude</label>
            <input type="text" name="latitude" class="form-control" id="Latitude" placeholder="Enter Latitude"
                value="@if(isset($data->latitude)){{ $data->latitude }}@endif">

        </div>

        <div class="col-md-6">
            <label for="longitude">Longitude</label>
            <input type="text" name="longitude" class="form-control" id="longitude" placeholder="Enter Longitude"
                value="@if(isset($data->longitude)){{ $data->longitude }}@endif">
        </div>
    </div>
    @endif
    <div class="form-group row">
        <!-- <div class="col-md-6">
            <label for="userAddress">Distance</label>
            <input type="text" name="distance" class="form-control" id="distance" placeholder="Enter Distance"
                value="@if(isset($data->distance)){{ $data->distance }}@endif">
        </div> -->
        <div class="col-md-6">
            <label for="rating">Rating</label>
            @if(isset($data->rating))
            <input type="text" name="rating" class="form-control" id="rating" placeholder="Enter Rating"
                value="@if(isset($data->rating)){{ $data->rating }}@endif">
            @else
            <select class="form-control" id="rating" name="rating">
                <option value="">Select</option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            @endif
        </div>
    </div>
    <div class="form-group row ">
        <div class="col-md-12">
            <label for="userAddress">What’s Happening Description</label>
            <textarea name="description" class="form-control" id="description" placeholder="Enter Description"
                value="">@if(isset($data->description)){{ $data->description }}@endif</textarea>

        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label for="image_file">Select Image</label>
            <div class="">
                <div class="mt-10">
                    <label class="custom-file-upload btn btn-primary ">
                        <input type="file" name="userProfilePicture_file" id="userProfilePicture_file"
                            onchange="upload_bar_restaurant_image('userProfilePicture_file','imagenameUrl','userProfilePicture_preview')">
                        Select File
                    </label>
                </div>
            </div>
        </div>
    </div>
    @if(isset($image))
    <div class="form-group row">
        @foreach($image as $xx)

        <div class=" form-group img-wrap col-md-4" id="search_image_section_{{$xx->imageId}}">
            <span class="close"><i class="fa fa-remove"
                    onclick="remove_image('{{$xx->imageId}}','{{$xx->restaurantId}}')"
                    style="font-size:30px;color:white"></i></span>
            @if($data->type == 1 && $xx->type == 1)
            <img src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference={{$xx->imageName}}&key=AIzaSyDRTvKgRxnqrA8FJt_-n4EvgtK-N64GoFg"
                width="220" height="150">
            @else
            <img src="{{$xx->imageName}}" width="220" height="150">
            @endif
        </div>
        @endforeach
    </div>
    @endif
    <div class="form-group row">
        <div class="userProfilePicture_preview"> </div>
    </div>

    <div class="imagenameUrl">
        @if(empty($data))
        <input type="hidden" name="imageUrl[]" id="temp_image_Url" value="">
        @endif
    </div>
    <input type="hidden" name="restaurantId" id="restaurantId"
        value="@if(isset($data->restaurantId)){{ $data->restaurantId }}@endif">
        <input type="hidden" name="type" id="type"
        value="@if(isset($data->type)){{ $data->type }}@endif   ">
    <!-- <div class="mb-10"><button type="button" id="formid" onclick="add_update_provider_details('formid')" class="btn btn-primary form-custom-btn">Submit</button></div> -->
    <div class="mb-10">
        <button type="submit" id="subbutton" submit="submit" class="btn btn-primary form-custom-btn">Submit
        </button>
</form>