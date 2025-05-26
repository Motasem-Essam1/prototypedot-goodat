@extends('layouts.app')
@section('title', 'Subscription')
@section('main-style')
{{--    <link rel="stylesheet" href="{{ asset('assets/css/telephone-validation.css') }}" />--}}
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/profile.css') }}" />
@endsection
@section('content')

    <!-- Cancel Subscription Dialog -->
    @include('components.cancel-subscription-dialog')
    <!-- Cancel Subscription Dialog -->

    <div class="wrapper-container wrapper-with-top">
        <div class="profile-container">
            <div class="container">
                <div class="profile-wrapper">
                    <div class="profile-flexable flex-between-h">
                        <!-- Sidebar -->
                        <div class="profile-sidebar">
                            @include('account.sidebar')
                        </div>
                        <!-- Sidebar -->

                        <!-- Content -->
                        <div class="profile-content">
                            <!-- Subscriptions -->
                            <div data-tab-profile="#subscriptions" class="pc-tab pct-bg active">
                                <div class="pc-title title d-flex justify-content-between">
                                    <div>
                                        SUBSCRIPTIONS
                                        @if(Auth::User()->user_data->user_type == 'Service Provider')
                                            <a class="user-provider-link" href="{{ route('provider.profile', Auth::id()) }}">
                                                <span class="provider-tooltip">Show provider profile</span>
                                                <span class="user-type user-type-provider">{{ Auth::User()->user_data->user_type }}</span>
                                            </a>
                                        @else
                                            <span class="user-type user-type-normal">{{ Auth::User()->user_data->user_type }}</span>
                                        @endif
                                    </div>

                                    @if(Auth::User()->user_data->generated_Code)
                                        <div class="d-flex align-items-start activation-code-parent">
                                            <button id="copyClipboard" type="button" class="activation-code d-flex align-items-center" data-text="{{ Auth::User()->user_data->generated_Code }}">
                                                {{ Auth::User()->user_data->generated_Code }}
                                                <div><span class="icon-copy icon"></span></div>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="pc-sub-title mb-3">Manage your account Subscriptions!</div>
                                @if(count($errors) > 0)
                                    <div class="p-1">
                                        @foreach($errors->all() as $error)
                                            <div class="alert alert-warning alert-danger fade show" role="alert">{{$error}} <button type="button" class="close"
                                                                                                                                    data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button></div>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="subscriptions-packs-container">
                                    <!-- Current Container -->
                                    @if(!empty(Auth::user()->user_data->package))
                                    <div class="current-package mb-5">
                                        <div class="sp-main-title mb-2">Current Subscription</div>
                                        @if($type == "Service Provider")
                                            <div class="sub-package-bg mb-4" style="--gat-package-color: {{ $current_package->color }}">
                                                <div class="sub-package sub-package-friend" style="--gat-package-color: {{ $current_package->color }}">
                                                    <div class="sp-avatar" style="--gat-package-color: {{ $current_package->color }}">
                                                        <img class="img-fluid" src="{{ asset($current_package->image) }}" alt="Subscriptions" />
                                                        <div class="sp-avatar-title">{{ $current_package->package_name }}</div>
                                                    </div>
                                                    <div class="sp-content" style="--gat-package-color: {{ $current_package->color }}">
                                                        <div class="sp-features sp-features-grid">
                                                            @foreach(explode('|', $current_package->description) as $point)
                                                                <div class="sp-feat-row"><span><span class="sp-bullet"></span></span>{{ $point }}</div>
                                                            @endforeach
                                                        </div>
                                                        @if($current_package->id != 1)
{{--                                                            <a class="button-default d-inline-block" style="--gat-package-color: {{ $current_package->color }}" href="{{route('subscription.cancel')}}">Cancel</a>--}}
                                                            <button
                                                                class="button-default d-inline-block"
                                                                style="--gat-package-color: {{ $current_package->color }}"
                                                                data-bs-toggle="modal" data-bs-target="#cancelSubscriptionDialog">
                                                                Cancel
                                                            </button>
                                                        @endif
                                                    </div>
                                                    <div class="sp-payment" style="background-color: {{ $current_package->color }}">
                                                        @if($current_package->price > 0)
                                                        {{$configurations['value']}}
                                                        @endif
                                                        {{ $current_package->slug }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            you don't have package subscribed
                                        @endif

                                    </div>
                                    @else
                                        you don't have package subscribed
                                    @endif
                                    <!-- Current Container -->

                                    <!-- Other Packages -->
                                    <div class="other-packages">
                                        <div class="sp-main-title mb-2">Other Packages</div>
                                        @foreach($packages as $package)
{{--                                            @if($current_package->package_name == $package->package_name)--}}
{{--                                            --}}

{{--                                            @else--}}
                                            <div class="sub-package-bg" style="--gat-package-color: {{ $package->color }}">
                                                <div class="sub-package" style="--gat-package-color: {{ $package->color }}">
                                                    <div class="sp-avatar" style="--gat-package-color: {{ $package->color }}">
                                                        <img class="img-fluid" src="{{ asset($package->image) }}" alt="Subscriptions" />
                                                        <div class="sp-avatar-title">{{ $package->package_name }}</div>
                                                    </div>
                                                    <div class="sp-content" style="--gat-package-color: {{ $package->color }}">
                                                        <div class="sp-features sp-features-grid">
                                                            @foreach(explode('|', $package->description) as $point)
                                                                <div class="sp-feat-row"><span><span class="sp-bullet"></span></span>{{ $point }}</div>
                                                            @endforeach
                                                        </div>
                                                        <div class="sp-action mt-3">
                                                            @if($type == "Service Provider")
                                                                @if(!empty($current_package))
                                                                    @if($package->id != $current_package->id)
                                                                        <a class="button-default d-inline-block" style="--gat-package-color: {{ $package->color }}" href="{{route('subscription.subscription', $package->package_name)}}">Upgrade</a>
                                                                    @elseif($package->id == $current_package->id)
                                                                        @if($current_package->id != 1)
{{--                                                                            <a class="button-default d-inline-block" style="--gat-package-color: {{ $current_package->color }}" href="{{route('subscription.cancel')}}">Cancel</a>--}}
                                                                            <button
                                                                                class="button-default d-inline-block"
                                                                                style="--gat-package-color: {{ $current_package->color }}"
                                                                                data-bs-toggle="modal" data-bs-target="#cancelSubscriptionDialog">
                                                                                Cancel
                                                                            </button>
                                                                        @endif                                                                    @endif
                                                                @else
                                                                <a class="button-default d-inline-block" style="--gat-package-color: {{ $package->color }}" href="{{route('subscription.subscription', $package->package_name)}}">Upgrade</a>
                                                                @endif
                                                            @else
                                                                <a class="button-default d-inline-block" style="--gat-package-color: {{ $package->color }}" href="{{route('subscription.subscription', $package->package_name)}}">Upgrade</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="sp-payment">
                                                        @if($type == "Service Provider")
                                                            @if(!empty($current_package))
                                                                @if($package->id != $current_package->id)
                                                                    @if($package->price > 0)
                                                                        {{$configurations['value']}}
                                                                    @endif
                                                                    {{ $package->slug }}
                                                                @endif
                                                                @if($package->id == $current_package->id)
                                                                    Current
                                                                @endif
                                                            @endif
                                                        @else
                                                            @if($package->price > 0)
                                                                {{$configurations['value']}}
                                                            @endif
                                                            {{ $package->slug }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
{{--                                            @endif--}}
                                        @endforeach
                                    </div>
                                    <!-- Other Packages -->
                                </div>
                            </div>
                            <!-- Subscriptions -->
                        </div>
                        <!-- Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
@endsection
