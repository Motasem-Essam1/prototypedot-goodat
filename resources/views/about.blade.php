@extends('layouts.app')
@section('title', 'About')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/about.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-with-top">
        <div class="about">
            <div class="container">
                <div class="about-container">
                    {{-- About Header --}}
                    <div class="about-header" style="background-image: url('{{ asset('assets/img/about/about-header.jpeg') }}')">
                        <div class="header-overlay text-center">
                            <div class="header-breadcrumb d-flex align-items-center justify-content-center">
                                <a class="breadcrumb-url text-uppercase" href="{{ route('home') }}">Home</a>
                                <span class="divider"></span>
                                <span class="breadcrumb-url text-uppercase" href="javascript:void(0)">About</span>
                            </div>
                            <div class="about-main-title title">About DEFT@</div>
                            <div class="text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,<br />
                                molestiae quas vel sint commodi repudiandae consequuntur
                            </div>
                        </div>
                    </div>

                    {{-- About Widgets --}}
                    <div class="about-container-widgets">
                        <div class="about-widget-block">
                            <div class="about-widget-view">
                                <img class="img-fluid" src="{{ asset('assets/img/about/about-1.png') }}" />
                            </div>
                            <div class="about-widget-details">
                                <div class="about-sub-title">ABOUT DEFT@</div>
                                <div class="about-title">Top digital solutions for beating the system</div>
                                <div class="about-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
                                    molestiae quas vel sint commodi repudiandae consequuntur voluptatum laborum
                                    numquam blanditiis harum quisquam eius sed odit fugiat iusto fuga praesentium
                                    optio, eaque rerum! Provident similique accusantium nemo autem.
                                </div>
                                <div class="about-action">
                                    <button class="button-default">Learn More</button>
                                </div>
                            </div>
                        </div>

                        <div class="about-widget-block">
                            <div class="about-widget-details">
                                <div class="about-sub-title">ABOUT DEFT@</div>
                                <div class="about-title">Company Value</div>
                                <div class="about-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
                                    molestiae quas vel sint commodi repudiandae consequuntur voluptatum laborum
                                    numquam blanditiis harum quisquam eius sed odit fugiat iusto fuga.
                                </div>
                                <div class="about-correct-benefits">
                                    <div class="correct-benefit-row">
                                        <span class="icon-correct icon"></span>
                                        <div class="about-text">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
                                            molestiae quas vel sint
                                        </div>
                                    </div>

                                    <div class="correct-benefit-row">
                                        <span class="icon-correct icon"></span>
                                        <div class="about-text">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
                                            molestiae quas vel sint
                                        </div>
                                    </div>

                                    <div class="correct-benefit-row">
                                        <span class="icon-correct icon"></span>
                                        <div class="about-text">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
                                            molestiae quas vel sint
                                        </div>
                                    </div>

                                    <div class="correct-benefit-row">
                                        <span class="icon-correct icon"></span>
                                        <div class="about-text">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
                                            molestiae quas vel sint
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="about-widget-view">
                                <div class="about-widget-bg">
                                    <div class="about-title">Let's see our Experience</div>
                                    <div class="vpr-progressbars">
                                        <!-- Progress Bar Row -->
                                        <div class="progress-bar-row">
                                            <div class="pbr-title title text-capitalize lines-1">Service Quality</div>
                                            <div class="flex-progress flex-between-vh">
                                                <div class="progress width-fluid">
                                                    <div class="progress-bar" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <div class="progress-count-rate ml-3">3.0</div>
                                            </div>
                                        </div>
                                        <!-- Progress Bar Row -->

                                        <!-- Progress Bar Row -->
                                        <div class="progress-bar-row">
                                            <div class="pbr-title title text-capitalize lines-1">Service Time</div>
                                            <div class="flex-progress flex-between-vh">
                                                <div class="progress width-fluid">
                                                    <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <div class="progress-count-rate ml-3">4.0</div>
                                            </div>
                                        </div>
                                        <!-- Progress Bar Row -->

                                        <!-- Progress Bar Row -->
                                        <div class="progress-bar-row">
                                            <div class="pbr-title title text-capitalize lines-1">Accuracy</div>
                                            <div class="flex-progress flex-between-vh">
                                                <div class="progress width-fluid">
                                                    <div class="progress-bar" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <div class="progress-count-rate ml-3">2.0</div>
                                            </div>
                                        </div>
                                        <!-- Progress Bar Row -->
                                        <!-- Progress Bar Row -->
                                        <div class="progress-bar-row">
                                            <div class="pbr-title title text-capitalize lines-1">Communication</div>
                                            <div class="flex-progress flex-between-vh">
                                                <div class="progress width-fluid">
                                                    <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="5"></div>
                                                </div>
                                                <div class="progress-count-rate ml-3">4.0</div>
                                            </div>
                                        </div>
                                        <!-- Progress Bar Row -->

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="about-widget-block">
                            <div class="about-widget-details">
                                <div class="about-sub-title">ABOUT DEFT@</div>
                                <div class="about-title">Top digital solutions for beating the system</div>
                                <div class="about-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
                                    molestiae quas vel sint commodi repudiandae consequuntur voluptatum laborum
                                    numquam blanditiis
                                </div>
                                <div class="about-correct-benefits mb-5">
                                    <div class="correct-benefit-row">
                                        <span class="icon-correct icon"></span>
                                        <div class="about-text">About Company</div>
                                    </div>

                                    <div class="correct-benefit-row">
                                        <span class="icon-correct icon"></span>
                                        <div class="about-text">High Speed Internet</div>
                                    </div>

                                    <div class="correct-benefit-row">
                                        <span class="icon-correct icon"></span>
                                        <div class="about-text">Fully Airconditional rooms</div>
                                    </div>

                                    <div class="correct-benefit-row">
                                        <span class="icon-correct icon"></span>
                                        <div class="about-text">Office boy service</div>
                                    </div>

                                    <div class="correct-benefit-row">
                                        <span class="icon-correct icon"></span>
                                        <div class="about-text">Power Supply</div>
                                    </div>
                                </div>
                                <div class="about-action">
                                    <button class="button-default">Learn More</button>
                                </div>
                            </div>
                            <div class="about-widget-view">
                                <img class="img-fluid" src="{{ asset('assets/img/about/about-2.png') }}" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Newsletter --}}
            <div class="newsletter-container">
                <div class="container">
                    <div class="newsletter-box text-center">
                        <img class="img-fluid" src="{{ asset('assets/img/icons/send.svg') }}" />
                        <div class="about-title">Get Updates From DEFT@</div>
                        <div class="about-text">Sign Up for services, tasks, providers and more...</div>
                        <form>
                            <div class="newsletter-input-group">
                                <input type="text" name="newsletter" placeholder="example@example.com" />
                                <button class="btn btn-bg">Send Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('custom-script')

@endsection
