@extends('layouts.app')
@section('title', $provider->name)
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/pages/view-profile.css') }}"/>
    <meta property="fb:app_id" content="629322731899614"/>
    <meta property="og:title" content="{{ $provider->name }}"/>
    <meta property="og:description" content="Phone Number: {{ $provider->phone_number }}"/>
    {{--    <meta property="og:type" content="Services" />--}}
    <meta property="og:image" content="{{ asset($data->avatar )}}"/>
    <meta property="og:url" content="https://deftat.com/service-provider/{{$provider->id}}"/>
    <meta property="og:image:width" content="1200"/>
    <meta property="og:image:height" content="630"/>
@endsection
@section('content')
    <!-- Modal Rating -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingModalLabel">Rating Provider</h5>
                    <button id="closeRatingProvider" type="button" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group-row mb-3">
                        <label class="fgr-label">Provider Quality</label>
                        <div class="d-flex align-items-center">
                            <select id="quality_rating" {{ Auth::check() ? '' : 'disabled' }} class="star-rating"
                                    data-count="#qualityRating">
                                <option {{ $auth_rating['quality'] == ''  ? 'selected="selected"' : '' }}    value="">
                                    Select a rating
                                </option>
                                <option {{ $auth_rating['quality'] == '5' ? 'selected="selected"' : '' }} value="5">
                                    Excellent
                                </option>
                                <option {{ $auth_rating['quality'] == '4' ? 'selected="selected"' : '' }} value="4">Very
                                    Good
                                </option>
                                <option {{ $auth_rating['quality'] == '3' ? 'selected="selected"' : '' }} value="3">
                                    Average
                                </option>
                                <option {{ $auth_rating['quality'] == '2' ? 'selected="selected"' : '' }} value="2">
                                    Poor
                                </option>
                                <option {{ $auth_rating['quality'] == '1' ? 'selected="selected"' : '' }} value="1">
                                    Terrible
                                </option>
                            </select>
                            <span id="qualityRating" class="rating-count">({{$auth_rating['quality']}})</span>
                        </div>
                    </div>

                    <div class="form-group-row mb-3">
                        <label class="fgr-label">Provider Time</label>
                        <div class="d-flex align-items-center">
                            <select id="time_rating" {{ Auth::check() ? '' : 'disabled' }} class="star-rating"
                                    data-count="#timeRating">
                                <option {{ $auth_rating['time'] == ''  ? 'selected="selected"' : '' }}    value="">
                                    Select a rating
                                </option>
                                <option {{ $auth_rating['time'] == '5' ? 'selected="selected"' : '' }} value="5">
                                    Excellent
                                </option>
                                <option {{ $auth_rating['time'] == '4' ? 'selected="selected"' : '' }} value="4">Very
                                    Good
                                </option>
                                <option {{ $auth_rating['time'] == '3' ? 'selected="selected"' : '' }} value="3">
                                    Average
                                </option>
                                <option {{ $auth_rating['time'] == '2' ? 'selected="selected"' : '' }} value="2">Poor
                                </option>
                                <option {{ $auth_rating['time'] == '1' ? 'selected="selected"' : '' }} value="1">
                                    Terrible
                                </option>
                            </select>
                            <span id="timeRating" class="rating-count">({{$auth_rating['time']}})</span>
                        </div>
                    </div>

                    <div class="form-group-row mb-3">
                        <label class="fgr-label">Accuracy</label>
                        <div class="d-flex align-items-center">
                            <select id="accuracy_rating" {{ Auth::check() ? '' : 'disabled' }} class="star-rating"
                                    data-count="#accuracyRating">
                                <option {{ $auth_rating['accuracy'] == ''  ? 'selected="selected"' : '' }}    value="">
                                    Select a rating
                                </option>
                                <option {{ $auth_rating['accuracy'] == '5' ? 'selected="selected"' : '' }} value="5">
                                    Excellent
                                </option>
                                <option {{ $auth_rating['accuracy'] == '4' ? 'selected="selected"' : '' }} value="4">
                                    Very Good
                                </option>
                                <option {{ $auth_rating['accuracy'] == '3' ? 'selected="selected"' : '' }} value="3">
                                    Average
                                </option>
                                <option {{ $auth_rating['accuracy'] == '2' ? 'selected="selected"' : '' }} value="2">
                                    Poor
                                </option>
                                <option {{ $auth_rating['accuracy'] == '1' ? 'selected="selected"' : '' }} value="1">
                                    Terrible
                                </option>
                            </select>
                            <span id="accuracyRating" class="rating-count">({{$auth_rating['accuracy']}})</span>
                        </div>
                    </div>

                    <div class="form-group-row mb-4">
                        <label class="fgr-label">Communication</label>
                        <div class="d-flex align-items-center">
                            <select id="Communication_rating" {{ Auth::check() ? '' : 'disabled' }} class="star-rating"
                                    data-count="#communicationRating">
                                <option {{ $auth_rating['communication'] == ''  ? 'selected="selected"' : '' }}    value="">
                                    Select a rating
                                </option>
                                <option {{ $auth_rating['communication'] == '5' ? 'selected="selected"' : '' }} value="5">
                                    Excellent
                                </option>
                                <option {{ $auth_rating['communication'] == '4' ? 'selected="selected"' : '' }} value="4">
                                    Very Good
                                </option>
                                <option {{ $auth_rating['communication'] == '3' ? 'selected="selected"' : '' }} value="3">
                                    Average
                                </option>
                                <option {{ $auth_rating['communication'] == '2' ? 'selected="selected"' : '' }} value="2">
                                    Poor
                                </option>
                                <option {{ $auth_rating['communication'] == '1' ? 'selected="selected"' : '' }} value="1">
                                    Terrible
                                </option>
                            </select>
                            <span id="communicationRating"
                                  class="rating-count">({{$auth_rating['communication']}})</span>
                        </div>
                    </div>

                    <div class="form-group-row">
                        <label class="fgr-label">Review Comment</label>
                        <textarea id="comment-textarea" class="fgr-input info-input fgr-textarea" name="comment"
                                  placeholder="Write your review here">{{$auth_rating['comment']}}</textarea>
                    </div>
                </div>
                <div class="modal-footer modal-footer-unround">
                    <button type="button" class="btn button-default" data-bs-dismiss="modal">Close</button>
                    <button id="saveReviewBtn" type="button" class="btn btn-bg">Save Review</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Rating -->

    @if(Auth::check())
        <!-- Upgrade Message -->
        @include('components.upgrade-message-dialog')
        <!-- Upgrade Message -->

        <!-- Review Pending -->
        @include('components.review-pending-dialog')
        <!-- Review Pending -->
    @endif

    <!-- Modal Share Container -->
    <div class="modal modal-share-container fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><span
                                class="icon-cancel icon"></span></button>
                </div>
                <div class="modal-body">
                    <div class="modal-b-title">Share your profile with friends and family</div>
                    <div class="modal-b-it d-flex align-items-center">
                        <img src="{{ asset($data->avatar )}}" alt=""/>
                        <div class="modal-b-it-title text-capitalize max-1-line">{{ $provider->name }}</div>
                    </div>
                    <div class="modal-grid">
                        <button id="copyUrlBrowser" class="btn share-linked-card">
                            <img src="{{ asset('assets/img/icons/copy.svg') }}" class="img-fluid" alt=""/>
                            <span class="url-text">Copy Link</span>
                        </button>
                        <button class="btn share-linked-card wap-btn" data-name="{{ $provider->name }}"
                                data-phone="{{ $provider->phone_number }}">
                            <img src="{{ asset('assets/img/icons/whatsapp.svg') }}" class="img-fluid" alt=""/>
                            Whatsapp
                        </button>
                        <button id="shareMessenger" class="btn share-linked-card">
                            <img src="{{ asset('assets/img/icons/messenger.svg') }}" class="img-fluid" alt=""/>
                            Messenger
                        </button>

                        <button id="shareFacebook" class="btn share-linked-card">
                            <img src="{{ asset('assets/img/icons/facebook.svg') }}" class="img-fluid" alt=""/>
                            Facebook
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Share Container -->

    <div class="wrapper-container wrapper-with-top">
        <div class="view-profile-container">
            <div class="container">
                <div class="view-profile-wrraper">

                    <!-- Breadcrumb -->
                    <div class="breadcrumb-container flex-between-vh mb-3">
                        <ol class="breadcrumb breadcrumb-list">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item text-capitalize"><a href="#">Service Providers</a></li>
                            <li class="breadcrumb-item text-capitalize active max-1-line"
                                aria-current="page">{{ $provider->name }}</li>
                        </ol>

                        <div class="share-btn ">
                            <button data-toggle="modal" data-target="#shareModal" class="btn btn-gray flex-center-vh">
                                <span class="icon-share icon mr-2"></span> Share
                            </button>
                        </div>
                    </div>
                    <!-- Breadcrumb -->

                    <!-- View Profile -->
                    <div class="view-profile-flexable flex-between-h">
                        <!-- User Card -->
                        <div class="view-profile-user-card text-center">
                            <div class="vpuc-body">
                                <div class="likes-container">
                                    <button {{ !Auth::check() ? "data-toggle=modal data-target=#loginDialog data-toggle=tooltip data-placement=top" : "" }}  title="{{ Auth::check() ? 'Like/UnLike' : "Please Login" }}"
                                            id="provider_fav_btn_{{ $provider->id }}"
                                            class="ns-like {{ Auth::check() ? 'favorite-btn' : '' }} {{ $provider->is_like ? 'active' : '' }}"
                                            data-id="{{ $provider->id }}" data-type="provider">
                                        <div id="provider_fav_content_{{ $provider->id }}" class="like-content">
                                            <span class="icon-heart-empty icon-unlike icon"></span>
                                            <span class="icon-heart-full icon-like icon"></span>
                                        </div>
                                        <div id="provider_fav_loading_{{ $provider->id }}" class="loading-content">
                                            <img class="img-fluid" src="{{ asset('assets/img/icons/ripple.svg') }}"/>
                                        </div>
                                    </button>
                                    <span id="provider_fav_count_{{ $provider->id }}"
                                          class="likes-count">{{ $provider->likes_count }}</span>
                                </div>
                                <div class="pro-user-view">
                                    <img class="img-fluid" src="{{ asset($data->avatar )}}" alt=""/>
                                </div>
                                <div class="pro-name title mb-2 text-capitalize">{{ $provider->name }}</div>
                                <div class="rating-container mb-2 justify-content-center">
                                    <form action="#">
                                        <select {{ Auth::check() ? '' : 'disabled' }} id="starRating"
                                                class="star-rating">
                                            <option {{ (int) $auth_rating['average'] == ''  ? 'selected="selected"' : '' }}   value="">
                                                Select a rating
                                            </option>
                                            <option {{ (int) $auth_rating['average'] == '5' ? 'selected="selected"' : '' }}   value="5">
                                                Excellent
                                            </option>
                                            <option {{ (int) $auth_rating['average'] == '4' ? 'selected="selected"' : '' }}   value="4">
                                                Very Good
                                            </option>
                                            <option {{ (int) $auth_rating['average'] == '3' ? 'selected="selected"' : '' }}   value="3">
                                                Average
                                            </option>
                                            <option {{ (int) $auth_rating['average'] == '2' ? 'selected="selected"' : '' }}   value="2">
                                                Poor
                                            </option>
                                            <option {{ (int) $auth_rating['average'] == '1' ? 'selected="selected"' : '' }}   value="1">
                                                Terrible
                                            </option>
                                        </select>
                                    </form>
                                    <span id="ratingCount" class="rating-count">({{$auth_rating['average']}})</span>
                                </div>
                                <div class="pro-date">based on {{sizeof($customer_reviews)}} ratings</div>
                                <div class="pro-type text-capitalize">{{$user_sub_categories_text}}</div>
                                @if(Auth::check())
                                    @if($provider->user_data->phone_status)
                                        @if(Auth::user()->user_data->user_type == 'Service Provider')
                                            <div class="vpuc-action mt-5">
                                                <button class="btn btn-bg w-100 add-provider-contact"
                                                        data-user-id="{{ $provider->id }}"
                                                        data-item-id="{{ $provider->id }}"
                                                        data-item-type="provider"
                                                        data-phone="{{ str_replace('+', '', $provider->country_code . $provider->phone_number) }}">
                                                    <span class="icon-call icon mr-2"></span> Contact Provider
                                                </button>
                                            </div>
                                        @else
                                            <div class="vpuc-action mt-5">
                                                <button class="btn btn-bg w-100 add-provider-contact"
{{--                                                        data-toggle="modal"--}}
{{--                                                        data-target="#upgradeMessageDialog"--}}
                                                        data-user-id="{{ $provider->user_data->id }}"
                                                        data-item-id="{{ $provider->user_data->id }}"
                                                        data-item-type="provider"
                                                        data-modal="upgrade"
                                                        data-phone="">
                                                    <span class="icon-call icon mr-2"></span> Contact Provider
                                                </button>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    <div class="vpuc-action mt-5">
                                        <button class="btn btn-bg w-100" data-toggle="modal" data-target="#loginDialog">
                                            <span class="icon-call icon mr-2"></span> Contact Provider
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- User Card -->

                        <!-- User Services -->
                        <div class="view-profile-services">
                            <div class="title">Available Services</div>
                            <div class="vpuc-tabs child-sub-category mt-3 mb-3">
                                {{--                                <button class="btn vpuc-tab-btn ptab-btn text-capitalize active">All</button>--}}
                                {{--                                <button class="btn vpuc-tab-btn ptab-btn text-capitalize">Plumbing</button>--}}
                                {{--                                <button class="btn vpuc-tab-btn ptab-btn text-capitalize">Gardening</button>--}}
                            </div>

                            <!-- Services Container -->
                            <div class="vps-container mb-5">
                                @if($service->isEmpty())
                                    <div class="empty-data-container">
                                        <div class="text-center">
                                            <img class="empty-data-img img-fluid"
                                                 src="{{ asset('assets/img/icons/empty-data.svg') }}"/>
                                            <div class="empty-data-title text-center">There Are No Services Yet</div>
                                        </div>
                                    </div>
                                @else
                                    @foreach($service as $item)
                                        <!-- Row -->
                                        <div class="vps-row">
                                            <div class="likes-container">
                                                <button {{ !Auth::check() ? "data-toggle=modal data-target=#loginDialog data-toggle=tooltip data-placement=top" : "" }} id="service_fav_btn_{{ $item->id }}"
                                                        class="ns-like {{ Auth::check() ? 'favorite-btn' : '' }} {{ $item->is_like ? 'active' : '' }}"
                                                        data-id="{{ $item->id }}" data-type="service">
                                                    <div id="service_fav_content_{{ $item->id }}" class="like-content">
                                                        <span class="icon-heart-empty icon-unlike icon"></span>
                                                        <span class="icon-heart-full icon-like icon"></span>
                                                    </div>
                                                    <div id="service_fav_loading_{{ $item->id }}"
                                                         class="loading-content">
                                                        <img class="img-fluid"
                                                             src="{{ asset('assets/img/icons/ripple.svg') }}"/>
                                                    </div>
                                                </button>
                                                <span id="service_fav_count_{{ $item->id }}"
                                                      class="likes-count">{{ $item->likes_count }}</span>
                                            </div>
                                            <div class="vps-view">
                                                <div class="swiper vps-swiper">
                                                    <!-- Additional required wrapper -->
                                                    <div class="swiper-wrapper">
                                                        @if (count($item->images) > 0)
                                                            @foreach($item->images as $image)
                                                                <div class="swiper-slide vps-slide">
                                                                    <img class="img-fluid vps-slide-view"
                                                                         src="{{ $image->image_path }}" alt=""/>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="swiper-slide vps-slide">
                                                                <img class="img-fluid vps-slide-view"
                                                                     src="{{ asset('assets/img/default/default_no_image.jpg') }}"
                                                                     alt=""/>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <!-- If we need pagination -->
                                                    <div class="vps-pagination flex-center-vh"></div>
                                                </div>
                                            </div>

                                            <div class="vps-details">
                                                <div class="vps-fgrow">
                                                    <div class="title mb-2 text-capitalize">{{ $item->service_name }}</div>
                                                    <div class="vps-price-parent">
                                                        <div class="vps-price">Start
                                                            From {{$configurations['value']}}{{ $item->starting_price }}</div>
                                                    </div>
                                                    <div class="vps-type mb-2 text-capitalize">{{ $item->category->sub_category_name }}</div>
                                                    <div class="vps-location flex-center-v">
                                                        <span class="icon-location icon mr-1"></span>
                                                        <span id="service_{{ $item->id }}" class="kilometers-distance"
                                                              data-selector="#service_{{ $item->id }}"
                                                              data-lat="{{ $item->location_lat }}"
                                                              data-lng="{{ $item->location_lng }}"></span> Away
                                                    </div>
                                                </div>
                                                <div class="vps-action text-right pt-2">
                                                    <a href="{{ route('service.view', $item->service_slug) }}"
                                                       class="btn btn-gray">View Service</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Row -->
                                    @endforeach
                                @endif
                            </div>
                            <!-- Services Container -->

                            <!-- Reviews Container -->
                            <div class="vpr-container">

                                <!-- Profile Reviews -->
                                <div class="view-product-reviews">
                                    <div class="title mb-3">Reviews</div>
                                    <div class="vpr-grid">
                                        <!-- Reviews Rate & Progress Bar -->
                                        <div class="vpr-badge">
                                            <div class="vpr-sub-badge flex-center-v">
                                                <div class="vpr-counter">
                                                    <div class="vpr-counter-cricle flex-center-vh mb-3">
                                                        <div class="text-center">
                                                            <div class="vpr-rate-count d-block">
                                                                {{(int)$customer_reviews_rating['average']}}
                                                            </div>
                                                            <div class="vpr-users-count d-block">{{sizeof($customer_reviews)}}
                                                                Reviews
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="vpr-rate">
                                                        <div class="rating-container flex-center-vh mb-3">
                                                            @for($i =0; $i < (int) $customer_reviews_rating['average']; $i++)
                                                                <span class="icon-star-empty icon gold"></span>
                                                            @endfor

                                                            @for ($i = 5 - (int) $customer_reviews_rating['average'] ;$i > 0; $i--)
                                                                <span class="icon-star-empty icon"></span>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Progress Bar -->
                                                <div class="vpr-progressbars">
                                                    <!-- Progress Bar Row -->
                                                    <div class="progress-bar-row">
                                                        <div class="pbr-title title text-capitalize lines-1">Over All
                                                        </div>
                                                        <div class="flex-progress flex-between-vh">
                                                            <div class="progress width-fluid">
                                                                <div class="progress-bar" role="progressbar"
                                                                     style="width: {{$customer_reviews_rating['average_percentage']}}%"
                                                                     aria-valuenow="25" aria-valuemin="0"
                                                                     aria-valuemax="100"></div>
                                                            </div>
                                                            <div class="progress-count-rate ml-3">
                                                                {{$customer_reviews_rating['average']}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Progress Bar Row -->

                                                    <!-- Progress Bar Row -->
                                                    <div class="progress-bar-row">
                                                        <div class="pbr-title title text-capitalize lines-1">Provider
                                                            Quality
                                                        </div>
                                                        <div class="flex-progress flex-between-vh">
                                                            <div class="progress width-fluid">
                                                                <div class="progress-bar" role="progressbar"
                                                                     style="width: {{$customer_reviews_rating['quality_percentage']}}%"
                                                                     aria-valuenow="25" aria-valuemin="0"
                                                                     aria-valuemax="100"></div>
                                                            </div>
                                                            <div class="progress-count-rate ml-3">
                                                                {{$customer_reviews_rating['quality']}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Progress Bar Row -->

                                                    <!-- Progress Bar Row -->
                                                    <div class="progress-bar-row">
                                                        <div class="pbr-title title text-capitalize lines-1">Provider
                                                            Time
                                                        </div>
                                                        <div class="flex-progress flex-between-vh">
                                                            <div class="progress width-fluid">
                                                                <div class="progress-bar" role="progressbar"
                                                                     style="width: {{$customer_reviews_rating['time_percentage']}}%"
                                                                     aria-valuenow="25" aria-valuemin="0"
                                                                     aria-valuemax="100"></div>
                                                            </div>
                                                            <div class="progress-count-rate ml-3">
                                                                {{$customer_reviews_rating['time']}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Progress Bar Row -->

                                                    <!-- Progress Bar Row -->
                                                    <div class="progress-bar-row">
                                                        <div class="pbr-title title text-capitalize lines-1">Accuracy
                                                        </div>
                                                        <div class="flex-progress flex-between-vh">
                                                            <div class="progress width-fluid">
                                                                <div class="progress-bar" role="progressbar"
                                                                     style="width: {{$customer_reviews_rating['accuracy_percentage']}}%"
                                                                     aria-valuenow="25" aria-valuemin="0"
                                                                     aria-valuemax="100"></div>
                                                            </div>
                                                            <div class="progress-count-rate ml-3">
                                                                {{$customer_reviews_rating['accuracy']}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Progress Bar Row -->

                                                    <!-- Progress Bar Row -->
                                                    <div class="progress-bar-row">
                                                        <div class="pbr-title title text-capitalize lines-1">
                                                            Communication
                                                        </div>
                                                        <div class="flex-progress flex-between-vh">
                                                            <div class="progress width-fluid">
                                                                <div class="progress-bar" role="progressbar"
                                                                     style="width: {{$customer_reviews_rating['communication_percentage']}}%"
                                                                     aria-valuenow="25" aria-valuemin="0"
                                                                     aria-valuemax="100"></div>
                                                            </div>
                                                            <div class="progress-count-rate ml-3">
                                                                {{$customer_reviews_rating['communication']}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Progress Bar Row -->

                                                </div>
                                            </div>
                                        </div>
                                        <!-- Reviews Rate & Progress Bar -->

                                        <!-- Reviews Comments -->
                                        <div class="vpr-comments-container">
                                            @if(sizeof($customer_reviews) === 0)
                                                <div class="empty-data-container">
                                                    <div class="text-center">
                                                        <img class="empty-data-img img-fluid"
                                                             src="{{ asset('assets/img/icons/empty-reviews.svg') }}"/>
                                                        <div class="empty-data-title text-center">There are no reviews
                                                            yet
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                @foreach($customer_reviews as $customer_review)
                                                    <!-- Comment Row -->
                                                    <div class="vpr-comment-row">
                                                        <a href="{{route('provider.profile', $customer_review->customer->id)}}"
                                                           class="vprc-user flex-center-v mb-3">
                                                            <img class="img-fluid"
                                                                 src="{{ asset($customer_review->customer->user_data->avatar)}}"
                                                                 alt=""/>
                                                            <div class="vprcu-details">
                                                                <div class="vprcu-title title text-capitalize lines-1">{{$customer_review->customer->name}}</div>
                                                                @if($customer_review->created_at === 0)

                                                                @else
                                                                    <div class="vprcu-date text">{{$customer_review->created_at ? $customer_review->created_at->diffForHumans() : 'UnAvailable'}}</div>
                                                                @endif
                                                            </div>
                                                        </a>

                                                        <div class="rating-container mb-3">
                                                            @for ($i = 0 ;$i < (int) $customer_review->rate; $i++)
                                                                <span class="icon-star-empty icon gold"></span>
                                                            @endfor

                                                            @for ($i = 5 - (int) $customer_review->rate ;$i > 0; $i--)
                                                                <span class="icon-star-empty icon"></span>
                                                            @endfor
                                                            <span class="rating-count">({{$customer_review->rate}})</span>
                                                        </div>

                                                        <div class="read-more-parent">
                                                        <span class="vprc-text text read-more-content"
                                                              data-length="300">
                                                        {{ $customer_review->description }}
                                                        </span>
                                                            <button class="read-more-btn"> ...Show More</button>
                                                        </div>
                                                    </div>
                                                    <!-- Comment Row -->
                                                @endforeach

                                                <!-- View More Action -->
                                                <div class="view-more-comments text-center">
                                                    <button id="viewMoreBtn" class="btn btn-simple">View More</button>
                                                </div>
                                                <!-- View More Action -->
                                            @endif
                                        </div>
                                        <!-- Reviews Comments -->

                                    </div>
                                </div>
                                <!-- Profile Reviews -->

                            </div>
                            <!-- Reviews Container -->
                        </div>
                        <!-- User Services -->
                    </div>
                    <!-- View Profile -->

                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        // Main Profile Tabs
        $(function () {
            $(".ptab-btn").on('click', function () {
                var id = $(this).attr("id");
                $(this).addClass('active').siblings().removeClass("active");
                $(`.pc-tab[data-tab-profile="#${id}"]`).addClass("active").siblings().removeClass("active");
            });
        });


        $(function () {
            const swiper = new Swiper('.vps-swiper', {
                // Optional parameters
                direction: 'horizontal',
                loop: false,
                spaceBetween: 15,

                // If we need pagination
                pagination: {
                    el: '.vps-pagination',
                    clickable: true
                },
            });
        });
    </script>
    <script>
        $(window).scroll(function () {
            if ($(window).scrollTop() >= 80) {
                $('.vpuc-body').addClass('active-fixed');
            } else {
                $('.vpuc-body').removeClass('active-fixed');
            }
        });
        $(window).on("load", function () {
            if ($(window).scrollTop() >= 80) {
                $('.vpuc-body').addClass('active-fixed');
            } else {
                $('.vpuc-body').removeClass('active-fixed');
            }
        });
    </script>
    <script>
        //update rate and description
        var review_id = {{ Js::from($data->user_id) }};//provider id
        var quality = {{ Js::from($auth_rating['quality']) }};
        var time = {{ Js::from($auth_rating['time']) }};
        var accuracy = {{ Js::from($auth_rating['accuracy']) }};
        var communication = {{ Js::from($auth_rating['communication']) }};
        var comment = {{ Js::from($auth_rating['comment']) }};


        $("#quality_rating").on("change", function (event) {
            if (event.target.value == 0) {
                quality = 0;
            } else {
                quality = event.target.value;
            }
        })

        $("#time_rating").on("change", function (event) {
            if (event.target.value == 0) {
                time = 0;
            } else {
                time = event.target.value;
            }
        })

        $("#accuracy_rating").on("change", function (event) {
            if (event.target.value == 0) {
                accuracy = 0;
            } else {
                accuracy = event.target.value;
            }
        })

        $("#Communication_rating").on("change", function (event) {
            if (event.target.value == 0) {
                communication = 0;
            } else {
                communication = event.target.value;
            }
            // window.location.href = "{{URL::to('provider-update-rate')}}"+ "/"+value + "/"+review_id
        })

        $("#saveReviewBtn").click(function () {
            comment = document.getElementById("comment-textarea").value;

            if (quality == null) {
                quality = 0;
            }

            if (time == null) {
                time = 0;
            }

            if (accuracy == null) {
                accuracy = 0;
            }

            if (communication == null) {
                communication = 0;
            }

            updateRating(quality, time, accuracy, communication, comment, review_id)

        });

        function updateRating(quality, time, accuracy, communication, comment, review_id) {

            let url = "";
            url = '/provider-update-rate/'
            //console.log(url)

            $("#saveReviewBtn").attr('disabled', true);
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    quality : quality,
                    time: time,
                    accuracy: accuracy,
                    communication: communication,
                    review_id: review_id,
                    comment: comment,
                },
                accept: 'application/json',
                beforeSend: function () {
                    $("#saveReviewBtn").attr('disabled', true);
                },
                success: function (res) {
                    $("#saveReviewBtn").attr('disabled', false);
                    $('#closeRatingProvider').click();
                    if (res.success) {
                        $("#reviewPendingDialog").modal('show');
                    }
                },
                error: function () {
                    $("#saveReviewBtn").attr('disabled', false);
                }
            });
        }

    </script>

@endsection
