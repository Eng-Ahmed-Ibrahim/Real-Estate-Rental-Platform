@extends('admin.app')
@section('title',trans('messages.Payment_reports') )
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
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{trans('messages.Payment_reports')}}</h1>
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted text-hover-primary">{{__('messages.Reports')}}</a>
					</li>
					<li class="breadcrumb-item">
						<span class="bullet bg-gray-400 w-5px h-2px"></span>
					</li>
					<li class="breadcrumb-item text-muted">{{__('messages.Payment_reports')}}</li>
				</ul>
			</div>
			<div class="d-flex align-items-center gap-2 gap-lg-3">
				<!-- <div class="m-0">
					<a href="#" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
						<i class="ki-duotone ki-filter fs-6 text-muted me-1">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>{{__('messages.Filter')}}</a>
					<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_64b77630f13b9">
						<div class="px-7 py-5">
							<div class="fs-5 text-dark fw-bold">{{__('messages.Filter_Options')}}</div>
						</div>

						<div class="separator border-gray-200"></div>
						<form action="{{route('admin.payment_reports')}}"  method="get">

							<div class="m-2">
								<div class="mb-3">
									<label for="formGroupExampleInput" class="form-label">{{__('messages.From')}}</label>
									<input type="date" class="form-control" id="formGroupExampleInput" name="from">
								</div>
								<div class="mb-3">
									<label for="formGroupExampleInput2" class="form-label">{{__('messages.To')}}</label>
									<input type="date" class="form-control" id="formGroupExampleInput2" name="to" value="<?php echo date('Y-m-d'); ?>">
								</div>
							</div>
							<div class="px-7 py-5">




								<div class="d-flex justify-content-end">
									<button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">{{__('messages.Apply')}}</button>
								</div>
							</div>
						</form>
					</div>
				</div> -->

			</div>
		</div>
	</div>
	<div id="kt_app_content" class="app-content flex-column-fluid">
		<div id="kt_app_content_container" class="app-container container-xxl">
			<div class="card">
				<div class="card-body p-lg-17">
					<div class=" row my-2">

						<form id="filterForm" class="col-lg-2 col-md-4  col-sm-12   mb-2" action="{{ route('admin.payment_reports') }}" method="GET">
							<input type="hidden" name="role" value="{{request()->query('role')}}">
							<select name="date" id="dateFilter" class="form-select w-100" onchange="this.form.submit()">
								<option {{$date=="all" ? "selected" : ' '}} value="all" {{! request()->query('date')   ? 'selected' : ' '}}>{{ __('messages.All') }}</option>
								<option {{$date=="daily" ? "selected" : ' '}} value="daily" {{ request()->query('date') == 'daily'  ? 'selected' : ' '}}>{{ __('messages.Daily') }}</option>
								<option {{$date=="weekly" ? "selected" : ' '}} value="weekly" {{ request()->query('date') == 'weekly' ? 'selected' : ' '}}>{{ __('messages.Weekly') }}</option>
								<option {{$date=="monthly" ? "selected" : ' '}} value="monthly" {{ request()->query('date') == 'monthly'  ? 'selected' : ' '}}>{{ __('messages.Monthly') }}</option>
							</select>
						</form>
						<form id="dateBetweenFilter" action="{{route('admin.payment_reports')}}" class="col-lg-8 col-md-8  col-sm-12 row  ">
							<div class="col-lg-6  col-sm-12 mb-2 row" style="align-items: center;">
								<label for="from " class="form-label text-center" style="    display: inline;width: 20%;">{{__('messages.From')}}</label>
								<input value="{{$from != null  ? $from : ' '}}" onchange="dateFilter()" 
								type="date" class="form-control col-10" id="from" name="from" style="    display: inline;width: 80%;">
							</div>
							<div class="col-lg-6  col-sm-12 mb-2 row" style="align-items: center;">
								<label for="to" class="form-label text-center" style="    display: inline;width: 20%;">{{__('messages.To')}}</label>
								<input type="date" onchange="dateFilter()" class="form-control" id="to"
									style="display: inline;width: 80%;" name="to"
									value="{{ isset($to) ? $to : date('Y-m-d') }}">
							</div>
						</form>
						<div class="col-lg-2 col-md-4  col-sm-12   mb-2">
							<a href="/admin/reports/payment-reports" class="btn btn-primary">{{__("messages.Reset")}}</a>
						</div>
					</div>
					<div style="width: 100%;overflow-x: scroll;">
						<table class="table align-middle gs-0 gy-4">
							<thead>
								<tr class="fw-bold text-muted bg-light">
									<th class="ps-4 min-w-125px rounded-start">{{__('messages.Invoice_id')}}</th>
									<th class="min-w-125px">{{__('messages.Customer_name')}}</th>
									<th class="min-w-125px">{{__('messages.Amount')}}</th>
									<th class="min-w-125px">{{__('messages.Payment_method')}}</th>
									<th class="min-w-125px">{{__('messages.Date')}}</th>
									<th class="min-w-125px">{{__('messages.Payment_status')}}</th>

								</tr>
							</thead>
							<tbody>
								@foreach($payments as $payment)

								<tr>
									<td><a href="{{route('admin.payment_reports.show',$payment->invoice_id)}}">#{{$payment->invoice_id}}</a></td>
									<td>{{$payment->customer->name}}</td>
									<td>{{$payment->amount}}</td>
									<td>
										@if($payment->payment_type=="bank_transfer")
										{{session('lang')=='en'? $payment->payment_method->name :  $payment->payment_method->name_ar}}
										@else 
										{{__("messages.$payment->payment_type")}}
										@endif
									</td>
									<td> {{ \Carbon\Carbon::parse($payment->created_at)->toDateString() }}</td>
									<td>
										@if($payment->payment_status_id ==1)
										<span class="badge badge-light-danger">{{__('messages.Un_paid')}}</span>
										@elseif($payment->payment_status_id ==2)
										<span class="badge badge-light-warning">{{__('messages.Pending')}}</span>
										@elseif($payment->payment_status_id ==3)
										<span class="badge badge-light-success">{{__('messages.Paid')}}</span>
										@endif
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>

						{{ $payments->links('vendor.pagination.custom') }}


					</div>
				</div>

			</div>
		</div>
	</div>

	@endsection

	@section("js")
	<script>
		function dateFilter() {
			var from = document.getElementById('from').value;
			var to = document.getElementById('to').value;

			// Check if both 'from' and 'to' fields are filled
			if (from !== '' && to !== '') {
				// Submit the form
				document.getElementById('dateBetweenFilter').submit();
			}
		}
	</script>

	@endsection