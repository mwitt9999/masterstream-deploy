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
                    <h2>Status</h2>
                </div>

                <div class="portlet-body form">
                    <table class="table table-striped" id="status-table">
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
@endsection

@section('scripts')

    <script>
        statusTable = $('#status-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '/status/all',
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
                "defaultContent": "<button class='btn btn-sm btn-danger btn-table-delete'>Delete</button><button class='btn btn-sm btn-success btn-table-edit' data-toggle='modal' data-target='#statusModal'>Edit</button>"
            } ]
        });

        $('#status-table tbody').on( 'click', 'button', function () {

            mode = $(this).html();

            var data = statusTable.row( $(this).parents('tr') ).data();

            if(mode == 'Delete'){
                url = '/status/delete/'+data.id;

                $.ajax({
                    type: 'get',
                    url: url,
                    success: function(data){
                        statusTable.ajax.reload(null,false);
                    }
                });

            }else{
                $('.err-div-form-status ul li').remove();
                $('.err-div-form-status').hide();
                $('#statusModalTitle').html('Edit Status');
                $('input[name="mode"]').val('edit');
                $('input[name="id"]').val(data.id);
                $('input[name="name"]').val(data.name);
                $('input[name="ip"]').val(data.ip);
            }
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