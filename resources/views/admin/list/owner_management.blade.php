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
        Owner Management
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
                    {{--
                    <div class="col-md-2 pull-left" style="padding-right: 0">
                        <select class="form-control filter-submit" id="filter_status" name="filter_status"
                            onchange="search_user(1,'FILTER')">
                            <option value="">Search by Status</option>
                            <option value="0">Block</option>
                            <option value="1">Unblock</option>
                        </select>
                    </div>
                    --}}
                    <div class="col-md-2 pull-left ">
                        <select class="form-control filter-submit" id="order_by" name="order_by"
                            onchange="search_user(1,'FILTER')">
                            <option value="">Sort by</option>
                            <option value="name-asc">Name (A-Z)</option>
                            <option value="name-desc">Name (Z-A)</option>
                            <option value="email-asc">Email (A-Z)</option>
                            <option value="email-desc">Email (Z-A)</option>
                            <option value="created_at-asc">Date (ASC)</option>
                            <option value="created_at-desc">Date (DESC)</option>
                        </select>
                    </div>
                    <a id="filter-reset" href="#" class="btn btn-flat btn-default pull-left">
                        Reset</a>
                    <a class="btn btn-flat btn-primary pull-right" onclick="show_add_category()"> Add New </a>

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
                        <a id="loadmore-owner" class="btn btn-primary" onclick="search_user(1,'','loadmore-owner')">
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
$(document).on('click', '.toggle-password', function(e) {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});

function search_user(page = 1, filter_status = "",load_more="") {

    $(document).find("#no-data-box").hide();
    var url = api_url + 'owner/search-api-owner';
    var obj = {};
    obj.search_text = $('#search_text').val();
    obj.filter_status = $('#filter_status').val();
    obj.order_by = $('#order_by').val();
    obj.report = 1;
    if (filter_status == 'FILTER')
        next_page = 1;
    search_and_load_more_data(url, obj, page, 'search_result_box', 'no-data-box', 'load_more_btn', 'loadmoreIcon',load_more);
}
search_user(1, 'FILTER');

function search2(page = 1, filter_status = ""){
    var url = api_url + 'owner/search-api-user';
    var obj = {};
    obj.search_text = $('#search_text2').val();
    if (filter_status == 'FILTER')
        next_page = 1;
    search_and_load_more_data(url, obj, page, 'search_result_box_user', 'no-data-box_user', 'load_more_btn_user', 'loadmoreIcon_user');
}
function make_owner(id)
{
    var url = api_url + 'owner/make_owner';
    confirm_message ='Are you sure you want to make this Owner?';
    delete_single_details_without_zindex(url, id, refresh_status = 3,confirm_message);
}
function show_add_category() {
    OpenZPanel('Add Owner');
    $(document).find('.z-panel-loader').removeClass('on');
    $(document).find('.z-panel-card-body-content').html('');
    var html = `{!! $owner_add_form !!}`;
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
    var url = api_url + 'owner/owner_add_or_update';
    form_id = "subbutton";
    add_update_details(url, form_id, data);
});

function edit(id) {
    $.ajax({
        url: api_url + 'owner/get_single_owner/' + id,
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", __token);
        },
        success: function(response) {
            OpenZPanel('Edit Owner');
            $(document).find('.z-panel-loader').removeClass('on');
            $(document).find('.z-panel-card-body-content').html('');
            var html = response.response;
            $(document).find('.z-panel-card-body-content').html(html);
        }
    });

}

function update_user_status(id, status, box_id) {
    var postData = {};
    postData.id = id;
    postData.status = status;

    var url = api_url + 'owner/update_status';
    if(status == 0)
    confirm_message ="Are you sure you want to block this Owner?";
    if(status == 1)
    confirm_message ="Are you sure you want to unblock this Owner?";
    update_single_status(url, postData, box_id, status,confirm_message);
}

function delete_box(id) {
    var url = api_url + 'owner/delete';
    confirm_message ='Are you sure you want to remove this Owner?';
    delete_single_details(url, id, refresh_status = 2,confirm_message);
}

function view(page = 1, id) {
    OpenZPanel('Bar/Restaurant List');
    var html = loadmorehtml();
    $(document).find('.z-panel-card-body-content').html(html);
    var obj = {};
    obj.id = id;
    var url = api_url + 'owner/view_bar_res';
    search_and_load_more_data(url, obj, page, 'search_result_box_invite', 'no-data-box-invite', 'load_more_btn_invite',
        'loadmoreIcon_invite');
    $(document).find('.z-panel-loader').removeClass('on');
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