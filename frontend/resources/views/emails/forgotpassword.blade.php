<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>


    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
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

        .link-text{
            margin-bottom: 1%;
        }
        </style>

</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">Forgot password you have, help fix I will...</div>
        <div class="row link-text">
            <span><h2>Feel the force you must, click <a href="{{ $forgotpasswordlink }}">here</a> you should</h2></span>
        </div>
        <div class="row">
            <div class="col-lg-6 404-img-cont">
                <span><img class="img-yoda" src="{{ $message->embed('images/Yoda.jpg') }}"/></span>
            </div>
        </div>
    </div>
</div>
</body>
</html>
