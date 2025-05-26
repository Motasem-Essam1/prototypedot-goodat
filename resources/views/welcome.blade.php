@extends('layouts.app')
@section('title', 'Home')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-with-top">
        <div class="landing-page-container">
            <div class="container">
                <div class="ld-header-bg">
                    <div class="ld-header text-center">
                        <div class="ld-title"><b class="white-color sub-ld-title">Deft At</b> Your Hand</div>
                        <div class="ld-text">
{{--                            All the services you need in just <span>one place!</span>--}}
                            Became a Service Provider at <span>Deft At</span> and list your <br />
                            service now and get more clients!
                        </div>
                        <form action="{{ route('search') }}" method="get">
                            <div class="ld-search-service">
                                <div id="searchGroup" class="ld-input-group ld-search-group d-flex align-items-center">
                                    <span class="icon-search icon"></span>
                                    <input id="searchInput" type="search" placeholder="Search for service" name="q" autocomplete="false" />
                                    <div class="search-dropdown-loading mr-2">
                                        <div class="d-flex align-items-center">
                                            <img class="img-fluid search-loading" src="{{ asset('assets/img/icons/rolling.svg') }}" />
                                        </div>
                                    </div>
                                    <button id="cancelSearch" class="cancel-search flex-center-center" type="button"><span class="icon-cancel icon"></span></button>
                                    <div id="searchDropdown" class="main-search-dropdown">
                                        <div id= "search-items" class="search-dropdown-content scroller"></div>
                                        <div class="search-results text-left">Search results <span id="searchResult"></span></div>
                                        <div class="search-show-all"><button type="submit" class="show-all-link d-inline-block">Show All</button></div>
                                    </div>
                                </div>

                                <div class="ld-input-group d-flex align-items-center">
                                    <span class="icon-location icon"></span>
                                    <input type="search" placeholder="City" name="c" />
                                </div>

                                <button type="submit" class="btn btn-bg ld-action"><span class="icon-search icon mr-2"></span> Search</button>
                            </div>
                            <ul class="p-0 m-0" style="list-style: none;">
                                @foreach($errors->all() as $error)
                                    <li style="color: Red; font-size:20px;">{{$error}}</li>
                                    </br>
                                @endforeach
                            </ul>
                        </form>
                        <div class="ld-notes text-left">Popular: House Cleaning, Gardening, Plumbing, Electric Work.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nearby Services -->
        <div class="nearby-services-container">
            <div class="container">
                <div class="nearby-services">
                    <div class="ns-title cos-title"><span>Nearby Services</span></div>
                    <div class="tabs-container">
                        <div class="parent-main-categories tabs-titles">
                            @foreach($categories as $category)
                                <button onclick="getAllService({{ $category->id }}, 'category')" id="nearby-{{ $category->category_slug }}" class="tt-btn text-capitalize @if($loop->first) active @endif">{{ $category->category_name }}</button>
                            @endforeach
                        </div>
                        <div class="children-sub-categories sub-tabs-wrapper">
                            @foreach($categories as $category)
                                <div class="child-sub-category sub-tab-container @if($loop->first) active @endif" data-tab-target="#nearby-{{ $category->category_slug }}">
                                    <button onclick="getAllService({{ $category->id }}, 'category')" class="stc-btn text-capitalize active">All</button>
                                    @foreach($category->subCategoriesActive as $subCategory)
                                        <button onclick="getAllService({{ $subCategory->id }}, 'sub-category')" class="stc-btn text-capitalize">{{ $subCategory->sub_category_name }}</button>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="nearby-services-tabs-slider tabs-slider-container">
                            <!-- Loading -->
                            <div id="nearby-services-loading" class="slider-loading">
                                <div class="loader-container">
                                    <img src="{{ asset('assets/img/icons/ripple.svg') }}" />
                                </div>
                            </div>
                            <!-- Loading -->

                            <!-- Empty -->
                            <div id="nearby-services-empty" class="slider-empty">
                                <div class="empty-data-container">
                                    <div class="text-center">
                                        <img class="empty-data-img img-fluid" src="{{ asset('assets/img/icons/empty-data.svg') }}" />
                                        <div class="empty-data-title text-center">There are no services yet</div>
                                    </div>
                                </div>
                            </div>
                            <!-- Empty -->

                            <div class="nearby-services-slider">
                                <div class="slider-arrows-wrapper d-flex align-items-center justify-content-between">
                                    <button id="arrowNearbyPrev" class="slider-arrow-circle arrow-circle-prev"><span class="icon-arrow-left icon"></span></button>
                                    <button id="arrowNearbyNext" class="slider-arrow-circle arrow-circle-next"><span class="icon-arrow-right icon"></span></button>
                                </div>
                                <div class="swiper services-swiper-wrapper">
                                    <!-- Additional required wrapper -->
                                    <div id="swiper-slide-service" class="swiper-wrapper"><!-- Data Here --></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Nearby Services -->

        <!-- Tasks -->
        <div class="tasks-container">
            <div class="container">
                <div class="tasks-wrapper">
                    <div class="tasks-title cos-title"><span>Available Tasks Around</span></div>
                    <div class="tabs-container">
                        <div class="parent-main-categories tabs-titles">
                            @foreach($categories as $category)
                                <button onclick="getAllTasks({{ $category->id }}, 'category')" id="tasks-{{ $category->category_slug }}" class="tt-btn text-capitalize @if($loop->first) active @endif">{{ $category->category_name }}</button>
                            @endforeach
                        </div>
                        <div class="children-sub-categories sub-tabs-wrapper sub-tabs-tasks">
                            @foreach($categories as $category)
                                <div class="child-sub-category sub-tab-container @if($loop->first) active @endif" data-tab-target="#tasks-{{ $category->category_slug }}">
                                    <button onclick="getAllTasks({{ $category->id }}, 'category')" class="stc-btn text-capitalize active">All</button>
                                    @foreach($category->subCategoriesActive as $subCategory)
                                        <button onclick="getAllTasks({{ $subCategory->id }}, 'sub-category')" class="stc-btn text-capitalize">{{ $subCategory->sub_category_name }}</button>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="tasks-tabs-slider tabs-slider-container">
                            <!-- Loading -->
                            <div id="tasks-loading" class="slider-loading">
                                <div class="loader-container">
                                    <img src="{{ asset('assets/img/icons/ripple.svg') }}" />
                                </div>
                            </div>
                            <!-- Loading -->

                            <!-- Empty -->
                            <div id="tasks-empty" class="slider-empty">
                                <div class="empty-data-container">
                                    <div class="text-center">
                                        <img class="empty-data-img img-fluid" src="{{ asset('assets/img/icons/empty-data.svg') }}" />
                                        <div class="empty-data-title text-center">There are no tasks yet</div>
                                    </div>
                                </div>
                            </div>
                            <!-- Empty -->

                            <div class="tasks-slider">
                                <div class="slider-arrows-wrapper d-flex align-items-center justify-content-between">
                                    <button id="arrowTaskPrev" class="slider-arrow-circle arrow-circle-prev"><span class="icon-arrow-left icon"></span></button>
                                    <button id="arrowTaskNext" class="slider-arrow-circle arrow-circle-next"><span class="icon-arrow-right icon"></span></button>
                                </div>
                                <div class="swiper tasks-swiper-wrapper">
                                    <!-- Additional required wrapper -->
                                    <div id="swiper-slide-task" class="swiper-wrapper"><!-- Data Here --></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Tasks -->

        <!-- Why Using Deft @ -->
        <div class="features-container">
            <div class="features-bg">
                <div class="container">
                    <div class="features-wrapper">
                        <div class="feat-title cos-title"><span>Why Using<br /> Deft at?</span></div>

                        <!-- Features Cards -->
                        <div class="features-cards">
                            <div class="feat-card d-flex align-items-center">
                                <span class="icon-explore fc-icon"></span>
                                <div class="fc-details">
                                    <div class="fc-title title">Variety of service providers!</div>
                                    <div class="fc-text text">Explore a variety of providers</div>
                                </div>
                            </div>

                            <div class="feat-card d-flex align-items-center">
                                <span class="icon-dollar fc-icon"></span>
                                <div class="fc-details">
                                    <div class="fc-title title">FREE of Fees!</div>
                                    <div class="fc-text text">Describe Feature in a Sentence</div>
                                </div>
                            </div>

                            <div class="feat-card d-flex align-items-center">
                                <span class="icon-location-2 fc-icon"></span>
                                <div class="fc-details">
                                    <div class="fc-title title">Explore Nearby Services</div>
                                    <div class="fc-text text">Describe Feature in a Sentence</div>
                                </div>
                            </div>
                        </div>
                        <!-- Features Cards -->

                    </div>
                </div>
            </div>
        </div>
        <!-- Why Using Deft @ -->

        <!-- Services Ad -->
        <div class="ads-container">
            <div class="ads-bg">
                <div class="container">
                    <div class="ads-wrapper text-center">
                        <div class="ads-title">Are you DEFT AT Something?</div>
                        <div class="ads-text">Became a service provider at deft at<br /> and list your service now and get more clients!</div>
                        <div class="ads-action d-flex justify-content-center">
                            @if(Auth::check())
                                @if(Auth::user()->user_data->user_type == "Normal")
                                    <a href="{{ route('account.subscription') }}" class="btn btn-bg"> Became A Provider </a>
                                @endif
                            @else
                                <a href="{{ route('register') }}" class="btn btn-bg"> Became A Provider </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Services Ad -->

        <!-- Professionals -->
        <div class="pro-container">
            <div class="container">
                <div class="pro-wrapper">
                    <div class="pro-title cos-title"><span>Professionals Around You</span></div>
                    <div class="pro-tabs">
                        <div class="parent-main-categories tabs-titles">
                            @foreach($categories as $category)
                                <button onclick="getAllProviders({{ $category->id }}, 'category')" id="nearby-{{ $category->category_slug }}" class="tt-btn text-capitalize @if($loop->first) active @endif">{{ $category->category_name }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="pro-slider tabs-slider-container">
                        <!-- Loading -->
                        <div id="professionals-loading" class="slider-loading">
                            <div class="loader-container">
                                <img src="{{ asset('assets/img/icons/ripple.svg') }}" />
                            </div>
                        </div>
                        <!-- Loading -->

                        <!-- Empty -->
                        <div id="professionals-empty" class="slider-empty">
                            <div class="empty-data-container">
                                <div class="text-center">
                                    <img class="empty-data-img img-fluid" src="{{ asset('assets/img/icons/empty-data.svg') }}" />
                                    <div class="empty-data-title text-center">There are no providers yet</div>
                                </div>
                            </div>
                        </div>
                        <!-- Empty -->

                        <div class="professionals-slider">
                            <div class="slider-arrows-wrapper d-flex align-items-center justify-content-between">
                                <button id="arrowProPrev" class="slider-arrow-circle arrow-circle-prev"><span class="icon-arrow-left icon"></span></button>
                                <button id="arrowProNext" class="slider-arrow-circle arrow-circle-next"><span class="icon-arrow-right icon"></span></button>
                            </div>
                            <div class="swiper pro-swiper-wrapper">
                                <!-- Additional required wrapper -->
                                <div id="providersContainer" class="swiper-wrapper"><!-- Data Here --></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profeesionals -->

        <!-- Customers -->
        <div class="customers-container">
            <div class="container">
                <div class="customers-wrapper">
                    <div>
                        <div class="pro-title cos-title"><span>What Our<br /> Customers Says</span></div>
                    </div>
                    <div class="cust-slider">
                        <div class="swiper customers-swiper-wrapper">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">

                            @if(sizeof($customer_reviews) === 0)
                                <div class="empty-data-container">
                                    <div class="text-center">
                                        <img class="empty-data-img img-fluid" src="{{ asset('assets/img/icons/empty-reviews.svg') }}" />
                                        <div class="empty-data-title text-center">There are no reviews yet</div>
                                    </div>
                                </div>
                            @else
                                @foreach($customer_reviews as $customer_review)
                                    <!-- Slide Row -->
                                    <div class="swiper-slide customer-slide">
                                        <div class="review-card">
                                            <div class="customize-slide-top">
                                                <a href="{{route('provider.profile', $customer_review->user->id)}}">
                                                    <div class="review-user d-flex align-items-center">
                                                        <img class="img-fluid" src="{{ asset($customer_review->user->user_data->avatar)}}" alt="" />
                                                        <div class="review-user-name text-capitalize max-1-line">{{ $customer_review->user->name }}</div>
                                                    </div>
                                                </a>
                                                <div class="review-job-title title">{{ $customer_review['user_sub_categories_text']}}</div>
                                                <div class="review-text text lines-4">{{ $customer_review->description }}</div>
                                            </div>

                                            <div class="customize-slide-bottom">
                                                <div class="rating-container mb-2">
                                                    @for ($i = 0 ;$i < (int) $customer_review->rate; $i++)
                                                        <span class="icon-star-empty icon gold"></span>
                                                    @endfor

                                                    @for ($i = 5 - (int) $customer_review->rate ;$i > 0; $i--)
                                                        <span class="icon-star-empty icon"></span>
                                                    @endfor

                                                    <span class="rating-count">({{$customer_review->rate}})</span>
                                                </div>
                                                <a href="{{route('provider.profile', $customer_review->customer->id)}}" class="review-view-user d-flex align-items-center">
                                                    <img class="img-fluid" src="{{ asset($customer_review->customer->user_data->avatar) }}" alt="" />
                                                    <div class="rv-user-details">
                                                        <div class="rv-title title text-capitalize max-1-line">{{$customer_review->customer->name}}</div>
                                                        <div class="rv-text text d-flex align-items-center">Provider Details <span class="icon-arrow-right icon"></span></div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Slide Row -->
                                @endforeach
                            @endif

                            </div>
                        </div>

                        <!-- Arrows And Pagination -->
                        <div class="review-p-a d-flex align-items-center justify-content-between">
                            <div class="reviews-pagination"></div>
                            <div class="slider-arrows-wrapper reviews-slider-arrows d-flex align-items-center w-100 justify-content-end">
                                <button class="slider-arrow-circle review-arrow-prev"><span class="icon-arrow-left icon"></span></button>
                                <button class="slider-arrow-circle review-arrow-next"><span class="icon-arrow-right icon"></span></button>
                            </div>
                        </div>
                        <!-- Arrows And Pagination -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Customers -->

    </div>
@endsection


@section('custom-script')
    <script>
        function reloadNearbyServices() {
            new Swiper('.services-swiper-wrapper', {
                direction: 'horizontal',
                loop: false,
                // slidesPerView: 4,
                spaceBetween: 20,

                // Navigation arrows
                navigation: {
                    nextEl: '#arrowNearbyNext',
                    prevEl: '#arrowNearbyPrev',
                },
                breakpoints: {
                    // when window width is >= 320px
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 20
                    },
                    // when window width is >= 480px
                    480: {
                        slidesPerView: 1.3,
                        spaceBetween: 15
                    },
                    // when window width is >= 640px
                    576: {
                        slidesPerView: 1.6,
                        spaceBetween: 15
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 15
                    },
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 15
                    },
                    1330: {
                        slidesPerView: 3.3,
                        spaceBetween: 15
                    },
                    1440: {
                        slidesPerView: 4,
                        spaceBetween: 15
                    }
                }
            });
        }

        function reloadAroundTasks() {
            new Swiper('.tasks-swiper-wrapper', {
                direction: 'horizontal',
                loop: false,
                slidesPerView: 4,
                spaceBetween: 20,
                navigation: {
                    nextEl: '#arrowTaskNext',
                    prevEl: '#arrowTaskPrev',
                },
                breakpoints: {
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 20
                    },
                    480: {
                        slidesPerView: 1.3,
                        spaceBetween: 15
                    },
                    576: {
                        slidesPerView: 1.6,
                        spaceBetween: 15
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 15
                    },
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 15
                    },
                    1330: {
                        slidesPerView: 3.4,
                        spaceBetween: 15
                    },
                    1440: {
                        slidesPerView: 4,
                        spaceBetween: 15
                    }
                }
            });
        }

        function reloadProfessionals() {
            new Swiper('.pro-swiper-wrapper', {
                direction: 'horizontal',
                loop: false,
                slidesPerView: 4,
                spaceBetween: 20,
                navigation: {
                    nextEl: '#arrowProNext',
                    prevEl: '#arrowProPrev',
                },
                breakpoints: {
                    320: {
                        slidesPerView: 1.3,
                        spaceBetween: 20
                    },
                    480: {
                        slidesPerView: 1.6,
                        spaceBetween: 15
                    },
                    576: {
                        slidesPerView: 1.7,
                        spaceBetween: 15
                    },
                    768: {
                        slidesPerView: 2.4,
                        spaceBetween: 15
                    },
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 15
                    },
                    1330: {
                        slidesPerView: 3.5,
                        spaceBetween: 15
                    },
                    1440: {
                        slidesPerView: 4,
                        spaceBetween: 15
                    }
                }
            });
        }

        new Swiper('.customers-swiper-wrapper', {
            direction: 'horizontal',
            loop: false,
            slidesPerView: 2,
            spaceBetween: 20,
            pagination: {
                el: '.reviews-pagination',
                type: 'bullets',
                clickable: true
            },

            // Navigation arrows
            navigation: {
                nextEl: '.review-arrow-next',
                prevEl: '.review-arrow-prev',
            },
            breakpoints: {
                // when window width is >= 320px
                320: {
                    slidesPerView: 1,
                    spaceBetween: 20
                },
                // when window width is >= 480px
                480: {
                    slidesPerView: 1.3,
                    spaceBetween: 15
                },
                // when window width is >= 640px
                576: {
                    slidesPerView: 1.4,
                    spaceBetween: 15
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 15
                },
                992: {
                    slidesPerView: 2,
                    spaceBetween: 15
                }
            }
        });

    </script>

    <script>
        function getAllService(id, selector) {
            var latitude = 0;
            var longitude = 0;
            if (navigator.geolocation) {
                navigator.permissions.query({ name: "geolocation" }).then((result) => {
                    if (result.state === "granted") {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            latitude = position.coords.latitude;
                            longitude = position.coords.longitude;
                            callAjaxServices(id, selector, latitude, longitude);
                        });
                    }
                    else{
                        callAjaxServices(id, selector, latitude, longitude);
                    }
                });
            }
            else
            {
                callAjaxServices(id, selector, latitude, longitude);
            }
        }
        getAllService(1, 'category');

        function callAjaxServices(id, selector, latitude, longitude) {
            $("#swiper-slide-service").empty();
            let url = "";
            var i = 0; //for loop to get span stars
            var stars_string = '';
            var average = 0;
            var currency = {{Js::from($configurations['value'])}};

            if (selector === 'category') {
                url = '/service/all/'+id;
            } else {
                url = '/service/all/sub/'+id;
            }

            $.ajax({
                type: 'GET',
                url,
                data: {
                    latitude,
                    longitude,
                },
                beforeSend: function () {
                    $("#nearby-services-loading").addClass('active');
                    $(".nearby-services-slider").css('display', 'none');
                    $("#swiper-slide-service").empty();
                    $("#nearby-services-empty").removeClass('active');
                },
                accept: 'application/json',
                success: function (res) {
                    $("#nearby-services-loading").removeClass('active');
                    if (res.length) {
                        $("#nearby-services-empty").removeClass('active');
                        $(".nearby-services-slider").css('display', 'block');
                        let htmlData = ''
                        $.each(res, function(key, value) {
                            average = value.average;
                            stars_string = '';

                            for(i = 0; i < parseInt(average); i++){
                                stars_string += `<span class="icon-star-empty icon gold"></span>`;
                            }

                            for(i = 5- parseInt(average); i > 0; i--){
                                stars_string += `<span class="icon-star-empty icon"></span>`;
                            }

                            let service_image = null;
                            if(value.service_image) {
                                service_image = `<img class="img-fluid" src="${value.service_image}" alt="" />`;
                            }
                            else {
                                service_image = `<img class="img-fluid" src="{{ asset('assets/img/default/default_no_image.jpg') }}" alt="" />`;
                            }


                            htmlData += `
                            <div class="swiper-slide">
                                <div class="ns-slide">
                                    <div class="likes-container">
                                        <button type="button" {{ !Auth::check() ? "data-toggle=modal data-target=#loginDialog data-toggle=tooltip data-placement=top" : "" }}  title="{{ Auth::check() ? 'Like/UnLike' : "Please Login" }}" id="service_fav_btn_${value.id}" class="ns-like {{ Auth::check() ? 'favorite-btn' : '' }} ${value.is_like ? 'active' : ''}" data-id="${value.id}" data-type="service">
                                            <div id="service_fav_content_${value.id}" class="like-content">
                                                <span class="icon-heart-empty icon-unlike icon"></span>
                                                <span class="icon-heart-full icon-like icon"></span>
                                            </div>
                                            <div id="service_fav_loading_${value.id}" class="loading-content">
                                                <img class="img-fluid" src="{{ asset('assets/img/icons/ripple.svg') }}" />
                                            </div>
                                        </button>
                                        <span id="service_fav_count_${value.id}" class="likes-count">${value.likes_count}</span>
                                    </div>
                                    <a href="/service-provider/${value.user_id}" class="ns-user">
                                        <img src="${value.user_avatar}" class="img-fluid" alt="" />
                                        <div class="ns-user-name text-capitalize lines-1">${value.user}</div>
                                    </a>
                                    <div class="ns-user-view">
                                      <a href="/service-view/${value.service_slug}">${service_image}</a>
                                    </div>
                                    <div class="mb-2">
                                        <a href="/service-view/${value.service_slug}" class="title mb-2 text-capitalize lines-1">${value.service_name}</a>
                                    </div>
                                    <div class="ns-price">Start from {{$configurations['value']}}${value.starting_price}</div>
                                    <div class="rating-container">
                                    `
                                + stars_string +
                                `
                                    <span class="rating-count">(${value.average})</span>

                                    </div>
                                    <div class="ns-type text-capitalize">${value.category}</div>
                                </div>
                            </div>`;
                        });
                        $("#swiper-slide-service").html(htmlData)
                        reloadNearbyServices()
                    } else {
                        $("#nearby-services-empty").addClass('active');
                        $(".nearby-services-slider").css('display', 'none');
                    }
                }
            });
        }

    </script>

    <script>
        function getAllTasks(id,selector){
            var latitude = 0;
            var longitude =0;
            if (navigator.geolocation) {
                navigator.permissions.query({ name: "geolocation" }).then((result) => {
                    if (result.state === "granted") {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            latitude = position.coords.latitude;
                            longitude = position.coords.longitude;
                            callAjaxTasks(id, selector, latitude, longitude);
                        });
                    }
                    else {
                        callAjaxTasks(id, selector, latitude, longitude);
                    }

                });
            }
            else{
                callAjaxTasks(id, selector, latitude, longitude);
            }
        }
        getAllTasks(1, 'category')

        function callAjaxTasks(id, selector, latitude, longitude) {
            $("#swiper-slide-task").empty();
            let url = "";
            var i = 0; //for loop to get span stars
            var stars_string = '';
            var average = 0;


            if (selector === 'category'){
                url = '/task/all/'+id;
            }else {
                url = '/task/all/sub/'+id;
            }

            $.ajax({
                type: 'GET',
                url: url,
                beforeSend: function () {
                    $("#tasks-loading").addClass('active');
                    $(".tasks-slider").css('display', 'none');
                    $("#swiper-slide-task").empty();
                    $("#tasks-empty").removeClass('active');
                },
                data: {
                    latitude,
                    longitude,
                },
                accept: 'application/json',
                success: function (res) {
                    $("#tasks-loading").removeClass('active');
                    if (res.length) {
                        $("#tasks-empty").removeClass('active');
                        $(".tasks-slider").css('display', 'block');
                        let htmlData = ''
                        $.each(res, function(key, value) {
                            average = value.average;
                            stars_string = '';

                            for(i = 0; i <  parseInt(average); i++){
                                stars_string += `<span class="icon-star-empty icon gold"></span>`;
                            }

                            for(i = 5- parseInt(average); i > 0; i--){
                                stars_string += `<span class="icon-star-empty icon"></span>`;
                            }

                            let task_image = null;
                            if(value.task_image) {
                                task_image = `<img class="img-fluid" src="${value.task_image}" alt="" />`;
                            } else {
                                task_image = `<img class="img-fluid" src="{{ asset('assets/img/default/default_no_image.jpg') }}" alt="" />`;
                            }

                            htmlData += `
                                <div class="swiper-slide">
                                    <div>
                                        <div class="likes-container">
                                            <button type="button" {{ !Auth::check() ? "data-toggle=modal data-target=#loginDialog data-toggle=tooltip data-placement=top" : "" }}  title="{{ Auth::check() ? 'Like/UnLike' : "Please Login" }}" id="task_fav_btn_${value.id}" class="ns-like {{ Auth::check() ? 'favorite-btn' : '' }} ${value.is_like ? 'active' : ''}" data-id="${value.id}" data-type="task">
                                                <div id="task_fav_content_${value.id}" class="like-content">
                                                    <span class="icon-heart-empty icon-unlike icon"></span>
                                                    <span class="icon-heart-full icon-like icon"></span>
                                                </div>
                                                <div id="task_fav_loading_${value.id}" class="loading-content">
                                                    <img class="img-fluid" src="{{ asset('assets/img/icons/ripple.svg') }}" />
                                                </div>
                                            </button>
                                            <span id="task_fav_count_${value.id}" class="likes-count">${value.likes_count}</span>
                                        </div>
                                        <a href="/task-view/${value.task_slug}" class="tasks-slide d-block">
                                            <div class="tasks-user-view">
                                                ${task_image}
                                            </div>
                                            <div class="title mb-2 text-capitalize lines-1">${value.task_name}</div>
                                            <div class="tasks-price">From {{$configurations['value']}}${value.starting_price} ~ {{$configurations['value']}}${value.ending_price}</div>
                                            <div class="rating-container">
                                                `
                                + stars_string +
                                `
                                                <span class="rating-count">(${value.average})</span>
                                            </div>
                                            <div class="tasks-type text-capitalize">${value.category}</div>
                                        </a>
                                    </div>
                                </div>`;
                        });
                        $("#swiper-slide-task").html(htmlData);
                        reloadAroundTasks();
                    } else {
                        $("#tasks-empty").addClass('active');
                        $(".tasks-slider").css('display', 'none');
                    }
                }
            });

        }

    </script>

    <script>
        $("#searchInput").on("input", function(e) {
            if (e.target.value.length > 0) {
                $(".search-dropdown-loading").addClass('active');
            } else {
                $(".search-dropdown-loading").removeClass('active');
            }
            let url = "";
            url = '/search-by-key/'+$(this).val();
            $.ajax({
                type: 'GET',
                url: url,
                data: JSON.stringify(data),
                accept: 'application/json',
                beforeSend: function () {
                    if (e.target.value.length > 0) {
                        $(".search-dropdown-loading").addClass('active');
                    } else {
                        $(".search-dropdown-loading").removeClass('active');
                    }
                },
                success: function (res) {
                    $(".search-dropdown-loading").removeClass('active');
                    $("#searchResult").text(res.length);
                    let htmlData = '';
                    $.each(res, function(key, value) {

                        if(value.type == "Provider")
                        {

                            htmlData += `
                                <div class="search-item">
                                <a href="/service-provider/${value.id}" class="search-item-url">${value.name} -  ${value.type} </a>
                                </div>
                                `;
                        }
                        else if(value.type == "Service")
                        {
                            htmlData += `
                                <div class="search-item">
                                <a href="/service-view/${value.id}" class="search-item-url">${value.name} -  ${value.type} </a>
                                </div>
                                `;
                        }
                        else if(value.type == "Task")
                        {
                            htmlData += `
                                <div class="search-item">
                                <a href="/task-view/${value.id}" class="search-item-url">${value.name} -  ${value.type} </a>
                                </div>
                                `;

                        }
                    });
                    $("#search-items").html(htmlData);
                },
                errors: function (data) {
                    $(".search-dropdown-loading").removeClass('active');
                }
            })

        });
    </script>

    <script>
        function getAllProviders(id) {
            $("#providersContainer").empty();
            let url = "";
            url = '/provider/all/'+id;

            $.ajax({
                type: 'GET',
                url: url,
                data: JSON.stringify(data),
                beforeSend: function () {
                    $("#professionals-loading").addClass('active');
                    $(".professionals-slider").css('display', 'none');
                    $("#providersContainer").empty();
                    $("#professionals-empty").removeClass('active');
                },
                accept: 'application/json',
                success: function (res) {
                    $("#professionals-loading").removeClass('active');
                    if (res.length) {
                        $("#professionals-empty").removeClass('active');
                        $(".professionals-slider").css('display', 'block');
                        let htmlData = '';
                        $.each(res, function(key, value) {
                            htmlData += `<div class="swiper-slide slide-ho-provider">
                                    <div class="pro-slide text-center">
                                        <div class="customize-slide-top">
                                            <div class="likes-container">
                                                <button type="button" {{ !Auth::check() ? "data-toggle=modal data-target=#loginDialog data-toggle=tooltip data-placement=top" : "" }}  title="{{ Auth::check() ? 'Like/UnLike' : "Please Login" }}" id="provider_fav_btn_${value.id}" class="ns-like {{ Auth::check() ? 'favorite-btn' : '' }} ${value.is_like ? 'active' : ''}" data-id="${value.id}" data-type="provider">
                                                    <div id="provider_fav_content_${value.id}" class="like-content">
                                                        <span class="icon-heart-empty icon-unlike icon"></span>
                                                        <span class="icon-heart-full icon-like icon"></span>
                                                    </div>
                                                    <div id="provider_fav_loading_${value.id}" class="loading-content">
                                                        <img class="img-fluid" src="{{ asset('assets/img/icons/ripple.svg') }}" />
                                                    </div>
                                                </button>
                                                <span id="provider_fav_count_${value.id}" class="likes-count">${value.likes_count}</span>
                                            </div>
                                            <div class="pro-user-view">
                                                <img class="img-fluid" src="${value.avatar}" alt="${value.name}" />
                                            </div>
                                            <div class="pro-name text-capitalize lines-1 title mb-2">${value.name}</div>
                                            <div class="rating-container mb-2 justify-content-center">
                                            `;

                            for(i=0; i< parseInt(value.rate); i++)
                            {
                                htmlData += `<span class="icon-star-empty icon gold"></span>`;
                            }


                            for(i=5; i >  parseInt(value.rate); i--)
                            {
                                htmlData += `<span class="icon-star-empty icon"></span>`;
                            }

                            htmlData +=`
                                                <span class="rating-count">(${value.rate})</span>
                                            </div>
                                            <div class="pro-date">based on ${value.customer_review_number} ratings</div>
                                            <div class="pro-type">${value.user_sub_categories_text}</div>
                                        </div>
                                        <div class="customize-slide-bottom">
                                            <a href="/service-provider/${value.id}" class="btn btn-gray">View Provider</a>
                                        </div>
                                    </div>
                                </div>`;
                        });
                        $("#providersContainer").html(htmlData);
                        reloadProfessionals();
                    } else {
                        $("#professionals-empty").addClass('active');
                        $(".professionals-slider").css('display', 'none');
                    }
                }
            })
        };

        getAllProviders(1);
    </script>

@endsection

