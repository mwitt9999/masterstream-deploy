
@extends('layouts.default')

@section('content')

    <a href="/build/test/submit" id="btn-test-build">Build</a>

@endsection

@section('scripts')


<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.2/socket.io.min.js"></script>
<script>

    function listenForTaskResults() {
        var socketIPAddress = 'http://{{getenv('MAIN_DOCKER_IP_ADDRESS')}}:3000';
        var socket = io(socketIPAddress);

//        socket.on('terminal-output:ShowTerminalTaskResult', function(data){
//            console.log(data.output);
//        });
    }

    $(document).ready(function(){
        listenForTaskResults();

        $('#btn-test-build').on('click', function(e){
            e.preventDefault();

            $.ajax({
                type: 'get',
                url: '/build/test',
                data: {},
                success: function (data) {
                    alert(data.responseJSON)
                }
            });

        });
    });

</script>
@endsection