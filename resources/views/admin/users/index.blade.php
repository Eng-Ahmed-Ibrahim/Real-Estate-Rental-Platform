@extends('admin.app')
@section('title', __("messages.$role"))
@section('content')
    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        {{ __("messages.$role") }}</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a class="text-muted text-hover-primary">{{ __('messages.Users') }}</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">{{ __("messages.$role") }}</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    @if (request('role') == 2)
                        <a href="{{ route('admin.export.providers') }}" class="btn btn-sm fw-bold btn-primary">
                            {{ __('messages.Export_excel') }}</a>
                    @endif
                    @can('add new user')
                        <button class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal"
                            data-bs-target="#add">{{ __('messages.Create') }}</button>
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

                            <form id="filterForm" class="col-lg-2 col-md-4  " action="{{ route('admin.users') }}"
                                method="GET">
                                <input type="hidden" name="role" value="{{ request()->query('role') }}">
                                <select name="date" id="dateFilter" class="form-select w-100"
                                    onchange="this.form.submit()">
                                    <option value="all" {{ !request()->query('date') ? 'selected' : ' ' }}>
                                        {{ __('messages.All') }}</option>
                                    <option value="daily" {{ request()->query('date') == 'daily' ? 'selected' : ' ' }}>
                                        {{ __('messages.Daily') }}</option>
                                    <option value="weekly" {{ request()->query('date') == 'weekly' ? 'selected' : ' ' }}>
                                        {{ __('messages.Weekly') }}</option>
                                    <option value="monthly" {{ request()->query('date') == 'monthly' ? 'selected' : ' ' }}>
                                        {{ __('messages.Monthly') }}</option>
                                </select>
                            </form>
                            <form action="{{ route('admin.users') }}" method="GET" class=" col-lg-4 col-md-5  "
                                style="display: flex;align-items: center;justify-content: center;">
                                <input type="hidden" name="role" value="{{ request()->query('role') }}">

                                <div style="width: 80%;">
                                    <input type="text" class="form-control " value="{{ request('search') }}"
                                        style="border-radius: 5px 0 0 5px;" name="search"
                                        placeholder="{{ __('messages.Search') }}">
                                </div>

                                <button type="submit" class="btn  btn-primary h-100"
                                    style="border-radius: 0 5px 5px 0;width:20%;display: flex;align-items: center;justify-content: center;"><i
                                        class="fa-solid fa-magnifying-glass"></i></button>
                            </form>

                        </div>
                        @if (count($users) > 0)
                            <table class="table align-middle gs-0 gy-4">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="ps-4 min-w-300px rounded-start">{{ __('messages.Name') }}</th>
                                        <th class="min-w-125px">{{ __('messages.Image') }}</th>
                                        <th class="min-w-125px">{{ __('messages.Phone') }}</th>
                                        <th class="min-w-125px">{{ __('messages.Role') }}</th>
                                        <th class="min-w-125px">{{ __('messages.Actions') }}</th>

                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td> <a href="{{ route('admin.profile', $user->id) }}">{{ $user->name }}</a>
                                            </td>
                                            <td><img src="/{{ $user->image }}" style="height: 60px;" alt=""></td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->power == "provider"  ? "Owner" : $user->power }}</td>
                                            <td class="text-center">
                                                <a
                                                    href="{{ route('admin.profile', $user->id) }}">{{ __('messages.View') }}</a>
                                                <!-- <button  onclick="setData('{{ $user->id }}','{{ $user->name }}','{{ $user->phone }}','{{ $user->email }}','{{ $user->power }}','{{ $user->image }}','{{ $user->bio }}')" data-bs-toggle="modal" data-bs-target="#edit" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">Edit</button> -->
                                                <!-- <form action="{{ route('admin.delete_user') }}" method="post" style="display: inline-block;">
                   @csrf
                   <input type="text" name="id" hidden value="{{ $user->id }}">
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.add_new_user') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.add_user') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col mb-2">
                                <input required name="name" value="{{ old('name') }}" type="text" class="form-control"
                                    placeholder="{{ __('messages.Name') }}" aria-label="First name">
                            </div>
                            <div class="col-12 mb-2 " style="display: flex;gap:10px">
                                <select id="country_code" name="country_code" style="width:140px" class="form-select">
                                    <option  disabled>{{ __('messages.Choose_country') }}</option>
                                    <option value="1">🇺🇸 United States (+1)</option>
                                    <option value="44">🇬🇧 United Kingdom (+44)</option>
                                    <option value="20" selected>🇪🇬 Egypt (+20)</option>
                                    <option value="971">🇦🇪 United Arab Emirates (+971)</option>
                                    <option value="966">🇸🇦 Saudi Arabia (+966)</option>
                                    <option value="974">🇶🇦 Qatar (+974)</option>
                                    <option value="965">🇰🇼 Kuwait (+965)</option>
                                    <option value="973">🇧🇭 Bahrain (+973)</option>
                                    <option value="968">🇴🇲 Oman (+968)</option>
                                    <option value="961">🇱🇧 Lebanon (+961)</option>
                                    <option value="962">🇯🇴 Jordan (+962)</option>
                                    <option value="963">🇸🇾 Syria (+963)</option>
                                    <option value="964">🇮🇶 Iraq (+964)</option>
                                    <option value="212">🇲🇦 Morocco (+212)</option>
                                    <option value="213">🇩🇿 Algeria (+213)</option>
                                    <option value="216">🇹🇳 Tunisia (+216)</option>
                                    <option value="218">🇱🇾 Libya (+218)</option>
                                    <option value="249">🇸🇩 Sudan (+249)</option>
                                    <option value="7">🇷🇺 Russia (+7)</option>
                                    <option value="86">🇨🇳 China (+86)</option>
                                    <option value="91">🇮🇳 India (+91)</option>
                                    <option value="92">🇵🇰 Pakistan (+92)</option>
                                    <option value="880">🇧🇩 Bangladesh (+880)</option>
                                    <option value="81">🇯🇵 Japan (+81)</option>
                                    <option value="82">🇰🇷 South Korea (+82)</option>
                                    <option value="90">🇹🇷 Turkey (+90)</option>
                                    <option value="39">🇮🇹 Italy (+39)</option>
                                    <option value="33">🇫🇷 France (+33)</option>
                                    <option value="34">🇪🇸 Spain (+34)</option>
                                    <option value="49">🇩🇪 Germany (+49)</option>
                                    <option value="43">🇦🇹 Austria (+43)</option>
                                    <option value="41">🇨🇭 Switzerland (+41)</option>
                                    <option value="31">🇳🇱 Netherlands (+31)</option>
                                    <option value="32">🇧🇪 Belgium (+32)</option>
                                    <option value="46">🇸🇪 Sweden (+46)</option>
                                    <option value="45">🇩🇰 Denmark (+45)</option>
                                    <option value="47">🇳🇴 Norway (+47)</option>
                                    <option value="48">🇵🇱 Poland (+48)</option>
                                    <option value="380">🇺🇦 Ukraine (+380)</option>
                                    <option value="357">🇨🇾 Cyprus (+357)</option>
                                    <option value="61">🇦🇺 Australia (+61)</option>
                                    <option value="64">🇳🇿 New Zealand (+64)</option>
                                    <option value="1">🇨🇦 Canada (+1)</option>
                                    <option value="52">🇲🇽 Mexico (+52)</option>
                                    <option value="55">🇧🇷 Brazil (+55)</option>
                                    <option value="54">🇦🇷 Argentina (+54)</option>
                                    <option value="51">🇵🇪 Peru (+51)</option>
                                    <option value="56">🇨🇱 Chile (+56)</option>
                                    <option value="63">🇵🇭 Philippines (+63)</option>
                                    <option value="60">🇲🇾 Malaysia (+60)</option>
                                    <option value="65">🇸🇬 Singapore (+65)</option>
                                    <option value="66">🇹🇭 Thailand (+66)</option>
                                    <option value="84">🇻🇳 Vietnam (+84)</option>
                                    <option value="62">🇮🇩 Indonesia (+62)</option>
                                    <option value="94">🇱🇰 Sri Lanka (+94)</option>
                                    <option value="880">🇧🇩 Bangladesh (+880)</option>
                                    <option value="254">🇰🇪 Kenya (+254)</option>
                                    <option value="27">🇿🇦 South Africa (+27)</option>
                                    <option value="234">🇳🇬 Nigeria (+234)</option>
                                    <option value="221">🇸🇳 Senegal (+221)</option>
                                    <option value="225">🇨🇮 Côte d'Ivoire (+225)</option>
                                    <option value="256">🇺🇬 Uganda (+256)</option>
                                    <option value="255">🇹🇿 Tanzania (+255)</option>
                                </select>
                                <input required name="phone" id="add-form-phone" type="text" class="form-control"
                                    value="{{ old('phone') }}" placeholder="{{ __('messages.Phone') }}" aria-label="Last name" maxlength="15" pattern="^[1-9][0-9]{0,11}$">
                            </div>

                        </div>
                        <div class="row my-2">
                            <div class="col  mb-2">
                                <input name="email" type="email" class="form-control "
                                    placeholder="{{ __('messages.Email') }}" value="{{ old('email') }}" aria-label="Last name">
                            </div>
                            <div class="col mb-2">
                                @if(in_array(request('role'), ['provider', 'admin', 'customer']))

                                <input type="hidden" name="power" value="{{ request('role') }}">
                                @else 
                                <select required class="form-select" name="power" aria-label="Default select example">
                                    <option selected disabled>{{ __('messages.Permissions') }}</option>
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('power') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col mb-2">
                                <input required name="password" type="password" class="form-control"
                                    placeholder="{{ __('messages.Password') }}" aria-label="First name">
                            </div>
                            <div class="col mb-2">
                                <input required name="password_confirmation" type="password" class="form-control"
                                    placeholder="{{ __('messages.Password_confirmation') }}" aria-label="Last name">
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col mb-2">
                                <input required name="image" type="file" class="form-control"
                                    placeholder="{{ __('messages.Image') }}" aria-label="First name">
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col mb-2">
                                <div class="form-floating">
                                    <textarea rows="5" name="bio" class="form-control" placeholder="{{ __('messages.Bio') }}"
                                        id="floatingTextarea"></textarea>
                                    <label for="floatingTextarea">{{ __('messages.Bio') }}</label>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.Save_changes') }}</button>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.edit_user') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.update_user') }}" method="post">
                        @csrf
                        <input type="text" name="id" class="user_id" hidden>
                        <div class="row">
                            <div class="col">
                                <input required name="name" type="text" class="form-control name"
                                    placeholder="{{ __('messages.Name') }}" aria-label="First name">
                            </div>
                            <div class="col">


                                <input required name="phone" type="text" class="form-control phone"
                                    placeholder="{{ __('messages.Phone') }}" aria-label="Last name">
                            </div>

                        </div>
                        <div class="row my-2">
                            <div class="col">
                                <input name="email" type="email" class="form-control email "
                                    placeholder="{{ __('messages.Email') }}" aria-label="Last name">
                            </div>
                            <div class="col">
                                <select required class="form-select power" name="power"
                                    aria-label="Default select example">
                                    <option selected disabled>{{ __('messages.Permissions') }}</option>
                                    <option value="admin">{{ __('messages.Admin') }}</option>
                                    <option value="provider">{{ __('messages.Provider') }}</option>
                                    <option value="customer">{{ __('messages.Customer') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col">
                                <input name="password" type="password" class="form-control"
                                    placeholder="{{ __('messages.Password') }}" aria-label="First name">
                            </div>
                            <div class="col">
                                <input name="password_confirmation" type="password" class="form-control"
                                    placeholder="{{ __('messages.Password_confirmation') }}" aria-label="Last name">
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col">
                                <img src="" style="height: 60px;" class="user_image" alt="">
                                <input name="image" type="file" class="form-control"
                                    placeholder="{{ __('messages.Image') }}" aria-label="First name">
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col">
                                <div class="form-floating">
                                    <textarea rows="5" name="bio" class="form-control bio" placeholder="{{ __('messages.Bio') }}"
                                        id="floatingTextarea"></textarea>
                                    <label for="floatingTextarea">{{ __('messages.Bio') }}</label>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.Save_changes') }}</button>
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
    <script>
 

        document.getElementById('add-form-phone').addEventListener('input', function () {
    // Remove any non-digit characters
    this.value = this.value.replace(/\D/g, '');

    // Remove leading zeros
    if (this.value.length > 0 && this.value[0] === '0') {
        this.value = this.value.slice(1);
    }

    // Limit to 12 digits
    if (this.value.length > 12) {
        this.value = this.value.slice(0, 12);
    }
});

    </script>

@endsection
