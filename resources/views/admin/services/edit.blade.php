@extends('admin.app')
@section('title',__('messages.Edit_service') )
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

    .img-slider__container-1 {
        display: flex;
        column-gap: 20px;
        width: 100%;
        overflow: auto;
        justify-content: start;
        align-items: center;

        .img-items {
            width: 100%;

            img {
                width: var(--img-width);

                &.img-active {
                    transform: translateX(-100%);
                }
            }
        }
    }

    .menu-sub-dropdown {

        box-shadow: none !important;

    }

    .breadcrumb{
        background-color: #5C5BE5 !important;
        color: white !important;
    }


    .menu-title   .ki-duotone, .ki-outline, .ki-solid {
        line-height: 1;
        font-size: 1rem;
        color: var(--bs-text-muted);
        position: absolute;
        left: -28px !important;
    }
    
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempus-dominus/6.0.0/tempus-dominus.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempus-dominus/6.0.0/tempus-dominus.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

@endsection
@section('content')
<div class="d-flex flex-column flex-column-fluid">

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Edit_service')}}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a class="" style="color: white !important;">{{__('messages.Services')}}</a>
                    </li>

                    <li class="breadcrumb-item ">{{__('messages.Edit_service')}}</li>
                </ul>

            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <div class="m-0">

                </div>
                @if($service->duration != 'monthly')
                <button data-bs-toggle="modal" data-bs-target="#eventDays" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">{{__('messages.Event_days')}}</button>
                @endif
                <button data-bs-toggle="modal" data-bs-target="#add-feature" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">{{__('messages.Add_feature')}}</button>
                <button data-bs-toggle="modal" data-bs-target="#add-booking" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">{{__('messages.Add_booking')}}</button>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card" style="border:none">
                <div class="card-body p-lg-17">



                    <form class="card-body" enctype="multipart/form-data" action="{{route('admin.services.update',$service->id)  }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row my-5">

                            <div class="container mt-3">
                                <label for="datePicker"> {{__('messages.Available_days')}}</label>
                                <div class="input-group date" id="datePicker" data-target-input="nearest">
                                    <span class="avaliable-span"> {{__('messages.See_Workings_days')}}</span>
                                    <input type="text" id="inp-days" name="days" class="form-control" data-target="#datePicker" style="color:white;" />
                                    <div class="input-group-append" style="height: 100%;" data-target="#datePicker" data-toggle="datetimepicker">
                                        <span class="input-group-text" style="height: 100%;"><i style="height: 100%;" class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('admin.layouts.calendar')

                        <div class="row my-5">
                            <div class="col">
                                <input required value="{{$service->name}}" type="text" class="form-control" name="name_en" placeholder="{{__('messages.Name_en')}}" aria-label="First name">
                            </div>
                            <div class="col">
                                <input required value="{{$service->name_ar}}" type="text" class="form-control" name="name_ar" placeholder="{{__('messages.Name_ar')}}" aria-label="Last name">
                            </div>
                        </div>
                        <div class="row my-5">
                            <div class="col">
                                <input required type="text" value="{{$service->place}}" class="form-control" name="place_en" placeholder="{{__('messages.Place_en')}}" aria-label="First name">
                            </div>
                            <div class="col">
                                <input required type="text" value="{{$service->place_ar}}" class="form-control" name="place_ar" placeholder="{{__('messages.Place_ar')}}" aria-label="Last name">
                            </div>
                        </div>
                        <div class="row my-5">
                            <div class="col">
                                <select required class="form-select" name="category_id" aria-label="Default select example">
                                    <option selected disabled>{{__('messages.categories')}}</option>
                                    @foreach($categories as $category)
                                    @if($category->id==$service->category_id)
                                    <option selected value="{{$category->id}}">{{app()->getLocale() == 'en' ? $category->brand_name : $category->brand_name_ar}}</option>
                                    @else
                                    <option value="{{$category->id}}">{{app()->getLocale() == 'en' ? $category->brand_name : $category->brand_name_ar}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <select required class="form-select" name="provider_id" aria-label="Default select example">
                                    <option selected disabled>{{__('messages.Provider')}}</option>
                                    @foreach($providers as $provider)
                                    @if($provider->id ==$service->user_id)
                                    <option selected value="{{$provider->id}}">{{$provider->name}}</option>
                                    @else
                                    <option value="{{$provider->id}}">{{$provider->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>





                        <div class="row my-5">
                            <div class="col">
                                <input required value="{{$service->living_room}}" type="number" class="form-control" name="living_room" placeholder="{{__('messages.living_room')}}" aria-label="First name">
                            </div>
                            <div class="col">
                                <input required type="number" value="{{$service->bed}}" class="form-control" name="bed" placeholder="{{__('messages.Bed_rooms')}}" aria-label="Last name">
                            </div>
                            <div class="col">
                                <input required type="number" value="{{$service->bath}}" class="form-control" name="bath" placeholder="{{__('messages.Bath_rooms')}}" aria-label="Last name">
                            </div>
                            <div class="col">
                                <input required type="number" value="{{$service->property_size}}" class="form-control" name="property_size" placeholder="{{__('messages.property_size')}}" aria-label="Last name">
                            </div>
                        </div>
                        <div class="row my-5">
                            <div class="col">
                                <input required type="number" value="{{$service->price}}" class="form-control" name="price" placeholder="{{__('messages.Price')}}" aria-label="First name">
                            </div>
                        </div>
                        <div class="row my-5">
                            <div class="col-4 my-2">
                                <div class="menu menu-sub menu-sub-dropdown w-100" style="display: block;" data-kt-menu="true" id="cities">

                                    <select name="city_id" class="form-select ">

                                        @foreach($cities as $city)
                                        <option value="{{$city->id}}" {{$city->id == $service->city_id ? 'selected' : ' '}}>{{session('lang')=='en'?$city->name_en:$city->name_ar}}</option>
                                        @endforeach


                                    </select>

                                </div>
                            </div>
                            <div class="col-4 my-2">
                                <div class="menu menu-sub menu-sub-dropdown w-100" style="display: block;" data-kt-menu="true">

                                    <select name="property" class="form-select ">
                                        <option value="house" {{ $service->property =="house" ? 'selected' : ' '}}>house</option>
                                        <option value="apartment" {{ $service->property =="apartment" ? 'selected' : ' '}}>apartment</option>
                                        <option value="villa" {{ $service->property =="villa" ? 'selected' : ' '}}>villa</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-4 my-2">
                                <div class="menu menu-sub menu-sub-dropdown w-100" style="display: block;" data-kt-menu="true">

                                    <select name="duration" class="form-select ">
                                        <option value="daily" {{ $service->duration =="daily" ? 'selected' : ' '}}>daily</option>
                                        <option value="weekly" {{ $service->duration =="weekly" ? 'selected' : ' '}}>weekly</option>
                                        <option value="monthly" {{ $service->duration =="monthly" ? 'selected' : ' '}}>monthly</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-6 my-2">
                                <div class="menu menu-sub menu-sub-dropdown w-100" style="display: block;" data-kt-menu="true">

                                    <select name="property_type" class="form-select ">
                                        <option value="Compound" {{ $service->property_type == "Compound" ? 'selected' : '' }}>Compound</option>
                                        <option value="Not Compound" {{ $service->property_type =="Not Compound" ? 'selected' : ' '}}>Not Compound</option>
                                    </select>

                                </div>
                            </div>

                        </div>
                        <div class="row my-5">
                            <div class="col mb-3">
                                <label for="floatingTextarea2">{{__('messages.Description_en')}}</label>
                                <div class="">
                                    <textarea required name="description_en" class="form-control" placeholder="{{__('messages.Description_en')}}" id="floatingTextarea2" style="height: 100px">{{$service->description}}</textarea>
                                </div>
                            </div>
                            <div class="col mb-3">
                                <label for="floatingTextarea2">{{__('messages.Description_ar')}}</label>
                                <div class="">
                                    <textarea required name="description_ar" class="form-control" placeholder="{{__('messages.Description_ar')}}" id="floatingTextarea2" style="height: 100px">{{$service->description_ar}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row my-5">
                            <div class="col">
                                <img src="/{{$service->image}}" style="height: 100px; width:100px" alt="">
                                <label for="image" class="form-label mx-1">{{__('messages.Image')}}</label>
                                <input type="file" name="image" class="form-control" name="image" id="image" placeholder="{{__('messages.Image')}}" aria-label="First name">
                            </div>
                        </div>
                        <div class="row my-5">
                            <h2 class="col-12" style="display: flex;    align-items: center;">
                                {{__('messages.Gallery')}}
                                <button id="updateButton" type="submit" class="mx-3 btn btn-success" style="display: none;">{{__("messages.Save_changes")}}</button>
                            </h2>
                            <div class="col-3">
                                <label for="galleries" class="fv-row mb-2" style="height: 100% ; width:100%;display: flex;justify-content: center;">
                                    <div class="dropzone dz-clickable" id="kt_ecommerce_add_product_media" style="    display: flex;justify-content: center;align-items: center;">
                                        <div class="dz-message needsclick">
                                            <i class="ki-duotone ki-file-up text-primary fs-3x">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            <div class="ms-4">
                                                <h3 class="fs-5 fw-bold text-gray-900 mb-1">{{__('messages.Click_to_upload')}}</h3>
                                                <span class="fs-7 fw-semibold text-gray-400">{{__('messages.upload_up_to_10_files')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                <input type="file" hidden name="galleries[]" id="galleries" multiple>
                                <script>
                                    const fileInput = document.getElementById('galleries');
                                    const updateButton = document.getElementById('updateButton');

                                    fileInput.addEventListener('change', function() {
                                        if (this.files.length > 0) {
                                            updateButton.style.display = 'block';
                                        } else {
                                            updateButton.style.display = 'none';
                                        }
                                    });
                                </script>
                            </div>
                            <div class="col-9">
                                <div class="img-slider__container-1">
                                    @foreach($service->gallery as $media)
                                    @php
                                    // Get the file extension
                                    $extension = pathinfo($media->path, PATHINFO_EXTENSION);

                                    // Define arrays for image and video extensions
                                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                    $videoExtensions = ['mp4', 'mov', 'avi', 'wmv'];
                                    @endphp

                                    <div class="img-items">
                                        <a href="{{ route('admin.service.delete_gallery_image', $media->id) }}" class="btn">
                                            <i style="color: red;" class="fa fa-trash"></i>
                                        </a>

                                        @if(in_array(strtolower($extension), $imageExtensions))
                                        <img src="/{{ $media->path }}" alt="" style="width: 130px">
                                        @elseif(in_array(strtolower($extension), $videoExtensions))
                                        <video src="{{ asset($media->path) }}" controls style="width: 130px"></video>
                                        @else
                                        <p>Unsupported media type</p>
                                        @endif
                                    </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="row my-5">
                            <div class="col">
                                <hr>
                                <h4 style="font-size: 18px;border-top: 1px solid;padding-top: 18px;" class="form-label mx-1">{{__('messages.Documnet')}}</h4>
                                @if($service->document)
                                <div>
                                    <a class="btn btn-primary my-3" href="{{ asset($service->document)  }}" download="{{$service->document}}">{{__('messages.Download_document')}}</a>
                                </div>
                                @endif
                                <label for="Document" class="form-label mx-1">{{__('messages.Change_document')}}</label>
                                <input type="file" name="document" class="form-control" id="Document" placeholder="{{__('messages.Documnet')}}" aria-label="First name">
                            </div>
                        </div>
                        <div class=" my-5">
                            <div style="display: inline-block;" class="mx-3">

                                <label for="accept" class="form-label mx-1 top-6">{{__('messages.Accept')}}</label>
                                <label class="switch">
                                    <input {{$service->accept ==1 ? 'checked' : ' '}} type="checkbox" id="accept" name="accept">
                                    <span class="slider round"></span>
                                </label>
                            </div>

    
                        </div>















                        <div class="container my-5">
                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longitude">
                            <div id="map" style="height: 300px;"></div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary w-100">{{__('messages.Update')}}</button>
                        </div>
                    </form>




                </div>
            </div>
        </div>
    </div>
    @php
    // Initialize an empty array for formatted dates
    $formattedDays = [];

    // Decode the JSON and handle any errors
    $daysArray = json_decode($service->days, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($daysArray)) {
    foreach ($daysArray as $day) {
    try {
    // Create and format a DateTime object
    $formattedDays[] = (new DateTime($day))->format('m/d/Y');
    } catch (Exception $e) {
    \Log::error("Error parsing date: $day - " . $e->getMessage()); // Log error
    $formattedDays[] = null; // Handle invalid dates
    }
    }
    } else {
    \Log::error("Invalid JSON data in service->days: " . json_last_error_msg());
    }
    @endphp

    <div class="modal fade" id="eventDays" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.Event_days')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.service.event_day')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$service->id}}">
                        <div class="row my-2">
                            <div class="col">

                                <select name="day" class="form-select" aria-label="Default select example">
                                    <option selected disabled> {{__('messages.Work_days')}}</option>
                                    @foreach(json_decode($service->days) as $day)
                                    <?php
                                    // Create a DateTime object from the original date-time string
                                    $date = DateTime::createFromFormat('m/d/Y g:i A', $day);

                                    // Format the date to the desired format
                                    $formattedDate = $date->format('m/d/Y');
                                    ?>
                                    <option value="{{ $formattedDate }}">{{ $formattedDate }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" name="price" placeholder="{{__('messages.Price')}}" aria-label="First name">
                            </div>

                        </div>
                        <div class="row my-2">
                            <button class="btn btn-primary"> {{__('messages.Add')}} </button>
                        </div>
                    </form>
                    <table class="table table-hover">
                        <thead>

                            <tr>
                                <th>{{__('messages.Day')}}</th>
                                <th>{{__('messages.Price')}}</th>
                                <th>{{__('messages.Actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventDays as $event)
                            <tr>
                                <td>{{$event->day}}</td>
                                <td>{{$event->price}}</td>
                                <td>
                                    <form action="{{route('admin.service.delete_event_day')}}" method="post">
                                        @csrf 
                                        <input type="hidden" name="id" value="{{$event->id}}">
                                        <button class="btn btn-danger">{{__("messages.Delete")}} </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('messages.Close')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add-feature" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.Add_feature')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.service.add_feature')}}" method="post">
                        @csrf
                        <input type="hidden" name="service_id" value="{{$service->id}}">
                        <div class="row my-2">
                            <div class="col">

                                <select name="feature_id" class="form-select" aria-label="Default select example">
                                    <option selected disabled> {{__('messages.Feature')}}</option>
                                    @foreach($features as $feature)
                                    <option value="{{ $feature->id }}">{{ session('lang')=='en'? $feature->feature_name : $feature->feature_name_ar  }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row my-2">
                            <button class="btn btn-primary"> {{__('messages.Add')}} </button>
                        </div>
                    </form>
                    <table class="table table-hover">
                        <thead>

                            <tr>
                                <th>{{__('messages.Name')}}</th>
                                <th>{{__('messages.Image')}}</th>
                                <th>{{__('messages.Actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($service_features as $service_feature)
                            <tr>
                                <td>{{ session('lang')=='en'? $service_feature->feature->feature_name : $service_feature->feature->feature_name_ar  }}</td>
                                <td><img src="/{{$service_feature->feature->image}}" style="height:30px" alt=""></td>
                                <td class="text-center">
                                    <form action="{{route('admin.service.delete_feature')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="feature_id" value="{{$service_feature->id}}">
                                        <button class="btn" type="submit">
                                            <i style="color: red;cursor: pointer;font-size: 20px;" class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('messages.Close')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add-booking" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.Add_booking')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.booking.store')}}" method="post">
                        @csrf
                        <input type="hidden" name="service_id" value="{{$service->id}}">
                        <div id='kt_menu_64b77630f13b911'>

                            <select name="customer_id" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="{{__('messages.Customers')}}" data-dropdown-parent="#kt_menu_64b77630f13b911" data-allow-clear="true">
                                <option selected disabled>{{__('messages.Customers')}}</option>
                                @foreach($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row my-2">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">{{__('messages.Start_at')}}</label>
                                    <input type="date" name="start_at" class="form-control" id="formGroupExampleInput" placeholder="Example input placeholder">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">{{__('messages.End_at')}}</label>
                                    <input type="date" name="end_at" class="form-control" id="formGroupExampleInput" placeholder="Example input placeholder">
                                </div>
                            </div>

                        </div>
                        <div class="row my-2">
                            <button class="btn btn-primary"> {{__('messages.Add')}} </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('messages.Close')}}</button>
                </div>
            </div>
        </div>
    </div>

    @endsection
    @section('js')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var lat = parseFloat('{{ $service->lat ?? 0 }}'); // Ensure numeric conversion
        var lng = parseFloat('{{ $service->lng ?? 0 }}');

        var map = L.map('map').setView([29.655985070590823, 31.27120971679688], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        L.marker([lat, lng]).addTo(map)
            .bindPopup('{{$service->name}}') // Add a popup to the marker
            .openPopup(); // Open the popup by default
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
        var datesFromDb = @json(json_decode($service -> days)); // Encode the dates as a JSON array
        document.addEventListener('DOMContentLoaded', function() {
            const datePickerElement = document.getElementById('datePicker');

            if (datePickerElement && Array.isArray(datesFromDb)) {
                const datePicker = window.datePicker; // Retrieve the Tempus Dominus instance
                datesFromDb.forEach(dateStr => {
                    try {
                        const date = moment(dateStr, 'MM/DD/YYYY').toDate();
                        datePicker.dates.add(date); // Add date to the calendar
                    } catch (error) {
                        console.error("Error parsing date:", error);
                    }
                });

                document.getElementById('inp-days').value = datesFromDb.join('; '); // Display in input
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            const datePicker = window.datePicker;

            if (datePicker) {
                // Event listener for date selection
                datePicker.dates.on('update', function() {
                    const selectedDates = datePicker.dates.getDates(); // Get the list of dates

                    // Display the selected dates in the input field
                    const formattedDates = selectedDates.map(date => moment(date).format('MM/DD/YYYY'));
                    document.getElementById('inp-days').value = formattedDates.join('; ');

                    // Optional: Perform additional logic with the new dates, like updating the database
                    // For example, you could send the new dates to the backend via AJAX
                });
            }
        });





        document.addEventListener('DOMContentLoaded', function() {
            const datePickerElement = document.getElementById('datePicker');

            if (!datePickerElement) {
                console.error("datePicker element not found");
                return;
            }

            const datePicker = new tempusDominus.TempusDominus(datePickerElement, {
                display: {
                    viewMode: 'calendar',
                    components: {
                        calendar: true,
                        date: true,
                    },
                },
                multipleDates: true, // Allows multiple dates
            });
        });
    </script>
    <script>
        window.onload = function() {
            document.getElementById('inp-days').click();
        };
    </script>





    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function() {
            var calendar_dates = @json($calendar_dates);
            var calendar = $('#calendar').fullCalendar({
                header: {
                    left: 'prev, next',
                    center: 'title',
                    right: 'month, agendaWeek, agendaDay ',
                },
                events: calendar_dates,
                selectable: true,
                selectHelper: true,

                displayEventTime: false, // Hide time globally


                select: function(start, end, allDays) {
                    $("#selectDay").modal('toggle')
                    $('#save-changes').click(function() {

                        let start_at = moment(start).format('M/D/YYYY')
                        let end_at = moment(end).add(1, 'days').format('M/D/YYYY')
                        let title = $('#title').val();
                        $.ajax({
                            url: "{{route('test')}}",
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
        element.find('.fc-title').html('<a href="' + event.url + '" style="color:' + event.textColor + ';">' + event.title + '</a>');
    } else {
        element.find('.fc-title').html(event.title); // Only show the title
    }
}
            });

        });
    </script>

    @endsection