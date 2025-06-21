@extends('admin.app')
@section('title',trans('messages.Deposit_requests'))
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
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Deposit_requests')}}</h1>
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted text-hover-primary">{{__('messages.Settings')}}</a>
					</li>
					<li class="breadcrumb-item">
						<span class="bullet bg-gray-400 w-5px h-2px"></span>
					</li>
					<li class="breadcrumb-item text-muted">{{__('messages.Deposit_requests')}}</li>
				</ul>
			</div>
		</div>
	</div>
	<div id="kt_app_content" class="app-content flex-column-fluid">
		<div id="kt_app_content_container" class="app-container container-xxl">
			<div class="card">
				<div class="card-body p-lg-17">
					<table class="table align-middle table-row-dashed fs-6 gy-5" id="packages-table" style="width: 100%;">
						<thead>
							<tr>
								<th>{{__('messages.Amount')}}</th>
								<th>{{__('messages.User')}}</th>
								<th>{{__('messages.Request_date')}}</th>
								<th>{{__('messages.Status')}}</th>
								<th>{{__('messages.Image')}}</th>
								<th>{{__('messages.Description')}}</th>
								<th>{{__('messages.Actions')}}</th>
							</tr>
						</thead>
						<tbody>
							@foreach($transactions as $transaction)
							<tr>

								<td>{{$transaction->transaction_type=="reservation" ? "-" : "+"}}{{$transaction->amount}}</td>

								<td>
									@if(auth()->user()->power=="admin")
									<a class="deco-none" href="{{route('admin.profile',$transaction->user->id)}}">{{$transaction->user->name}}</a>
									@else 
									{{$transaction->user->name}}
									@endif
								</td>
								<td>{{\Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d') }}
								</td>
								<td>
									@if($transaction->status == 1)
									<label class="badge badge-success"> {{__('messages.Approved')}}</label>
									@elseif($transaction->status==0)
									<label class="badge badge-danger"> {{__('messages.Denied')}}</label>
									@elseif($transaction->status==2)
									<label class="badge badge-info"> {{__('messages.Pending')}}</label>
									@endif
								</td>
								<td><img height="90" src="{{asset($transaction->attachment)}}" alt=""></td>
								<td>
									
								@if($transaction->admin_id >0)
									<a href="{{route('admin.profile',$transaction->admin_id)}}">
										{{session('lang')=="en" ?  $transaction->description:  $transaction->description_ar}}
									</a> 
									@endif
								</td>
								<td>
									<div id='kt_menu_64b77630f13b911{{$transaction->id}}'>

										<form method="POST" action="{{ route('admin.transcations.change_status') }}" id="auto-submit-form-booking{{$transaction->id}}">
											@csrf <!-- Laravel CSRF protection -->
											<input type="hidden" name="transaction_id" value="{{$transaction->id}}">
											<select onchange="document.getElementById('auto-submit-form-booking{{$transaction->id}}').submit();" name="status" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="{{__('messages.Change_status')}}" data-dropdown-parent="#kt_menu_64b77630f13b911{{$transaction->id}}" >
												<option selected disabled>{{__('messages.Change_status')}}</option>
												<option value="0">{{__('messages.Rejected')}}</option>
												<option value="1">{{__('messages.Approved')}}</option>
												<option value="2">{{__('messages.Pending')}}</option>
											</select>
										</form>
									</div>

								</td>
							</tr>
							@endforeach


						</tbody>
					</table>


				</div>
			</div>
		</div>
	</div>

	@endsection