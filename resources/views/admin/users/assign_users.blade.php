@extends('admin.app')
@section('title',trans('messages.Assign'))
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
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Assign')}}</h1>
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted text-hover-primary">{{__('messages.Pages')}}</a>
					</li>
					<li class="breadcrumb-item">
						<span class="bullet bg-gray-400 w-5px h-2px"></span>
					</li>
					<li class="breadcrumb-item text-muted">{{__('messages.Assign')}}</li>
				</ul>
			</div>
			<div class="d-flex align-items-center gap-2 gap-lg-3">
				<div class="m-0">
					<a href="#" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
					{{__('messages.Filter')}}</a>
					<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_64b77630f13b9">
						<div class="px-7 py-5">
							<div class="fs-5 text-dark fw-bold">{{__('messages.Filter_Options')}}</div>
						</div>

						<div class="separator border-gray-200"></div>

						<div class="px-7 py-5">
							<div class="mb-10">
								<label class="form-label fw-semibold">{{__('messages.Provider')}}:</label>

								<form method="get" id="filter-form">
									<select onchange="document.querySelector('#filter-form').submit()"  name="provider_id" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="{{__('messages.Provider')}}" data-dropdown-parent="#kt_menu_64b77630f13b9" data-allow-clear="true">
										<option ></option>
										@foreach($providers as $provider)
										<option value="{{$provider->id}}">{{$provider->name}}</option>
										@endforeach

									</select>
								</form>
							</div>



							<div class="d-flex justify-content-end">
								<button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">{{__('messages.Apply')}}</button>
							</div>
						</div>
					</div>
				</div>
				@can("Add Assign")
				<a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#assign-user">{{__('messages.Assign')}}</a>
				@endcan
			</div>
		</div>
	</div>
	<div id="kt_app_content" class="app-content flex-column-fluid">
		<div id="kt_app_content_container" class="app-container container-xxl">
			<div class="card">
				<div class="card-body p-lg-17">


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

								<td><a href="{{route('admin.profile',$assing->provider->id)}}">{{$assing->provider->name}}</a></td>
								<td><a href="{{route('admin.profile',$assing->employee->id)}}">{{$assing->employee->name}}</a></td>
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

					{{ $assings->links('vendor.pagination.custom') }}

				</div>
			</div>
		</div>
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
						<select name="employee_id" class="form-select" aria-label="Default select example">
							<option selected disabled>{{__('messages.Employee')}}</option>
							@foreach($employees as $employee)
							<option value="{{$employee->id}}">{{$employee->name}}</option>
							@endforeach

						</select>
					</div>
					<div class="my-3">
						<select name="provider_id" class="form-select" aria-label="Default select example">
							<option selected disabled>{{__('messages.Provider')}}</option>
							@foreach($providers as $provider)
							<option value="{{$provider->id}}">{{$provider->name}}</option>
							@endforeach
						</select>
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