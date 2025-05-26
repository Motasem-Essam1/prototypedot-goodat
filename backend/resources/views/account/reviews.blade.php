@extends('layouts.app')
@section('title', 'My Favorites')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/profile.css') }}" />
@endsection
@section('content')
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
                            <!-- My Favorites -->
                            <div data-tab-profile="#myFavorites" class="pc-tab active">
                                <div class="pct-bg">
                                    <div class="pc-title title">MY REVIEWS</div>
                                    <div data-tab-profile="#reviewServices" class="pcf-container pt-0 pb-0 active">
                                        <div class="pc-sub-title mb-3">You have total {{sizeof($service_CustomerReviews)}} Reviews Services.</div>
                                    </div>
                                    <div data-tab-profile="#reviewProviders" class="pcf-container pt-0 pb-0">
                                        <div class="pc-sub-title mb-3">You have total {{sizeof($provider_CustomerReviews)}} Reviews Providers.</div>
                                    </div>
                                    <div class="pc-main-tabs-actions d-inline-block">
                                        <button id="reviewServices" class="pcmta-btn active mr-2">Services</button>
                                        <button id="reviewProviders" class="pcmta-btn">Providers</button>
                                    </div>
                                </div>

                                <!-- Services Container -->
                                <div data-tab-profile="#reviewServices" class="pcf-container active">
                                    @if(count($service_CustomerReviews) > 0)
                                        <div class="reviews-grid">
                                            @foreach($service_CustomerReviews as $service_CustomerReview)
                                                <!-- Row -->
                                                <div class="review-row">
                                                    @if ($service_CustomerReview->approvel == 1)
                                                        <div class="review-status review-published">Published</div>
                                                    @else
                                                        <div class="review-status review-pending">Pending...</div>
                                                    @endif
                                                    <div class="review-header mb-4">
                                                        <div class="rw-user-img">
                                                            <img class="img-fluid w-100 h-100" src="{{ count($service_CustomerReview->service->images) > 0 ? asset($service_CustomerReview->service->images[0]->image_path) : asset('assets/img/default/default_no_image.jpg') }}" />
                                                        </div>
                                                        <div class="review-details d-flex align-items-center pl-3">
                                                            <div>
                                                                <div class="title text-capitalize max-1-line">{{$service_CustomerReview->service->service_name}}</div>
                                                                <div class="ns-price ml-0"> Price Start From {{$configurations['value']}}{{$service_CustomerReview->service->starting_price}}</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="review-block">
                                                        <div class="review-rating-block d-flex align-items-center mb-2">
                                                            <div class="rating-container">
                                                                @for ($i = 0 ;$i <  (int) $service_CustomerReview->rate; $i++)
                                                                    <span class="icon-star-empty icon gold"></span>
                                                                @endfor

                                                                @for ($i = 5 - (int) $service_CustomerReview->rate ;$i > 0; $i--)
                                                                    <span class="icon-star-empty icon"></span>
                                                                @endfor
                                                                <span class="rating-count">({{$service_CustomerReview->rate}})</span>
                                                            </div>
                                                            <span class="bullet"></span>
                                                            @if($service_CustomerReview->created_at)
                                                                <div class="review-date">{{$service_CustomerReview->created_at ? $service_CustomerReview->created_at->diffForHumans() : 'UnAvailable'}}</div>
                                                            @else
                                                                <div class="review-date"></div>
                                                            @endif
                                                        </div>
                                                        <div class="review-comment">
                                                            {{ $service_CustomerReview->description }}
                                                        </div>
                                                        <div class="view-action text-right mt-3">
                                                            <a href="/service-view/{{$service_CustomerReview->service->service_slug}}" class="btn btn-gray">View Now</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Row -->
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="empty-data-container">
                                            <div class="text-center">
                                                <img class="empty-data-img img-fluid" src="{{ asset('assets/img/icons/empty-reviews.svg') }}" />
                                                <div class="empty-data-title text-center">There are no reviews yet</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!-- Services Container -->

                                <!-- Providers Container -->
                                <div data-tab-profile="#reviewProviders" class="pcf-container">
                                    @if (count($provider_CustomerReviews) > 0)
                                        <div class="reviews-grid">
                                            @foreach($provider_CustomerReviews as $provider_CustomerReview)
                                                <!-- Row -->
                                                <div class="review-row">
                                                    @if ($provider_CustomerReview->approvel == 1)
                                                        <div class="review-status review-published">Published</div>
                                                    @else
                                                        <div class="review-status review-pending">Pending...</div>
                                                    @endif
                                                    <div class="review-header mb-4">
                                                        <div class="rw-user-img">
                                                            <img class="img-fluid w-100 h-100" src="{{ asset($provider_CustomerReview->user->user_data->avatar) }}" />
                                                        </div>
                                                        <div class="review-details d-flex align-items-center pl-3">
                                                            <div>
                                                                <div class="title text-capitalize max-1-line">{{$provider_CustomerReview->user->name}}</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="review-block">
                                                        <div class="review-rating-block d-flex align-items-center mb-2">
                                                            <div class="rating-container">
                                                                @for ($i = 0 ;$i <  (int) $provider_CustomerReview->rate; $i++)
                                                                    <span class="icon-star-empty icon gold"></span>
                                                                @endfor

                                                                @for ($i = 5 - (int) $provider_CustomerReview->rate ;$i > 0; $i--)
                                                                    <span class="icon-star-empty icon"></span>
                                                                @endfor
                                                                <span class="rating-count">({{$provider_CustomerReview->rate}})</span>
                                                            </div>
                                                            <span class="bullet"></span>
                                                            <div class="review-date">{{$provider_CustomerReview->created_at ? $customer_review->created_at->diffForHumans() : 'UnAvailable'}}</div>
                                                        </div>
                                                        <div class="review-comment">
                                                            {{ $provider_CustomerReview->description }}
                                                        </div>
                                                        <div class="view-action text-right mt-3">
                                                            <a href="/service-provider/{{$provider_CustomerReview->user->id}}" class="btn btn-gray">View Now</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Row -->
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="empty-data-container">
                                            <div class="text-center">
                                                <img class="empty-data-img img-fluid" src="{{ asset('assets/img/icons/empty-reviews.svg') }}" />
                                                <div class="empty-data-title text-center">There are no reviews yet</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!-- Providers Container -->
                            </div>
                            <!-- My Favorites -->
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
    // Tabs
    $(function () {
      $(".pcmta-btn").on('click', function () {
        var id = $(this).attr("id");
        $(this).addClass('active active-res').siblings().removeClass("active active-res");
        $(`.pcf-container[data-tab-profile="#${id}"]`).addClass("active active-res").siblings().removeClass("active active-res");
      });
    });
</script>
@endsection
