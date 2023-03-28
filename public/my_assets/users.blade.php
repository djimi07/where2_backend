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
           Users
        </h1>
    </section>
    <section class="content">

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header filter-form">
                        <div class="col-md-4 input-group pull-left ">
                            <input type="text" id="search_text" class="form-control" name="search_text"
                                   placeholder="Search User by Name, Email,Mobile No" onkeyup="search_user(1,'FILTER')"
                                   value="">
                            <span class="input-group-btn">
                               <button class="btn btn-default btn-flat" type="submit">
                                <i class="fa fa-search"></i>
                               </button>
                            </span>
                        </div>
                        <!-- <div class="col-md-2 pull-left" style="padding-right: 0">
                            <select class="form-control filter-submit" id="filter_status" name="filter_status" onchange="search_user(1,'FILTER')">
                                <option value="">--Search by Status--</option>
                                <option value="1">Active</option>
                                <option value="0">Block</option>
                            </select>
                        </div> -->
                        <div class="col-md-2 pull-left ">
                            <select class="form-control filter-submit" id="order_by" name="order_by" onchange="search_user(1,'FILTER')">
                                <option value="">--Sort by--</option>
                                <option value="created_at-asc">Date (ASC)</option>
                                <option value="created_at-desc">Date (DESC)</option>
                            </select>
                        </div>
                        <a id="filter-reset" href="Javascript:void()"
                           class="btn btn-flat btn-default pull-left">
                            Reset</a>

                    </div><!-- /.box-header -->

                </div><!-- /.box -->

                <!-- user-card-row -->
                <div class="row">

                    <div class="col-xs-12">
                        <div id="no-data-box" style="display: none;">Record not found...!</div>
                    </div>
                    <div class="col-xs-12 append-users" id="search_result_box">



                    </div>

                    <div class="text-center" id="load_more_btn">
                        <a id="loadmore" class="btn btn-primary" onclick="search_user(1)">
                            Load More</a>
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
        var user_default_image = '{{ config('constants.default_user_image') }}';
    </script>
    <script>

        function search_user(page = 1,filter_status = "")
        {
            var url = api_url+'users/search-api';
            var obj = {};
            obj.search_text = $('#search_text').val();
            obj.filter_status = $('#filter_status').val();
            obj.order_by = $('#order_by').val();
            if(filter_status == 'FILTER')
                next_page = 1;
            search_and_load_more_data(url,obj,page,'search_result_box','no-data-box','load_more_btn','loadmoreIcon');
        }
        search_user(1,'FILTER');

        function show_detalis_box(id)
        {
            $.ajax({
                url: api_url+'get_users_answer/'+id,
                type: 'GET',
                dataType: 'JSON',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader ("Authorization", __token);
                },
                success: function (response)
                {
                    OpenZPanel('View User Answer');
                    $(document).find('.z-panel-loader').removeClass('on');
                    $(document).find('.z-panel-card-body-content').html('');
                    var html = response.response;
                    $(document).find('.z-panel-card-body-content').html(html);
                    search_custom_place();
                }
            });

        }
         function delete_user_box(id)
        {
            var url = api_url+'delete_single_user';
            delete_single_details(url,id,refresh_status = 2);
        }

        function show_user_detalis(id)
        {
            $.ajax({
                url: api_url+'get_users_details/'+id,
                type: 'GET',
                dataType: 'JSON',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader ("Authorization", __token);
                },
                success: function (response)
                {
                    OpenZPanel('View User Details');
                    $(document).find('.z-panel-loader').removeClass('on');
                    $(document).find('.z-panel-card-body-content').html('');
                    var html = response.response;
                    $(document).find('.z-panel-card-body-content').html(html);
                    // search_custom_place();
                }
            });
        }

        // function add_update_provider_details(form_id)
        // {
        //     var postData = {};
        //     postData.userId = $('#userId').val();
        //     postData.firstName = $('#firstName').val();
        //     postData.lastName = $('#lastName').val();
        //     postData.userMobile = $('#userMobile').val();
        //     postData.password = $('#password').val();
        //     postData.password_confirmation = $('#password_confirmation').val();
        //     postData.userProfilePicture = $('#userProfilePicture').val();
        //     postData.userAddress = $('#userAddress').val();
        //     postData.city = $('#city').val();
        //     postData.state = $('#state').val();
        //     postData.zip_code = $('#zip_code').val();
        //     var url = api_url+'provider/add-update-api';
        //     add_update_details(url,form_id,postData);

        // }

        // function upload_user_image(file_id,filed_id,image_preview_id)
        // {
        //     var image = document.getElementById(file_id);
        //     var file = image.files[0];
        //     var fileType = file["type"];

        //     var validImageTypes = ["image/gif", "image/jpeg","image/jpg", "image/png"];
        //     if ($.inArray(fileType, validImageTypes) < 0) {
        //         error_message('Allow jpeg,jpg,png and gif');
        //     }
        //     else {
        //         var url = api_url + 'upload-image';
        //         upload_image(file_id, url, image_preview_id, filed_id);
        //     }
        // }

        // function update_user_status(id,status,box_id)
        // {
        //     var postData = {};
        //     postData.userId = id;
        //     postData.userStatus = status;

        //     var url = api_url+'provider/update-status-api';
        //     update_single_status(url,postData,box_id,status);
        // }


        /*  function add_user() {

              OpenZPanel('Add New');
              $(document).find('.z-panel-loader').removeClass('on');
              $(document).find('.z-panel-card-body-content').html('');
              var user_image = user_default_image;
{{--var html = `{!! $form_html !!}`;--}}
        $(document).find('.z-panel-card-body-content').html(html);
        /!*   $("#search_all_city").select2("destroy");*!/
        // search_city();
        search_custom_place();
    }*/
    </script>
    <script type="text/javascript">

        $(function () {
            $("#filter-reset").on("click", function (e) {

                $('[name="search_text"]').val("");
                $('[name="filter_status"]').val("");
                $('[name="order_by"]').val("");
                search_user(1,'FILTER');

            });
        });
    </script>
@endsection
