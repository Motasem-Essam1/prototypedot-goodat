@extends('layouts.app')
@section('title', 'Sign up')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/membership.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-without-top">
        <div class="membership-container">
            <div class="container">
                <div class="membership-grid">
                    <div class="membership-card mem-card-details">
                        <div class="memgrid-title ld-title">Welcome to Deft at</div>
                        <div class="memgrid-text ld-text">All the services you need in just <span>one place!</span></div>
                        <div class="memgrid-view">
                            <img class="img-fluid" src="{{ asset('assets/img/auth/auth-bg.png') }}" alt="Membership" />
                        </div>
                    </div>
                    <div class="membership-card">
                        <div class="membership-form-container text-center">
                            <div class="membership-title title">Signup easily using your <br /> social accounts</div>
                            <a href="{{ route('login.social', 'google') }}" class="membership-connect-card google-connect flex-center-vh">
                                <img class="img-fluid" src="{{ asset('assets/img/icons/google.png') }}" alt="" />
                                <div class="mcc-title">Continue With Google</div>
                            </a>

                            <ul class="p-0 m-0" style="list-style: none;">
                                    @foreach($errors->all() as $error)
                                        <li style="color: Red;">{{$error}}</li>
                                        </br>
                                    @endforeach
                            </ul>


                            <a href="{{ route('login.social', 'facebook') }}" class="membership-connect-card facebook-connect flex-center-vh">
                                <span class="icon-facebook icon white-color"></span>
                                <div class="mcc-title white-color">Continue With Facebook</div>
                            </a>

                            <!-- Title Line -->
                            <div class="title-line text-center">
                                <span class="wing-line"></span>
                                    <span class="title-line-text">OR SIGN UP WITH YOUR NUMBER</span>
                                <span class="wing-line"></span>
                            </div>

                            <a href="{{ route('register') }}" class="membership-connect-card phone-connect mb-5 flex-center-vh">
                                <span class="icon-phone icon white-color"></span>
                                <div class="mcc-title white-color">Continue Phone Number</div>
                            </a>

                            <div class="ask-account pt-4">
                                Have an account? <a class="btn btn-simple" href="{{ route('login') }}">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
