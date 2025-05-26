@extends('layouts.app')
@section('title', 'Congratulations')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/subscriptions.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-without-top">
        <div class="subscriptions-container">
            <div class="container">
                <div class="subs-content">
                    <!-- Subscribed Card -->
                    <div class="subscribed-card m-auto text-center">
                        <div class="subsed-logo flex-center-vh">
                            <span class="icon-correct icon"></span>
                        </div>
                        <div class="subsed-title title mb-2">{{ Session::get('title') }}</div>
                        <div class="subsed-sub-title">{{ Session::get('massage') }}</div>
                        <div class="subsed-actions flex-center-vh mt-5">
                            @if(Session::get('successButtonUrl'))
                            <a href="{{ route('home') . '/' . Session::get('successButtonUrl') }}" class="btn btn-bg">{{ Session::get('successButtonText') }}</a>
                            @endif
                            <a href="{{ route('home') }}" class="btn btn-simple">Back Home</a>
                        </div>
                    </div>
                    <!-- Subscribed Card -->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
@endsection
