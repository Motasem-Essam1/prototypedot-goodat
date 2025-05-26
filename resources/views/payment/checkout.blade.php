@extends('layouts.app')
@section('title', 'Checkout')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/payments.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-without-top">
        <div class="payments-container">
            <div class="container">
                <div class="payments-header flex-center-v mb-3">
                    <!-- Breadcrumb -->
                    <div class="breadcrumb-container">
                        <ol class="breadcrumb breadcrumb-list w-100">
                            <li class="breadcrumb-item"><a href="{{ route('account.account') }}">Account</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('account.subscription') }}">Upgrade Account</a></li>
                            <li class="breadcrumb-item active max-1-line" aria-current="page">Payment</li>
                        </ol>
                    </div>
                    <!-- Breadcrumb -->
                </div>
                <div class="payments-content">
                    <div class="pays-heading text-center">
                        <div class="pays-main-title title mb-2">Payment</div>
                        <div class="pays-sub-title text mb-5">Finalize your payment to upgrade your account!</div>
                    </div>
                    <div class="pays-grid">
                        <div class="pays-grid-card">
                            <div class="pgc-body">
                                @if($package->has_price)
                                    <div class="pgc-title title mb-3">Payment Method</div>
                                @endif
                                <div class="pgc-form">
                                    @if($package->has_price)
                                        <div class="choose-payment mb-5">
{{--                                            <div class="checkAll checkbox-group checkbox-circle flex-center-v mb-4">--}}
{{--                                                <div class="fake-checkbox">--}}
{{--                                                    <input disabled id="paypal" type="radio" name="payments" class="input-checkbox"/>--}}
{{--                                                    <div class="fake-cb-child">--}}
{{--                                                        <span></span>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <label class="checkbox-text" for="paypal">--}}
{{--                                                    <img class="img-fluid" src="{{ asset('assets/img/subscriptions/paypal.svg') }}" alt="" /> <span class="unavailable-text">(Unavailable Now)</span>--}}
{{--                                                </label>--}}
{{--                                            </div>--}}

{{--                                            <div class="checkAll checkbox-group checkbox-circle flex-center-v mb-4">--}}
{{--                                                <div class="fake-checkbox">--}}
{{--                                                    <input id="paypal" type="radio" name="payments" class="input-checkbox" checked/>--}}
{{--                                                    <div class="fake-cb-child">--}}
{{--                                                        <span></span>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <label class="checkbox-text" for="paypal">--}}
{{--                                                    <i style="height: 3em;color: #635bff" class="fa-brands fa-stripe"></i>--}}
{{--                                                    --}}{{--                                                <img style="width: 30px" class="img-fluid" src="{{ asset('assets/img/subscriptions/stripe.svg') }}" alt="" />--}}
{{--                                                </label>--}}
{{--                                            </div>--}}

                                            <!-- Paypal -->
                                            <div class="payment-option-group">
                                                <input disabled id="paypal" class="po-input" type="radio" name="payments" />
                                                <label class="checkbox-text" for="paypal">
                                                    <img style="width: 85px" class="po-img img-fluid" src="{{ asset('assets/img/icons/paypal.svg') }}" alt="" /> <span class="unavailable-text">(Unavailable now)</span>
                                                </label>
                                            </div>
                                            <!-- Paypal -->

                                            <!-- Stripe -->
                                            <div class="payment-option-group">
                                                <input id="stripe" class="po-input" type="radio" name="payments" checked />
                                                <label class="checkbox-text" for="stripe">
                                                    <img class="po-img img-fluid" src="{{ asset('assets/img/icons/stripe.svg') }}" alt="" />
                                                </label>
                                            </div>
                                            <!-- Stripe -->
                                        </div>
                                    @endif
                                    <!-- Payments Actions -->
                                    <div class="payments-actions d-flex">
                                        <a href="{{ route('subscription.payment').'?package='. $package->package_name."&amount=".$package->price."&package_id=".$package->id }}" class="btn btn-bg">Subscribe</a>
                                        <a href="{{ route('account.subscription') }}" class="btn btn-simple ml-4">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pays-grid-card">
                            <div class="pgc-body">
                                <div class="pgc-title title mb-3">Payment Summary</div>
                                <div class=" flex-between-vh">
                                    <div class="pgc-pack-name">{{ $package->package_name }} ( {{ $package->slug }} )</div>
                                    <div class="pgc-pack-price">$ {{ $package->price == 0 ? "0.0" : $package->price }}</div>
                                </div>
                                <div class="pgc-price flex-between-vh">
                                    <div class="pgc-pack-name">Handling fees</div>
                                    <div class="pgc-pack-price">$ 0.0</div>
                                </div>
                                <div class="pgc-total-price flex-between-vh">
                                    <div class="total-text">Total</div>
                                    <div class="total-int">$ {{ $package->price == 0 ? "0.0" : $package->price }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
@endsection
