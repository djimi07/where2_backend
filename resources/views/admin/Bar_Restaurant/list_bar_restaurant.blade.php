@extends('admin.layouts.master')
@section('page_title')
@if(isset($page_title) && !empty($page_title))
{{ $page_title }}
@else
{{ config('constants.default_admin_page_title') }}
@endif
@endsection
@section('style')
<link href="{{ asset('my_assets/select2.min.css') }}" rel="stylesheet" />

@endsection
@section('content')
<section class="content-header">
    <h1>
        Add Bar/Restaurant
    </h1>
</section>
<section class="content">

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header filter-form">

                    {{--<div class="form-group row">
                        <div class="col-md-3">
                            <label for="Latitude">Latitude</label>
                            <input type="text" name="latitude" class="form-control" id="Latitude"
                                placeholder="Latitude">
                        </div>
                        <div class="col-md-3">
                            <label for="Longitude">Longitude</label>
                            <input type="text" name="longitude" class="form-control" id="Longitude"
                                placeholder="Longitude">
                        </div>
                        
                        <div class="col-md-3">
                            <label for="Location">Location</label>
                            <input type="text" name="location" class="form-control" id="Location"
                                placeholder="Location">
                        </div>
                    </div>
                    --}}
                    <div class="form-group row">
                        <div class="col-md-4 ">
                            <label for="limit">Search</label>
                            <input type="text" id="search_text" class="form-control" name="search_text"
                                placeholder="Search by name" onclick="" value="">
                            <!-- <span class="input-group-btn">
                                    <button class="btn btn-default btn-flat" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span> -->
                        </div>
                        <div class="col-md-4">
                            <label for="Location">Location</label>
                            <input type="text" name="location" class="form-control" id="Location"
                                placeholder="Enter Location">
                        </div>
                       
                      
                    <!-- </div>
                    <div class="form-group row"> -->
                    <div class="col-md-4">
                            <label for="Location">Radius</label>
                            <input type="text" name="Radius" class="form-control" id="radius" placeholder="Radius in miles">
                        </div>
                        {{--<div class="col-md-6">
                            <label for="limit">Limit</label>
                            <select class="form-control " id="limit" name="limit">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                                <option value="50">50</option>
                            </select>
                        </div>--}}
                        <input type="hidden" id="next_page_token" name="next_page_token">
                    </div>
                    <div class="text-center">
                        <button type="submit" id="subbutton2" submit="submit" onclick="search_bar_restaurant('subbutton2','',1)"
                            class="btn btn-primary form-custom-btn ">Search</button>
                        <input type="reset" class="btn btn-flat btn-primary"
                                value="Reset" onclick="rest()">
                        <a class="btn btn-flat btn-primary pull-right" onclick="show_add_category()">Manually Create </a>
                    </div>
                </div><!-- /.box-header -->

            </div><!-- /.box -->

            <!-- user-card-row -->
            <div class="row">

                <div class="col-xs-12">
                <div id="no-data-box" style="display:none">Record not found...!</div>
                </div>
                <div class="col-xs-12 " id="search_result_box">
                </div>

                <div class="text-center" id="load_more_btn" style="display:none">
                    <div>
                        <!-- <button type="submit" id="add" onclick="add_bar_restaurant(1,1)"
                            class="btn btn-primary form-custom-btn">ADD
                        </button> -->
                        <a id="resloadmore" class="btn btn-primary"  onclick="search_bar_restaurant('resloadmore',1,2)">
                            Load More</a>
                    </div>
                </div>
                <div class="text-center">
                    <div class="sk-circle" id="loadmoreIcon" style="display:none">
                        <div class="sk-circle1 sk-child"></div>
                        <div class="sk-circle2 sk-child"></div>
                        <div class="sk-circle3 sk-child"></div>
                        <div class="sk-circle4 sk-child"></div>
                        <div class="sk-circle5 sk-child"></div>
                        <div class="sk-circle6 sk-child"></div>
                        <div class="sk-circle7 sk-child"></div>
                        <div class="sk-circle8 sk-child"></div>
                        <div class="sk-circle9 sk-child"></div>
                        <div class="sk-circle10 sk-child"></div>
                        <div class="sk-circle11 sk-child"></div>
                        <div class="sk-circle12 sk-child"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section><!-- /.content -->
@endsection

@section('script')

