@extends('admin.app')
@section('title',__("messages.$role"))
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
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__("messages.$role")}}</h1>
				<!--end::Title-->
				<!--begin::Breadcrumb-->
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<!--begin::Item-->
					<li class="breadcrumb-item text-muted">
						<a class="text-muted text-hover-primary">{{__('messages.Users')}}</a>
					</li>
					<!--end::Item-->
					<!--begin::Item-->
					<li class="breadcrumb-item">
						<span class="bullet bg-gray-400 w-5px h-2px"></span>
					</li>
					<!--end::Item-->
					<!--begin::Item-->
					<li class="breadcrumb-item text-muted">{{__("messages.$role")}}</li>
					<!--end::Item-->
				</ul>
				<!--end::Breadcrumb-->
			</div>
			<!--end::Page title-->
			<!--begin::Actions-->
			<div class="d-flex align-items-center gap-2 gap-lg-3">
				@if(request('role')==2)
				<a href="{{route('admin.export.providers')}}" class="btn btn-sm fw-bold btn-primary"> {{__('messages.Export_excel')}}</a>
				@endif
				@can('add new user')
				<button class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#add">{{__('messages.Create')}}</button>
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
					<div class="my-2 row">

						<form id="filterForm" class="col-lg-2 col-md-4  " action="{{ route('admin.users') }}" method="GET">
							<input type="hidden" name="role" value="{{request()->query('role')}}">
							<select name="date" id="dateFilter" class="form-select w-100" onchange="this.form.submit()">
								<option value="all" {{! request()->query('date')   ? 'selected' : ' '}}>{{ __('messages.All') }}</option>
								<option value="daily" {{ request()->query('date') == 'daily'  ? 'selected' : ' '}}>{{ __('messages.Daily') }}</option>
								<option value="weekly" {{ request()->query('date') == 'weekly' ? 'selected' : ' '}}>{{ __('messages.Weekly') }}</option>
								<option value="monthly" {{ request()->query('date') == 'monthly'  ? 'selected' : ' '}}>{{ __('messages.Monthly') }}</option>
							</select>
						</form>
						<form action="{{ route('admin.users') }}" method="GET" class=" col-lg-4 col-md-5  " style="display: flex;align-items: center;justify-content: center;">
							<input type="hidden" name="role" value="{{request()->query('role')}}">

							<div style="width: 80%;">
								<input type="text" class="form-control " value="{{ request('search')  }}" style="border-radius: 5px 0 0 5px;" name="search" placeholder="{{__('messages.Search')}}">
							</div>

							<button type="submit" class="btn  btn-primary h-100" style="border-radius: 0 5px 5px 0;width:20%;display: flex;align-items: center;justify-content: center;"><i class="fa-solid fa-magnifying-glass"></i></button>
						</form>

					</div>
					@if(count($users)>0)
					<table class="table align-middle gs-0 gy-4">
						<!--begin::Table head-->
						<thead>
							<tr class="fw-bold text-muted bg-light">
								<th class="ps-4 min-w-300px rounded-start">{{__('messages.Name')}}</th>
								<th class="min-w-125px">{{__('messages.Image')}}</th>
								<th class="min-w-125px">{{__('messages.Phone')}}</th>
								<th class="min-w-125px">{{__('messages.Role')}}</th>
								<th class="min-w-125px">{{__('messages.Actions')}}</th>

							</tr>
						</thead>
						<!--end::Table head-->
						<!--begin::Table body-->
						<tbody>
							@foreach($users as $user)
							<tr>
								<td> <a href="{{route('admin.profile',$user->id)}}">{{$user->name}}</a> </td>
								<td><img src="/{{$user->image}}" style="height: 60px;" alt=""></td>
								<td>{{$user->phone}}</td>
								<td>{{$user->power}}</td>
								<td class="text-center">
									<a href="{{route('admin.profile', $user->id)}}">{{__('messages.View')}}</a>
									<!-- <button  onclick="setData('{{$user->id}}','{{$user->name}}','{{$user->phone}}','{{$user->email}}','{{$user->power}}','{{$user->image}}','{{$user->bio}}')" data-bs-toggle="modal" data-bs-target="#edit" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">Edit</button> -->
									<!-- <form action="{{route('admin.delete_user')}}" method="post" style="display: inline-block;">
											@csrf 
											<input type="text" name="id" hidden value="{{$user->id}}">
											<button type="submit"  class="btn btn-bg-light btn-color-muted btn-active-color-danger btn-sm px-4">Delete</button>
										</form> -->
								</td>

							</tr>
							@endforeach
						</tbody>
						<!--end::Table body-->
					</table>
					{{ $users->appends(['role' => request('role')])->links('vendor.pagination.custom') }}

					@else
					<div class="text-center">
						There no data to show
					</div>
					@endif
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
				<h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.add_new_user')}}</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" action="{{route('admin.add_user')}}" method="post">
					@csrf
					<div class="row">
						<div class="col">
							<input required name="name" type="text" class="form-control" placeholder="{{__('messages.Name')}}" aria-label="First name">
						</div>
						<div class="col">
							<input required name="phone" type="text" class="form-control" placeholder="{{__('messages.Phone')}}" aria-label="Last name">
						</div>

					</div>
					<div class="row my-2">
						<div class="col">
							<input name="email" type="email" class="form-control " placeholder="{{__('messages.Email')}}" aria-label="Last name">
						</div>
						<div class="col">
							<select required class="form-select" name="power" aria-label="Default select example">
								<option selected disabled>{{__('messages.Permissions')}}</option>
								@foreach($roles as $role)
								<option value="{{ $role->name}}">{{ $role->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="row my-2">
						<div class="col">
							<input required name="password" type="password" class="form-control" placeholder="{{__('messages.Password')}}" aria-label="First name">
						</div>
						<div class="col">
							<input required name="password_confirmation" type="password" class="form-control" placeholder="{{__('messages.Password_confirmation')}}" aria-label="Last name">
						</div>
					</div>
					<div class="row my-2">
						<div class="col">
							<input required name="image" type="file" class="form-control" placeholder="{{__('messages.Image')}}" aria-label="First name">
						</div>
					</div>
					<div class="row my-2">
						<div class="col">
							<div class="form-floating">
								<textarea rows="5" name="bio" class="form-control" placeholder="{{__('messages.Bio')}}" id="floatingTextarea"></textarea>
								<label for="floatingTextarea">{{__('messages.Bio')}}</label>
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
				<h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.edit_user')}}</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" action="{{route('admin.update_user')}}" method="post">
					@csrf
					<input type="text" name="id" class="user_id" hidden>
					<div class="row">
						<div class="col">
							<input required name="name" type="text" class="form-control name" placeholder="{{__('messages.Name')}}" aria-label="First name">
						</div>
						<div class="col">
							<input required name="phone" type="text" class="form-control phone" placeholder="{{__('messages.Phone')}}" aria-label="Last name">
						</div>

					</div>
					<div class="row my-2">
						<div class="col">
							<input name="email" type="email" class="form-control email " placeholder="{{__('messages.Email')}}" aria-label="Last name">
						</div>
						<div class="col">
							<select required class="form-select power" name="power" aria-label="Default select example">
								<option selected disabled>{{__('messages.Permissions')}}</option>
								<option value="admin">{{__('messages.Admin')}}</option>
								<option value="provider">{{__('messages.Provider')}}</option>
								<option value="customer">{{__('messages.Customer')}}</option>
							</select>
						</div>
					</div>
					<div class="row my-2">
						<div class="col">
							<input name="password" type="password" class="form-control" placeholder="{{__('messages.Password')}}" aria-label="First name">
						</div>
						<div class="col">
							<input name="password_confirmation" type="password" class="form-control" placeholder="{{__('messages.Password_confirmation')}}" aria-label="Last name">
						</div>
					</div>
					<div class="row my-2">
						<div class="col">
							<img src="" style="height: 60px;" class="user_image" alt="">
							<input name="image" type="file" class="form-control" placeholder="{{__('messages.Image')}}" aria-label="First name">
						</div>
					</div>
					<div class="row my-2">
						<div class="col">
							<div class="form-floating">
								<textarea rows="5" name="bio" class="form-control bio" placeholder="{{__('messages.Bio')}}" id="floatingTextarea"></textarea>
								<label for="floatingTextarea">{{__('messages.Bio')}}</label>
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
	function setData(id, name, phone, email, power, image, bio) {
		let InputName = document.querySelector(".name")
		let InputPhone = document.querySelector(".phone")
		let InputEmail = document.querySelector(".email")
		let InputID = document.querySelector(".user_id")
		let InputPower = document.querySelector(".power")
		let InputImage = document.querySelector(".user_image")
		let InputBio = document.querySelector(".bio")

		InputID.value = id
		InputName.value = name
		InputPhone.value = phone
		InputEmail.value = email
		InputPower.value = power
		InputImage.src = '/' + image
		InputBio.value = bio
	}
</script>
@endsection