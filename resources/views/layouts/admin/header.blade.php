<div class="row border-bottom" id="top_header_menu">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">

            <li class="dropdown">
                <a title="{{ trans('global.show_all_alerts') }}" onclick="" class="dropdown-toggle count-info" data-toggle="dropdown" href="javascript:void(1);">
                    <i class="fa fa-bell"></i> <span class="label label-primary"></span>
                </a>



            </li>
            <li>

                <a href="{{ url('/logout') }}"
                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out"></i> {{ trans('global.logout') }}
                </a>

                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </nav>
</div> <!-- ./row border-bottom -->