<script type="text/javascript" src="{{ asset('my_assets/select2.min.js') }}"></script>
<script>
var user_default_image = '{{ config('
constants.default_user_image ') }}';
</script>

<script>
function rest()
{
        $('[name="search_text"]').val("");
        $('[name="Radius"]').val("");
        $('[name="location"]').val("");
    $(document).find('#search_result_box').html(''); 
    $(document).find('#resloadmore').hide();
}
$(document).find("#no-data-box").hide();
function show_add_category() {
    OpenZPanel('Add Bar/Restaurant');
    $(document).find('.z-panel-loader').removeClass('on');
    $(document).find('.z-panel-card-body-content').html('');
    var html = `{!! $add_form !!}`;
    $(document).find('.z-panel-card-body-content').html(html);
    $('input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
}

$(document).on('click','.remove_image',function(){
    var imagename = $(this).attr('data');
    $('#userProfilePicture_file').val('');
    $('.imagenameUrl').find('input[value="'+imagename+'"]').remove();
    $(this).parent().parent().remove();
})
// var next_page =1;
function search_bar_restaurant(form_id,page="",searchbt=""){
    $(document).find('#'+form_id).buttonLoader('start');
    $(document).find('#'+form_id).prop('disabled', true);
    // if(laodmore == "")
    // $(document).find("#loadmoreIcon").show();
    // if(page == 1)
    // {
    //     page = next_page;
    //     next_page++;
    // }
    // else
    // {
    //     next_page =1; 
    // }   
    // var limit = $('#limit').val();
    // //page = page - 1;
    var offset = "";

    if(searchbt == 1)
    {
        $('#next_page_token').val('');
    }     
    // else
    // {
    //     offset = page * limit;
    // }
        

    var obj = {};
    obj.search_text = $('#search_text').val();
    obj.latitude = $('#Latitude').val();
    obj.longitude = $('#Longitude').val();
    obj.location = $('#Location').val();
    obj.radius = $('#radius').val();
    obj.limit = $('#limit').val();
    obj.next_page_token = $('#next_page_token').val();
    // obj.offset = offset;
    var _url = api_url + 'Bar-restaurant/search-api-from-yelp';
  
    $.ajax({
        url: _url,
        type: 'POST',
        data: obj,
        dataType: 'JSON',
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", __token);
        },
        success: function(response) {
            $('#'+form_id).prop('disabled', false);
            $('#'+form_id).buttonLoader('stop');
           if(response.status == true){
            var total = response.response.total;
            var html = response.html;
            $(document).find("#no-data-box").hide();

            if (total == 0) {
                $(document).find("#no-data-box").show();
            }
            if (response.next)
            {
                $('#load_more_btn').show();
                $('#next_page_token').val(response.next);
            }  
            else{
                $('#load_more_btn').hide();
                $('#next_page_token').val('');
            }
               

            // if (response.response.length < limit)
            //     $('#load_more_btn').hide();

            // else
            //     $('#load_more_btn').show();

            // var total_offset_value = (offset + limit);

            // if (response.total == total_offset_value)
            //     $('#load_more_btn').hide();

            // if (offset == 0) {
            //     $('#search_result_box').empty();
            // }
            $('#loadmoreIcon').hide();

            if(searchbt == 1)
            {
                $(document).find('#search_result_box').html(html);
            }
           else{
                $(document).find('#search_result_box').append(html);
           }
            $.each(response.response, function(obj, item) {
               var rating = item.rating;
               $(document).find('#rateYo_'+item.place_id+'').rateYo({
                rating: rating,
                starWidth: "16px",
                readOnly: true
                    });
            }, {});

           }
           else{
            $('#loadmoreIcon').hide();
            $(document).find("#no-data-box").show();
            $('#load_more_btn').hide();
            $('#search_result_box').html('');
           }
           
        },
        error:function (response) {
            $('#'+form_id).prop('disabled', false);
            $('#'+form_id).buttonLoader('stop');
            var responseJSON = response.responseJSON;
            error_message(responseJSON.msg);
            $('#loadmoreIcon').hide();

        }
    });
}

$(document).on('submit', '#add_edit_form_data', function(e) {
    e.preventDefault();

    var data = $('#add_edit_form_data').serializeArray();
    $.each(data, function(obj, item) {
        obj[item.name] = item.value;
        return obj;
    }, {});
    var url = api_url + 'Bar-restaurant/add_or_update';
    form_id = "subbutton";
    add_update_details(url, form_id, data);
});

