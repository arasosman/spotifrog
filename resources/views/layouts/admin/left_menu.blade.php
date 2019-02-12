<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element text-center">
							<span>
								<img alt="image" class="img-circle" style="max-height: 48px; max-width: 48px;" src="/img/avatar/user/{{ Auth::user()->avatar }}" />
                            </span>


                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<span class="clear">
									<span class="block m-t-xs">
										<strong class="font-bold">{{ Auth::user()->name }}</strong>
										<b class="caret"></b>
									</span>
                                    <!--<span class="text-muted text-xs block">Art Director <b class="caret"></b></span> -->
								</span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">

                        <li><a href="/admin/my_profile"><i class="fa fa-user"></i> {{ trans('global.profile') }}</a></li>
                        <li class="divider"></li>

                        <li><a href="{{ url('/logout') }}"
								   onclick="event.preventDefault();
																 document.getElementById('logout-form').submit();">
									<i class="fa fa-sign-out"></i> {{ trans('global.logout') }}
								</a>
						</li>
                    </ul>

                </div>
                <div class="logo-element">
                    <a href="#" target="_blank"> EE </a>
                </div>
            </li>
            <li>
                <a href="/admin"><i class="fa fa-home"></i> <span class="nav-label">Dashboard</span></a>
            </li>

            <li>
                <a href="/"><i class="fa fa-list"></i> <span class="nav-label">Visit Page</span></a>
            </li>


            <div class="hr-line-dashed"></div>
            <div class="text-center" style="padding:5px;">

                </div>
        </ul>

    </div>
</nav>
