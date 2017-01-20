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
                    <h2>Tasks</h2>
                </div>

                <div class="portlet-body form">
                    <table class="table table-striped" id="tasks-table">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Name</th>
                            <th>Command</th>
                            <th>Run from Build Directory</th>
                            <th>Command Directory</th>
                            <th>Output Message</th>
                            <th>Task Type</th>
                            <th>Created At</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="taskModalTitle">Add Task</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger err-div-form-task">
                            <ul>

                            </ul>
                        </div>

                        <form action="/task/add" method="post" id="form-task">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" value="">
                            <input type="hidden" name="mode" value="">

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label for="command">Command</label>
                                <input type="text" class="form-control" name="command" placeholder="Command">
                            </div>
                            <div class="form-group">
                            <label>Command Directory: </label>
                                <select class="form-control selectpicker command_dir_select" name="run_from_build_directory">
                                    <option value=''>Run this command from the build directory?</option>
                                    <option value=1>Yes</option>
                                    <option value=0>No</option>
                                </select>
                            </div>
                            <div class="form-group command_directory_container" style='display: none;'>
                                <label>(Specify a different directory to run command from) </label>
                                <input type="text" class="form-control" name="command_directory" placeholder="Command Directory">
                            </div>
                            <div class="form-group">
                                <label for="output_message">Output Message</label>
                                <input type="text" class="form-control" name="output_message" placeholder="Output Message">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-task" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        $(document).ready(function(){
            $("div.addNewTaskButton").html("<div class='ml-1 float-xs-right'><button type='button' id='btn-show-task-modal' class='btn btn-primary btn-md .dataTables_wrapper' data-toggle='modal' data-target='#taskModal'>Add New Task </button></div>");

            $(document).on('click', '#btn-show-task-modal', function(){
                $('.err-div-form-task ul li').remove();
                $('.err-div-form-task').hide();
                $('#taskModalTitle').html('Add Task');
                $('input[name="mode"]').val('add');
                $('input[name="id"]').val('');
                $('input[name="name"]').val('');
                $('input[name="command"]').val('');
                $('input[name="output_message"]').val('');
                $('input[name="command_directory"]').val('');
                $('.command_dir_select option[value=""]').prop('selected', false).trigger('change');
            });
        });

        $('.command_dir_select').on('change', function(){
           if($(this).val() == 1) {
               $('.command_directory_container').hide().val("");
           } else if($(this).val() == "") {
               $('.command_directory_container').hide().val("");
           } else {
               $('.command_directory_container').show();
           }
        });

        taskTable = $('#tasks-table').DataTable({
            processing: true,
            taskSide: true,
            responsive: true,
            dom: '<"addNewTaskButton">frtip',
            ajax: '/task/all',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'command', name: 'command'},
                {data: 'run_from_build_directory', name: 'run_from_build_directory'},
                {data: 'command_directory', name: 'command_directory'},
                {data: 'output_message', name: 'output_message'},
                {data: 'created_at', name: 'created_at'},
                {data: '', name: ''},
            ],
            columnDefs: [ {
                "targets": -1,
                "data": null,
                "defaultContent": "<button class='btn btn-sm btn-danger btn-table-delete mr-1'>Delete</button><button class='btn btn-sm btn-success btn-table-edit' data-toggle='modal' data-target='#taskModal'>Edit</button>"
            } ]
        });


        $('#tasks-table tbody').on( 'click', 'button', function () {

            mode = $(this).html();

            var data = taskTable.row( $(this).parents('tr') ).data();

            if(mode == 'Delete'){
                url = '/task/delete/'+data.id;

                $.ajax({
                    type: 'get',
                    url: url,
                    success: function(data){
                        taskTable.ajax.reload(null,false);
                        toastr.success( 'Task Deleted' , "Success!");

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
                $('.err-div-form-task ul li').remove();
                $('.err-div-form-task').hide();
                $('#taskModalTitle').html('Edit Task');
                $('input[name="mode"]').val('edit');
                $('input[name="id"]').val(data.id);
                $('input[name="name"]').val(data.name);
                $('input[name="command"]').val(data.command);
                $('input[name="output_message"]').val(data.output_message);
                $('input[name="command_directory"]').val(data.command_directory);
                $('.command_dir_select option[value="' + data.run_from_build_directory + '"]').prop('selected', true).trigger('change');

            }
        });

        $('#taskModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });

        $('#btn-task').on('click', function(e){
           e.preventDefault();

            $('.err-div-form-task ul li').remove();
            $('.err-div-form-task').hide();

            mode = $('input[name="mode"]').val();

            if(mode == "add"){
                url = '/task/add';
            } else {
                url = '/task/update';
            }

            formData = $( "#form-task" ).serialize();

            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(data){
                    $('#taskModal').modal('hide');
                    taskTable.ajax.reload(null,false);
                    toastr.success( 'Task Saved' , "Success!");
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