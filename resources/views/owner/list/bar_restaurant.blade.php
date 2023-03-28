@extends('owner.layouts.master')
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
        List of Bar/Restaurant
    </h1>
</section>
<section class="content">

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header filter-form">
                    <div class="col-md-4 input-group pull-left ">
                        <input type="text" id="search_text" class="form-control" name="search_text"
                            placeholder="Search by keyword" onkeyup="search_user(1,'FILTER')" value="">
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-flat" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                    {{--<div class="col-md-2 pull-left" style="padding-right: 0">
                        <select class="form-control filter-submit" id="filter_status" name="filter_status"
                            onchange="search_user(1,'FILTER')">
                            <option value="">Search by Status</option>
                            <option value="1">Publish</option>
                            <option value="0">UnPublish</option>
                        </select>
                    </div>
                    <div class="col-md-2 pull-left" style="padding-right: 0">
                        <select class="form-control filter-submit" id="filter_owner" name="filter_status"
                            onchange="search_user(1,'FILTER')">
                            <option value="">Search by Owner</option>
                            @if(@owner)
                            @foreach(@$owner as $val)
                            <option value="{{$val->userId}}">{{$val->firstName .' '.$val->lastName}}</option>
                    @endforeach
                    @endif;
                    </select>
                </div>
                --}}
                <div class="col-md-2 pull-left ">
                    <select class="form-control filter-submit" id="order_by" name="order_by"
                        onchange="search_user(1,'FILTER')">
                        <option value="">Sort by</option>
                        <option value="created_at-asc">Date (ASC)</option>
                        <option value="created_at-desc">Date (DESC)</option>
                    </select>
                </div>
                <a id="filter-reset" href="#" class="btn btn-flat btn-default pull-left">
                    Reset</a>
                <!-- <a class="btn btn-flat btn-primary pull-right" onclick="show_add_category()"> Add New </a> -->
                <input type="hidden" id="count_increment" value="2">
            </div><!-- /.box-header -->

        </div><!-- /.box -->

        <!-- user-card-row -->
        <div class="row">

            <div class="col-xs-12">
                <div id="no-data-box" style="display:none">Record not found...!</div>
            </div>
            <div class="col-xs-12 append-users" id="search_result_box">
            </div>
            <div class="text-center" id="load_more_btn" style="display:none">
                <div>
                    <a id="loadmore-owner-bar-res" class="btn btn-primary" onclick="search_user(1,'','loadmore-owner-bar-res')">
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
var admin_url = "{{url('api/admin')}}" + '/';

function search_user(page = 1, filter_status = "" ,loadmore="") {
    // $(document).find("#loadmoreIcon").show();
     $(document).find("#no-data-box").hide();
    var url = api_url + 'Bar-restaurant/owner-search-api';
    var obj = {};
    obj.search_text = $('#search_text').val();
    obj.filter_status = $('#filter_status').val();
    obj.filter_owner = '{{$auth_user->userId}}';
    obj.order_by = $('#order_by').val();
    if (filter_status == 'FILTER')
        next_page = 1;
    search_and_load_more(url, obj, page, 'search_result_box', 'no-data-box', 'load_more_btn', 'loadmoreIcon',loadmore);
}
search_user(1, 'FILTER');

function search_and_load_more(url, searchData, page, search_box_id, no_box_id, load_btn_id, more_icon,form_id="") {
    
    if(form_id!= ""){
    $(document).find("#"+form_id).buttonLoader('start');
    $(document).find("#"+form_id).prop('disabled', true);
    }
    if (page == 1)
        page = next_page;
    next_page++;
    var limit = 10;
    page = page - 1;
    var offset = "";
    if (page == 0)
        offset = 0;
    else
        offset = page * limit;
    searchData.limit = limit;
    searchData.offset = offset;
    searchData.web_data_status = 1; // CALL FOR WEBSITE
    searchData._token = "{{ csrf_token() }}";
    if(form_id== "")
    $('#'+more_icon).show();
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'JSON',
        data: searchData,
        success: function(response) {
            if(form_id != ""){
            $('#'+form_id).prop('disabled', false);
            $('#'+form_id).buttonLoader('stop');
            }
            $(document).find('#' + no_box_id).hide();

            if (response.total == 0) {
                $('#' + no_box_id).show();
            }
            if (response.total <= limit)
                $('#' + load_btn_id).hide();
            else
                $('#' + load_btn_id).show();

            if (response.response.length < limit)
                $('#' + load_btn_id).hide();

            else
                $('#' + load_btn_id).show();

            var total_offset_value = (offset + limit);

            if (response.total == total_offset_value)
                $('#' + load_btn_id).hide();

            if (offset == 0) {
                $('#' + search_box_id).empty();
            }
            $('#' + search_box_id).append(response.html_response);
            $('#' + more_icon).hide();
            if(response.rating){
            $.each(response.rating, function(obj, item) {
                var rating = item.rating;
                $(document).find('#rateYo_'+item.restaurantId+'').rateYo({
                 rating: rating,
                 starWidth: "16px",
                 readOnly: true
                     });
             }, {});
            }
        }
    });
}

