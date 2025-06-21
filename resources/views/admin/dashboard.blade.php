@extends('admin.app')
@section('title', trans('messages.Dashboard'))
@section('css')
    <style>
        /* canvas {
          width: 100% !important;
          height: auto !important;
         } */


        main {
            width: 100%;
            min-height: 300px;
            /* background-color: #ffffff; */
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 0.5rem;

        }

        #header {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 2.5rem 2rem;
        }

        .share {
            width: 4.5rem;
            height: 3rem;
            /* background-color: #f55e77; */
            border: 0;
            border-bottom: 0.2rem solid #c0506a;
            border-radius: 2rem;
            cursor: pointer;
        }

        .share:active {
            border-bottom: 0;
        }

        .share i {
            /* color: #fff; */
            font-size: 2rem;
        }

        h1 {
            font-family: 'Bahij', sans-serif !important;
            /* Fallback to sans-serif if Bahij doesn't load */
            font-size: 1.7rem;
            /* color: #141a39; */
            text-transform: uppercase;
            cursor: default;
        }

        #leaderboard {
            width: 100%;
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            /* color: #141a39; */
            cursor: default;
        }

        tr {
            transition: all 0.2s ease-in-out;
            border-radius: 0.2rem;
        }

        tr:not(:first-child):hover {
            /* background-color: #fff; */
            transform: scale(1.1);
        }

        tr:nth-child(odd) {
            /* background-color: #f9f9f9; */
        }

        tr:nth-child(1) {
            color: #fff;
        }

        td {
            height: 5rem;
            font-family: 'Bahij', sans-serif !important;
            /* Fallback to sans-serif if Bahij doesn't load */
            font-size: 1.4rem;
            padding: 1rem 2rem;
            position: relative;
        }

        .number {
            width: 1rem;
            font-size: 2.2rem;
            font-weight: bold;
            text-align: left;
        }

        .name {
            text-align: left;
            font-size: 1.2rem;
        }

        .points {
            font-weight: bold;
            font-size: 1.3rem;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .points:first-child {
            width: 10rem;
            font-family: 'Bahij', sans-serif !important;
            /* Fallback to sans-serif if Bahij doesn't load */

        }

        .gold-medal {
            height: 3rem;
            margin-left: 1.5rem;
        }

        .ribbon {
            width: 100%;
            height: 5.5rem;
            background-color: #5c5be5;
            position: absolute;
            left: -1rem;
        }

        .ribbon::before {
            content: "";
            height: 1.5rem;
            width: 1.5rem;
            bottom: -0.8rem;
            left: 0.35rem;
            transform: rotate(45deg);
            background-color: #5c5be5;
            position: absolute;
            z-index: -1;
        }

        .ribbon::after {
            content: "";
            height: 1.5rem;
            width: 1.5rem;
            bottom: -0.8rem;
            right: 0.35rem;
            transform: rotate(45deg);
            background-color: #5c5be5;
            position: absolute;
            z-index: -1;
        }

        #buttons {
            width: 100%;
            margin-top: 3rem;
            display: flex;
            justify-content: center;
            gap: 2rem;
        }

        .exit {
            width: 11rem;
            height: 3rem;
            font-family: "Rubik", sans-serif;
            font-size: 1.3rem;
            text-transform: uppercase;
            color: #7e7f86;
            border: 0;
            background-color: #fff;
            border-radius: 2rem;
            cursor: pointer;
        }

        .exit:hover {
            border: 0.1rem solid #5c5be5;
        }

        .continue {
            width: 11rem;
            height: 3rem;
            font-family: "Rubik", sans-serif;
            font-size: 1.3rem;
            text-transform: uppercase;
            background-color: #5c5be5;
            border: 0;
            border-bottom: 0.2rem solid #3838b8;
            border-radius: 2rem;
            cursor: pointer;
        }

        .continue:active {
            border-bottom: 0;
        }

        .total {
            font-size: 25px;
            font-weight: bold;
        }

        .currency {
            font-size: 12px;
            position: relative;
            top: -8px;
            left: -2px;
        }

        tr {
            transition: all 0.2s ease-in-out;
        }

        tr:hover {
            transform: scale(1.1);
        }
    </style>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500&display=swap" rel="stylesheet" />
