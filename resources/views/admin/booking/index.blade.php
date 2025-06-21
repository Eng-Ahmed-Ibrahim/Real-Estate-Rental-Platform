@extends('admin.app')
@section('title',__("messages.Booking"))
@section('css')
<style>
	/* Style the Image Used to Trigger the Modal */
	#myImg {
		border-radius: 5px;
		cursor: pointer;
		transition: 0.3s;
	}

	#myImg:hover {
		opacity: 0.7;
	}

	/* The Modal (background) */
	.modal {
		display: none;
		/* Hidden by default */
		position: fixed;
		/* Stay in place */
		z-index: 9999;
		/* Sit on top */
		padding-top: 100px;
		/* Location of the box */
		left: 0;
		top: 0;
		width: 100%;
		/* Full width */
		height: 100%;
		/* Full height */
		overflow: auto;
		/* Enable scroll if needed */
		background-color: rgb(0, 0, 0);
		/* Fallback color */
		background-color: rgba(0, 0, 0, 0.9);
		/* Black w/ opacity */
	}

	/* Modal Content (Image) */
	.modal-content {
		margin: auto;
		display: block;
		width: 100%;
		max-width: 900px;
	}

	/* Caption of Modal Image (Image Text) - Same Width as the Image */
	#caption {
		margin: auto;
		display: block;
		width: 80%;
		max-width: 700px;
		text-align: center;
		color: #ccc;
		padding: 10px 0;
		height: 150px;
	}

	/* Add Animation - Zoom in the Modal */
	.modal-content,
	#caption {
		-webkit-animation-name: zoom;
		-webkit-animation-duration: 0.6s;
		animation-name: zoom;
		animation-duration: 0.6s;
	}

	@-webkit-keyframes zoom {
		from {
			-webkit-transform: scale(0)
		}

		to {
			-webkit-transform: scale(1)
		}
	}

	@keyframes zoom {
		from {
			transform: scale(0)
		}

		to {
			transform: scale(1)
		}
	}

	/* The Close Button */
	.close {
		position: absolute;
		top: 15px;
		right: 35px;
		color: #f1f1f1;
		font-size: 40px;
		font-weight: bold;
		transition: 0.3s;
	}

	.close:hover,
	.close:focus {
		color: #bbb;
		text-decoration: none;
		cursor: pointer;
	}

	/* 100% Image Width on Smaller Screens */
	@media only screen and (max-width: 700px) {
		.modal-content {
			width: 100%;
		}
	}
