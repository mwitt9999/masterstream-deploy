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
                    <h2>Users</h2>
                </div>

                <div class="portlet-body form">
                    <table class="table table-striped" id="users-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Created At</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="userModalTitle">Add User</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger err-div-form-user">
                            <ul>

                            </ul>
                        </div>

                        <form action="/user/add" method="post" id="form-user">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" value="">
                            <input type="hidden" name="mode" value="add">

                            <div class="form-group">
                                <label for="name">First Name</label>
                                <input type="text" class="form-control" name="first_name" placeholder="First Name">
                            </div>
                            <div class="form-group">
                                <label for="ip">Last Name</label>
                                <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Email">
                            </div>
                            <div class="form-group form-field-password">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label for="ip">Type</label>
                                <select type="text" class="form-control type-select-box" name="type" placeholder="Type">
                                    <option value="">Choose a user type</option>
                                    <option value="admin">admin</option>
                                    <option value="basic">basic</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-user" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>s
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
//            $("div.toolbar").html('<b>Custom tool bar! Text/images etc.</b>');
            $("div.addNewUserButton").html("<div class='ml-1 float-xs-right'><button type='button' id='btn-table-add' class='btn btn-primary btn-md' data-toggle='modal' data-target='#userModal'>Add New User </button></div>");
        });

        userTable = $('#users-table').DataTable({
            processing: true,
            userSide: true,
            responsive: true,
            dom: '<"addNewUserButton">frtip',
            ajax: '/user/all',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'first_name', name: 'first_name'},
                {data: 'last_name', name: 'last_name'},
                {data: 'email', name: 'email'},
                {data: 'type', name: 'type'},
                {data: 'created_at', name: 'created_at'},
                {data: '', name: ''},
            ],
            columnDefs: [ {
                "targets": -1,
                "data": null,
                "defaultContent": "<button class='btn btn-sm btn-danger btn-table-delete' id='btn-table-delete'>Delete</button> <button class='btn btn-sm btn-success btn-table-edit' id='btn-table-edit' data-toggle='modal' data-target='#userModal'>Edit</button> <button class='btn btn-sm btn-info btn-table-reset' id='btn-table-reset'>Reset Password</button>"
            } ]
        });

        $('#users-table tbody').on( 'click', 'button', function () {
            mode = $(this).attr('id');

            var data = userTable.row( $(this).parents('tr') ).data();

            if(mode == 'btn-table-delete'){
                authenticatedUserId = "{{ $authenticatedUserId  }}";

                if(data.id == authenticatedUserId){
                    message = 'Cannot delete your own user profile';
                    toastr.error(message, "Error!");
                    return;
                }

                url = '/user/delete?userId='+data.id;

                $.ajax({
                    type: 'get',
                    url: url,
                    success: function(response){
                        if(response.success == 'true') {
                            message = 'Successfully deleted user';
                            toastr.success( message , "Success!");
                            userTable.ajax.reload(null,false);
                        } else {
                            message = 'Failed to delete. Please try again.';
                            toastr.error( message , "Error!");
                        }
                        userTable.ajax.reload(null,false);

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
            }else if(mode == 'btn-table-edit') {
                $('.form-field-password').hide();
                $('.err-div-form-user ul li').remove();
                $('.err-div-form-user').hide();
                $('#userModalTitle').html('Edit User');
                $('input[name="mode"]').val('edit');
                $('input[name="id"]').val(data.id);
                $('input[name="first_name"]').val(data.first_name);
                $('input[name="last_name"]').val(data.last_name);
                $('.type-select-box option[value="' + data.type + '"]').prop('selected', true).trigger('change');
                $('input[name="email"]').val(data.email);
                $('.form-field-password').val('');
            }else if(mode == 'btn-table-reset') {
                url = '/login/forgotpassword?userId='+data.id;

                $.ajax({
                    type: 'get',
                    url: url,
                    success: function(response){

                        if(response.success == 'true') {
                            message = 'Successfully sent forgot password email';
                            toastr.success( message , "Success!");
                            userTable.ajax.reload(null,false);
                        } else {
                            message = 'Failed to send forgot password email. Please try again.';
                            toastr.error( message , "Error!");
                        }

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
            }
        });

        $(document).on('click', '#btn-table-add', function(){
            $('.form-field-password').show();
            $('.err-div-form-user ul li').remove();
            $('.err-div-form-user').hide();
            $('#userModalTitle').html('Add User');
            $('input[name="mode"]').val('add');
            $('input[name="id"]').val("");
            $('input[name="first_name"]').val("");
            $('input[name="last_name"]').val("");
            $('input[name="email"]').val("");
            $('.type-select-box').val('').prop('selected', false).trigger('change');
            $('.form-field-password').val('');
        });

        $('#userModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });

        $('#btn-user').on('click', function(e){
           e.preventDefault();

            $('.err-div-form-user ul li').remove();
            $('.err-div-form-user').hide();

            mode = $('input[name="mode').val();

            if(mode == "add"){
                url = '/user/add';
            } else {
                url = '/user/update';
            }

            formData = $( "#form-user" ).serialize();

            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(data) {
                    if (data.success == "false") {
                        toastr.success('Failed to save user', "Error!");
                    } else {
                        $('#userModal').modal('hide');
                        userTable.ajax.reload(null, false);
                        toastr.success('User Saved', "Success!");
                    }
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