function search_and_load_more_data_for_inner2(url,searchData,page,search_box_id,no_box_id,load_btn_id,more_icon,form_id="")
{
    
    if(form_id!= ""){
    $(document).find("#"+form_id).buttonLoader('start');
    $(document).find("#"+form_id).prop('disabled', true);
    }

    var limit = 10;
    page = page - 1;
    var offset = "";
    if (page == 0){
        offset = 0;
    }
    else{
        offset = page * limit;
        }
    searchData.limit = limit;
    searchData.offset = offset;
    searchData.web_data_status = 1; // CALL FOR WEBSITE
    searchData._token = "{{ csrf_token() }}";
    if(form_id== "")
    $('#'+more_icon).show();
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'JSON',
        data:searchData,
        beforeSend: function (xhr) {
            xhr.setRequestHeader ("Authorization", __token);
        },
        success: function (response)
        {
            
            if(form_id != ""){
            $('#'+form_id).prop('disabled', false);
            $('#'+form_id).buttonLoader('stop');
            }
            $(document).find('#'+no_box_id).hide();

            if(response.total == 0){
                $('#'+no_box_id).show();
            }

            if(response.total <= limit)
            {
                $('#'+load_btn_id).hide();
            }
            else
            {
               $('#'+load_btn_id).show();
            }
            
            if(response.response.length < limit)
            {  
                $('#'+load_btn_id).hide();
            } 
            // else{
            //     $('#'+load_btn_id).show();
            // }
                

            var total_offset_value = (offset + limit);

            if(response.total == total_offset_value)
                $('#'+load_btn_id).hide();

            if(offset == 0){
                $('#'+search_box_id).empty();
            }
            $('#'+search_box_id).append(response.html_response);
            $('#'+more_icon).hide();
        },
        error:function (response) {
            if(response.status == 401)
            {
                window.location.href = base_url+'clearcache';
            }
        }

    });
}
function create_event(id) {
    OpenZPanel('Create Live Event/Special');
    $(document).find('.z-panel-loader').removeClass('on');
    $(document).find('.z-panel-card-body-content').html('');
    var html = `{!! $create_event_add_form !!}`;

    $(document).find('.z-panel-card-body-content').html(html);
    $('#restaurant_Id').val(id);
    $('input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
}

$(document).on('submit', '#add_edit_form_data', function(e) {
    e.preventDefault();
    //   console.log(values);
    var obj = {};
    obj.description = $('#description').val();
    obj.restaurantId = $('#restaurantId').val();
    obj.imageUrl = $("input[name^='imageUrl']").map(function(idx, ele) {
        return $(ele).val();
    }).get();

    // obj.latitude = $('#Latitude').val();
    obj._token = "{{ csrf_token() }}";
    var url = api_url + 'Bar-restaurant/update_bar_res';
    form_id = "subbutton";
    add_update_details(url, form_id, obj);
});
$(document).on('submit', '#add_event', function(e) {
    e.preventDefault();
    var data = $('#add_event').serializeArray();
    $.each(data, function(obj, item) {
        obj[item.name] = item.value;
        return obj;
    }, {});

    // obj.latitude = $('#Latitude').val();
    data._token = "{{ csrf_token() }}";
    var url = api_url + 'Bar-restaurant/add_update_event';
    form_id = "subbutton_event";
    add_update_details(url, form_id, data);
});
$(document).on('change', '#selectevent', function() {
    var event = $(this).val();
    $(document).find('.event').hide();
    $(document).find('.drink_special').hide();
    if (event == 1) {
        $(document).find('.event').hide();
        $(document).find('.drink_special').show();
    }
    if (event == 2) {
        $(document).find('.event').show();
        $(document).find('.drink_special').hide();
    } 
});
$(document).on('mouseover', '#start', function() {
    $('#start').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        endDate: "+2099-12-31",
    });
});
$(document).on('change', "#start", function() {
    var startdate = $("#start").val();
    $("#end").datepicker('destroy');
    $('#end').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        startDate: startdate,
        endDate: "+2099-12-31",
    });
});

