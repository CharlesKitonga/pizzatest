<?php
use App\Http\Controllers\Controller;
$specificMenu = Controller::specificMenu();
$getMenu = Controller::getMenu();

?>
<div class="row menu-flex-row">
    <div class="col-3 col-xs-12 order-first nav-side-menu">
        <!-- Menu Navigation -->
        <nav id="menu-navigation">
            <ul class="nav nav-menu">
                @foreach($specificMenu as $menu)
                    <li><a href="{{url('/menu-list-navigation/'.$menu->id)}}" >{{$menu->category_name}}</a></li>
                @endforeach
            </ul>
            <ul class="nav nav-menu">
                @foreach($getMenu as $drinks)
                    <li><a href="{{url('/menu-list-navigation/'.$drinks->id)}}" >{{$drinks->category_name}}</a></li>
                @endforeach
            </ul>
        </nav>
    </div>
    <div class="col-8 col-xs-12 order-last">
        @include('frontpages.menu_category')
    </div>
    </div>


