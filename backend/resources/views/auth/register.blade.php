@extends('layouts.app')
@section('title', 'Register')
@section('main-style')
{{--    <link rel="stylesheet" href="{{ asset('assets/css/telephone-validation.css') }}" />--}}
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/membership.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-without-top">
        <div class="membership-container membership-bg" style="background-image: url({{ asset('assets/img/jobs-bg.png') }})">
            <div class="container">
                <!-- Title In Small Screen -->
                <div class="mem-sm-title title text-center mb-3">Sign Up</div>
                <div class="membership-center">
                    <div class="membership-form-container">
                        <div class="membership-title title text-center mb-5">Sign Up</div>
                        <div class="membership-form-content">
                            <form method="POST" action="{{ route('register') }}" autocomplete="off">
                                @csrf
                                <div class="form-group-row mb-4">
                                    <label class="fgr-label">Full Name</label>
                                    <input class="fgr-input invalid" type="text" name="name" value="{{ old('name') }}" placeholder="Enter Full Name" required autocomplete="none" />
                                    <label id="name-error" class="error validation-error" for="name"></label>
                                    @if($errors->has('name'))
                                        <p style="color: red">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group-row mb-4">
                                    <label class="fgr-label">Email</label>
                                    <input class="fgr-input" type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email Address" required autocomplete="none"/>
                                    <label id="email-error" class="error validation-error" for="email"></label>
                                    @if($errors->has('email'))
                                        <p style="color: red">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>

{{--                                <div class="form-group-row mb-4">--}}
{{--                                    <label class="fgr-label">Phone Number</label>--}}
{{--                                    <input id="phoneNumber" class="fgr-input" type="tel" name="phone_number" value="{{ old('phone_number') }}" required autocomplete="none"/>--}}
{{--                                    <input type="hidden" id="phone_code" name="phone_code">--}}
{{--                                    @if($errors->has('phone_number'))--}}
{{--                                        <p style="color: red">{{ $errors->first('phone_number') }}</p>--}}
{{--                                    @endif--}}
{{--                                </div>--}}

                                <!-- Phone Dropdown -->
                                <div class="form-group-row mb-4">
                                    <label class="fgr-label">Phone Number</label>
                                    <div class="input-phone-block d-flex">
                                        <select class="country-code-select" id="countryCode" name="phone_code" data-value="{{ old('phone_code', '353') }}"></select>
                                        <input class="input-phone-value only-numbers" type="text" name="phone_number" autocomplete="none" value="{{ old('phone_number') }}" placeholder="Enter number phone" required />
                                    </div>
                                    <label id="phone_number-error" class="error validation-error" for="phone_number"></label>
                                    <p style="color: red">
                                        @if($errors->has('phone_number'))
                                            {{$errors->first('phone_number')}}
                                        @endif
                                    </p>
                                </div>
                                <!-- Phone Dropdown -->


                                <div class="form-group-row mb-4">
                                    <label class="fgr-label">Password</label>
                                    <div class="password-display">
                                        <input class="fgr-input" type="password" name="password" placeholder="Enter Your Password" required autocomplete="new-password" />
                                        <button type="button" class="password-display-btn">
                                            <i class="fa-solid fa-eye-slash password-eye"></i>
                                        </button>
                                    </div>
                                    <label id="password-error" class="error validation-error" for="password"></label>
                                    @if($errors->has('password'))
                                            <p style="color: red">{{ $errors->first('password') }}</p>
                                    @endif
                                </div>
                                <div class="form-group-row mb-4">
                                    <label class="fgr-label">Confirm Password</label>
                                    <div class="password-display">
                                        <input class="fgr-input" type="password" name="password_confirmation" placeholder="Confirm Your Password" required autocomplete="new-password" />
                                        <button type="button" class="password-display-btn">
                                            <i class="fa-solid fa-eye-slash password-eye"></i>
                                        </button>
                                    </div>
                                    <label id="password_confirmation-error" class="error validation-error" for="password_confirmation"></label>
                                </div>
                                <div class="form-group-row mb-4">
                                    <label class="fgr-label">Invitation Code <span class="optional">(Optional)</span></label>
                                    <input class="fgr-input" type="text" name="invitation_code" placeholder="Invitation Code" value="{{ old('invitation_code') }}" autocomplete="none" />
                                    @if($errors->has('invitation_code'))
                                        <p style="color: red">{{ $errors->first('invitation_code') }}</p>
                                    @endif
                                </div>
                                <div class="form-group-row flex-between-vh mb-4">
                                    <div class="toggle-switch-title">Are you a service provider?</div>
                                    <div class="toggle-switch-btn" id="button-13">
                                        <input type="checkbox" class="ts-checkbox" name="is_provider"/>
                                        <div class="knobs">
                                            <span></span>
                                        </div>
                                        <div class="layer"></div>
                                    </div>
                                </div>
                                <div class="form-group-row mb-3">
                                    <button class="btn btn-bg width-fluid">Sign Up</button>
                                </div>
                            </form>
                        </div>
                        <div class="terms-conditions-text mb-4 pt-4">
                            by signing up you agree to Deft@ <a class="btn btn-simple" href="terms-conditions.html">Terms</a> and <a class="btn btn-simple" href="privacy.html">Privacy Policy</a>
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
                    name: {
                        required: true,
                        minlength: 3
                    },
                    phone_number: {
                        required: true,
                        minlength: 7
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: '[name="password"]'
                    }
                },
                messages: {
                    name: {
                        required: "Please enter your fullname",
                        minlength: "Your name must be at least 3 characters"
                    },
                    phone_number: {
                        required: "Please enter your phone number",
                        minlength: "Your phone number must be at least 7 numbers"
                    },
                    password: {
                        required: "Please enter a password",
                        minlength: "Your password must be at least 6 characters"
                    },
                    password_confirmation: {
                        required: "Please enter a confirmation password",
                        equalTo: "Password does not match !"
                    },
                    email: "Please enter a valid email address"
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
