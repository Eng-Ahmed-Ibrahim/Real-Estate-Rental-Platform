@extends('admin.app')
@section('title', trans('messages.Profile'))
@section('content')
    <div class="d-flex flex-column flex-column-fluid">

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
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">{{ __('messages.Profile') }}</li>
                    </ul>
                </div>

            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card mb-5 mb-xl-10">
                    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                        data-bs-target="#kt_account_profile_details" aria-expanded="true"
                        aria-controls="kt_account_profile_details">
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">{{ __('messages.Profile_Details') }}</h3>
                        </div>
                    </div>
                    <div id="kt_account_settings_profile_details" class="collapse show">
                        <form enctype="multipart/form-data" id="kt_account_profile_details_form" method="post"
                            action="{{ route('admin.profile.update', $user->id) }}"
                            class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                            @csrf
                            <input type="hidden" name="section" value="1">
                            <div class="card-body border-top p-9">
                                <div class="row mb-6">
                                    <label
                                        class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('messages.Avatar') }}</label>
                                    <div class="col-lg-8">
                                        <div class="image-input image-input-outline" data-kt-image-input="true"
                                            style="background-image: url('/{{ $user->image }}')">
                                            <div class="image-input-wrapper w-125px h-125px"
                                                style="background-image: url(/{{ $user->image }})"></div>
                                            <label
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                aria-label="Change avatar" data-bs-original-title="Change avatar"
                                                data-kt-initialized="1">
                                                <i class="ki-duotone ki-pencil fs-7">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <input type="file" name="image" accept=".png, .jpg, .jpeg">
                                                <input type="hidden" name="avatar_remove">
                                            </label>
                                            <span
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                aria-label="Cancel avatar" data-bs-original-title="Cancel avatar"
                                                data-kt-initialized="1">
                                                <i class="ki-duotone ki-cross fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                            <span
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                aria-label="Remove avatar" data-bs-original-title="Remove avatar"
                                                data-kt-initialized="1">
                                                <i class="ki-duotone ki-cross fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label
                                        class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __('messages.Full_name') }}</label>
                                    <div class="col-lg-8">
                                        <div class="row">

                                            <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                                <input required type="text" value='{{ $user->name }}' name="name"
                                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                    placeholder="{{ __('messages.Full_name') }}">
                                                <div
                                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @if ($user->power != 'customer')
                                    <div class="row mb-6">
                                        <label
                                            class="col-lg-4 col-form-label required fw-semibold fs-6">{{ __('messages.Role') }}</label>
                                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                            <select name="role" id="" class="form-control">
                                                <option disabled selected>{{ __('messages.Roles') }}</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}"
                                                        {{ $user->roles->first() ? ($user->roles->first()->id == $role->id ? 'selected' : ' ') : ' ' }}>
                                                        {{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">
                                        <span class="required">{{ __('messages.Email') }}</span>

                                    </label>
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                        <input required type="email" name="email"
                                            class="form-control form-control-lg form-control-solid"
                                            placeholder="{{ __('messages.Email') }}" value="{{ $user->email }}">
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                <button type="reset"
                                    class="btn btn-light btn-active-light-primary me-2">{{ __('messages.Discard') }}</button>
                                @if (auth()->user()->can('edit user') || $user->id == auth()->user()->id)
                                    <button type="submit" class="btn btn-primary"
                                        id="kt_account_profile_details_submit">{{ __('messages.Save_changes') }}</button>
                                @endif
                            </div>
                            <input type="hidden">
                        </form>
                    </div>
                </div>
                <div class="card mb-5 mb-xl-10">
                    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                        data-bs-target="#kt_account_signin_method">
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">{{ __('messages.Sign-in_Method') }}</h3>
                        </div>
                    </div>
                    <div id="kt_account_settings_signin_method" class="collapse show">
                        <div class="card-body border-top p-9">
                            <div class="d-flex flex-wrap align-items-center">
                                <div id="kt_signin_email">
                                    <div class="fs-6 fw-bold mb-1">{{ __('messages.Phone') }}</div>
                                    <div class="fw-semibold text-gray-600">{{ $user->phone }}</div>
                                </div>
                                <div id="kt_signin_email_edit" class="flex-row-fluid d-none">
                                    <form method="post" action="{{ route('admin.profile.update', $user->id) }}"
                                        id="kt_signin_change_email"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                                        @csrf
                                        <input type="hidden" name="section" value="2">
                                        <div class="row mb-6">
                                            <div class="col-lg-6 mb-4 mb-lg-0">
                                                <div class="fv-row mb-0 fv-plugins-icon-container">
                                                    <label for="phone"
                                                        class="form-label fs-6 fw-bold mb-3">{{ __('messages.Enter_New_Phone_Address') }}</label>
                                                    <input required type="tel"
                                                        class="form-control form-control-lg form-control-solid"
                                                        id="phone" placeholder="{{ __('messages.phone') }}"
                                                        name="phone" value="{{ $user->phone }}">
                                                    <div
                                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="fv-row mb-0 fv-plugins-icon-container">
                                                    <label for="pass"
                                                        class="form-label fs-6 fw-bold mb-3">{{ __('messages.Confirm_password') }}</label>
                                                    <input required type="password"
                                                        class="form-control form-control-lg form-control-solid"
                                                        name="confirmemailpassword" id="pass">
                                                    <div
                                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="d-flex">
                                            @if (auth()->user()->can('edit user') || $user->id == auth()->user()->id)
                                                <button type="submit"
                                                    class="btn btn-primary me-2 px-6">{{ __('messages.Update_phone') }}</button>
                                            @endif
                                            <button id="kt_signin_cancel" type="button"
                                                class="btn btn-color-gray-400 btn-active-light-primary px-6">{{ __('messages.Cancel') }}</button>
                                        </div>
                                    </form>
                                </div>

                                <div id="kt_signin_email_button" class="ms-auto">
                                    <button
                                        class="btn btn-light btn-active-light-primary">{{ __('messages.Change_phone') }}</button>
                                </div>
                            </div>
                            <div class="separator separator-dashed my-6"></div>
                            <div class="d-flex flex-wrap align-items-center mb-10">
                                <div id="kt_signin_password">
                                    <div class="fs-6 fw-bold mb-1">{{ __('messages.Confirm_password') }}</div>
                                    <div class="fw-semibold text-gray-600">************</div>
                                </div>
                                <div id="kt_signin_password_edit" class="flex-row-fluid d-none">
                                    <form method="post" action="{{ route('admin.profile.update', $user->id) }}"
                                        id="kt_signin_change_password"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                                        @csrf
                                        <input type="hidden" name="section" value="3">
                                        <div class="row mb-1">

                                            <div class="col-lg-4">
                                                <div class="fv-row mb-0 fv-plugins-icon-container">
                                                    <label for="new_password" class="form-label fs-6 fw-bold mb-3">
                                                        {{ __('messages.New_Password') }}</label>
                                                    <input required type="password"
                                                        class="form-control form-control-lg form-control-solid"
                                                        name="new_password" id="new_password">
                                                    <div
                                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="fv-row mb-0 fv-plugins-icon-container">
                                                    <label for="confirm_password" class="form-label fs-6 fw-bold mb-3">
                                                        {{ __('messages.Confirm_New_Password') }}</label>
                                                    <input required type="password"
                                                        class="form-control form-control-lg form-control-solid"
                                                        name="confirm_password" id="confirm_password">
                                                    <div
                                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-text mb-5">{{ __('messages.Validate_password_min_char') }}</div>
                                        <div class="d-flex">
                                            @if (auth()->user()->can('edit user') || $user->id == auth()->user()->id)
                                                <button type="submit"
                                                    class="btn btn-primary me-2 px-6">{{ __('messages.Update_Password') }}</button>
                                            @endif
                                            <button id="kt_password_cancel" type="button"
                                                class="btn btn-color-gray-400 btn-active-light-primary px-6">Cancel</button>
                                        </div>
                                    </form>
                                </div>

                                <div id="kt_signin_password_button" class="ms-auto">
                                    <button
                                        class="btn btn-light btn-active-light-primary">{{ __('messages.Reset_Password') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection

    @section('js')
        <script src="{{ asset('assets/js/custom/account/settings/signin-methods.js') }}"></script>

    @endsection
