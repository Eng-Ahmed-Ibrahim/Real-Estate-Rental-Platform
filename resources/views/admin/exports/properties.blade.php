<table class="table align-middle gs-0 gy-4">
    <thead>
        <tr class="fw-bold text-muted bg-light">
            <th class="ps-4 min-w-150px rounded-start">{{__('messages.Name')}}</th>
            <th class="ps-4 min-w-150px rounded-start">{{__('messages.Provider_name')}}</th>
            <th class="ps-4 min-w-100px rounded-start">{{__('messages.Category_name')}}</th>
            <th class="min-w-100px">{{__('messages.Amount')}}</th>
            <th class="min-w-150px" style="font-size: 12px;">{{__('messages.Amount_after_commission')}}</th>
            <th class="min-w-100x">{{__('messages.Accept')}}</th>

        </tr>
    </thead>
    <tbody>
        @foreach($services as $service)
        <tr>
            <td>
                <a  style="text-align: left;" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ session('lang') == 'en' ? $service->name : $service->name_ar}}</a>
            </td>
            <td>
                <a  style="text-align: left;" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ $service->user->name}}</a>
            </td>
            <td>
                <a   style="text-align: left;" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ session('lang') == 'en' ? $service->category->brand_name : $service->category->brand_name_ar }}</a>
            </td>
            <td  style="text-align: left;">{{$service->price }}</td>
            <td  style="text-align: left;">{{$service->price + $service->commission_money	}}</td>
            <td  style="text-align: left;">
                @if($service->accept ==1)
                    {{__('messages.Yes')}}
                @else
                    {{__('messages.No')}}
                @endif

            </td>

        </tr>
        @endforeach
    </tbody>
</table>