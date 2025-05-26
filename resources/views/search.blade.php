@extends('layouts.app')
@section('title', 'Search')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/search.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-with-top">
        <div class="search-container">
            <div class="container">
                <div class="search-wrapper">
                    <div class="search-flexable">

                        <!-- Search Sidebar -->
                        <div id="searchBackdrop" class="search-backdrop"></div>
                        <div id="searchSidebar" class="search-sidebar">
                            <div class="search-sidebar-block">
                                <!-- Appear In Responsive Only -->
                                <div class="search-cos-title">
                                    <div class="sct-flexable flex-between-vh">
                                        <div class="ns-title cos-title"><span>Filter</span></div>
                                        <button id="closeSearchSidebar" class="btn"><span class="icon-cancel icon"></span></button>
                                    </div>
                                </div>
                                <!-- Accordion -->
                                <div class="search-filter-col">
                                    <div class="ssb-title title mb-3">Categories</div>
                                    <div class="search-accordion">
                                        <div class="sidebar-accordion">
                                            <div class="accordion" id="accordionSearchFilter">
                                                @foreach($categories as $category)
                                                    <!-- Accordion Row -->
                                                    <div class="card">
                                                        <div class="card-header accordion-card-header" id="headingOne">
                                                            <button class="toggle-accordion-btn flex-center" type="button" data-toggle="collapse"
                                                                    data-target="#{{ $category->category_slug }}" aria-expanded="true" aria-controls="collapseOne">
                                                                <span class="icon-plus icon"></span>
                                                                <div class="title text-capitalize">{{ $category->category_name }}</div>
                                                            </button>
                                                        </div>

                                                        <div id="{{ $category->category_slug }}" class="collapse" aria-labelledby="headingOne">
                                                            <!-- data-parent="#accordionSearchFilter" to remove collapse -->
                                                            <div class="card-body">
                                                                <div class="card-body-tabs">
                                                                    <button id="data{{ $category->category_slug }}" onclick="selectAllCategory('{{ $category->category_slug }}')" class="btn btn-simple accordion-tab-btn all-tab-btn">All</button>
                                                                    @foreach($category->subCategoriesActive as $item)
                                                                        <button id="{{ $item->sub_category_slug }}" onclick="addCategory('{{ $category->category_slug }}','{{ $item->sub_category_slug }}')" class="btn btn-simple accordion-tab-btn text-capitalize {{ $category->category_slug }}">{{ $item->sub_category_name }}</button>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Accordion -->

                                <!-- Search Country -->
{{--                                <div class="search-selectbox mb-3">--}}
{{--                                    <div class="ssb-title title mb-2">City</div>--}}
{{--                                    <select id="citySearch">--}}
{{--                                        <option value="value_1">Dublin</option>--}}
{{--                                        <option value="value_2">Value 2</option>--}}
{{--                                        <option value="value_3">Value 3</option>--}}
{{--                                        <option value="value_4">Value 4</option>--}}
{{--                                        <option value="value_5">Value 5</option>--}}
{{--                                        <option value="value_6">Value 6</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
                                <div class="search-country-sidebar">
                                    <div class="ld-input-group ld-location-group d-flex align-items-center">
                                        <span class="icon-location icon"></span>
                                        <input type="search" placeholder="City" name="c" value="{{ $c }}"/>
                                    </div>
                                </div>
                                <!-- Search Country -->

                                <!-- Search Range Slider -->
                                <div class="search-filter-col">
                                    <div class="search-range-slider">
                                        <div class="ssb-title title mb-3">Price Range</div>
                                        <div class="filter-price-range">
                                            <div class="srs-counter flex-center-v">
                                                <div class="mfgd-input flex-center-v mr-2">$<div id="mfgdInputValPrice-1" class="mfgd-input-val"></div></div>
                                                <span> - </span>
                                                <div class="mfgd-input flex-center-v ml-2"> $<div id="mfgdInputValPrice-2" class="mfgd-input-val"></div></div>
                                            </div>
                                            <input type="text" dir="rtl" data-drag-interval="false" class="js-range-slider-price" name="my_range"
                                                   value="" data-type="double" data-min="0" data-max="{{$max_price}}" data-from="{{ request()->query('price_start') }}" data-to="{{ request()->query('price_end')}}"
                                                   data-grid="false" data-step="1" />
                                        </div>
                                    </div>
                                </div>
                                <!-- Search Range Slider -->

                                <!-- Search Rate -->
                                <div class="search-filter-col">
                                    <div class="search-rate">
                                        <div class="ssb-title title mb-3">Average Rating . <button id="resetRating" type="btn" class="btn btn-reset">reset</button></div>

                                        <!-- Rate Row -->
                                        <div class="search-rate-row">
                                            <div class="checkAll checkbox-group flex-center-vh">
                                                <div class="fake-checkbox">
                                                    <input id="rate5" value="5" type="radio" name="rating" class="input-rating input-checkbox" {{ request()->query('rating') == 5 ? 'checked' : '' }} />
                                                    <div class="fake-cb-child">
                                                        <span></span>
                                                    </div>
                                                </div>
                                                <label class="checkbox-text" for="rate5">
                                                    <div class="rating-container">
                                                        <span class="icon-star-empty icon gold"></span>
                                                        <span class="icon-star-empty icon gold"></span>
                                                        <span class="icon-star-empty icon gold"></span>
                                                        <span class="icon-star-empty icon gold"></span>
                                                        <span class="icon-star-empty icon gold"></span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Rate Row -->

                                        <!-- Rate Row -->
                                        <div class="search-rate-row">
                                            <div class="checkAll checkbox-group flex-center-vh">
                                                <div class="fake-checkbox">
                                                    <input id="rate4" value="4" type="radio" name="rating" class="input-rating input-checkbox" {{ request()->query('rating') == 4 ? 'checked' : '' }} />
                                                    <div class="fake-cb-child">
                                                        <span></span>
                                                    </div>
                                                </div>
                                                <label class="checkbox-text" for="rate4">
                                                    <div class="rating-container">
                                                        <span class="icon-star-empty icon gold"></span>
                                                        <span class="icon-star-empty icon gold"></span>
                                                        <span class="icon-star-empty icon gold"></span>
                                                        <span class="icon-star-empty icon gold"></span>
                                                        <span class="icon-star-empty icon"></span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Rate Row -->

                                        <!-- Rate Row -->
                                        <div class="search-rate-row">
                                            <div class="checkAll checkbox-group flex-center-vh">
                                                <div class="fake-checkbox">
                                                    <input id="rate3" value="3" type="radio" name="rating" class="input-rating input-checkbox" {{ request()->query('rating') == 3 ? 'checked' : '' }} />
                                                    <div class="fake-cb-child">
                                                        <span></span>
                                                    </div>
                                                </div>
                                                <label class="checkbox-text" for="rate3">
                                                    <div class="rating-container">
                                                        <span class="icon-star-empty icon gold"></span>
                                                        <span class="icon-star-empty icon gold"></span>
                                                        <span class="icon-star-empty icon gold"></span>
                                                        <span class="icon-star-empty icon"></span>
                                                        <span class="icon-star-empty icon"></span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Rate Row -->

                                        <!-- Rate Row -->
                                        <div class="search-rate-row">
                                            <div class="checkAll checkbox-group flex-center-vh">
                                                <div class="fake-checkbox">
                                                    <input id="rate2" value="2" type="radio" name="rating" class="input-rating input-checkbox" {{ request()->query('rating') == 2 ? 'checked' : '' }} />
                                                    <div class="fake-cb-child">
                                                        <span></span>
                                                    </div>
                                                </div>
                                                <label class="checkbox-text" for="rate2">
                                                    <div class="rating-container">
                                                        <span class="icon-star-empty icon gold"></span>
                                                        <span class="icon-star-empty icon gold"></span>
                                                        <span class="icon-star-empty icon"></span>
                                                        <span class="icon-star-empty icon"></span>
                                                        <span class="icon-star-empty icon"></span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Rate Row -->

                                        <!-- Rate Row -->
                                        <div class="search-rate-row">
                                            <div class="checkAll checkbox-group flex-center-vh">
                                                <div class="fake-checkbox">
                                                    <input id="rate1" value="1" type="radio" name="rating" class="input-rating input-checkbox" {{ request()->query('rating') == 1 ? 'checked' : '' }} />
                                                    <div class="fake-cb-child">
                                                        <span></span>
                                                    </div>
                                                </div>
                                                <label class="checkbox-text" for="rate1">
                                                    <div class="rating-container">
                                                        <span class="icon-star-empty icon gold"></span>
                                                        <span class="icon-star-empty icon"></span>
                                                        <span class="icon-star-empty icon"></span>
                                                        <span class="icon-star-empty icon"></span>
                                                        <span class="icon-star-empty icon"></span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Rate Row -->
                                    </div>
                                </div>
                                <!-- Search Rate -->

                                <!-- Search Action -->
                                <div class="search-action">
                                    <form id="search_form" action="{{ route('search') }}" method="get">
                                        <input id="category-values" type="hidden" name="category" value="{{ request()->query('category') }}">
                                        <input id="price-start-values" type="hidden" name="price_start">
                                        <input id="price-end-values" type="hidden" name="price_end">
                                        <input id="text-values" type="hidden" name="q" value="{{ request()->query('q') }}">
                                        <input id="rating-values" type="hidden" name="rating" value="{{ request()->query('rating') }}">
                                        <input id="order-value" type="hidden" name="order_search" value="ASC">
                                        <button  class="button-default width-fluid">Search</button>
                                    </form>
                                </div>
                                {{--                            <div class="search-action">--}}
                                {{--                                <button id="clearAll" class="button-default width-fluid">Reset</button>--}}
                                {{--                            </div>--}}
                                <!-- Search Action -->
                            </div>
                        </div>

                        <!-- Search Content -->
                        <div class="search-content">
                        <form action="{{ route('search') }}" method="get">
                                <!-- Bar -->
                                <div class="search-content-bar d-inline-block mb-4">
                                <div class="scb-flexable flex-center">
                                    <div class="ld-input-group ld-search-group d-flex align-items-center">
                                        <span class="icon-search icon"></span>
                                        <input type="search" onkeyup="updateQuery()" id="internal-search" placeholder="Gardening" name="q" value="{{ $q }}"/>
                                    </div>

                                        <div class="main-location-search ld-input-group ld-location-group d-flex align-items-center">
                                            <span class="icon-location icon"></span>
                                            <input type="search" placeholder="City" name="c" value="{{ $c }}"/>
                                        </div>

                                    <div class="search-action flex-center-v">
                                        <button id="filterBtn" type="button" class="btn btn-filter"><span class="icon-filter icon"></span></button>
                                        <button id="search_icon" class="btn btn-bg sb-action"><span class="icon-search icon"></span></button>
                                    </div>
                                </div>
                            </div>
                            <!-- Bar -->
                        </form>

                            <!-- Tabs -->
                            <div class="search-tabs mt-2 mb-4">
                                <button id="allTabBtn" class="btn st-btn">All</button>
                                <button id="servicesTabBtn" class="btn st-btn active">Services</button>
                                <button id="tasksTabBtn" class="btn st-btn">Tasks</button>
{{--                                <button id="serviceProviderTabBtn" class="btn st-btn">Service Provider</button>--}}
                            </div>
                            <!-- Tabs -->

                            <!-- Results Of Search -->
                            <div class="search-find-order flex-between-vh mb-4">

                                <div data-tab-target="#allTabBtn" class="sfs-counter counter-tab-container"><span class="mr-1">{{ count($tasks) + count($service) }}</span> All Service & Tasks Near you </div>

                                <div data-tab-target="#servicesTabBtn" class="sfs-counter counter-tab-container active"><span class="mr-1">{{ count($service) }}</span> Services Near you </div>

                                <div data-tab-target="#tasksTabBtn" class="sfs-counter counter-tab-container"><span class="mr-1">{{ count($tasks) }}</span> Tasks Near you </div>

                                <div class="sfs-order">
                                    <select id="orderSearch">
                                        <option value="ASC"  {{($order == "ASC") ?  "selected" : "" }}>  LOWEST</option>
                                        <option value="DESC" {{($order == "DESC") ? "selected" : "" }}> HIGHEST</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Results Of Search -->

                            <!-- Products -->
                            <div class="search-produts-container">

                                @if(empty($service) &&  empty($tasks))
                                    <div data-tab-target="#allTabBtn" class="spc-tab-container spc-tab-all">
                                        <div class="empty-data-container">
                                            <div class="text-center">
                                                <img class="empty-data-img img-fluid" src="{{ asset('assets/img/icons/empty-data.svg') }}" />
                                                <div class="empty-data-title text-center">There are no services and tasks results</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Services Tab Container -->
                                <div data-tab-target="#servicesTabBtn" data-count="{{ count($service) }}" class="spc-tab-container spc-tab-services active">
                                    @if(empty($service))
                                        <div class="empty-data-container">
                                            <div class="text-center">
                                                <img class="empty-data-img img-fluid" src="{{ asset('assets/img/icons/empty-data.svg') }}" />
                                                <div class="empty-data-title text-center">There are no services results</div>
                                            </div>
                                        </div>
                                    @else
                                        @foreach($service as $item)
                                            <!-- Product Row -->
                                            <div class="search-product-row">
                                                <div class="card-type card-type-service">Service</div>
                                                <div class="likes-container">
                                                    <button {{ !Auth::check() ? "data-toggle=modal data-target=#loginDialog data-toggle=tooltip data-placement=top" : "" }} id="service_fav_btn_{{ $item['id'] }}" class="ns-like {{ Auth::check() ? 'favorite-btn' : '' }} {{ $item['is_like'] ? 'active' : '' }}" data-id="{{ $item['id'] }}" data-type="service">
                                                        <div id="service_fav_content_{{ $item['id'] }}" class="like-content">
                                                            <span class="icon-heart-empty icon-unlike icon"></span>
                                                            <span class="icon-heart-full icon-like icon"></span>
                                                        </div>
                                                        <div id="service_fav_loading_{{ $item['id'] }}" class="loading-content">
                                                            <img class="img-fluid" src="{{ asset('assets/img/icons/ripple.svg') }}" />
                                                        </div>
                                                    </button>
                                                    <span id="service_fav_count_{{ $item['id'] }}" class="likes-count">{{ $item['likes_count'] }}</span>
                                                </div>
                                                <div class="sp-view">
                                                    <div class="swiper sp-swiper">
                                                        <!-- Additional required wrapper -->
                                                        <div class="swiper-wrapper">
                                                            <!-- Slides -->
                                                            @if (count($item['images']) > 0)
                                                                @foreach($item['images'] as $image)
                                                                    <div class="swiper-slide sp-slide">
                                                                        <img class="img-fluid sp-slide-view" src="{{ $image->image_path }}" alt="" />
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="swiper-slide sp-slide">
                                                                    <img class="img-fluid sp-slide-view" src="{{ asset('assets/img/default/default_no_image.jpg') }}" alt="" />
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <!-- If we need pagination -->
                                                        <div class="sp-pagination flex-center-vh"></div>
                                                    </div>
                                                </div>

                                                <div class="sp-details">
                                                    <div class="sp-type mb-1 text-capitalize">{{ $item['category']}}</div>
                                                    <div class="title mb-2 text-capitalize lines-1">{{ $item['service_name'] }}</div>
                                                    <div class="sp-description text mb-2 lines-2">{{ $item['service_description'] }}</div>
                                                    <div class="rating-container mb-3">
                                                        @if($item['average'] != 0)
                                                            @for ($i = 0 ;$i < (int) $item['average']; $i++)
                                                                <span class="icon-star-empty icon gold"></span>
                                                            @endfor

                                                            @for ($i = 5 - (int) $item['average'] ;$i > 0; $i--)
                                                                    <span class="icon-star-empty icon"></span>
                                                            @endfor
                                                            <span class="rating-count">({{$item['average']}})</span>

                                                        @else
                                                            <span class="icon-star-empty icon"></span>
                                                            <span class="icon-star-empty icon"></span>
                                                            <span class="icon-star-empty icon"></span>
                                                            <span class="icon-star-empty icon"></span>
                                                            <span class="icon-star-empty icon"></span>
                                                            <span class="rating-count">(0)</span>
                                                        @endif
                                                    </div>
                                                    <div class="sp-price">Start From {{$configurations['value']}}{{ $item['starting_price'] }} ~ {{$configurations['value']}}{{ $item['ending_price'] }}</div>
                                                    <div class="sp-location flex-center-v">
                                                        <span class="icon-location icon mr-1"></span>
                                                        <span id="service_{{ $item['id'] }}" class="kilometers-distance" data-selector="#service_{{ $item['id'] }}" data-lat="{{ $item['location_lat'] }}" data-lng="{{ $item['location_lng'] }}"></span> Away
                                                    </div>

                                                    <div class="sp-user-product flex-between-vh mt-4">
                                                        <a href="{{ route('provider.profile', $item['user_id']) }}" class="sp-view-user d-flex align-items-center">
                                                            <img class="img-fluid" src="{{ asset($item['user_avatar']) }}" alt="" />
                                                            <div class="sp-user-details">
                                                                <div class="sp-title title text-capitalize lines-1">{{ $item['user'] }}</div>
                                                                <div class="sp-text text d-flex align-items-center">View Profile <span class="icon-arrow-right icon"></span></div>
                                                            </div>
                                                        </a>

                                                        <a href="{{ route('service.view', $item['service_slug']) }}" class="btn btn-gray">View Details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <!-- Services Tab Container -->

                                <!-- Tasks Tab Container -->
                                <div data-tab-target="#tasksTabBtn" data-count="{{ count($tasks) }}" class="spc-tab-container spc-tab-tasks">
                                    @if(empty($tasks))
                                        <div class="empty-data-container">
                                            <div class="text-center">
                                                <img class="empty-data-img img-fluid" src="{{ asset('assets/img/icons/empty-data.svg') }}" />
                                                <div class="empty-data-title text-center">There are no tasks results</div>
                                            </div>
                                        </div>
                                    @else
                                        @foreach($tasks as $item)
                                            <!-- Product Row -->
                                            <div class="search-product-row">
                                                <div class="card-type card-type-task">Task</div>
                                                <div class="likes-container">
                                                    <button {{ !Auth::check() ? "data-toggle=modal data-target=#loginDialog data-toggle=tooltip data-placement=top" : "" }} id="task_fav_btn_{{ $item['id'] }}" class="ns-like {{ Auth::check() ? 'favorite-btn' : '' }} {{ $item['is_like'] ? 'active' : '' }}" data-id="{{ $item['id'] }}" data-type="task">
                                                        <div id="task_fav_content_{{ $item['id'] }}" class="like-content">
                                                            <span class="icon-heart-empty icon-unlike icon"></span>
                                                            <span class="icon-heart-full icon-like icon"></span>
                                                        </div>
                                                        <div id="task_fav_loading_{{ $item['id'] }}" class="loading-content">
                                                            <img class="img-fluid" src="{{ asset('assets/img/icons/ripple.svg') }}" />
                                                        </div>
                                                    </button>
                                                    <span id="task_fav_count_{{ $item['id'] }}" class="likes-count">{{ $item['likes_count'] }}</span>
                                                </div>
                                                <div class="sp-view">
                                                    <div class="swiper sp-swiper">
                                                        <!-- Additional required wrapper -->
                                                        <div class="swiper-wrapper">
                                                            <!-- Slides -->
                                                            @if (count($item['images']) > 0)
                                                                @foreach($item['images'] as $image)
                                                                    <div class="swiper-slide sp-slide">
                                                                        <img class="img-fluid sp-slide-view" src="{{ $image->image_path }}" alt="" />
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="swiper-slide sp-slide">
                                                                    <img class="img-fluid sp-slide-view" src="{{ asset('assets/img/default/default_no_image.jpg') }}" alt="" />
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <!-- If we need pagination -->
                                                        <div class="sp-pagination flex-center-vh"></div>
                                                    </div>
                                                </div>

                                                <div class="sp-details">
                                                    <div class="sp-type mb-1 text-capitalize">{{ $item['category'] }}</div>
                                                    <div class="title mb-2 text-capitalize lines-1">{{ $item['task_name'] }}</div>
                                                    <div class="sp-description text mb-2 lines-2">{{ $item['task_description'] }}</div>
                                                    <div class="rating-container mb-3">
                                                        @if($item['average'] != 0)
                                                            @for ($i = 0 ;$i < (int) $item['average']; $i++)
                                                                <span class="icon-star-empty icon gold"></span>
                                                            @endfor

                                                            @for ($i = 5 - (int) $item['average'] ;$i > 0; $i--)
                                                                    <span class="icon-star-empty icon"></span>
                                                            @endfor
                                                            <span class="rating-count">({{$item['average']}})</span>

                                                        @else
                                                            <span class="icon-star-empty icon"></span>
                                                            <span class="icon-star-empty icon"></span>
                                                            <span class="icon-star-empty icon"></span>
                                                            <span class="icon-star-empty icon"></span>
                                                            <span class="icon-star-empty icon"></span>
                                                            <span class="rating-count">(0)</span>
                                                        @endif
                                                    </div>
                                                    <div class="sp-price">Start From {{$configurations['value']}}{{ $item['starting_price'] }} ~ {{$configurations['value']}}{{ $item['ending_price'] }}</div>
                                                    <div class="sp-location flex-center-v">
                                                        <span class="icon-location icon mr-1"></span>
                                                        <span id="task_{{ $item['id'] }}" class="kilometers-distance" data-selector="#task_{{ $item['id'] }}" data-lat="{{ $item['location_lat'] }}" data-lng="{{ $item['location_lng'] }}"></span> Away
                                                    </div>

                                                    <div class="sp-user-product flex-between-vh mt-4">
                                                        <a href="{{ route('provider.profile', $item['user_id']) }}" class="sp-view-user d-flex align-items-center">
                                                            <img class="img-fluid" src="{{ asset($item['user_avatar']) }}" alt="" />
                                                            <div class="sp-user-details">
                                                                <div class="sp-title title text-capitalize lines-1">{{ $item['user'] }}</div>
                                                                <div class="sp-text text d-flex align-items-center">View Profile <span class="icon-arrow-right icon"></span></div>
                                                            </div>
                                                        </a>

                                                        <a href="{{ route('task.view', $item['task_slug']) }}" class="btn btn-gray">View Details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <!-- Tasks Tab Container -->

                            </div>
                            <!-- Products -->

                            <!-- Pagination -->
{{--                            <div class="search-pagination">--}}
{{--                                <div aria-label="Page navigation example">--}}
{{--                                    <ul class="pagination flex-center-vh">--}}
{{--                                        <li class="page-item disabled">--}}
{{--                                            <a class="page-link"><span class="icon-arrow-left icon"></span></a>--}}
{{--                                        </li>--}}
{{--                                        <li class="page-item"><a class="page-link active" href="#">1</a></li>--}}
{{--                                        <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
{{--                                        <li class="page-item"><a class="page-link" href="#">3</a></li>--}}
{{--                                        <li class="page-item"><a class="page-link" href="#">4</a></li>--}}
{{--                                        <li class="page-item"><a class="page-link" href="#">5</a></li>--}}
{{--                                        <li class="page-item"><a class="page-link" href="#">...</a></li>--}}
{{--                                        <li class="page-item"><a class="page-link" href="#">15</a></li>--}}
{{--                                        <li class="page-item">--}}
{{--                                            <a class="page-link"><span class="icon-arrow-right icon"></span></a>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <!-- Pagination -->

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        // Select City
        $(function () {
            new SlimSelect({
                select: '#citySearch',
                showSearch: false,
                showContent: 'down'
            })
        });

        // Select Order
        $(function () {
            new SlimSelect({
                select: '#orderSearch',
                showSearch: false,
                showContent: 'down'
            })
        });

        $(function () {
            $(".st-btn").on('click', function () {
                var id = $(this).attr("id");
                $(".spc-tab-container").removeClass('active');
                $(".counter-tab-container").removeClass('active');
                $(`div[data-tab-target="#${id}"]`).addClass("active");
                $(this).addClass('active').siblings().removeClass("active");
                if (id === 'allTabBtn') {
                    $(".spc-tab-container.spc-tab-all").addClass('active all-active');
                    let servicesTab = $(".spc-tab-services")
                    let tasksTab = $(".spc-tab-tasks")

                    if (servicesTab.data('count') > 0) {
                        servicesTab.addClass('active all-active')
                    }

                    if (tasksTab.data('count') > 0) {
                        tasksTab.addClass('active all-active')
                    }

                } else {
                    $(".spc-tab-container").removeClass('all-active');
                }
            });
            $(function () {
                const swiper = new Swiper('.sp-swiper', {
                    // Optional parameters
                    direction: 'horizontal',
                    loop: false,
                    spaceBetween: 15,
                    // If we need pagination
                    pagination: {
                        el: '.sp-pagination',
                        clickable: true
                    },
                });
            });
        });

        // Reset Search
        $("#clearAll").on("click", function () {
            let rangePrice = $(".js-range-slider-price").data("ionRangeSlider");
            rangePrice.reset();
            $("#modalCheckboxContainer").slideUp(300);
            $(".multiple-checkbox-container").slideUp(300);
            $(".input-checkbox").prop("checked", false);
            $("#citySearch option:first-of-type").attr('selected','selected');
            var val = $("#citySearch option:first-of-type").text();
            $(".ss-single-selected .placeholder").text(val);
            $(".ss-option").removeClass("ss-disabled ss-option-selected");
            $(".ss-option:first-of-type").addClass("ss-disabled ss-option-selected");
            $('.collapse').collapse('hide');
        });

        // Toggle Search Sidebar
        $("#filterBtn").on('click', function () {
            $("#searchSidebar").addClass("active");
            $("#searchBackdrop").addClass("active");
        });

        $("#closeSearchSidebar, #searchBackdrop").on('click', function () {
            $("#searchSidebar").removeClass("active");
            $("#searchBackdrop").removeClass("active");
        });
    </script>
    <script>
        let categories_list = [];
        function addCategory(parent_slug, category_slug, all) {
            let categories_string = ""
            $('#data'+parent_slug).removeClass("active");
            const element = $('#'+category_slug);

            if (element.hasClass("active")){
                categories_list.splice(categories_list.indexOf(category_slug), 1);
                categories_list.forEach(value => {
                    categories_string += value + "|"
                });
                categories_string = categories_string.slice(0,-1);
                $('#category-values').val(categories_string);
                element.removeClass("active");
                // $('.'+parent_slug).removeClass("active-all");
            } else {
                categories_list.push(category_slug);
                categories_list.forEach(value => {
                    categories_string += value + "|"
                });
                categories_string = categories_string.slice(0,-1);
                $('#category-values').val(categories_string);
                element.addClass("active");
            }
        }
        function selectAllCategory(selector){
            $('.'+selector).each(function(i, obj) {
                addCategory(selector, obj.id, 'all');
            });
            $('.'+selector).removeClass("active");
            $('#data'+selector).addClass("active");
            $('.' + parent_slug).addClass('active');
        }
        $('#category-values').val().split('|').forEach(val =>{
            $('#'+val).addClass("active");
        });
        function updateQuery(){
            let query= $('#internal-search').val();
            $('#text-values').val(query);
        }
        categories_list = $('#category-values').val().split('|');
    </script>
    <script>
        // submit search form
        $("#search_icon").on('click', function () {
            $("#search_form").submit();
        });
        $(".input-rating").on('change', function () {
            $("#rating-values").val($(this).val());
        });
        $(function () {
            $('.input-rating').each((index, value) => {
                if (value.checked) {
                    $("#rating-values").val(value.value)
                }
            })
        });

        $("#resetRating").on('click', function () {
            $("#rating-values").val("");
            $(".input-rating").attr('checked', false);
        });

        $(function () {
            $('#orderSearch').on('change', function (e) {
                $("#order-value").val(e.target.value);
            });
        });
    </script>
@endsection
