<!DOCTYPE html>
<html>
<head>
    <title>404</title>


    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'Lato', sans-serif;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
            color: #8c8c8c;
        }

        .img-yoda {
            width: 400px;
            height: auto;
            display:block;
            margin:auto;
        }

        .header {
            color: #8c8c8c; !important;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">Back, soon I will be...</div>
        <h2 class="header">May the force be with you</h2>
        <div class="row">
            <div class="col-lg-6 404-img-cont">
                <span><img class="img-yoda" src="{{ asset('images/Yoda.jpg') }}"/></span>
            </div>
        </div>
    </div>
</div>
</body>
</html>
