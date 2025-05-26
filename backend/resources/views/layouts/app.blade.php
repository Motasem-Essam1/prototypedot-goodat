<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8"/>
    <meta name="description" content="#"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    <title>{{ getenv('APP_NAME') }} | @yield('title')</title>

    <!-- Links -->
    <!-- Project Shortcut Icon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon"/>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon"/>

    <!-- Font (Poppins) -->
    <link rel="stylesheet" href="{{ asset('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap') }}">
    <!-- Bootstrap V 4.1.3 -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}"/>
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}"/>
    <!-- Swiper Js Library -->
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}"/>
    <!-- Convert Images Svg To Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/icomoon.css') }}"/>
    <!-- Range Slider -->
    <link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css') }}"/>
    <!-- Slim Select -->
    <link rel="stylesheet"
          href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.0/slimselect.min.css') }}"/>
    <!-- Selectize -->
    <link rel="stylesheet"
          href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.legacy.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/flags.css') }}"/>
    <!-- Star Rating -->
    <link rel="stylesheet" href="{{ asset('assets/css/star-rating.css') }}"/>
    <!-- Toaster -->
    <link rel="stylesheet" href="{{ asset('assets/css/notyf.min.css') }}">
    <!-- Main Style -->
    @yield('main-style')
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-NYDH7X9Y9E"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-NYDH7X9Y9E');
    </script>
</head>

<body>
@if(Auth::check())
    <!-- Add Services / Task Modal -->
    <div class="modal fade" id="servicesTasksModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content navbar-modal">
                <div class="nbm-title text-center">Listing a service or task?</div>
                <div class="nbm-grid">
                    <div class="nbm-parent-element">
                        <a href="{{ route('service.add.view') }}" class="nbm-card text-center">
                            <span class="icon-service icon"></span>
                            <div class="nbm-card-title">Add a Service</div>
                            <!-- <div class="nbm-card-text text">If you are a service provider and want to show your services to the customers.</div> -->
                        </a>
                    </div>

                    <div class="nbm-parent-element">
                        <a href="{{ route('task.add.view') }}" class="nbm-card text-center">
                            <span class="icon-task icon"></span>
                            <div class="nbm-card-title">Add a Task</div>
                            <!-- <div class="nbm-card-text text">If you are a customer and looking for someone to do a task for you.</div> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Services / Task Modal -->
@else
    @if(!Route::is('login') && !Route::is('register'))
        <!-- Login Dialog -->
        @include('components.login-dialog')
        <!-- Login Dialog -->
    @endif
@endif

<div class="wrapper">
    <!-- Navbar -->
    @include('layouts.header')

    <!-- Content -->
    @yield('content')

    {{-- Alert Danger for inactive user --}}
    @if(auth()->check() && auth()->user()->status == 0)
        <div class="alert-inactive-container alert alert-danger fade show text-center" role="alert">
            <strong>Hello {{ auth()->user()->name }} your account is deactivated!</strong> You should contact support to active your account.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <!-- Footer -->
    @include('layouts.footer')
</div>

<!-- Scripts -->
<!-- Jquery V 3.3.1 -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>

<!-- Range Slider -->
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js') }}"></script>

<!-- Slim Select -->
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.0/slimselect.min.js') }}"></script>

<!-- Fontawesome -->
<script src="{{ asset('assets/js/fontawesome.js') }}"></script>

<!-- Bootstrap V 4.3.1 -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

<!-- Swiper Js -->
<script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>

<!-- Telephone -->
{{--<script src="{{ asset('assets/js/telephone-validation.js') }}"></script>--}}

<!-- Selectize -->
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.js') }}"></script>

<!-- Country Keys -->
<script src="{{ asset('assets/js/countries-keys.js') }}"></script>
<script src="{{ asset('assets/js/countries-dial-code.js') }}"></script>

<!-- Cleave -->
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js') }}"></script>

<!-- Star Rating -->
<script src="{{ asset('assets/js/star-rating.min.js') }}"></script>

<!-- Toaster Alerts -->
<script src="{{ asset('assets/js/notyf.min.js') }}"></script>

<!-- Moment Js -->
<script src="{{ asset('assets/js/moment.js') }}"></script>

<!-- Meta (Facebook) -->
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v13.0&appId=629322731899614&autoLogAppEvents=1" nonce="ywRETNx8"></script>

<!-- Main Functions -->
<script src="{{ asset('assets/js/functions.js') }}"></script>

<!-- Validation -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<!-- Tawk.to Chat Support -->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/6507f4bf0f2b18434fd91583/1hajh8321';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Logout
    $('.logout-btn').on("click", function () {
        const _token = $("#_token").val();
        $.ajax({
            url: "/logout",
            type: "POST",
            data: {_token: _token},
            success: function () {
                window.location.reload();
            }
        });
    });

    // Star Rating
    var stars = new StarRating(".star-rating", {
        classNames: {
            active: "gl-active",
            base: "gl-star-rating",
            selected: "gl-selected",
        },
        clearable: true,
        maxStars: 10,
        prebuilt: false,
        stars: null,
        tooltip: false,
    });

    $(".star-rating").on("change", function (e) {
        let count = $(this).data('count');
        $(count).html(`(${e.target.value ? e.target.value + '.0' : '0.0'})`);
    });
</script>

