<table class="table align-middle gs-0 gy-4 w-100">
						<!--begin::Table head-->
						<thead>
							<tr class="fw-bold text-muted bg-light">
								<th class="min-w-100px rounded-start">{{__('messages.Customer_name')}}</th>
								<th class="min-w-100px">{{__('messages.Provider_name')}}</th>
								<th class="ps-4 min-w-80px ">{{__('messages.Service_name')}}</th>
								<th class="ps-4 min-w-80px ">{{__('messages.Category_name')}}</th>
								<th class="min-w-50px">{{__('messages.Price')}}</th>
								<th class="min-w-50px">{{__('messages.Booking_status')}}</th>
								<th class="min-w-50px">{{__('messages.Payment_status')}}</th>
								<th class="min-w-100px">{{__('messages.Start_at')}}</th>
								<th class="min-w-100px">{{__('messages.End_at')}}</th>

							</tr>
						</thead>

						<tbody>
							@foreach($bookings as $booking)
							<tr>
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
									@endif
								</td>
								<td>
									@if($booking->payment_status_id ==1)
									<span class="badge badge-light-danger">{{__('messages.Un_paid')}}</span>
									@elseif($booking->payment_status_id ==2)
									<span class="badge badge-light-warning">{{__('messages.Pending')}}</span>
									@elseif($booking->payment_status_id ==3)
									<span class="badge badge-light-success">{{__('messages.Paid')}}</span>
									@endif
								</td>
								<td>{{ \Carbon\Carbon::parse($booking->start_at)->format('Y-m-d')}}</td>
								<td>{{ \Carbon\Carbon::parse($booking->end_at)->format('Y-m-d')}}</td>

							</tr>
							@endforeach
						</tbody>
					</table>