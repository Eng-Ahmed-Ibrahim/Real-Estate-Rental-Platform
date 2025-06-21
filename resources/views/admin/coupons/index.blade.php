@extends('admin.app')
@section('title',__('messages.Coupon'))
@section('css')
<style>
	.d-flex-center {
		display: flex;
		justify-content: center;
		align-items: center;
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
	<!--begin::Toolbar-->
	<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
		<!--begin::Toolbar container-->
		<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
			<!--begin::Page title-->
			<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
				<!--begin::Title-->
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Coupon')}}</h1>
				<!--end::Title-->
				<!--begin::Breadcrumb-->
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<!--begin::Item-->
					<li class="breadcrumb-item text-muted">
						<a class="text-muted text-hover-primary">{{__('messages.Pages')}}</a>
					</li>
					<!--end::Item-->
					<!--begin::Item-->
					<li class="breadcrumb-item">
						<span class="bullet bg-gray-400 w-5px h-2px"></span>
					</li>
					<!--end::Item-->
					<!--begin::Item-->
					<li class="breadcrumb-item text-muted">{{__('messages.Coupon')}}</li>
					<!--end::Item-->
				</ul>
				<!--end::Breadcrumb-->
			</div>
			<!--end::Page title-->
			<!--begin::Actions-->
			<div class="d-flex align-items-center gap-2 gap-lg-3">

				@can('add coupon')

				<button class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#add">{{__('messages.Create')}}</button>
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
				<div class="card-body p-lg-17">
					<table class="table align-middle gs-0 gy-4">
						<!--begin::Table head-->
						<thead>
							<tr class="fw-bold text-muted bg-light">
								<th class="ps-4 min-w-125px rounded-start">{{__('messages.Coupon_code')}}</th>
								<th class="min-w-125px">{{__('messages.Coupon_value')}}</th>
								<th class="min-w-125px">{{__('messages.Type')}}</th>
								<th class="min-w-125px">{{__('messages.Start_at')}}</th>
								<th class="min-w-125px">{{__('messages.End_at')}}</th>
								<th class="min-w-125px">{{__('messages.Actions')}}</th>

							</tr>
						</thead>
						<!--end::Table head-->
						<!--begin::Table body-->
						<tbody>
							@foreach($coupons as $coupon)
							<tr>

								<td>{{$coupon->coupon_code}}</td>
								<td>{{$coupon->coupon_value}}</td>
								<td>{{__("messages.$coupon->type")}}</td>
								<td> {{$coupon->start_at}}</td>
								<td>{{$coupon->end_at}} </td>
								<td class="text-center">
									@can('edit coupon')
									<button onclick="setData('{{$coupon->id}}','{{$coupon->coupon_code}}','{{$coupon->coupon_value}}','{{$coupon->type}}','{{ \Carbon\Carbon::parse($coupon->start_at)->format('Y-m-d') }}','{{ \Carbon\Carbon::parse($coupon->end_at)->format('Y-m-d') }}')" data-bs-toggle="modal" data-bs-target="#edit" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">
										{{__('messages.Edit')}}
									</button>
									@endcan
									@can('delete coupon')

									<form action="{{route('admin.delete_coupon')}}" method="post" style="display: inline-block;">
										@csrf
										<input type="text" name="id" hidden value="{{$coupon->id}}">
										<button type="submit" class="btn btn-bg-light btn-color-muted btn-active-color-danger btn-sm px-4">
											{{__('messages.Delete')}}
										</button>
									</form>
									@endcan

								</td>

							</tr>
							@endforeach
						</tbody>
						<!--end::Table body-->
					</table>
					{{ $coupons->links('vendor.pagination.custom') }}


				</div>
				<!--end::Body-->
			</div>
			<!--end::About card-->
		</div>
		<!--end::Content container-->
	</div>
</div>
<!--end::Content-->


<!-- modals -->

<!-- add -->
<div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.add_new_coupon')}}</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" action="{{route('admin.add_coupon')}}" method="post">
					@csrf
					<div class="row my-2">
						<div class="col">
							<input required name="coupon_code" type="text" class="form-control " placeholder="{{__('messages.Coupon_code')}}" aria-label="First name">
						</div>
						<div class="col">
							<input required name="coupon_value" type="text" class="form-control " placeholder="{{__('messages.Coupon_value')}}" aria-label="Last name">
						</div>

					</div>
					<div class="row my-2">
						<div class="col">
							<label for="start_at">{{__('messages.Start_at')}}</label>
							<input required  id="start_at" name="start_at" type="date" class="form-control " placeholder="{{__('messages.Start_at')}}" aria-label="First name">
						</div>
						<div class="col">
							<label for="end_at">{{__('messages.End_at')}}</label>
							<input required id="end_at" name="end_at" type="date" class="form-control " placeholder="{{__('messages.End_at')}}" aria-label="Last name">
						</div>

					</div>
					<div class="row my-2">
						<div class="col text-center">
							<div class="input-group row">
								<label for="coupon_value_percentage" class="col-md-4 col-form-label ">{{__('messages.Percentage')}} (%)</label>
								<div class="col-md-6 d-flex-center">

									<input class="form-check-input" name="type" id="coupon_value_percentage" type="radio" value="percentage" checked="">
								</div>
							</div>
						</div>
						<div class="col text-center">
							<div class="input-group row">
								<label for="coupon_value_amount" class="col-md-4 col-form-label ">{{__('messages.Amount')}} (EGP)</label>
								<div class="col-md-6 d-flex-center">
									<input class="form-check-input" id="coupon_value_amount" name="type" type="radio" value="amount" checked="">
								</div>
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('messages.Close')}}</button>
				<button type="submit" class="btn btn-primary">{{__('messages.Save_changes')}}</button>
			</div>
			</form>
		</div>
	</div>
