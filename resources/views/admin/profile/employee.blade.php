@extends('admin.app')
@section('title',__('messages.Profile'))
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Employee')}}</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{route('admin.dashboard')}}" class="text-muted text-hover-primary">{{__('messages.Home')}}</a>
                    </li>
                    <li class="breadcrumb-item text-muted"><a href="/admin/users?role=employee" class="text-muted text-hover-primary">{{__('messages.Employee')}}</a>
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="#" class="btn btn-sm fw-bold btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#add-log">{{__('messages.Add_log')}}</a>

                @can('Add Assign')
                <a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#assign-user">{{__('messages.Assign')}}</a>
                @endcan
            </div>
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                    <!--begin::Card-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body pt-15">
                            <!--begin::Summary-->
                            <div class="d-flex flex-center flex-column mb-5">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-100px symbol-circle mb-7">
                                    <img src="/{{$user->image}}" alt="image" data--h-bstatus="4PROCESSING">
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Name-->
                                <a  class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">{{$user->name}}</a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="fs-5 fw-semibold text-muted mb-6">{{$user->roles->first()->name}}</div>
                                <!--end::Position-->

                            </div>
                            <!--end::Summary-->
                            <!--begin::Details toggle-->
                            <div class="d-flex flex-stack fs-4 py-3">
                                <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_customer_view_details" role="button" aria-expanded="false" aria-controls="kt_customer_view_details">{{__('messages.Details')}} <span class="ms-2 rotate-180">
                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </span>
                                </div>
                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-original-title="Edit customer details" data-kt-initialized="1">
                                    @if(auth()->user()->can('edit user') || $user->id == auth()->user()->id)

                                    <a href="{{route('admin.profile.edit',$user->id)}}" class="btn btn-sm btn-light-primary">{{__('messages.Edit')}}</a>
                                    @endif
                                </span>
                            </div>
                            <!--end::Details toggle-->
                            <div class="separator separator-dashed my-3"></div>
                            <!--begin::Details content-->
                            <div id="kt_customer_view_details" class="collapse show">
                                <div class="py-5 fs-6">
                                    <!--begin::Badge-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">{{__('messages.Name')}}</div>
                                    <div class="text-gray-600">{{$user->name}}</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">{{__('messages.Email')}}</div>
                                    <div class="text-gray-600">
                                        <a  class="text-gray-600 text-hover-primary">{{$user->email}}</a>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">{{__('messages.Phone')}}</div>
                                    <div class="text-gray-600">
                                        {{$user->phone}}
                                    </div>
                                    <div class="fw-bold mt-5">{{__('messages.Created_at')}}</div>
                                    <div class="text-gray-600">{{$user->created_at}}</div>
                                    <!--begin::Details item-->

                                </div>
                            </div>
                            <!--end::Details content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Sidebar-->
                <!--begin::Content-->
                <div class="flex-lg-row-fluid ms-lg-15">
                    <!--begin:::Tabs-->
                    <!--begin:::Tabs-->
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-15" role="tablist">
                        <!--begin:::Tab item-->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary d-flex align-items-center pb-5 active" data-bs-toggle="tab" href="#Assigned_Providers" aria-selected="true" role="tab">
                                <!-- <i class="ki-duotone ki-home fs-2 me-2"></i> -->
                                {{__('messages.Assigned_Providers')}}
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary d-flex align-items-center pb-5 " data-bs-toggle="tab" href="#logs" aria-selected="true" role="tab">
                                <!-- <i class="ki-duotone ki-home fs-2 me-2"></i> -->
                                {{__('messages.Logs')}}
                            </a>
                        </li>


                    </ul>
                    <!--end:::Tabs-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body pt-15">
                            <!--begin:::Tab content-->
                            <div class="tab-content" id="myTabContent" data-select2-id="select2-data-myTabContent">
                                <!--begin:::Tab pane-->
                                <div class="tab-pane fade show active" id="Assigned_Providers" role="tabpanel" data-select2-id="select2-data-kt_calendar">


                                    <table class="table align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th class="ps-4 min-w-300px rounded-start">{{__('messages.Provider')}}</th>
                                                <th class="min-w-125px">{{__('messages.Employee')}}</th>
                                                @can('Delete Assign')

                                                <th class="min-w-125px">{{__('messages.Actions')}}</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($assings as $assing)
                                            <tr>

                                                <td> <a href="{{route('admin.profile',$assing->provider->id)}}">{{$assing->provider->name}}</a> </td>
                                                <td>{{$assing->employee->name}}</td>
                                                @can('Delete Assign')
                                                <td>
                                                    <form action="{{route('admin.delete_assign')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="assign_id" value="{{$assing->id}}">
                                                        <button type="submit" class="btn btn-bg-light btn-color-muted btn-active-color-danger btn-sm px-4">{{__("messages.Delete")}}</button>
                                                    </form>
                                                </td>
                                                @endcan

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade  " id="logs" role="tabpanel" data-select2-id="select2-data-kt_calendar">

                                    <table class="table align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th class="min-w-125px">{{__('messages.Provider')}}</th>
                                                <th class="min-w-125px">{{__('messages.Subject')}}</th>
                                                <th class="min-w-125px">{{__('messages.Communicate_by')}}</th>
                                                <th class="min-w-125px">{{__('messages.Notes')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($logs as $log)
                                            <tr>
                                                <td><a href="{{route('admin.profile',$log->provider->id)}}">{{$log->provider->name}}</a></td>
                                                <td>{{$log->subject}}</td>
                                                <td>{{$log->communicate_by}}</td>
                                                <td>
                                                    <button onclick="setNotes(`{{$log->notes}}`)" data-bs-toggle="modal" data-bs-target="#notesModal" class="btn">
                                                        <i class="fa-regular fa-comment" style="font-size: 20px;cursor: pointer;"></i>
                                                    </button>
                                                </td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>


                                </div>








                            </div>
                            <!--end:::Tab content-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Layout-->

    </div>
    <!--end::Content container-->
</div>
<!--end::Content-->
</div>
<!-- Modal -->
<div class="modal fade" id="assign-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{route('admin.assign_employee_to_provider')}}" method="post" class="modal-content">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.Assign_user')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="my-3">
                    <select name="provider_id" class="form-select" aria-label="Default select example">
                        <option selected disabled>{{__('messages.Provider')}}</option>
                        @foreach($providers as $provider)
                        <option value="{{$provider->id}}">{{$provider->name}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="employee_id" value="{{$user->id}}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('messages.Close')}}</button>
                <button type="submit" class="btn btn-primary">{{__('messages.Save_changes')}}</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="notesModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.Notes')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <!-- Textarea -->
                    <div class="form-floating">
                        <textarea readonly name="notes" style="min-height: 300px;resize: none;background:white" class="form-control" placeholder="{{__('messages.Notes')}}" id="notes"></textarea>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="add-log" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{route('admin.add_log')}}" method="post" class="modal-content">
            @csrf
            <input type="text" hidden value="{{$user->id}}" name="employee_id">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.Add_log')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="provider_id" value="{{$user->id}}">
                <div class="mb-3">
                    <select name="provider_id" class="form-select" aria-label="Default select example">
                        <option selected disabled>{{__('messages.Provider')}}</option>
                        @foreach($providers as $provider)
                        <option value="{{$provider->id}}">{{$provider->name}}</option>
                        @endforeach

                    </select>
                </div>
                <div class="mb-3">
                    <select name="subject" class="form-select" aria-label="Default select example">
                        <option selected disabled>{{__('messages.Subject')}}</option>
                        <option value="{{__('messages.Property')}}">{{__('messages.Property')}}</option>
                        <option value="{{__('messages.Details_about_adding_property')}}">{{__('messages.Details_about_adding_property')}}</option>
                    </select>
                </div>
                <div class="mb-3">
                    <select name="communicate_by" class="form-select" aria-label="Default select example">
                        <option selected disabled>{{__('messages.Communicate_by')}}</option>
                        <option value="{{__('messages.Meeting')}}">{{__('messages.Meeting')}}</option>
                        <option value="{{__('messages.Call')}}">{{__('messages.Call')}}</option>
                    </select>
                </div>
                <div class="mb-3">
                    <!-- Textarea -->
                    <div class="form-floating">
                        <textarea name="notes" style="min-height: 250px;" class="form-control" placeholder="{{__('messages.Notes')}}" id="floatingTextarea"></textarea>
                        <label for="floatingTextarea">{{__('messages.Notes')}}</label>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary w-100">{{__('messages.Add')}}</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('js')
<script>
    function setNotes(notes) {
        console.log(notes);
        let textarea = document.getElementById("notes")
        textarea.value = notes
    }
</script>
@endsection