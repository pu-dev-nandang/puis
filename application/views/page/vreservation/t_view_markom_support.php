<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>Podomoro University</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('images/icon/favicon.png'); ?>">
</head>
<?php echo $include; ?>
<style type="text/css">
	@media screen and (min-width: 768px)
	{
		.modal-dialog {
		    right: auto;
		    left: 50%;
		    width: 1200px;
		    padding-top: 30px;
		    padding-bottom: 30px;
		}
	}
	
</style>
<body style="background: #f2f2f2;">
<div class="container">
	<div class="row">
		<div id="login-overlay" class="modal-dialog center" style="z-index:0;max-width: 1200px;">
		    <div class="modal-content">
		        <div class="modal-body" style="padding-bottom:0px;">

		            <div class="row">
		                <div class="col-xs-12" style="text-align: center;">
		                    <img src="<?php echo url_sign_out ?>assets/icon/logo.jpg" style="max-width: 200px;">
		                    <hr/>
		                </div>
		            </div>

		            <div id="pageData">
		                                            
		            </div>
		            
		            <div class="row">
		                <div class="col-xs-12" style="text-align: center;font-size: 12px;color: #9E9E9E;">
		                    <hr style="margin-bottom:10px;" />
		                    <p>© 2018 Universitas Agung Podomoro
		                        <br/> Version 2.0.1
		                    </p>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</div>
</body>
</html>
<!-- Modal Notification -->
<div class="modal fade" id="NotificationModal" role="dialog" style="top: 100px;">
    <div class="modal-dialog" style="width: 400px;" role="document">
        <div class="modal-content animated flipInX">
            <!--            <div class="modal-header"></div>-->
            <div class="modal-body"></div>
            <!--            <div class="modal-footer"></div>-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
	var ID_t_booking = "<?php echo $ID_t_booking ?>";
	$(document).ready(function(){
		loadDataListApprove();
	});

	$(document).on('click','.btn_markom_submit', function () {
	   var idtbooking = $(this).attr('idtbooking');
	   var action = $(this).attr('action');
	   var arr_eq = [];
	   $(".MarkomSupport_"+idtbooking).each(function(){
	    if(this.checked){
	      arr_eq.push($(this).val())
	    }
	   });
	   if (arr_eq.length > 0) {
	      $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Are you sure ? </b> ' +
	          '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
	          '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
	          '</div>');
	      $('#NotificationModal').modal('show');

	      $("#confirmYes").click(function(){
	          $('#NotificationModal .modal-header').addClass('hide');
	          $('#NotificationModal .modal-body').html('<center>' +
	              '                    <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>' +
	              '                    <br/>' +
	              '                    Loading Data . . .' +
	              '                </center>');
	          $('#NotificationModal .modal-footer').addClass('hide');
	          $('#NotificationModal').modal({
	              'backdrop' : 'static',
	              'show' : true
	          });
	          var url =base_url_js+'vreservation/confirm_markom_support';
	          var data = {
	                        idtbooking : idtbooking,
	                        action    : action,
	                        arr_eq : arr_eq,
	                        auth : 's3Cr3T-G4N',
	                      };
	          var token = jwt_encode(data,'UAP)(*');
	          $.post(url,{token:token},function (data_json) {
	            var response = jQuery.parseJSON(data_json);
	            if (response == '') {
	                setTimeout(function () {
	                   toastr.options.fadeOut = 10000;
	                   toastr.success('Data berhasil disimpan', 'Success!');
	                   //loadDataListApprove();
	                   // send notification other school from client
	                   var socket = io.connect( 'http://'+window.location.hostname+':3000' );
	                   // var socket = io.connect( '<?php echo serverRoot ?>'+':3000' );
	                     socket.emit('update_schedule_notifikasi', { 
	                       update_schedule_notifikasi: '1',
	                       date : '',
	                     });
	                     $("#pageData").html('<h3 align = "center">Thank for your appreciated</h3>');
	                   $('#NotificationModal').modal('hide');
	                },500);
	            }
	            else
	              {
	                toastr.error(response, 'Failed!!');
	                $('#NotificationModal').modal('hide');
	              }
	          });
	      })
	   }
	   else
	   {
	    toastr.info('Please choose equipment');
	   }
	   
	});

	function loadDataListApprove()
	{
	    $("#pageData").empty();
	    loading_page('#pageData');
	    var html_table ='<div class="col-md-12">'+
	                     '<div class="table-responsive">'+
	                        '<table class="table table-bordered table-hover table-checkable datatable">'+
	                            '<thead>'+
	                                '<tr>'+
	                               ' <th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Start</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">End</th>'+
	                               //' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Time</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Agenda</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Room</th>'+
	                               //' <th style = "text-align: center;background: #20485A;color: #FFFFFF;" width = "40%">Equipment</th>'+
	                               // ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;" width = "15%">Support</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;" width = "35%">Markom</th>'+
	                               //' <th style = "text-align: center;background: #20485A;color: #FFFFFF;" width = "15%">Participant</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Status</th>'+
	                               //' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Layout</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Requester</th>'+
	                                '</tr>'+
	                           ' </thead>'+
	                            '<tbody>'+
	                            '</tbody>'+
	                        '</table>'+
	                     '</div>'+   
	                    '</div>';
	    var url = base_url_js+'api/vreservation/json_list_booking';
	    var data = {
	                auth : 's3Cr3T-G4N',
	                ID_t_booking : ID_t_booking,
	                };
	    var token = jwt_encode(data,"UAP)(*");
	    $.post(url,{token:token},function (response) {
	        setTimeout(function () {
	           $("#pageData").html(html_table);
	           for (var i = 0; i < response.length; i++) {
	            var btn  = ''  ;
	            var Req_layout = (response[i]['Req_layout'] == '') ? 'Default' : '<a href="javascript:void(0)" class="btn-action btn-get-link2" data-page="fileGetAny/vreservation-'+response[i]['Req_layout']+'">Request Layout</a>'; 
	            var tr = '<tr idtbooking = "'+response[i]['ID']+'" >';
	            var No = parseInt(i)+1;
	            $(".datatable tbody").append(
	                tr+
	                    '<td>'+No+'</td>'+
	                    '<td>'+response[i]['Start']+'</td>'+
	                    '<td>'+response[i]['End']+'</td>'+
	                    //'<td>'+response[i]['Time']+'</td>'+
	                    '<td>'+response[i]['Agenda']+'</td>'+
	                    '<td>'+response[i]['Room']+'</td>'+
	                    //'<td>'+response[i]['Equipment_add']+'</td>'+
	                    // '<td>'+response[i]['Persone_add']+'</td>'+
	                    '<td>'+response[i]['MarkomSupport']+'</td>'+
	                    //'<td>'+response[i]['Participant']+'</td>'+
	                    '<td>'+response[i]['StatusBooking']+'</td>'+
	                    //'<td>'+Req_layout+'</td>'+
	                    '<td>'+response[i]['Req_date']+'</td>'+
	                '</tr>' 
	                );
	        }
	        // LoaddataTable('.datatable');
	        $(".datatable").DataTable({
	            'iDisplayLength' : 10,
	            'ordering' : false,
	            });
	        },500);

	    });
	}
</script>

