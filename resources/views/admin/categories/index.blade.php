@extends('admin.app')
@section('title', __('messages.categories'))
@section('content')
    <div class="d-flex flex-column flex-column-fluid">


        <div class="container">


        </div>
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        {{ __('messages.categories') }}</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a class="text-muted text-hover-primary">{{ __('messages.categories') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">{{ __('messages.Pages') }}</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">

                    @can('add category')
                        <button class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal"
                            data-bs-target="#add">{{ __('messages.Create') }}</button>
                    @endcan
                </div>
            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body p-lg-17">
                        <div class="w-100" style="overflow-x: scroll;">

                            <table class="table align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold  bg-light">
                                        <th class="ps-4 min-w-150px rounded-start">{{ __('messages.Name') }}</th>
                                        <th class="min-w-125px">{{ __('messages.Image') }}</th>
                                        <th class="min-w-125px text-center">{{ __('messages.Actions') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>
                                                {{ session('lang') == 'en' ? $category->brand_name : $category->brand_name_ar }}
                                            </td>
                                            <td>
                                                <img src="/{{ $category->image }}" style="width:60px;" alt="">

                                            </td>
                                            <td class="text-center">
                                                @can('edit category')
                                                    <button
                                                        onclick="setData('{{ $category->id }}','{{ $category->brand_name }}','{{ $category->brand_name_ar }}','{{ $category->image }}')"
                                                        data-bs-toggle="modal" data-bs-target="#edit"
                                                        class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">{{ __('messages.Edit') }}</button>
                                                @endcan
                                                @can('delete category')
                                                    <form action="{{ route('admin.delete_category') }}" method="post"
                                                        style="display: inline-block;">
                                                        @csrf
                                                        <input type="text" name="id" hidden
                                                            value="{{ $category->id }}">
                                                        <button type="submit"
                                                            class="btn btn-bg-light btn-color-muted btn-active-color-danger btn-sm px-4">{{ __('messages.Delete') }}</button>
                                                    </form>
                                                @endcan
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $categories->links('vendor.pagination.custom') }}


                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.add_new_category') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.add_category') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <input required name="name_en" type="text" class="form-control"
                                    placeholder="{{ __('messages.Name_en') }}" aria-label="First name">
                            </div>
                            <div class="col">
                                <input required name="name_ar" type="text" class="form-control"
                                    placeholder="{{ __('messages.Name_ar') }}" aria-label="Last name">
                            </div>

                        </div>
                        <div class="row my-2">
                            <div class="col">
                                <input required name="image" type="file" class="form-control"
                                    placeholder="{{ __('messages.image') }}">
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
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.edit_category') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.update_category') }}" method="post">
                        @csrf
                        <input type="text" name="id" class="category_id" hidden>
                        <div class="row">
                            <div class="col">
                                <input required name="name_en" type="text" class="form-control name_en"
                                    placeholder="{{ __('messages.Name_en') }}" aria-label="First name">
                            </div>
                            <div class="col">
                                <input required name="name_ar" type="text" class="form-control name_ar"
                                    placeholder="{{ __('messages.Name_ar') }}" aria-label="Last name">
                            </div>

                        </div>
                        <div class="row my-2">
                            <div class="col">
                                <div>
                                    <img src="" id="image-preview" style="height: 70px ; width:70px"
                                        alt="">
                                </div>
                                <label for="EditImage">{{ __('messages.Image') }} (60*60)</label>
                                <input name="image" id="EditImage" type="file" class="form-control  "
                                    placeholder="{{ __('messages.image') }}">
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
        function setData(id, name_en, name_ar, image) {
            let InputNameEn = document.querySelector(".name_en")
            let InputNameAr = document.querySelector(".name_ar")
            let InputImage = document.querySelector("#image-preview")
            let InputID = document.querySelector(".category_id")
            InputID.value = id
            InputNameAr.value = name_ar
            InputNameEn.value = name_en
            InputImage.src = '/' + image
        }
    </script>
    <script>
        document.getElementById('EditImage').addEventListener('change', function(event) {
            const input = event.target;
            const preview = document.getElementById('image-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
    </script>
@endsection
