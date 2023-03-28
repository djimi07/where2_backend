<style>
<style>.pac-container {
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
.floatRight {
    position: relative;
}
.text1 {
    position: absolute;
    top: 83px;
    left: 23px;
    color: #ddd;
    font: bold 16px sans-serif;
}
.text2 {
    position: absolute;
    top: 104px;
    left: 23px;
    color: #ddd;
    font: bold 16px sans-serif;
}
.text3 {
    position: absolute;
    top: 126px;
    left: 23px;
    color: #ddd;
    font: bold 16px sans-serif;
}
}
</style>
<form id="add_event" role="form" method="Post" autocomplete="off" role="form" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <div class="col-md-6">
            <label for="selectevent">Event Type</label>
            <select class="form-control" id="selectevent" name="eventType">
                <option data-countryCode="" value="">Select</option>
                <option value="1">Drink special</option>
                <option value="2">Event</option>
            </select>
        </div>
    </div>
    </div>
    <div class="form-group drink_special" style="display:none">
        <div class="input-group input-daterange date " id="datetimepicker1">
            <input type="text" class="form-control picdate"  placeholder="Start Date"
                name="firstdate" data-date-format="YYYY-MM-DD hh:mm A"  id="start" value="@if(isset($data->startdate)){{ $data->startdate }}@endif">
            <div class="input-group-addon ">to</div>
            <input type="text" class="form-control  picdate"  placeholder="End Date"
                name="secdate" data-date-format="YYYY-MM-DD hh:mm A" id="end" value="@if(isset($data->enddate)){{ $data->enddate }}@endif">
        </div>
    </div>

    <div class="form-group row event" style="display:none">
        <div class="col-md-6">
            <label for="selectevent">Event</label>
            <select class="form-control" id="event" name="eventname">
                <option value="">Select</option>
                <option value="Music">Music</option>
                <option value="Trivia">Trivia</option>
                <option value="Television">Television</option>
            </select>
        </div>
    </div>
    <div class="form-group row drink_special" style="display:none">
        <div class="col-md-6">
            <label for="selectevent">Select Deals</label>
            <select class="form-control" id="offer_type" name="offer">
                <option value="">Select</option>
                <option value="BOGO">BOGO</option>
                <option value="Buy one 50% off">Buy one 50% off</option>
                <option value="25% off">25% off</option>
                <option value="Happy Hour Specials">Happy Hour Specials</option>
            </select>
        </div>
        <?php
              $_yes="";
              $_no="";
        if(@$data->hot_deal) 
        if(@$data->hot_deal ==1 ||@$data->hot_deal =="1")
            $_yes="checked";
        else
            $_no="checked"; 
        else
            @$_no="checked";
        ?>
        <div class="col-md-4">
            <div class="form-group status" id="status">
                <label for="">Is Hot Deal</label><br>
                <label>
                    <input type="radio" name="hot_deal" id="Yes" value="1" class="minimal" {{$_yes}}>Yes
                </label>
                &nbsp;
                <label>
                    <input type="radio" name="hot_deal" id="No" value="0" class="minimal" {{$_no}}>No
                </label>
            </div>
        </div>
    </div>
    <div class="form-group row" style="">
        <div class="col-md-12">
            <label for="userAddress">Description</label>
            <textarea name="description" class="form-control" id="description" placeholder="Enter Description"
                value="">@if(isset($data->description)){{ $data->description }}@endif</textarea>

        </div>
    </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label for="image_file">Image</label>
            <div class="">
                <img id="userProfilePicture_preview"
                    src="@if(isset($data->imageUrl) && !empty($data->imageUrl)){{$data->imageUrl }}@else{{ config('constants.default_image') }}@endif"
                    style="width: 100px;">

                <div class="mt-10">
                    <label class="custom-file-upload btn btn-primary ">
                        <input type="file" name="userProfilePicture_file" id="userProfilePicture_file"
                            onchange="upload_event_image('userProfilePicture_file','image_url','userProfilePicture_preview')">
                        Select File
                    </label>
                </div>
            </div>
        </div>
       

    </div>
    <input type="hidden" name="restaurantId" id="restaurant_Id" value="@if(isset($data->restaurantId)){{ $data->restaurantId }}@endif">
    <input type="hidden" name="dealId" id="dealId" value="@if(isset($data->dealId)){{ $data->dealId }}@endif">
    <input type="hidden" name="image_url" id="image_url" value="@if(isset($data->imageUrl)){{ $data->imageUrl }}@endif">
    <div class="mb-10">

        <button type="submit" id="subbutton_event" submit="submit" class="btn btn-primary form-custom-btn">Submit
        </button>
        <button type="button" onclick="previous_view()" class="btn btn-primary" data-toggle="modal"
            data-target="#myModal">View</button>
</form>

<!-- Model -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Show Preview</h4>
            </div>
            <div class="modal-body">
                <img id="image_modal" class ="floatRight" src="" width="220" height="150">
                <h4 class="text1" id="offer_modal"></h4>
                <h5 class="text2" id="description_modal"></h5>
                <h5 class="text3" id="bogo_modal"></h5>
                <h4>Description</h4>
                <p id="long_description"></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>