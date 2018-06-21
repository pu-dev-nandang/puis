<!-- Socket js -->
<script type="text/javascript" src="<?php echo base_url('simulator/node_modules/socket.io/node_modules/socket.io-client/socket.io.js');?>"></script>
<script type="text/javascript">
	// $(document).ready(function () {
		var link = "<?php echo $link ?>";
		// var socket = io.connect( link.hostname+':3000' );
		var socket = io.connect( 'http://10.1.10.230:3000' );
		  socket.emit('update_notifikasi', { 
		    update_notifikasi: '1'
		  });
	// });	
</script>