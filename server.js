var socket  = require( 'socket.io' );
var express = require('express');
var app     = express();
var server  = require('http').createServer(app);
var io      = socket.listen( server );
var port    = process.env.PORT || 3000;

server.listen(port, function () {
  console.log('Server listening at port %d', port);
});


io.on('connection', function (socket) {

  socket.on( 'update_notifikasi', function( data ) {
      io.sockets.emit( 'update_notifikasi', {
        update_notifikasi: data.update_notifikasi 
      });
  })

  socket.on( 'update_schedule_notifikasi', function( data ) {
      io.sockets.emit( 'update_schedule_notifikasi', {
        update_schedule_notifikasi: data.update_schedule_notifikasi,
        date :  data.date,
      });
  })
  
  /*socket.on( 'new_message', function( data ) {
    io.sockets.emit( 'new_message', {
    	name: data.name,
    	email: data.email,
    	subject: data.subject,
    	created_at: data.created_at,
    	id: data.id
    });
  });*/

  
});
