<table class="table align-middle gs-0 gy-4">
    <!--begin::Table head-->
    <thead>
        <tr class="fw-bold text-muted bg-light">
            <th class="ps-4 min-w-300px rounded-start">{{__('messages.Name')}}</th>
            <th class="min-w-125px">{{__('messages.Phone')}}</th>
            <th class="min-w-125px">{{__('messages.Email')}}</th>
            <th class="min-w-125px">{{__('messages.Role')}}</th>

        </tr>
    </thead>
    <!--end::Table head-->
    <!--begin::Table body-->
    <tbody>
        @foreach($providers as $provider)
        <tr>
            <td> <a href="{{route('admin.profile',$provider->id)}}">{{$provider->name}}</a> </td>
            <td>{{$provider->phone}}</td>
            <td>{{$provider->email}}</td>
            <td>{{$provider->power}}</td>
        </tr>
        @endforeach
    </tbody>
    <!--end::Table body-->
</table>