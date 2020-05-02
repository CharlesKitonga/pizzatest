@extends('layouts.frontLayout.confirmation_layout')
@section('content')
    <!-- Content -->
    <div id="content">
        <!-- Section -->
        <section class="section bg-light">
            <div class="container">

            <div style="margin-top: 60px; align-items: center;justify-content: center;display: flex">
            <img src="{{asset('assets/img/svg/Group15.svg')}}" class="img-fluid" alt="Thank you image">
            </div>
            <br>
            <br>
            <h3 class="text-center"><b>Thank you for your order!</b></h3>
            <h4 class="mb-5 text-center">You will recieve it in 30 minutes.</h4>
            <div style="align-items: center; justify-content: center ; display: flex ;">
            <a href="{{url('/')}}" ><button type="button"  style="width: 300px;" class="btn btn-dark btn-block">Go back to menu</button></a>

                    </div>

            </div>
        </section>
    </div>
    <!-- Content / End -->
    <!-- Body Overlay -->
    <div id="body-overlay"></div>


@endsection
