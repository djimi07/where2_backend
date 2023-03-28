@php $uri_segment_2 =  Request::segment(2)  @endphp
@php $uri_segment_3 =  Request::segment(3)  @endphp
<aside class="main-sidebar">
    <section class="sidebar" >
        <ul class="sidebar-menu" data-widget="tree">
            <li class="@if($uri_segment_2=='dashboard')active @endif">
                <a href="{{ url('admin/dashboard')}}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard </span>
                </a>
            </li>
            <li class="@if($uri_segment_2=='user_management')active @endif">
                <a href="{{ url('admin/user_management')}}">
                    <i class="fa fa-group"></i> <span>User Management </span>
                </a>
            </li>
            <li class="@if($uri_segment_2=='owner_management')active @endif">
                <a href="{{ url('admin/owner_management')}}">
                    <i class="fa fa-users "></i> <span>Owner Management </span>
                </a>
            </li>
        
            <li class="treeview @if($uri_segment_2=='add_bar_restaurant')active @endif @if($uri_segment_2=='view_bar_restaurant')active @endif ">
                <a href="">
                    <i class="fa fa-cutlery"></i><span>Bar/Restaurant Manage</span>
                    <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu">
                    <li class="@if($uri_segment_2=='add_bar_restaurant') active @endif">
                        <a href="{{ url('admin/add_bar_restaurant')}}">
                            <i class="fa fa-circle"></i> <span>ADD</span>
                        </a>
                    </li>
                    <li class="@if($uri_segment_2=='view_bar_restaurant') active @endif">
                        <a href="{{ url('admin/view_bar_restaurant')}}">
                            <i class="fa fa-circle"></i> <span>VIEW</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="@if($uri_segment_2=='comment_management')active @endif">
                <a href="{{ url('admin/comment_management')}}">
                    <i class="fa fa-comments-o"></i><span>Comment Management</span>
                </a>
            </li>
         <li class="treeview @if($uri_segment_2=='terms_condition')active @endif @if($uri_segment_2=='privacy_policy')active @endif ">
                <a href="#">
                    <i class="fa fa-th"></i> <span>CMS</span>
                    <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu">
                    <!-- <li class="@if($uri_segment_3=='terms_conditions') active @endif">
                        <a href="{{ url('admin/terms_conditions') }}">
                            <i class="fa fa-circle"></i> <span>Terms & Conditions</span>
                        </a>
                    </li> -->
                    <li class="@if($uri_segment_2=='privacy_policy') active @endif">
                        <a href="{{ url('admin/privacy_policy') }}">
                            <i class="fa fa-circle"></i> <span>Privacy Policy</span>
                        </a>
                    </li>
                </ul>
            </li>


            <li>
                <a href="javascript:void(0)" onclick="logout_admin()">
                    <i class="fa fa-sign-out"></i>
                    <span>Sign out</span>
                </a>

            </li>
        </ul>
    </section>

</aside>