function add_single_bar_restaurant(id) {
    var _url = api_url + 'Bar-restaurant/add_single_bar_restaurant';
    iziToast.question({
        timeout: 2000,
        close: false,
        overlay: true,
        displayMode: 'once',
        id: 'question',
        zindex: 999,
        title: 'Hey',
        message: 'Are you sure you want to Add this Bar/Restaurant?',
        position: 'center',
        buttons: [
            ['<button><b>YES</b></button>', function(instance, toast) {
        $.ajax({
            url: _url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", __token);
            },
        success: function(response) {
            if (response.status == false) {
                error_message(response.message);
            }
            if (response.status == true) {
               
               $('#rest_'+id).val(response.data.restaurantId)
            var html ='<a onclick="delete_box()" id="remove_box_'+response.data.yelpId+'" >\
                    <i class="fa fa-trash"></i>\
                    <span>Remove</span>\
                        </a>';
                 $('#after-action_'+id).remove();
                $('#add_box_' + id).hide();
                $('#remove_box_' + id).show();

                success_message_without_reload(response.message);
                        }
                    }
                });
            }, true],
            ['<button>NO</button>', function(instance, toast) {

                instance.hide({
                    transitionOut: 'fadeOut'
                }, toast, 'button');

            }],
        ],

    });
}

function view(id) {
    OpenZPanel('View');
    $.ajax({
        url: api_url + 'owner/get_details/' + id,
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", __token);
        },
        success: function(response) {
            OpenZPanel('View Owner Details');
            $(document).find('.z-panel-loader').removeClass('on');
            $(document).find('.z-panel-card-body-content').html('');
            var html = response.response;
            $(document).find('.z-panel-card-body-content').html(html);
        }
    });
}
function upload_bar_restaurant_image(file_id,filed_id,image_preview_id)
{
    var image = document.getElementById(file_id);
    var file = image.files[0];
    var fileType = file["type"];

    var url = api_url + 'Bar-restaurant/upload_image';
    upload_image(file_id, url, image_preview_id, filed_id);
    
}
function delete_box(placeId) {
    id =$('#rest_'+placeId).val();
    var url = api_url + 'Bar-restaurant/delete';

confirm_message = 'Are you sure you want to remove this Bar/Restaurant?';
delete_single_box(url, id,placeId, refresh_status = 2, confirm_message);
}
function delete_single_box(url,id,placeId,refresh_status = 2,confirm_message="")
{
    if(confirm_message == "")
    {
    confirm_message ='Are you sure you want to delete this item?';
    }
    
    iziToast.question({
        timeout: 2000,
        close: false,
        overlay: true,
        displayMode: 'once',
        id: 'question',
        zindex: 999,
        title: 'Hey',
        message: confirm_message,
        position: 'center',
        buttons: [
            ['<button><b>YES</b></button>', function (instance, toast)
            {
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        _token: $("input[name=_token]").val(),
                        id:id
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", __token);
                    },
                    success: function (response)
                    {
                        instance.hide({transitionOut: 'fadeOut'}, toast, 'button');
                        if (response.status == 400)
                        {
                            error_message(response.msg);
                        }
                        if (response.status == 200)
                        {
                            $('#after-action_'+placeId).remove();
                            if($('#remove_box_' + placeId));
                            $('#remove_box_' + placeId).hide();
                            $('#add_box_' + placeId).show();
                            success_message(response.msg,"",refresh_status);
                        }

                    }
                });
                }, true],
            ['<button>NO</button>', function (instance, toast)
            {

                instance.hide({transitionOut: 'fadeOut'}, toast, 'button');

            }],
        ],

    });
}
</script>

<script type="text/javascript">
$(function() {
    $("#filter-reset").on("click", function(e) {

        $('[name="search_text"]').val("");
        $('[name="filter_status"]').val("");
        $('[name="order_by"]').val("");
        $('#next_page_token').val('');
        search_user(1, 'FILTER');

    });
});
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{config('constants.google_api_key')}}&libraries=places"></script>
<script>
function search_custom_place()
{
    var autocomplete = new google.maps.places.Autocomplete((
            document.getElementById('Location')), {
            types: ['geocode'], //(cities)

            componentRestrictions: {'country': 'us'}
        });
}
search_custom_place();
</script>
@endsection