</div>
<!-- Edit -->
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.edit_coupon')}}</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" action="{{route('admin.update_coupon')}}" method="post">
					@csrf
					<input type="text" class="coupon_id" name="id" class="coupon_id" hidden>
					<div class="row my-2">
						<div class="col">
							<input required name="coupon_code" type="text" class="form-control Coupon_code" placeholder="{{__('messages.Coupon_code')}}" aria-label="First name">
						</div>
						<div class="col">
							<input required name="coupon_value" type="text" class="form-control Coupon_value" placeholder="{{__('messages.Coupon_value')}}" aria-label="Last name">
						</div>

					</div>
					<div class="row my-2">
						<div class="col">
							<input required name="start_at" type="date" class="form-control coupon_start_at" placeholder="{{__('messages.Start_at')}}" aria-label="First name">
						</div>
						<div class="col">
							<input required name="end_at" type="date" class="form-control coupon_end_at" placeholder="{{__('messages.End_at')}}" aria-label="Last name">
						</div>

					</div>
					<div class="row my-2">
						<div class="col text-center">
							<div class="input-group row">
								<label for="coupon_value_percentage" class="col-md-4 col-form-label ">{{__('messages.Percentage')}} (%)</label>
								<div class="col-md-6 d-flex-center">

									<input class="form-check-input" id="coupon_value_percentage_edit" name="type" type="radio" value="percentage">
								</div>
							</div>
						</div>
						<div class="col text-center">
							<div class="input-group row">
								<label for="coupon_value_amount" class="col-md-4 col-form-label ">{{__('messages.Amount')}} (EGP)</label>
								<div class="col-md-6 d-flex-center">
									<input class="form-check-input" id="coupon_value_amount_edit" name="type" type="radio" value="amount">
								</div>
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('messages.Close')}}</button>
				<button type="submit" class="btn btn-primary">{{__('messages.Save_changes')}}</button>
			</div>
			</form>
		</div>
	</div>
</div>

@endsection

@section('js')
<script>
	function setData(id, coupon_code, coupon_value, type, start_at, end_at) {
		let InputEndAt = document.querySelector(".coupon_end_at")
		let InputStartAt = document.querySelector(".coupon_start_at")
		let InputValue = document.querySelector(".Coupon_value")
		let InputCode = document.querySelector(".Coupon_code")
		let InputId = document.querySelector(".coupon_id")
		let InputAmount = document.getElementById('coupon_value_amount_edit');
		let InputPercentage = document.getElementById('coupon_value_percentage_edit');

		InputId.value = id
		InputEndAt.value = end_at
		InputStartAt.value = start_at
		InputValue.value = coupon_value
		InputCode.value = coupon_code
		if (type == 'percentage') {
			InputPercentage.checked = true
			InputAmount.checked = false
		} else {
			InputPercentage.checked = false
			InputAmount.checked = true
		}
	}
</script>
@endsection