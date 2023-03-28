
<form id="add_edit_form_data" role="form" method="Post" autocomplete="off" role="form" enctype="multipart/form-data">

    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label for="comment">Comment</label>
            <input type="text" name="comment" class="form-control" id="comment" placeholder="Enter Comment"
                value="@if(isset($data->comment)){{ $data->comment }}@endif">
        </div>
    </div>
    <input type="hidden" name="precommentId" id="precommentId" value="@if(isset($data->precommentId)){{ $data->precommentId }}@endif">
    <!-- <div class="mb-10"><button type="button" id="form_id_btn" onclick="add_update_provider_details('form_id')" class="btn btn-primary form-custom-btn">Submit</button></div> -->
    <div class="mb-10">
        <button type="submit" id="subbutton" submit="submit" class="btn btn-primary form-custom-btn">Submit
        </button>
</form>
