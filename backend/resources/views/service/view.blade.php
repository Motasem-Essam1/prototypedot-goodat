@extends('layouts.app')
@section('title', $service->service_name)
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/pages/view-product.css') }}"/>
    <meta property="fb:app_id" content="629322731899614"/>
    <meta property="og:title" content="{{ $service->service_name }}"/>
    <meta property="og:description" content="{{ $service->service_description }}"/>
    {{--    <meta property="og:type" content="Services" />--}}
    <meta property="og:image"
          content="{{count($service->images) > 0 ? $service->images[0]->image_path : asset('assets/img/default/default_no_image.jpg') }}"/>
    <meta property="og:url" content="https://deftat.com/service-view/service-{{$service->id}}"/>
    <meta property="og:image:width" content="1200"/>
    <meta property="og:image:height" content="630"/>
@endsection
@section('content')
    <!-- Modal Rating -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingModalLabel">Rating Service</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group-row mb-3">
                        <label class="fgr-label">Service Quality</label>
                        <div class="d-flex align-items-center">
                            <select id="quality_rating" {{ Auth::check() ? '' : 'disabled' }} class="star-rating"
                                    data-count="#qualityRating">
                                <option {{ $auth_rating['quality'] == ''  ? 'selected="selected"' : '' }} value="">
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
                        <label class="fgr-label">Service Time</label>
                        <div class="d-flex align-items-center">
                            <select id="time_rating" {{ Auth::check() ? '' : 'disabled' }} class="star-rating"
                                    data-count="#timeRating">
                                <option {{ $auth_rating['time'] == ''  ? 'selected="selected"' : '' }} value="">Select a
                                    rating
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
                                <option {{ $auth_rating['accuracy'] == ''  ? 'selected="selected"' : '' }} value="">
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
                                <option
                                    {{ $auth_rating['communication'] == ''  ? 'selected="selected"' : '' }} value="">
                                    Select a rating
                                </option>
                                <option
                                    {{ $auth_rating['communication'] == '5' ? 'selected="selected"' : '' }} value="5">
                                    Excellent
                                </option>
                                <option
                                    {{ $auth_rating['communication'] == '4' ? 'selected="selected"' : '' }} value="4">
                                    Very Good
                                </option>
                                <option
                                    {{ $auth_rating['communication'] == '3' ? 'selected="selected"' : '' }} value="3">
                                    Average
                                </option>
                                <option
                                    {{ $auth_rating['communication'] == '2' ? 'selected="selected"' : '' }} value="2">
                                    Poor
                                </option>
                                <option
                                    {{ $auth_rating['communication'] == '1' ? 'selected="selected"' : '' }} value="1">
                                    Terrible
                                </option>
                            </select>
                            <span id="communicationRating"
                                  class="rating-count">({{$auth_rating['communication']}})</span>
                        </div>
                    </div>

                    <div class="form-group-row">
                        <label class="fgr-label">Review Comment</label>
                        <textarea id="comment-textarea" class="fgr-input info-input fgr-textarea" name="review_comment"
                                  placeholder="Write your review here">{{$auth_rating['comment']}}</textarea>
                    </div>
                </div>
                <div class="modal-footer modal-footer-unround">
                    <button id="closeRatingProvider" type="button" class="btn button-default" data-bs-dismiss="modal">
                        Close
                    </button>
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
                    <button type="button" class="btn-close " data-dismiss="modal" aria-label="Close"><span
                            class="icon-cancel icon"></span></button>
                </div>
                <div class="modal-body">
                    <div class="modal-b-title">Share your service with friends and family</div>
                    <div class="modal-b-it d-flex align-items-center">
                        <img
                            src="{{ count($service->images) > 0 ? $service->images[0]->image_path : asset('assets/img/default/default_no_image.jpg') }}"
                            alt="">
                        <div class="modal-b-it-title text-capitalize max-1-line">{{ $service->service_name }}</div>
                    </div>
                    <div class="modal-grid">
                        <button id="copyUrlBrowser" class="btn share-linked-card">
                            <img src="{{ asset('assets/img/icons/copy.svg') }}" class="img-fluid" alt=""/>
                            <span class="url-text">Copy Link</span>
                        </button>
                        <button class="btn share-linked-card wap-btn" data-name="{{ $service->user->name }}"
                                data-phone="{{ $service->user->phone_number }}">
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
        <div class="view-product-container">
            <div class="container">
                <div class="view-product-wrraper">
                    <div class="breadcrumb-container flex-between-vh mb-3">
                        <ol class="breadcrumb breadcrumb-list">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item text-capitalize"><a
                                    href="#">{{ $service->category->sub_category_name }}</a></li>
                            <li class="breadcrumb-item text-capitalize active max-1-line"
                                aria-current="page">{{ $service->service_name }}</li>
                        </ol>

                        <div class="share-btn ">
                            <button data-toggle="modal" data-target="#shareModal" class="btn btn-gray flex-center-vh">
                                <span class="icon-share icon mr-2"></span> Share
                            </button>
                        </div>
                    </div>

                    <!-- View Product Header -->
                    <div class="view-product-header flex-between-h">
                        <!-- Swiper -->
                        <div class="view-product-slider">
                            <!-- Swiper Top -->
                            <div class="vps-top-parent">
                                <div class="swiper vps-top">
                                    <!-- Additional required wrapper -->
                                    <div class="swiper-wrapper">
                                        @if(count($service->images) > 0)
                                            @foreach($service->images as $image)
                                                <!-- Slide Row -->
                                                <div class="swiper-slide">
                                                    <div class="vps-top-slide">
                                                        <img class="img-fluid" src="{{ $image->image_path }}"
                                                             alt="Product"/>
                                                    </div>
                                                </div>
                                                <!-- Slide Row -->
                                            @endforeach
                                        @else
                                            <!-- Slide Row -->
                                            <div class="swiper-slide">
                                                <div class="vps-top-slide">
                                                    <img class="img-fluid"
                                                         src="{{ asset('assets/img/default/default_no_image.jpg') }}"
                                                         alt="Product"/>
                                                </div>
                                            </div>
                                            <!-- Slide Row -->
                                        @endif
                                    </div>
                                    <!-- If we need pagination -->
                                    <div class="vps-pagination flex-center-vh"></div>
                                </div>

                                <!-- Arrows -->
                                <div class="vps-arrows flex-between-vh">
                                    <button class="btn vps-arrow-btn vps-arrow-prev flex-center-vh"><span
                                            class="icon-arrow-left icon"></span></button>
                                    <button class="btn vps-arrow-btn vps-arrow-next flex-center-vh"><span
                                            class="icon-arrow-right icon"></span></button>
                                </div>
                            </div>

                            <div class="vps-thumbs-parent">
                                <!-- Swiper Thumbs -->
                                <div class="swiper vps-thumbs">
                                    <!-- Additional required wrapper -->
                                    <div class="swiper-wrapper">
                                        @if(count($service->images) > 0)
                                            @foreach($service->images as $image)
                                                <!-- Slide Row -->
                                                <div class="swiper-slide">
                                                    <div class="vps-thumb-slide">
                                                        <img class="img-fluid" src="{{ $image->image_path }}"
                                                             alt="Product"/>
                                                    </div>
                                                </div>
                                                <!-- Slide Row -->
                                            @endforeach
                                        @else
                                            <!-- Slide Row -->
                                            <div class="swiper-slide">
                                                <div class="vps-thumb-slide">
                                                    <img class="img-fluid"
                                                         src="{{ asset('assets/img/default/default_no_image.jpg') }}"
                                                         alt="Product"/>
                                                </div>
                                            </div>
                                            <!-- Slide Row -->
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Swiper -->

                        <!-- Product Details -->
                        <div class="view-product-details">
                            <div class="likes-container">
                                <button
                                    {{ !Auth::check() ? "data-toggle=modal data-target=#loginDialog data-toggle=tooltip data-placement=top" : "" }}  title="{{ Auth::check() ? 'Like/UnLike' : "Please Login" }}"
                                    id="service_fav_btn_{{ $service->id }}"
                                    class="ns-like {{ Auth::check() ? 'favorite-btn' : '' }} {{ $service->is_like ? 'active' : '' }}"
                                    data-id="{{ $service->id }}" data-type="service">
                                    <div id="service_fav_content_{{ $service->id }}" class="like-content">
                                        <span class="icon-heart-empty icon-unlike icon"></span>
                                        <span class="icon-heart-full icon-like icon"></span>
                                    </div>
                                    <div id="service_fav_loading_{{ $service->id }}" class="loading-content">
                                        <img class="img-fluid" src="{{ asset('assets/img/icons/ripple.svg') }}"/>
                                    </div>
                                </button>
                                <span id="service_fav_count_{{ $service->id }}"
                                      class="likes-count">{{ $service->likes_count }}</span>
                            </div>
                            <!-- Product Row -->
                            <div class="view-product-row">
                                <div class="vpr-top">
                                    <div
                                        class="vpd-type mb-1 text-capitalize">{{ $service->category->sub_category_name }}</div>
                                    <div class="title mb-2 text-capitalize">{{ $service->service_name }}</div>
                                    <div class="rating-container mb-3">
                                        <form action="#">
                                            <div class="d-flex align-items-center">
                                                <select {{ Auth::check() ? '' : 'disabled' }} id="starRating"
                                                        class="star-rating" data-count="#normalRating">
                                                    <option
                                                        {{ (int) $auth_rating['average'] == ''  ? 'selected="selected"' : '' }} value="">
                                                        Select a rating
                                                    </option>
                                                    <option
                                                        {{ (int) $auth_rating['average'] == '5' ? 'selected="selected"' : '' }} value="5">
                                                        Excellent
                                                    </option>
                                                    <option
                                                        {{ (int) $auth_rating['average'] == '4' ? 'selected="selected"' : '' }} value="4">
                                                        Very Good
                                                    </option>
                                                    <option
                                                        {{ (int) $auth_rating['average'] == '3' ? 'selected="selected"' : '' }} value="3">
                                                        Average
                                                    </option>
                                                    <option
                                                        {{ (int) $auth_rating['average'] == '2' ? 'selected="selected"' : '' }} value="2">
                                                        Poor
                                                    </option>
                                                    <option
                                                        {{ (int) $auth_rating['average'] == '1' ? 'selected="selected"' : '' }} value="1">
                                                        Terrible
                                                    </option>
                                                </select>
                                                <span id="normalRating" class="rating-count">({{$auth_rating['average']}})</span>
                                            </div>
                                        </form>
                                        {{--                                        <span class="icon-star-empty icon @if($service->rateValue >= 1) gold @endif"></span>--}}
                                        {{--                                        <span class="icon-star-empty icon @if($service->rateValue >= 2) gold @endif"></span>--}}
                                        {{--                                        <span class="icon-star-empty icon @if($service->rateValue >= 3) gold @endif"></span>--}}
                                        {{--                                        <span class="icon-star-empty icon @if($service->rateValue >= 4) gold @endif"></span>--}}
                                        {{--                                        <span class="icon-star-empty icon @if($service->rateValue == 5) gold @endif"></span>--}}
                                        {{--                                        <span id="ratingCount" class="rating-count">({{ $service->rateValue }})</span>--}}
                                    </div>
                                    <div class="vpd-price-parent">
                                        <div class="vpd-price">Start
                                            From {{$configurations['value']}}{{ $service->starting_price }}</div>
                                    </div>
                                    <div class="vpd-location flex-center-v mb-3">
                                        <span class="icon-location icon mr-1"></span>
                                        <span id="service_{{ $service->id }}" class="kilometers-distance"
                                              data-selector="#service_{{ $service->id }}"
                                              data-lat="{{ $service->location_lat }}"
                                              data-lng="{{ $service->location_lng }}"></span> Away
                                    </div>

                                    <div class="vpd-text text scroller">
                                        {{ $service->service_description }}
                                    </div>
                                    <div class="vpd-user-product flex-between-vh mt-4">
                                        <a href="{{ route('provider.profile', $service->user->id) }}"
                                           class="vpd-view-user d-flex align-items-center">
                                            <img class="img-fluid" src="{{ asset($service->user->user_data->avatar) }}"
                                                 alt=""/>
                                            <div class="vpd-user-details">
                                                <div
                                                    class="vpd-title title text-capitalize lines-1">{{ $service->user->name }}</div>
                                                <div class="vpd-text text d-flex align-items-center">View Profile <span
                                                        class="icon-arrow-right icon"></span></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                @if(Auth::check())
                                    @if($service->user->user_data->phone_status)
                                        @if(Auth::user()->user_data->user_type == 'Service Provider')
                                            <div class="vpuc-action mt-5">
                                                <button class="btn btn-bg w-100 add-provider-contact"
                                                        data-user-id="{{ $service->user->id }}"
                                                        data-item-id="{{ $service->id }}"
                                                        data-item-type="service"
                                                        data-phone="{{ str_replace('+', '', $service->user->country_code . $service->user->phone_number) }}">
                                                    <span class="icon-call icon mr-2"></span> Contact Provider
                                                </button>
                                            </div>
                                        @else
                                            <div class="vpuc-action mt-5">
                                                <button class="btn btn-bg w-100 add-provider-contact"
{{--                                                        data-toggle="modal"--}}
{{--                                                        data-target="#upgradeMessageDialog"--}}
                                                        data-user-id="{{ $service->user->id }}"
                                                        data-item-id="{{ $service->id }}"
                                                        data-item-type="service"
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
                            <!-- Product Row -->
                        </div>
                        <!-- Product Details -->
                    </div>
                    <!-- View Product Header -->

                    <!-- Product Reviews -->
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
                                            <div class="pbr-title title text-capitalize lines-1">Over All</div>
                                            <div class="flex-progress flex-between-vh">
                                                <div class="progress width-fluid">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{$customer_reviews_rating['average_percentage']}}%"
                                                         aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <div class="progress-count-rate ml-3">
                                                    {{$customer_reviews_rating['average']}}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Progress Bar Row -->

                                        <!-- Progress Bar Row -->
                                        <div class="progress-bar-row">
                                            <div class="pbr-title title text-capitalize lines-1">Provider Quality</div>
                                            <div class="flex-progress flex-between-vh">
                                                <div class="progress width-fluid">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{$customer_reviews_rating['quality_percentage']}}%"
                                                         aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <div class="progress-count-rate ml-3">
                                                    {{$customer_reviews_rating['quality']}}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Progress Bar Row -->

                                        <!-- Progress Bar Row -->
                                        <div class="progress-bar-row">
                                            <div class="pbr-title title text-capitalize lines-1">Provider Time</div>
                                            <div class="flex-progress flex-between-vh">
                                                <div class="progress width-fluid">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{$customer_reviews_rating['time_percentage']}}%"
                                                         aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <div class="progress-count-rate ml-3">
                                                    {{$customer_reviews_rating['time']}}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Progress Bar Row -->

                                        <!-- Progress Bar Row -->
                                        <div class="progress-bar-row">
                                            <div class="pbr-title title text-capitalize lines-1">Accuracy</div>
                                            <div class="flex-progress flex-between-vh">
                                                <div class="progress width-fluid">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{$customer_reviews_rating['accuracy_percentage']}}%"
                                                         aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <div class="progress-count-rate ml-3">
                                                    {{$customer_reviews_rating['accuracy']}}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Progress Bar Row -->

                                        <!-- Progress Bar Row -->
                                        <div class="progress-bar-row">
                                            <div class="pbr-title title text-capitalize lines-1">Communication</div>
                                            <div class="flex-progress flex-between-vh">
                                                <div class="progress width-fluid">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{$customer_reviews_rating['communication_percentage']}}%"
                                                         aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
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
                                            <div class="empty-data-title text-center">There are no reviews yet</div>
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
                                                    <div
                                                        class="vprcu-title title text-capitalize lines-1">{{$customer_review->customer->name}}</div>
                                                    @if($customer_review->created_at === 0)

                                                    @else
                                                        <div
                                                            class="vprcu-date text">{{$customer_review->created_at ? $customer_review->created_at->diffForHumans() : 'UnAvailable'}}</div>
                                                    @endif
                                                </div>
                                            </a>

                                            <div class="rating-container mb-3">
                                                @for ($i = 0 ;$i <  (int) $customer_review->rate; $i++)
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
                    <!-- Product Reviews -->

                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        $(function () {
            var galleryThumbs = new Swiper('.vps-thumbs', {
                spaceBetween: 17,
                slidesPerView: 6,
                freeMode: true,
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
                breakpoints: {
                    // when window width is >= 320px
                    320: {
                        slidesPerView: 4,
                        spaceBetween: 13
                    },
                    // when window width is >= 480px
                    480: {
                        // slidesPerView: 1.6,
                        spaceBetween: 13
                    },
                    // when window width is >= 640px
                    576: {
                        slidesPerView: 5,
                        spaceBetween: 13
                    },
                    768: {
                        slidesPerView: 4,
                        spaceBetween: 13
                    },
                    992: {
                        slidesPerView: 5,
                        spaceBetween: 13
                    },
                    1200: {
                        slidesPerView: 6,
                        spaceBetween: 17
                    }
                }
            });
            var galleryTop = new Swiper('.vps-top', {
                spaceBetween: 10,
                slidesPerView: 1,
                speed: 670,
                autoplay: {
                    delay: 5000,
                },
                navigation: {
                    nextEl: '.vps-arrow-next',
                    prevEl: '.vps-arrow-prev',
                },
                pagination: {
                    el: '.vps-pagination',
                    clickable: true
                },
                thumbs: {
                    swiper: galleryThumbs,
                },
            });
        });

    </script>

    <script>
        //update rate and description
        var review_id = {{ Js::from($service->id) }};//provider id
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
            url = '/service-update-rate/';

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
