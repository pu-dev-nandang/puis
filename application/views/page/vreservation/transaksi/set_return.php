<div id="pageData" class="btn-read">
                
</div>
<script type="text/javascript">
	$(document).ready(function () {
	    loadDataListApprove()
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
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Action</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Start</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">End</th>'+
	                               //' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Time</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Agenda</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Room</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;" width = "25%">Equipment</th>'+
	                               // ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;" width = "15%">Support</th>'+
	                               // ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;" width = "15%">Markom</th>'+
	                               //' <th style = "text-align: center;background: #20485A;color: #FFFFFF;" width = "15%">Participant</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Status</th>'+
	                               //' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Layout</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Requester</th>'+
	                               ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Detail</th>'+
	                                '</tr>'+
	                           ' </thead>'+
	                            '<tbody>'+
	                            '</tbody>'+
	                        '</table>'+
	                     '</div>'+   
	                    '</div>';
	    var url = base_url_js+'vreservation/return_eq';
	    $.post(url,function (data_json) {
	        setTimeout(function () {
	            var response = jQuery.parseJSON(data_json);
	            console.log(response);
	            var NIP = "<?php echo $this->session->userdata('NIP') ?>"
	           $("#pageData").html(html_table);
	           for (var i = 0; i < response.length; i++) {
	            var btn  = '<span class="btn btn-primary return_eq" data ="'+response[i]['ID']+'" >'+
	                                      '<i class="fa fa-hand-o-right"></i> Return'+
	                                     '</span>'  ;
	            var Req_layout = (response[i]['Req_layout'] == '') ? 'Default' : '<a href="javascript:void(0)" class="btn-action btn-get-link2" data-page="fileGetAny/vreservation-'+response[i]['Req_layout']+'">Request Layout</a>'; 
	            var tr = '<tr idtbooking = "'+response[i]['ID']+'" >';
	            var No = parseInt(i)+1;
	            var Detail = '<span class="btn btn-primary Detail" data ="'+response[i]['Detail']+'" >'+
	                                      '<i class="fa fa-search"></i> Detail'+
	                                     '</span>';
	            $(".datatable tbody").append(
	                tr+
	                    '<td>'+No+'</td>'+
	                    '<td>'+btn+'</td>'+
	                    '<td>'+response[i]['Start']+'</td>'+
	                    '<td>'+response[i]['End']+'</td>'+
	                    //'<td>'+response[i]['Time']+'</td>'+
	                    '<td>'+response[i]['Agenda']+'</td>'+
	                    '<td>'+response[i]['Room']+'</td>'+
	                    '<td>'+response[i]['Equipment_add']+'</td>'+
	                    // '<td>'+response[i]['Persone_add']+'</td>'+
	                    // '<td>'+response[i]['MarkomSupport']+'</td>'+
	                    //'<td>'+response[i]['Participant']+'</td>'+
	                    '<td>'+response[i]['StatusBooking']+'</td>'+
	                    //'<td>'+Req_layout+'</td>'+
	                    '<td>'+response[i]['Req_date']+'</td>'+
	                    '<td>'+Detail+'</td>'+
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

	$(document).on('click','.Detail', function () {
	   var dataJson = $(this).attr('data');
	   var dtarr = dataJson.split('@@');
	   var room = dtarr[6];
	   var tgl = dtarr[10];;
	   var time =  dtarr[1];
	   modal_generate('view','Form Booking Reservation',room,time,tgl,'',dtarr);
	});

	$(document).on('click','.return_eq', function () {
	   var ID = $(this).attr('data');
	   var url = base_url_js+"vreservation/modal_form_return_eq";
	   var data = {
	       ID : ID,
	   };
	   var token = jwt_encode(data,"UAP)(*");
	   $.post(url,{ token:token }, function (data_json) {
	    var response = jQuery.parseJSON(data_json);
	    if (response.length == 0) {
	      var html = '<h3 align = "center">No Equipment Confirmed from user</h3>';
	    }
	    else
	    {

	      var html = '';
	      for (var i = 0; i < response.length; i++) {
	        html += '<div class = "row id_'+response[i]['IDTable']+'">'+
	                      '<div class = "col-xs-3" style = "margin-top : 25px">'+
	                        '<div class="form-group"><label>'+response[i]['Name']+'</label>'+
	                        '</div>'+
	                      '</div>'+
	                      '<div class = "col-xs-3" style = "margin-top : 25px">'+
	                        '<div class="form-group"><label>Qty :'+response[i]['Qty']+'</label>'+
	                        '</div>'+
	                      '</div>'+
	                      '<div class = "col-xs-3">'+
	                        '<div class="form-group"><label>Desc</label><textarea class="form-control Desc" idtable = "'+response[i]['IDTable']+'"></textarea>'+
	                        '</div>'+
	                      '</div>'+
	                      '<div class = "col-xs-3" style = "margin-top : 25px">'+
	                        '<div class="form-group"><button type="button" class="btn btn-success btnreturn" idtable = "'+response[i]['IDTable']+'">Save</button>'+
	                        '</div>'+
	                      '</div>'+
	                '</div>';         
	      }
	    }
	    // console.log(response);
	       $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Form Return Equipment'+'</h4>');
	       $('#GlobalModalLarge .modal-body').html(html);
	       $('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>');
	       $('#GlobalModalLarge').modal({
	           'show' : true,
	           'backdrop' : 'static'
	       });


	       $(".btnreturn").click(function(){
	               var url = base_url_js+"vreservation/modal_form_return_eq_save";
	               var IDTable = $(this).attr('idtable');
	               loading_button('.btnreturn[idtable="'+IDTable+'"]');
	               var Desc = $('.Desc[idtable = "'+IDTable+'"]').val();
	               var data = {
	                   ID : ID,
	                   IDTable : IDTable,
	                   Desc : Desc,
	               };
	               var token = jwt_encode(data,"UAP)(*");
	               $.post(url,{ token:token }, function (data_json) {
	               	$('.id_'+IDTable).remove();
	               	loadDataListApprove();
	               	$('#GlobalModalLarge .modal-body').html('Done');
	               	
	               })

	       })
	   })
	});
</script>