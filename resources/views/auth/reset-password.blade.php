{{--<x-guest-layout>--}}
{{--    <x-auth-card>--}}
{{--        <x-slot name="logo">--}}
{{--            <a href="/">--}}
{{--                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />--}}
{{--            </a>--}}
{{--        </x-slot>--}}

{{--        <!-- Validation Errors -->--}}
{{--        <x-auth-validation-errors class="mb-4" :errors="$errors" />--}}

{{--        <form method="POST" action="{{ route('password.update') }}">--}}
{{--            @csrf--}}

{{--            <!-- Password Reset Token -->--}}
{{--            <input type="hidden" name="token" value="{{ $request->route('token') }}">--}}

{{--            <!-- Email Address -->--}}
{{--            <div>--}}
{{--                <x-label for="email" :value="__('Email')" />--}}

{{--                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />--}}
{{--            </div>--}}

{{--            <!-- Password -->--}}
{{--            <div class="mt-4">--}}
{{--                <x-label for="password" :value="__('Password')" />--}}

{{--                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />--}}
{{--            </div>--}}

{{--            <!-- Confirm Password -->--}}
{{--            <div class="mt-4">--}}
{{--                <x-label for="password_confirmation" :value="__('Confirm Password')" />--}}

{{--                <x-input id="password_confirmation" class="block mt-1 w-full"--}}
{{--                                    type="password"--}}
{{--                                    name="password_confirmation" required />--}}
{{--            </div>--}}

{{--            <div class="flex items-center justify-end mt-4">--}}
{{--                <x-button>--}}
{{--                    {{ __('Reset Password') }}--}}
{{--                </x-button>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </x-auth-card>--}}
{{--</x-guest-layout>--}}

@extends('layouts.app')
@section('title', 'Create new password')
@section('main-style')
{{--    <link rel="stylesheet" href="{{ asset('assets/css/telephone-validation.css') }}" />--}}
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/membership.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-without-top">
        <div class="membership-container membership-bg">
            <div class="container">
                <!-- Title In Small Screen -->
                <div class="mem-sm-title title text-center mb-3">Forgot your password</div>
                <div class="membership-center">
                    <div class="membership-form-container">
                        <!-- Email -->
                        @if($errors->has('email'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $errors }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <!-- Email -->

                        <!-- Password -->
                        @if($errors->has('password'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $errors->first('password') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <!-- Password -->
                        <div class="membership-title title text-center mb-3">Forgot your password</div>
                        <div class="membership-text mb-3 text-center">
                            Please enter your email you’ve<br /> created your account with
                        </div>

                        <div class="membership-form-content">
                            <form id="resetPasswordForm" method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <div class="mship-center-vertical">
                                    <div class="mship-stretch w-100">
                                        <!-- Input Group Row -->
                                        <div class="form-group-row mb-4">
                                            <label class="fgr-label">Email</label>
                                            <input class="fgr-input" type="email" name="email" placeholder="example@email.com" value="{{old('email',$request->email)}}"/>
                                            <label id="email-error" class="error validation-error" for="email"></label>
{{--                                            <p style="color: red">--}}
{{--                                                @if($errors->has('email'))--}}
{{--                                                    {{ $errors }}--}}
{{--                                                @endif--}}
{{--                                            </p>--}}
                                        </div>
                                        <!-- Input Group Row -->

                                        <!-- Input Group Row -->
                                        <div class="form-group-row mb-4">
                                            <label class="fgr-label">Password</label>
                                            <div class="password-display">
                                                <input class="fgr-input" type="password" name="password" />
                                                <button type="button" class="password-display-btn">
                                                    <i class="fa-solid fa-eye-slash password-eye"></i>
                                                </button>
                                            </div>
                                            <label id="password-error" class="error validation-error" for="password"></label>
{{--                                            <p style="color: red">--}}
{{--                                                @if($errors->has('password'))--}}
{{--                                                    {{ $errors->first('password') }}--}}
{{--                                                @endif--}}
{{--                                            </p>--}}
                                        </div>
                                        <!-- Input Group Row -->

                                        <!-- Input Group Row -->
                                        <div class="form-group-row mb-4">
                                            <label class="fgr-label">Confirm Password</label>
                                            <div class="password-display">
                                                <input class="fgr-input" type="password" name="password_confirmation" />
                                                <button type="button" class="password-display-btn">
                                                    <i class="fa-solid fa-eye-slash password-eye"></i>
                                                </button>
                                            </div>
                                            <label id="password_confirmation-error" class="error validation-error" for="password_confirmation"></label>
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
            $("#resetPasswordForm").validate({
                rules: {
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
                        equalTo : '[name="password"]'
                    }
                },
                messages: {
                    password: {
                        required: "Please enter a password",
                        minlength: "Your password must be at least 6 characters"
                    },
                    password_confirmation: {
                        required: "Please enter a confirmation password",
                        equalTo: "Password does not match !"
                    },
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



