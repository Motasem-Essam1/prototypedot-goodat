<nav id="nav" class="nav">

    <div class="container">
        <div class="navbar">
            <div class="nav-left d-flex align-items-center">
                {{--
                <?php
                    $prevRoute = app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();

                @if ($prevRoute == 'search' && Route::currentRouteName() == 'service.view' || Route::currentRouteName() == 'task.view' || Route::currentRouteName() == 'provider.profile')
                    <button onclick="history.back()" class="nav-back-btn"><span class="icon-arrow-left-2 icon"></span></button>
                @endif
                ?>--}}

                <a href="{{ route('home') }}">
                    <div class="logo-view">
                        <img class="img-fluid logo-img" src="{{ asset('assets/img/logo.png') }}" />
                    </div>
                </a>
            </div>
            @if(Auth::check())
                <div class="nav-right">
                    @if(Auth::user()->user_data->user_type == "Normal")
                        <a style="margin-right: 10px" href="{{ route('task.add.view') }}" class="button-default nav-st-btn hidden-sm hidden-xs"> + Add Task </a>
                        <a href="{{ route('account.subscription') }}" class="button-default nav-st-btn hidden-sm hidden-xs"> Became A Provider </a>
                    @else
                        <button class="button-default nav-st-btn hidden-sm hidden-xs" data-toggle="modal" data-target="#servicesTasksModal">
                            <i class="fa-reguler fa-plus icon"></i> Add Service / Task
                        </button>
                    @endif

                    <!-- Notifications -->
                    <div class="nots-container">
                        <button class="notifications-btn nc-btn btn nnc-btn">
                            <div class="nav-noti-box">
                                <span class="icon-notification icon">
{{--                                    @if(isset($notify) && $notify['is_read'] > 0)--}}
{{--                                        <span class="noti-circle"></span>--}}
{{--                                    @endif--}}
                                    <span class="active-notify noti-circle"></span>
                                </span>
                            </div>
                        </button>

                        <div id="notificationsDropdown" class="notifications-dropdown scroller">
                            <div class="ns-title cos-title"><span>Updates <span class="updates-count"></span></span></div>
                            <!-- Notifications Date -->
                            <div class="notify-data noti-users"><!-- Data Here --></div>
                            <div class="notify-action">
                                <a class="notify-show-all" href="{{ route('notifications') }}">Show All</a>
                            </div>
                        </div>
                    </div>
                    <!-- Notifications -->

                    <div class="nav-user-menu"> <!-- User Menu -->
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                <div class="nav-user-box d-flex align-items-center">
                                    <img class="img-fluid circle" src="{{ asset(Auth::user()->user_data->avatar) }}" alt="" />
                                    <div class="nav-user-name text-capitalize max-1-line">{{ Auth::user()->name }}</div>
                                    <span class="icon-arrow-bottom icon"></span>
                                </div>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item num-row" href="{{ route('account.account') }}"> <span class="icon-user icon"></span> <span class="num-title">My Account</span></a>
                                <a class="dropdown-item num-row" href="{{ route('account.service-task') }}"> <span class="icon-services icon"></span> <span class="num-title">My Services</span></a>
                                <a class="dropdown-item num-row" href="{{ route('account.my-favorites') }}"> <span class="icon-heart-empty icon"></span> <span class="num-title">My Favorites</span></a>
                                <a class="dropdown-item num-row logout-btn" href="javascript:void(0)"> <span class="icon-logout icon"></span> <span class="num-title">Logout</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="nav-right">
                    <a href="{{ route('register') }}" class="button-default hidden-sm hidden-xs"> Became A Provider </a>
                    <div class="nav-log-reg-actions flex-center-v">
                        <a href="{{route('login')}}" class="btn btn-simple">Login</a>
                        <a href="{{ route('signup') }}" class="btn btn-simple">Sign up</a>
                    </div>
                </div>
            @endif
            <!-- Navbar Mobile -->
            <div class="nav-mob">
                <div class="nav-mob-flexable flex-between-vh">
{{--                    @if(Route::is('search'))--}}
{{--                        <a class="btn {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">--}}
{{--                            <span class="icon-home icon"></span>--}}
{{--                            <div class="nmf-title">Home</div>--}}
{{--                        </a>--}}
{{--                    @else--}}
{{--                        <a class="btn {{ Route::is('search') ? 'active' : '' }}" href="{{ route('search') }}">--}}
{{--                            <span class="icon-search icon"></span>--}}
{{--                            <div class="nmf-title">Explore</div>--}}
{{--                        </a>--}}
{{--                    @endif--}}

                    @if(Route::is('home'))
                        <a class="btn {{ Route::is('search') ? 'active' : '' }}" href="{{ route('search') }}">
                            <span class="icon-search icon"></span>
                            <div class="nmf-title">Explore</div>
                        </a>
                    @else
                        <a class="btn {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <span class="icon-home icon"></span>
                            <div class="nmf-title">Home</div>
                        </a>
                    @endif

                    @if(Auth::check())
                        @if(Auth::user()->user_data->user_type == "Normal")
                            <a class="btn {{ Route::is('task.add.view') ? 'active' : '' }}" href="{{ route('task.add.view') }}">
                                <span class="icon-services icon"></span>
                                <div class="nmf-title">Add Task</div>
                            </a>
                        @else
                            <a class="btn" href="javascript:void(0)" data-toggle="modal" data-target="#servicesTasksModal">
                                <span class="icon-services icon"></span>
                                <div class="nmf-title">Services</div>
                            </a>
                        @endif
                        <a class="btn {{ Route::is('account.my-favorites') ? 'active' : '' }}" href="{{ route('account.my-favorites') }}">
                            <span class="icon-heart-empty icon"></span>
                            <div class="nmf-title">Favorites</div>
                        </a>
{{--                        <a class="btn notifications-btn notify-res-btn" href="javascript:void(0)">--}}
{{--                            <span class="active-notify noti-circle"></span>--}}
{{--                            <span class="icon-notification icon"></span>--}}
{{--                            <div class="nmf-title">Updates</div>--}}
{{--                        </a>--}}
                        <a class="btn {{ Route::is('notifications') ? 'active' : '' }}" href="{{ route('notifications') }}">
                            <span class="icon-notification icon"></span>
                            <div class="nmf-title">Updates</div>
                        </a>
                        <a class="btn {{ Route::is('account.account') ? 'active' : '' }}" href="{{ route('account.account') }}">
                            <span class="icon-user-circle icon"></span>
                            <div class="nmf-title">Profile</div>
                        </a>
                    @else
                        <a class="btn {{ Route::is('login') ? 'active' : '' }}" href="{{ route('login') }}">
                            <span class="icon-user-circle icon"></span>
                            <div class="nmf-title">Sign Up / Login</div>
                        </a>
                    @endif
                </div>
            </div>
            <!-- Navbar Mobile -->
        </div>
    </div>
</nav>
