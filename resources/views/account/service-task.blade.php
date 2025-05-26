@extends('layouts.app')
@section('title', 'My Service / Task')
@section('main-style')
{{--    <link rel="stylesheet" href="{{ asset('assets/css/telephone-validation.css') }}"/>--}}
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/pages/profile.css') }}"/>
@endsection
@section('content')
    <!-- Modal Delete -->
    <div class="modal fade" id="deleteServiceTaskModal" tabindex="-1" aria-labelledby="deleteServiceTaskLabel"
         aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteServiceTaskLabel">Delete <span id="deleteType" class="text-capitalize"></span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure to delete <span id="itemTitle"></span> ?
                </div>
                <div class="modal-footer modal-footer-unround">
                    <button type="button" class="btn button-default" data-bs-dismiss="modal">Close</button>
                    <button id="deleteServiceTaskBtn" type="button" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Delete -->

    <div class="wrapper-container wrapper-with-top">
        <div class="profile-container">
            <div class="container">
                <div class="profile-wrapper">
                    <div class="profile-flexable flex-between-h">
                        <!-- Sidebar -->
                        <div class="profile-sidebar">
                            @include('account.sidebar')
                        </div>
                        <!-- Sidebar -->

                        <!-- Content -->
                        <div class="profile-content">
                            <!-- Services & Tasks -->
                            <div data-tab-profile="#myServicesTasks" class="pc-tab active">
                                <div class="pct-bg">
                                    <div class="flex-between-h">
                                        <div>
                                            <div class="pc-title title">MY SERVICES AND TASKS</div>
                                            <div class="pc-sub-title mb-3">You have total {{ count($service) }} Services
                                                & {{ count($tasks) }} Tasks.
                                            </div>
                                        </div>
                                        <div class="add-service-task">
                                            @if(Auth::user()->user_data->user_type == "Normal")
                                                <a href="{{ route('task.add.view') }}" class="btn btn-bg">
                                                    <span class="icon-plus icon pl-2 pr-2"></span>
                                                    <span class="ast-text">Add</span>
                                                </a>
                                            @else
                                                <button class="btn btn-bg" data-toggle="modal"
                                                        data-target="#servicesTasksModal">
                                                    <span class="icon-plus icon pl-2 pr-2"></span>
                                                    <span class="ast-text">Add</span>
                                                </button>
                                            @endif
                                            {{--                                            <a href="{{ route('task.add.view') }}" class="btn btn-bg">--}}
                                            {{--                                                <span class="icon-plus icon pl-2 pr-2"></span>--}}
                                            {{--                                                <span class="ast-text">Add</span>--}}
                                            {{--                                            </a>--}}
                                        </div>
                                    </div>
                                    <div class="pc-main-tabs-actions d-inline-block">
                                        <button id="stServices" class="pcmta-btn active mr-2">Services</button>
                                        <button id="stTasks" class="pcmta-btn">Tasks</button>
                                    </div>
                                </div>

                                <div data-tab-profile="#stServices" class="pcf-container active">
                                    @if($service->isEmpty())
                                        <div class="empty-data-container">
                                            <div class="text-center">
                                                <img class="empty-data-img img-fluid"
                                                     src="{{ asset('assets/img/icons/empty-data.svg') }}"/>
                                                <div class="empty-data-title text-center">There Are No Services Yet
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="pstc-grid-cols">
                                            <!-- Row -->

                                            <!-- Row -->
                                            @foreach($service as $item)
                                                <div class="pstc-row">
                                                    <div class="dropdown dropdown-options">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa-solid fa-ellipsis"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                   href="{{ route('update.service', $item->id) }}"><i
                                                                        class="fa-solid fa-pen-to-square icon"></i> Edit</a>
                                                            </li>
                                                            {{-- <li><a class="dropdown-item" href="{{ route('delete.service', $item->id) }}"><i class="fa-solid fa-trash icon"></i> Delete</a></li>--}}
                                                            <li>
                                                                <button class="dropdown-item delete-service-task" data-type="service" data-id="{{ $item->id }}" data-title="{{ $item->service_name }}">
                                                                    <i class="fa-solid fa-trash icon"></i> Delete
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="tasks-user-view my-tasks-main-img">
                                                        <a href="{{ route('service.view', $item->service_slug) }}">
                                                            <img class="img-fluid" src="{{ count($item->images) > 0 ? $item->images[0]->image_path : asset('assets/img/default/default_no_image.jpg') }}" alt="">
                                                        </a>
                                                    </div>
                                                    <a href="{{ route('service.view', $item->service_slug) }}">
                                                        <div
                                                            class="title mb-2 text-capitalize lines-1">{{ $item->service_name }}</div>
                                                    </a>
                                                    <div class="tasks-price">Start From
                                                        {{$configurations['value']}}{{ $item->starting_price }}</div>
                                                    <div
                                                        class="tasks-type text-capitalize">{{ $item->category->sub_category_name }}</div>
                                                    @if($item->is_active)
                                                        <div class="status-text status-published mt-3">Published</div>
                                                    @else
                                                        <div class="status-text status-approved mt-3">Waiting For
                                                            Approve
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div data-tab-profile="#stTasks" class="pcf-container">
                                    @if($tasks->isEmpty())
                                        <div class="empty-data-container">
                                            <div class="text-center">
                                                <img class="empty-data-img img-fluid"
                                                     src="{{ asset('assets/img/icons/empty-data.svg') }}"/>
                                                <div class="empty-data-title text-center">There Are No Tasks Yet</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="pstc-grid-cols">
                                            @foreach($tasks as $item)
                                                <div class="pstc-row">
                                                    <div class="dropdown dropdown-options">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa-solid fa-ellipsis"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                   href="{{ route('update.task', $item->id) }}"><i
                                                                        class="fa-solid fa-pen-to-square icon"></i> Edit</a>
                                                            </li>
                                                            {{-- <li><a class="dropdown-item" href="{{ route('delete.task', $item->id) }}"><i class="fa-solid fa-trash icon"></i> Delete</a></li>--}}
                                                            <li>
                                                                <button class="dropdown-item delete-service-task" data-type="task" data-id="{{ $item->id }}" data-title="{{ $item->task_name }}">
                                                                    <i class="fa-solid fa-trash icon"></i> Delete
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <a href="{{ route('task.view', $item->task_slug) }}">
                                                        <div class="tasks-user-view">
                                                            <img class="img-fluid" src="{{ count($item->images) > 0 ? $item->images[0]->image_path : asset('assets/img/default/default_no_image.jpg') }}" alt="">
                                                        </div>
                                                    </a>
                                                    <a href="{{ route('task.view', $item->task_slug) }}">
                                                        <div
                                                            class="title mb-2 text-capitalize lines-1">{{ $item->task_name }}</div>
                                                    </a>
                                                    <div class="tasks-price">Start From
                                                        {{$configurations['value']}}{{ $item->starting_price }}</div>
                                                    <div
                                                        class="tasks-type text-capitalize">{{ $item->category->sub_category_name }}</div>
                                                    @if($item->is_active)
                                                        <div class="status-text status-published mt-3">Published</div>
                                                    @else
                                                        <div class="status-text status-approved mt-3">Waiting For
                                                            Approve
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>


                            </div>
                            <!-- Services & Tasks -->
                        </div>
                        <!-- Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        // Tabs
        $(function () {
            $(".pcmta-btn").on('click', function () {
                var id = $(this).attr("id");
                $(this).addClass('active active-res').siblings().removeClass("active active-res");
                $(`.pcf-container[data-tab-profile="#${id}"]`).addClass("active active-res").siblings().removeClass("active active-res");
            });
        });

        // Handle delete service & task
        $(function () {
            let itemType    = null;
            let itemId      = null;
            let itemTitle   = null;

            // Open modal
            $(".delete-service-task").on('click', function () {
                itemType    = $(this).data('type');
                itemId      = $(this).data('id');
                itemTitle   = $(this).data('title');

                $("#deleteType").text(itemType);
                $("#itemTitle").text(itemTitle);
                $('#deleteServiceTaskModal').modal('show');
            });

            // Delete service & task
            $('#deleteServiceTaskBtn').on('click', function (e) {
                let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                let url = null;
                if (itemType == 'service') {
                    url = `delete-service/${itemId}`
                } else {
                    url = `delete-task/${itemId}`
                }

                $('#deleteServiceTaskModal').modal('hide');

                $.ajax({
                    url,
                    type: "POST",
                    data: {
                        _token: CSRF_TOKEN,
                    },
                    beforeSend: function() {

                    },
                    success: function (data) {
                        window.location.href = "{{URL::to('account/service-task')}}"

                    },
                    error: function(data) {

                    },
                });
            });
        });
    </script>
@endsection
