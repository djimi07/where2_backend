<header class="main-header">
    <div class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>
                  <!-- <img src="{{ config('constants.logo_image') }}"> -->
            </b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
        <!-- <img src="{{ asset('default_image/logo_white.svg') }}"> -->

            <img src="{{ config('constants.logo_image') }}" style="">
            


        </span>

    </div>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" onclick="sidebar_toggle()" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                @if(isset($auth_user->id))
                    @php $notificationData = get_notification_list($auth_user->id,'ADMIN'); @endphp
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">{{ $notificationData['count'] }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have {{ $notificationData['count'] }} notifications</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    @foreach($notificationData['data'] AS $key => $value)
                                        <li>
                                            <a href="@if(isset($value->redirect_url) && !empty($value->redirect_url)){{ url('/'.$value->redirect_url) }}@else javascript:void(0) @endif">
                                                <i class="fa fa-users text-aqua"></i> {{ $value->msg }}
                                            </a>
                                        </li>
                                    @endforeach

                                </ul>
                            </li>
                            <li class="footer"><a href="{{ url('/admin/notification') }}">View all</a></li>
                        </ul>
                    </li>
                @endif
                <li class="dropdown user user-menu">
                    @php $url =  config('constants.default_user_image') @endphp
                    @if(isset($auth_user->userProfilePicture) && !empty($auth_user->userProfilePicture))
                        @php $url = config('constants.image_url').$auth_user->userProfilePicture @endphp
                    @endif
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ $url }}" id="userProfilePictureHeaderPreview" class="user-image" alt="User Image">
                        <span class="hidden-xs">@if(isset($auth_user->firstName)){{ $auth_user->firstName.' '.$auth_user->lastName }}@endif <i
                                class="caret"></i></span>
                    </a>

                    <style>
                        .sbm-user-wrapper {
                            display: block;
                            height: 100%;
                        }

                        .sbm-user-wrapper p {
                            color: #fff;
                            font-size: 16px;
                            line-height: 1.3;
                            margin-top: 5px;
                        }

                        .sbm-user-wrapper p > small {
                            display: block;
                        }

                        .dropdown-menu > li > a.sbm-user-wrapper:hover {
                            background: transparent;
                        }
                    </style>


                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">

                            <p>
                                <a class="heading"
                                   href="{{ url('admin/profile') }}">Admin</a>

                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="link-list">
                                <a href="{{ url('admin/profile') }}"><i class="fa fa-user"></i> View Profile</a>
                                <a href="{{ url('admin/change-password') }}"><i class="fa fa-lock"></i> Change
                                    Password</a>
                                <a href="javascript:void(0)" onclick="logout_admin()">
                                    <i class="fa fa-power-off"></i> Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>


