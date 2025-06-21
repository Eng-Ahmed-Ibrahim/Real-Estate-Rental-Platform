@extends('admin.app')
@section('title', trans('messages.Profile'))
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
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
                        {{ __('messages.Customer') }}</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}"
                                class="text-muted text-hover-primary">{{ __('messages.Home') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted"><a href="{{ route('admin.dashboard') }}"
                                class="text-muted text-hover-primary">{{ __('messages.Customer') }}</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="d-flex flex-column flex-xl-row">
                    <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-body pt-15">
                                <div class="d-flex flex-center flex-column mb-5">
                                    <div class="symbol symbol-100px symbol-circle mb-7">
                                        <img src="/{{ $user->image }}" alt="image" data--h-bstatus="4PROCESSING">
                                    </div>
                                    <a href="#"
                                        class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">{{ $user->name }}</a>
                                    <div class="fs-5 fw-semibold text-muted mb-6">{{ $user->roles->first()->name }}</div>
                                    <div class="fs-5 fw-semibold text-muted mb-6">
                                        {{ __('messages.Balance') }}:{{ $user->blance }}</div>

                                    <div style="display: flex;align-items:center;justify-content:space-around;">

                                        <button class="btn btn-primary mx-2" data-bs-toggle="modal"
                                            data-bs-target="#change-password">{{ __('messages.Change_password') }}</button>
                                        <button class="btn btn-primary mx-2" data-bs-toggle="modal"
                                            data-bs-target="#add-balance">{{ __('messages.Add_balance') }}</button>
                                    </div>

                                </div>
                                <div class="d-flex flex-stack fs-4 py-3">
                                    <div class="fw-bold rotate collapsible" data-bs-toggle="collapse"
                                        href="#kt_customer_view_details" role="button" aria-expanded="false"
                                        aria-controls="kt_customer_view_details">{{ __('messages.Details') }} <span
                                            class="ms-2 rotate-180">
                                            <span class="svg-icon svg-icon-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </span>
                                        </span>
                                    </div>
                                    <div style="display: flex;gap: 7px;">
                                        <span data-bs-toggle="tooltip" data-bs-trigger="hover"
                                            data-bs-original-title="Edit customer details" data-kt-initialized="1">
                                            @if (auth()->user()->can('edit user') || $user->id == auth()->user()->id)
                                                <a href="{{ route('admin.profile.edit', $user->id) }}"
                                                    class="btn btn-sm btn-light-primary">{{ __('messages.Edit') }}</a>
                                            @endif
                                        </span>
                                        @php
                                            $confirm_message =
                                                $user->blocked == 1
                                                    ? __('messages.Are_you_sure_you_want_to_unban_this_account')
                                                    : __('messages.Are_you_sure_you_want_to_ban_this_account');
                                        @endphp
                                        <form action="{{ route('admin.block_user', $user->id) }}"
                                            onsubmit="return confirm('{{ $confirm_message }}');" method="post">
                                            @csrf
                                            <input type="hidden" value="{{ $user->id }}" name="user_id">
                                            <button type="submit"
                                                class="btn btn-sm {{ $user->blocked == 1 ? 'btn-success' : 'btn-danger' }} ">
                                                {{ $user->blocked == 1 ? __('messages.Un_block') : __('messages.Block') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <div id="kt_customer_view_details" class="collapse show">
                                    <div class="py-5 fs-6">
                                        <div class="fw-bold mt-5">{{ __('messages.Name') }}</div>
                                        <div class="text-gray-600">{{ $user->name }}</div>
                                        <div class="fw-bold mt-5">{{ __('messages.Email') }}</div>
                                        <div class="text-gray-600">
                                            <a href="#"
                                                class="text-gray-600 text-hover-primary">{{ $user->email }}</a>
                                        </div>
                                        <div class="fw-bold mt-5">{{ __('messages.Phone') }}</div>
                                        <div class="text-gray-600">
                                            {{ $user->phone }}
                                        </div>
                                        <div class="fw-bold mt-5">{{ __('messages.Created_at') }}</div>
                                        <div class="text-gray-600">{{ $user->created_at }}</div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-lg-row-fluid ms-lg-15">
                        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                            @can('show booking_')
                                <li class="nav-item">
                                    <a class="nav-link text-active-primary active pb-4 "
                                        href="/admin/profile/{{ $user->id }}?section=orders"
                                        data-kt-countup-tabs="true">{{ __('messages.Orders') }}</a>
                                </li>
                            @endcan

                            @can('show rating_')
                                <li class="nav-item">
                                    <a class="nav-link text-active-primary pb-4 "
                                        href="/admin/profile/{{ $user->id }}?section=rating"
                                        data-kt-countup-tabs="true">{{ __('messages.Rating') }}</a>
                                </li>
                            @endcan

                            @can('show calendar_')
                                <li class="nav-item">
                                    <a class="nav-link text-active-primary pb-4 "
                                        href="/admin/profile/{{ $user->id }}?section=calendar"
                                        data-kt-countup-tabs="true">{{ __('messages.Calendar') }}</a>
                                </li>
                            @endcan

                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="kt_customer_view_branche_tab" role="tabpanel">

                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>{{ __('messages.Orders') }}</h2>
                                        </div>

                                    </div>


                                    @can('show booking_')

                                        <div class="card-body pt-0 pb-5">
                                            <div class="row my-2">

                                                <div class="table-responsive">

                                                    <div id="order-table_processing" class="dataTables_processing"
                                                        style="display: none;">
                                                        <span
                                                            class="spinner-border w-15px h-15px text-muted align-middle me-2">

                                                        </span>
                                                        <div>
                                                            <div></div>
                                                            <div></div>
                                                            <div></div>
                                                            <div></div>
                                                        </div>
                                                    </div>
                                                    <table id="order-table"
                                                        class="table table-row-dashed align-middle gs-0 gy-3 my-0 dataTable no-footer"
                                                        aria-describedby="order-table_info" style="width: 569px;">
                                                        <thead>
                                                            <tr>

                                                                <th class="text-center px-4 py-3 sorting_disabled"
                                                                    rowspan="1" colspan="1" style="width: 52px;"
                                                                    aria-label="Vendor">
                                                                    {{ __('messages.Service_name') }}
                                                                </th>
                                                                <th class="text-center px-4 py-3 sorting_disabled"
                                                                    rowspan="1" colspan="1" style="width: 52px;"
                                                                    aria-label="Vendor">
                                                                    {{ __('messages.Provider_name') }}
                                                                </th>
                                                                <th class="text-center px-4 py-3 sorting" tabindex="0"
                                                                    aria-controls="order-table" rowspan="1" colspan="1"
                                                                    style="width: 110px;"
                                                                    aria-label="Payment Status: activate to sort column ascending">
                                                                    {{ __('messages.Payment_status') }}
                                                                </th>
                                                                <th class="text-start sorting" tabindex="0"
                                                                    aria-controls="order-table" rowspan="1" colspan="1"
                                                                    style="width: 37.25px;"
                                                                    aria-label="Total: activate to sort column ascending">
                                                                    {{ __('messages.Total') }}
                                                                </th>
                                                                <th class="text-start sorting" tabindex="0"
                                                                    aria-controls="order-table" rowspan="1" colspan="1"
                                                                    style="width: 98.25px;"
                                                                    aria-label="Orders Status: activate to sort column ascending">
                                                                    {{ __('messages.Order_status') }}
                                                                </th>
                                                                <th class="text-center px-4 py-3 sorting_disabled"
                                                                    rowspan="1" colspan="1" style="width: 54px;"
                                                                    aria-label="Actions">
                                                                    {{ __('messages.Actions') }}
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            @if (count($orders) > 0)
                                                                @foreach ($orders as $order)
                                                                    <tr>
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
                                                                                    class="badge badge-light-Secondary">{{ __('messages.Pending') }}</span>
                                                                            @elseif($order->booking_status_id == 2)
                                                                                <span class="badge badge-light-warning">
                                                                                    {{ __('messages.In_process') }}</span>
                                                                            @elseif($order->booking_status_id == 3)
                                                                                <span
                                                                                    class="badge badge-light-success">{{ __('messages.Approved') }}</span>
                                                                            @elseif($order->booking_status_id == 4)
                                                                                <span
                                                                                    class="badge badge-light-danger">{{ __('messages.Rejected') }}</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <a href="{{ route('admin.booking.show', $order->id) }}"
                                                                                class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">{{ __('messages.View') }}</a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr class="odd">
                                                                    <td valign="top" colspan="6"
                                                                        class="dataTables_empty">No data available in table
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>

                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    <div class="modal fade" id="change-password" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <input required type="password" class="form-control form-control-lg form-control-solid"
                                    name="new_password" id="new_password">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="confirm_password" class="form-label fs-6 fw-bold mb-3">
                                    {{ __('messages.Confirm_New_Password') }}</label>
                                <input required type="password" class="form-control form-control-lg form-control-solid"
                                    name="confirm_password" id="confirm_password">
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
    <div class="modal fade" id="add-balance" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.profile.add_balance') }}" method="post" class="modal-content">
                @csrf
                <input type="hidden" name="section" value="3">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.Add_balance') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="customer_id" value="{{ $user->id }}">
                    <div class="row mb-1">

                        <div class="col-12 mb-3">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="password" class="form-label fs-6 fw-bold mb-3">
                                    {{ __('messages.Password') }}</label>
                                <input required type="password" class="form-control form-control-lg form-control-solid"
                                    name="password" id="password">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="balance" class="form-label fs-6 fw-bold mb-3">
                                    {{ __('messages.Balance') }}</label>
                                <input required type="number" class="form-control form-control-lg form-control-solid"
                                    name="balance" id="balance">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">{{ __('messages.Add') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
