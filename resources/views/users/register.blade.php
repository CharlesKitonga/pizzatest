@extends('layouts.frontLayout.front_unique_design')
@section('content')

<!-- Page Title -->
<div class="page-title bg-dark dark">
    <!-- BG Image -->
    <div class="bg-image bg-parallax"><img src="assets/img/photos/bg-croissant.jpg" alt=""></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 push-lg-4">
                <h1 class="mb-0">Join us Today</h1>
            </div>
        </div>
    </div>
</div>
<div class="container" style="margin-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/register') }}">
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

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section -->
<section class="section">

    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 push-lg-1 col-md-6">
                <img src="assets/img/logo-horizontal-dark.svg" alt="" class="mb-5" width="130">
                <h4 class="mb-0">Soup Restaurant</h4>
                <span class="text-muted">Green Street 22, New York</span>
                <hr class="hr-md">
                <div class="row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <h6 class="mb-1 text-muted">Phone:</h6>
                        +48 21200 2122 221
                    </div>
                    <div class="col-sm-6">
                        <h6 class="mb-1 text-muted">E-mail:</h6>
                        <a href="#">hello@example.com</a>
                    </div>
                </div>
                <hr class="hr-md">
                <h6 class="mb-3 text-muted">Follow Us!</h6>
                <a href="#" class="icon icon-social icon-circle icon-sm icon-facebook"><i class="fa fa-facebook"></i></a>
                <a href="#" class="icon icon-social icon-circle icon-sm icon-google"><i class="fa fa-google"></i></a>
                <a href="#" class="icon icon-social icon-circle icon-sm icon-twitter"><i class="fa fa-twitter"></i></a>
                <a href="#" class="icon icon-social icon-circle icon-sm icon-youtube"><i class="fa fa-youtube"></i></a>
                <a href="#" class="icon icon-social icon-circle icon-sm icon-instagram"><i class="fa fa-instagram"></i></a>
            </div>
            <div class="col-lg-5 push-lg-2 col-md-6">
                <div id="google-map-1" class="h-500 shadow"></div>
            </div>
        </div>
    </div>

</section>
@endsection
