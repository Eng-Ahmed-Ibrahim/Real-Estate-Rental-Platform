@extends('admin.app')
@section('title',trans('messages.Subscribers'))
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
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Subscribers')}}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted text-hover-primary">{{__('messages.Packages')}}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{__('messages.Subscribers')}}</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <div class="m-0">
                <a href="{{ route('admin.subscribers.export', request()->query()) }}" class="btn btn-success mt-3">{{__('messages.Export_to_Excel')}}</a>

                </div>

            </div>
        </div>
    </div>
    <!-- s -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card">
                <div class="card-body p-lg-17">
                    <form method="GET" action="{{ route('admin.packages.subscribers') }}">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-12 mb-2">
                                <input type="text" name="name" class="form-control" placeholder="{{__('messages.Filter_by_Provider_Name')}}" value="{{ request('name') }}">
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12 mb-2">
                                <input type="text" name="package_name" class="form-control" placeholder="{{__('messages.Filter_by_Package_Name')}}" value="{{ request('package_name') }}">
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12 mb-2">

                                <select  value="{{ request('paid') }}" name="paid" class="form-select form-select-solid" >
                                    <option selected disabled>{{__('messages.Payment')}}</option>
                                    <option  {{request("paid")=="pending" ? 'selected' :' '}}  value="pending">{{__('messages.pending')}}</option>
                                    <option  {{request("paid")=="paid" ? 'selected' :' '}}  value="paid">{{__('messages.paid')}}</option>
                                    <option   {{request("paid")=="rejected" ? 'selected' :' '}} value="rejected">{{__('messages.rejected')}}</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12 mb-2">

                                <select   name="status" class="form-select form-select-solid" >
                                    <option selected disabled>{{__('messages.Status')}}</option>
                                    <option {{request("status")==1 ? 'selected' :' '}} value="1">{{__('messages.Active')}}</option>
                                    <option  {{request("status")==0 ? 'selected' :' '}} value="0">{{__('messages.Not_Active')}}</option>

                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xs-12 mb-2 row" style="align-items: center;">
								<label for="from " class="form-label text-center" style="    display: inline;width: 20%;">{{__('messages.From')}}</label>
								<input  value="{{ request('from') !== null ? request('from') : ' ' }}" onchange="dateFilter()" 
								type="date" class="form-control col-10" id="from" name="from" style="    display: inline;width: 80%;">
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 mb-2 row" style="align-items: center;">
								<label for="to" class="form-label text-center" style="    display: inline;width: 20%;">{{__('messages.To')}}</label>
								<input type="date" onchange="dateFilter()" class="form-control" id="to"
									style="display: inline;width: 80%;" name="to"
                                    value="{{ request('to') !== null ? request('to') : ' ' }}">
							</div>
                            <div class="col-md-3 mb-2">
                                <button type="submit" class="btn btn-primary">{{__('messages.Filter')}}</button>
                                <a href="{{ route('admin.packages.subscribers') }}" class="btn btn-secondary">{{__('messages.Reset')}}</a>
                                
                            </div>
                        </div>
                    </form>
                    <div  style="width:100%;overflow-x:scroll">

                    <table class="table align-middle gs-0 gy-4 w-100">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-125px rounded-start">{{__('messages.Provider_name')}}</th>
                                <th class="min-w-125px">{{__('messages.Package_name')}}</th>
                                <th class="min-w-125px">{{__('messages.Duration')}}</th>
                                <th class="min-w-125px">{{__('messages.Amount')}}</th>
                                <th class="min-w-125px">{{__('messages.Payment_method')}}</th>
                                <th class="min-w-125px">{{__('messages.Payment')}}</th>
                                <th class="min-w-125px">{{__('messages.Status')}}</th>
                                <th class="min-w-125px">{{__('messages.Attachment')}}</th>
                                <th class="min-w-125px">{{__('messages.start_subscribe')}}</th>
                                <th class="min-w-125px">{{__('messages.end_subscribe')}}</th>
                                @can('change_subscriber_status')

                                <th class="min-w-125px">{{__('messages.Actions')}}</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscribers as $subscriber)
                            <tr>
                                <td>{{$subscriber->provider->name}}</td>
                                <td>{{ session('lang') == 'en' ?  $subscriber->package->name :$subscriber->package->name_ar}}</td>
                                <td>{{$subscriber->package_duration }} {{__('messages.Month')}} </td>
                                <td>{{$subscriber->package_amount }} </td>
                                <td>
                                    @if($subscriber->payment_method == "wallet")
                                    {{__("messages.wallet")}}
                                    @else
                                    {{session("lang")=="en" ? $subscriber->method->name : $subscriber->method->name_ar}}
                                    @endif
                                </td>
                                <td>
                                    @if($subscriber->paid=="paid")
                                    <span class="badge badge-light-success">{{__("messages.$subscriber->paid")}}</span>
                                    @elseif($subscriber->paid=="pending")
                                    <span class="badge badge-light-warning">{{__("messages.$subscriber->paid")}}</span>
                                    @else
                                    <span class="badge badge-light-danger">{{__("messages.$subscriber->paid")}}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subscriber->status==1)
                                    <span class="badge badge-light-success">{{__('messages.Active')}}</span>

                                    @else
                                    <span class="badge badge-light-danger">{{__('messages.Not_Active')}}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subscriber->attachment != null)
                                    <a href="{{asset($subscriber->attachment)}}" download>{{__('messages.Download')}}</a>
                                    @else
                                    {{__("messages.No_Attachment_Found")}}
                                    @endif
                                </td>
                                <td>{{$subscriber->start_subscribe}}</td>
                                <td>{{$subscriber->end_subscribe}}</td>
                                @can('change_subscriber_status')
                                <td>

                                    <div id="kt_menu_64b77630f13b912{{$subscriber->id}}">

                                        <form method="POST" action="{{ route('admin.packages.subscribers.status') }}" id="auto-submit-form-payment{{$subscriber->id}}">
                                            @csrf <!-- Laravel CSRF protection -->
                                            <input type="hidden" name="id" value="{{$subscriber->id}}">
                                            <select onchange="document.getElementById('auto-submit-form-payment{{$subscriber->id}}').submit();" name="status" class="form-select form-select-solid my-2" data-kt-select2="true" data-close-on-select="false" data-placeholder="{{__('messages.Payment_status')}}" data-dropdown-parent="#kt_menu_64b77630f13b912{{$subscriber->id}}">
                                                <option selected disabled>{{__('messages.Payment_status')}}</option>
                                                <option value="0">{{__('messages.Rejected')}}</option>
                                                <option value="1">{{__('messages.Paid')}}</option>

                                            </select>
                                        </form>
                                    </div>
                                </td>
                                @endcan
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $subscribers->links('vendor.pagination.custom') }}
                    </div>


                </div>
            </div>
        </div>
    </div>

    @endsection