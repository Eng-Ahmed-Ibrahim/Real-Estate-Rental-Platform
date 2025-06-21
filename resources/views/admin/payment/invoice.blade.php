<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INV #11</title>
    <link rel="icon" href="{{asset('assets/static/logo.png')}}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            /* font-family: 'tahoma' ; */
            color: #4e5e6a;
            margin: 0;
            padding: 0;
        }

        .invoices {
            padding: 30px;
        }

        .invoice-left {
            float: left;
            width: 65%;
            padding-right: 20px;
        }

        .invoice-right {
            float: left;
            width: 30%;
        }

        .invoice-left img {
            display: block;
        }

        .invoice-left div,
        .invoice-right div {
            margin-bottom: 10px;
        }

        .text-bold {
            font-size: 14px;
            font-weight: bold;
            color: rgb(78, 94, 106);
        }

        .invoice-info-title {
            padding: 5px;
            font-size: 20px;
            font-weight: bold;
            background-color: rgba(98, 150, 212, 1);
            color: #fff;
            margin: 10px 0;
            display: inline-block;
            ;
            border-radius: 8px;
        }

        .text-right {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }

        th,
        td {
            border: 1px solid #eee;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: rgba(98, 150, 212, 1);
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }

        .total-paid {
            background-color: #f4f4f4;
        }

        .total-remaining {
            background-color: rgba(98, 150, 212, 1);
            color: #fff;
        }

        .main-btn-primary {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
        }

        .main-btn-primary i {
            font-size: 14px;
        }
    </style>
    @if(session('lang')=="ar")
    <style>
        body {
            font-family: 'DejaVu Sans', 'XBRiyaz', sans-serif;
            direction: rtl;
            text-align: right !important;
            font-size: 13px;
        }

        .invoice-info-title {
            text-align: right;
            width: 100%;
        }
    </style>
    @endif
</head>

<body>
    <section class="invoices">
        @if(session('lang')=="ar")
        <div class="invoice-right">
            <div class="text-right">
                <div class="invoice-info-title">&nbsp;INV#{{$invoice->invoice_id}}&nbsp;</div>
                <div>{{__('messages.Due_Date')}} : {{\Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }}</div>

            </div>

        </div>
        <div class="invoice-left">
            <img src="{{request()->getSchemeAndHttpHost()}}/static/icon.png" alt="Company Logo">

            <div class="text-bold" style="margin-top: 10px;">Ren2go</div>
            <div>{{__('messages.Phone')}}: {{$company_information->phone}}</div>
            <div>{{__('messages.Email')}}: {{$company_information->contact_email}}</div>
            <div>{{__('messages.Website')}}: <a href="{{request()->getSchemeAndHttpHost()}}" style="color: #4e5e6a;">{{request()->getSchemeAndHttpHost()}}</a></div>
            <div style="margin-top: 10px;">
                <div style="margin: 0;"> <b>{{__("messages.Bill_To")}}</b></div>
                <div style="margin: 0;"><strong>{{$invoice->customer->name}}</strong></div>
            </div>
        </div>
        @else
        <div class="invoice-left">
            <img src="{{request()->getSchemeAndHttpHost()}}/static/icon.png" alt="Company Logo">

            <div class="text-bold" style="margin-top: 10px;">Ren2go</div>
            <div>{{__('messages.Phone')}}: {{$company_information->phone}}</div>
            <div>{{__('messages.Email')}}: {{$company_information->contact_email}}</div>
            <div>{{__('messages.Website')}}: <a href="{{request()->getSchemeAndHttpHost()}}" style="color: #4e5e6a;">{{request()->getSchemeAndHttpHost()}}</a></div>
            <div style="margin-top: 10px;">
                <div style="margin: 0;"> <b>{{__("messages.Bill_To")}}</b></div>
                <div style="margin: 0;"><strong>{{$invoice->customer->name}}</strong></div>
            </div>
        </div>
        <div class="invoice-right">
            <div class="text-right">
                <div class="invoice-info-title">&nbsp;INV#{{$invoice->invoice_id}}&nbsp;</div>
                <div>{{__('messages.Due_Date')}} : {{\Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }}</div>

            </div>

        </div>

        @endif

        <div style="clear: both;"></div>
        <table class="table-responsive">
            <thead>
                <tr style="font-weight: bold; color: rgb(78, 94, 106); border-bottom: 1px solid #eee; border-top: 1px solid #eee;">
                    <th style="width: 5%;">#</th>
                    <th style="width: 25%;">{{__("messages.Payment_method")}}</th>
                    <th style="width: 30%;">{{__('messages.Date')}}</th>
                    <th style="width: 30%;">{{__('messages.Amount')}}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$invoice->invoice_id}}</td>
                    @if($invoice->payment_type=="bank_transfer")
                    <td>{{session('lang')=='en'?$invoice->payment_method->name :$invoice->payment_method->name_ar  }}</td>
                    @else 
                    <td>
                        {{__("messages.$invoice->payment_type")}}
                    </td>
                    @endif
                    <td>{{\Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }}</td>
                    <td>{{$invoice->amount}}</td>
                </tr>
            </tbody>
        </table>

    </section>
</body>

</html>