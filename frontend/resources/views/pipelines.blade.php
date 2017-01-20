@extends('layouts.default')

@section('navbar')
    @include('components.navbar')
@endsection

@section('sidebar')
    @include('components.sidebar')
@endsection

@section('stylesheets')
    <style>
        #updated-pipeline-task-list ul { width:100% !important; list-style: none !important; align-text:justrify !important;  margin: 0!important; }
        #updated-pipeline-task-list div { float:left !important; display:inline-block; width:100% !important; border-radius: 5px; overflow: hidden !important; border: groove thin darkgrey; padding: 2px 2px 2px 2px !important; margin-bottom: 10px !important;;}
        #updated-pipeline-task-list div::after { width:100% !important; }
        #updated-pipeline-task-list a { float:right !important;}
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
                    <h2>Pipelines</h2>
                </div>

                <div class="portlet-body form">
                    <table class="table table-striped" id="pipelines-table">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Name</th>
                            <th>Created At</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="updatePipelineTaskList" tabindex="-1" role="dialog" aria-labelledby="updatePipelineTaskListLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="updatePipelineTaskListTitle">Add/Remove Tasks</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger err-div-form-pipeline">
                            <ul>

                            </ul>
                        </div>

                        <form action="/pipeline/task/add" method="post" id="form-pipeline-add-task">
                            {{ csrf_field() }}

                            <input type="hidden" name="pipeline_id" value="">

                            <div class="col-lg-12">
                                <select class="mt-2" style="width: 100% !important; height: 200px !important;" id="task_id_select-from" multiple size="5">
                                    @if (count($tasks) > 0)
                                        @foreach ($tasks as $task)
                                            <option value="{{ $task->id }}">{{ $task->command }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <a href="JavaScript:void(0);" class="btn btn-primary btn-sm" id="btn-add">Add &raquo;</a>
                            </div>

                            <div class="col-lg-12 mt-1">
                                <h3>Current Task List</h3>
                                <p>Drag task to change position</p>
                                <div id="updated-pipeline-task-list">
                                </div>
                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-pipeline-task-list-update" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="pipelineModal" tabindex="-1" role="dialog" aria-labelledby="pipelineModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="pipelineModalTitle">Add Pipeline</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger err-div-form-pipeline">
                            <ul>

                            </ul>
                        </div>

                        <form action="/pipeline/add" method="post" id="form-pipeline">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" value="">
                            <input type="hidden" name="mode" value="">

                            <div class="form-group">
                                <label for="name">Pipeline Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Pipeline Name">
                            </div>
                            <div class="form-group">
                                <label for="type">Pipeline Type</label>
                                <select class="form-control" name="type">
                                    <option value="">Choose a Deployment Type</option>
                                    <option value="Deployment">Deployment</option>
                                    <option value="rollback">Rollback</option>
                                    <option value="build">Build</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-pipeline" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="crossorigin="anonymous"></script>

    <script>
        var taskList = $( "#updated-pipeline-task-list" ).sortable();
        $( "#updated-pipeline-task-list" ).disableSelection();

        $(document).on('click', '#btn-update-task-list',function(){
            $("#updated-pipeline-task-list").children().remove();
            $('#updated-pipeline-task-list').sortable({ refresh: taskList });
            $('.err-div-form-pipeline ul li').remove();
            $('.err-div-form-pipeline').hide();
            pipelineId = $('input[name="pipeline_id"]').val();

            url = '/pipeline/tasks/all/'+pipelineId;

            $.ajax({
                type: 'get',
                url: url,
                success: function(data){
                    if(data.pipelineTasks){
                        $.each( data.pipelineTasks, function( key, pipelineTask ){
                            addedTask = "<div data-task-command='"+pipelineTask.command+"' data-task-id='"+pipelineTask.task_id+"' class='ui-state-default'><span>"+pipelineTask.command+"</span><a href='JavaScript:void(0);' class='btn btn-danger btn-sm' id='btn-remove'>&laquo; Remove</a></div>";
                            $(addedTask).appendTo($('#updated-pipeline-task-list'));
                        });

                        $('#updated-pipeline-task-list').sortable({ refresh: taskList })
                    }
                }
            });

        });

        $(document).on('click', '#btn-remove', function(){
            $this = $(this);
            $this.parent().remove();
            $('#updated-pipeline-task-list').sortable({ refresh: taskList })
        });

        $(document).ready(function(){

            $('#btn-add').on('click', function(){
                $('#task_id_select-from option:selected').each( function() {
                    $this = $(this);
                    addedTask = "<div data-task-command='"+$this.text()+"' data-task-id='"+$this.val()+"' class='ui-state-default'><span>"+$this.text()+"</span><a href='JavaScript:void(0);' class='btn btn-danger btn-sm' id='btn-remove'>&laquo; Remove</a></div>";
                    $(addedTask).appendTo($('#updated-pipeline-task-list'));
                    $('#updated-pipeline-task-list').sortable({ refresh: taskList })
                });
            });

            $('#btn-pipeline-task-list-update').click(function(e){
                e.preventDefault();

                var taskIds = [];

                $("#updated-pipeline-task-list").children('div').each(function() {
                    taskIds.push($(this).attr('data-task-id'));
                });

                var data = {};
                data.task_ids = taskIds;
                data._token = "<?php echo csrf_token(); ?>" ;
                data.pipeline_id = $("input[name='pipeline_id']").val();

                var postData = $.param(data);

                $.ajax({
                    type: 'post',
                    url: '/pipeline/tasks/update',
                    data: postData,
                    dataType: 'json',
                    success: function(data){
                        if(data.success == 'true')
                        {
                            $('#updatePipelineTaskList').modal('hide');
                            toastr.success( 'Pipeline Tasks Updated' , "Success!");
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

            $("div.addNewPipelineButton").html("<div class='ml-1 float-xs-right'><button type='button' id='btn-show-pipeline-modal' class='btn btn-primary btn-md .dataTables_wrapper' data-toggle='modal' data-target='#pipelineModal'>Add New Pipeline </button></div>");

            $(document).on('click', '#btn-show-pipeline-modal', function(){
                $('.err-div-form-pipeline ul li').remove();
                $('.err-div-form-pipeline').hide();
                $('#pipelineModalTitle').html('Add Pipeline');
                $('input[name="mode"]').val('add');
                $('input[name="id"]').val('');
                $('input[name="name"]').val('');
            });
        });

        pipelineTable = $('#pipelines-table').DataTable({
            processing: true,
            pipelineSide: true,
            responsive: true,
            dom: '<"addNewPipelineButton">frtip',
            ajax: '/pipeline/all',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'created_at', name: 'created_at'},
                {data: '', name: ''},
            ],
            columnDefs: [ {
                "targets": -1,
                "data": null,
                "defaultContent": "<button class='btn btn-sm btn-danger btn-table-delete mr-1'>Delete</button><button class='btn btn-sm btn-success btn-table-edit mr-1' data-toggle='modal' data-target='#pipelineModal'>Edit</button><button class='btn btn-sm btn-primary btn-table-add-task' id='btn-update-task-list' data-toggle='modal' data-target='#updatePipelineTaskList'>Add/Remove Tasks</button>"
            } ]
        });


        $('#pipelines-table tbody').on( 'click', 'button', function () {

            mode = $(this).html();

            var data = pipelineTable.row( $(this).parents('tr') ).data();

            if(mode == 'Delete'){
                url = '/pipeline/delete/'+data.id;

                $.ajax({
                    type: 'get',
                    url: url,
                    success: function(data){
                        pipelineTable.ajax.reload(null,false);
                        toastr.success( 'Pipeline Deleted' , "Success!");
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

            }else if(mode == 'Edit') {
                $('.err-div-form-pipeline ul li').remove();
                $('.err-div-form-pipeline').hide();
                $('#pipelineModalTitle').html('Edit Pipeline');
                $('input[name="mode"]').val('edit');
                $('input[name="id"]').val(data.id);
                $('input[name="name"]').val(data.name);

            }else if(mode == "Add/Remove Tasks") {
                $('.err-div-form-pipeline ul li').remove();
                $('.err-div-form-pipeline').hide();
                $('input[name="pipeline_id"]').val(data.id);
            }

        });

        $('#pipelineModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });

        $('#btn-pipeline').on('click', function(e){
            e.preventDefault();

            $('.err-div-form-pipeline ul li').remove();
            $('.err-div-form-pipeline').hide();

            mode = $('input[name="mode"]').val();

            if(mode == "add"){
                url = '/pipeline/add';
            } else {
                url = '/pipeline/update';
            }

            formData = $( "#form-pipeline" ).serialize();

            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(data){
                    $('#pipelineModal').modal('hide');
                    pipelineTable.ajax.reload(null,false);
                    toastr.success( 'Pipeline Added' , "Success!");

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