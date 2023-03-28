@extends('admin.layouts.master') 
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
        <div class="col-lg-4 col-md-6 col-xs-6">
            <!-- small box -->
            <a href="{{ url('/admin/user_management')}}" class="small-box bg-theme-3">
                <div class="inner">
                    <h3>
                        @if(isset($total_users)){{ $total_users }}@endif
                      
                    </h3>
                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-6 col-xs-6">
            <!-- small box -->
            <a href="{{ url('/admin/owner_management')}}" class="small-box bg-theme-2">
                <div class="inner">
                    <h3>
                        @if(isset($total_owner)) {{ $total_owner }} @endif
                  
                    </h3>
                    <p>Total Owners</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-6 col-xs-6">
            <!-- small box -->
            <a href="{{ url('/admin/view_bar_restaurant')}} " class="small-box bg-theme-4">
                <div class="inner">
                    <h3>
                        @if(isset($total_bar_restaurant)){{ $total_bar_restaurant }}@endif
    
                    </h3>
                    <p>Total Bar/Restaurant</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cutlery"></i>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <!-- Left col -->
        <div class="col-md-12">

            <div class="latest-user-news-row">
           
                <div class="col-md-6">
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
                                    @if(@$allnewusers) @foreach($allnewusers as $value)

                                    <li>
                                        <!-- <a href="{{url('/admin/user_management')}}"> -->
                                            <img src="@if(@$u->image){{ str_replace('index.php','', url('storage/user_images/'.@$u->image)) }} @else {{ str_replace('index.php','', url('public/assets/img/placeholder-user.jpg')) }} @endif"
                                                alt="User Image">
                                            <span class="users-list-name">{{ @$value->firstName .' '.@$value->lastName }}</span>

                                            <span
                                                class="users-list-date">{{ set_date_format($value->created_at) }}</span>
                                        <!-- </a> -->
                                    </li>

                                    @endforeach @endif
                                </ul>
                            </div>
                            <!-- /.users-list -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-center">
                            <a class="btn btn-sm btn-primary btn-flat pull-left"
                                href="{{ url('admin/user_management') }}" class=" uppercase">View All </a>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!--/.box -->
                </div>
                <div class="col-md-6">
                    <!-- USERS LIST -->
                    <div class="box box-danger bb-box min-bb">
                        <div class="box-header with-border bb-box-head">
                            <h3 class="box-title">Latest Owners</h3>

                            <div class="box-tools pull-right">
                                <span class="label label-danger">{{@$allnewuserscount}} New</span>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <div id="bbscroll">
                                <ul class="users-list dashboard-users clearfix">
                                    @if(@$allnewowner) @foreach($allnewowner as $value)

                                    <li>
                                        <!-- <a href="{{url('/admin/owner_management')}}"> -->
                                            <img src="@if(@$u->image){{ str_replace('index.php','', url('storage/user_images/'.@$u->image)) }} @else {{ str_replace('index.php','', url('public/assets/img/placeholder-user.jpg')) }} @endif"
                                                alt="User Image">
                                            <span class="users-list-name">{{@$value->firstName .' '.@$value->lastName }}</span>

                                            <span
                                                class="users-list-date">{{ set_date_format($value->created_at) }}</span>
                                        <!-- </a> -->
                                    </li>

                                    @endforeach @endif
                                </ul>
                            </div>
                            <!-- /.users-list -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-center">
                            <a class="btn btn-sm btn-primary btn-flat pull-left"
                                href="{{ url('admin/owner_management') }}" class=" uppercase">View All </a>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!--/.box -->
                </div>
                <div class="col-md-4" style="">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title"><b>Available Owners</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="myChart4" style="height: 230px"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" style="">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title"><b>Available Users</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="myChart5" style="height: 230px"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" style="">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title"><b>Available Bar/Restaurant</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="myChart6" style="height: 230px"></div>
                        </div>
                    </div>
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
    if ($('#myChart5').length) {
        var home = Morris.Donut({
            element: 'myChart5',
            data: [{
                value: '<?php echo @round($Useractive); ?>',
                label: 'Active',
                color: '#26c66f',

            }, {
                value: '<?php echo @round($Userdeactive); ?>',
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
    if ($('#myChart6').length) {
        var home = Morris.Donut({
            element: 'myChart6',
            data: [{
                value: '<?php echo @round($Publish); ?>',
                label: 'Publish',
                color: '#26c66f'
            }, {
                value: '<?php echo @round($UnPublish); ?>',
                label: 'Unpublish',
                color: '#EE1A00'
            }, ],
            resize: true,
            formatter: function(x) {
                return x
            }
        }).on('click', function(i, row) {});
        home.redraw();
    }
    if ($('#myChart4').length) {
        var home = Morris.Donut({
            element: 'myChart4',
            data: [{
                value: '<?php echo @round($Owneractive); ?>',
                label: 'Active',
                color: '#26c66f'
            }, {
                value: '<?php echo @round($Ownerdeactive); ?>',
                label: 'Inactive',
                color: '#EE1A00'
            }, ],
            resize: true,
            formatter: function(x) {
                return x
            }
        }).on('click', function(i, row) {});
        home.redraw();
    }
});
</script>
@endsection