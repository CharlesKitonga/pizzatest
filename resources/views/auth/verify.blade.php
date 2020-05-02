@extends('layouts.frontLayout.front_design')
@section('content')

    <!-- Content -->
    <div id="content">
        <!-- Section -->
        <section class="section bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 push-lg-4">
                        <span class="icon icon-xl icon-success"><i class="ti ti-check-box"></i></span>
                        <h1 class="mb-2">Succesfully Registered!!</h1>
                        <h4 class="text-muted mb-5">{{ __('Verify Your Email Address') }}</h4>
                        <div class="card-body">
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('A fresh verification link has been sent to your email address.') }}
                                </div>
                            @endif
                            <br>
                            {{ __('Before proceeding, please check your email for a verification link.') }}
                            <br><br>
                            {{ __('If you did not receive the email') }},
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary">{{ __('click here to request another') }}</button>.
                            </form>
                            <br><br>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Content / End -->
    <!-- Body Overlay -->
    <div id="body-overlay"></div>

@endsection
