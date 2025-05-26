@extends('layouts.app')
@section('title', 'Add new service')
@section('main-style')
    <!-- Toast -->
    <link rel="stylesheet" href="{{ asset('assets/css/toast.css') }}"/>
    <!-- Map -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <!-- Wizard Steps -->
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-steps.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/pages/services.css') }}"/>
@endsection
@section('content')
    <div class="wrapper-container wrapper-without-top">
        <div class="services-container">
            <div class="container">
                <div class="services-header flex-center-v mb-3">
                    <!-- Breadcrumb -->
                    <div class="breadcrumb-container">
                        <ol class="breadcrumb breadcrumb-list w-100">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active max-1-line" aria-current="page">Add new service</li>
                        </ol>
                    </div>
                    <!-- Breadcrumb -->

                    <!-- Appear In Responsive Only -->
                    <div class="ns-title cos-title sht-res"><span>Services.</span></div>
                </div>

                <!-- Services Content -->
                <div class="services-content">
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
                    <div id="demo" class="services-flexable d-flex">
                        <div class="ser-steps text-center flex-center-vh">
                            <div class="steps-cards">
                                <button data-step-target="step1" class="step-card-btn active">
                                    <div class="step-logo flex-center-vh"><span class="icon-form icon"></span></div>
                                    <div class="step-center-line"></div>
                                    <div class="step-title">Informations</div>
                                    <div class="step-after-line"></div>
                                </button>

                                <button data-step-target="step2" class="step-card-btn">
                                    <div class="step-logo flex-center-vh"><span class="icon-pics icon"></span></div>
                                    <div class="step-center-line"></div>
                                    <div class="step-title">Pictures</div>
                                    <div class="step-after-line"></div>
                                </button>

                                <button data-step-target="step3" class="step-card-btn">
                                    <div class="step-logo flex-center-vh"><span class="icon-location-3 icon"></span>
                                    </div>
                                    <div class="step-center-line"></div>
                                    <div class="step-title">Location</div>
                                </button>
                            </div>
                        </div>

                        <!-- Steps Container -->
                        <div class="ser-steps-container">
                            <form action="{{ route('service.add') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <!-- Information -->
                                <div data-step="step1" class="step-container step-info">
                                    <div class="sc mb-5">
                                        <div class="step-main-title title text-uppercase">ADD A NEW SERVICE</div>
                                        <div class="step-sub-title">Add your service information</div>
                                    </div>

                                    <div class="sc-form">
                                        <div class="sc-double sc-double-text flex-between-h mb-4">
                                            <div class="form-group-row">
                                                <label class="fgr-label">Service Name</label>
                                                <input class="fgr-input info-input" type="text" name="service_name"
                                                       placeholder="Your service name"/>
                                                <p style="color: red">
                                                    @if($errors->has('service_name'))
                                                        {{ $errors->first('service_name') }}
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="form-group-row">
                                                <label class="fgr-label">Service Category</label>
                                                <select class="sc-selectbox info-input text-capitalize"
                                                        name="category_id" id="category">
                                                    @foreach($categories as $category)
                                                        <optgroup label="{{$category->category_name}}">
                                                            @foreach($category->subCategories as $subCategory)
                                                                <option name
                                                                        value="{{$subCategory->id}}">{{$subCategory->sub_category_name}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="sc-single mb-4">
                                            <div class="form-group-row">
                                                <label class="fgr-label">Service Description</label>
                                                <textarea class="fgr-input info-input fgr-textarea"
                                                          name="service_description"
                                                          placeholder="Write Your Service Description"></textarea>
                                            </div>
                                            <p style="color: red">
                                                @if($errors->has('service_description'))
                                                    {{ $errors->first('service_description') }}
                                                @endif
                                            </p>
                                        </div>

                                        <div class="sc-double flex-between-vh mb-4">
                                            <div class="form-group-row mb-3">
                                                <label class="fgr-label">Price Range ( {{$configurations['value']}} )</label>
                                                <input class="fgr-input info-input only-numbers" type="text"
                                                       name="starting_price" placeholder="FROM"/>
                                                <p style="color: red">
                                                    @if($errors->has('starting_price'))
                                                        {{ $errors->first('starting_price') }}
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="form-group-row mb-3">
                                                <label class="fgr-label opacity-0">Price Range ( {{$configurations['value']}} )</label>
                                                <input class="fgr-input info-input only-numbers" type="text"
                                                       name="ending_price" placeholder="TO"/>
                                                <p style="color: red">
                                                    @if($errors->has('ending_price'))
                                                        {{ $errors->first('ending_price') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pictures -->
                                <div data-step="step2" class="step-container step-pictures">
                                    <div class="sc mb-5 pb-2">
                                        <div class="step-main-title title text-uppercase">ADD A NEW SERVICE</div>
                                        <div class="step-sub-title">Add your service pictures</div>
                                    </div>

                                    <div class="sc-form">
                                        <!-- Main Image -->
                                        <div class="sc-main-img mb-4">
                                            <div class="sc-img-main-title mb-2">Main Picture</div>
                                            <div class="sc-img-card d-inline-block">
                                                <div class="sc-img sc-img-bg inputfr-parent">
                                                    <div class="image-view imgfr-parent">
                                                        <img class="img-fluid img-view-src img-file-reader" src=""
                                                             alt=""/>
                                                        <button type="button" class="btn close-img-reader"><span
                                                                class="icon-cancel icon"></span></button>
                                                    </div>
                                                    <div class="sc-it text-center">
                                                        <span class="icon-pic icon"></span>
                                                        <div class="sc-img-title">Add Picture</div>
                                                    </div>
                                                    <input class="input-file-reader" name="images[]" type="file"
                                                           accept="image/*"/>
                                                </div>
                                                <p style="color: red">
                                                    @if($errors->has('images'))
                                                        {{ $errors->first('images') }}
                                                    @endif
                                                </p>

                                            </div>
                                        </div>
                                        <!-- Other Images -->
                                        <div class="sc-other-img mb-3">
                                            <div class="sc-img-main-title mb-2">Other Pictures</div>
                                            <!-- Collection -->
                                            <div class="sc-collection">
                                                @for ($i = 0; $i < Auth::user()->user_data->package->number_of_images_per_service; $i++)
                                                    <div class="other-img-card d-inline-block">
                                                        <div class="sc-img sc-img-sm inputfr-parent">
                                                            <div class="image-view imgfr-parent">
                                                                <img class="img-fluid img-view-src img-file-reader"
                                                                     src="" alt=""/>
                                                                <button type="button" class="btn close-img-reader"><span
                                                                        class="icon-cancel icon"></span></button>
                                                            </div>
                                                            <div class="sc-it text-center">
                                                                <span class="icon-pic icon"></span>
                                                                <div class="sc-img-title">Add Picture</div>
                                                            </div>
                                                            <input class="input-file-reader" name="images[]" type="file"
                                                                   accept="image/*"/>
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="sc-alert flex-center-v pt-2 pb-4">
                                            <span class="icon-exclamation icon mr-2"></span>
                                            If you have a picture to clear the service for the provider please include
                                            it.
                                        </div>
                                    </div>
                                </div>

                                <!-- Location -->
                                <div data-step="step3" class="step-container step-location">
                                    <div class="sc mb-5">
                                        <div class="step-main-title title text-uppercase">ADD A NEW SERVICE</div>
                                        <div class="step-sub-title">Add where your service will be done.</div>
                                    </div>

                                    <div class="sc-location">
                                        <div class="sc-places">
                                            <div class="sc-img-main-title mb-2">Service Location</div>
                                            <div class="places-search position-relative flex-between-vh mb-4">
                                                <div id="searchGroup"
                                                     class="access-close ps-input width-fluid flex-center-v mr-3">
                                                    <span class="icon-search icon mr-2"></span>
                                                    <input id="pac-input" placeholder="Search Location" type="text"/>
                                                    <div id="searchDropdown"
                                                         class="access-close main-search-dropdown mt-1">
                                                        <div id="places-search"
                                                             class="search-dropdown-content scroller">
                                                            <!-- Data Here --></div>
                                                        <div class="search-results text-left">Search results <span
                                                                id="search-result"></span></div>
                                                    </div>
                                                </div>
                                                <div class="search-dropdown-loading mr-2">
                                                    <div class="d-flex align-items-center">
                                                        <img class="img-fluid search-loading"
                                                             src="{{ asset('assets/img/icons/rolling.svg') }}"/>
                                                    </div>
                                                </div>
                                                <button id="cancelSearch" class="cancel-search flex-center-center"
                                                        type="button"><span class="icon-cancel icon"></span></button>
                                                <button type="button" class="btn btn-gray geo-location"
                                                        id="getLocation">
                                                    <span class="icon-current-location icon mr-2"></span> <span
                                                        class="get-loc-span">Get Location</span>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="map-container">
                                            <div id="map"></div>
                                            <div class="map-loader">
                                                <img class="img-fluid"
                                                     src="{{ asset("assets/img/icons/rolling.svg") }}"/>
                                            </div>
                                            <button id="toggleMapStyle" type="button" class="btn btn-toggle-style"
                                                    data-style="dark">
                                                <span class="icon-moon icon"></span>
                                                <span class="icon-sun icon"></span>
                                            </button>
                                        </div>
                                        <input type="hidden" id="data-lat" name="location_lat" value="">
                                        <input type="hidden" id="data-lng" name="location_lng" value="">
                                    </div>

                                    <div class="sc-alert flex-center-v pt-2 pb-4">
                                        <span class="icon-exclamation icon mr-2"></span>
                                        Move the pin point to the location or click on current location icon .
                                    </div>
                                </div>

                                <!-- Steps Actions Btns -->
                                <div class="steps-actions d-flex pt-3">
                                    <button data-step-action="next" class="btn step-btn btn-bg mr-4 step-continue-btn">Continue</button>
                                    <button type="submit" class="btn step-btn btn-bg mr-4">Submit Service</button>
                                    <button data-step-action="prev" class="btn step-btn btn-simple">Previous</button>
                                </div>
                            </form>
                            <!-- <div class="step-footer">
                              <button data-step-action="prev" class="step-btn">Previous</button>
                              <button data-step-action="next" class="step-btn">Next</button>
                              <button data-step-action="finish" class="step-btn">Finish</button>
                            </div> -->
                        </div>
                    </div>
                </div>
                <!-- Services Content -->

            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <!-- Toast -->
    <script src="{{ asset('assets/js/toast.js') }}"></script>
    <!-- Map Styles Data -->
    <script src="{{ asset('assets/js/map-styles.js') }}"></script>
    <!-- Google Maps -->
    {{--    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtwuPEM2cVeJ6U5AIrVYhE-uTVGR7S0oo&callback=initMap&v=weekly" defer></script>--}}
    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtwuPEM2cVeJ6U5AIrVYhE-uTVGR7S0oo&libraries=places&callback=initMap"></script>
    <script src="{{ asset('assets/js/jquery-steps.min.js') }}"></script>

    <script>
        // https://oguzhanoya.github.io/jquery-steps/#events
        $('#demo').steps({
            startAt: 0,
            showBackButton: true,
            showFooterButtons: true,
            stepSelector: '.steps-cards',
            contentSelector: '.ser-steps-container',
            footerSelector: '.steps-actions',
            validate: true,
            onFinish: function () {
                // alert('Services completed added! redirect to Congts Page');
                // window.location.href = 'service-added.html';
            },
        });
    </script>

    <script>
        // Main Variables to maps
        const mainLocation = {
            lat: null,
            lng: null
        };

        const currentLocationLatLng = {
            lat: null,
            lng: null
        };

        let mainStyle = mapStylesData["default"];

        // Find Places
        $('#pac-input').on('input', function (e) {
            $("#places-search").html(' ');
            const value = e.target.value;

            if (value.length > 0) {
                $(".search-dropdown-loading").addClass('active');
            } else {
                $(".search-dropdown-loading").removeClass('active');
            }

            if (value.length >= 3) {
                $.ajax({
                    type: 'GET',
                    url: "{{ route('maps.search') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        location: value,
                        lat: currentLocationLatLng["lat"],
                        lng: currentLocationLatLng["lng"],
                    },
                    beforeSend: function () {
                        if (value.length > 0) {
                            $(".search-dropdown-loading").addClass('active');
                        } else {
                            $(".search-dropdown-loading").removeClass('active');
                        }

                        $("#places-search").html(' ');
                    },
                    success: function (response) {
                        $(".search-dropdown-loading").removeClass('active');
                        let results = response.data.predictions;
                        if (results) {
                            if (results.length) {
                                let htmlData = '';
                                $.each(results, function (index, row) {
                                    htmlData += `<button type="button" class="search-item fps-btn" data-place-id="${row.place_id}" data-location="${row.structured_formatting.main_text}">
                                                    <div class="search-item-url close-dd-btn d-flex">
                                                        <span class="icon-location-3 icon-location"></span>
                                                        <div class="w-100">
                                                            <div class="search-place-title text-capitalize lines-1">${row.structured_formatting.main_text}</div>
                                                            <div class="search-place-sub-title lines-1">${row.description}</div>
                                                        </div>
                                                    </div>
                                                </button>`;
                                });
                                $("#places-search").html(htmlData);
                                $("#search-result").text(results.length);
                            }
                        }
                    },
                    error: function (error) {
                        $(".search-dropdown-loading").removeClass('active');
                    }
                })
            }
        });

        function mainLatLng(locationLat, locationLng) {
            const lat = parseFloat(locationLat);
            const lng = parseFloat(locationLng);
            $('#map').attr('data-lat', lat);
            $('#map').attr('data-lng', lng);
            $('#data-lat').val(lat);
            $('#data-lng').val(lng);
        }

        function initMap() {
            const tagData = document.getElementById("map");
            // The location of Uluru
            const uluru = {
                lat: parseFloat(tagData.getAttribute("data-lat")),
                lng: parseFloat(tagData.getAttribute("data-lng"))
            };

            mainLocation['lat'] = parseFloat(tagData.getAttribute("data-lat"));
            mainLocation['lng'] = parseFloat(tagData.getAttribute("data-lng"));

            // The map, centered at Uluru
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                styles: mainStyle,
                // center: uluru
                center: mainLocation,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                navigationControl: true,
                scrollWheel: true,
                scaleControl: true,
                draggable: true,
                mapTypeControl: false,
                mapId: ''
            });

            // map.setOptions({ styles: mapStylesData["default"] });

            // The marker, positioned at Uluru
            const marker = new google.maps.Marker({
                map,
                // position: uluru,
                position: map.getCenter(),
                draggable: true,
                animation: google.maps.Animation.DROP,
                // title:"Hello World!",
                icon: {
                    // url: "https://res.cloudinary.com/aramco-sa/image/upload/v1663800552/mappin-ic_1_aofwrm.svg",
                    url: "{{ asset('assets/img/icons/marker.svg') }}",
                    scale: 12,
                    fillColor: "#080",
                    fillOpacity: 0.4,
                    strokeWeight: 0.4
                },
            });

            map.setCenter(mainLocation);
            marker.setPosition(mainLocation);

            marker.addListener("click", toggleBounce);
            google.maps.event.addListener(marker, 'dragend', function (marker) {
                var latLng = marker.latLng;
                mainLatLng(latLng.lat(), latLng.lng());
                mainLocation['lat'] = latLng.lat();
                mainLocation['lng'] = latLng.lng();
                // map.setCenter({lat:latLng.lat(), lng: latLng.lng()});
                // map.setCenter(mainLocation);
                map.panTo({lat: latLng.lat(), lng: latLng.lng()});
            });

            google.maps.event.addListener(map, 'center_changed', function (event) {
                marker.setPosition(map.getCenter());
                mainLatLng(map.getCenter().lat(), map.getCenter().lng());
            });

            google.maps.event.addListener(map, 'click', function (event) {
                mainLatLng(event.latLng.lat(), event.latLng.lng());
                mainLocation['lat'] = event.latLng.lat();
                mainLocation['lng'] = event.latLng.lng();
                // map.setCenter(mainLocation);
                // marker.setPosition(event.latLng);
                map.panTo(event.latLng);
            });

            function toggleBounce() {
                if (marker.getAnimation() !== null) {
                    marker.setAnimation(null);
                } else {
                    marker.setAnimation(google.maps.Animation.BOUNCE);
                }
            }

        }

        $(document).on('click', '.fps-btn', function () {
            const placeId = $(this).data('place-id');
            const location = $(this).data('location');

            if (placeId) {
                $(".map-loader").addClass('active');
                $.ajax({
                    type: 'GET',
                    url: "{{ route('maps.searchByPlaceId') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        location: location,
                        place_id: placeId
                    },
                    beforeSend: function () {

                    },
                    success: function (response) {
                        if (response.data && response.data.result) {
                            let result = response.data.result.geometry.location;
                            mainLatLng(result.lat, result.lng);
                            mainLocation['lat'] = parseFloat(result.lat);
                            mainLocation['lng'] = parseFloat(result.lng);
                            // map.setCenter(mainLocation);
                            // marker.setPosition(mainLocation);
                            $(".map-loader").removeClass('active');
                            initMap();
                        } else {
                            $(".map-loader").removeClass('active');
                        }
                    },
                    error: function (error) {
                        $(".map-loader").removeClass('active');
                    }
                });
            }
        });

        $(".btn-toggle-style").on('click', function () {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $(this).data('style', 'default');
                mainStyle = mapStylesData["default"];
                initMap();
            } else {
                $(this).addClass('active');
                $(this).data('style', 'dark');
                mainStyle = mapStylesData["dark"];
                initMap();
            }
        });


        // window.initMap = initMap;

        // Get Location
        $("#getLocation").on('click', function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        });

        function showPosition(position) {
            const posLat = position.coords.latitude;
            const posLng = position.coords.longitude;
            mainLatLng(posLat, posLng);
            initMap();
        }

        $(document).ready(function () {
            function currentLocation() {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        const {latitude, longitude} = position.coords;
                        mainLatLng(latitude, longitude);
                        currentLocationLatLng['lat'] = latitude;
                        currentLocationLatLng['lng'] = longitude;
                        initMap();
                    },
                    () => {
                    }
                )
            }

            currentLocation();
        });
    </script>

    <script>
        // Select Order
        $(function () {
            new SlimSelect({
                select: '#category',
                showSearch: false,
                showContent: 'down'
            })
        });

        // $(document).ready(function () {
        //     $("form").validate({
        //         errorClass: 'validation-error',
        //         rules: {
        //             service_name: {
        //                 required: true,
        //                 minlength: 3
        //             },
        //             category_id: {
        //                 required: true
        //             },
        //             service_description: {
        //                 required: true,
        //                 minlength: 3
        //             },
        //             starting_price: {
        //                 required: true
        //             },
        //             ending_price: {
        //                 required: true,
        //                 // greaterThanEquals: '[name="starting_price"]',
        //                 // maxTruck: {greaterThan: '[name="starting_price"]'}
        //             },
        //         },
        //         messages: {
        //             service_name: {
        //                 required: "Please enter service name",
        //                 minlength: "Your service name must be at least 3 characters"
        //             },
        //             category_id: {
        //                 required: "Please choose category"
        //             },
        //             service_description: {
        //                 required: "Please enter service description",
        //                 minlength: "Your service description must be at least 3 characters"
        //             },
        //             starting_price: {
        //                 required: "Please enter starting price"
        //             },
        //             ending_price: {
        //                 required: "Please enter starting price"
        //             },
        //         },
        //         submitHandler: function(form) {
        //             form.submit();
        //         }
        //     });
        // });
        //
        // $('.step-continue-btn').on('click', function () {
        //     if ($("form").valid()) {
        //         console.log('valid');
        //     } else {
        //         console.log('invalid');
        //     }
        // });
    </script>

@endsection
