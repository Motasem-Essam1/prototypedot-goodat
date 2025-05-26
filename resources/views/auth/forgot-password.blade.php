{{--<x-guest-layout>--}}
{{--    <x-auth-card>--}}
{{--        <x-slot name="logo">--}}
{{--            <a href="/">--}}
{{--                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />--}}
{{--            </a>--}}
{{--        </x-slot>--}}

{{--        <div class="mb-4 text-sm text-gray-600">--}}
{{--            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}--}}
{{--        </div>--}}

{{--        <!-- Session Status -->--}}
{{--        <x-auth-session-status class="mb-4" :status="session('status')" />--}}

{{--        <!-- Validation Errors -->--}}
{{--        <x-auth-validation-errors class="mb-4" :errors="$errors" />--}}

{{--        <form method="POST" action="{{ route('password.email') }}">--}}
{{--            @csrf--}}

{{--            <!-- Email Address -->--}}
{{--            <div>--}}
{{--                <x-label for="email" :value="__('Email')" />--}}

{{--                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />--}}
{{--            </div>--}}

{{--            <div class="flex items-center justify-end mt-4">--}}
{{--                <x-button>--}}
{{--                    {{ __('Email Password Reset Link') }}--}}
{{--                </x-button>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </x-auth-card>--}}
{{--</x-guest-layout>--}}


@extends('layouts.app')
@section('title', 'Forgot Password')
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
                <div class="mem-sm-title title text-center mb-3">Forgot your password</div>
                <div class="membership-center">
                    <div class="membership-form-container">
                        @if(session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if($errors->has('email'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $errors->first('email') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="membership-title title text-center mb-3">Forgot your password</div>
                        <div class="membership-text text-center">
                            Please enter your email address you’ve<br /> created your account with
                        </div>
{{--                        <h6 style="color: green">{{ session('status') }}</h6>--}}
                        <div class="membership-form-content">
                            <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="mship-center-vertical">
                                    <div class="mship-stretch w-100">
                                        <!-- Input Group Row -->
                                        <div class="form-group-row mb-4">
                                            <label class="fgr-label">Email</label>
                                            <input class="fgr-input" type="email" name="email" placeholder="example@email.com" autocomplete="none" required />
                                            <label id="email-error" class="error validation-error" for="email"></label>
{{--                                            <p style="color: red">--}}
{{--                                                @if($errors->has('email'))--}}
{{--                                                    {{ $errors->first('email') }}--}}
{{--                                                @endif--}}
{{--                                            </p>--}}
                                        </div>
                                        <!-- Input Group Row -->
                                    </div>
                                </div>

                                <a class="btn btn-simple d-block text-center mb-3" href="{{ route('login') }}">Login</a>

                                <div class="form-group-row mb-4">
                                    <button type="submit" class="btn btn-bg width-fluid">Reset Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        $(document).ready(function () {
            $("#forgotPasswordForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                },
                messages: {
                    email: {
                        required: "Please enter an email address",
                        email: "Please enter a valid email address"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection

