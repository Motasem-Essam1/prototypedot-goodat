@extends('layouts.app')
@section('title', 'Pricing List')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/subscriptions.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-without-top">
        <div class="subscriptions-container">
            <div class="container">
                <div class="subs-header flex-center-v mb-3">
                    <!-- Breadcrumb -->
                    <div class="breadcrumb-container">
                        <div aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ Auth::check() ? route('account.account') : route('home') }}">{{ Auth::check() ? 'Account' : 'Home' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Upgrade Account</li>
                            </ol>
                        </div>
                    </div>
                    <!-- Breadcrumb -->
                </div>

                <div class="subs-content">
                    <div class="sub-main-title title text-center">Choose your subscription type</div>
                    <div class="subs-packs-container">
                        <div class="subs-packs-grid">

                            @foreach($packages as $package)
                                <!-- Package Row -->
                                <div class="sub-pack-card text-center" style="--gat-package-color: {{ $package->color }}">
                                    <div class="spc-dir">
                                        <div class="spc-avatar">
                                            <img class="img-fluid" src="{{ asset($package->image) }}" alt="" />
                                            <div class="spc-title">{{ $package->package_name }}</div>
                                        </div>
                                        @if($package->price == 0)
                                            <div class="spc-payment">{{$package->slug }}</div>
                                        @else
                                            <div class="spc-payment">{{$configurations['value']}} {{$package->slug }}</div>
                                        @endif
                                        <div class="spc-feats mb-4">
                                            @foreach(explode('|', $package->description) as $point)
                                                <div class="spc-feat">{{ $point }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="spc-action text-center">
                                        @if($package->price == 0)
                                            <a href="{{Auth::check() ? route('subscription.subscription', $package->package_name) : route('login')}}" class="button-default d-inline-block">{{ $package->slug }}</a>
                                        @else
                                            <a href="{{Auth::check() ? route('subscription.subscription', $package->package_name) : route('login')}}" class="button-default d-inline-block">{{$configurations['value']}} {{ $package->slug }}</a>

                                        @endif

                                    </div>
                                </div>
                                <!-- Package Row -->
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')

@endsection
