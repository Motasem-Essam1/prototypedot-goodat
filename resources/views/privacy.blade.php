@extends('layouts.app')
@section('title', 'Privacy Policy')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/privacy.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-with-top">
        <div class="privacy">
            <div class="container">
                <div class="privacy-container">
                    {{-- Privacy Header --}}
                    <div class="privacy-header text-center">
                        <div class="big-title">Privacy Policy</div>
                        <div class="date">Last Updated May 23rd, 2023.</div>
                    </div>

                    {{-- Privacy Content --}}
                    <div class="privacy-content">
                        <div class="privacy-title mb-4">This Privacy Policy will help you better understand how we collect,<br /> use, and share your personal information.</div>
                        <div class="privacy-row">
                            <div class="privacy-title mb-2">Privacy Policy</div>
                            <div class="privacy-text">
                                This privacy policy sets out how Creative Layer Inc. (“Creative Layer”, “we”, “us”, or “our”, and also doing business and “remx”) collects, uses, and discloses, any personal information that you give us or that we collect when you use our website or Services. Creative Layer offers a platform that allows artists, brands and us to mint and sell NFTs and tokens and for purchasers to mint and buy NFTs (“Services”). By using our website or Services, or by choosing to give us personal information, you consent to this Privacy Policy and the processing of your Personal Information it describes. If you do not agree with any terms of this Privacy Policy, please exercise the choices we describe in this Policy, or do not use the Services and do not give us any personal information. Creative Layer may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes. Your continued access to and/or use of our website or Services after any such changes constitutes your acceptance of, and agreement to this Privacy Policy, as revised.
                            </div>
                        </div>

                        {{-- First Row --}}
                        <div class="privacy-row">
                            <div class="privacy-title mb-2">Privacy Summary</div>
                            <div class="privacy-text">
                                <span class="privacy-sub-title">1. What Personal Information we collect.</span> To register for our Services, you provide your public wallet address. If you make or buy an NFT, we collect information about your transaction, such as public wallet address, date and time, value, token ID, and transaction history. You may also choose to provide your email address, profile information such as name, social media handles, website, banner and profile picture, as well as other information you choose. We may also collect information to meet our anti-money laundering and ‘know your client’ obligations.
                            </div>
                        </div>

                        <div class="privacy-row">
                            <div class="privacy-text">
                                <span class="privacy-sub-title">2. What we do with the Personal Information we collect.</span> We use the information we collect to provide our Services, including to allow you to register, make and buy NFTs, for record keeping, to improve our Services, to communicate with and respond to you, to manage, administer, maintain, service, collect and enforce our Terms and Conditions, to investigate and settle disputes, to investigate breaches, and for legal purposes, including anti-money laundering and ‘know your client’ requirements.
                            </div>
                        </div>

                        <div class="privacy-row">
                            <div class="privacy-text">
                                <span class="privacy-sub-title">3. When we Disclose Personal Information</span> When you buy an NFT, your public wallet address, the date and time, the transaction value, and your token ID is recorded on the public block chain. We also share information with service providers, if we enter a business transaction, or for legal purposes.
                            </div>
                        </div>

                        <div class="privacy-row">
                            <div class="privacy-text">
                                <span class="privacy-sub-title">4. How we use cookies and collect information using technology</span> We use technologies such as cookies to collect IP address or device ID, operating system, browser type, username, the pages of our website that you visit, the time spent on those pages, access dates and times, clicks, links used, and actions you take on our site, errors you encounter, and general location derived from IP address. We use this to understand how our Services are used and improve them.
                            </div>
                        </div>

                        <div class="privacy-row">
                            <div class="privacy-text">
                                <ul>
                                    <li>When you join our mailing list, we collect your email address. You are not required to join our mailing list to use our Services. If you wish, you may create a new email address for this purpose, and can choose not to include any identifiable information such as your name in it,</li>
                                    <li>If you make an account to use our Services, for example, to make a purchase of a token, or mint an NFT, we collect your public wallet address, and whether you are using the Services as an artist, or purchaser, or both,</li>
                                    <li>If you choose to create a profile, we collect your username, email address, Twitter handle, Instagram handle, other social media handles you choose to provide, website address, and if you upload one, your banner and profile picture. Providing this information is optional,</li>
                                    <li>If you use the Services to create NFTs, information about the NFTs you create, and the transactions you enter, including your public wallet address, date and time of transaction, transaction value, token ID,</li>
                                    <li>If you purchase NFTs using the Services, information about your transactions (amounts, dates, NFTs purchased, etc.), and your transaction history through the Services,</li>
                                    <li> If required by applicable anti-money laundering and ‘know your client’ rules, we may collect information such as your name, address and phone number, or other information required or reasonable for the purpose of such rules, and</li>
                                    <li>Other information such as your communications with us, surveys, and/or other information that you choose to provide to us.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('custom-script')

@endsection
