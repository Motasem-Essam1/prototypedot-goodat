@extends('layouts.app')
@section('title', 'My Favorites')
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
                                    <div class="pc-title title">MY FAVORITES</div>
                                    <div data-tab-profile="#favServices" class="pcf-container pt-0 pb-0 active">
                                        <div class="pc-sub-title mb-3">You have total {{ $services->total() }} Favorites Services.</div>
                                    </div>
                                    <div data-tab-profile="#favTasks" class="pcf-container pt-0 pb-0">
                                        <div class="pc-sub-title mb-3">You have total {{ $tasks->total() }} Favorites Tasks.</div>
                                    </div>
                                    <div data-tab-profile="#favProviders" class="pcf-container pt-0 pb-0">
                                        <div class="pc-sub-title mb-3">You have total {{ $providers->total() }} Favorites Providers.</div>
                                    </div>
                                    <div class="pc-main-tabs-actions d-inline-block">
                                        <button id="favServices" class="pcmta-btn mr-2 active">Services</button>
                                        <button id="favTasks" class="pcmta-btn mr-2">Tasks</button>
                                        <button id="favProviders" class="pcmta-btn">Providers</button>
                                    </div>
                                </div>

                                <!-------- Services -------->
                                <div data-tab-profile="#favServices" class="pcf-container active">
                                    @if(count($services) > 0)
                                        <div class="pfc-grid-cols">
                                            <!-- Row -->
                                            @foreach($services as $service)
                                                <div class="pfc-row">
                                                    <div class="likes-container">
                                                        <button id="service_fav_btn_{{ $service->id }}" class="ns-like favorite-btn active" data-id="{{ $service->id }}" data-type="service">
                                                            <div id="service_fav_content_{{ $service->id }}" class="like-content">
                                                                <span class="icon-heart-empty icon-unlike icon"></span>
                                                                <span class="icon-heart-full icon-like icon"></span>
                                                            </div>
                                                            <div id="service_fav_loading_{{ $service->id }}" class="loading-content">
                                                                <img class="img-fluid" src="{{ asset('assets/img/icons/ripple.svg') }}" />
                                                            </div>
                                                        </button>
                                                        <span id="service_fav_count_{{ $service->id }}" class="likes-count">{{ $service->likes_count }}</span>
                                                    </div>
                                                    <a href="{{ route('provider.profile', $service->user->id) }}" class="ns-user">
                                                        <img src="{{ asset($service->user->user_data->avatar) }}" class="img-fluid" alt="" />
                                                        <div class="ns-user-name text-capitalize lines-1">{{ $service->user->name }}</div>
                                                    </a>
                                                    <div class="ns-user-view">
                                                        <a href="{{ route('service.view', $service->service_slug) }}"><img class="img-fluid" src="{{ count($service->images) > 0 ? $service->images[0]->image_path : asset('assets/img/default/default_no_image.jpg') }}" alt="" /></a>
                                                    </div>
                                                    <div class="mb-2">
                                                        <a href="{{ route('service.view', $service->service_slug) }}" class="pfcr-title title mb-2 text-capitalize lines-1">{{ $service->service_name }}</a>
                                                    </div>
                                                    <div class="ns-price">Start from {{$configurations['value']}}{{ $service->starting_price }}</div>
                                                    <div class="rating-container">
                                                        @for ($i = 0 ;$i < (int) $service->rate; $i++)
                                                        <span class="icon-star-empty icon gold"></span>
                                                        @endfor

                                                        @for ($i = 5 - (int) $service->rate ;$i > 0; $i--)
                                                        <span class="icon-star-empty icon"></span>
                                                        @endfor
                                                        <span class="rating-count">({{$service->rate}})</span>
                                                    </div>
                                                    <div class="ns-type text-capitalize">{{$service->category->sub_category_slug}}</div>
                                                </div>
                                            @endforeach
                                            <!-- Row -->

                                        </div>

                                        <!-- Pagination -->
                                        @if ($services->hasPages())
                                            <div class="profile-pagination main-pagination mt-5 pb-2">
                                                <div aria-label="Page navigation">
                                                    {{ $services->links('pagination::bootstrap-4') }}
                                                </div>
                                            </div>
                                        @endif
                                        <!-- Pagination -->
                                    @else
                                        <div class="empty-data-container">
                                            <div class="text-center">
                                                <img class="empty-data-img img-fluid" src="{{ asset('assets/img/icons/empty-data.svg') }}" />
                                                <div class="empty-data-title text-center">There are no services yet</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!-------- Services -------->

                                <!-------- Tasks -------->
                                <div data-tab-profile="#favTasks" class="pcf-container">
                                    @if(count($tasks) > 0)
                                        <div class="pfc-grid-cols">
                                            <!-- Row -->
                                            @foreach($tasks as $task)
                                                <div class="swiper-slide">
                                                    <div>
                                                        <div class="likes-container">
                                                            <button id="task_fav_btn_{{ $task->id }}" class="ns-like favorite-btn {{ $task->is_like ? 'active' : '' }}" data-id="{{ $task->id }}" data-type="task">
                                                                <div id="task_fav_content_{{ $task->id }}" class="like-content">
                                                                    <span class="icon-heart-empty icon-unlike icon"></span>
                                                                    <span class="icon-heart-full icon-like icon"></span>
                                                                </div>
                                                                <div id="task_fav_loading_{{ $task->id }}" class="loading-content">
                                                                    <img class="img-fluid" src="{{ asset('assets/img/icons/ripple.svg') }}" />
                                                                </div>
                                                            </button>
                                                            <span id="task_fav_count_{{ $task->id }}" class="likes-count">{{ $task->likes_count }}</span>
                                                        </div>
                                                        <a href="{{ route('task.view', $task->task_slug) }}">
                                                            <div class="tasks-slide">
                                                                <div class="tasks-user-view">
                                                                    <img class="img-fluid" src="{{ count($task->images) > 0 ? $task->images[0]->image_path : asset('assets/img/default/default_no_image.jpg') }}" alt="" />
                                                                </div>
                                                                <div class="title mb-2">{{ $task->task_name }}</div>
                                                                <div class="tasks-price">Start from {{$configurations['value']}}{{ $task->starting_price }}</div>
                                                                <div class="rating-container">
                                                                    @for ($i = 0 ;$i < (int) $task->rate; $i++)
                                                                    <span class="icon-star-empty icon gold"></span>
                                                                    @endfor

                                                                    @for ($i = 5 - (int) $task->rate ;$i > 0; $i--)
                                                                    <span class="icon-star-empty icon"></span>
                                                                    @endfor
                                                                    <span class="rating-count">({{$task->rate}})</span>
                                                                </div>
                                                                <div class="ns-type text-capitalize">{{$task->category->sub_category_slug}}</div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <!-- Row -->

                                        </div>

                                        <!-- Pagination -->
                                        @if ($tasks->hasPages())
                                            <div class="profile-pagination main-pagination mt-5 pb-2">
                                                <div aria-label="Page navigation">
                                                    {{ $tasks->links('pagination::bootstrap-4') }}
                                                </div>
                                            </div>
                                        @endif
                                        <!-- Pagination -->
                                    @else
                                        <div class="empty-data-container">
                                            <div class="text-center">
                                                <img class="empty-data-img img-fluid" src="{{ asset('assets/img/icons/empty-data.svg') }}" />
                                                <div class="empty-data-title text-center">There are no tasks yet</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!-------- Tasks -------->

                                <!-------- Providers -------->
                                <div data-tab-profile="#favProviders" class="pcf-container">
                                    @if(count($providers) > 0)
                                        <div class="pfc-grid-cols">
                                            <!-- Row -->
                                            @foreach($providers as $provider)
                                                <div class="pfc-row pfc-provider-row text-center">
                                                    <div class="likes-container">
                                                        <button id="provider_fav_btn_{{ $provider->id }}" class="ns-like favorite-btn active" data-id="{{ $provider->id }}" data-type="provider">
                                                            <div id="provider_fav_content_{{ $provider->id }}" class="like-content">
                                                                <span class="icon-heart-empty icon-unlike icon"></span>
                                                                <span class="icon-heart-full icon-like icon"></span>
                                                            </div>
                                                            <div id="provider_fav_loading_{{ $provider->id }}" class="loading-content">
                                                                <img class="img-fluid" src="{{ asset('assets/img/icons/ripple.svg') }}" />
                                                            </div>
                                                        </button>
                                                        <span id="provider_fav_count_{{ $provider->id }}" class="likes-count">{{ $provider->likes_count }}</span>
                                                    </div>
                                                    <div class="pro-user-view">
                                                        <img class="img-fluid" src="{{ asset($provider->user_data->avatar) }}" alt="" />
                                                    </div>
                                                    <div class="pro-name title mb-2 text-capitalize max-1-line">{{ $provider->name }}</div>
                                                    <div class="rating-container mb-2 justify-content-center">
                                                        @for ($i = 0 ;$i < (int) $provider->rate; $i++)
                                                        <span class="icon-star-empty icon gold"></span>
                                                        @endfor

                                                        @for ($i = 5 - (int) $provider->rate ;$i > 0; $i--)
                                                        <span class="icon-star-empty icon"></span>
                                                        @endfor
                                                        <span class="rating-count">({{$provider->rate}})</span>
                                                    </div>
                                                    <div class="pro-date">based on {{$provider->customer_review_number}} ratings</div>
                                                    <div class="pro-type mb-4">{{$provider->user_sub_categories_text}}</div>
                                                    <a href="{{ route('provider.profile', $provider->id) }}" class="btn btn-gray">View Provider</a>
                                                </div>
                                            @endforeach
                                            <!-- Row -->

                                        </div>

                                        <!-- Pagination -->
                                        @if ($providers->hasPages())
                                            <div class="profile-pagination main-pagination mt-5 pb-2">
                                                <div aria-label="Page navigation">
                                                    {{ $providers->links('pagination::bootstrap-4') }}
                                                </div>
                                            </div>
                                        @endif
                                        <!-- Pagination -->
                                    @else
                                        <div class="empty-data-container">
                                            <div class="text-center">
                                                <img class="empty-data-img img-fluid" src="{{ asset('assets/img/icons/empty-data.svg') }}" />
                                                <div class="empty-data-title text-center">There are no providers yet</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!-------- Providers -------->
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
