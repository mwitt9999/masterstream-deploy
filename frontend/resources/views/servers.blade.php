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
                    <h2>Servers</h2>
                </div>

                <div class="portlet-body form">
                    <table class="table table-striped" id="servers-table">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Name</th>
                            <th>IP Address</th>
                            <th>Created At</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="serverModal" tabindex="-1" role="dialog" aria-labelledby="serverModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="serverModalTitle">Add Server</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger err-div-form-server">
                            <ul>

                            </ul>
                        </div>

                        <form action="/server/add" method="post" id="form-server">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" value="">
                            <input type="hidden" name="mode" value="">

                            <div class="form-group">
                                <label for="name">Server Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Server Name">
                            </div>
                            <div class="form-group">
                                <label for="ip">IP Address</label>
                                <input type="text" class="form-control" name="ip" placeholder="IP Address">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-server" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        $(document).ready(function(){
            $("div.addNewServerButton").html("<div class='ml-1 float-xs-right'><button type='button' id='btn-show-server-modal' class='btn btn-primary btn-md .dataTables_wrapper' data-toggle='modal' data-target='#serverModal'>Add New Server </button></div>");

            $(document).on('click', '#btn-show-server-modal', function(){
                $('.err-div-form-server ul li').remove();
                $('.err-div-form-server').hide();
                $('#serverModalTitle').html('Add Server');
                $('input[name="mode"]').val('add');
                $('input[name="id"]').val('');
                $('input[name="name"]').val('');
                $('input[name="ip"]').val('');
            });
        });

        serverTable = $('#servers-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            dom: '<"addNewServerButton">frtip',
            ajax: '/server/all',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'ip', name: 'ip'},
                {data: 'created_at', name: 'created_at'},
                {data: '', name: ''},
            ],
            columnDefs: [ {
                "targets": -1,
                "data": null,
                "defaultContent": "<button class='btn btn-sm btn-danger btn-table-delete mr-1'>Delete</button><button class='btn btn-sm btn-success btn-table-edit' data-toggle='modal' data-target='#serverModal'>Edit</button>"
            } ]
        });


        $('#servers-table tbody').on( 'click', 'button', function () {

            mode = $(this).html();

            var data = serverTable.row( $(this).parents('tr') ).data();

            if(mode == 'Delete'){
                url = '/server/delete/'+data.id;

                $.ajax({
                    type: 'get',
                    url: url,
                    success: function(data){
                        serverTable.ajax.reload(null,false);
                        toastr.success( 'Server Deleted' , "Success!");
                    },
                    error   : function ( jqXhr, json, errorThrown )
                    {
                        var errors = jqXhr.responseJSON;
                        var errorsHtml= '';
                        $.each( errors, function( key, value ) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error( errorsHtml , "");
                    }
                });

            }else{
                $('.err-div-form-server ul li').remove();
                $('.err-div-form-server').hide();
                $('#serverModalTitle').html('Edit Server');
                $('input[name="mode"]').val('edit');
                $('input[name="id"]').val(data.id);
                $('input[name="name"]').val(data.name);
                $('input[name="ip"]').val(data.ip);
            }
        });

        $('#serverModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });

        $('#btn-server').on('click', function(e){
           e.preventDefault();

            $('.err-div-form-server ul li').remove();
            $('.err-div-form-server').hide();

            mode = $('input[name="mode"]').val();

            if(mode == "add"){
                url = '/server/add';
            } else {
                url = '/server/update';
            }

            formData = $( "#form-server" ).serialize();

            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(data){
                    $('#serverModal').modal('hide');
                    serverTable.ajax.reload(null,false);
                    toastr.success( 'Server Saved' , "Success!");
                },
                error   : function ( jqXhr, json, errorThrown )
                {
                    var errors = jqXhr.responseJSON;
                    var errorsHtml= '';
                    $.each( errors, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    toastr.error( errorsHtml , "");
                }
            });
        });

        //Datatables debugger
//         (function() {
//             var url = '//debug.datatables.net/bookmarklet/DT_Debug.js';
//             if (typeof DT_Debug != 'undefined') {
//                 if (DT_Debug.instance !== null) {
//                     DT_Debug.close();
//                 } else {
//                     new DT_Debug();
//                 }
//             } else {
//                 var n = document.createElement('script');
//                 n.setAttribute('language', 'JavaScript');
//                 n.setAttribute('src', url + '?rand=' + new Date().getTime());
//                 document.body.appendChild(n);
//             }
//         })();


    </script>

@stop