@endsection
@section('content')


    <div class="d-flex flex-column flex-column-fluid">

        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        {{ __('messages.Dashboard') }}</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a class="text-muted text-hover-primary">{{ __('messages.Home') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">{{ __('messages.Dashboard') }}</li>
                    </ul>
                </div>

            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl ">

                <div class="row gy-5 gx-xl-10 ">




                    <div class="col-lg-3 col-md-3 col-sm-4 ">

                        <!--begin::Card widget 2-->
                        <div class="card h-lg-80">
                            <!--begin::Body-->
                            <div class="card-body d-flex justify-content-between align-items-start text-center flex-column">
                                <!--begin::Icon-->
                                <div class="m-0 text-center w-100">
                                    <i class="ki-duotone ki-chart-simple fs-2hx text-gray-600"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span></i>

                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <a 
                                    class="d-flex flex-column my-7 w-100">
                                    <!--begin::Number-->
                                    <span class="fw-semibold fs-3x text-gray-800  text-center lh-1 ls-n2"
                                        style="font-family: 'Bahij', sans-serif !important;">{{ $total_customers }}</span>
                                    <!--end::Number-->

                                    <!--begin::Follower-->
                                    <div class="m-0  " style="text-align: left;">
                                        <span class="fw-semibold fs-6 text-gray-500"
                                            style="text-align: left;font-size:12px;font-family: 'Bahij', sans-serif !important;">
                                            {{ __("messages.Total_customers") }}</span>

                                    </div>
                                    <!--end::Follower-->
                                </a>

                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->


                    </div>
                    <div class="col-12"></div>
                    <!-- count of bookings -->
                    @can('show_booking_requests')
                        @foreach ($count_each_booking_section as $section => $count)
                            <div class="col-lg-3 col-md-3 col-sm-4 " onclick="window.href='/admin/services?accept=0'">

                                <!--begin::Card widget 2-->
                                <div class="card h-lg-80">
                                    <!--begin::Body-->
                                    <div
                                        class="card-body d-flex justify-content-between align-items-start text-center flex-column">
                                        <!--begin::Icon-->
                                        <div class="m-0 text-center w-100">
                                            <i class="ki-duotone ki-chart-simple fs-2hx text-gray-600"><span
                                                    class="path1"></span><span class="path2"></span><span
                                                    class="path3"></span><span class="path4"></span></i>

                                        </div>
                                        <!--end::Icon-->

                                        <!--begin::Section-->
                                        <a href="/admin/booking?booking_status={{ $count['status'] }}"
                                            class="d-flex flex-column my-7 w-100">
                                            <!--begin::Number-->
                                            <span class="fw-semibold fs-3x text-gray-800  text-center lh-1 ls-n2"
                                                style="font-family: 'Bahij', sans-serif !important;">{{ $count['section'] }}</span>
                                            <!--end::Number-->

                                            <!--begin::Follower-->
                                            <div class="m-0  " style="text-align: left;">
                                                <span class="fw-semibold fs-6 text-gray-500"
                                                    style="text-align: left;font-size:12px;font-family: 'Bahij', sans-serif !important;">
                                                    {{ __("messages.$section") }}</span>

                                            </div>
                                            <!--end::Follower-->
                                        </a>

                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Card widget 2-->


                            </div>
                        @endforeach
                    @endcan
                    <div class="col-12"></div>

                    <!-- top providers -->
                    @can('show_top_booked_properties')
                        <div class="col-lg-6 col-md-12">
                            <main class="card mb-3">
                                <div id="header">
                                    <h1 style="    font-weight: 400;">{{ __('messages.top_5_provider') }} </h1>
                                </div>
                                <div class="card-body" id="leaderboard">
                                    @if (count($Top_provider_names) > 0)
                                        <div class="ribbon"></div>
                                    @endif
                                    <table>
                                        @foreach ($Top_provider_names as $key => $provider)
                                            <tr>
                                                <td class="number" style="padding:0;">{{ $key + 1 }}</td>

                                                <td class="name text-gray-800 " style="padding:0;">
                                                    <a href="{{ route('admin.profile', $provider['id']) }}"
                                                        style="display: flex; align-items:center;padding:0">
                                                        <img src="{{ $provider['image'] }}"
                                                            style="border-radius: 24%;width: 35px;height: 35px;margin: 0 10px;"
                                                            alt="">
                                                        <div class="text-gray-800 ">
                                                            <div>{{ $provider['name'] }}</div>
                                                            <div>{{ $provider['phone'] }}</div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="points"
                                                    style="padding: 0;padding: 0;position: absolute; font-size:13px">
                                                    {{ $provider['total_earning'] }}{{ __('messages.EGP') }}
                                                    @if ($key == 0)
                                                        <img class="gold-medal" src="{{ asset('static/gold-medal.png') }}"
                                                            alt="gold medal" />
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach


                                    </table>

                                </div>
                            </main>
                            <main class="card">
                                <div id="header">
                                    <h1 style="    font-weight: 400;">{{ __('messages.top_5_customers') }}</h1>
                                </div>
                                <div id="leaderboard" class="card-body">
                                    <table>

                                        <tr>

                                            <td class="name text-right text-gray-800 ">
                                                {{ __('messages.Customer') }}
                                            </td>
                                            <td class="points text-center text-gray-800 ">
                                                {{ __('messages.Total_Booked') }}
                                            </td>
                                        </tr>
                                        @foreach ($topCustomers as $key => $customer)
                                            <tr>

                                                <td class="name text-center text-gray-800 ">
                                                    <a href="{{ route('admin.profile', $customer['customer']['id']) }}"
                                                        class="text-gray-800 " style="display: flex; align-items:center">
                                                        <img src="{{ $customer['customer']['image'] }}"
                                                            style="border-radius: 24%;width: 35px;height: 35px;margin: 0 10px 0 0;"
                                                            alt="">
                                                        <div class="mx-2">
                                                            <div>{{ $customer['customer']['name'] }}</div>
                                                            <div>{{ $customer['customer']['phone'] }}</div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="points text-center text-gray-800 ">
                                                    {{ $customer['total_booked'] }}
                                                </td>
                                            </tr>
                                        @endforeach


                                    </table>

                                </div>
                            </main>
                        </div>
                    @endcan

                    <!-- top properties -->
                    @can('show_top_properties')

                        <div class="col-lg-6 col-md-12">
                            <main class="card">
                                <div id="header">
                                    <h1 style="    font-weight: 400;">{{ __('messages.top_5_properties') }}</h1>
                                </div>
                                <div id="leaderboard" class="card-body">
                                    <table>

                                        <tr>

                                            <td class="name text-right text-gray-800 ">
                                                {{ __('messages.Property') }}
                                            </td>
                                            <td class="points text-center text-gray-800 ">
                                                {{ __('messages.Total_Booked') }}
                                            </td>
                                        </tr>
                                        @foreach ($topServices as $key => $service)
                                            <tr>

                                                <td class="name text-center text-gray-800 ">
                                                    <a href="{{ route('admin.services.show', $service['id']) }}"
                                                        class="text-gray-800 " style="display: flex; align-items:center">
                                                        <img src="{{ $service['image'] }}"
                                                            style="border-radius: 24%;width: 35px;height: 35px;margin: 0 10px 0 0;"
                                                            alt="">
                                                        <div class="mx-2">
                                                            <div>{{ $service['name'] }}</div>
                                                            <div>{{ $service['category'] }}</div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="points text-center text-gray-800 ">
                                                    {{ $service['booking_count'] }}
                                                </td>
                                            </tr>
                                        @endforeach


                                    </table>

                                </div>
                            </main>
                        </div>
                    @endcan

                    <!-- Admin and provider earning -->
                    @can('show_earning')
                        <div class="col-12 ">
                            <div class=" my-2">
                                <div class="card-body">
                                    <h2><span id="earningText">
                                            {{ session('lang') == 'en' ? 'Earning for' : 'الأرباح لـ' }}
                                            <span id="currentMonth">{{ __("messages.$currentMonth") }}</span>
                                        </span>
                                    </h2>
                                </div>
                            </div>
                            <div style="display: flex; ">

                                <div class="card">

                                    <div id="adminTotal" class="card-body" style="    padding: 10px;margin-right: 20px; ">
                                        <div> <span class="currency">{{ __('messages.EGP') }}</span> <span
                                                class="total">{{ $currentMonthEarnings['admin'] }}</span> </div>
                                        <div>{{ __('messages.Admin_Earning') }}</div>
                                    </div>
                                </div>
                                <div class="card mx-2">
                                    <div id="providerTotal" class="card-body" style="    padding: 10px;margin-right: 10px; ">
                                        <div> <span class="currency">{{ __('messages.EGP') }}</span> <span
                                                class="total">{{ $currentMonthEarnings['provider'] }}</span> </div>
                                        <div>{{ __('messages.Provider_Earning') }}</div>
                                    </div>
                                </div>
                            </div>
                            <canvas id="earningsChart"></canvas>

                        </div>
                    @endcan

                    <!-- registered customers -->
                    @can('show_registered_customers')
                        <div class="col-lg-6 col-md-12 ">
                            <canvas id="usersByMonthChart"></canvas>
                        </div>
                    @endcan

                    <!-- total booking requests -->
                    @can('show_booking_requests')
                        <div class="col-lg-6 col-md-12 ">
                            <canvas id="bookingChart"></canvas>
                        </div>
                    @endcan

                </div>
            </div>

        @endsection

        @section('js')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.1.0"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {


                    var theme = document.documentElement.getAttribute('data-bs-theme');
                    if (theme == "light") {
                        tickColor = "rgba(91, 92, 229, 0.8)";
                        gridColor = "rgba(91, 92, 229, 0.2) ";
                    } else {
                        tickColor = "rgba(91, 92, 229, 0.8)";
                        gridColor = "rgba(91, 92, 229, 0.2) ";
                    }




                    var ctx = document.getElementById('usersByMonthChart').getContext('2d');
                    var usersByMonthChart = new Chart(ctx, {
                        type: 'line', // You can change this to 'line', 'pie', etc.
                        data: {
                            labels: @json($labels),
                            datasets: [{
                                label: '{{ __('messages.Registered_Customers') }}',
                                data: @json($counts),
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 2, // Increase border width for emphasis
                                pointRadius: 0, // Hide dots by default
                                pointHoverRadius: 6, // Show dots on hover
                                pointHitRadius: 10, // Increase hit area for easier hovering
                                tension: 0.4 // Add some curvature to the line
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    min: 0,
                                    ticks: {
                                        stepSize: 1,
                                        color: tickColor // White y-axis labels
                                    },
                                    grid: {
                                        color: gridColor // White grid lines for y-axis
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: tickColor // White x-axis labels
                                    },
                                    grid: {
                                        color: gridColor // White grid lines for x-axis
                                    }
                                }



                            },
                            hover: {
                                mode: 'nearest', // This determines the hover mode
                                intersect: false, // Ensure hover only happens when hovering over the actual point
                                animationDuration: 0 // Disable hover animation to stop vibration
                            }
                        }
                    });

                    var ctxBooking = document.getElementById('bookingChart').getContext('2d');
                    var bookingChart = new Chart(ctxBooking, {
                        type: 'bar',
                        data: {
                            labels: @json($labels),
                            datasets: [{
                                label: '{{ __('messages.Total_Booking_Requests') }}',
                                data: @json($bookingCounts),
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                borderColor: 'rgba(153, 102, 255, 1)',
                                borderWidth: 2, // Increase border width for emphasis
                                pointRadius: 0, // Hide dots by default
                                pointHoverRadius: 6, // Show dots on hover
                                pointHitRadius: 10, // Increase hit area for easier hovering
                                tension: 0.4 // Add some curvature to the line
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    min: 0,
                                    ticks: {
                                        stepSize: 1,
                                        color: tickColor // White y-axis labels
                                    },
                                    grid: {
                                        color: gridColor // White grid lines for y-axis
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: tickColor // White x-axis labels
                                    },
                                    grid: {
                                        color: gridColor // White grid lines for x-axis
                                    }
                                }
                            }
                        }
                    });
                });
            </script>


            <script>
                document.addEventListener("DOMContentLoaded", function() {

                    var theme = document.documentElement.getAttribute('data-bs-theme');
                    if (theme == "light") {
                        tickColor = "rgba(91, 92, 229, 0.8)";
                        gridColor = "rgba(91, 92, 229, 0.2) ";
                    } else {
                        tickColor = "rgba(91, 92, 229, 0.8)";
                        gridColor = "rgba(91, 92, 229, 0.2) ";
                    }
                    var currentMonthIndex = new Date().getMonth(); // Get the index of the current month (0-11)
                    console.log(currentMonthIndex);

                    var earningsData = @json($earnings);
                    var ctx = document.getElementById('earningsChart').getContext('2d');
                    var earningsChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: @json($labels),
                            datasets: [{
                                label: '{{ __('messages.Total_earning') }}',
                                data: @json($total_admin_provider_earning),
                                backgroundColor: 'rgba(255, 0, 0, 0.2)', // Red background with opacity
                                borderColor: 'rgba(255, 0, 0, 1)', // Red border color
                                borderWidth: 2, // Increase border width for emphasis
                                pointRadius: 0, // Hide dots by default
                                pointHoverRadius: 6, // Show dots on hover
                                pointHitRadius: 10, // Increase hit area for easier hovering
                                tension: 0.4 // Add some curvature to the line

                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    min: 0,
                                    ticks: {
                                        stepSize: 1,
                                        color: tickColor // White y-axis labels
                                    },
                                    grid: {
                                        color: gridColor // White grid lines for y-axis
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: tickColor // White x-axis labels
                                    },
                                    grid: {
                                        color: gridColor // White grid lines for x-axis
                                    }
                                }
                            },
                            plugins: {
                                annotation: {
                                    annotations: {
                                        line1: {
                                            type: 'line',
                                            mode: 'vertical',
                                            scaleID: 'x',
                                            value: currentMonthIndex, // Index of the current month
                                            borderColor: 'rgba(255, 0, 0, 0.2)',
                                            borderWidth: 2,
                                            borderDash: [10, 5], // Dashed line configuration
                                            label: {
                                                enabled: true,
                                                position: 'start',
                                                backgroundColor: 'rgba(255, 0, 0, 0.2)',
                                                color: 'black',
                                                font: {
                                                    style: 'bold'
                                                }
                                            }
                                        }
                                    }
                                }
                            },
                            onClick: function(evt, element) {
                                if (element.length > 0) {
                                    var index = element[0].index;
                                    var month = this.data.labels[index];
                                    console.log(index + "--" + month);
                                    const months = {
                                        en: [
                                            'January', 'February', 'March', 'April', 'May', 'June',
                                            'July', 'August', 'September', 'October', 'November',
                                            'December'
                                        ],
                                        ar: [
                                            'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
                                            'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
                                        ]
                                    };
                                    month = months["en"][index];
                                    month_ar = months["ar"][index];
                                    updateEarningsSections(month, month_ar);
                                }
                            }
                        }
                    });
                    var adminEarning = '{{ __('messages.Admin_Earning') }}'
                    var providerEarning = '{{ __('messages.Provider_Earning') }}'

                    function updateEarningsSections(month, month_ar) {
                        console.log(earningsData);


                        var adminTotal = earningsData[month].admin;
                        var providerTotal = earningsData[month].provider;

                        document.getElementById('adminTotal').innerHTML = `
					<div><span class="currency">{{ __('messages.EGP') }}</span> <span class="total">${adminTotal}</span></div>
					<div>${adminEarning}</div>
				`;

                        document.getElementById('providerTotal').innerHTML = `
					<div><span class="currency">{{ __('messages.EGP') }}</span> <span class="total">${providerTotal}</span></div>
					<div>${providerEarning}</div>
				`;
                        @if (session('lang') == 'en')
                            document.getElementById('currentMonth').innerText = month; // Update month name
                        @else
                            document.getElementById('currentMonth').innerText = month_ar; // Update month name
                        @endif
                    }
                });
            </script>


        @endsection
