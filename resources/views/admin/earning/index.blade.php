@extends('admin.app')
@section('title',__('messages.Earning_list'))
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
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Earning_list')}}</h1>
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<li class="breadcrumb-item text-muted">
						<a  class="text-muted text-hover-primary">{{__('messages.Earning')}}</a>
					</li>
					<li class="breadcrumb-item">
						<span class="bullet bg-gray-400 w-5px h-2px"></span>
					</li>
					<li class="breadcrumb-item text-muted">{{__('messages.Earning_list')}}</li>
				</ul>
			</div>
			<!-- <div class="d-flex align-items-center gap-2 gap-lg-3">
				<div class="m-0">
					<a class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
					<i class="ki-duotone ki-filter fs-6 text-muted me-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>{{__('messages.Filter')}}</a>
					<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_64b77630f13b9">
						<div class="px-7 py-5">
							<div class="fs-5 text-dark fw-bold">{{__('messages.Filter_Options')}}</div>
						</div>

						<div class="separator border-gray-200"></div>

						<div class="px-7 py-5">
							<div class="mb-10">
								<label class="form-label fw-semibold">{{__('messages.Status')}}:</label>

								<div>
									<select class="form-select form-select-solid" multiple="multiple" data-kt-select2="true" data-close-on-select="false" data-placeholder="Select option" data-dropdown-parent="#kt_menu_64b77630f13b9" data-allow-clear="true">
										<option></option>
										<option value="1">Approved</option>
										<option value="2">Pending</option>
										<option value="2">In Process</option>
										<option value="2">Rejected</option>
									</select>
								</div>
							</div>



							<div class="d-flex justify-content-end">
								<button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">{{__('messages.Reset')}}</button>
								<button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">{{__('messages.Apply')}}</button>
							</div>
						</div>
					</div>
				</div>

			</div> -->
		</div>
	</div>
	<div id="kt_app_content" class="app-content flex-column-fluid">
		<div id="kt_app_content_container" class="app-container container-xxl">
			<div class="card">
				<div class="card-body p-lg-17">

                <table class="table align-middle gs-0 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th class="ps-4 min-w-300px rounded-start">{{__('messages.Provider_name')}}</th>
                            <th class="min-w-125px">{{__('messages.Total_earning')}}</th>
                            <th class="min-w-125px">{{__('messages.Provider_earnings')}}</th>
                            <th class="min-w-125px">{{__('messages.Admin_earning')}}</th>
                            
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($earnings as $earning)
                        <tr>
                            <td>{{$earning->user->name}}</td>
                            <td>{{$earning->total_earning}}</td>
                            <td>{{$earning->provider_earning}}</td>
                            <td>{{$earning->admin_earning}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
				{{ $earnings->links('vendor.pagination.custom') }}


				</div>
			</div>
		</div>
	</div>

@endsection