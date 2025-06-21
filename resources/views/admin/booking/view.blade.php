@extends('admin.app')
@section('title', trans('messages.Order_details'))
@section('css')
    <style>
        /* Style the Image Used to Trigger the Modal */
        #myImg {
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        #myImg:hover {
            opacity: 0.7;
        }

        /* The Modal (background) */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 9999;
            /* Sit on top */
            padding-top: 100px;
            /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.9);
            /* Black w/ opacity */
        }

        /* Modal Content (Image) */
        .modal-content {
            margin: auto;
            display: block;
            width: 100%;
            max-width: 900px;
        }

        /* Caption of Modal Image (Image Text) - Same Width as the Image */
        #caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
        }

        /* Add Animation - Zoom in the Modal */
        .modal-content,
        #caption {
            -webkit-animation-name: zoom;
            -webkit-animation-duration: 0.6s;
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @-webkit-keyframes zoom {
            from {
                -webkit-transform: scale(0)
            }

            to {
                -webkit-transform: scale(1)
            }
        }

        @keyframes zoom {
            from {
                transform: scale(0)
            }

            to {
                transform: scale(1)
            }
        }

        /* The Close Button */
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px) {
            .modal-content {
                width: 100%;
            }
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
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        {{ __('messages.Order_details') }}</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a class="text-muted text-hover-primary">{{ __('messages.Pages') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">{{ __('messages.Order_details') }}</li>
                    </ul>
                </div>

            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">


                <div class='d-flex flex-column flex-xl-row gap-7 gap-lg-10'>

                    <div class="card card-flush py-4 flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('messages.Order_details') }} (#{{ $order->id }})</h2>
                            </div>
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-calendar fs-2 me-2"><span
                                                            class="path1"></span><span class="path2"></span></i>
                                                    {{ __('messages.Customer_name') }}
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end"><a
                                                    href="{{ route('admin.profile', $order->provider->id) }}">{{ $order->customer->name }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-calendar fs-2 me-2"><span
                                                            class="path1"></span><span class="path2"></span></i>
                                                    {{ __('messages.Date_Added') }}
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                {{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}</td>
                                        </tr>
                                        <tr>

                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-wallet fs-2 me-2"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span><span class="path4"></span></i>
                                                    {{ __('messages.Payment_method') }}
                                                </div>
                                            </td>

                                            @if ($order->payment_method && $order->payment_type == 'bank_transfer')
                                                <td class="fw-bold text-end">
                                                    {{ session('lang') == 'en' ? $order->payment_method->name : $order->payment_method->name_ar }}
                                                    <img src="/{{ $order->payment_method->image }}" class="w-50px ms-2">
                                                </td>
                                            @elseif($order->payment_type != null)
                                                <td class="fw-bold text-end">
                                                    {{ __("messages.$order->payment_type") }}
                                                </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-calendar fs-2 me-2"><span
                                                            class="path1"></span><span class="path2"></span></i>
                                                    {{ __('messages.Provider_name') }}
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end"><a
                                                    href="{{ route('admin.profile', $order->provider->id) }}">{{ $order->provider->name }}</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <div class="card card-flush py-4 flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">

                                <h2>{{ __('messages.Full_payment') }}</h2>
                            </div>
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            @if ($order->attachment)
                                <img onclick="ImageModal('{{ $order->attachment }}')"
                                    style="height: 300px;w;cursor: pointer;" src="/{{ $order->attachment }}"
                                    alt="">
                            @else
                                <h3
                                    style="    height: 100%;width: 100%;color: white;display: flex;justify-content: center;align-items: center;background: gray;">
                                    {{ __('messages.Not_uploaded_image_yet') }}
                                </h3>
                            @endif
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
                <div class="card card-flush py-4 flex-row-fluid">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>{{ __('messages.Down_payment') }}</h2>
                        </div>
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        @if ($order->down_attachment)
                            <img onclick="ImageModal('{{ $order->down_attachment }}')"
                                style="height: 300px;w;cursor: pointer;" src="/{{ $order->down_attachment }}"
                                alt="">
                        @else
                            <h3
                                style="    height: 100%;width: 100%;color: white;display: flex;justify-content: center;align-items: center;background: gray;">
                                {{ __('messages.Not_uploaded_image_yet') }}
                            </h3>
                        @endif
                    </div>
                    <!--end::Card body-->
                </div>
                <div class='d-flex flex-column flex-xl-row gap-7 gap-lg-10 my-5'>

                    <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('messages.Order_details') }} (#{{ $order->id }})</h2>
                            </div>
                        </div>

                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0 my-5">
                                    <thead>
                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="ps-4 min-w-80px ">{{ __('messages.Service_name') }}</th>
                                            <th class="min-w-50px">{{ __('messages.Booking_status') }}</th>
                                            <th class="min-w-50px">{{ __('messages.Payment_status') }}</th>
                                            <th class="min-w-100px">{{ __('messages.Start_at') }}</th>
                                            <th class="min-w-100px">{{ __('messages.End_at') }}</th>
                                            <th class="min-w-150px">{{ __('messages.Actions') }}</th>

                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td>{{ session('lang') == 'en' ? $order->service->name : $order->service->name_ar }}
                                            </td>
                                            <td>
                                                @if ($order->booking_status_id == 1)
                                                    <span
                                                        class="badge badge-light-Secondary">{{ __('messages.Pending') }}</span>
                                                @elseif($order->booking_status_id == 3)
                                                    <span
                                                        class="badge badge-light-success">{{ __('messages.Approved') }}</span>
                                                @elseif($order->booking_status_id == 4)
                                                    <span
                                                        class="badge badge-light-danger">{{ __('messages.Rejected') }}</span>
                                                @elseif($order->booking_status_id == 5)
                                                    <span
                                                        class="badge badge-light-danger">{{ __('messages.Cancelled') }}</span>
                                                @endif
                                            </td>
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
                                                @elseif($order->payment_status_id == 4)

                                                @elseif($booking->payment_status_id == 5)
                                                    <span
                                                        class="badge badge-light-info">{{ __('messages.Under_review') }}</span>
                                                    <span
                                                        class="badge badge-light-info">{{ __('messages.Partialـpayment') }}</span>
                                                @elseif($booking->payment_status_id == 6)
                                                    <span
                                                        class="badge badge-light-info">{{ __('messages.Payment_delay') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($order->start_at)->format('Y-m-d') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order->end_at)->format('Y-m-d') }}</td>
                                            <td class="text-center">
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
                                                                name="booking_status" class="form-select form-select-solid"
                                                                data-kt-select2="true" data-close-on-select="false"
                                                                data-placeholder="{{ __('messages.Booking_status') }}"
                                                                data-dropdown-parent="#kt_menu_64b77630f13b911{{ $order->id }}"
                                                                data-allow-clear="true">
                                                                <option selected disabled>{{ __('messages.Booking_status') }}
                                                                </option>
                                                                <option value="1">{{ __('messages.Pending') }}</option>
                                                                <option value="3">{{ __('messages.Approved') }}</option>
                                                                <option value="4">{{ __('messages.Rejected') }}</option>
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
                                                                data-kt-select2="true" data-close-on-select="false"
                                                                data-placeholder="{{ __('messages.Payment_status') }}"
                                                                data-dropdown-parent="#kt_menu_64b77630f13b912{{ $order->id }}"
                                                                data-allow-clear="true">
                                                                <option selected disabled>{{ __('messages.Payment_status') }}
                                                                </option>
                                                                <option value="1">{{ __('messages.Un_paid') }}</option>
                                                                <option value="2">{{ __('messages.Pending') }}</option>
                                                                <option value="3">{{ __('messages.Paid') }}</option>
                                                                <option value="4">{{ __('messages.Partialـpayment') }}
                                                                </option>

                                                            </select>
                                                        </form>
                                                    </div>
                                                @endcan

                                            </td>

                                        </tr>

                                        <tr>
                                            <td colspan="4" class="text-end">
                                                {{ __('messages.Subtotal') }}
                                            </td>
                                            <td class="text-end">
                                                {{ $order->amount }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end">
                                                {{ __('messages.Commission') }}
                                            </td>
                                            <td class="text-end">
                                                {{ $order->taxes }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="4" class="fs-3 text-gray-900 text-end">
                                                {{ __('messages.Total') }}
                                            </td>
                                            <td class="text-gray-900 fs-3 fw-bolder text-end">
                                                {{ $order->total_amount }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!-- <div class="container">
                            <h3>
                                {{ __('messages.Total_event_days') }} : {{ $data['total_event_days'] }}
                            </h3>
                            <h3>
                                {{ __('messages.Total_normal_days') }} : {{ $data['total_normal_days'] }}
                            </h3>

                            @if (isset($data['total_after_apply_coupon']))
    <h3>

                                {{ __('messages.Price_before_Coupon') }} : {{ $data['total'] }}
                            </h3>
                            <h3>
                                {{ __('messages.Price_after_Coupon') }} : {{ $data['total_after_apply_coupon'] }}
                            </h3>
@else
    <h3>

                                {{ __('messages.Amount') }} : {{ $order->total_amount }}
                            </h3>
    @endif
                        </div> -->
                        <!-- <div class="text-center my-5">
                            @if ($order->booking_status_id == 5 && $order->confirm_cancellation == 0)
    <form action="{{ route('admin.booking.confrim_cancellation') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $order->id }}">
                                <button class="btn btn-info w-100 text-center" type="submit">{{ __('messages.Confirm_cancellation') }}</button>
                            </form>
@elseif($order->booking_status_id == 5 && $order->confirm_cancellation == 1)
    <button class="btn btn-success w-100 text-cen">{{ __('messages.Confirmed') }}</button>
    @endif
                        </div> -->
                        <!--end::Card body-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <span class="close" onclick="CloseModal()">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>
@endsection
@section('js')
    <script>
        // Get the modal
        var modal = document.getElementById('myModal');

        function ImageModal(image) {

            var modalImg = document.getElementById("img01");

            // Get the image and insert it inside the modal - use its "alt" text as a caption

            var captionText = document.getElementById("caption");

            modal.style.display = "block";
            modalImg.src = '/' + image;




        }

        function CloseModal() {

            modal.style.display = "none";
        }
    </script>
@endsection
