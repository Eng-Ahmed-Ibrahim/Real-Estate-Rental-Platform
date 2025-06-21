@extends('admin.app')
@section('title',__('messages.Services'))
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
	<!--begin::Toolbar-->
	<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
		<!--begin::Toolbar container-->
		<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
			<!--begin::Page title-->
			<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
				<!--begin::Title-->
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Services')}}</h1>
				<!--end::Title-->
				<!--begin::Breadcrumb-->
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<!--begin::Item-->
					<li class="breadcrumb-item text-muted">
						<a class="text-muted text-hover-primary">{{__('messages.Services')}}</a>
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

				<a href="{{route('admin.export.properties')}}" class="btn btn-sm fw-bold btn-primary"> {{__('messages.Export_excel')}}</a>
				@can('add Property')
				<a href="{{route('admin.services.create')}}" class="btn btn-sm fw-bold btn-primary">{{__('messages.Create')}}</a>
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
				<div class="card-body p-lg-17" style="    overflow-x: auto;">

					<table class="table align-middle gs-0 gy-4">
						<!--begin::Table head-->
						<thead>
							<tr class="fw-bold text-muted bg-light">
								<th class="ps-4 min-w-100px rounded-start">{{__('messages.Name')}}</th>
								<th class="ps-4 min-w-150px rounded-start">{{__('messages.Provider_name')}}</th>
								<th class="ps-4 min-w-100px rounded-start">{{__('messages.Category_name')}}</th>
								<th class="min-w-100px">{{__('messages.Image')}}</th>
								<th class="min-w-100px">{{__('messages.Amount')}}</th>
								<th class="min-w-150px" style="font-size: 12px;">{{__('messages.Amount_after_commission')}}</th>
								<!-- <th class="min-w-100px">{{__('messages.Avaliable')}}</th> -->
								<th class="min-w-100px">{{__('messages.Accept')}}</th>
								<!-- <th class="min-w-100px">{{__('messages.Status')}}</th> -->
								<th class="min-w-100px">{{__('messages.Best_deal')}}</th>

								<th class="min-w-200px text-center">{{__('messages.Actions')}}</th>

							</tr>
						</thead>
						<!--end::Table head-->
						<!--begin::Table body-->
						<tbody>
							@foreach($services as $service)
							<tr>

								<td>
									<a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ session('lang') == 'en' ? $service->name : $service->name_ar}}</a>
								</td>

								<td>
									<a href="{{route('admin.profile',$service->user->id)}}" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ $service->user->name}}</a>
								</td>
								<td>
									<a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ session('lang') == 'en' ? $service->category->brand_name : $service->category->brand_name_ar }}</a>
								</td>
								<td>
									<img src="/{{$service->image}}" style="height: 60px;" alt="">
								</td>
								<td>{{$service->price }}</td>
								<td>{{$service->price  + $service->commission_money	}}</td>
								<td>
									@if($service->accept ==1)
									<a href="{{route('admin.service.change_accept_status' ,$service->id)}}" class="badge badge-light-success">{{__('messages.Yes')}}</a>
									@elseif($service->accept==2)
									<a href="{{route('admin.service.change_accept_status' ,$service->id)}}" class="badge badge-light-info">{{__('messages.Pending')}}</a>
									@else
									<a href="{{route('admin.service.change_accept_status' ,$service->id)}}" class="badge badge-light-danger">{{__('messages.No')}}</a>
									@endif
								</td>
								<!-- <td>
									@if($service->disabled ==1)
									<a href="{{route('admin.service.disabled_service' ,$service->id)}}" class="badge badge-light-danger">{{__('messages.deleted')}}</a>
									@else
									<a href="{{route('admin.service.disabled_service' ,$service->id)}}" class="badge badge-light-success">{{__('messages.enabled')}}</a>
									@endif
								</td> -->
								<td>
									@if($service->is_best_deal ==1)
									<a href="{{route('admin.service.best_deal' ,$service->id)}}" class="badge badge-light-success">{{__('messages.Yes')}}</a>
									@else
									<a href="{{route('admin.service.best_deal' ,$service->id)}}" class="badge badge-light-danger">{{__('messages.No')}}</a>
									@endif
								</td>
								<td class="text-center">
									@can('show Property')

									<a href="{{route('admin.services.show',$service->id)}}" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">{{__('messages.Show')}}</a>
									@endcan
									@can('edit Property')

									<a href="{{route('admin.services.edit',$service->id)}}" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">{{__('messages.Manage')}}</a>
									@endcan
								</td>
							</tr>
							@endforeach
						</tbody>
						<!--end::Table body-->
					</table>
					{{ $services->links('vendor.pagination.custom') }}

				</div>
				<!--end::Body-->
			</div>
			<!--end::About card-->
		</div>
		<!--end::Content container-->
	</div>
	<!--end::Content-->

	@endsection