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
                    <h2>Reset Password</h2>
                    <p>Enter a new password to reset your password</p>
                </div>
                <div class="portlet-body form">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form role="form" class="form-horizontal" action="/login/resetpassword/submit" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <input type="hidden" name="user_id" value="{{$userId}}"/>
                                    <label>Password: </label>
                                    <input class="form-control" name="password" type="password" placeholder="Password"/>
                                    <label>Confirm Password: </label>
                                    <input class="form-control" name="password_confirmation" type="password" placeholder="Confirm Password"/>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-lg-5">
                                <button type="submit" class="btn btn-md btn-primary" id="btn-login">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
@endsection