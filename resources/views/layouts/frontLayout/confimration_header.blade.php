<?php
use App\Http\Controllers\Controller;
$mainCategories = Controller::mainCategories();
$categories = Controller::categories();
$getMenu = Controller::getMenu();

?>
<!-- Header -->
<header id="header" class="gray">

    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <!-- Logo -->
                <div class="module module-logo light">
                    <a href="{{url('/')}}">
                    </a>
                </div>
            </div>
            <div class="col-md-7">

            </div>
            <div class="col-md-2">
                <nav class="module module-navigation left mr-4">
                    <ul id="nav-main" class="nav nav-main">
                        <li class="has-dropdown">
                            <a href="javascript:void(0);">Account</a>
                            <div class="dropdown-container">
                                <ul class="dropdown-mega">
                                    @if(empty(Auth::check()))
                                        <li><a href="#loginHeaderModal" data-toggle="modal">Login</a></li>
                                        <li><a href="#registerHeaderModal" data-toggle="modal">Register</a></li>
                                    @else
                                        <li ><a href="{{url('/account')}}" >My Account Details</a></li>
                                        <li ><a href="{{url('/order-details')}}" ><i class="#"></i> My Orders</a></li>
                                        <li ><a href="{{url('/user-logout')}}"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                                    @endif
                                </ul>
                                <div class="dropdown-image">
                                    <img src="{{asset('assets/img/photos/dropdown-about.jpg')}}" alt="">
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

</header>
<!-- Header / End -->

<!-- Header -->
<header id="header-mobile" class="light">
    <div class="module module-logo">
        <a href="{{url('/')}}">
            <img src="{{asset('assets/img/KiliMobileLogo.png')}}" alt="">
        </a>
        <nav class="module module-navigation right mr-4">
            <ul id="nav-main" class="nav nav-main">
                <li class="has-dropdown">
                    @if(Auth::check())
                        <a href="javascript:void(0);"> {{ Auth::user()->name }}</a>
                        @else
                        <a href="javascript:void(0);">Sign in/Register</a>
                    @endif
                    <div class="dropdown-container">
                        <ul class="dropdown-mega">
                            @if(empty(Auth::check()))
                                <li><a href="#loginHeaderModal" data-toggle="modal">Login</a></li>
                                <li><a href="#registerHeaderModal" data-toggle="modal">Register</a></li>
                            @else
                                <li ><a href="{{url('/account')}}" >My Account Details</a></li>
                                <li ><a href="{{url('/order-details')}}" ><i class="#"></i> My Orders</a></li>
                                <li ><a href="{{url('/user-logout')}}"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                            @endif
                        </ul>
                        <div class="dropdown-image">
                            <img src="{{asset('assets/img/photos/dropdown-about.jpg')}}" alt="">
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</header>
<!-- Header / End -->

<!-- Modal / Login -->
<div class="modal " id="loginHeaderModal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Login</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ti-close"></i></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">{{ old('remember') ? 'checked' : '' }}

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember me') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="col-md-10">
                            <button type="submit" class="btn btn-primary">
                                    <span>
                                        {{ __('Login') }}
                                    </span>
                            </button>
                            <br />
                            @if (Route::has('password.request'))
                                <a style="margin-left: -30px;" class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                            <br />
                            <a data-toggle="modal" href="#registerHeaderModal" class="btn btn-link">Join us Today</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal / Register -->
<div class="modal fade" id="registerHeaderModal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Register</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ti-close"></i></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('register')}}">
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
