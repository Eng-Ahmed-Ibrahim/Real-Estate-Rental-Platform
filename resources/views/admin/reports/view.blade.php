@extends('admin.app')
@section('title',trans('messages.Invoice_details'))
@section('css')
<style>
    /* Style the Image Used to Trigger the Modal */
    #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    #myImg:hover {
        opacity: 0.7;
    }

    /* The Modal (background) */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 9999;
        /* Sit on top */
        padding-top: 100px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.9);
        /* Black w/ opacity */
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
    .modal-content,
    #caption {
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @-webkit-keyframes zoom {
        from {
            -webkit-transform: scale(0)
        }

        to {
            -webkit-transform: scale(1)
        }
    }

    @keyframes zoom {
        from {
            transform: scale(0)
        }

        to {
            transform: scale(1)
        }
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
    @media only screen and (max-width: 700px) {
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
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Invoice_details')}}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted text-hover-primary">{{__('messages.Pages')}}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{__('messages.Invoice_details')}}</li>
                </ul>
            </div>
			<div class="d-flex align-items-center gap-2 gap-lg-3">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{__('messages.Actions')}}
                </button>
                <ul class="dropdown-menu">
                    <li><a target="_blank" href="{{route('admin.payment_reports.download_pdf',$payment->invoice_id)}}" class="dropdown-item" href="#">{{__('messages.Download_invoice')}}</a></li>
                    <li><a target="_blank" href="{{route('admin.payment_reports.perview_pdf',$payment->invoice_id)}}" class="dropdown-item" href="#">{{__('messages.View_invoice')}}</a></li>
                </ul>
                </div>
			</div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">


            <div class='d-flex flex-column flex-xl-row gap-7 gap-lg-10'>

                <div class="card card-flush py-4 flex-row-fluid">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>{{__('messages.Invoice_details')}} (#{{$payment->invoice_id}})</h2>
                        </div>
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                <tbody class="fw-semibold text-gray-600">
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-calendar fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                                                {{__('messages.Customer_name')}}
                                            </div>
                                        </td>
                                        <td class="fw-bold text-end"><a href="{{route('admin.profile',$payment->provider->id)}}">{{ $payment->customer->name}}</a> </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-calendar fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                                                {{__('messages.Date_Added')}}
                                            </div>
                                        </td>
                                        <td class="fw-bold text-end"> {{ \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d')}}</td>
                                    </tr>
                                    <tr>

                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-wallet fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                {{__('messages.Payment_method')}}
                                            </div>
                                        </td>
                                        @if($payment->payment_method)
                                        <td class="fw-bold text-end">
                                            {{ session('lang')=='en'? $payment->payment_method->name :  $payment->payment_method->name_ar }}
                                            <img src="/{{$payment->payment_method->image}}" class="w-50px ms-2">
                                        </td>
                                        @else 
                                            <td class="fw-bold text-end">
                                                {{__("messages.$payment->payment_type")}}
                                            </td>
                                        @endif

                                    </tr>
                                </tbody>
                            </table>
                            <!--end::Table-->
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <div class="card card-flush py-4 flex-row-fluid">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>{{__('messages.Payment_Attachment')}}</h2>
                        </div>
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        @if($payment->attachment)
                        <img onclick="ImageModal('{{$payment->attachment}}')" style="height: 300px;cursor: pointer;" src="/{{$payment->attachment}}" alt="">
                        @else
                        <h3 style="    height: 100%;width: 100%;color: white;display: flex;justify-content: center;align-items: center;background: gray;">
                            {{__('messages.Not_uploaded_image_yet')}}
                        </h3>
                        @endif
                    </div>
                    <!--end::Card body-->
                </div>
            </div>

        </div>
    </div>
</div>

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

    function ImageModal(image) {

        var modalImg = document.getElementById("img01");

        // Get the image and insert it inside the modal - use its "alt" text as a caption

        var captionText = document.getElementById("caption");

        modal.style.display = "block";
        modalImg.src = '/' + image;




    }

    function CloseModal() {

        modal.style.display = "none";
    }
</script>
@endsection