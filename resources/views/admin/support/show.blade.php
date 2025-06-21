@extends('admin.app')
@section('title',trans('messages.Support'))
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
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Support')}}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted text-hover-primary">{{__('messages.Pages')}}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{__('messages.Support')}}</li>
                </ul>
            </div>

        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="row  p-lg-17">


                <div class="col-lg-6 col-xl-7">
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header pt-7" id="kt_chat_contacts_header">
                            <div class=" h-lg-100" id="kt_contacts_main">
                                <!--begin::Card header-->
                                <div class="card-header pt-7" id="kt_chat_contacts_header">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <i class="ki-duotone ki-badge fs-1 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        <h2>{{__("messages.Contact_details")}}</h2>
                                    </div>
                                    <!--end::Card title-->
                                <!--begin::Profile-->
                                <div class="d-flex gap-7 align-items-center py-4">
                                    <!--begin::Avatar-->
                                    <div class="symbol symbol-circle symbol-100px">
                                        <img src="/{{$support->user->image}}" alt="image" data--h-bstatus="4PROCESSING">
                                    </div>
                                    <!--end::Avatar-->

                                    <!--begin::Contact details-->
                                    <div class="d-flex flex-column gap-2">
                                        <!--begin::Name-->
                                        <h3 class="mb-0">{{$support->user->name}}</h3>
                                        <!--end::Name-->

                                        <!--begin::Email-->
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ki-duotone ki-sms fs-2"><span class="path1"></span><span class="path2"></span></i> <a href="#" class="text-muted text-hover-primary">{{$support->user->email}}</a>
                                        </div>
                                        <!--end::Email-->

                                        <!--begin::Phone-->
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ki-duotone ki-phone fs-2"><span class="path1"></span><span class="path2"></span></i> <a href="#" class="text-muted text-hover-primary">{{$support->user->phone}}</a>
                                        </div>
                                        <!--end::Phone-->
                                    </div>
                                    <!--end::Contact details-->
                                </div>
                                <!--end::Profile-->
                                </div>
                                <!--end::Card header-->


                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-5">

                    <!--begin::Contact group wrapper-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header pt-7" id="kt_chat_contacts_header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>{{__('messages.Support_Details')}}</h2>
                            </div>
                            <!--begin::Card body-->
                            <div class="card-body pt-5">


                                <div class="tab-content" id="">
                                    <!--begin:::Tab pane-->
                                    <div class="tab-pane fade active show" id="kt_contact_view_general" role="tabpanel">

                                        <!--begin::Additional details-->
                                        <div class="d-flex flex-column gap-5 mt-7">




                                            <!--begin::Country-->
                                            <div class="d-flex flex-column gap-1">
                                                <div class="fw-bold text-muted">{{__('messages.Subject')}}</div>
                                                <div class="fw-bold fs-5">{{$support->subject}}</div>
                                            </div>
                                            <!--end::Country-->

                                            <!--begin::Notes-->
                                            <div class="d-flex flex-column gap-1">
                                                <div class="fw-bold text-muted">{{__('messages.Message')}}</div>
                                                {{$support->message}}
                                            </div>
                                            <!--end::Notes-->
                                        </div>
                                        <!--end::Additional details-->
                                    </div>
                                    <!--end:::Tab pane-->

                                </div>
                                <!--end::Tab content-->


                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card header-->


                    </div>
                    <!--end::Contact group wrapper-->
                </div>

            </div>
        </div>
    </div>
</div>

@endsection