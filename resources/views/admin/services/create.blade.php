@extends('admin.app')
@section('title', __('messages.Add_new_service'))
@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
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

        .top-6 {
            position: relative;
            top: 6px;
        }

        .select2-container--bootstrap5 .select2-selection--single.form-select-solid .select2-selection__rendered {
            margin: 0 10px !important;
        }

        .select2-container .select2-selection--single .select2-selection__clear {
            left: 6px !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus/dist/css/tempus-dominus.css" />

    <script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus/dist/js/tempus-dominus.js"></script>
    <style>
        .form-floating>.form-control:focus,
        .form-floating>.form-control:not(:placeholder-shown),
        .form-floating>.form-control-plaintext:focus,
        .form-floating>.form-control-plaintext:not(:placeholder-shown) {
            padding-top: 2rem;
            padding-bottom: 0.625rem;
            /* margin-top: 42px; */
            position: relative;
            top: 20px;
        }
    </style>
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        /* Style for Select2 dropdowns when invalid */
        .select2-container--default .select2-selection--single.is-invalid {
            border-color: #dc3545 !important;
        }

        .select2-container--default .select2-selection--multiple.is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
@endsection
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
 
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        {{ __('messages.Add_new_service') }}</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a class="text-muted text-hover-primary">{{ __('messages.Services') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">{{ __('messages.Add_new_service') }}</li>
                    </ul>
                </div>

            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card">
                    <form enctype="multipart/form-data" action="{{ route('admin.services.store') }}" id="add-service-form"
                        method="post" novalidate>
                        <div class="card-body p-lg-17">
                            @csrf
                            <div class="row my-5">
                                <div class="col">
                                    {{ old('name_en') }}
                                    <label for="name_en" class="form-label">{{ __('messages.Name_en') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <input required type="text" class="form-control" name="name_en" id="name_en"
                                        placeholder="{{ __('messages.Name_en') }}" value="{{ old('name_en') }}">
                                </div>
                                <div class="col">
                                    <label for="name_ar" class="form-label">{{ __('messages.Name_ar') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <input required type="text" class="form-control" name="name_ar" id="name_ar"
                                        placeholder="{{ __('messages.Name_ar') }}" aria-label="Last name"
                                        value="{{ old('name_ar') }}">
                                </div>
                            </div>
                            <div class="row my-5">
                                <div class="col">
                                    <label for="place_en" class="form-label">{{ __('messages.Place_en') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <input required type="text" class="form-control" name="place_en" id="place_en"
                                        placeholder="{{ __('messages.Place_en') }}" value="{{ old('place_en') }}">
                                </div>
                                <div class="col">
                                    <label for="place_ar" class="form-label">{{ __('messages.Place_ar') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <input required type="text" class="form-control" name="place_ar" id="place_ar"
                                        placeholder="{{ __('messages.Place_ar') }}" aria-label="Last name"
                                        value="{{ old('place_ar') }}">
                                </div>
                            </div>
                            <div class="row my-5">
                                <div class="col">
                                    <label for="category_id" class="form-label">{{ __('messages.categories') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <select required class="form-select" name="category_id" id="category_id"
                                        aria-label="Default select example">
                                        <option disabled value="" {{ old('category_id') == '' ? 'selected' : '' }}>
                                            {{ __('messages.categories') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ app()->getLocale() == 'en' ? $category->brand_name : $category->brand_name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="provider_id" class="form-label">{{ __('messages.Provider') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <select required class="form-select" name="provider_id" id="provider_id"
                                        aria-label="Default select example">
                                        <option disabled value="" {{ old('provider_id') == '' ? 'selected' : '' }}>
                                            {{ __('messages.Provider') }}</option>
                                        @foreach ($providers as $provider)
                                            <option value="{{ $provider->id }}"
                                                {{ old('provider_id') == $provider->id ? 'selected' : '' }}>
                                                {{ $provider->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row my-5">
                                <div class="col">
                                    <label for="living_room" class="form-label">{{ __('messages.living_room') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <input required type="number" class="form-control" name="living_room"
                                        id="living_room" placeholder="{{ __('messages.living_room') }}"
                                        aria-label="First name" value="{{ old('living_room') }}">
                                </div>
                                <div class="col">
                                    <label for="bed" class="form-label">{{ __('messages.Bed_rooms') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <input required type="number" class="form-control" name="bed" id="bed"
                                        placeholder="{{ __('messages.Bed_rooms') }}" aria-label="Last name"
                                        value="{{ old('bed') }}">
                                </div>
                                <div class="col">
                                    <label for="bath" class="form-label">{{ __('messages.Bath_rooms') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <input required type="number" class="form-control" name="bath" id="bath"
                                        placeholder="{{ __('messages.Bath_rooms') }}" aria-label="Last name"
                                        value="{{ old('bath') }}">
                                </div>
                                <div class="col">
                                    <label for="property_size" class="form-label">{{ __('messages.property_size') }}
                                        <span class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <input required type="number" class="form-control" name="property_size"
                                        id="property_size" placeholder="{{ __('messages.property_size') }}"
                                        aria-label="Last name" value="{{ old('property_size') }}">
                                </div>
                            </div>
                            <div class="row my-5">
                                <div class="col">
                                    <label for="price" class="form-label">{{ __('messages.Price') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <input required type="number" class="form-control" name="price" id="price"
                                        placeholder="{{ __('messages.Price') }}" aria-label="First name"
                                        value="{{ old('price') }}">
                                </div>
                            </div>
                            <div class="row my-5">
                                <div class="col-4 my-2">
                                    <label for="city_id" class="form-label">{{ __('messages.City') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <div class="menu menu-sub menu-sub-dropdown w-100" style="display: block;"
                                        data-kt-menu="true" id="cities">
                                        <select required name="city_id" id="city_id"
                                            class="form-select form-select-solid" data-kt-select2="true"
                                            data-close-on-select="false" data-placeholder="{{ __('messages.City') }}"
                                            data-dropdown-parent="#cities" data-allow-clear="true">
                                            <option disabled value="" {{ old('city_id') == '' ? 'selected' : '' }}>
                                                {{ __('messages.City') }}</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}"
                                                    {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                    {{ session('lang') == 'en' ? $city->name_en : $city->name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4 my-2">
                                    <label for="property" class="form-label">{{ __('messages.Property') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <div class="menu menu-sub menu-sub-dropdown w-100" style="display: block;"
                                        data-kt-menu="true" id="property_select">
                                        <select required name="property" id="property"
                                            class="form-select form-select-solid" data-kt-select2="true"
                                            data-close-on-select="false" data-placeholder="{{ __('messages.Property') }}"
                                            data-dropdown-parent="#property_select" data-allow-clear="true">
                                            <option disabled value="" {{ old('property') == '' ? 'selected' : '' }}>
                                                {{ __('messages.Property') }}</option>
                                            <option value="house" {{ old('property') == 'house' ? 'selected' : '' }}>
                                                house</option>
                                            <option value="apartment"
                                                {{ old('property') == 'apartment' ? 'selected' : '' }}>apartment</option>
                                            <option value="villa" {{ old('property') == 'villa' ? 'selected' : '' }}>
                                                villa</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4 my-2">
                                    <label for="duration" class="form-label">{{ __('messages.Duration') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <div class="menu menu-sub menu-sub-dropdown w-100" style="display: block;"
                                        data-kt-menu="true" id="duration_select">
                                        <select required name="duration" id="duration"
                                            class="form-select form-select-solid" data-kt-select2="true"
                                            data-close-on-select="false" data-placeholder="{{ __('messages.Duration') }}"
                                            data-dropdown-parent="#duration_select" data-allow-clear="true">
                                            <option disabled value="" {{ old('duration') == '' ? 'selected' : '' }}>
                                                {{ __('messages.Duration') }}</option>
                                            <option value="daily" {{ old('duration') == 'daily' ? 'selected' : '' }}>
                                                daily</option>
                                            <option value="weekly" {{ old('duration') == 'weekly' ? 'selected' : '' }}>
                                                weekly</option>
                                            <option value="monthly" {{ old('duration') == 'monthly' ? 'selected' : '' }}>
                                                monthly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6 my-2">
                                    <label for="property_type" class="form-label">{{ __('messages.Property_type') }}
                                        <span class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <div class="menu menu-sub menu-sub-dropdown w-100" style="display: block;"
                                        data-kt-menu="true" id="property_type_select">
                                        <select required name="property_type" id="property_type"
                                            class="form-select form-select-solid" data-kt-select2="true"
                                            data-close-on-select="false"
                                            data-placeholder="{{ __('messages.Property_type') }}"
                                            data-dropdown-parent="#property_type_select" data-allow-clear="true">
                                            <option disabled value=""
                                                {{ old('property_type') == '' ? 'selected' : '' }}>
                                                {{ __('messages.Property_type') }}</option>
                                            <option value="Compound"
                                                {{ old('property_type') == 'Compound' ? 'selected' : '' }}>Compound
                                            </option>
                                            <option value="Not Compound"
                                                {{ old('property_type') == 'Not Compound' ? 'selected' : '' }}>Not Compound
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-6 my-2">
                                    <label for="rating" class="form-label">{{ __('messages.Rating') }}</label>
                                    <input type="number" class="form-control" name="rating" id="rating"
                                        placeholder="{{ __('messages.Rating') }}" min="0" max="5"
                                        step="0.1" value="{{ old('rating') }}">
                                </div> --}}
                            </div>
                            <div class="row my-5">
                                <div class="col mb-3">
                                    <label for="description_en">{{ __('messages.Description_en') }}</label>
                                    <div>
                                        <textarea name="description_en" class="form-control" placeholder="{{ __('messages.Description_en') }}"
                                            id="description_en" style="height: 100px">{{ old('description_en') }}</textarea>
                                    </div>
                                </div>
                                <div class="col mb-3">
                                    <label for="description_ar">{{ __('messages.Description_ar') }}</label>
                                    <div>
                                        <textarea name="description_ar" class="form-control" placeholder="{{ __('messages.Description_ar') }}"
                                            id="description_ar" style="height: 100px">{{ old('description_ar') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-5">
                                <div class="col">
                                    <label for="image" class="form-label mx-1">{{ __('messages.Image') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <input required type="file" name="image" class="form-control" id="image"
                                        placeholder="{{ __('messages.Image') }}" aria-label="First name">
                                </div>
                            </div>
                            <div class="row my-5">
                                <h2 class="col-12" style="display: flex;    align-items: center;">
                                    {{ __('messages.Gallery') }}
                                    <button id="updateButton" type="submit" class="mx-3 btn btn-success"
                                        style="display: none;">{{ __('messages.Save_changes') }}</button>
                                </h2>
                                <div class="col-lg-3 col-sm-12">
                                    <label for="galleries" class="fv-row mb-2"
                                        style="height: 100% ; width:100%;display: flex;justify-content: center;">
                                        <div class="dropzone dz-clickable" id="kt_ecommerce_add_product_media"
                                            style="    display: flex;justify-content: center;align-items: center;">
                                            <div class="dz-message needsclick">
                                                <i class="ki-duotone ki-file-up text-primary fs-3x">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <div class="ms-4">
                                                    <h3 class="fs-5 fw-bold text-gray-900 mb-1" id="file-label">
                                                        {{ __('messages.Click_to_upload') }}</h3>
                                                    <span
                                                        class="fs-7 fw-semibold text-gray-400">{{ __('messages.upload_up_to_10_files') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    <input type="file" hidden name="galleries[]" accept="image/*,video/*"
                                        id="galleries" multiple>
                                </div>
                                <div class="col-lg-9 col-sm-12 mt-2" id="files-names-galleries"></div>

                            </div>
                            <div class="row my-5">
                                <div class="col">
                                    <label for="Document" class="form-label mx-1">{{ __('messages.Documnet') }} <span
                                            class="text-danger">*{{ __('messages.Required') }}</span></label>
                                    <input required type="file" name="document" class="form-control" id="Document"
                                        placeholder="{{ __('messages.Documnet') }}" aria-label="First name">
                                </div>
                            </div>
                        </div>

                        <div class="form-group card-body">

                            <div class=" my-5">
                                <div style="display: inline-block;" class="mx-3">
                                    <label for="accept"
                                        class="form-label mx-1 top-6">{{ __('messages.Accept') }}</label>
                                    <label class="switch">
                                        <input type="checkbox" id="accept" name="accept"
                                            {{ old('accept') ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="container mt-3">
                                <label for="datePicker">{{ __('messages.Calendar') }} <span
                                        class="text-danger">*{{ __('messages.Required') }}</span></label>
                                <div class="input-group date" id="datePicker" data-td-target-input="nearest">
                                    <span class="avaliable-span"> {{ __('messages.See_Workings_days') }}</span>

                                    <input onchange="log()" required name="days" type="text"
                                        class="form-control days" data-td-target="#datePicker"
                                        value="{{ old('days') }}" />
                                    <div class="input-group-append" data-td-target="#datePicker"
                                        data-td-toggle="datetimepicker">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
                                style="display: block;box-shadow: none;" data-kt-menu="true" id="kt_menu_64b77630f13b9">
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-dark fw-bold">{{ __('messages.Event_days') }}</div>
                                </div>
                                <div class="px-7 py-5">
                                    <div class="mb-10">
                                        <label for="selectDays"
                                            class="form-label">{{ __('messages.Select_Days') }}</label>
                                        <div>
                                            <select class="form-select form-select-solid selectDays" multiple="multiple"
                                                data-kt-select2="true" data-close-on-select="false"
                                                data-placeholder="{{ __('messages.Select_Option') }}"
                                                data-dropdown-parent="#kt_menu_64b77630f13b9" data-allow-clear="true"
                                                id="selectDays">
                                                <option value=""></option>
                                                @if (old('event_days'))
                                                    @foreach (json_decode(old('event_days')) as $old_event_day)
                                                        <option value="{{ $old_event_day }}" selected>
                                                            {{ $old_event_day }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <input type="hidden" name="event_days" id="event_days"
                                            value="{{ old('event_days') }}">
                                        <div class="mb-3">
                                            <label for="priceInput">{{ __('messages.Price') }}</label>
                                            <input type="number" class="form-control priceInput" name="event_day_price"
                                                id="priceInput" placeholder="{{ __('messages.Price') }}"
                                                value="{{ old('event_day_price') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px"
                                style="display: block;box-shadow: none;" data-kt-menu="true" id="kt_menu_64b77630f13b91">
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-dark fw-bold">{{ __('messages.Features') }}</div>
                                </div>
                                <div class="px-7 py-5">
                                    <div class="mb-10">
                                        <label for="selectFeatures"
                                            class="form-label">{{ __('messages.Select_Features') }}</label>
                                        <div>
                                            <select class="form-select form-select-solid selectFeatures"
                                                multiple="multiple" data-kt-select2="true" data-close-on-select="false"
                                                data-placeholder="{{ __('messages.Select_Option') }}"
                                                data-dropdown-parent="#kt_menu_64b77630f13b91" data-allow-clear="true"
                                                id="selectFeatures">
                                                <option value=""></option>
                                                @foreach ($features as $feature)
                                                    <option value="{{ $feature->id }}"
                                                        {{ in_array($feature->id, old('features_id') ? json_decode(old('features_id')) : []) ? 'selected' : '' }}>
                                                        {{ session('lang') == 'en' ? $feature->feature_name : $feature->feature_name_ar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="features_id" id="features_id"
                                            value="{{ old('features_id') }}">
                                    </div>

                                </div>
                            </div>
                            <div class="container my-5">
                                <label for="map" class="form-label">{{ __('messages.Location') }} <span
                                        class="text-danger">*{{ __('messages.Required') }}</span></label>
                                <input required type="hidden" id="latitude" name="latitude"
                                    value="{{ old('latitude') }}">
                                <input required type="hidden" id="longitude" name="longitude"
                                    value="{{ old('longitude') }}">
                                <div id="map" style="height: 300px;"></div>
                            </div>
                            <div>
                                <button type="button" onclick="submitForm()"
                                    class="btn btn-primary w-100">{{ __('messages.Add') }}</button>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>
        document.getElementById('galleries').addEventListener('change', function() {
            var fileInput = document.getElementById('galleries');
            var filesContainer = document.getElementById('files-names-galleries');

            // Clear any previous content
            filesContainer.innerHTML = '';

            // Loop through selected files
            Array.from(fileInput.files).forEach(file => {
                var reader = new FileReader();

                // Check if the file is an image
                if (file.type.startsWith('image/')) {
                    reader.onload = function(e) {
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '100px'; // Set a max width for the preview
                        img.style.margin = '5px'; // Add some margin
                        filesContainer.appendChild(img); // Append image to the container
                    };
                    reader.readAsDataURL(file); // Read the image file as a data URL

                    // Check if the file is a video
                } else if (file.type.startsWith('video/')) {
                    reader.onload = function(e) {
                        var video = document.createElement('video');
                        video.src = e.target.result;
                        video.controls = true;
                        video.style.maxWidth = '150px'; // Set a max width for the preview
                        video.style.margin = '5px'; // Add some margin
                        filesContainer.appendChild(video); // Append video to the container
                    };
                    reader.readAsDataURL(file); // Read the video file as a data URL

                } else {
                    // Display file name for non-image, non-video files
                    var fileNameDiv = document.createElement('div');
                    fileNameDiv.textContent = file.name;
                    filesContainer.appendChild(fileNameDiv);
                }
            });
        });
    </script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([0, 0], 2); // Center on a default location

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var marker;

        map.on('click', function(e) {
            if (marker) {
                marker.setLatLng(e.latlng); // Update marker position
            } else {
                marker = L.marker(e.latlng).addTo(map); // Add a new marker
            }

            document.getElementById('latitude').value = e.latlng.lat; // Store lat in input
            document.getElementById('longitude').value = e.latlng.lng; // Store long in input
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const datePicker = new tempusDominus.TempusDominus(document.getElementById('datePicker'), {
                display: {
                    viewMode: 'calendar',
                    components: {
                        calendar: true,
                        date: true,
                        month: true,
                        year: true,
                        decades: true
                    },
                    inline: false
                },
                multipleDates: true // Enable multi-date selection
            });



        });
    </script>
    <script>
        function log() {
            let days = document.querySelector('.days').value;
            console.log(days);

            let selectElement = document.querySelector('.selectDays');

            // Empty the select before updating
            selectElement.innerHTML = '';

            if (days) {
                // Step 1: Split and format the dates into a clean array
                let datesArray = days
                    .split(';')
                    .map(date => moment(date.trim(), 'MM/DD/YYYY')) // parse with moment
                    .filter(m => m.isValid());

                // Step 2: Sort the dates
                datesArray.sort((a, b) => a.toDate() - b.toDate());

                // Step 3: Loop over sorted array and add to select
                datesArray.forEach(m => {
                    let formattedDate = m.format('MM/DD/YYYY');

                    let newOption = document.createElement('option');
                    newOption.text = formattedDate;
                    newOption.value = formattedDate;
                    selectElement.appendChild(newOption);
                });
            }
        }



        function submitForm() {
            let selectElement = document.querySelector('.selectDays');
            let selectedOptions = Array.from(selectElement.selectedOptions).map(option => option.value);

            document.getElementById('event_days').value = JSON.stringify(selectedOptions);



            let selectFeatures = document.querySelector('.selectFeatures');
            let selectedOptionsFeatures = Array.from(selectFeatures.selectedOptions).map(option => option.value);
            console.log(JSON.stringify(selectedOptionsFeatures))
            document.getElementById('features_id').value = JSON.stringify(selectedOptionsFeatures);

            const form = document.getElementById('add-service-form');


            if (form.checkValidity()) {
                // Submit the form if valid
                form.submit();
            } else {
                // Trigger native HTML5 form validation messages
                form.reportValidity();
            }
            // Now you can submit the form
        }
    </script>


@endsection
