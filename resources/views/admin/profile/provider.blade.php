@extends('admin.app')
@section('title', __('messages.Profile'))
@section('css')
    <style>
        .badge-light-success {
            color: var(--bs-success) !important;
            background-color: var(--bs-success-light) !important;
        }

        .badge-light-warning {
            color: var(--bs-warning) !important;
            background-color: var(--bs-warning-light) !important;
        }

        .badge-light-secondary {
            color: var(--bs-secondary) !important;
            background-color: var(--bs-secondary-light) !important;
        }

        .badge-light-danger {
            color: var(--bs-danger) !important;
            background-color: var(--bs-danger-light);
        }
    </style>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .inline-block {
            display: inline-block;
        }
    </style>
@endsection
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div class="container">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show " role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show " role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show " role="alert">
                        {{ $error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endforeach
            @endif

        </div>

        @if ($user->blocked == 1)
            <div class="container">

                <div class="alert alert-primary " role="alert">
                    <h4 class="alert-heading">
                        <svg style="height: 50px;" xmlns="http://www.w3.org/2000/svg"
                            class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img"
                            aria-label="Warning:">
                            <path
                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                        {{ __('messages.Profile_blocked') }}
                    </h4>
                    <p class="mb-0">{{ __('messages.This_Profile_Blocked_by_admin') }}</p>
                </div>
            </div>
        @endif

        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        {{ __('messages.Profile') }}</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="../../demo1/dist/index.html"
                                class="text-muted text-hover-primary">{{ __('messages.Pages') }}</a>
                        </li>

                        <li class="breadcrumb-item text-muted">{{ __('messages.Profile') }}</li>
                    </ul>
                </div>

            </div>
        </div>
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!--begin::Navbar-->
                <div class="card mb-5 mb-xxl-8">
                    <div class="card-body pt-9 pb-0">
                        <!--begin::Details-->
                        <div class="d-flex flex-wrap flex-sm-nowrap">
                            <!--begin: Pic-->
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <img src="/{{ $user->image }}" alt="image" />
                                    <div
                                        class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                                    </div>
                                </div>
                            </div>
                            <!--end::Pic-->
                            <!--begin::Info-->
                            <div class="flex-grow-1">
                                <!--begin::Title-->
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <!--begin::User-->
                                    <div class="d-flex flex-column">
                                        <!--begin::Name-->
                                        <div class="d-flex align-items-center mb-2">
                                            <a href="#"
                                                class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $user->name }}</a>
                                            <a href="#">
                                                <i class="ki-duotone ki-verify fs-1 text-primary">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </a>
                                        </div>
                                        <!--end::Name-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <a href="#"
                                                class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                                <i class="ki-duotone ki-profile-circle fs-4 me-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>{{ $user->roles->first()->name }}</a>
                                            <a href="#"
                                                class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                                <i class="ki-duotone ki-geolocation fs-4 me-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>{{ $user->phone }}</a>
                                            <a href="#"
                                                class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                                <i class="ki-duotone ki-sms fs-4 me-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>{{ $user->email }}</a>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::User-->
                                    <!--begin::Actions-->
                                    <div class="d-flex my-4">
                                        <a href="#" class="btn   btn-primary mx-2" data-bs-toggle="modal"
                                            data-bs-target="#add-log">{{ __('messages.Add_log') }}</a>
                                        @can('Add Assign')
                                            <a href="#" class="btn   btn-primary mx-2" data-bs-toggle="modal"
                                                data-bs-target="#assign-user">{{ __('messages.Assign') }}</a>
                                        @endcan
                                        @php 
                                        $confirm_message=   $user->blocked == 1 ?  __('messages.Are_you_sure_you_want_to_unban_this_account') : __('messages.Are_you_sure_you_want_to_ban_this_account')
                                        @endphp 
                                        <button class="btn btn-primary mx-2" data-bs-toggle="modal"
                                            data-bs-target="#change-password">{{ __('messages.Change_password') }}</button>
                                        <form action="{{ route('admin.block_user', $user->id) }}" onsubmit="return confirm('{{ __('messages.Are_you_sure_you_want_to_ban_this_account') }}');" method="post">
                                            @csrf
                                            <input type="hidden" value="{{ $user->id }}" name="user_id">
                                            <button type="submit"
                                                class="btn  {{ $user->blocked == 1 ? 'btn-success' : 'btn-danger' }} me-3">
                                                {{ $user->blocked == 1 ? __('messages.Un_block') : __('messages.Block') }}
                                            </button>
                                        </form>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Title-->
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap flex-stack">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-column flex-grow-1 pe-8">
                                        <!--begin::Stats-->
                                        <div class="d-flex flex-wrap">

                                            <div
                                                class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="fs-2 fw-bold" data-kt-countup="true"
                                                        data-kt-countup-value="{{ $user->blance }}"
                                                        data-kt-countup-prefix=" EGP ">{{ $user->blance }}</div>
                                                </div>
                                                <div class="fw-semibold fs-6 text-gray-400">
                                                    {{ __('messages.Available_balance') }}</div>
                                            </div>

                                            <div
                                                class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="fs-2 fw-bold" data-kt-countup="true"
                                                        data-kt-countup-value="{{ $total_services }}">0</div>
                                                </div>
                                                <div class="fw-semibold fs-6 text-gray-400">{{ __('messages.Services') }}
                                                </div>
                                            </div>



                                        </div>
                                        <div class="d-flex flex-wrap">
                                            <div
                                                class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="fs-2 fw-bold" data-kt-countup="true"
                                                        data-kt-countup-value="{{ $total_approved_requests }}">
                                                        {{ $total_approved_requests }}</div>
                                                </div>
                                                <div class="fw-semibold fs-6 text-gray-400">
                                                    {{ __('messages.Booking_accepted') }}</div>
                                            </div>
                                            <div
                                                class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="fs-2 fw-bold" data-kt-countup="true"
                                                        data-kt-countup-value="{{ $total_rejected_requests }}">
                                                        {{ $total_rejected_requests }}</div>
                                                </div>
                                                <div class="fw-semibold fs-6 text-gray-400">
                                                    {{ __('messages.Booking_rejected') }}</div>
                                            </div>
                                            <div
                                                class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="fs-2 fw-bold" data-kt-countup="true"
                                                        data-kt-countup-value="{{ $total_cancelled_requests }}">
                                                        {{ $total_cancelled_requests }}</div>
                                                </div>
                                                <div class="fw-semibold fs-6 text-gray-400">{{ __('messages.Cancelled') }}
                                                </div>
                                            </div>
                                            <div
                                                class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="fs-2 fw-bold" data-kt-countup="true"
                                                        data-kt-countup-value="{{ $total_overview_time }}">
                                                        {{ $total_overview_time }}</div>
                                                </div>
                                                <div class="fw-semibold fs-6 text-gray-400">
                                                    {{ __('messages.Overview_time') }}</div>
                                            </div>
                                            <div
                                                class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="fs-2 fw-bold" data-kt-countup="true"
                                                        data-kt-countup-value="{{ $total_overview_time_payment }}">
                                                        {{ $total_overview_time_payment }}</div>
                                                </div>
                                                <div class="fw-semibold fs-6 text-gray-400">
                                                    {{ __('messages.Overview_time_payment') }}</div>
                                            </div>
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Details-->

                    </div>
                </div>

            </div>
        </div>
        <!--begin:: Sectons-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" style="min-height: 70vh;" class="app-container container-xxl">
                <div class="card card-flush container">
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin:::Tabs-->
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-15"
                            role="tablist">
                            <!--begin:::Tab item-->
                            @can('show_calendar')
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-active-primary d-flex align-items-center pb-5 {{ $tab == null ? 'active' : ' ' }} "
                                        data-bs-toggle="tab" href="#kt_calendar" aria-selected="true" role="tab">
                                        <!-- <i class="ki-duotone ki-home fs-2 me-2"></i> -->
                                        {{ __('messages.Calendar') }}
                                    </a>
                                </li>
                            @endcan
                            @can('show_services')
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-active-primary d-flex align-items-center pb-5 "
                                        data-bs-toggle="tab" href="#kt_ecommerce_settings_general" aria-selected="true"
                                        role="tab">
                                        <!-- <i class="ki-duotone ki-home fs-2 me-2"></i> -->
                                        {{ __('messages.Services') }}
                                    </a>
                                </li>
                            @endcan
                            @can('show_booking')
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-active-primary d-flex align-items-center pb-5  {{ $tab == 'booking' ? 'active' : ' ' }}"
                                        data-bs-toggle="tab" href="#kt_orders" aria-selected="true" role="tab">
                                        <!-- <i class="ki-duotone ki-home fs-2 me-2"></i> -->
                                        {{ __('messages.Booking') }}
                                    </a>
                                </li>
                            @endcan
                            @can('show_rating')
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-active-primary d-flex align-items-center pb-5 "
                                        data-bs-toggle="tab" href="#kt_rating" aria-selected="true" role="tab">
                                        <!-- <i class="ki-duotone ki-home fs-2 me-2"></i> -->
                                        {{ __('messages.Rating') }}
                                    </a>
                                </li>
                            @endcan
                            @can('Show Commission')
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-active-primary d-flex align-items-center pb-5 "
                                        data-bs-toggle="tab" href="#kt_settings" aria-selected="true" role="tab">
                                        <!-- <i class="ki-duotone ki-home fs-2 me-2"></i> -->
                                        {{ __('messages.Commission') }}
                                    </a>
                                </li>
                            @endcan

                            @can('show_overview_time')
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-active-primary d-flex align-items-center pb-5 "
                                        data-bs-toggle="tab" href="#overview_time" aria-selected="true" role="tab">
                                        <!-- <i class="ki-duotone ki-home fs-2 me-2"></i> -->
                                        {{ __('messages.Overview_time') }}
                                    </a>
                                </li>
                            @endcan
                            @can('show_wallet')
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-active-primary d-flex align-items-center pb-5 "
                                        data-bs-toggle="tab" href="#kt_wallet" aria-selected="true" role="tab">
                                        <!-- <i class="ki-duotone ki-home fs-2 me-2"></i> -->
                                        {{ __('messages.Wallet') }}
                                    </a>
                                </li>
                            @endcan
                            @can('show_logs')
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-active-primary d-flex align-items-center pb-5 "
                                        data-bs-toggle="tab" href="#logs" aria-selected="true" role="tab">
                                        <!-- <i class="ki-duotone ki-home fs-2 me-2"></i> -->
                                        {{ __('messages.Logs') }}
                                    </a>
                                </li>
                            @endcan
                            @can('show_packages')
                                <li class="nav-item" role="packages">
                                    <a class="nav-link text-active-primary d-flex align-items-center pb-5 "
                                        data-bs-toggle="tab" href="#packages" aria-selected="true" role="tab">
                                        {{ __('messages.Packages') }}
                                    </a>
                                </li>
                            @endcan
                        </ul>
                        <!--end:::Tabs-->

                        <!--begin:::Tab content-->
                        <div class="tab-content" id="myTabContent" data-select2-id="select2-data-myTabContent">
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show {{ $tab == null ? 'active' : ' ' }}" id="kt_calendar"
                                role="tabpanel" data-select2-id="select2-data-kt_calendar">

                                @include('admin.layouts.calendar')

                            </div>
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show " id="kt_ecommerce_settings_general" role="tabpanel"
                                data-select2-id="select2-data-kt_ecommerce_settings_general">

                                <table class="table align-middle gs-0 gy-4">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="ps-4 min-w-150px rounded-start">{{ __('messages.Name') }}</th>
                                            <th class="ps-4 min-w-100px rounded-start">{{ __('messages.Category_name') }}
                                            </th>
                                            <th class="min-w-100px">{{ __('messages.Image') }}</th>
                                            <th class="min-w-100px">{{ __('messages.Amount') }}</th>
                                            <!-- <th class="min-w-100px">{{ __('messages.Avaliable') }}</th> -->
                                            <th class="min-w-100x">{{ __('messages.Accept') }}</th>
                                            <th class="min-w-200px text-center">{{ __('messages.Actions') }}</th>

                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        @foreach ($services as $service)
                                            <tr>

                                                <td>
                                                    <form method="get" action="{{ route('admin.booking') }}">
                                                        <!-- Other form fields -->
                                                        <input type="hidden" name="service_id"
                                                            value="{{ $service->id }}">
                                                        <button type="submit"
                                                            class="  btn text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ session('lang') == 'en' ? $service->name : $service->name_ar }}</button>
                                                    </form>
                                                </td>

                                                <td>
                                                    <a
                                                        class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ session('lang') == 'en' ? $service->category->brand_name : $service->category->brand_name_ar }}</a>
                                                </td>
                                                <td>
                                                    <img src="/{{ $service->image }}" style="height: 60px;"
                                                        alt="">
                                                </td>
                                                <td>
                                                    {{ $service->price_with_commission }}
                                                </td>
                                                <!-- <td>
               @if ($service->available == 1)
    <a href="{{ route('admin.service.change_available_status', $service->id) }}" class="badge badge-light-success">{{ __('messages.Yes') }}</a>
