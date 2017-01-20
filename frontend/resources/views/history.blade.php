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
                    <h2>Deployment History</h2>
                </div>

                <div class="portlet-body form">
                    <table class="table table-striped" id="history-table">
                        <thead>
                        <tr>
                            <th>Server</th>
                            <th>Version</th>
                            <th>Commit Hash</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Created At</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        $('#history-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '/history/all',
            columns: [
                {data: 'server.name', name: 'server.name'},
                {data: 'version', name: 'version'},
                {data: 'commit_hash', name: 'commit_hash'},
                {data: 'type', name: 'type'},
                {data: 'status', name: 'status'},
                {data: 'user.first_name', name: 'first_name'},
                {data: 'user.last_name', name: 'last_name'},
                {data: 'created_at', name: 'created_at'},
            ]
        });

        //Datatables debugger
        // (function() {
        //     var url = '//debug.datatables.net/bookmarklet/DT_Debug.js';
        //     if (typeof DT_Debug != 'undefined') {
        //         if (DT_Debug.instance !== null) {
        //             DT_Debug.close();
        //         } else {
        //             new DT_Debug();
        //         }
        //     } else {
        //         var n = document.createElement('script');
        //         n.setAttribute('language', 'JavaScript');
        //         n.setAttribute('src', url + '?rand=' + new Date().getTime());
        //         document.body.appendChild(n);
        //     }
        // })();
    </script>

@stop