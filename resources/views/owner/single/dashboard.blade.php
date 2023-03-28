@extends('owner.layouts.master') 
@section('page_title') @if(isset($page_title) && !empty($page_title)) {{ $page_title }}
@else {{ config('constants.default_admin_page_title') }} @endif @endsection @section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Dashboard
    </h1>
    <!-- <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Dashboard</li>
        </ol> -->
</section>
<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-lg-6 col-md-6 col-xs-6">
            <a href="{{ url('/owner/bar_restaurant')}}" class="small-box bg-theme-1">
                <div class="inner">
                    <h3>
                    @if(isset($allbarrestaurnat)) {{ $allbarrestaurnat }} @endif
                    </h3>
                    <p>Bar/Restaurant</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cutlery"></i>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-6">
            <!-- small box -->
            <a href="{{ url('/owner/bar_restaurant')}}" class="small-box bg-theme-3">
                <div class="inner">
                    <h3>
                         @if(isset($checkedinuser)){{ $checkedinuser }}@endif
                    </h3>
                    <p>Today Checked-In Users</p>
                </div>
                <div class="icon">
                    <i class="fa fa-check-circle "></i>
                </div>
            </a>
        </div>

        {{--<div class="col-lg-3 col-md-6 col-xs-6">
            <!-- small box -->
            <a href="javascript:void(0)" class="small-box bg-theme-2">
                <div class="inner">
                    <h3>
                        <!-- @if(isset($total_order)) {{ $total_order }} @endif -->
                        0
                    </h3>
                    <p>Not Available</p>
                </div>
                <div class="icon">
                    <i class="fa fa-first-order"></i>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 col-xs-6">
            <!-- small box -->
            <a href="javascript:void(0)" class="small-box bg-theme-4">
                <div class="inner">
                    <h3>
                        <!-- @if(isset($total_tour_plan)){{ $total_tour_plan }}@endif -->
                        0
                    </h3>
                    <p>Not Available</p>
                </div>
                <div class="icon">
                    <i class="fa fa-group"></i>
                </div>
            </a>
        </div>
    </div>
        --}}
    <div class="row">
        <!-- Left col -->
        <div class="col-md-12">

            {{--<div class="latest-user-news-row">
            <div class="col-md-4" style="">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title"><b>Available</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="myChart4" style="height: 230px"></div>
                        </div>
                    </div>
                </div>
            --}}
                <div class="col-md-12">
                    <!-- USERS LIST -->
                    <div class="box box-danger bb-box min-bb">
                        <div class="box-header with-border bb-box-head">
                            <h3 class="box-title">Latest Users</h3>

                            <div class="box-tools pull-right">
                                <span class="label label-danger">{{@$allnewuserscount}} New</span>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <div id="bbscroll">
                                <ul class="users-list dashboard-users clearfix">
                               
                                    @if(@$allnewusers) @foreach($allnewusers as $row)
                                    @foreach($row as $value)
                                   
                                    <li>
                                       
                                            <img src="@if(@$u->image){{ str_replace('index.php','', url('storage/user_images/'.@$u->image)) }} @else {{ str_replace('index.php','', url('public/assets/img/placeholder-user.jpg')) }} @endif"
                                                alt="User Image">
                                                <?php $sinfo =get_user_name(@$value->userId);
                                                 $restinfo =get_restaurant_name($value->restaurantId);?>
    
                                            <span class="users-list-name">{{@$sinfo->firstName.' '.@$sinfo->lastName}}</span>
                                            <span class="users-list-name">Mobile No:{{@$sinfo->userMobile}}</span>
                                            <span class="users-list-name">Checked-In:<b>{{@$restinfo}}</b></span>
                                            <span
                                                class="users-list-date">{{ set_date_format($value->created_at) }}</span>
                                 
                                    </li>
                                    @endforeach 
                                    @endforeach @endif
                                </ul>
                            </div>
                            <!-- /.users-list -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-center">
                            {{--<a class="btn btn-sm btn-primary btn-flat pull-left"
                                href="#" class=" uppercase">View All </a>--}}
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!--/.box -->
                </div>
              
            </div>

        </div>
        <!-- /.col -->

    </div>
    <!-- /.col -->

    <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

@endsection @section('script')
<script src="{{ asset('my_assets/Chart.min.js')}}"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"></script> -->
<script type="text/javascript">
$(function() {
    if ($('#bbscroll').length != 0) {
        $('#bbscroll').slimscroll({
            height: 'auto'
        });
    }
})
$(function() {
    if ($('#myChart4').length) {
        var home = Morris.Donut({
            element: 'myChart4',
            data: [{
                value: '<?php echo @round(1); ?>',
                label: 'Active',
                color: '#26c66f',

            }, {
                value: '<?php echo @round(0); ?>',
                label: 'Inactive',
                color: '#EE1A00',

            }, ],
            resize: true,
            formatter: function(x) {
                return x
            }
        }).on('click', function(i, row) {});
        home.redraw();
    }
    // if ($('#myChart3').length) {
    //     var home = Morris.Donut({
    //         element: 'myChart3',
    //         data: [{
    //             value: '<?php echo @round($publish); ?>',
    //             label: 'Publish',
    //             color: '#26c66f'
    //         }, {
    //             value: '<?php echo @round($Unpublish); ?>',
    //             label: 'UnPublish',
    //             color: '#EE1A00'
    //         }, ],
    //         resize: true,
    //         formatter: function(x) {
    //             return x
    //         }
    //     }).on('click', function(i, row) {});
    //     home.redraw();
    // }
});
// $(document).find('#myChart4 tspan , #myChart4 text').css('font-size', '1px !important'); 
// $(document).find('#myChart4 tspan , #myChart4 text').css('padding', '16%'); 
// $('#server-donut').find('svg').css('font-size', '6px !important');
</script>
@endsection