@else
    <a href="{{ route('admin.service.change_available_status', $service->id) }}" class="badge badge-light-danger">{{ __('messages.No') }} </a>
    @endif

              </td> -->
                                                <td>
                                                    @if ($service->accept == 1)
                                                        <a href="{{ route('admin.service.change_accept_status', $service->id) }}"
                                                            class="badge badge-light-success">{{ __('messages.Yes') }}</a>
                                                    @else
                                                        <a href="{{ route('admin.service.change_accept_status', $service->id) }}"
                                                            class="badge badge-light-danger">{{ __('messages.No') }}</a>
                                                    @endif

                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.services.edit', $service->id) }}"
                                                        class="btn btn-bg-light btn-color-muted btn-active-color-primary  px-4">{{ __('messages.Manage') }}</a>
                                                    <!-- <form style="display: inline-block;" action="{{ route('admin.services.destroy', $service->id) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit"  class="btn btn-bg-light btn-color-muted btn-active-color-danger  px-4">Delete</button>
           </form> -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <!--end::Table body-->
                                </table>


                            </div>
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show {{ $tab == 'booking' ? 'active' : ' ' }}" id="kt_orders"
                                role="tabpanel" data-select2-id="select2-data-kt_orders">
                                <div class="my-2 row" style="    align-items: center;">
                                    <div class="  col-lg-2 col-md-4 col-sm-4 p-1">
                                        <button data-bs-toggle="modal" data-bs-target="#add-booking"
                                            class="w-100 btn  fw-bold btn-primary m-0" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_create_app">{{ __('messages.Add_booking') }}</button>
                                    </div>
                                    <div class="col-lg-2 col-md-4 my-2 col-sm-4 p-1">
                                        <form action="{{ route('admin.export.booking') }}" method="get">
                                            @if (request()->query('date'))
                                                <input type="hidden" name="date"
                                                    value="{{ request()->query('date') }}">
                                            @endif
                                            @if (request()->query('from') && request()->query('to'))
                                                <input type="hidden" name="from"
                                                    value="{{ request()->query('from') }}">
                                                <input type="hidden" name="to"
                                                    value="{{ request()->query('to') }}">
                                            @endif
                                            <button
                                                class="w-100 btn  fw-bold btn-primary m-0 ">{{ __('messages.Export_excel') }}</button>
                                        </form>
                                    </div>
                                    <form id="filterForm" class="   my-2 col-lg-2 col-md-4 col-sm-4 p-1"
                                        action="{{ route('admin.profile', $user->id) }}" method="GET">
                                        <input type="hidden" name="role" value="{{ request()->query('role') }}">
                                        <select name="date" id="dateFilter" class="form-select w-100"
                                            onchange="this.form.submit()">
                                            <option value="all" {{ !request()->query('date') ? 'selected' : ' ' }}>
                                                {{ __('messages.All') }}</option>
                                            <option value="daily"
                                                {{ request()->query('date') == 'daily' ? 'selected' : ' ' }}>
                                                {{ __('messages.Daily') }}</option>
                                            <option value="weekly"
                                                {{ request()->query('date') == 'weekly' ? 'selected' : ' ' }}>
                                                {{ __('messages.Weekly') }}</option>
                                            <option value="monthly"
                                                {{ request()->query('date') == 'monthly' ? 'selected' : ' ' }}>
                                                {{ __('messages.Monthly') }}</option>
                                        </select>
                                    </form>

                                    <form id="dateBetweenFilter" action="{{ route('admin.profile', $user->id) }}"
                                        class="  my-2 col-lg-6 col-md-12 row p-1">
                                        <div class="col-lg-6 col-sm-12 row my-2" style="align-items: center;">
                                            <label for="from  p-0 " class="form-label text-center"
                                                style="    display: inline;width: 25% !important;">{{ __('messages.From') }}</label>
                                            <input onchange="dateFilter()" type="date" class="form-control "
                                                id="from" name="from"
                                                style="    display: inline;width: 75% !important;">
                                        </div>
                                        <div class="col-lg-6 col-sm-12 row my-2" style="align-items: center;">
                                            <label for="to" class="form-label text-center p-0"
                                                style="    display: inline;width: 25% !important;">{{ __('messages.To') }}</label>
                                            <input type="date" onchange="dateFilter()" class="form-control"
                                                id="to" style="    display: inline;width: 75% !important;"
                                                name="to" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </form>


                                </div>
                                <table class="table align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">

                                            <th class=" px-4 py-3 sorting_disabled" rowspan="1" colspan="1"
                                                style="width: 20px;" aria-label="Vendor">
                                                #
                                            </th>
                                            <th class=" px-4 py-3 sorting_disabled" rowspan="1" colspan="1"
                                                style="width: 40px;" aria-label="Vendor">
                                                {{ __('messages.Service_name') }}
                                            </th>
                                            <th class=" px-4 py-3 sorting_disabled" rowspan="1" colspan="1"
                                                style="width: 40px;" aria-label="Vendor">
                                                {{ __('messages.Provider_name') }}
                                            </th>
                                            <th class=" px-4 py-3 sorting" tabindex="0" aria-controls="order-table"
                                                rowspan="1" colspan="1" style="width: 50px;"
                                                aria-label="Payment Status: activate to sort column ascending">
                                                {{ __('messages.Payment_status') }}
                                            </th>
                                            <th class="text-start sorting" tabindex="0" aria-controls="order-table"
                                                rowspan="1" colspan="1" style="width: 37.25px;"
                                                aria-label="Total: activate to sort column ascending">
                                                {{ __('messages.Total') }}
                                            </th>
                                            <th class="text-start sorting" tabindex="0" aria-controls="order-table"
                                                rowspan="1" colspan="1" style="width: 40px;"
                                                aria-label="Orders Status: activate to sort column ascending">
                                                {{ __('messages.Status') }}
                                            </th>
                                            <th class=" px-4 py-3 sorting_disabled" rowspan="1" colspan="1"
                                                style="width: 70px;" aria-label="Actions">
                                                {{ __('messages.Actions') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @if (count($orders) > 0)
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>

                                                        <a
                                                            href="{{ route('admin.booking.show', $order->id) }}">{{ $order->id }}</a>

                                                    </td>
                                                    <td>{{ session('lang') == 'en' ? $order->service->name : $order->service->name_ar }}
                                                    </td>
                                                    <td>{{ $order->provider->name }}</td>
                                                    <td>
                                                        @if ($order->payment_status_id == 1)
                                                            <span
                                                                class="badge badge-light-danger">{{ __('messages.Un_paid') }}</span>
                                                        @elseif($order->payment_status_id == 2)
                                                            <span
                                                                class="badge badge-light-warning">{{ __('messages.Pending') }}</span>
                                                        @elseif($order->payment_status_id == 3)
                                                            <span
                                                                class="badge badge-light-success">{{ __('messages.Paid') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $order->total_amount }}</td>
                                                    <td>
                                                        @if ($order->booking_status_id == 1)
                                                            <span
                                                                class="badge badge-light-warning">{{ __('messages.Pending') }}</span>
                                                        @elseif($order->booking_status_id == 3)
                                                            <span
                                                                class="badge badge-light-success">{{ __('messages.Approved') }}</span>
                                                        @elseif($order->booking_status_id == 4)
                                                            <span
                                                                class="badge badge-light-danger">{{ __('messages.Rejected') }}</span>
                                                        @elseif($order->booking_status_id == 5)
                                                            <span
                                                                class="badge badge-light-danger">{{ __('messages.Cancelled') }}</span>
                                                        @elseif($order->booking_status_id == 6)
                                                            <span
                                                                class="badge badge-light-warning">{{ __('messages.Overview_time') }}</span>
                                                        @elseif($order->booking_status_id == 5)
                                                            <span
                                                                class="badge badge-light-warning">{{ __('messages.Overview_time_payment') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($order->booking_status_id != 5)
                                                            @can('change booking status')
                                                                <div id='kt_menu_64b77630f13b911{{ $order->id }}'>

                                                                    <form method="POST"
                                                                        action="{{ route('admin.booking.change_booking_status') }}"
                                                                        id="auto-submit-form-booking{{ $order->id }}">
                                                                        @csrf <!-- Laravel CSRF protection -->
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $order->id }}">
                                                                        <select
                                                                            onchange="document.getElementById('auto-submit-form-booking{{ $order->id }}').submit();"
                                                                            name="booking_status"
                                                                            class="form-select form-select-solid"
                                                                            data-kt-select2="true"
                                                                            data-close-on-select="false"
                                                                            data-placeholder="{{ __('messages.Booking_status') }}"
                                                                            data-dropdown-parent="#kt_menu_64b77630f13b911{{ $order->id }}"
                                                                            data-allow-clear="true">
                                                                            <option selected disabled>
                                                                                {{ __('messages.Booking_status') }}</option>
                                                                            <option value="1">
                                                                                {{ __('messages.Pending') }}</option>
                                                                            <!-- <option value="2">{{ __('messages.In_process') }}</option> -->
                                                                            <option value="3">
                                                                                {{ __('messages.Approved') }}</option>
                                                                            <option value="4">
                                                                                {{ __('messages.Rejected') }}</option>
                                                                        </select>
                                                                    </form>
                                                                </div>
                                                            @endcan
                                                            @can('change payment status')
                                                                <div id="kt_menu_64b77630f13b912{{ $order->id }}">

                                                                    <form method="POST"
                                                                        action="{{ route('admin.booking.change_payment_status') }}"
                                                                        id="auto-submit-form-payment{{ $order->id }}">
                                                                        @csrf <!-- Laravel CSRF protection -->
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $order->id }}">
                                                                        <select
                                                                            onchange="document.getElementById('auto-submit-form-payment{{ $order->id }}').submit();"
                                                                            name="payment_status"
                                                                            class="form-select form-select-solid my-2"
                                                                            data-kt-select2="true"
                                                                            data-close-on-select="false"
                                                                            data-placeholder="{{ __('messages.Payment_status') }}"
                                                                            data-dropdown-parent="#kt_menu_64b77630f13b912{{ $order->id }}"
                                                                            data-allow-clear="true">
                                                                            <option selected disabled>
                                                                                {{ __('messages.Payment_status') }}</option>
                                                                            <option value="1">
                                                                                {{ __('messages.Un_paid') }}</option>
                                                                            <option value="2">
                                                                                {{ __('messages.Pending') }}</option>
                                                                            <option value="3">{{ __('messages.Paid') }}
                                                                            </option>

                                                                        </select>
                                                                    </form>
                                                                </div>
                                                            @endcan
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="odd">
                                                <td valign="top" colspan="6" class="dataTables_empty text-center">No
                                                    data available in table</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>


                            </div>
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show " id="kt_rating" role="tabpanel"
                                data-select2-id="select2-data-kt_rating">

                                <table class="table align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">

                                            <th class=" px-4 py-3 sorting_disabled" rowspan="1" colspan="1"
                                                style="width: 52px;" aria-label="Vendor">
                                                {{ __('messages.Service_name') }}
                                            </th>
                                            <th class=" px-4 py-3 sorting_disabled" rowspan="1" colspan="1"
                                                style="width: 52px;" aria-label="Vendor">
                                                {{ __('messages.Customer_name') }}
                                            </th>
                                            <th class=" px-4 py-3 sorting_disabled" rowspan="1" colspan="1"
                                                style="width: 52px;" aria-label="Vendor">
                                                {{ __('messages.Provider_name') }}
                                            </th>
                                            <th class=" px-4 py-3 sorting_disabled" rowspan="1" colspan="1"
                                                style="width: 52px;" aria-label="Vendor">
                                                {{ __('messages.Review') }}
                                            </th>
                                            <th class=" px-4 py-3 sorting" tabindex="0" aria-controls="order-table"
                                                rowspan="1" colspan="1" style="width: 110px;"
                                                aria-label="Payment Status: activate to sort column ascending">
                                                {{ __('messages.Comment') }}
                                            </th>


                                        </tr>
                                    </thead>
                                    <tbody>

                                        @if (count($ratings) > 0)
                                            @foreach ($ratings as $rating)
                                                <tr>
                                                    <td>{{ session('lang') == 'en' ? $rating->service->name : $rating->service->name_ar }}
                                                    </td>
                                                    <td>{{ $rating->user->name }}</td>
                                                    <td>{{ $rating->provider->name }}</td>

                                                    <td>{{ $rating->rating }}</td>
                                                    <td>{{ $rating->review }}</td>

                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="odd">
                                                <td valign="top" colspan="6" class="dataTables_empty">No data
                                                    available in table</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>


                            </div>
                            @can('Show Commission')
                                <!--begin:::Tab pane-->
                                <div class="tab-pane fade show " id="kt_settings" role="tabpanel"
                                    data-select2-id="select2-data-kt_settings">

                                    <form method="post"
                                        action="{{ route('admin.profile.add_commissin_to_provider', $user->id) }}"
                                        class=" pt-4 mb-6 mb-xl-9">
                                        @csrf
                                        <div class="card-body pt-0 pb-5">
                                            <div class="row mb-6">
                                                <h3>
                                                    {{ __('messages.Commission') }}
                                                </h3>
                                                <div class="row">

                                                    <div class="col-6 mb-4">

                                                        <input required type="number" min="0" max="100"
                                                            value="{{ $commission->commission_value }}" name="commission"
                                                            placeholder="{{ __('messages.Commission_value') }}"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-6" style="    display: flex;align-items: center;">
                                                        <span>
                                                            {{ __('messages.Percentage') }}
                                                        </span>
                                                        <label class="switch mx-3">
                                                            <input
                                                                {{ $commission->commission_type == 'percentage' ? 'checked' : ' ' }}
                                                                type="radio" name="commission_type" value="percentage">
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <span>
                                                            {{ __('messages.Flat') }}
                                                        </span>
                                                        <label class="switch mx-3">
                                                            <input
                                                                {{ $commission->commission_type == 'flat' ? 'checked' : ' ' }}
                                                                type="radio" name="commission_type" value="flat">
                                                            <span class="slider round"></span>
                                                        </label>

                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        @can('Update Commission')
                                            <div>
                                                <button type="submit" class="btn btn-primary w-100">Save</button>
                                            </div>
                                        @endcan
                                        <!--end::Card body-->
                                    </form>

                                </div>
                            @endcan
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show " id="overview_time" role="tabpanel"
                                data-select2-id="select2-data-kt_settings">

                                <form method="post" action="{{ route('admin.update_overview_time', $user->id) }}"
                                    class="card pt-4 mb-6 mb-xl-9">
                                    @csrf
                                    <div class="card-body pt-0 pb-5">
                                        <div class="row mb-6">
                                            <h3>
                                                {{ __('messages.Overview_time') }}
                                            </h3>
                                            <div class="row">

                                                <div class="col-12 mb-4">

                                                    <input required type="number" value="{{ $overview_time }}"
                                                        name="overview_time"
                                                        placeholder="{{ __('messages.Overview_time') }}"
                                                        class="form-control">
                                                </div>


                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary w-100">Save</button>
                                    </div>
                                    <!--end::Card body-->
                                </form>

                            </div>
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show " id="kt_wallet" role="tabpanel"
                                data-select2-id="select2-data-kt_wallet">

                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <!--begin::Card header-->
                                    <div class="card-header border-0">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h2>{{ __('messages.Wallet') }}</h2>
                                        </div>
                                        <div>
                                            <button class="btn" data-bs-toggle="modal" data-bs-target="#deposit">
                                                <i style="font-size: 25px;" class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                        <!--end::Card title-->

                                    </div>





                                    <div class="card-body pt-0 pb-5">
                                        <div class="row my-2">
                                            <!-- Earnings (Monthly) Card Example -->
                                            <div class="for-card col-md-4 mb-1">
                                                <div class="card for-card-body-2 shadow h-100 text-white"
                                                    style="background: #8d8d8d;">
                                                    <div class="card-body">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div
                                                                    class="font-weight-bold  text-uppercase for-card-text mb-1">
                                                                    {{ __('messages.Cash_collection') }}
                                                                </div>
                                                                <div class="for-card-count">{{ $withdrawn }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer" style="background: #8d8d8d; border:none;">
                                                        <a class="btn w-100 btn-danger" role="button"
                                                            data-toggle="popover" data-bs-toggle="modal"
                                                            data-bs-target="#withdrawn" data-trigger="focus"
                                                            title="messages.warning_missing_bank_info"
                                                            data-content="messages.warning_add_bank_info">Withdraw
                                                            request</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="row">
                                                    <!-- Panding Withdraw Card Example -->
                                                    <div class="for-card col-lg-6 col-md-6 col-sm-6 col-12 mb-1">
                                                        <div class="card  shadow h-100 for-card-body-3  badge-secondary">
                                                            <div class="card-body">
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col mr-2">
                                                                        <div
                                                                            class=" font-weight-bold for-card-text text-uppercase mb-1">
                                                                            {{ __('messages.Pending_withdraw') }}
                                                                        </div>
                                                                        <div class="for-card-count">
                                                                            {{ $withdraw_pending }}</div>
                                                                    </div>
                                                                    <div class="col-auto for-margin">
                                                                        <i
                                                                            class="fas fa-money-bill fa-2x for-fa-3 text-gray-300"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Earnings (Monthly) Card Example -->
                                                    <div class="for-card col-lg-6 col-md-6 col-sm-6 col-12 mb-1">
                                                        <div class="card  shadow h-100 for-card-body-3 text-white"
                                                            style="background: #2C2E43;">
                                                            <div class="card-body">
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col mr-2">
                                                                        <div
                                                                            class=" font-weight-bold for-card-text text-uppercase mb-1">
                                                                            {{ __('messages.Withdrawn') }}
                                                                        </div>
                                                                        <div class="for-card-count">{{ $withdrawn }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-auto for-margin">
                                                                        <i
                                                                            class="fas fa-money-bill fa-2x for-fa-3 text-gray-300"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Collected Cash Card Example -->
                                                    <div class="for-card col-lg-6 col-md-6 col-sm-6 col-12 mb-1">
                                                        <div class="card r shadow h-100 for-card-body-4  badge-dark">
                                                            <div class="card-body">
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col mr-2">
                                                                        <div
                                                                            class=" for-card-text font-weight-bold  text-uppercase mb-1">
                                                                            {{ __('messages.Balance') }}
                                                                        </div>
                                                                        <div class="for-card-count">{{ $user->blance }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-auto for-margin">
                                                                        <i
                                                                            class="fas fa-money-bill for-fa-fa-4  fa-2x text-300"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Pending Requests Card Example -->
                                                    <div class="for-card col-lg-6 col-md-6 col-sm-6 col-12 mb-1">
                                                        <div class="card r shadow h-100 for-card-body-4 text-white"
                                                            style="background:#362222;">
                                                            <div class="card-body">
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col mr-2">
                                                                        <div
                                                                            class=" for-card-text font-weight-bold  text-uppercase mb-1">
                                                                            {{ __('messages.Total_earning') }}
                                                                        </div>
                                                                        <div class="for-card-count">{{ $user_earning }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-auto for-margin">
                                                                        <i
                                                                            class="fas fa-money-bill for-fa-fa-4  fa-2x text-300"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show " id="logs" role="tabpanel"
                                data-select2-id="select2-data-kt_wallet">


                                <div class="card-title">
                                    <h2>{{ __('messages.Logs') }}</h2>
                                </div>

                                <table class="table align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="min-w-125px">{{ __('messages.Employee') }}</th>
                                            <th class="min-w-125px">{{ __('messages.Subject') }}</th>
                                            <th class="min-w-125px">{{ __('messages.Communicate_by') }}</th>
                                            <th class="min-w-125px">{{ __('messages.Notes') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($logs as $log)
                                            <tr>
                                                <td>{{ $log->employee->name }}</td>
                                                <td>{{ __("messages.$log->subject") }}</td>
                                                <td>{{ __("messages.$log->communicate_by") }}</td>
                                                <td>
                                                    <button onclick="setNotes(`{{ $log->notes }}`)"
                                                        data-bs-toggle="modal" data-bs-target="#notesModal"
                                                        class="btn">
                                                        <i class="fa-regular fa-comment"
                                                            style="font-size: 20px;cursor: pointer;"></i>
                                                    </button>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show " style="overflow-x: scroll;" id="packages" role="tabpanel"
                                data-select2-id="select2-data-kt_wallet">


                                <div class="card-title"
                                    style="display: flex;align-items: center;justify-content: space-between;">
                                    <h2>{{ __('messages.Packages') }}</h2>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-package">
                                        {{ __('messages.Add_package') }}
                                    </button>
                                </div>

                                <table class="table align-middle gs-0 gy-4 w-100">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="ps-4 min-w-125px rounded-start">{{ __('messages.Provider_name') }}
                                            </th>
                                            <th class="min-w-125px">{{ __('messages.Package_name') }}</th>
                                            <th class="min-w-125px">{{ __('messages.Duration') }}</th>
                                            <th class="min-w-125px">{{ __('messages.Amount') }}</th>
                                            <th class="min-w-125px">{{ __('messages.Payment_method') }}</th>
                                            <th class="min-w-125px">{{ __('messages.Payment') }}</th>
                                            <th class="min-w-125px">{{ __('messages.Status') }}</th>
                                            <th class="min-w-125px">{{ __('messages.Attachment') }}</th>
                                            <th class="min-w-125px">{{ __('messages.start_subscribe') }}</th>
                                            <th class="min-w-125px">{{ __('messages.end_subscribe') }}</th>
                                            <th class="min-w-125px">{{ __('messages.Actions') }}</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($packages as $subscriber)
                                            <tr>
                                                <td>{{ $subscriber->provider->name }}</td>
                                                <td>{{ session('lang') == 'en' ? $subscriber->package->name : $subscriber->package->name_ar }}
                                                </td>
                                                <td>{{ $subscriber->package_duration }} Month </td>
                                                <td>{{ $subscriber->package_amount }} </td>
                                                <td>{{ $subscriber->payment_method }} </td>
                                                <td>
                                                    @if ($subscriber->paid == 'paid')
                                                        <span
                                                            class="badge badge-light-success">{{ $subscriber->paid }}</span>
                                                    @elseif($subscriber->paid == 'pending')
                                                        <span
                                                            class="badge badge-light-warning">{{ $subscriber->paid }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-light-danger">{{ $subscriber->paid }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($subscriber->status == 1)
                                                        <span
                                                            class="badge badge-light-success">{{ __('messages.Active') }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-light-danger">{{ __('messages.Not_Active') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($subscriber->attachment != null)
                                                        <a href="{{ asset($subscriber->attachment) }}"
                                                            download>{{ __('messages.Download') }}</a>
                                                    @else
                                                        {{ __('messages.No_Attachment_Found') }}
                                                    @endif
                                                </td>
                                                <td>{{ $subscriber->start_subscribe }}</td>
                                                <td>{{ $subscriber->end_subscribe }}</td>
                                                <td>

                                                    <div id="kt_menu_64b77630f13b912{{ $subscriber->id }}">

                                                        <form method="POST"
                                                            action="{{ route('admin.packages.subscribers.status') }}"
                                                            id="auto-submit-form-payment{{ $subscriber->id }}">
                                                            @csrf <!-- Laravel CSRF protection -->
                                                            <input type="hidden" name="id"
                                                                value="{{ $subscriber->id }}">
                                                            <select
                                                                onchange="document.getElementById('auto-submit-form-payment{{ $subscriber->id }}').submit();"
                                                                name="status" class="form-select form-select-solid my-2"
                                                                data-kt-select2="true" data-close-on-select="false"
                                                                data-placeholder="{{ __('messages.Payment_status') }}"
                                                                data-dropdown-parent="#kt_menu_64b77630f13b912{{ $subscriber->id }}"
                                                                data-allow-clear="true">
                                                                <option selected disabled>
                                                                    {{ __('messages.Payment_status') }}</option>
                                                                <option value="0">{{ __('messages.Rejected') }}
                                                                </option>
                                                                <option value="1">{{ __('messages.Paid') }}</option>

                                                            </select>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>







                        </div>
                        <!--end:::Tab content-->
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
        </div>



        <!-- Modal -->
        <div class="modal fade" id="withdrawn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.profile.withdraw', $user->id) }}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.Withdraw') }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h3>
                            {{ __('messages.You_blance') }} : {{ $user->blance }}
                        </h3>
                        <div class="row my-4">
                            <div class="col">
                                <input required type="password" name="password" class="form-control"
                                    placeholder="{{ __('messages.Password') }}" aria-label="First name">
                            </div>
                        </div>

                        <div class="row my-4">
                            <div class="col">
                                <input required type="number" name="amount" min="1" max="{{ $user->blance }}"
                                    class="form-control" placeholder="{{ __('messages.Amount') }}"
                                    aria-label="First name">
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col">
                                <select required class="form-select" name="payment_method_id"
                                    aria-label="Default select example">
                                    <option selected disabled>{{ __('messages.Payment_method') }}</option>
                                    @foreach ($payment_methods as $method)
                                        <option value="{{ $method->id }}">
                                            {{ session('lang') == 'en' ? $method->name : $method->name_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <input required type="text" name="account_number" class="form-control"
                                    placeholder="{{ __('messages.Account_number') }}" aria-label="First name">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('messages.Save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="deposit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.profile.deposit', $user->id) }}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.Deposit') }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row my-4">
                            <div class="col">
                                <input required type="password" name="password" class="form-control"
                                    placeholder="{{ __('messages.Your_Password') }}" aria-label="First name">
                            </div>
                        </div>

                        <div class="row my-4">
                            <div class="col">
                                <input required type="number" name="amount" min="1" class="form-control"
                                    placeholder="{{ __('messages.Amount') }}" aria-label="First name">
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('messages.Deposit') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="add-booking" tabindex="-1" aria-labelledby="addBookingLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBookingLabel">{{ __('messages.Add_booking') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form Start -->
                        <form action="{{ route('admin.booking.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" autocomplete="off">

                            <div class="mb-3">
                                <label for="customer_id" class="form-label">{{ __('messages.Customers') }}</label>
                                <select name="customer_id" class="form-select form-select-solid" id="customer_id"
                                    required>
                                    <option selected disabled>{{ __('messages.Customers') }}</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="service_id" class="form-label">{{ __('messages.Service_name') }}</label>
                                <select name="service_id" class="form-select form-select-solid" id="service_id" required>
                                    <option selected disabled>{{ __('messages.Service_name') }}</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row my-2">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="start_at" class="form-label">{{ __('messages.Start_at') }}</label>
                                        <input type="date" name="start_at" class="form-control" id="start_at"
                                            placeholder="Example input placeholder" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="end_at" class="form-label">{{ __('messages.End_at') }}</label>
                                        <input type="date" name="end_at" class="form-control" id="end_at"
                                            placeholder="Example input placeholder" required>
                                    </div>
                                </div>
                            </div>

                            <div class="my-2">
                                <button type="submit" class="btn btn-primary w-100">{{ __('messages.Add') }}</button>
                            </div>
                        </form>
                        <!-- Form End -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.Close') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="assign-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.assign_employee_to_provider') }}" method="post" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.Assign_user') }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="my-3">
                            <select name="employee_id" class="form-select" aria-label="Default select example">
                                <option selected disabled>{{ __('messages.Employee') }}</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="provider_id" value="{{ $user->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('messages.Save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="add-log" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.add_log') }}" method="post" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.Add_log') }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="provider_id" value="{{ $user->id }}">
                        <div class="mb-3">
                            <select name="subject" id="Subject-log" required class="form-select"
                                aria-label="Default select example">
                                <option value="" selected disabled>{{ __('messages.Subject') }}</option>
                                <option value="Property">{{ __('messages.Property') }}</option>
                                <option value="Details_about_adding_property">
                                    {{ __('messages.Details_about_adding_property') }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <select name="communicate_by" id="communicateBy-log" required class="form-select"
                                aria-label="Default select example">
                                <option value="" selected disabled>{{ __('messages.Communicate_by') }}</option>
                                <option value="Meeting">{{ __('messages.Meeting') }}</option>
                                <option value="Call">{{ __('messages.Call') }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <!-- Textarea -->
                            <div class="form-floating">
                                <textarea name="notes" required style="min-height: 300px;" class="form-control"
                                    placeholder="{{ __('messages.Notes') }}" id="floatingTextarea"></textarea>
                                <label for="floatingTextarea">{{ __('messages.Notes') }}</label>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">{{ __('messages.Add') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="change-password" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.profile.update', $user->id) }}" method="post" class="modal-content">
                    @csrf
                    <input type="hidden" name="section" value="3">

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.Change_password') }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="provider_id" value="{{ $user->id }}">
                        <div class="row mb-1">

                            <div class="col-12 mb-3">
                                <div class="fv-row mb-0 fv-plugins-icon-container">
                                    <label for="new_password" class="form-label fs-6 fw-bold mb-3">
                                        {{ __('messages.New_Password') }}</label>
                                    <input required type="password"
                                        class="form-control form-control-lg form-control-solid" name="new_password"
                                        id="new_password">
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="fv-row mb-0 fv-plugins-icon-container">
                                    <label for="confirm_password" class="form-label fs-6 fw-bold mb-3">
                                        {{ __('messages.Confirm_New_Password') }}</label>
                                    <input required type="password"
                                        class="form-control form-control-lg form-control-solid" name="confirm_password"
                                        id="confirm_password">
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">{{ __('messages.Update') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="notesModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.Notes') }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <!-- Textarea -->
                            <div class="form-floating">
                                <textarea readonly name="notes" style="min-height: 300px;resize: none;background:white" class="form-control"
                                    placeholder="{{ __('messages.Notes') }}" id="notes"></textarea>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>


<!-- Modal add package -->
<div class="modal fade" id="add-package" tabindex="-1" aria-labelledby="depositLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="depositLabel">{{ __('messages.Choose_package') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form action="{{ route('admin.profile.add_package') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="package" class="form-label">{{ __('messages.Select_package') }}</label>
			<input type="hidden" name="provider_id" value="{{ $user->id }}">
            <select class="form-select" id="package" name="package_id" required>
              <option value="" disabled selected>{{ __('messages.Choose') }}</option>
              @foreach ($packages_list as $package)
                <option value="{{ $package->id }}">
                  {{ session("lang") == 'en'
                      ? $package->name
                      : $package->name_ar }}
                </option>
              @endforeach
            </select>
          </div>

          <button type="submit" class="btn btn-primary">
              {{ __('messages.Submit') }}
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
    @endsection

    @section('js')
        <script>
            function dateFilter() {
                var from = document.getElementById('from').value;
                var to = document.getElementById('to').value;

                // Check if both 'from' and 'to' fields are filled
                if (from !== '' && to !== '') {
                    // Submit the form
                    document.getElementById('dateBetweenFilter').submit();
                }
            }
        </script>
        <script>
            function setNotes(notes) {
                console.log(notes);
                let textarea = document.getElementById("notes")
                textarea.value = notes
            }
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale-all.js"></script>

        <script>
            $(document).ready(function() {
                var calendar_dates = @json($calendar_dates);
                var userLanguage = '{{ session('lang') }}'; // Get the current locale
                console.log(userLanguage);

                var calendar = $('#calendar').fullCalendar({
                    header: {
                        left: 'prev, next',
                        center: 'title',
                        right: 'month, agendaWeek, agendaDay ',
                    },
                    locale: userLanguage, // Use the language detected from the backend

                    events: calendar_dates,
                    selectHelper: true,



                    select: function(start, end, allDays) {
                        $("#selectDay").modal('toggle')
                        $('#save-changes').click(function() {

                            let start_at = moment(start).format('M/D/YYYY')
                            let end_at = moment(end).add(1, 'days').format('M/D/YYYY')
                            let title = $('#title').val();
                            $.ajax({
                                url: "{{ route('test') }}",
                                type: "POST",
                                dataType: 'json',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    title: title,
                                    start_at: start_at,
                                    end_at: end_at
                                },
                                success: function(response) {
                                    console.log(response);
                                },
                                error: function(error) {
                                    console.log(error);
                                }
                            })
                        })
                    },
                    editable: true,

                    eventRender: function(event, element) {
                        if (event.url) {
                            element.find('.fc-title').html('<a  href="' + event.url + '"style="color:' +
                                event.textColor + ';">' + event.title + '</a>');
                        }
                    }
                });

            });
        </script>

    @endsection
