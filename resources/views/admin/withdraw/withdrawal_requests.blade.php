@extends('admin.app')
@section('title',trans('messages.Withdrawal_requests'))
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
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Withdrawal_requests')}}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted text-hover-primary">{{__('messages.Pages')}}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{__('messages.Withdrawal_requests')}}</li>
                </ul>
            </div>

        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card">
                <div class="card-body p-lg-17">
                    <div class="table-responsive">

                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="packages-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{__('messages.Amount')}}</th>
                                    <th>{{__('messages.Provider')}}</th>
                                    <th>{{__('messages.Request_time')}}</th>
                                    <th>{{__('messages.Status')}}</th>
                                    <th> {{__("messages.Admin_name")}} </th>
                                    <th>{{__('messages.Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($withdraws as $withdraw)
                                <tr>

                                    <td>{{$withdraw->amount}}</td>

                                    <td><a class="deco-none" href="{{route('admin.profile',$withdraw->user->id)}}">{{$withdraw->user->name}}</a></td>
                                    <td>{{\Carbon\Carbon::parse($withdraw->created_at)->format('Y-m-d') }}
                                    </td>
                                    <td>
                                        @if($withdraw->status == 1)
                                        <label class="badge badge-success"> {{__('messages.Approved')}}</label>
                                        @elseif($withdraw->status==0)
                                        <label class="badge badge-danger"> {{__('messages.Denied')}}</label>
                                        @elseif($withdraw->status==2)
                                        <label class="badge badge-info"> {{__('messages.Pending')}}</label>
                                        @endif
                                    </td>
                                    <td>{{$withdraw->admin ? $withdraw->admin->name : ' '}}</td>
                                    <td>
                                        <a href="{{route('admin.settings.withdrawal_requests.details',$withdraw->id)}}" class="btn btn-info btn-sm"><i class="la la-eye"></i>
                                        </a>

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

    @endsection