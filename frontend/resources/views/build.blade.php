@extends('layouts.default')

@section('navbar')
    @include('components.navbar')
@endsection

@section('sidebar')
    @include('components.sidebar')
@endsection

@section('stylesheets')
    <style>
    </style>

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
                    <h2>Build Site</h2>
                </div>
                <div class="portlet-body form">
                    <form role="form" class="form-horizontal" id='form-submit-build'>
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <label>Site: </label>
                                    <select class="form-control selectpicker" name="site_id" id="site_id">
                                        <option value="">Select a Site to Build</option>
                                        @if(isset($sites))
                                            @foreach ($sites as $site)
                                                <option value="{{ $site->id }}">{{ $site->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="hide-build-container" style="display: none;">
                                        <label>Git Tag Version: </label>
                                        <select class="form-control selectpicker" name="commit_hash" id="commit_hash">
                                            <option value="">Select a version to build</option>
                                        </select>
                                        <label>Servers: </label>
                                        <select multiple class="form-control selectpicker" id="servers" name="server_id[]" title="Choose one or multiple servers">
                                            @if(count($servers)>0)
                                                @foreach ($servers as $server)
                                                    <option value="{{ $server->id }}">{{ $server->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <label>Pipeline: </label>
                                        <select class="form-control selectpicker" id="pipeline" name="pipeline_id" title="Choose a pipeline to build with">
                                            @if(count($pipelines)>0)
                                                @foreach ($pipelines as $pipeline)
                                                    <option value="{{ $pipeline->id }}">{{ $pipeline->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="col-lg-1-5 mt-1">
                                            <button type="submit" class="btn btn-md btn-primary" id="btn-build">Build</button>
                                            <button class="btn btn-danger" id='btn-reset-build' >Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <h2>Completed Builds</h2><h5>(On Server)</h5>
                </div>

                <div class="portlet-body form">
                    <table class="table table-striped deploy-tables" id="build-table">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Site Name</th>
                            <th>Commit</th>
                            <th>Git Tag</th>
                            <th>Server Name</th>
                            <th>Build Directory</th>
                            <th>Created At</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>


@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.2/socket.io.min.js"></script>
    <script>

        $('#btn-reset-build').on('click', function(e){
            e.preventDefault();
            $('.selectpicker').selectpicker('val', '');
            $(".selectpicker").selectpicker('refresh');
            $('#site_id').selectpicker('toggle').prop('disabled', false).selectpicker('refresh');
            $('.hide-build-container').hide();
        });

        $('#pipeline_id').on('change', function() {
            $('#site_id').selectpicker('toggle');
        });

        $('#site_id').on('change', function(){

            if($(this).val() != ""){
                $('.hide-build-container').show();
                $('#site_id').selectpicker('toggle').prop('disabled', true).selectpicker('refresh');
                $('#commit_hash').children().remove();
                $('#commit_hash').append('<option value="">Select a version to build</option>').attr('selected', false).selectpicker('refresh');

                $.ajax({
                    type: 'get',
                    url: '/build/commits/get',
                    data: { 'site_id' : $(this).val()},
                    success: function (data) {
                        $.each(data.commits, function(key, version) {
                            $('#commit_hash').append('<option value="'+version.commit['sha']+'">'+version.name+'</option>');
                            $("#commit_hash").val('').selectpicker('refresh');
                        });
                    }
                });
            } else {

            }
        });

        function listenForBuildRemovalResults() {
            var socketIPAddress = 'http://{{getenv('MAIN_DOCKER_IP_ADDRESS')}}:3000';
            var socket = io(socketIPAddress);

            socket.on('remove-builds:ShowBuildRemovalResult', function(data){
                if(data.output == 'Completed Removal') {
                    buildTable.ajax.reload(null, false);
                    toastr.success('Build removed from server', "Success!");
                }
            });
        }

        $(document).ready(function(){
            listenForBuildRemovalResults();

            $('#btn-build').on('click', function(e) {
                e.preventDefault();
                $('#site_id').selectpicker('toggle').prop('disabled', false).selectpicker('refresh');

                version = $("#commit_hash option:selected").text();
                formData = $("#form-submit-build").serialize() + '&version=' + version;

                $.ajax({
                    type: 'POST',
                    url: '/build/submit',
                    data: formData,
                    success: function (data) {
                        $('.selectpicker').selectpicker('val', '');
                        $(".selectpicker").selectpicker('refresh');
                        $('#site_id').prop('disabled', false).selectpicker('refresh');
                        $('.hide-build-container').hide();

                        toastr.success( 'Build Successfully Started' , "Success!");
                    },
                    error   : function ( jqXhr, json, errorThrown )
                    {
                        $('#site_id').prop('disabled', true).selectpicker('refresh');
                        var errors = jqXhr.responseJSON;
                        var errorsHtml= '';
                        $.each( errors, function( key, value ) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error( errorsHtml , "");
                    }

                });
            });
        });

        buildTable = $('#build-table').DataTable({
            processing: true,
            buildSide: true,
            responsive: true,
            ajax: '/build/all',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'site_name', name: 'site_name'},
                {data: 'commit_hash', name: 'commit_hash'},
                {data: 'version', name: 'version'},
                {data: 'server_name', name: 'server_name'},
                {data: 'build_directory', name: 'build_directory'},
                {data: 'created_at', name: 'created_at'},
                {data: '', name: ''},
            ],
            columnDefs: [ {
                "targets": -1,
                "data": null,
                "defaultContent": "<button class='btn btn-sm btn-danger btn-table-delete mr-1'>Remove from Server</button>"
            } ]
        });

        $('#build-table tbody').on( 'click', 'button', function (e) {
            e.preventDefault();

            var data = buildTable.row($(this).parents('tr')).data();

            url = '/build/delete/' + data.id;

            $.ajax({
                type: 'get',
                url: url,
                success: function (data) {
                    toastr.success('Build Job Started', "Success!");
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    var errorsHtml = '';
                    $.each(errors, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    toastr.error(errorsHtml, "");
                }
            });
        });

    </script>
@endsection