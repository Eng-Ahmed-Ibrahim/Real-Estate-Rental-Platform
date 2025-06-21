@extends('admin.app')
@section('title',__('messages.Payment_list'))

@section('css')
<style>
	/* Style the Image Used to Trigger the Modal */
	#myImg {
		border-radius: 5px;
		cursor: pointer;
		transition: 0.3s;
	}

	#myImg:hover {opacity: 0.7;}

	/* The Modal (background) */
	.modal {
		display: none; /* Hidden by default */
		position: fixed; /* Stay in place */
		z-index: 9999; /* Sit on top */
		padding-top: 100px; /* Location of the box */
		left: 0;
		top: 0;
		width: 100%; /* Full width */
		height: 100%; /* Full height */
		overflow: auto; /* Enable scroll if needed */
		background-color: rgb(0,0,0); /* Fallback color */
		background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
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
	.modal-content, #caption { 
		-webkit-animation-name: zoom;
		-webkit-animation-duration: 0.6s;
		animation-name: zoom;
		animation-duration: 0.6s;
	}

	@-webkit-keyframes zoom {
		from {-webkit-transform:scale(0)} 
		to {-webkit-transform:scale(1)}
	}

	@keyframes zoom {
		from {transform:scale(0)} 
		to {transform:scale(1)}
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
	@media only screen and (max-width: 700px){
		.modal-content {
			width: 100%;
		}
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
					<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Payment_list')}}</h1>
					<!--end::Title-->
					<!--begin::Breadcrumb-->
					<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">
							<a  class="text-muted text-hover-primary">{{__('messages.Payment')}}</a>
						</li>
						<!--end::Item-->
						<!--begin::Item-->
						<li class="breadcrumb-item">
							<span class="bullet bg-gray-400 w-5px h-2px"></span>
						</li>
						<!--end::Item-->
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">{{__('messages.Payment_list')}}</li>
						<!--end::Item-->
					</ul>
					<!--end::Breadcrumb-->
				</div>
				<!--end::Page title-->

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
								<th class="ps-4 min-w-100px rounded-start">{{__('messages.Invoice_id')}}</th>
								<th class="ps-4 min-w-100px rounded-start">{{__('messages.Customer_name')}}</th>
								<th class="ps-4 min-w-100px rounded-start">{{__('messages.Amount')}}</th>
								<th class="ps-4 min-w-100px rounded-start">{{__('messages.Payment_status')}}</th>
								<th class="ps-4 min-w-100px rounded-start">{{__('messages.Payment_method')}}</th>
								<th class="ps-4 min-w-100px rounded-start">{{__('messages.Attachment')}}</th>
								<th class="ps-4 min-w-100px rounded-start">{{__('messages.Date')}}</th>
								<!-- <th class="ps-4 min-w-100px rounded-start">{{__('messages.Invoice')}}</th> -->

								
							</tr>
						</thead>

						<tbody>
							@foreach($payments as $payment)
							<tr>
								<td>{{$payment->invoice_id}}</td>
								<td>{{ $payment->customer->name}}</td>
								<td>{{ $payment->amount}}</td>
								<td class="text-center">
									@php
									$name=$payment->payment_status->name;
									@endphp

                                    @if($payment->payment_status->id==1)
                                    <span class="badge badge-light-danger p-3">{{__("messages.$name")}}</span>
                                    @elseif($payment->payment_status->id==2)
                                    <span class="badge badge-light-warning p-3"> {{__("messages.$name")}}</span>
                                    @elseif($payment->payment_status->id==3)

                                    <span class="badge badge-light-success p-3">{{__("messages.$name")}}</span>
                                    @endif
                                </td>
								<td>{{ isset($payment->payment_method) ? ( session('lang')=='en'?  $payment->payment_method->name : $payment->payment_method->name_ar): ' '}}</td>
								<td><img onclick="ImageModal('{{$payment->attachment}}')" src="/{{$payment->attachment}}" style="height:50px" alt=""></td>
								<td>{{ $payment->created_at->diffForHumans()}}</td>
                                <!-- <td> <a  class="btn btn-bg-primary btn-color-muted btn-active-color-primary btn-sm px-4" style="color:white">{{__('messages.Invoice')}}</a> </td> -->

							</tr>
							@endforeach
						</tbody>
						<!--end::Table body-->
					</table>
						

					</div>
					<!--end::Body-->
				</div>
				<!--end::About card-->
			</div>
			<!--end::Content container-->
		</div>
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
	function ImageModal(image){

		var modalImg = document.getElementById("img01");

		// Get the image and insert it inside the modal - use its "alt" text as a caption

		var captionText = document.getElementById("caption");

		modal.style.display = "block";
		modalImg.src = '/'+image;
		

		
		
	}

	function CloseModal(){
		
		modal.style.display = "none";
	}


</script>
@endsection