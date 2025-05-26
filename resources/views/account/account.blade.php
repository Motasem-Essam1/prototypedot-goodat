@extends('layouts.app')
@section('title', 'My Account')
@section('main-style')
{{--    <link rel="stylesheet" href="{{ asset('assets/css/telephone-validation.css') }}" />--}}
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/profile.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-with-top">
        <div class="profile-container">
            <div class="container">
                <div class="profile-wrapper">
                    @if(count($errors) > 0 )
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <ul class="p-0 m-0" style="list-style: none;">
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                    @endif
                    <div class="profile-flexable flex-between-h">
                        <!-- Sidebar -->
                        <div class="profile-sidebar">
                            <!-- Appear In Small Responsive Only -->
                            @include('account.sidebar')
                        </div>
                        <!-- Sidebar -->

                        <!-- Content -->
                        <div class="profile-content">
                            <form action="{{ route('account.account.upload-profile') }}" method="post" enctype="multipart/form-data">
                                @csrf
                            <div data-tab-profile="#info" class="pc-tab pct-bg active">
                                <div class="pc-title title d-flex justify-content-between">
                                    <div>
                                    MY ACCOUNT
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
                                <div class="pc-sub-title">You can edit your account information.</div>
                                <div class="pc-avatar inputfr-parent d-inline-block mt-5">
                                    <label class="fgr-label mb-3">Account Picture</label>
                                    <div class="pca-img mb-2">
                                        <div class="imgfr-parent">
                                            <img class="img-fluid img-file-reader" src="" alt="" />
                                            <button class="btn close-img-reader"><span class="icon-cancel icon"></span></button>
                                        </div>
                                        <img class="img-fluid" src="{{ asset($avatar) }}" alt="Avatar" />
                                    </div>
                                    <div class="upload-avatar flex-center-v">
                                        <input class="input-file-reader" type="file" name="avatar" accept="image/*" />
                                        <span class="icon-upload icon mr-2"></span> Change
                                    </div>
                                </div>
                                <div class="pc-info-form pt-4">
                                    <div class="pc-grid-double mb-5">
                                        <div class="form-group-row">
                                            <label class="fgr-label">Full Name</label>
                                            <input class="fgr-input" type="text" name="fullname" placeholder="Enter Full Name" value="{{ $full_name }}" />
                                            <label id="fullname-error" class="error validation-error" for="fullname"></label>
                                        </div>

                                        <div class="form-group-row">
                                            <label class="fgr-label">Email Address</label>
                                            <input class="fgr-input" type="email" name="email" placeholder="example@mail.com" value="{{ $email }}" {{ Auth::user()->phoned_Signed != 1 ? 'disabled' : '' }} />
                                            <label id="email-error" class="error validation-error" for="email"></label>
                                        </div>


{{--                                        <div class="form-group-row">--}}
{{--                                            <label class="fgr-label">Phone Number</label>--}}
{{--                                            <input id="phoneNumber" class="fgr-input" type="tel" name="phone" value="{{ $phone_number }}"/>--}}
{{--                                            <input type="hidden" id="phone_code" name="phone_code" value="353">--}}
{{--                                            <p style="color: red">--}}
{{--                                                @if($errors->has('phone'))--}}
{{--                                                    {{ $errors->first('phone') }}--}}
{{--                                                @endif--}}
{{--                                            </p>--}}
{{--                                        </div>--}}

                                        <!-- Phone Dropdown -->
                                        <div class="form-group-row">
                                            <label class="fgr-label">Phone Number</label>
                                            <div class="input-phone-block d-flex">
                                                <select class="country-code-select" id="countryCode" name="phone_code" data-value="{{ $country_code }}"></select>
                                                <input class="input-phone-value only-numbers" type="text" name="phone" autocomplete="none" value="{{ $phone }}" placeholder="Enter number phone" required />
                                            </div>
                                            <label id="phone-error" class="error validation-error" for="phone"></label>
                                            <p style="color: red">
                                                @if($errors->has('phone'))
                                                    {{ $errors->first('phone') }}
                                                @endif
                                            </p>
                                        </div>
                                        <!-- Phone Dropdown -->

                                        <div class="form-group-row">
                                            <label class="fgr-label">Select Category</label>
                                            <select id="category" class="multiple-select text-capitalize" name="category_id[]" multiple>
                                                @foreach($categories as $category)
                                                    <optgroup label="{{$category->category_name}}">
                                                        @foreach($category->subCategoriesActive as $subCategory)
                                                            {{$checkselected = false}}
                                                            @foreach($user_sub_categories as $user_sub_category)
                                                                @if($user_sub_category['user_category_id'] == $subCategory['id'])
                                                                    <option name value="{{$subCategory->id}}" selected>{{$subCategory->sub_category_name}}</option>
                                                                   {{$checkselected = true}}
                                                                    @break;
                                                                @else
                                                                @endif
                                                            @endforeach
                                                                @if($checkselected == false)
                                                                  <option  name value="{{$subCategory->id}}">{{$subCategory->sub_category_name}}</option>
                                                                @endif
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                            <label id="category_id-error" class="error validation-error" for="category_id"></label>
                                            <p style="color: red">
                                                @if($errors->has('user_sub_categories'))
                                                        {{ $errors->first('user_sub_categories') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-bg pl-4 pr-4">Save</button>
                                </div>
                            </div>
                            </form>
                            <!-- Info -->
                        </div>
                        <!-- Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        // Select Order
        $(function () {
            new SlimSelect({
                select: '#category',
                showSearch: false,
                showContent: 'down',
                settings: {
                    minSelected: 1,
                    maxSelected: 5,
                },
                onChange: (data) => {}
            });
        });

        $(document).ready(function () {
            $("form").validate({
                rules: {
                    fullname: {
                        required: true,
                        minlength: 3
                    },
                    phone: {
                        required: true,
                        minlength: 7
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    'category_id[]': {
                        required: true,
                        min: 1
                    }
                },
                messages: {
                    fullname: {
                        required: "Please enter your fullname",
                        minlength: "Your name must be at least 3 characters"
                    },
                    phone: {
                        required: "Please enter your phone number",
                        minlength: "Your phone number must be at least 7 numbers"
                    },
                    email: "Please enter a valid email address",
                    'category_id[]': {
                        required: "Please select category"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
