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
                    <h2>Server Deployment</h2>
                </div>
                <div class="portlet-body form">
                    <form role="form" class="form-horizontal" id='form-submit-deployment'>
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <label>Git Tag Version: </label>
                                    <select class="form-control selectpicker" name="commit_hash" id="commit_hash">
                                        <option value="">Select a version to deploy</option>
                                        @foreach ($commits as $commit)
                                            <option value="{{ $commit['commit']['sha'] }}">{{ $commit['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <label>Servers: </label>
                                    <select multiple class="form-control selectpicker" name="server_id[]" title="Choose one or multiple servers">
                                        @foreach ($servers as $server)
                                            <option value="{{ $server->id }}">{{ $server->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-lg-5">
                                <button type="submit" class="btn btn-md btn-primary" id="btn-deploy">Deploy</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <h2>Deployment Status</h2>
                </div>
                <div class="portlet-body deployment-status">
                    <ul>

                    </ul>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.2/socket.io.min.js"></script>
    <script>

        function listenForTaskResults() {
            var socketIPAddress = 'http://{{getenv('DOMAIN_NAME')}}:3000';
            var socket = io(socketIPAddress);

            socket.on('terminal-output:ShowTerminalTaskResult', function(data){
                if(data.output == 'Completed Build') {

                }
            });
        }

        $(document).ready(function(){
            listenForTaskResults();

            $('#btn-deploy').on('click', function(e) {
                e.preventDefault();

                version = $("#commit_hash option:selected").text();
                formData = $("#form-submit-deployment").serialize() + '&version=' + version;

                $.ajax({
                    type: 'POST',
                    url: '/deployment/submit',
                    data: formData,
                    success: function (data) {

                    }
                });
            });
        });

    </script>
@endsection