@extends('layouts.app')
@section('title', 'Contact Us')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/contact.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-with-top">
        <div class="contact-us">
            <div class="container">
                <div class="contact-us-container">
                    <div class="contact-block">
                        {{-- Left --}}
                        <div class="contact-left">
                            <div class="ns-title cos-title"><span>Contact Us</span></div>
                            <div class="contact-text text mb-4">{{ $attributes['contact_us_description'] ?? '' }}</div>
                            @if(isset($attributes['email']))
                                <div class="ctn-row single">
                                    <div class="contact-sub-title"><span class="cst">Email:</span> <a href="mailto:{{ $attributes['email'] }}" class="contact-text text">{{ $attributes['email'] }}</a></div>
                                </div>
                            @endif

                            @if(isset($attributes['phone_number']))
                                <div class="ctn-row single">
                                    <div class="contact-sub-title "><span class="cst">Phone:</span> <a href="tel:{{ $attributes['phone_number'] }}" class="contact-text text">+{{ $attributes['phone_number'] }}</a></div>
                                </div>
                            @endif

                            @if(isset($attributes['address']))
                                <div class="ctn-row">
                                    <div class="contact-sub-title ">Deft@ Office <div class="contact-text text">{{ $attributes['address'] }}</div></div>
                                </div>
                            @endif

                            @if(isset($attributes['contact_us_questions_list']))
                                <div class="contact-benefits pt-4 mb-5">
                                    <div class="contact-title mb-3">What You Get When Asking Your Question</div>
                                    <ul class="benefits-list">
                                        @foreach(explode('|', $attributes['contact_us_questions_list']) as $point)
                                            <li>{{ $point }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="contact-form">
                                <form id="contactUsForm">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group-row">
                                                <label class="fgr-label">First Name <span class="restrict">*</span></label>
                                                <input class="fgr-input info-input" type="text" name="first_name" placeholder="First Name" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group-row">
                                                <label class="fgr-label">Last Name <span class="restrict">*</span></label>
                                                <input class="fgr-input info-input" type="text" name="last_name" placeholder="Last Name" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group-row">
                                                <label class="fgr-label">Email <span class="restrict">*</span></label>
                                                <input class="fgr-input info-input" type="email" name="email" placeholder="example@example.com" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group-row">
                                                <label class="fgr-label">Phone Number</label>
                                                <input class="fgr-input info-input" type="text" name="phone_number" placeholder="+365 123 456 789" required />
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <div class="form-group-row">
                                                <label class="fgr-label">How may we help you ? <span class="restrict">*</span></label>
                                                <textarea rows="5" class="fgr-input info-input" name="message" placeholder="Enter your message" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="contact-action d-flex justify-content-end">
                                        <button id="contactUsBtn" type="submit" class="btn btn-bg">Send Now</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Right --}}
                        <div class="contact-right pt-4">
                            @if(isset($attributes['location']))
                                <div class="contact-map">
                                    <iframe src="{{ $attributes['location'] }}" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            @endif

                            @if(isset($attributes['phone_number']))
                                <div class="contact-widget">Call Us <a href="tel:{{ $attributes['phone_number'] }}">+{{ $attributes['phone_number'] }}</a></div>
                            @endif

                            @if(isset($attributes['email']))
                                <div class="contact-widget">Contact Us <a href="mailto:{{ $attributes['email'] }}">{{ $attributes['email'] }}</a></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var notyf = new Notyf(
            {
                position: {
                    x: 'right',
                    y: 'top',
                }
            }
        );

        // Contact Us
        $('#contactUsBtn').on("click", function () {
            var data = $("#contactUsForm").serialize();
            $.ajax({
                url: "/api/contact-us",
                type: "POST",
                data: data,
                beforeSend: function() {
                    $('#contactUsBtn').attr('disabled', true);
                    $('#contactUsBtn').text("Loading...");
                },
                success: function (data) {
                    notyf.success({
                        duration: 6000,
                        message: data.message,
                    });
                    $("#contactUsForm")[0].reset();
                    $('#contactUsBtn').attr('disabled', false);
                    $('#contactUsBtn').text("Send Now");
                },
                error: function(data) {
                    let res = data.responseJSON.errors;
                    Object.keys(res).map(function(key) {
                        res[key].map((row) => {
                            notyf.error({
                                duration: 6000,
                                message: row,
                            });
                        })
                    })
                    $('#contactUsBtn').attr('disabled', false);
                    $('#contactUsBtn').text("Send Now");
                },
            });
        });
    </script>
@endsection
