@extends('layouts.app')
@section('title', 'Change Password')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/toast.css') }}" />
{{--    <link rel="stylesheet" href="{{ asset('assets/css/telephone-validation.css') }}" />--}}
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/profile.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-with-top">
        <div class="profile-container">
            <div class="container">
                <div class="profile-wrapper">
                    @if($errors->has('massage'))
                        <div class="alert alert-{{ $errors->first('status') == true ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                            {{ $errors->first('massage') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="profile-flexable flex-between-h">
                        <!-- Sidebar -->
                        <div class="profile-sidebar">
                            @include('account.sidebar')
                        </div>
                        <!-- Sidebar -->
                        <!-- Content -->
                        <div class="profile-content">
                            <!-- Password -->
                            <form action="{{ route('account.account.update-password') }}" method="post">
                                @csrf
                                <div data-tab-profile="#changePassword" class="pc-tab pct-bg active">
                                    <div class="pc-title title">CHANGE PASSWORD</div>
                                    <div class="pc-sub-title mb-5">Make a secure password for your account.</div>
                                    <div class="pc-info-form">
                                        <div class="pc-grid mb-5">
                                            <div class="form-group-row">
                                                <label class="fgr-label">Current Password</label>
                                                <input class="fgr-input" type="password" name="old_password" placeholder="Enter Your Current Password" />
                                                <label id="old_password-error" class="error validation-error" for="old_password"></label>
                                                @if($errors->has('old_password'))
                                                    <p style="color: red">{{ $errors->first('old_password') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group-row">
                                                <label class="fgr-label">New Password</label>
                                                <input class="fgr-input" type="password" name="password" placeholder="Enter Your New Password" />
                                                <label id="password-error" class="error validation-error" for="password"></label>
                                                @if($errors->has('password'))
                                                    <p style="color: red">{{ $errors->first('password') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group-row">
                                                <label class="fgr-label">Confirm New Password</label>
                                                <input class="fgr-input" type="password" name="password_confirmation" placeholder="Confirm New Password" />
                                                <label id="password_confirmation-error" class="error validation-error" for="password_confirmation"></label>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-bg pl-4 pr-4">Change Password</button>
                                    </div>
                                </div>
                            </form>
                            <!-- Password -->
                        </div>
                        <!-- Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script src="{{ asset('assets/js/toast.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("form").validate({
                rules: {
                    old_password: {
                        required: true,
                        minlength: 6
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
                    old_password: {
                        required: "Please enter an old password",
                        minlength: "Your password must be at least 6 characters"
                    },
                    password: {
                        required: "Please enter a password",
                        minlength: "Your password must be at least 6 characters"
                    },
                    password_confirmation: {
                        required: "Please enter a confirmation password",
                        equalTo: "Password does not match !"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection

