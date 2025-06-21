@extends('admin.app')
@section('title',__('messages.Packages'))
@section('css')
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


    <div class="container">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show " role="alert">
            {{session('success')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if($errors->any())
        @foreach($errors->all() as $error)

        <div class="alert alert-danger alert-dismissible fade show " role="alert">
            {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endforeach
        @endif

    </div>
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Packages')}}</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted text-hover-primary">{{__('messages.Packages')}}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">{{__('messages.Pages')}}</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">


                @can('Create Package')
                <button class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#add">{{__('messages.Create')}}</button>
                <!--end::Primary button-->
                @endcan
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::About card-->
            <div class="card">
                <!--begin::Body-->
                <div class="card-body p-lg-17" style="width: 100%; overflow-x:scroll">
                    <table class="table align-middle gs-0 gy-4">
                        <!--begin::Table head-->
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-125px rounded-start">{{__('messages.Name')}}</th>
                                <th class="min-w-125px">{{__('messages.Image')}}</th>
                                <th class="min-w-125px">{{__('messages.Created_at')}}</th>

                                <th class="min-w-125px text-center">{{__('messages.Actions')}}</th>

                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                            @foreach($packages as $package)
                            <tr>
                                <td>
                                    {{session('lang')=='en' ? $package->name : $package->name_ar}}
                                </td>
                                <td>
                                    <img src="/{{$package->image}}" style="width:60px;" alt="">

                                </td>
                                <td>{{ \Carbon\Carbon::parse($package->created_at)->format('Y-m-d')}}</td>

                                <td class="text-center">
                                    @can('Edit packages')
                                    <button onclick="setData(
                                        '{{$package->id}}',
                                        '{{$package->name}}',
                                        '{{$package->name_ar}}',
                                        '{{$package->image}}',
                                        '{{$package->service_limit}}',
                                        '{{$package->duration}}',
                                        '{{$package->price}}',
                                        '{{$package->features}}',
                                        '{{$package->verified}}',
                                    )" data-bs-toggle="modal" data-bs-target="#edit" class="btn btn-bg-light btn-color-muted btn-sm px-4">{{__('messages.Edit')}}</button>
                                    @endcan
                                    @if($package->id != 3)
                                        @can('Delete Package')
                                        <form action="{{ route('admin.delete_package') }}" method="post" style="display: inline-block;">
                                            @csrf
                                            <input type="hidden" name="package_id" value="{{$package->id}}" />
                                            <button type="submit" class="btn btn-bg-light btn-color-muted btn-active-color-danger btn-sm px-4">{{__('messages.Delete')}}</button>
                                        </form>
                                        @endcan
                                    @endif
                                </td>


                            </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
					{{ $packages->links('vendor.pagination.custom') }}


                </div>
                <!--end::Body-->
            </div>
            <!--end::About card-->
        </div>
        <!--end::Content container-->
    </div>
</div>
<!--end::Content-->


<!-- modals -->

<!-- add -->
<div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.Add_new_package')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" action="{{route('admin.add_package')}}" method="post">
                    @csrf

                    <div class="row">
                        <div class="col">
                            <input required name="name" type="text" class="form-control" placeholder="{{__('messages.Name_en')}}" aria-label="First name">
                        </div>
                        <div class="col">
                            <input required name="name_ar" type="text" class="form-control" placeholder="{{__('messages.Name_ar')}}" aria-label="Last name">
                        </div>

                    </div>

                    <div class="row my-3">
                        <div class="col-6 my-3">
                            <input required name="service_limit" type="number" class="form-control" placeholder="{{__('messages.Limitation_number_of_properties')}}" aria-label="First name">
                        </div>
                        <div class="col-6 my-3">
                            <input required name="duration" type="number" class="form-control" placeholder="{{__('messages.Duration')}}" aria-label="Last name">
                        </div>
                        <div class="col-12 my-3">
                            <input required name="price" type="number" class="form-control" placeholder="{{__('messages.Price')}}" aria-label="Last name">
                        </div>

                    </div>
                    <div class="row ">
                        <div class="col">
                            <label for="inputimage" class="form-label">{{__('messages.Image')}}</label>
                            <input required name="image" type="file" id="inputimage" class="form-control" placeholder="{{__('messages.image')}}">
                        </div>
                    </div>
                    <div class="my-3" style="display: flex; align-items: center;">
                        <label for="verified" class="form-label mx-1 top-6">{{__('messages.Verified')}}</label>
                        <label class="switch">
                            <input  type="checkbox"  value="1" name="verified">
                            <span class="slider round"></span>
                        </label>

                    </div>
                    <div class="row my-5">
                        <div class="col-12 my-2">
                            {{__("messages.Features")}}
                        </div>
                        @foreach($features as $feature)
                        <div class="col-lg-6 col-sm-12">
                            <label class="col-4 mb-2">
                                <input class="form-check-input permissions" type="checkbox" value="{{$feature->id}}" name="features[]">
                                <span class="form-check-label fw-bold">{{session('lang')=='en' ? $feature->feature : $feature->feature_ar}}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('messages.Close')}}</button>
                <button type="submit" class="btn btn-primary">{{__('messages.Save_changes')}}</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit -->
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.Edit_package')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" action="{{route('admin.update_package')}}" method="post">
                    @csrf

                    <input type="hidden" name="package_id" id="package_id">
                    <div class="row">
                        <div class="col">
                            <input required name="name" id="name" type="text" class="form-control" placeholder="{{__('messages.Name_en')}}" aria-label="First name">
                        </div>
                        <div class="col">
                            <input required name="name_ar" id="name_ar" type="text" class="form-control" placeholder="{{__('messages.Name_ar')}}" aria-label="Last name">
                        </div>

                    </div>
                    <div class="row my-3">
                        <div class="col-6 my-3">
                            <input id="service_limit" required name="service_limit" type="number" class="form-control" placeholder="{{__('messages.Limitation_number_of_properties')}}" aria-label="First name">
                        </div>
                        <div class="col-6 my-3">
                            <input id="duration" required name="duration" type="number" class="form-control" placeholder="{{__('messages.Duration')}}" aria-label="Last name">
                        </div>
                        <div class="col-12 my-3">
                            <input id="price" required name="price" type="number" class="form-control" placeholder="{{__('messages.Price')}}" aria-label="Last name">
                        </div>

                    </div>
                    <div class="my-3" style="display: flex; align-items: center;">
                        <label for="verified" class="form-label mx-1 top-6">{{__('messages.Verified')}}</label>
                        <label class="switch">
                            <input  type="checkbox" id="verified" value="1" name="verified">
                            <span class="slider round"></span>
                        </label>

                    </div>
                    <div class="row my-2">
                        <div class="col">
                            <label for="inputimage" class="form-label">{{__('messages.Change_image')}}</label>
                            <input name="image" type="file" id="inputimage" class="form-control" placeholder="{{__('messages.image')}}">
                            <img style="height:100px" src="" id="image" alt="">
                        </div>
                    </div>
                    <div class="row my-5 features-section">
                        <div class="col-12 my-2">
                            {{__("messages.Features")}}
                        </div>

                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('messages.Close')}}</button>
                <button type="submit" class="btn btn-primary">{{__('messages.Save_changes')}}</button>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    function setData(id, name, name_ar, image, service_limit, duration, price, features,verified) {

        let packageId = document.querySelector("#package_id")
        let InputName = document.querySelector("#name")
        let InputNameAr = document.querySelector("#name_ar")

        let InputImage = document.querySelector("#image")
        let InputServiceLimit = document.querySelector("#service_limit")
        let InputDuration = document.querySelector("#duration")
        let InputPrice = document.querySelector("#price")
        let InputVerified= document.querySelector("#verified")

        packageId.value = id;
        InputName.value = name;
        InputNameAr.value = name_ar;
        InputImage.src = '/' + image;
        InputServiceLimit.value = service_limit;
        InputDuration.value = duration;
        InputPrice.value = price;
        if(verified==true)
            InputVerified.checked=true
        let parsedFeatures;
        try {
            parsedFeatures = JSON.parse(features);
        } catch (error) {
            console.error("Error parsing features JSON:", error);
            return
        }


        let all_features = @json($features);
        let features_section = document.querySelector(".features-section");
        let lang = '{{session("lang")}}'
        var featureIds = parsedFeatures.map(function(feature) {
            return feature.feature_id;
        });
        let featuresHTML = '';
        all_features.forEach(function(feature) {
            let isChecked = featureIds.includes(feature.id);
            featuresHTML += `
                <div class="col-lg-6 col-sm-12">
                    <label class="col-4 mb-2">
                        <input ${isChecked ? 'checked' : ''} class="form-check-input permissions" type="checkbox" value="${feature.id}" name="features[]">
                        <span class="form-check-label fw-bold">${lang === 'en' ? feature.feature : feature.feature_ar}</span>
                    </label>
                </div>
            `;
        });

        // Set the complete HTML to features_section
        features_section.innerHTML = featuresHTML;




    }
</script>
@endsection