function edit(id) {
    $.ajax({
        url: api_url + 'Bar-restaurant/edit_basic_details/' + id,
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", __token);
        },
        success: function(response) {
            OpenZPanel('Edit');
            $(document).find('.z-panel-loader').removeClass('on');
            $(document).find('.z-panel-card-body-content').html('');
            var html = response.response;
            $(document).find('.z-panel-card-body-content').html(html);

        }
    });

}

// function update_status(id, status, box_id) {
//     var postData = {};
//     postData.id = id;
//     postData.status = status;

//     var url = api_url + 'Bar-restaurant/update_status';
//     update_single_status(url, postData, box_id, status);
// }

$(document).on('click', '#loadmore-invite-user', function() {
    var P_age = $('#count_increment').val();
   increment(P_age)
    var id = $(document).find('#inviterestaurantid').val();
    var obj = {};
    obj.id = id;
    var url = api_url + 'Bar-restaurant/invited_user_list';
    search_and_load_more_data_for_inner2(url, obj, P_age, 'search_result_box_invite', 'no-data-box-invite','load_more_btn_invite','loadmoreIcon_invite', 'loadmore-invite-user');
});
function Invited_user(page = 0, id) {
    $('#count_increment').val(2);
    OpenZPanel('Invited User List');
    var html = loadmorehtml();
    $(document).find('.z-panel-card-body-content').html(html);
    var l_button = '<a id="loadmore-invite-user" class="btn btn-primary">\
                Load More</a>';
    $(document).find('.z-panel-card-body-content #appendloardmorebutton').html(l_button);
    var obj = {};
    obj.id = id;
    var url = api_url + 'Bar-restaurant/invited_user_list';
    search_and_load_more_data_for_inner2(url, obj, page, 'search_result_box_invite', 'no-data-box-invite', 'load_more_btn_invite',
        'loadmoreIcon_invite');
    $(document).find('.z-panel-loader').removeClass('on');
}

$(document).on('click', '#z-panel-loadmore', function() {
    var P_age = $('#count_increment').val();
   increment(P_age)
    var id = $(document).find('#checkedrestaurantid').val();
    var obj = {};
    obj.id = id;
    var url = api_url + 'Bar-restaurant/checkedinlist';
    search_and_load_more_data_for_inner2(url, obj, page, 'search_result_box_invite', 'no-data-box-invite','load_more_btn_invite','loadmoreIcon_invite', 'z-panel-loadmore');
});

function Checkedlist(page = 0, id) {
    $('#count_increment').val(2);
    OpenZPanel('Checked-In User List');
    var html = loadmorehtml();
    $(document).find('.z-panel-card-body-content').html(html);
    var l_button = '<a id="z-panel-loadmore" class="btn btn-primary">\
                Load More</a>';
    $(document).find('.z-panel-card-body-content #appendloardmorebutton').html(l_button);
    var obj = {};
    obj.id = id;
    var url = api_url + 'Bar-restaurant/checkedinlist';
    search_and_load_more_data_for_inner2(url, obj, page, 'search_result_box_invite', 'no-data-box-invite', 'load_more_btn_invite',
        'loadmoreIcon_invite');
    $(document).find('.z-panel-loader').removeClass('on');
}
// function view(id) {

