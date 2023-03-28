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
        View Bar/Restaurant
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
                    <div class="col-md-2 pull-left" style="padding-right: 0">
                        <select class="form-control filter-submit" id="filter_status" name="filter_status"
                            onchange="search_user(1,'FILTER')">
                            <option value="">Search by Status</option>
                            <option value="1">Publish</option>
                            <option value="0">Unpublish</option>
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
                    <div class="col-md-2 pull-left ">
                        <select class="form-control filter-submit" id="order_by" name="order_by"
                            onchange="search_user(1,'FILTER')">
                            <option value="">Sort by</option>
                            <option value="name-asc">Name (ASC)</option>
                            <option value="name-desc">Name (DESC)</option>
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
                        <a id="load-more-bar-res" class="btn btn-primary" onclick="search_user(1,'','load-more-bar-res')">
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
function search_user(page = 1, filter_status = "",load_more="") {
    $(document).find("#no-data-box").hide();
    var url = api_url + 'Bar-restaurant/search-api';
    var obj = {};
    obj.search_text = $('#search_text').val();
    obj.filter_status = $('#filter_status').val();
    obj.filter_owner = $('#filter_owner').val();
    obj.order_by = $('#order_by').val();
    if (filter_status == 'FILTER')
        next_page = 1;
    search_and_load_more_data(url, obj, page, 'search_result_box', 'no-data-box', 'load_more_btn', 'loadmoreIcon',load_more);
}
search_user(1, 'FILTER');

function show_add_category() {
    OpenZPanel('Add');
    $(document).find('.z-panel-loader').removeClass('on');
    $(document).find('.z-panel-card-body-content').html('');
    var html = ''
    $(document).find('.z-panel-card-body-content').html(html);
    $('input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
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

function edit(id) {
    $.ajax({
        url: api_url + 'Bar-restaurant/edit/' + id,
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

function update_status(id, status, box_id) {
    var postData = {};
    postData.id = id;
    postData.status = status;

    var url = api_url + 'Bar-restaurant/update_status';
    if (status == 0)
        confirm_message = "Are you sure you want to unpublish this Bar/Restaurant?";
    if (status == 1)
        confirm_message = "Are you sure you want to publish this Bar/Restaurant?";
    update_single_status(url, postData, box_id, status, confirm_message);
}

function add_owner(form_id) {
    var postData = {};
    postData.ownerId = $(document).find("#owner-Id").val();
    postData.restaurantId = $(document).find("#restaurant-Id").val();
    var url = api_url + 'Bar-restaurant/add_update_assign_owner';
    add_update_details(url, form_id, postData);
}

function delete_box(id) {

    var url = api_url + 'Bar-restaurant/delete';

    confirm_message = 'Are you sure you want to delete this Bar/Restaurant?';
    delete_single_details(url, id, refresh_status = 2, confirm_message);
}

$(document).on('click', '#loadmore-invite-user', function() {
    var P_age = $('#count_increment').val();
   increment(P_age)
    var id = $(document).find('#inviterestaurantid').val();
    var obj = {};
    obj.id = id;
    var url = api_url + 'Bar-restaurant/invited_user_list';
    search_and_load_more_data_for_inner(url, obj, P_age, 'search_result_box_invite', 'no-data-box-invite','load_more_btn_invite','loadmoreIcon_invite', 'loadmore-invite-user');
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
    search_and_load_more_data_for_inner(url, obj, page, 'search_result_box_invite', 'no-data-box-invite', 'load_more_btn_invite','loadmoreIcon_invite');
    $(document).find('.z-panel-loader').removeClass('on');
}

function remove_image(id, res) {
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
                    data: {
                        id: id,
                        res: res,
                        _token: "{{ csrf_token() }}"
                    },
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
$(document).on('click', '.remove_image', function() {
    $('#userProfilePicture_file').val('');
    var imagename = $(this).attr('data');
    $('.imagenameUrl').find('input[value="' + imagename + '"]').remove();
    $(this).parent().parent().remove();
})

$(document).on('click', '#z-panel-loadmore', function() {
    var P_age = $('#count_increment').val();
   increment(P_age)
    var id = $(document).find('#checkedrestaurantid').val();
    var obj = {};
    obj.id = id;
    var url = api_url + 'Bar-restaurant/checkedinlist';
    search_and_load_more_data_for_inner(url, obj, P_age, 'search_result_box_invite', 'no-data-box-invite','load_more_btn_invite','loadmoreIcon_invite', 'z-panel-loadmore');
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
    search_and_load_more_data_for_inner(url, obj, page, 'search_result_box_invite', 'no-data-box-invite', 'load_more_btn_invite','loadmoreIcon_invite');
    $(document).find('.z-panel-loader').removeClass('on');
}

function comment(page = 0, id) {
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
    search_and_load_more_data_for_inner(url, obj, page, 'search_result_box_invite', 'no-data-box-invite', 'load_more_btn_invite','loadmoreIcon_invite');
    $(document).find('.z-panel-loader').removeClass('on');
}
$(document).on('click', '#comment-loadmore', function() {
   var P_age = $('#count_increment').val();
   increment(P_age)
    var id = $(document).find('#commentrestaurantid').val();
    var obj = {};
    obj.restaurantId = id;
     url= api_url + 'Bar-restaurant/get_comment',
     search_and_load_more_data_for_inner(url, obj, P_age, 'search_result_box_invite', 'no-data-box-invite','load_more_btn_invite','loadmoreIcon_invite', 'comment-loadmore');
    
    });
    function increment(P_age)
    {
        $('#count_increment').val(++P_age);
    }
function view(id) {
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

function assign_owner(id) {
    $.ajax({
        url: api_url + 'Bar-restaurant/assign_owner/' + id,
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", __token);
        },
        success: function(response) {
            OpenZPanel('Assign Bar Owner');
            $(document).find('.z-panel-loader').removeClass('on');
            $(document).find('.z-panel-card-body-content').html('');
            var html = response.response;
            $(document).find('.z-panel-card-body-content').html(html);
            $('#owner-Id').find('option[value=' + response.data + ']').attr("selected", "selected");
        }
    });
}
function upload_bar_restaurant_image(file_id, filed_id, image_preview_id) {
    var image = document.getElementById(file_id);
    var file = image.files[0];
    var fileType = file["type"];

    var url = api_url + 'Bar-restaurant/upload_image';
    upload_image(file_id, url, image_preview_id, filed_id);

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