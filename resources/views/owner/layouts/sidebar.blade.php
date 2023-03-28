@php $uri_segment_2 =  Request::segment(2)  @endphp
@php $uri_segment_3 =  Request::segment(3)  @endphp
<aside class="main-sidebar">
    <section class="sidebar" >
        <ul class="sidebar-menu" data-widget="tree">
            <li class="@if($uri_segment_2=='dashboard')active @endif">
                <a href="{{ url('owner/dashboard')}}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard </span>
                </a>
            </li>
            <li class="@if($uri_segment_2=='bar_restaurant')active @endif">
                <a href="{{ url('owner/bar_restaurant')}}">
                    <i class="fa fa-cutlery"></i> <span>List of Bar/Restaurant </span>
                </a>
            </li>
            {{--
            <li class="@if($uri_segment_2=='owner_management')active @endif">
                <a href="{{ url('owner/owner_management')}}">
                    <i class="fa fa-file"></i> <span>Owner Management </span>
                </a>
            </li>
           <!-- <li class="@if($uri_segment_2=='bar_restaurant_management')active @endif">
                <a href="{{ url('owner/bar_restaurant_management')}}">
                    <i class="fa fa-tasks"></i><span>Bar Restaurant Management</span>
                </a>
            </li> -->
            <li class="treeview @if($uri_segment_2=='add_bar_restaurant')active @endif @if($uri_segment_2=='view_bar_restaurant')active @endif ">
                <a href="">
                    <i class="fa fa-th"></i> <span>Bar Restaurant</span>
                    <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu">
                    <li class="@if($uri_segment_2=='add_bar_restaurant') active @endif">
                        <a href="{{ url('owner/add_bar_restaurant')}}">
                            <i class="fa fa-circle"></i> <span>ADD</span>
                        </a>
                    </li>
                    <li class="@if($uri_segment_2=='view_bar_restaurant') active @endif">
                        <a href="{{ url('owner/view_bar_restaurant')}}">
                            <i class="fa fa-circle"></i> <span>View</span>
                        </a>
                    </li>
                </ul>
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
                        <a href="{{ url('owner/terms_conditions') }}">
                            <i class="fa fa-circle"></i> <span>Terms & Conditions</span>
                        </a>
                    </li> -->
                    <li class="@if($uri_segment_2=='privacy_policy') active @endif">
                        <a href="{{ url('owner/privacy_policy') }}">
                            <i class="fa fa-circle"></i> <span>Privacy Policy</span>
                        </a>
                    </li>
                </ul>
            </li>
         --}}

            <li>
                <a href="javascript:void(0)" onclick="logout_owner()">
                    <i class="fa fa-sign-out"></i>
                    <span>Sign out</span>
                </a>

            </li>
        </ul>
    </section>

</aside>
