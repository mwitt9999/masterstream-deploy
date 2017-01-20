<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href=" {{ asset('/global/vendor/bootstrap-select/bootstrap-select.css') }} ">
    <link rel="stylesheet" href=" {{ asset('/css/app.css') }} ">
    @yield('stylesheets')

    <style>
        .toast-top-full-width {
            position: absolute;
            left: 0;
            right: 0;
            margin-left: auto;
            margin-right: auto;
            width: 700px;
        }

        #toast-container > div {
            opacity: 1;
            -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=100);
            filter: alpha(opacity=100);
        }

        #toast-container > .alert {
            background-image: none !important;
        }

        #toast-container > .alert:before {
            position: fixed;
            font-family: FontAwesome;
            font-size: 24px;
            float: left;
            color: #FFF;
            padding-right: 0.5em;
            margin: auto 0.5em auto -1.5em;
        }

        #toast-container > .alert-info:before {
            content: "\f05a";
        }
        #toast-container > .alert-info:before,
        #toast-container > .alert-info {
            color: #31708f;
        }

        #toast-container > .alert-success:before {
            content: "\f00c";
        }
        #toast-container > .alert-success:before,
        #toast-container > .alert-success {
            color: #3c763d;
        }

        #toast-container > .alert-warning:before {
            content: "\f06a";
        }
        #toast-container > .alert-warning:before,
        #toast-container > .alert-warning {
            color: #8a6d3b;
        }

        #toast-container > .alert-danger:before {
            content: "\f071";
        }
        #toast-container > .alert-danger:before,
        #toast-container > .alert-danger {
            color: #a94442;
        }    </style>

</head>
<body>

@yield('navbar')

<div class="container-fluid">
    <div class="row">

        @yield('sidebar')


        @yield('content')

    </div>
</div>


<!-- jQuery first, then Tether, then Bootstrap JS. -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('/js/app.js') }}"></script>

@yield('scripts')

<script>
    function listenForTaskResults() {
        var socketIPAddress = 'http://{{getenv('DOMAIN_NAME')}}:3000';
        var socket = io(socketIPAddress);

        socket.on('terminal-output:ShowTerminalTaskResult', function(data){
            var output = data.output;

            if (/Completed Build:/i.test(output)){
                toastr.success(output, "Success!");
            }
        });
    }

    $(document).ready(function(){
        listenForTaskResults();

        toastr.options = {
        "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-full-width",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "1000000",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
        };

        //Login Errors
        @if (count($errors) > 0)
            errors = '';
            @foreach ($errors->all() as $error)
                errors += '{{$error}}'+'<br>';
            @endforeach

             toastr.error( errors , "");
        @endif


        <?php
            if(session()->has('app_error')){ ?>
                message = "{{ session('app_error') }}";
                toastr.error(message, 'Error!');
            <?php
            }
            ?>

            <?php
            if(session()->has('app_success')){ ?>
                message = "{{ session('app_success') }}";
                toastr.success(message, 'Success!');
            <?php
            }
            ?>

        });
</script>


</body>
</html>

