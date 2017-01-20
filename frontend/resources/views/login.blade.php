@extends('layouts.default')

@section('navbar')

    <nav class="navbar navbar-dark navbar-fixed-top bg-inverse">
        <button type="button" class="navbar-toggler hidden-sm-up" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar" aria-label="Toggle navigation"></button>
        <a class="navbar-brand" href="#">Masterstream Deploy</a>
        <div id="navbar">
        </div>
    </nav>

@endsection

@section('content')
    <div class="row main mt-3 col-lg-8">
        <div class="portlet light bordered offset-lg-6">
                <div class="portlet-title">
                    <h2>Login</h2>
                </div>
                <div class="portlet-body form">
                    <form role="form" class="form-horizontal" id='form-deployment' action="/login/authenticate" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <label>Email: </label>
                                    <input class="form-control" name="email" type="email" placeholder="Email Address"/>
                                    <label>Password: </label>
                                    <input class="form-control" name="password" type="password" placeholder="Password"/>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-lg-5">
                                <button type="submit" class="btn btn-md btn-primary" id="btn-login">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
@endsection