@extends('layouts.app')
@section('title', 'About')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/notifications.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-with-top">
        <div class="notify-page">
            <div class="container">
                <div class="notify-container">
                    <div class="ns-title cos-title"><span>Updates @if($is_read > 0) <span>({{ $is_read }})</span> @endif</span></div>
                    <div class="noti-users">
                        @if(count($data) > 0)
                            @foreach($data as $row)
                                @php
                                    $idOrSlug = '';
                                    $itemName = '';
                                    if ($row->item_type == 'provider') {
                                        $idOrSlug = $row->item_id;
                                        $itemName = $row->item ? $row->item->name : "";
                                    } elseif ($row->item_type == 'service') {
                                        $idOrSlug = $row->item ? $row->item->service_slug : "";
                                        $itemName = $row->item ? $row->item->service_name : "";
                                    } elseif ($row->item_type == 'task') {
                                        $idOrSlug = $row->item ? $row->item->task_slug : "";
                                        $itemName = $row->item ? $row->item->task_name : "";
                                    }
                                @endphp

                                <div onclick='notify("{{ $row->id }}", "{{ $row->item_type }}", "{{ $idOrSlug }}")' class="noti-user-row flex-center-v">
{{--                                    {{ $row->is_read == 0 ? 'active' : '' }}--}}
                                    <div class="nur-view">
                                        <img class="img-fluid" src="{{ $row->user->user_data->avatar }}" alt="{{ $row->user->name }}" />
                                        @if($row->action_type == 'favorite')
                                            <div class='notify-action-type'>
                                                <i class='fa-solid fa-heart icon'></i>
                                            </div>
                                        @elseif($row->action_type == 'review')
                                            <div class='notify-action-type'>
                                                <i class='fa-solid fa-star icon'></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="nsr-details">
                                        <div class="nsr-title text-capitalize flex-between-vh">
                                            <span class="nsr-name text-capitalize max-1-line">{{ $row->user->name }}</span>
                                            <span class="nsr-date">{{ $row->created_at }}
                                                @if($row->is_read == 0)
                                                    <span><span class="notify-bullet"></span></span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="nsr-text lines-2">{{ $row->message }} <b class="text-capitalize">({{ $itemName }})</b></div>
                                    </div>
                                </div>

                            @endforeach
                        @else
                            <div class="empty-noty d-flex justify-content-center">There are no notifications yet.</div>
                        @endif
                    </div>

                    <!-- Pagination -->
                    @if ($data->hasPages())
                        <div class="profile-pagination main-pagination mt-5 pb-2">
                            <div aria-label="Page navigation">
                                {{ $data->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                    <!-- Pagination -->
                </div>
            </div>
        </div>
    </div>
@endsection


@section('custom-script')

@endsection
