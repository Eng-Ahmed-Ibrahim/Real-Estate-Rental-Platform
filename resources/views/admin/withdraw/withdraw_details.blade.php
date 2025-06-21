@extends('admin.app')
@section('title',trans('messages.Withdrawal_requests'))
@section("css")
<style>
    .heading-elements-toggle , .heading-elements{
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
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3>{{__('messages.vendor_withdraw_information')}}</h3>
                    </div>

                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <!-- class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users" -->
                <div class="card-body py-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-head-inverse bg-primary">
                                    <h4 class="card-title text-white">
                                        {{__('messages.vendor_withdraw_information')}}
                                    </h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <i class="la la-cc-mastercard font-medium-3"></i>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <h5 class="text-capitalize font-weight-bold">
                                                {{__('messages.Amount')}} : {{$withdraw->amount}}
                                            </h5>
                                            <h5 class="text-capitalize font-weight-bold">
                                                {{__('messages.Blance')}} : {{$withdraw->user->blance}}
                                            </h5>
                                            <h5 class="text-capitalize font-weight-bold">{{__('messages.Request_time')}} : {{$created_at}}</h5>
                                        </div>


                                        <div class="col-4">
                                            <div class="text-center float-right text-capitalize">

                                                @if($withdraw->status == 1)
                                                <label class="badge badge-success"> {{__('messages.Approved')}}</label>
                                                @elseif($withdraw->status==0)
                                                <label class="badge badge-danger"> {{__('messages.Denied')}}</label>
                                                @elseif($withdraw->status==2)
                                                <label class="badge badge-info"> {{__('messages.Pending')}}</label>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-4 col-sm-12">
                            <div class="card border-success mb-3 box-shadow-0 ">
                                <div class="card-header">
                                    <h4 class="card-title font-weight-bold">{{__('messages.Payment_method')}}</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <i class="la la-dollar font-weight-bold"></i>
                                    </div>
                                </div>
                                <hr>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <h4 class="font-weight-bold">{{__('messages.Method_name')}}
                                            : {{ session('lang') =='en' ?  $withdraw->payment_method->name :$withdraw->payment_method->name_ar}}</h4>
                                        <h6>{{__('messages.Account_no')}}
                                            : {{$withdraw->account_number}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="card border-top-danger box-shadow-0 border-bottom-danger">
                                <div class="card-header">
                                    <h4 class="card-title font-weight-bold">{{__('messages.Owner_info')}}</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <i class="la la-user font-weight-bold"></i>
                                    </div>
                                </div>
                                <hr>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <h5>{{__('messages.Name')}} : {{$withdraw->user->name}}</h5>
                                        <h5>{{__('messages.Phone')}} : {{$withdraw->user->phone}}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($withdraw->status == 1)
                        <div class="col-md-4 col-sm-12">
                            <div class="card border-top-danger box-shadow-0 border-bottom-danger">
                                <div class="card-header">
                                    <h4 class="card-title font-weight-bold">{{__('messages.Payment_image')}}</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <i class="la la-user font-weight-bold"></i>
                                    </div>
                                </div>
                                <hr>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <h4>{{__('messages.Admin_name')}} : {{$withdraw->admin->name}}</h4>
                                        <img src="/{{$withdraw->admin_attachment}}" style="height:200px; width:100%" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($withdraw->status==2 && auth()->user()->can('accept or denied withdrawal'))
                        <div class="col-12 text-center">

                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approved"> {{__('messages.Approved')}} </button>
                            <form style="display: inline;" action="{{route('admin.settings.change_withdraw_status',$withdraw->id)}}" method="post">
                                @csrf
                                <input type="hidden" name="status" value="0">
                                <button type="submit" class="btn btn-danger"> {{__('messages.Denied')}} </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="approved" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form enctype="multipart/form-data" class="modal-content" action="{{route('admin.settings.change_withdraw_status',$withdraw->id)}}" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.Approved')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        @csrf
                        <input  type="hidden" name="status" value="1">
                        <div class="row my-4">
                            <label for="attachment">{{__('messages.Payment_image')}}</label>
                            <input  required type="file" id="attachment" name="attachment" >
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('messages.Close')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('messages.Save_changes')}}</button>
                </div>
            </form>
        </div>
    </div>
    @endsection