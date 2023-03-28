<div class="form-group row">
        <div class="col-md-6">
            <label for="first_name">Assign Bar owner</label>
            <select class="form-control" id="owner-Id" name="ownerId">
            <option data-countryCode="" value="">Select Owner Name</option>
                @foreach(@$owner as $val)
                <option value="{{$val->userId}}">{{$val->firstName .' '.$val->lastName}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <input type="hidden" name="restaurantId" id="restaurant-Id"
        value="@if(isset($restaurantId)){{ $restaurantId }}@endif">
    <!-- <div class="mb-10"><button type="button" id="formid" onclick="add_update_provider_details('formid')" class="btn btn-primary form-custom-btn">Submit</button></div> -->
    <div class="mb-10">
        <button type="submit" id="formid" onclick="add_owner('formid')" class="btn btn-primary form-custom-btn">Submit
        </button>
