@extends('layouts.app')
@section('title', 'Login')
@section('main-style')
{{--    <link rel="stylesheet" href="{{ asset('assets/css/telephone-validation.css') }}" />--}}
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
                            <a href="{{ route('login.social', 'google') }}" class="membership-connect-card google-connect flex-center-vh">
                                <img class="img-fluid" src="{{ asset('assets/img/icons/google.png') }}" alt="" />
                                <div class="mcc-title">Continue With Google</div>
                            </a>

                            <a href="{{ route('login.social', 'facebook') }}" class="membership-connect-card facebook-connect flex-center-vh">
                                <span class="icon-facebook icon white-color"></span>
                                <div class="mcc-title white-color">Continue With Facebook</div>
                            </a>

                            <!-- Title Line -->
                            <div class="title-line text-center">
                                <span class="wing-line"></span>
                                    <span class="title-line-text">OR LOGIN WITH PHONE</span>
                                <span class="wing-line"></span>
                            </div>
                            <div class="membership-form-content text-left">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
{{--                                    <div class="form-group-row mb-4">--}}
{{--                                        <label class="fgr-label">Phone Number</label>--}}
{{--                                        <input id="phoneNumber" class="fgr-input" type="tel" name="phone_number" required autocomplete="none" />--}}
{{--                                        <input type="hidden" id="phone_code" name="country_code">--}}
{{--                                        <p style="color: red">--}}
{{--                                            @if($errors->has('phone_number'))--}}
{{--                                                {{$errors->first('phone_number')}}--}}
{{--                                            @endif--}}
{{--                                        </p>--}}
{{--                                    </div>--}}


                                    <!-- Phone Dropdown -->
                                    <div class="form-group-row mb-4">
                                        <label class="fgr-label">Phone Number</label>
                                        <div class="input-phone-block d-flex">
                                            <select class="country-code-select" id="countryCode" name="country_code" data-value="{{ old('country_code', '353') }}"></select>
                                            <input class="input-phone-value only-numbers" type="text" name="phone_number" value="{{ old('phone_number') }}" autocomplete="none" placeholder="Enter number phone" required />
                                        </div>
                                        <label id="phone_number-error" class="error validation-error" for="phone_number"></label>
                                        <p style="color: red">
                                            @if($errors->has('phone_number'))
                                                {{$errors->first('phone_number')}}
                                            @endif
                                        </p>
                                    </div>
                                    <!-- Phone Dropdown -->

                                    <div class="form-group-row mb-3">
                                        <label class="fgr-label">Password</label>
                                        <div class="password-display">
                                            <input class="fgr-input" type="password" name="password" placeholder="Enter Your Password" required autocomplete="new-password" />
                                            <button type="button" class="password-display-btn">
                                                <i class="fa-solid fa-eye-slash password-eye"></i>
                                            </button>
                                        </div>
                                        <label id="password-error" class="error validation-error" for="password"></label>
                                    </div>
                                    <div class="keeped-forgot flex-between-vh mb-5">
                                        <div class="checkAll checkbox-group flex-center-vh">
                                            <div class="fake-checkbox">
                                                <input id="modalCheckbox1" type="checkbox" name="remember" {{ old('remember') !== null ? 'checked="true"' : '' }} class="input-checkbox" />
                                                <div class="fake-cb-child">
                                                    <span></span>
                                                </div>
                                            </div>
                                            <label class="checkbox-text" for="modalCheckbox1">Keep me logged in</label>
                                        </div>
                                        <a class="forgot-password-link" href="{{ route('password.request') }}">Forgot password</a>
                                    </div>

                                    <div class="form-group-row mb-3">
                                        <button type="submit" class="btn btn-bg width-fluid">Login</button>
                                    </div>
                                </form>

                            </div>

                            <div class="ask-account pt-4">
                                Don’t have an account? <a class="btn btn-simple" href="{{ route('signup') }}">Sign up</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
{{--    <script src="{{ asset('assets/js/telephone-validation.js') }}"></script>--}}
    <script>
        $(document).ready(function () {
            $("form").validate({
                rules: {
                    phone_number: {
                        required: true,
                        minlength: 7
                    },
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    phone_number: {
                        required: "Please enter your phone number",
                        minlength: "Your phone number must be at least 7 numbers"
                    },
                    password: {
                        required: "Please enter a password",
                        minlength: "Your password must be at least 6 characters"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection

