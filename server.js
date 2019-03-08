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
    });

    socket.on( 'update_schedule_notifikasi', function( data ) {
        io.sockets.emit( 'update_schedule_notifikasi', {
            update_schedule_notifikasi: data.update_schedule_notifikasi,
            date :  data.date,
        });
    });

    socket.on( 'update_log', function( data ) {
        io.sockets.emit( 'update_log', {
            update_log: data.update_log
        });
    });

    socket.on( 'mobile_notif', function( data ) {
        io.sockets.emit( 'mobile_notif', {
            mobile_notif: data.mobile_notif
        });
    });

    socket.on( 'notification_student', function( data ) {
        io.sockets.emit( 'notification_student', {
            notification_student: data.notification_student
        });
    });

    socket.on( 'notification_lecturer', function( data ) {
        io.sockets.emit( 'notification_lecturer', {
            notification_lecturer: data.notification_lecturer
        });
    });


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