@if(auth()->check())
    <!-- $request_token -->
    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.7.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.7.0/firebase-messaging.js"></script>

    <script>
        var notyf = new Notyf(
            {
                position: {
                    x: 'right',
                    y: 'top',
                }
            }
        );

        const firebaseConfig = {
            apiKey: "AIzaSyA47fTB49E6fDpSTRilLCw07RSci1W4P3Q",
            authDomain: "good-push-ddc80.firebaseapp.com",
            projectId: "good-push-ddc80",
            storageBucket: "good-push-ddc80.appspot.com",
            messagingSenderId: "84611328299",
            appId: "1:84611328299:web:9baa9f2a5bd57641db7d6e",
            measurementId: "G-W1MG0XM9G9"
        };

        firebase.initializeApp(firebaseConfig);

        const messaging = firebase.messaging();

        /*
            * Get current device token from firebase
            * Check if device user has token or no if not exist laravel will store it in database
        */
        messaging.requestPermission().then(function () {
            return messaging.getToken();
        })
            .then(function (token) {
                $.ajax({
                    url: '{{ route("save-token") }}',
                    type: 'POST',
                    data: {
                        device_token: token,
                        _token: "{{ csrf_token() }}"
                    },
                    // dataType: 'JSON',
                    success: function (response) {

                    },
                    error: function (err) {
                        // console.log('User Chat Token Error'+ err);
                    },
                });

            }).catch(function (err) {
            // console.log('User Chat Token Error'+ err);
        });
        // End

        navigator.serviceWorker.addEventListener('message', function (event) {
            fetchNotifications();
            notificationRingtone();
        });

        messaging.onMessage(function (payload) {
            const notificationOptions = {
                body: payload.data.body
            };
            fetchNotifications();
            notificationRingtone();

            // Check item type
            let row = JSON.parse(payload.data.row);
            // console.log(row);
            let idOrSlug = '';
            if (row.item_type == 'provider') {
                idOrSlug = row.item ? row.item.id : '';
            } else if (row.item_type == 'service') {
                idOrSlug = row.item ? row.item.service_slug : '';
            } else if (row.item_type == 'task') {
                idOrSlug = row.item ? row.item.task_slug : '';
            }
            notyf.success({
                duration: 8000,
                message: `<div onclick="notify(${row.id}, '${row.item_type}', '${idOrSlug}')" class="notify-toast-btn">${notificationOptions.body}</div>`,
            });
        });

        function fetchNotifications() {
            $.ajax({
                url: '{{ route("fetchNotifications") }}',
                type: 'GET',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function () {
                    let loading = `
                                        <div class="notify-loading text-center">
                                            <img class="img-fluid" src="{{ asset('assets/img/icons/rolling.svg') }}" width="40" />
                                        </div>
                                   `;
                    $(".notify-data").html(loading);
                },
                success: function (response) {
                    const data = response.data;

                    if (response.is_read > 0) {
                        $(".active-notify").addClass('active');
                    }

                    if (data.length > 0) {
                        let htmlData = '';
                        if (response.is_read > 0) {
                            $(".updates-count").text(`(${response.is_read})`);
                        }
                        $.each(data, function (index, row) {
                            // Check item type
                            let idOrSlug = '';
                            let itemName = '';
                            if (row.item_type == 'provider') {
                                idOrSlug = row.item_id;
                                itemName = row.item ? row.item.name : '';
                            } else if (row.item_type == 'service') {
                                idOrSlug = row.item ? row.item.service_slug : '';
                                itemName = row.item ? row.item.service_name : '';
                            } else if (row.item_type == 'task') {
                                idOrSlug = row.item ? row.item.task_slug : '';
                                itemName = row.item ? row.item.task_name : '';
                            }

                            // Check action type
                            let actionType = '';
                            if (row.action_type == 'favorite') {
                                actionType = `<div class='notify-action-type'>
                                                <i class="fa-solid fa-heart icon"></i>
                                            </div>`;
                            } else if (row.action_type == 'review') {
                                actionType = `<div class='notify-action-type'>
                                                <i class="fa-solid fa-star icon"></i>
                                            </div>`;
                            }

                            htmlData += `<div onclick="notify(${row.id}, '${row.item_type}', '${idOrSlug}')" class="noti-user-row flex-center-v">
                                            <div class="nur-view">
                                               <img class="img-fluid" src="${row.user.user_data.avatar}" alt="${row.user.name}" />
                                               ${actionType}
                                            </div>
                                            <div class="nsr-details">
                                                <div class="nsr-title text-capitalize flex-between-vh"><span class="nsr-name text-capitalize max-1-line">${row.user.name}</span> <span class="nsr-date">${moment(row.created_at).format('LL')} ${row.is_read == 0 ? '<span><span class="notify-bullet"></span></span>' : ''}</div>
                                                <div class="nsr-text lines-2">${row.message} <b class="text-capitalize">(${itemName})</b></div>
                                            </div>
                                        </div>`;
                        });

                        $(".notify-data").html(htmlData);
                    } else {
                        $(".notify-data").html(`<div class="empty-noty d-flex justify-content-center">There are no notifications yet.</div>`);
                    }
                },
                error: function (error) {
                },
            });
        }

        fetchNotifications();

        // Notification Ringtone
        function notificationRingtone() {
            const audio = new Audio("{{ asset('assets/audio/notifications.mp3') }}");
            audio.play();
        }

        // Redirect to service & change to readed
        function notify(id, type, idOrSlug) {
            let url = null;
            if (type === 'provider') {
                url = `/service-provider/${idOrSlug}`;
            } else if (type === 'service') {
                url = `/service-view/${idOrSlug}`;
            } else if (type === 'task') {
                url = `/task-view/${idOrSlug}`;
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('isRead') }}',
                data: {id: id, _token: "{{ csrf_token() }}"},
                accept: 'application/json',
                success: function (res) {
                    if (res.success) {
                        window.location.href = url;
                    }
                }
            });
        }
    </script>
@endif
@yield('custom-script')
</body>
</html>
