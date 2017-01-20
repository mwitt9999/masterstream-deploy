@extends('layouts.default')

@section('navbar')
    @include('components.navbar')
@endsection

@section('sidebar')
    @include('components.sidebar')
@endsection

@section('content')


    <div class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 main mt-3">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <h2>Server Rollback</h2>
                </div>
                <div class="portlet-body form">
                    <form role="form" class="form-horizontal" id='form-deployment' action="/rollback/submit" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <label>Git Tag Version: </label>
                                    <select class="form-control selectpicker" name="version">
                                        <option value="">Select a version to rollback</option>
                                        @foreach ($commits as $commit)
                                            <option value="{{ $commit['commit']['sha'] }}">{{ $commit['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <label>Servers: </label>
                                    <select multiple class="form-control selectpicker" name="ip[]" title="Choose one or multiple servers">
                                        @foreach ($servers as $server)
                                            <option value="{{ $server['ip'] }}">{{ $server['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-lg-5">
                                <button type="submit" class="btn btn-md btn-primary" id="btn-deploy">Rollback</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection