@extends('layouts.app')
@section('title', 'Page not found')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/404.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-with-top">
        <div class="error-404">
            <div class="container">
                <div class="not-found-page">
                    <div>
                        <img class="img-404 img-fluid" src="{{ asset('assets/img/404.svg') }}" />
                        <div class="title mb-4">Page not found</div>
                        <div>
                            <a href="{{ route('home') }}" class="btn btn-bg d-inline-block">Back to home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('custom-script')

@endsection
