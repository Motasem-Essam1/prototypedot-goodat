@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/membership.css') }}" />
@endsection
<div class="modal fade" id="loginDialog" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-login-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title" id="loginModalLabel">Login</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="auth-dialog">
                    <a href="{{ route('login.social', 'google') }}"
                       class="membership-connect-card google-connect flex-center-vh">
                        <img class="img-fluid" src="{{ asset('assets/img/icons/google.png') }}" alt=""/>
                        <div class="mcc-title">Continue With Google</div>
                    </a>

                    <a href="{{ route('login.social', 'facebook') }}"
                       class="membership-connect-card facebook-connect flex-center-vh">
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
                        <form id="formLoginDialog" method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="back" value="back" />
                            <div class="form-group-row mb-4">
                                <label class="fgr-label">Phone Number</label>
                                <div class="input-phone-block d-flex">
                                    <select class="country-code-select" id="countryCode" name="country_code"
                                            data-value="{{ old('country_code', '353') }}"></select>
                                    <input class="input-phone-value only-numbers" type="text" name="phone_number"
                                           value="{{ old('phone_number') }}" autocomplete="none"
                                           placeholder="Enter number phone" required/>
                                </div>
                                <label id="phone_number-error" class="error validation-error" for="phone_number"></label>
                                <p style="color: red">
                                    @if($errors->has('phone_number'))
                                        {{$errors->first('phone_number')}}
                                    @endif
                                </p>
                            </div>

                            <div class="form-group-row mb-3">
                                <label class="fgr-label">Password</label>
                                <div class="password-display">
                                    <input class="fgr-input" type="password" name="password"
                                           placeholder="Enter Your Password" required autocomplete="new-password"/>
                                    <button type="button" class="password-display-btn">
                                        <i class="fa-solid fa-eye-slash password-eye"></i>
                                    </button>
                                </div>
                                <label id="password-error" class="error validation-error" for="password"></label>
                            </div>
                            <div class="keeped-forgot flex-between-vh mb-5">
                                <div class="checkAll checkbox-group flex-center-vh">
                                    <div class="fake-checkbox">
                                        <input id="modalCheckbox1" type="checkbox" name="remember"
                                               {{ old('remember') !== null ? 'checked="true"' : '' }} class="input-checkbox"/>
                                        <div class="fake-cb-child">
                                            <span></span>
                                        </div>
                                    </div>
                                    <label class="checkbox-text" for="modalCheckbox1">Keep me logged in</label>
                                </div>
                                <a class="forgot-password-link" href="{{ route('password.request') }}">Forgot
                                    password</a>
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