//     $.ajax({
//         url: api_url + 'owner/get_details/' + id,
//         type: 'GET',
//         dataType: 'JSON',
//         beforeSend: function(xhr) {
//             xhr.setRequestHeader("Authorization", __token);
//         },
//         success: function(response) {
//             OpenZPanel('View Owner Details');
//             $(document).find('.z-panel-loader').removeClass('on');
//             $(document).find('.z-panel-card-body-content').html('');
//             var html = response.response;
//             $(document).find('.z-panel-card-body-content').html(html);
//         }
//     });
// }
$(document).on('click','.remove_image',function(){
    $('#userProfilePicture_file').val('');
    var imagename = $(this).attr('data');
    $('.imagenameUrl').find('input[value="'+imagename+'"]').remove();
    $(this).parent().parent().remove();
})
function increment(P_age)
{
$('#count_increment').val(++P_age);
}


function remove_image(id,res) {
    iziToast.question({
        timeout: 2000,
        close: false,
        overlay: true,
        displayMode: 'once',
        title: 'Hey',
        message: 'Are you sure to perform this action?',
        position: 'center',
        buttons: [
            ['<button><b>YES</b></button>', function(instance, toast) {
                $.ajax({
                    url: api_url + 'Bar-restaurant/delete_img',
                    type: 'POST',
                    dataType: 'JSON',
                    data:{id:id,res:res,_token:"{{ csrf_token() }}"},
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", __token);
                    },
                    success: function(response) {
                        instance.hide({
                            transitionOut: 'fadeOut'
                        }, toast, 'button');
                        if (response.status == 400) {
                            error_message(response.msg);
                        }
                        if (response.status == 200) {
                            $('#search_image_section_' + id).hide();
                            $('#search_image_section_' + id).empty();
                            success_message_without_reload(response.msg);
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

// function comment(id) {
//     var _token = "{{ csrf_token() }}";
//     $.ajax({
//         url: api_url + 'Bar-restaurant/get_comment',
//         type: 'POST',
//         dataType: 'JSON',
//         data: {
//             _token: _token,
//             restaurantId: id
//         },
//         success: function(response) {
//             OpenZPanel('View Comment');
//             $(document).find('.z-panel-loader').removeClass('on');
//             $(document).find('.z-panel-card-body-content').html('');
//             var html = response.response;
//             $(document).find('.z-panel-card-body-content').html(html);
//         }
//     });
// }
function comment(page=0, id) {
    $('#count_increment').val(2);
    OpenZPanel('Comments');
    var html = loadmorehtml();
    $(document).find('.z-panel-card-body-content').html(html);
    var l_button = '<a id="comment-loadmore" class="btn btn-primary">\
                Load More</a>';
    $(document).find('.z-panel-card-body-content #appendloardmorebutton').html(l_button);
    var obj = {};
    obj.restaurantId = id;
    url= api_url + 'Bar-restaurant/get_comment',
    search_and_load_more_data_for_inner2(url, obj, page, 'search_result_box_invite', 'no-data-box-invite', 'load_more_btn_invite','loadmoreIcon_invite');
    $(document).find('.z-panel-loader').removeClass('on');
}
$(document).on('click', '#comment-loadmore', function() {
    var P_age = $('#count_increment').val();
   increment(P_age)
    var id = $(document).find('#commentrestaurantid').val();
    var obj = {};
    obj.restaurantId = id;
     url= api_url + 'Bar-restaurant/get_comment',
     search_and_load_more_data_for_inner2(url, obj, P_age, 'search_result_box_invite', 'no-data-box-invite','load_more_btn_invite','loadmoreIcon_invite', 'comment-loadmore');
});
function upload_bar_restaurant_image(file_id, filed_id, image_preview_id) {
    var image = document.getElementById(file_id);
    var file = image.files[0];
    var fileType = file["type"];
    
    var validImageTypes = ["image/gif", "image/jpeg", "image/jpg", "image/png"];
    if ($.inArray(fileType, validImageTypes) < 0) {
        error_message('Allow jpeg,jpg,png and gif');
    } else {
        var url = api_url + 'Bar-restaurant/upload_image';
        upload_image(file_id, url, image_preview_id, filed_id);
    }
    

}
</script>
<script type="text/javascript">
$(function() {
    $("#filter-reset").on("click", function(e) {

        $('[name="search_text"]').val("");
        $('[name="filter_status"]').val("");
        $('[name="order_by"]').val("");
        search_user(1, 'FILTER');

    });
});
</script>
@endsection