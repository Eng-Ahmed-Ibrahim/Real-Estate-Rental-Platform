<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password </title>
    <style>
        *,
        *:before,
        *:after {
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        hr {
            background: #4bc970;
            height: 1px;
            border: 0;
            border-top: 1px solid #ccc;
            padding: 0;
            text-align: right;
            width: 5%;
            float: center;
        }

        span {
            color: red;
        }

        label {
            padding-top: 15px;
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        body {
            font-size: 13px;
            font-family: "Nunito", sans-serif;
            color: #384047;
        }

        form {
            font-size: 16px;
            max-width: 300px;
            margin: 10px auto;
            padding: 10px 20px;
            background: #f4f7f8;
            border-radius: 0px;
        }

        h1 {
            padding-top: 2em;
            font-size: 32px;
            margin: 0 0 30px 0;
            text-align: center;
        }

        h3 {
            padding-top: 1em;
            font-size: 20px;
            margin: 0 0 30px 0;
            text-align: center;
        }

        input[type="text"],
        input[type="password"],
        input[type="date"],
        input[type="datetime"],
        input[type="email"],
        input[type="number"],
        input[type="search"],
        input[type="tel"],
        input[type="time"],
        input[type="url"],
        textarea,
        select {
            border: none;
            font-size: 16px;
            height: auto;
            margin: 0;
            outline: 0;
            padding: 15px;
            width: 66%;
            color: #8a97a0;
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.03) inset;
            margin-bottom: 30px;
        }

        button {
            padding: 12px 39px 13px 39px;
            color: #fff;
            background-color: #4bc970;
            font-size: 18px;
            text-align: center;
            font-style: normal;
            border: 1px solid #3ac162;
            margin-bottom: 10px;
            overflow: hidden;
        }

        @media screen and (min-width: 480px) {
            form {
                max-width: 480px;
            }
        }
    </style>
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />


</head>

<body>
    @if(session('step') == null || session('step') == 1)

    <h1>Forgot your password?</h1>
    <hr>
    </hr>
    <h3>Enter your email address to reset your password</h3>
    <form action="{{route('send_otp')}}" method="post">
        @csrf

        <label for="mail">Email</label></br>
        <input type="email" id="name" name="email" placeholder="Enter your email address" >
        <button type="submit">Submit</button>
    </form>
    @endif
    @if(session('step') == 2)

    <form action="{{route('verify_otp')}}" method="post">
        @csrf

        <label for="otp">OTP</label></br>
        <input type="text" id="otp" name="otp" placeholder="Enter your OTP" required >
        <button type="submit">Submit</button>
    </form>
    @endif
    @if(session('step') == 3)

    <form action="{{route('reset_password')}}" method="post">
        @csrf
        <label for="new_password">New Passowrd</label></br>
        <input type="password" id="password" name="new_password" placeholder="New Password" required >
        <label for="confirm_password">Confirm Passowrd</label></br>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" >
        <button type="submit">Submit</button>
    </form>
    @endif
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "timeOut": "3000"
        };

        @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
        @endif

        @if(Session::has('info'))
        toastr.info("{{ Session::get('info') }}");
        @endif

        @if(Session::has('warning'))
        toastr.warning("{{ Session::get('warning') }}");
        @endif
    </script>

</body>

</html>