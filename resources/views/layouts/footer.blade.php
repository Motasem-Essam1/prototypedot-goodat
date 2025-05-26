<footer class="footer {{ Route::is('home') ? 'footer-home' : '' }}">
    <div class="container">
        <div class="footer-content">

            <div class="footer-top">
                <div class="footer-logo">
                    <a href="{{route('home')}}"><div class="logo-text">Deft at</div></a>
                </div>

                <div class="footer-links">
                    <div class="footer-title">Services</div>
                    <div class="footer-link"><a href="{{ route('pricing') }}">Pricing List</a></div>
                    <div class="footer-link"><a href="#">House Work</a></div>
                    <div class="footer-link"><a href="#">Home Caring</a></div>
                    <div class="footer-link"><a href="#">Artistic</a></div>
                    <div class="footer-link"><a href="#">Cooking / Baking</a></div>
                </div>

                <div class="footer-links">
                    <div class="footer-title">Support</div>
                    <div class="footer-link"><a href="{{route('contact')}}">Contact Us</a></div>
                    <div class="footer-link"><a href="{{route('about')}}">About Us</a></div>
                    <div class="footer-link"><a href="#">How Deft At Works</a></div>
                    <div class="footer-link"><a href="{{route('privacy')}}">Privacy Policy</a></div>
                </div>

                <div class="footer-contact">
                    <div class="footer-title">Follow us</div>
                    <div class="socialmedia">
                        <div class="social-icons d-flex">
                            @if(isset($attributes['facebook']))
                                <a href="{{ $attributes['facebook']}}" target="_blank" class="social-icon">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                            @endif
                            @if(isset($attributes['youtube']))
                                <a href="{{ $attributes['youtube'] }}" target="_blank" class="social-icon">
                                    <i class="fa-brands fa-youtube"></i>
                                </a>
                            @endif
                            @if(isset($attributes['instagram']))
                                <a href="{{ $attributes['instagram'] }}" target="_blank" class="social-icon">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                            @endif
                        </div>

{{--                        <div class="choose-country">--}}
{{--                            <div class="cc-title mb-2">Select Country</div>--}}
{{--                            <select id="country-selectize" name="country-selectize" placeholder="Select a Country"></select>--}}
{{--                        </div>--}}
                    </div>

                    @if(isset($attributes['ios_app']) || isset($attributes['android_app']))
                        <div class="footer-apps">
                            <div class="title">Download Now</div>
                            <div class="footer-apps-block d-flex mt-2">
                                @if(isset($attributes['ios_app']))
                                    <a class="footer-app-logo" href="{{ $attributes['ios_app'] }}" target="_blank"><img class="img-fluid" src="{{ asset('assets/img/icons/ios.png') }}" alt="Apple Store" /></a>
                                @endif

                                @if(isset($attributes['android_app']))
                                    <a class="footer-app-logo" href="{{ $attributes['android_app'] }}" target="_blank"><img class="img-fluid" src="{{ asset('assets/img/icons/android.png') }}" alt="Play Store" /></a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="footer-actions">
                    @if(Auth::check())
                        @if(Auth::user()->user_data->user_type == "Normal")
                            <a href="{{ route('account.subscription') }}" class="button-default nav-st-btn hidden-sm hidden-xs"> Became A Provider </a>
                        @else
                            <button class="btn btn-bg" data-toggle="modal" data-target="#servicesTasksModal">
                                Add Service / Task
                            </button>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="btn btn-bg">Became A Provider</a>
                    @endif
                </div>

            </div>

            <div class="footer-bottom">
                <div class="fb-text">&copy; {{ date('Y') }} Deft at, All Rights Received.</div>
            </div>

        </div>
    </div>
</footer>
