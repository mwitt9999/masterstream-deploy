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
                    <h2>Sites</h2>
                </div>

                <div class="portlet-body form">
                    <table class="table table-striped" id="sites-table">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Name</th>
                            <th>Github Account Name</th>
                            <th>Github Repository Name</th>
                            <th>Created At</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="siteModal" tabindex="-1" role="dialog" aria-labelledby="siteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="siteModalTitle">Add Site</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger err-div-form-site">
                            <ul>

                            </ul>
                        </div>

                        <form action="/site/add" method="post" id="form-site">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" value="">
                            <input type="hidden" name="mode" value="">

                            <div class="form-group">
                                <label for="name">Site Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Site Name">
                            </div>
                            <div class="form-group">
                                <label for="github_account_name">Github Account Name</label>
                                <input type="text" class="form-control" name="github_account_name" placeholder="Github Account Name">
                            </div>
                            <div class="form-group">
                                <label for="github_repository_name">Github Repository name</label>
                                <input type="text" class="form-control" name="github_repository_name" placeholder="Github Repository name">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-site" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        $(document).ready(function(){
            $("div.addNewSiteButton").html("<div class='ml-1 float-xs-right'><button type='button' id='btn-show-site-modal' class='btn btn-primary btn-md .dataTables_wrapper' data-toggle='modal' data-target='#siteModal'>Add New Site </button></div>");

            $(document).on('click', '#btn-show-site-modal', function(){
                $('.err-div-form-site ul li').remove();
                $('.err-div-form-site').hide();
                $('#siteModalTitle').html('Add Site');
                $('input[name="mode"]').val('add');
                $('input[name="id"]').val('');
                $('input[name="name"]').val('');
                $('input[name="github_account_name"]').val('');
                $('input[name="github_repository_name"]').val('');
            });
        });

        siteTable = $('#sites-table').DataTable({
            processing: true,
            siteSide: true,
            responsive: true,
            dom: '<"addNewSiteButton">frtip',
            ajax: '/site/all',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'github_account_name', name: 'github_account_name'},
                {data: 'github_repository_name', name: 'github_repository_name'},
                {data: 'created_at', name: 'created_at'},
                {data: '', name: ''},
            ],
            columnDefs: [ {
                "targets": -1,
                "data": null,
                "defaultContent": "<button class='btn btn-sm btn-danger btn-table-delete mr-1'>Delete</button><button class='btn btn-sm btn-success btn-table-edit' data-toggle='modal' data-target='#siteModal'>Edit</button>"
            } ]
        });


        $('#sites-table tbody').on( 'click', 'button', function () {

            mode = $(this).html();

            var data = siteTable.row( $(this).parents('tr') ).data();

            if(mode == 'Delete'){
                url = '/site/delete/'+data.id;

                $.ajax({
                    type: 'get',
                    url: url,
                    success: function(data){
                        siteTable.ajax.reload(null,false);
                        toastr.success( 'Site Deleted' , "Success!");
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
                $('.err-div-form-site ul li').remove();
                $('.err-div-form-site').hide();
                $('#siteModalTitle').html('Edit Site');
                $('input[name="mode"]').val('edit');
                $('input[name="id"]').val(data.id);
                $('input[name="name"]').val(data.name);
                $('input[name="github_account_name"]').val(data.github_account_name);
                $('input[name="github_repository_name"]').val(data.github_repository_name);
            }
        });

        $('#siteModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });

        $('#btn-site').on('click', function(e){
           e.preventDefault();

            $('.err-div-form-site ul li').remove();
            $('.err-div-form-site').hide();

            mode = $('input[name="mode"]').val();

            if(mode == "add"){
                url = '/site/add';
            } else {
                url = '/site/update';
            }

            formData = $( "#form-site" ).serialize();

            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(data){
                    $('#siteModal').modal('hide');
                    siteTable.ajax.reload(null,false);
                    toastr.success( 'Site Saved' , "Success!");
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