@extends('admin.app')
@section('title',trans('messages.Service_details'))
@section('css')
<style>
    input,textarea,select{
        border: none !important;
    }
    body{
        overflow-x: hidden;
    }
    .btn.btn-icon:not(.btn-outline):not(.btn-dashed):not(.border-hover):not(.border-active):not(.btn-flush) {
        border: 0;
        display: none;
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
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show " role="alert">
            {{session('error')}}
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
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Service_details')}}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted text-hover-primary">{{__('messages.Pages')}}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{__('messages.Service_details')}}</li>
                </ul>
            </div>

        </div>
    </div>
    <div id="kt_app_content" class="app-content  flex-column-fluid " data-select2-id="select2-data-kt_app_content">


        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container  container-xxl " data-select2-id="select2-data-kt_app_content_container">
            <!--begin::Form-->
            <form id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" data-kt-redirect="/metronic8/demo1/apps/ecommerce/catalog/products.html" data-select2-id="select2-data-kt_ecommerce_add_product_form">
                <!--begin::Aside column-->
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10" data-select2-id="select2-data-132-vtxe">
                    <!--begin::Thumbnail settings-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>{{__('messages.Image')}}</h2>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body text-center pt-0">
                            <!--begin::Image input-->

                            <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-150px h-150px" style="background-image: url(/{{$service->image}})"></div>
                                <!--end::Preview existing avatar-->

                                <!--begin::Label-->
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change avatar" data-bs-original-title="Change avatar" data-kt-initialized="1">
                                    <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                                    <!--begin::Inputs-->
                                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg">
                                    <input type="hidden" name="avatar_remove">
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->

                                <!--begin::Cancel-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar" data-bs-original-title="Cancel avatar" data-kt-initialized="1">
                                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i> </span>
                                <!--end::Cancel-->

                                <!--begin::Remove-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar" data-bs-original-title="Remove avatar" data-kt-initialized="1">
                                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i> </span>
                                <!--end::Remove-->
                            </div>
                            <!--end::Image input-->

                            <!--begin::Description-->
                            <div class="text-muted fs-7">{{__('messages.Thumbnail_image')}}</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Thumbnail settings-->
                    <!--begin::Status-->

                    <div class="card card-flush py-4" data-select2-id="select2-data-131-dgoh">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{__('messages.Status')}}</h2>
                            </div>
                            <!--end::Card title-->

                            <div class="card-toolbar">
                                <span class="mx-2" style="color: gray;">{{ $service->accept==1  ?  __('messages.Accepted') : __('messages.Rejected')}}</span> <div class="rounded-circle  {{$service->accept==1 ? '  bg-success' : ' bg-danger'}}  w-15px h-15px" id="kt_ecommerce_add_product_status"></div>
                            </div>
                        </div>

                        <!-- <div class="card-body pt-0" data-select2-id="select2-data-130-zbx3">
                            <select class="form-select mb-2 select2-hidden-accessible" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="kt_ecommerce_add_product_status_select" data-select2-id="select2-data-kt_ecommerce_add_product_status_select" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                <option data-select2-id="select2-data-134-3e5u"></option>
                                <option value="{{$service->accept==1 ? __('messages.Accepted') : __('messages.Rejected')}}" selected="" data-select2-id="select2-data-10-py3n">{{$service->accept==1 ? __('messages.Accepted') : __('messages.Rejected')}}</option>

                            </select><span class="select2 select2-container select2-container--bootstrap5 select2-container--above" dir="ltr" data-select2-id="select2-data-9-rw4f" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single form-select mb-2" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-kt_ecommerce_add_product_status_select-container" aria-controls="select2-kt_ecommerce_add_product_status_select-container"><span class="select2-selection__rendered" id="select2-kt_ecommerce_add_product_status_select-container" role="textbox" aria-readonly="true" title="Published">
                                {{$service->accept==1 ? __('messages.Accepted') : __('messages.Rejected')}}
                            </span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                        </div> -->


                    </div>

                    <!--end::Status-->

                    <!--end::Category & tags-->
                    <!--begin::Card widget 6-->
                    <div class="card card-flush  ">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <div class="card-title d-flex flex-column">
                                <!--begin::Info-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Currency-->
                                    <span class="fs-4 fw-semibold text-gray-500 me-1 align-self-start">{{__("messages.EGP")}} </span>
                                    <!--end::Currency-->

                                    <!--begin::Amount-->
                                    <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">{{$earning}}</span>
                                    <!--end::Amount-->

                                    <!--begin::Badge-->
                                    <span class="badge badge-light-success fs-base">
                                        <i class="ki-duotone ki-arrow-up fs-5 text-success ms-n1"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                    <!--end::Badge-->
                                </div>
                                <!--end::Info-->

                                <!--begin::Subtitle-->
                                <span class="text-gray-500 pt-1 fw-semibold fs-6">{{__('messages.Total_earning')}}</span>
                                <!--end::Subtitle-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->

                        <!--begin::Card body-->
                        <div class="card-body d-flex align-items-end px-0 pb-0">

                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card widget 6-->
                </div>
                <!--end::Aside column-->

                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin:::Tabs-->
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2" role="tablist">
                        <!--begin:::Tab item-->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_add_product_general" aria-selected="true" role="tab">{{__('messages.General')}}</a>
                        </li>
                        <!--end:::Tab item-->



                        <!--begin:::Tab item-->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_reviews" aria-selected="false" role="tab" tabindex="-1">{{__('messages.Reviews')}}</a>
                        </li>
                        <!--end:::Tab item-->
                    </ul>
                    <!--end:::Tabs-->
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade active show" id="kt_ecommerce_add_product_general" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">

                                <!--begin::General options-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>{{__('messages.General')}}</h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->

                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row fv-plugins-icon-container">
                                            <!--begin::Label-->
                                            <label class="required form-label">{{__('messages.Service_name')}}</label>
                                            <!--end::Label-->

                                            <!--begin::Input-->
                                            <input type="text" readonly name="product_name" value="{{ session('lang')=='en' ?  $service->name : $service->name_ar  }}" class="form-control mb-2" placeholder="{{__('messages.Service_name')}}" value="Sample product">
                                            <!--end::Input-->
                                        </div>
                                        <div class="mb-10 fv-row fv-plugins-icon-container">
                                            <!--begin::Label-->
                                            <label class="required form-label">{{__('messages.Provider_name')}}</label>
                                            <!--end::Label-->

                                            <!--begin::Input-->
                                            <input type="text" readonly name="product_name" value="{{$service->user->name  }}" class="form-control mb-2" placeholder="{{__('messages.Provider_name')}}" value="Sample product">
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div>
                                            <!--begin::Label-->
                                            <label class="form-label">{{__('messages.Description')}}</label>
                                            <!--end::Label-->

                                            <!--begin::Editor-->

                                            <div id="kt_ecommerce_add_product_description" name="kt_ecommerce_add_product_description" class="min-h-200px mb-2 ql-container ql-snow">
                                                <div class="ql-editor ql-blank" data-gramm="false" contenteditable="false">
                                                    <p><br></p>{{ session('lang')=='en' ?  $service->description : $service->description_ar  }}
                                                </div>
                                                <div class="ql-clipboard" contenteditable="true" tabindex="-1"></div>
                                                <div class="ql-tooltip ql-hidden"><a class="ql-preview" rel="noopener noreferrer" target="_blank" href="about:blank"></a><input type="text" data-formula="e=mc^2" data-link="https://quilljs.com" data-video="Embed URL"><a class="ql-action"></a><a class="ql-remove"></a></div>
                                            </div>
                                            <!--end::Editor-->

     
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::General options-->

                                <!--begin::Pricing-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>{{__('messages.Pricing')}}</h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->

                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row fv-plugins-icon-container">
                                            <!--begin::Label-->
                                            <label class="required form-label">{{__('messages.Price')}}</label>
                                            <!--end::Label-->

                                            <!--begin::Input-->
                                            <input readonly type="text" name="price" class="form-control mb-2" placeholder="Product price" value="{{$service->price+$service->commission_money}}  ">
                                            <!--end::Input-->

      
                                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="fv-row mb-10">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold mb-2">
                                            {{__('messages.Commission')}}


                                                <span class="ms-1" data-bs-toggle="tooltip" aria-label="Select a discount type that will be applied to this product" data-bs-original-title="Select a discount type that will be applied to this product" data-kt-initialized="1">
                                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span> </label>
                                            <!--End::Label-->

                                            <!--begin::Row-->
                                            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-1 row-cols-xl-3 g-9" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']" data-kt-initialized="1">


                                                <!--begin::Col-->
                                                <div class="col">
                                                    <!--begin::Option-->
                                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary active d-flex text-start p-6" data-kt-button="true">
                                                        <!--begin::Radio-->
                                                        <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                            <input class="form-check-input" type="radio" name="discount_option" value="2" checked="checked">
                                                        </span>
                                                        <!--end::Radio-->

                                                        <!--begin::Info-->
                                                        <span class="ms-5">
                                                            <span class="fs-4 fw-bold text-gray-800 d-block">{{__("messages.Percentage")}} %</span>
                                                        </span>
                                                        <!--end::Info-->
                                                    </label>
                                                    <!--end::Option-->
                                                </div>
                                                <!--end::Col-->

                                            </div>
                                            <!--end::Row-->
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class=" mb-10 fv-row" id="kt_ecommerce_add_product_discount_percentage">
                                            <!--begin::Label-->
                                            <label class="form-label">{{__('messages.Commission_Percentage')}}</label>
                                            <!--end::Label-->

                                            <!--begin::Slider-->
                                            <div class="d-flex flex-column text-center mb-5">
                                                <div class="d-flex align-items-start justify-content-center mb-7">
                                                    <span class="fw-bold fs-3x" id="kt_ecommerce_add_product_discount_label">{{$service->commission_percentage}}</span>
                                                    <span class="fw-bold fs-4 mt-1 ms-2">%</span>
                                                </div>
                                                <div id="kt_ecommerce_add_product_discount_slider" class="noUi-sm noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr">
                                                    <div class="noUi-base">
                                                        <div class="noUi-connects"></div>
                                                        <div class="noUi-origin" style="transform: translate(-90.9091%, 0px); z-index: 4;">
                                                            <div class="noUi-handle noUi-handle-lower" data-handle="0" tabindex="0" role="slider" aria-orientation="horizontal" aria-valuemin="1.0" aria-valuemax="100.0" aria-valuenow="10.0" aria-valuetext="10.00">
                                                                <div class="noUi-touch-area"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Slider-->

                                        </div>
                                        <!--end::Input group-->

                                       <!--begin::Input group-->
                                       <div class="mb-10 fv-row fv-plugins-icon-container">
                                            <!--begin::Label-->
                                            <label class="required form-label">{{__('messages.Admin_earning')}}</label>
                                            <!--end::Label-->

                                            <!--begin::Input-->
                                            <input type="text" readonly name="price" class="form-control mb-2" placeholder="Product price" value="{{$service->commission_money}}">
                                            <!--end::Input-->
                                            <!--begin::Label-->
                                            <label class="required form-label">{{__('messages.Provider_earning')}}</label>
                                            <!--end::Label-->

                                            <!--begin::Input-->
                                            <input type="text" readonly name="price" class="form-control mb-2" placeholder="Product price" value="{{$service->provider_money}}">
                                            <!--end::Input-->

      
                                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                        </div>
                                        <!--end::Input group-->



                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::Pricing-->
                            </div>
                        </div>
                        <!--end::Tab pane-->







                        <!--begin::Tab pane-->
                        <div class="tab-pane fade" id="kt_ecommerce_add_product_reviews" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">

                                <!--begin::Reviews-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h2>{{__('messages.Customer_reviews')}}</h2>
                                        </div>
                                        <!--end::Card title-->

                                        <!--begin::Card toolbar-->
                                        <div class="card-toolbar">
                                            <!--begin::Rating label-->
                                            <span class="fw-bold me-5">{{__('messages.Overall_rating')}}: </span>
                                            <!--end::Rating label-->

                                            <!--begin::Overall rating-->
                                            <div class="rating">
                                                <div class="rating-label {{$avg_rating >=1 ? 'checked' : ' '}}">
                                                    <i class="ki-duotone ki-star fs-2"></i>
                                                </div>
                                                <div class="rating-label  {{$avg_rating >=2 ? 'checked' : ' '}}">
                                                    <i class="ki-duotone ki-star fs-2"></i>
                                                </div>
                                                <div class="rating-label  {{$avg_rating >=3 ? 'checked' : ' '}}">
                                                    <i class="ki-duotone ki-star fs-2"></i>
                                                </div>
                                                <div class="rating-label  {{$avg_rating >=4 ? 'checked' : ' '}}">
                                                    <i class="ki-duotone ki-star fs-2"></i>
                                                </div>
                                                <div class="rating-label  {{$avg_rating >=5 ? 'checked' : ' '}}" >
                                                    <i class="ki-duotone ki-star fs-2"></i>
                                                </div>
                                            </div>
                                            <!--end::Overall rating-->
                                        </div>
                                        <!--end::Card toolbar-->
                                    </div>
                                    <!--end::Card header-->

                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <!--begin::Table-->
                                        <table id="order-table" class="table table-row-dashed align-middle gs-0 gy-3 my-0 dataTable no-footer" aria-describedby="order-table_info" style="width: 569px;">
												<thead>
													<tr>

                                                        <th class="text-center px-4 py-3 sorting_disabled" rowspan="1" colspan="1" style="width: 52px;" aria-label="Vendor">
                                                            {{__('messages.Service_name')}}
                                                        </th>
                                                        <th class="text-center px-4 py-3 sorting_disabled" rowspan="1" colspan="1" style="width: 52px;" aria-label="Vendor">
                                                            {{__('messages.Customer_name')}}
                                                        </th>
                                                        <th class="text-center px-4 py-3 sorting_disabled" rowspan="1" colspan="1" style="width: 52px;" aria-label="Vendor">
                                                            {{__('messages.Review')}}
                                                        </th>
                                                        <th class="text-center px-4 py-3 sorting" tabindex="0" aria-controls="order-table" rowspan="1" colspan="1" style="width: 110px;" aria-label="Payment Status: activate to sort column ascending">
                                                            {{__('messages.Comment')}}
                                                        </th>


                                                    </tr>
												</thead>
												<tbody>

                                                    @if(count($reviews)>0)
                                                    @foreach($reviews as $rating)
                                                    <tr>
                                                        <td>{{session('lang')=='en' ? $rating->service->name :$rating->service->name_ar }}</td>
                                                        <td>{{$rating->user->name}}</td>

                                                        <td>{{$rating->rating}}</td>
                                                        <td>{{$rating->review}}</td>

                                                    </tr> 
                                                    @endforeach
                                                    @else 
                                                    <tr class="odd">
                                                        <td valign="top" colspan="6" class="dataTables_empty">No data available in table</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
											</table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Reviews-->
                            </div>
                        </div>
                        <!--end::Tab pane-->
                    </div>
                    <!--end::Tab content-->


                </div>
                <!--end::Main column-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content container-->
    </div>

    @endsection