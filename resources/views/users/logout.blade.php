@extends('layouts.frontLayout.front_unique_design')
@section('content')
    <!-- Content -->
    <div id="content">
        <!-- Section -->
        <section class="section bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 push-lg-4">
                        <span class="icon icon-xl icon-success"><i class="ti ti-check-box"></i></span>
                        <h4 class="text-muted mb-5">You Have Succesfully logged out..</h4>
                        <a href="{{url('/')}}"  class="btn btn-outline-secondary"><span>Go back to menu</span></a>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Content / End -->
    <!-- Body Overlay -->
    <div id="body-overlay"></div>

@endsection
