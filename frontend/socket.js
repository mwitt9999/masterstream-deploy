var server = require('http').Server();
var io = require('socket.io')(server);
var Redis = require('ioredis');
var redis = new Redis(6379, 'masterstream_deploy_redis');

redis.subscribe('terminal-output');

redis.subscribe('remove-builds');

redis.on('message', function(channel, message){
   message = JSON.parse(message);
   io.emit(channel + ':' + message.event, message.data);
});

server.listen(3000);
