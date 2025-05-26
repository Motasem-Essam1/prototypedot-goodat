@extends('layouts.app')
@section('title', 'Home')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
@endsection
@section('content')
    <div class="container pt-5">
        <div class="row justify-content-center pt-5">
            <div class="col-md-8">
                <center>
                    <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()" class="btn btn-danger btn-xs btn-flat">Allow for Notification</button>
                </center>
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form id="noty-form">
                            @csrf
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" name="title">
                            </div>
                            <div class="form-group">
                                <label>Body</label>
                                <textarea class="form-control" name="body"></textarea>
                            </div>
                            <input type="text" class="form-control" name="link" value="https://www.emadsamy.com">
                            <button id="noty-btn" type="button" class="btn btn-primary">Send Notification</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-script')

{{--    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>--}}
{{--    <script>--}}

{{--        var firebaseConfig = {--}}
{{--            apiKey: "AIzaSyA47fTB49E6fDpSTRilLCw07RSci1W4P3Q",--}}
{{--            authDomain: "good-push-ddc80.firebaseapp.com",--}}
{{--            projectId: "good-push-ddc80",--}}
{{--            storageBucket: "good-push-ddc80.appspot.com",--}}
{{--            messagingSenderId: "84611328299",--}}
{{--            appId: "1:84611328299:web:9baa9f2a5bd57641db7d6e",--}}
{{--            measurementId: "G-W1MG0XM9G9"--}}
{{--        };--}}

{{--        firebase.initializeApp(firebaseConfig);--}}
{{--        const messaging = firebase.messaging();--}}

{{--        function initFirebaseMessagingRegistration() {--}}
{{--            messaging--}}
{{--                .requestPermission()--}}
{{--                .then(function () {--}}
{{--                    return messaging.getToken()--}}
{{--                })--}}
{{--                .then(function(token) {--}}
{{--                    console.log(token);--}}

{{--                    $.ajaxSetup({--}}
{{--                        headers: {--}}
{{--                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--                        }--}}
{{--                    });--}}

{{--                    $.ajax({--}}
{{--                        url: '{{ route("save-token") }}',--}}
{{--                        type: 'POST',--}}
{{--                        data: {--}}
{{--                            token: token--}}
{{--                        },--}}
{{--                        dataType: 'JSON',--}}
{{--                        success: function (response) {--}}
{{--                            alert('Token saved successfully.');--}}
{{--                        },--}}
{{--                        error: function (err) {--}}
{{--                            console.log('User Chat Token Error'+ err);--}}
{{--                        },--}}
{{--                    });--}}

{{--                }).catch(function (err) {--}}
{{--                console.log('User Chat Token Error'+ err);--}}
{{--            });--}}
{{--        }--}}

{{--        messaging.onMessage(function(payload) {--}}
{{--            const noteTitle = payload.notification.title;--}}
{{--            const noteOptions = {--}}
{{--                body: payload.notification.body,--}}
{{--                icon: payload.notification.icon,--}}
{{--                link: payload.notification.link--}}
{{--            };--}}
{{--            new Notification(noteTitle, noteOptions);--}}
{{--        });--}}

{{--    </script>--}}
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#noty-btn").on('click', function () {
        var data = $("#noty-form").serialize();
        $.ajax({
            url: '{{ route("send.notification") }}',
            type: 'POST',
            data: data,
            dataType: 'JSON',
            success: function (response) {

                var notyf = new Notyf(
                    {
                        position: {
                            x: 'right',
                            y: 'top',
                        }
                    }
                );
                notyf.success(`${response.message}`);
            },
            error: function (err) {
                notyf.success('User Chat Token Error'+ err);
            },
        });
    });
</script>
@endsection
