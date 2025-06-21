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

                <div class="col-lg-6 col-xl-3">

                    <!--begin::Contact group wrapper-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header pt-7" id="kt_chat_contacts_header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>Groups</h2>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body pt-5">
                            <!--begin::Contact groups-->
                            <div class="d-flex flex-column gap-5">
                                <!--begin::Contact group-->
                                <div class="d-flex flex-stack">
                                    
                                    <a href="/admin/support" class="fs-6 fw-bold text-gray-800 text-hover-primary text-active-primary {{request()->has('seen') ? ' ' : 'active'}} ">{{__('messages.All')}}</a>
                                    <div class="badge badge-light-primary">{{$not_seen_count + $seen_count}}</div>
                                </div>
                                <!--begin::Contact group-->
                                <!--begin::Contact group-->
                                <div class="d-flex flex-stack">
                                    <a href="/admin/support?seen=1" class="fs-6 fw-bold text-gray-800 text-hover-primary text-active-primary {{request()->get('seen') == 1 ? ' active' : ''}} ">{{__('messages.Seen')}}</a>
                                    <div class="badge badge-light-primary">{{$seen_count}}</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <a href="/admin/support?seen=0" class="fs-6 fw-bold text-gray-800 text-hover-primary text-active-primary {{(request()->get('seen') == 0 && request('seen') != null)  ? 'active' : ''}} ">{{__('messages.Not_seen')}}</a>
                                    <div class="badge badge-light-primary">{{$not_seen_count}}</div>
                                </div>

                            </div>

                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Contact group wrapper-->
                </div>
                <div class="col-lg-6 col-xl-9">
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header pt-7" id="kt_chat_contacts_header">
                            <table class="table align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="ps-4 min-w-300px rounded-start">{{__('messages.User_name')}}</th>
                                        <th class="min-w-125px">{{__('messages.Subject')}}</th>
                                        <th class="min-w-125px">{{__('messages.Actions')}}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supports as $support)
                                    <tr>

                                        <td>{{$support->user->name}}</td>
                                        <td>{{$support->subject}}</td>
                                        <td>
                                            <a href="{{route('admin.support.show',$support->id)}}" class="btn"> {{__('messages.View')}} </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

@endsection