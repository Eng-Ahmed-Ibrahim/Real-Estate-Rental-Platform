@extends('admin.app')
@section('title',__('messages.Features'))
@section('content')
	<div class="d-flex flex-column flex-column-fluid">

		<!--begin::Toolbar-->
		<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
			<!--begin::Toolbar container-->
			<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
				<!--begin::Page title-->
				<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
					<!--begin::Title-->
					<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Features')}}</h1>
					<!--end::Title-->
					<!--begin::Breadcrumb-->
					<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">
							<a  class="text-muted text-hover-primary">{{__('messages.Pages')}}</a>
						</li>
						<!--end::Item-->
						<!--begin::Item-->
						<li class="breadcrumb-item">
							<span class="bullet bg-gray-400 w-5px h-2px"></span>
						</li>
						<!--end::Item-->
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">{{__('messages.Features')}}</li>
						<!--end::Item-->
					</ul>
					<!--end::Breadcrumb-->
				</div>
				<!--end::Page title-->
				<!--begin::Actions-->
				<div class="d-flex align-items-center gap-2 gap-lg-3">


					@can('add feature')
					<button  class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#add">{{__('messages.Create')}}</button>
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
					<div class="card-body p-lg-17" style="overflow-x: scroll;">
					<table class="table align-middle gs-0 gy-4">
						<!--begin::Table head-->
						<thead>
							<tr class="fw-bold text-muted bg-light">
								<th class="ps-4 min-w-125px rounded-start text-center">{{__('messages.Name')}}</th>
								{{-- <th class="min-w-125px text-center">{{__('messages.Image')}}</th> --}}
								<th class="min-w-125px">{{__('messages.Created_at')}}</th>

								<th class="min-w-125px text-center">{{__('messages.Actions')}}</th>
								
							</tr>
						</thead>
						<!--end::Table head-->
						<!--begin::Table body-->
						<tbody>
							@foreach($features as $feature)
							<tr>
								<td class="text-center">
									{{session('lang')=='en'  ? $feature->feature_name : $feature->feature_name_ar}}
								</td>
								{{-- <td class="text-center">
								<img src="/{{$feature->image}}" style="width:60px;"  alt="">

								</td> --}}
								<td>{{ \Carbon\Carbon::parse($feature->created_at)->format('Y-m-d')}}</td>

								<td class="text-center">
									@can('edit feature')
									<button  onclick="setData('{{$feature->id}}','{{$feature->feature_name}}','{{$feature->feature_name_ar}}','{{$feature->image}}')" data-bs-toggle="modal" data-bs-target="#edit" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">
									{{__("messages.Edit")}}
									</button>
									@endcan
									@can('delete feature')
									<form action="{{route('admin.delete_feature')}}" method="post" style="display: inline-block;">
										@csrf 
										<input type="text" name="id" hidden value="{{$feature->id}}">
										<button type="submit"  class="btn btn-bg-light btn-color-muted btn-active-color-danger btn-sm px-4">{{__("messages.Delete")}}</button>
									</form>
									@endcan
								</td>

							</tr>
							@endforeach
						</tbody>
						<!--end::Table body-->
					</table>
					{{ $features->links('vendor.pagination.custom') }}


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
				<h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.Add_new_feature')}}</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" action="{{route('admin.add_feature')}}" method="post">
					@csrf
					<div class="row">
						<div class="col">
							<input required name="name_en" type="text" class="form-control" placeholder="{{__('messages.Name_en')}}" aria-label="First name">
						</div>
						<div class="col">
							<input required name="name_ar" type="text" class="form-control" placeholder="{{__('messages.Name_ar')}}" aria-label="Last name">
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
				<h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.Edit_feature')}}</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" action="{{route('admin.update_feature')}}" method="post">
					@csrf
					<input type="text" name="id" class="feature_id" hidden>
					<div class="row">
						<div class="col">
							<input required name="name_en" type="text"  class="form-control name_en" placeholder="{{__('messages.Name_en')}}" aria-label="First name">
						</div>
						<div class="col">
							<input required name="name_ar" type="text" class="form-control name_ar" placeholder="{{__('messages.Name_ar')}}" aria-label="Last name">
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
	function setData(id,name_en,name_ar,image){
		let InputNameEn=document.querySelector(".name_en")
		let InputNameAr=document.querySelector(".name_ar")
		// let InputImage=document.querySelector(".feature_image")
		let InputID=document.querySelector(".feature_id")
		InputID.value=id
		InputNameAr.value=name_ar
		InputNameEn.value=name_en
	}
</script>
@endsection