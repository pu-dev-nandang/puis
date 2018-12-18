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
	var jsData = <?php echo json_encode($data) ?>;
	$(document).ready(function(){
		loadDataInTable();
		console.log(jsData);
	});

	function loadDataInTable()
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
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Time</th>'+
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Agenda</th>'+
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Room</th>'+
		                           ' <th width = "40%" style = "text-align: center;background: #20485A;color: #FFFFFF;">Feedback</th>'+
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Action</th>'+
		                            '</tr>'+
		                       ' </thead>'+
		                        '<tbody>'+
		                        '</tbody>'+
		                    '</table>'+
		                 '</div>'+   
		                '</div>';
		$("#pageData").html(html_table);
		for (var i = 0; i < jsData.length; i++) {
            var btn  = '<button type="button" class="btn btn-success btn-save" id_key = "'+jsData[i]['ID']+'">Save</button>'  ;
            var No = parseInt(i)+1;
            var txtFeeback = (jsData[i]['Feedback'] == null || jsData[i]['Feedback'] == '') ? '' : jsData[i]['Feedback'];
            var Feedback = '<textarea class="form-control feedback" id_key = "'+jsData[i]['ID']+'">'+txtFeeback+'</textarea>';
            var tr = '<tr idtbooking = "'+jsData[i]['ID']+'" >';
            $(".datatable tbody").append(
                tr+
                    '<td>'+No+'</td>'+
                    '<td>'+jsData[i]['Start']+'</td>'+
                    '<td>'+jsData[i]['End']+'</td>'+
                    '<td>'+jsData[i]['Time']+'</td>'+
                    '<td>'+jsData[i]['Agenda']+'</td>'+
                    '<td>'+jsData[i]['Room']+'</td>'+
                    '<td>'+Feedback+'</td>'+
                    '<td>'+btn+'</td>'+
                '</tr>' 
                );
        }
        // LoaddataTable('.datatable');
        // $(".datatable").DataTable({
        //     'iDisplayLength' : 10,
        //     'ordering' : false,
        //     });
	}

	$(document).on('click','.btn-save', function () {
		var id_key = $(this).attr('id_key');
		var Feedback = $('.feedback[id_key = "'+id_key+'"]').val();
		var url =base_url_js+'vreservation/api-feedback';
	      var data = {
	                    id_key : id_key,
	                    Feedback    : Feedback,
	                    auth : 's3Cr3T-G4N',
	                  };
	      var token = jwt_encode(data,'UAP)(*');
	      $.post(url,{token:token},function (data_json) {
	      	toastr.success('Thanks you for your feedback');
	      	$('.btn-save[id_key="'+id_key+'"]')
	      	.closest('tr')
	      	.remove();

	      	if (!$('.btn-save').length) {
	      		$("#pageData").html('<h3 align = "center">Thank for your appreciated</h3>');
	      	}

		  });
		
	});
</script>

