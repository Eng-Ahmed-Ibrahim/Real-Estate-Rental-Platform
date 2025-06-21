@extends('admin.app')
@section('title',__('messages.Commission'))
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
					<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Commission')}}</h1>
					<!--end::Title-->
					<!--begin::Breadcrumb-->
					<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">
							<a  class="text-muted text-hover-primary">{{__('messages.Commission')}}</a>
						</li>
						<!--end::Item-->
						<!--begin::Item-->
						<li class="breadcrumb-item">
							<span class="bullet bg-gray-400 w-5px h-2px"></span>
						</li>
						<!--end::Item-->
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">{{__('messages.Services')}}</li>
						<!--end::Item-->
					</ul>
					<!--end::Breadcrumb-->
				</div>
				<!--end::Page title-->
				<!--begin::Actions-->
				<div class="d-flex align-items-center gap-2 gap-lg-3">


					<button  class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#add">{{__('messages.Create')}}</button>
					<!--end::Primary button-->
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
								<th class="ps-4 min-w-300px rounded-start">{{__('messages.Name')}}</th>
								<th class="min-w-125px">{{__('messages.Value')}}</th>
								<th class="min-w-125px">{{__('messages.Actions')}}</th>
								
							</tr>
						</thead>
						<!--end::Table head-->
						<!--begin::Table body-->
						<tbody>
							@foreach($commissions as $commission)
							<tr>
								<td>
									{{session('lang')=='en'  ? $commission->commission_name : $commission->commission_name_ar}}
								</td>
								<td>
									{{$commission->commission_value}}

								</td>
								<td class="text-center">
									<button  onclick="setData('{{$commission->id}}','{{$commission->commission_name}}','{{$commission->commission_name_ar}}','{{$commission->commission_value}}')"
									 data-bs-toggle="modal" data-bs-target="#edit" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">{{__('messages.Edit')}}</button>
									<form action="{{route('admin.delete_commission')}}" method="post" style="display: inline-block;">
										@csrf 
										<input type="text" name="id" hidden value="{{$commission->id}}">
										<button type="submit"  class="btn btn-bg-light btn-color-muted btn-active-color-danger btn-sm px-4">{{__('messages.Delete')}}</button>
									</form>
								</td>

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


				<!-- modals -->

	<!-- add -->
	<div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.add_new_commission')}}</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" action="{{route('admin.add_commission')}}" method="post">
					@csrf
					<div class="row">
						<div class="col">
							<input required name="name_en" type="text" class="form-control" placeholder="{{__('messages.Name_en')}}" aria-label="First name">
						</div>
						<div class="col">
							<input required name="name_ar" type="text" class="form-control" placeholder="{{__('messages.Name_ar')}}" aria-label="Last name">
						</div>
						
					</div>
					<div class="row my-2">
						<div class="col">
						<input required name="value" type="text" class="form-control " placeholder="{{__('messages.Value')}}" aria-label="Last name">
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
				<h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.edit_commission')}}</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" action="{{route('admin.update_commission')}}" method="post">
					@csrf
					<input type="text" name="id" class="commission_id" hidden>
					<div class="row">
						<div class="col">
							<input required name="name_en" type="text"  class="form-control name_en" placeholder="{{__('messages.Name_en')}}" aria-label="First name">
						</div>
						<div class="col">
							<input required name="name_ar" type="text" class="form-control name_ar" placeholder="{{__('messages.Name_ar')}}" aria-label="Last name">
						</div>
						
					</div>
					<div class="row my-2">
						<div class="col text-center">
							<input required name="value" type="text" class="form-control value" placeholder="{{__('messages.Value')}}" aria-label="Last name">
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
	function setData(id,name_en,name_ar,value){
		let InputNameEn=document.querySelector(".name_en")
		let InputNameAr=document.querySelector(".name_ar")
		let InputValue=document.querySelector(".value")
		let InputID=document.querySelector(".commission_id")

		InputID.value=id
		InputNameAr.value=name_ar
		InputNameEn.value=name_en
		InputValue.value=value
	}
</script>
@endsection