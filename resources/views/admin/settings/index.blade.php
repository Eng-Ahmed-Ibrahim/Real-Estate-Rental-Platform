@extends('admin.app')
@section('title', trans('messages.Settings'))
@section('css')
    <style>
        textarea {
            height: 200px !important;
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
    </style>


@endsection
@section('content')
    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">

            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">



                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        {{ __('messages.Settings') }}
                    </h1>

                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a class="text-muted text-hover-primary">{{ __('messages.Home') }}</a>
                        </li>

                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">{{ __('messages.Settings') }}</li>

                    </ul>
                </div>


            </div>
        </div>

        <div id="kt_app_content" class="app-content  flex-column-fluid ">


            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-xxl ">
                <!--begin::Card-->
                <div class="card card-flush">
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin:::Tabs-->
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-15"
                            role="tablist">
                            <!--begin:::Tab item-->
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5 active"
                                    data-bs-toggle="tab" href="#kt_ecommerce_settings_general" aria-selected="true"
                                    role="tab">
                                    {{ __('messages.General') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5 " data-bs-toggle="tab"
                                    href="#Loyalty_points" aria-selected="true" role="tab">
                                    {{ __('messages.Loyalty_points') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5 " data-bs-toggle="tab"
                                    href="#Terms_and_conditions" aria-selected="true" role="tab">
                                    {{ __('messages.Terms') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5 " data-bs-toggle="tab"
                                    href="#Refund_policy" aria-selected="true" role="tab">
                                    {{ __('messages.Refund_policy') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5 " data-bs-toggle="tab"
                                    href="#Privacy_policy" aria-selected="true" role="tab">
                                    {{ __('messages.Privacy_policy') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5 " data-bs-toggle="tab"
                                    href="#about_us" aria-selected="true" role="tab">
                                    {{ __('messages.About_us') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5 " data-bs-toggle="tab"
                                    href="#social_media" aria-selected="true" role="tab">
                                    {{ __('messages.social_media') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5 " data-bs-toggle="tab"
                                    href="#Sliders" aria-selected="true" role="tab">
                                    {{ __('messages.Sliders') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5 " data-bs-toggle="tab"
                                    href="#propertyType" aria-selected="true" role="tab">
                                    {{ __('messages.Property_types') }}
                                </a>
                            </li>
                        </ul>
                        <!--end:::Tabs-->

                        <!--begin:::Tab content-->
                        <div class="tab-content" id="myTabContent" data-select2-id="select2-data-myTabContent">
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel"
                                data-select2-id="select2-data-kt_ecommerce_settings_general">


                                <!--begin::Form-->
                                <form action="{{ route('admin.settings.change_commission') }}"
                                    enctype="multipart/form-data" method="post" id="kt_ecommerce_settings_general_form"
                                    class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                    data-select2-id="select2-data-kt_ecommerce_settings_general_form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="commission"
                                                class="form-label">{{ __('messages.Commission_value') }}</label>
                                            <input type="number" id="commission" class="form-control" name="commission"
                                                value="{{ $setting->commission_value }}"
                                                placeholder="{{ __('messages.Commission_value') }} %"
                                                aria-label="Commission value">
                                        </div>
                                        <div class="col-lg-6 col-sm-12 row">
                                            <div class="col-lg6 col-sm-12 my-2">

                                                <span>
                                                    {{ __('messages.Percentage') }}

                                                </span>
                                                <label class="switch mx-3">
                                                    <input
                                                        {{ $setting->commission_type == 'percentage' ? 'checked' : ' ' }}
                                                        type="radio" name="commission_type" value="percentage">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-lg-6 col-sm-12 my-2">

                                                <span>
                                                    {{ __('messages.Flat') }}
                                                </span>
                                                <label class="switch mx-3">
                                                    <input {{ $setting->commission_type == 'flat' ? 'checked' : ' ' }}
                                                        type="radio" name="commission_type" value="flat">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row my-4">

                                        <div class="col-lg-6 col-sm-12">
                                            <label for="down_payment"
                                                class="form-label">{{ __('messages.Down_payment') }} *
                                                {{ __('messages.Percentage') }}</label>
                                            <input type="number" id="down_payment" class="form-control"
                                                name="down_payment" value="{{ $setting->down_payment }}" min="0"
                                                max="100" placeholder="{{ __('messages.Down_payment') }} "
                                                aria-label="Down payment value">
                                        </div>

                                        <div class="col-lg-6 col-sm-12">
                                            <label for="min_partial_payment"
                                                class="form-label">{{ __('messages.min_partial_payment') }} </label>
                                            <input type="number" id="min_partial_payment" class="form-control"
                                                name="min_partial_payment" value="{{ $setting->min_partial_payment }}"
                                                min="0" placeholder="{{ __('messages.min_partial_payment') }} "
                                                aria-label="Down payment value">
                                        </div>
                                    </div>
                                    <div class="row my-4">

                                        <div class="col-lg-6 col-sm-12">
                                            <label for="cancel_within_hours"
                                                class="form-label">{{ __('messages.cancel_within_hours') }} </label>
                                            <input type="number" id="cancel_within_hours" class="form-control"
                                                name="cancel_within_hours" value="{{ $setting->cancel_within_hours }}"
                                                placeholder="{{ __('messages.cancel_within_hours') }} "
                                                aria-label="Down payment value">
                                        </div>

                                    </div>
                                    <div class="row my-4">
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="refund_full_amount_within_hours"
                                                class="form-label">{{ __('messages.refund_full_amount_within_hours') }}
                                            </label>
                                            <input type="number" id="refund_full_amount_within_hours"
                                                class="form-control" name="refund_full_amount_within_hours"
                                                value="{{ $setting->refund_full_amount_within_hours }}"
                                                placeholder="{{ __('messages.refund_full_amount_within_hours') }} "
                                                aria-label="Commission value">
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="deduct_an_amount"
                                                class="form-label">{{ __('messages.deduct_an_amount') }} *
                                                {{ __('messages.Percentage') }}</label>
                                            <input type="number" id="deduct_an_amount" class="form-control"
                                                name="deduct_an_amount" min="0" max="100"
                                                value="{{ $setting->deduct_an_amount }}"
                                                placeholder="{{ __('messages.deduct_an_amount') }} "
                                                aria-label="Commission value">
                                        </div>

                                    </div>
                                    <div class="row my-4">
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="whatsapp_phone"
                                                class="form-label">{{ __('messages.Whatsapp_phone') }} </label>
                                            <input type="number" id="whatsapp_phone" class="form-control"
                                                name="whatsapp_phone" value="{{ $setting->whatsapp_phone }}"
                                                placeholder="{{ __('messages.Whatsapp_phone') }} "
                                                aria-label="Commission value">
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="Contact_phone"
                                                class="form-label">{{ __('messages.Contact_phone') }} </label>
                                            <input type="number" id="Contact_phone" class="form-control" name="phone"
                                                value="{{ $setting->phone }}"
                                                placeholder="{{ __('messages.Contact_phone') }} "
                                                aria-label="Commission value">
                                        </div>
                                    </div>
                                    <div class="row my-4">
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="overview_time_payment"
                                                class="form-label">{{ __('messages.Overview_time_payment') }} </label>
                                            <input type="number" id="overview_time_payment" class="form-control"
                                                name="overview_time_payment"
                                                value="{{ $setting->overview_time_payment }}" min="0"
                                                max="100" placeholder="{{ __('messages.overview_time_payment') }} "
                                                aria-label="Commission value">
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="overview_time"
                                                class="form-label">{{ __('messages.Overview_time') }} </label>
                                            <input type="number" id="overview_time" class="form-control"
                                                name="overview_time" value="{{ $setting->overview_time }}"
                                                min="0" max="100"
                                                placeholder="{{ __('messages.overview_time') }} "
                                                aria-label="Commission value">
                                        </div>

                                    </div>
                                    <div class="row my-4">
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="email" class="form-label">{{ __('messages.Email') }} </label>
                                            <input type="email" id="email" class="form-control"
                                                name="contact_email" value="{{ $setting->contact_email }}"
                                                min="0" max="100" placeholder="{{ __('messages.Email') }} "
                                                aria-label="Commission value">
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="owner_name" class="form-label">{{ __('messages.Owner_name') }}
                                            </label>
                                            <input type="text" id="owner_name" class="form-control" name="owner_name"
                                                value="{{ $setting->owner_name }}" min="0" max="100"
                                                placeholder="{{ __('messages.Owner_name') }} "
                                                aria-label="Commission value">
                                        </div>

                                    </div>
                                    <div class="my-4">
                                        <div class="col-lg-6 col-sm-12">

                                            <label for="website_logo" class="form-label">
                                                <img src="/{{ $setting->website_logo }}" style="height:50px;width:50px"
                                                    alt="">
                                                {{ __('messages.Website_logo') }}
                                            </label>
                                            <input type="file" id="website_logo" class="form-control"
                                                name="website_logo">
                                        </div>
                                    </div>
                                    <div class="my-5">
                                        <button class="btn btn-primary w-100"
                                            type="submit">{{ __('messages.Save_changes') }}</button>
                                    </div>

                                </form>
                                <!--end::Form-->
                            </div>
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show " id="Loyalty_points" role="tabpanel"
                                data-select2-id="select2-data-Loyalty_points">
                                <form action="{{ route('admin.settings.policy') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="section" value="8">
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-6 col-xs-12 mb-2">
                                            <label for="commission"
                                                class="form-label">{{ __('messages.Equivalent_point_to_1_EGP') }}</label>
                                            <input type="number" id="point_equal_1_currency" class="form-control"
                                                name="point_equal_1_currency"
                                                value="{{ $setting->point_equal_1_currency }}"
                                                placeholder="{{ __('messages.Equivalent_point_to_1_EGP') }} "
                                                aria-label="Commission value">
                                        </div>
                                        <div class="col-lg-4 col-sm-6 col-xs-12 mb-2">
                                            <label for="commission"
                                                class="form-label">{{ __('messages.Loyalty_points_earn_on_each_booking') }}</label>
                                            <input type="number" id="point_earn_on_each_booking" class="form-control"
                                                name="point_earn_on_each_booking"
                                                value="{{ $setting->point_earn_on_each_booking }}"
                                                placeholder="{{ __('messages.Loyalty_points_earn_on_each_booking') }} "
                                                aria-label="Commission value">
                                        </div>
                                        <div class="col-lg-4 col-sm-6 col-xs-12 mb-2">
                                            <label for="commission"
                                                class="form-label">{{ __('messages.Minimum_points_required_to_convert') }}</label>
                                            <input type="number" id="minimum_point_required" class="form-control"
                                                name="minimum_point_required"
                                                value="{{ $setting->minimum_point_required }}"
                                                placeholder="{{ __('messages.Minimum_points_required_to_convert') }} "
                                                aria-label="Commission value">
                                        </div>
                                    </div>

                                    <button class="w-100 btn btn-primary my-3">{{ __('messages.Save_changes') }}</button>
                                </form>

                            </div>
                            <!--begin:::Tab pane-->
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show " id="Terms_and_conditions" role="tabpanel"
                                data-select2-id="select2-data-Terms_and_conditions">
                                <form action="{{ route('admin.settings.policy') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="section" value="1">

                                    <div style="display: flex; justify-content:space-between;">

                                        <h3>
                                            {{ __('messages.Terms_and_conditions') }} [{{ __('messages.Provider') }}]
                                        </h3>
                                        <label class="switch">
                                            <input {{ $setting->terms_status == 1 ? 'checked' : ' ' }} type="checkbox"
                                                name="status" value="{{ $setting->terms_status }}">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="row">

                                        <div class="col-lg-6 col-sm-12 form-floating my-2">
                                            <textarea name="term" class="form-control" placeholder="{{ __('messages.Text_en') }}" id="floatingTextarea">{{ $setting->term }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.Text_in_english') }}</label>
                                        </div>
                                        <div class="col-lg-6 col-sm-12 form-floating">
                                            <textarea name="term_ar" class="form-control" placeholder="{{ __('messages.Text_in_arabic') }}"
                                                id="floatingTextarea">{{ $setting->term_ar }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.Text_in_arabic') }}</label>
                                        </div>
                                    </div>

                                    <div class="mt-3" style="display: flex; justify-content:space-between;">

                                        <h3>
                                            {{ __('messages.Terms_and_conditions') }} [{{ __('messages.User') }}]
                                        </h3>
                                        <label class="switch">
                                            <input {{ $setting->terms_status_user == 1 ? 'checked' : ' ' }}
                                                type="checkbox" name="status_user"
                                                value="{{ $setting->terms_status_user }}">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="row">

                                        <div class="col-lg-6 col-sm-12 form-floating my-2">
                                            <textarea name="term_user" class="form-control" placeholder="{{ __('messages.Text_en') }}" id="floatingTextarea">{{ $setting->term_user }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.Text_in_english') }}</label>
                                        </div>
                                        <div class="col-lg-6 col-sm-12 form-floating">
                                            <textarea name="term_ar_user" class="form-control" placeholder="{{ __('messages.Text_in_arabic') }}"
                                                id="floatingTextarea">{{ $setting->term_ar_user }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.Text_in_arabic') }}</label>
                                        </div>
                                    </div>

                                    <button class="w-100 btn btn-primary my-3">{{ __('messages.Save_changes') }}</button>
                                </form>

                            </div>
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show " id="Refund_policy" role="tabpanel"
                                data-select2-id="select2-data-Refund_policy">

                                <form action="{{ route('admin.settings.policy') }}" method="post">
                                    @csrf

                                    <input type="hidden" name="section" value="2">
                                    <div style="display: flex; justify-content:space-between;">

                                        <h3>
                                            {{ __('messages.Refund_policy') }} [{{ __('messages.Provider') }}]
                                        </h3>
                                        <label class="switch">
                                            <input {{ $setting->refund_status == 1 ? 'checked' : ' ' }} type="checkbox"
                                                name="status" value="{{ $setting->refund_status }}">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="row">

                                        <div class="form-floating col-lg-6 col-sm-12 my-2">
                                            <textarea name="refund_policy" class="form-control" placeholder="{{ __('messages.Text_en') }}"
                                                id="floatingTextarea">{{ $setting->refund_policy }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.Text_in_english') }}</label>
                                        </div>
                                        <div class="form-floating col-lg-6 col-sm-12">
                                            <textarea name="refund_policy_ar" class="form-control" placeholder="{{ __('messages.Text_in_arabic') }}"
                                                id="floatingTextarea">{{ $setting->refund_policy_ar }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.Text_in_arabic') }}</label>
                                        </div>
                                    </div>

                                    <div class="mt-3" style="display: flex; justify-content:space-between;">

                                        <h3>
                                            {{ __('messages.Refund_policy') }} [{{ __('messages.User') }}]
                                        </h3>
                                        <label class="switch">
                                            <input {{ $setting->refund_status_user == 1 ? 'checked' : ' ' }}
                                                type="checkbox" name="status_user"
                                                value="{{ $setting->refund_status_user }}">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="row my-2">

                                        <div class="form-floating col-lg-6 col-sm-12 my-2">
                                            <textarea name="refund_policy_user" class="form-control" placeholder="{{ __('messages.Text_en') }}"
                                                id="floatingTextarea">{{ $setting->refund_policy_user }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.Text_in_english') }}</label>
                                        </div>
                                        <div class="form-floating col-lg-6 col-sm-12">
                                            <textarea name="refund_policy_ar_user" class="form-control" placeholder="{{ __('messages.Text_in_arabic') }}"
                                                id="floatingTextarea">{{ $setting->refund_policy_ar_user }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.Text_in_arabic') }}</label>
                                        </div>
                                    </div>
                                    <button class="w-100 btn btn-primary my-3">{{ __('messages.Save_changes') }}</button>
                                </form>

                            </div>
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show " id="Privacy_policy" role="tabpanel"
                                data-select2-id="select2-data-Privacy_policy">


                                <form action="{{ route('admin.settings.policy') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="section" value="3">
                                    <div style="display: flex; justify-content:space-between;">

                                        <h3>
                                            {{ __('messages.Privacy_policy') }} [{{ __('messages.Provider') }}]
                                        </h3>
                                        <label class="switch">
                                            <input {{ $setting->privacy_status == 1 ? 'checked' : ' ' }} type="checkbox"
                                                name="status" value="{{ $setting->privacy_status }}">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="row">

                                        <div class="col-lg-6 col-sm-12 form-floating my-2">
                                            <textarea name="privacy_policy" class="form-control" placeholder="{{ __('messages.Text_en') }}"
                                                id="floatingTextarea">{{ $setting->privacy_policy }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.Text_in_english') }}</label>
                                        </div>
                                        <div class="col-lg-6 col-sm-12 form-floating">
                                            <textarea name="privacy_policy_ar" class="form-control" placeholder="{{ __('messages.Text_in_arabic') }}"
                                                id="floatingTextarea">{{ $setting->privacy_policy_ar }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.Text_in_arabic') }}</label>
                                        </div>
                                    </div>
                                    <div class="mt-3" style="display: flex; justify-content:space-between;">

                                        <h3>
                                            {{ __('messages.Privacy_policy') }} [{{ __('messages.User') }}]
                                        </h3>
                                        <label class="switch">
                                            <input {{ $setting->privacy_status_user == 1 ? 'checked' : ' ' }}
                                                type="checkbox" name="status_user"
                                                value="{{ $setting->privacy_status_user }}">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="row">

                                        <div class="col-lg-6 col-sm-12 form-floating my-2">
                                            <textarea name="privacy_policy_user" class="form-control" placeholder="{{ __('messages.Text_en') }}"
                                                id="floatingTextarea">{{ $setting->privacy_policy_user }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.Text_in_english') }}</label>
                                        </div>
                                        <div class="col-lg-6 col-sm-12 form-floating">
                                            <textarea name="privacy_policy_ar_user" class="form-control" placeholder="{{ __('messages.Text_in_arabic') }}"
                                                id="floatingTextarea">{{ $setting->privacy_policy_ar_user }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.Text_in_arabic') }}</label>
                                        </div>
                                    </div>
                                    <button class="w-100 btn btn-primary my-3">{{ __('messages.Save_changes') }}</button>
                                </form>

                            </div>
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show " id="about_us" role="tabpanel"
                                data-select2-id="select2-data-Privacy_policy">

                                <form action="{{ route('admin.settings.policy') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="section" value="4">
                                    <div style="display: flex; justify-content:space-between;">

                                        <h3>
                                            {{ __('messages.About_us') }}
                                        </h3>
                                        <label class="switch">
                                            <input {{ $setting->about_us_status == 1 ? 'checked' : ' ' }} type="checkbox"
                                                name="status" value="{{ $setting->about_us_status }}">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="form-floating my-2">
                                        <textarea name="about_us" class="form-control" placeholder="{{ __('messages.Text_en') }}" id="floatingTextarea">{{ $setting->about_us }}</textarea>
                                        <label for="floatingTextarea">{{ __('messages.Text_in_english') }}</label>
                                    </div>
                                    <div class="form-floating">
                                        <textarea name="about_us_ar" class="form-control" placeholder="{{ __('messages.Text_in_arabic') }}"
                                            id="floatingTextarea">{{ $setting->about_us_ar }}</textarea>
                                        <label for="floatingTextarea">{{ __('messages.Text_in_arabic') }}</label>
                                    </div>
                                    <button class="w-100 btn btn-primary my-3">{{ __('messages.Save_changes') }}</button>
                                </form>

                            </div>
                            <div class="tab-pane fade show " id="social_media" role="tabpanel"
                                data-select2-id="select2-data-Privacy_policy">
                                <div style="text-align: right;">
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#add-social">
                                        {{ __('messages.Add') }}
                                    </button>
                                    <table class="table align-middle gs-0 gy-4" style="text-align: left !important;">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th class="ps-4 min-w-150px rounded-start">{{ __('messages.Link') }}</th>
                                                <th class="min-w-125px">{{ __('messages.Image') }}</th>
                                                <th class="min-w-125px">{{ __('messages.Status') }}</th>
                                                <th class="min-w-125px">{{ __('messages.Actions') }}</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($social_medias as $social_media)
                                                <tr>
                                                    <td>{{ $social_media->link }}</td>
                                                    <td><img src="{{ asset($social_media->image) }}" alt="">
                                                    </td>
                                                    <td>
                                                        @if ($social_media->status == 1)
                                                            <a href="{{ route('admin.settings.change_social_status', $social_media->id) }}"
                                                                class="badge badge-light-success">{{ __('messages.Yes') }}</a>
                                                        @else
                                                            <a href="{{ route('admin.settings.change_social_status', $social_media->id) }}"
                                                                class="badge badge-light-danger">{{ __('messages.No') }}</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a onclick="setData(  '{{ $social_media->id }}' , '{{ $social_media->link }}' )"
                                                            data-bs-toggle="modal" data-bs-target="#edit-social"
                                                            class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">{{ __('messages.Edit') }}</a>


                                                        <form
                                                            action="{{ route('admin.settings.delete_social_media', $social_media->id) }}"
                                                            onsubmit="return confirm('{{ __('messages.Are_you_sure_want_delete') }}');"
                                                            method="POST" style="display: inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">{{ __('messages.Delete') }}</button>
                                                        </form>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>


                            <div class="tab-pane fade show " id="Sliders" role="tabpanel"
                                data-select2-id="select2-data-Privacy_policy">
                                <div style="text-align: right;">
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#add-slider">
                                        {{ __('messages.Add') }}
                                    </button>
                                    <table class="table align-middle gs-0 gy-4" style="text-align: left !important;">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th class="ps-4 min-w-150px rounded-start">{{ __('messages.Text') }}</th>
                                                <th class="min-w-125px">{{ __('messages.Image') }}</th>
                                                <th class="min-w-125px">{{ __('messages.Actions') }}</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sliders as $slider)
                                                <tr>
                                                    <td>{{ session('lang') == 'en' ? $slider->text : $slider->text_ar }}
                                                    </td>
                                                    <td><img style="height: 60px;" src="{{ asset($slider->image) }}"
                                                            alt=""></td>
                                                    <td>
                                                        <a onclick="setDataSlider(  '{{ $slider->id }}' , '{{ $slider->text }}' , '{{ $slider->text_ar }}')"
                                                            data-bs-toggle="modal" data-bs-target="#edit-slider"
                                                            class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">{{ __('messages.Edit') }}</a>

                                                        <form
                                                            action="{{ route('admin.settings.delete_slider', $slider->id) }}"
                                                            onsubmit="return confirm('{{ __('messages.Are_you_sure_want_delete') }}');"
                                                            method="POST" style="display: inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">{{ __('messages.Delete') }}</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                            <div class="tab-pane fade show " id="propertyType" role="tabpanel"
                                data-select2-id="select2-data-Privacy_policy">
                                <div style="text-align: right;">
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#add-property-type">
                                        {{ __('messages.Add') }}
                                    </button>
                                    <table class="table align-middle gs-0 gy-4" style="text-align: left !important;">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th class="ps-4 min-w-150px rounded-start">{{ __('messages.Text') }}</th>
                                                <th class="min-w-125px">{{ __('messages.Actions') }}</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($propertyTypes as $type)
                                                <tr>
                                                    <td>{{ session('lang') == 'en' ? $type->title : $type->title_ar }}
                                                    </td>
                                                    <td>
                                                        <a onclick="setDataProperty(  '{{ $type->id }}' , '{{ $type->title }}' , '{{ $type->title_ar }}')"
                                                            data-bs-toggle="modal" data-bs-target="#edit-property-type"
                                                            class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">{{ __('messages.Edit') }}</a>
                                                        <form
                                                            action="{{ route('admin.settings.delete_property_type', $type->id) }}"
                                                            onsubmit="return confirm('{{ __('messages.Are_you_sure_want_delete') }}');"
                                                            method="POST" style="display: inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">{{ __('messages.Delete') }}</button>
                                                        </form>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>





                        </div>
                        <!--end:::Tab content-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

    </div>


    <!-- Modal -->
    <div class="modal fade" id="add-social" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.settings.add_social_media') }}" method="post" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.Add_social_media') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="link" class="form-label">{{ __('messages.Link') }} </label>

                            <input type="text" name="link" id="link" class="form-control"
                                placeholder="{{ __('messages.Link') }}" aria-label="First name">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="image" class="form-label">{{ __('messages.Image') }} </label>

                            <input type="file" name="image" id="image" class="form-control"
                                placeholder="{{ __('messages.Image') }}" aria-label="First name">
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
    <div class="modal fade" id="edit-social" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.settings.update_social_media') }}" method="post"
                enctype="multipart/form-data" class="modal-content">
                @csrf
                <input type="hidden" name="social_id" id="inp_social_id">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.Edit_social_media') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="inp_link" class="form-label">{{ __('messages.Link') }} </label>

                            <input type="text" name="link" id="inp_link" class="form-control"
                                placeholder="{{ __('messages.Link') }}" aria-label="First name">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="image" class="form-label">{{ __('messages.Update_Image') }} </label>

                            <input type="file" name="image" id="image" class="form-control"
                                placeholder="{{ __('messages.Image') }}" aria-label="First name">
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
    <div class="modal fade" id="add-slider" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.settings.add_slider') }}" method="post" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.Add_slider') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="Text" class="form-label">{{ __('messages.Text') }} </label>

                            <input type="text" name="text" id="Text" class="form-control"
                                placeholder="{{ __('messages.Text') }}" aria-label="First name">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="Text" class="form-label">{{ __('messages.Text_ar') }} </label>

                            <input type="text" name="text_ar" id="Text" class="form-control"
                                placeholder="{{ __('messages.Text_ar') }}" aria-label="First name">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="image" class="form-label">{{ __('messages.Image') }} </label>

                            <input type="file" name="image" id="image" class="form-control"
                                placeholder="{{ __('messages.Image') }}" aria-label="First name">
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
    <div class="modal fade" id="edit-slider" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.settings.update_slider') }}" method="post" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <input type="hidden" name="slider_id" id="inp_slider_id">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.Edit_slider') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="inp_text" class="form-label">{{ __('messages.Text') }} </label>

                            <input type="text" name="text" id="inp_text" class="form-control"
                                placeholder="{{ __('messages.Text') }}" aria-label="First name">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="inp_text" class="form-label">{{ __('messages.Text_ar') }} </label>

                            <input type="text" name="text_ar" id="inp_text_ar" class="form-control"
                                placeholder="{{ __('messages.Text_ar') }}" aria-label="First name">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="image" class="form-label">{{ __('messages.Update_Image') }} </label>

                            <input type="file" name="image" id="image" class="form-control"
                                placeholder="{{ __('messages.Image') }}" aria-label="First name">
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
    <div class="modal fade" id="add-property-type" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.settings.add_property_type') }}" method="post" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.Add_property_type') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="Text" class="form-label">{{ __('messages.Text') }} </label>

                            <input type="text" name="title" id="Text" class="form-control"
                                placeholder="{{ __('messages.Text') }}" aria-label="First name">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="Text" class="form-label">{{ __('messages.Text_ar') }} </label>

                            <input type="text" name="title_ar" id="Text" class="form-control"
                                placeholder="{{ __('messages.Text_ar') }}" aria-label="First name">
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
    <div class="modal fade" id="edit-property-type" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.settings.update_property_type') }}" method="post"
                enctype="multipart/form-data" class="modal-content">
                @csrf
                <input type="hidden" name="id" id="inp_property_id">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.Edit_property_type') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="inp_property_text" class="form-label">{{ __('messages.Text') }} </label>

                            <input type="text" name="title" id="inp_property_text" class="form-control"
                                placeholder="{{ __('messages.Text') }}" aria-label="First name">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="inp_text" class="form-label">{{ __('messages.Text_ar') }} </label>

                            <input type="text" name="title_ar" id="inp_property_text_ar" class="form-control"
                                placeholder="{{ __('messages.Text_ar') }}" aria-label="First name">
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


@endsection

@section('js')
    <script>
        function validateForm() {
            const input = document.getElementById('commission');
            const value = parseInt(input.value, 10);
            if (value < 0 || value > 100) {
                alert("Please enter a value between 0 and 100.");
                return false;
            }
            return true;
        }

        function setData(id, link) {
            document.getElementById('inp_social_id').value = id;
            document.getElementById('inp_link').value = link;
        }

        function setDataSlider(id, text, text_ar) {
            document.getElementById('inp_slider_id').value = id;
            document.getElementById('inp_text').value = text;
            document.getElementById('inp_text_ar').value = text_ar;
        }

        function setDataProperty(id, text, text_ar) {
            document.getElementById('inp_property_id').value = id;
            document.getElementById('inp_property_text').value = text;
            document.getElementById('inp_property_text_ar').value = text_ar;
        }
    </script>
@endsection