</style>
@endsection
@section('content')
<div class="d-flex flex-column flex-column-fluid">
	<div class="container">


	</div>
	<!--begin::Toolbar-->
	<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
		<!--begin::Toolbar container-->
		<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
			<!--begin::Page title-->
			<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
				<!--begin::Title-->
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Booking')}}</h1>
				<!--end::Title-->
				<!--begin::Breadcrumb-->
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<!--begin::Item-->
					<li class="breadcrumb-item text-muted">
						<a class="text-muted text-hover-primary">{{__('messages.Booking')}}</a>
					</li>
					<!--end::Item-->
					<!--begin::Item-->
					<li class="breadcrumb-item">
						<span class="bullet bg-gray-400 w-5px h-2px"></span>
					</li>
					<!--end::Item-->
					<!--begin::Item-->
					<li class="breadcrumb-item text-muted">{{__('messages.Booking_list')}}</li>
					<!--end::Item-->
				</ul>
				<!--end::Breadcrumb-->
			</div>
			<!--end::Page title-->
			<!--begin::Actions-->
			<!--begin::Actions-->
			<div class="d-flex align-items-center gap-2 gap-lg-3">


				<!-- <a href="{{route('admin.export.booking')}}" class="btn btn-sm fw-bold btn-primary"> {{__('messages.Export_excel')}}</a> -->

			</div>
			<!--end::Actions-->
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
				<div class="card-body w-100 " style="padding: 10px;overflow-x: scroll;">
					<div class="my-2 row" style="    align-items: center;">
						<div class="col-lg-2 col-md-4 my-2 col-sm-4 p-1">
							<form action="{{route('admin.export.booking')}}" method="get">
								@if(request()->query('date'))
								<input type="hidden" name="date" value="{{request()->query('date')}}">
								@endif
								@if(request()->query('from') && request()->query('to') )
								<input type="hidden" name="from" value="{{request()->query('from')}}">
								<input type="hidden" name="to" value="{{request()->query('to')}}">
								@endif
								<button class="w-100 btn btn-sm fw-bold btn-primary m-0 ">{{__('messages.Export_excel')}}</button>
							</form>
						</div>
						<form id="filterForm" class="   my-2 col-lg-2 col-md-4 col-sm-4 p-1" action="{{ route('admin.booking') }}" method="GET">
							<input type="hidden" name="booking_status" value="{{request()->query('booking_status')}}">
							<select name="date" id="dateFilter" class="form-select w-100" onchange="this.form.submit()">
								<option value="all" {{! request()->query('date')   ? 'selected' : ' '}}>{{ __('messages.All') }}</option>
								<option value="daily" {{ request()->query('date') == 'daily'  ? 'selected' : ' '}}>{{ __('messages.Daily') }}</option>
								<option value="weekly" {{ request()->query('date') == 'weekly' ? 'selected' : ' '}}>{{ __('messages.Weekly') }}</option>
								<option value="monthly" {{ request()->query('date') == 'monthly'  ? 'selected' : ' '}}>{{ __('messages.Monthly') }}</option>
							</select>
						</form>

						<form id="dateBetweenFilter" action="{{route('admin.booking')}}" class="  my-2 col-lg-6 col-md-12 row p-1">
							<input type="hidden" name="booking_status" value="{{request()->query('booking_status')}}">
							<div class="col-lg-6 col-sm-12 row my-2" style="align-items: center;">
								<label for="from" class="form-label text-center p-0 " style="    display: inline;width: 25% !important;">{{__('messages.From')}}</label>
								<input onchange="dateFilter()" type="date" class="form-control " id="from" name="from" style="    display: inline;width: 75% !important;">
							</div>
							<div class="col-lg-6 col-sm-12 row my-2" style="align-items: center;">
								<label for="to" class="form-label text-center p-0" style="    display: inline;width: 25% !important;">{{__('messages.To')}}</label>
								<input type="date" onchange="dateFilter()" class="form-control" id="to" style="    display: inline;width: 75% !important;" name="to" value="<?php echo date('Y-m-d'); ?>">
							</div>
						</form>
						<div class="col-lg-2 col-md-4 my-2 col-sm-4 p-1">
							@if(   request('booking_status') == null )
							<a href="/admin/booking" class="btn btn-primary"> {{__("messages.Reset")}} </a>
							@else 
							<a href="/admin/booking?booking_status={{ request('booking_status')}}" class="btn btn-primary"> {{__("messages.Reset")}} </a>
							@endif
						</div>

					</div>
					<div style="width: 100%; overflow-x:scroll">

					<table class="table align-middle gs-0 gy-4 w-100">
						<!--begin::Table head-->
						<thead>
							<tr class="fw-bold text-muted bg-light">
								<th class="min-w-30px rounded-start">#</th>
								<th class="min-w-100px rounded-start">{{__('messages.Customer_name')}}</th>
								<th class="min-w-100px">{{__('messages.Provider_name')}}</th>
								<th class="ps-4 min-w-80px ">{{__('messages.Service_name')}}</th>
								<th class="ps-4 min-w-80px ">{{__('messages.Category_name')}}</th>
								<th class="min-w-50px">{{__('messages.Price')}}</th>
								<th class="min-w-50px">{{__('messages.Booking_status')}}</th>
								<th class="min-w-50px">{{__('messages.Payment_status')}}</th>
								<th class="min-w-50px">{{__('messages.Payment_type')}}</th>
								<th class="min-w-100px">{{__('messages.Start_at')}}</th>
								<th class="min-w-100px">{{__('messages.End_at')}}</th>
								<th class="min-w-150px">{{__('messages.Actions')}}</th>

							</tr>
						</thead>

						<tbody>

							@if(count($bookings )> 0)
							@foreach($bookings as $booking)
							<tr>
								<td><a href="{{route('admin.booking.show',$booking->id)}}">{{$booking->id}}</a> </td>
								<td>
									<a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{$booking->customer->name}}</a>
								</td>
								<td>
									<a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{$booking->provider->name}}</a>
								</td>
								<td>
									<a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{session('lang')== 'en' ?  $booking->service->name : $booking->service->name_ar}}</a>
								</td>
								<td>
									<a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{session('lang')== 'en' ?  $booking->category->brand_name : $booking->category->brand_name_ar}}</a>
								</td>
								<td>
									<a class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{$booking->total_amount}}</a>
								</td>
								<td>
									@if($booking->booking_status_id ==1)
									<span class="badge badge-light-Secondary">{{__('messages.Pending')}}</span>
									@elseif($booking->booking_status_id==3)
									<span class="badge badge-light-success">{{__('messages.Approved')}}</span>
									@elseif($booking->booking_status_id==4)
									<span class="badge badge-light-danger">{{__('messages.Rejected')}}</span>
									@elseif($booking->booking_status_id==5)
									<span class="badge badge-light-danger">{{__('messages.Cancelled')}}</span>
									@elseif($booking->booking_status_id==6)
									<span class="badge badge-light-info">{{__('messages.Overview_time')}}</span>
									@elseif($booking->booking_status_id==5)
									<span class="badge badge-light-light">{{__('messages.Overview_time_payment')}}</span>
									@endif
								</td>
								<td>
									@if($booking->payment_status_id ==1)
									<span class="badge badge-light-danger">{{__('messages.Un_paid')}}</span>
									@elseif($booking->payment_status_id ==2)
									<span class="badge badge-light-warning">{{__('messages.Pending')}}</span>
									@elseif($booking->payment_status_id ==3)
									<span class="badge badge-light-success">{{__('messages.Paid')}}</span>
									@elseif($booking->payment_status_id ==4)
									<span class="badge badge-light-info">{{__('messages.Partialـpayment')}}</span>
									@elseif($booking->payment_status_id ==5)
									<span class="badge badge-light-info">{{__('messages.Under_review')}}</span>
									@elseif($booking->payment_status_id ==6)
									<span class="badge badge-light-info">{{__('messages.Payment_delay')}}</span>
									@endif
								</td>
								<td>
									@if($booking->payment_type != null)
									{{__("messages.$booking->payment_type")}}
									@endif
								</td>
								<!-- <td><img onclick="ImageModal('{{$booking->attachment}}')" src="/{{$booking->attachment}}" style="height:50px" alt=""></td> -->
								<td>{{ \Carbon\Carbon::parse($booking->start_at)->format('Y-m-d')}}</td>
								<td>{{ \Carbon\Carbon::parse($booking->end_at)->format('Y-m-d')}}</td>
								@if($booking->booking_status_id != 5)
								<td class="text-center">
									@can('change booking status')
									<div id='kt_menu_64b77630f13b911{{$booking->id}}'>

										<form method="POST" action="{{ route('admin.booking.change_booking_status') }}" id="auto-submit-form-booking{{$booking->id}}">
											@csrf <!-- Laravel CSRF protection -->
											<input type="hidden" name="id" value="{{$booking->id}}">
											<select onchange="document.getElementById('auto-submit-form-booking{{$booking->id}}').submit();" name="booking_status" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="{{__('messages.Booking_status')}}" data-dropdown-parent="#kt_menu_64b77630f13b911{{$booking->id}}" data-allow-clear="true">
												<option selected disabled>{{__('messages.Booking_status')}}</option>
												<option value="1">{{__('messages.Pending')}}</option>
												<!-- <option value="2">{{__('messages.In_process')}}</option> -->
												<option value="3">{{__('messages.Approved')}}</option>
												<option value="4">{{__('messages.Rejected')}}</option>
											</select>
										</form>
									</div>
									@endcan
									@can('change payment status')

									<div id="kt_menu_64b77630f13b912{{$booking->id}}">

										<form method="POST" action="{{ route('admin.booking.change_payment_status') }}" id="auto-submit-form-payment{{$booking->id}}">
											@csrf <!-- Laravel CSRF protection -->
											<input type="hidden" name="id" value="{{$booking->id}}">
											<select onchange="document.getElementById('auto-submit-form-payment{{$booking->id}}').submit();" name="payment_status" class="form-select form-select-solid my-2" data-kt-select2="true" data-close-on-select="false" data-placeholder="{{__('messages.Payment_status')}}" data-dropdown-parent="#kt_menu_64b77630f13b912{{$booking->id}}" data-allow-clear="true">
												<option selected disabled>{{__('messages.Payment_status')}}</option>
												<option value="1">{{__('messages.Un_paid')}}</option>
												<option value="2">{{__('messages.Pending')}}</option>
												<option value="3">{{__('messages.Paid')}}</option>
												<option value="4">{{__('messages.Partialـpayment')}}</option>

											</select>
										</form>
									</div>
									@endcan

								</td>
								@endif
							</tr>
							@endforeach
							@else
							<tr class="odd">
								<td valign="top" colspan="12" class="dataTables_empty text-center">{{__('messages.No_data_available_in_table')}}</td>
							</tr>
							@endif
						</tbody>
					</table>
					</div>


					{{ $bookings->links('vendor.pagination.custom') }}


				</div>
				<!--end::Body-->
			</div>
			<!--end::About card-->
		</div>
		<!--end::Content container-->
	</div>
	<!--end::Content-->


	<!-- The Modal -->
	<div id="myModal" class="modal">
		<span class="close" onclick="CloseModal()">&times;</span>
		<img class="modal-content" id="img01">
		<div id="caption"></div>
	</div>

	@endsection
	@section('js')
	<script>
		// Get the modal
		var modal = document.getElementById('myModal');

		function ImageModal(image) {

			var modalImg = document.getElementById("img01");

			// Get the image and insert it inside the modal - use its "alt" text as a caption

			var captionText = document.getElementById("caption");

			modal.style.display = "block";
			modalImg.src = '/' + image;




		}

		function CloseModal() {

			modal.style.display = "none";
		}
	</script>
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