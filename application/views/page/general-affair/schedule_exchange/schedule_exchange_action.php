
<style>
    #tableList thead tr th {
        text-align: center;
        background-color: #436888;
        color: #ffffff;
    }
    #tableList tbody tr td {
        text-align: center;
    }
</style>
<div id = "loadPage">
	
</div>
<script type="text/javascript">
	$(document).ready(function(){
		LoadFirst();
	})

	$(document).on('change','.filterSP',function () {
	    loadDatatable();
	});

	function LoadFirst()
	{
		var html = '';
		html = '<div class = "row">'+
					'<div class = "col-md-3 col-md-offset-3">'+
						'<label>Semester</label>'+
						'<select class="form-control filterSP" id="filterSemester"></select>'+
					'</div>'+
					'<div class = "col-md-3">'+
						'<label>Status</label>'+
						'<select class="form-control filterSP" id="filterStatus">'+
							'<Option value = "1" selected>Need Approve</option>'+
							'<Option value = "2">Approve</option>'+
							'<Option value = "-2">Reject</option>'+
						'</select>'+
					'</div>'+
				'</div>'		
				;
		
		$("#loadPage").html(html);

		loSelectOptionSemester('#filterSemester','');

		var bool = 0;
		var urlInarray = [base_url_js+'api/__crudSemester'];

		$( document ).ajaxSuccess(function( event, xhr, settings ) {
		   if (jQuery.inArray( settings.url, urlInarray )) {
		       bool++;
		       if (bool == 1) {
		           setTimeout(function(){ loadDatatable(); }, 500);
		          
		       }
		   }
		});
		

	}

	function loadDatatable()
	{
		var Semester = $("#filterSemester").val();
		Semester = Semester.split('.');
		Semester = Semester[0];

		var Status = $("#filterStatus").val();

		var data = {
			auth : 's3Cr3T-G4N',
			Status : Status,
			Semester : Semester,
		}
		var token = jwt_encode(data,'UAP)(*');   
		var html = '<div class = "row" style = "margin-top : 10px" id = "pagetable">'+
					'<div class = "col-md-12">'+
						'<div class = "table-responsive">'+
							'<table class="table table-bordered table-striped" id="tableList">'+
								'<thead>'+
									'<tr>'+
										'<th rowspan="2">No</th>'+
										'<th rowspan="2">Dosen</th>'+
										'<th rowspan="2">Program Studi</th>'+
										'<th colspan="6">Mengajukan permohonan untuk kuliah pengganti</th>'+
										'<th rowspan = "2" width = "20%">Status</th>'+
										'<th rowspan = "2" width = "20%">Action</th>'+
									'</tr>'+
									'<tr>'+
										'<th>Mata Kuliah</th>'+
										'<th>Group Kelas</th>'+
										'<th>Pertemuan Ke</th>'+
										'<th>Jadwal Semula</th>'+
										'<th>Diganti Pada </th>'+
										'<th>Alasan</th>'+
									'</tr>'+
								'</thead>'+
								'<tbody></tbody>'+
							'</table>'+
						'</div>'+
					'</div>'+
				'</div>';

				if ($("#pagetable").length) {
					$("#pagetable").remove();
				}

				$("#loadPage").append(html);

				$.fn.dataTable.ext.errMode = 'throw';
				//alert('hsdjad');
				$.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
				          {
				              return {
				                  "iStart": oSettings._iDisplayStart,
				                  "iEnd": oSettings.fnDisplayEnd(),
				                  "iLength": oSettings._iDisplayLength,
				                  "iTotal": oSettings.fnRecordsTotal(),
				                  "iFilteredTotal": oSettings.fnRecordsDisplay(),
				                  "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
				                  "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
				              };
				          };

				var dataTable = $('#tableList').DataTable( {
				    "processing": true,
				    "destroy": true,
				    "serverSide": true,
				    "iDisplayLength" : 10,
				    "ordering" : false,
				    "ajax":{
				        url : base_url_js+"rest/ga/__show_schedule_exchange", // json datasource
				        data : {token : token},
				        ordering : false,
				        type: "post",  // method  , by default get
				        error: function(){  // error handling
				            $(".employee-grid-error").html("");
				            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				            $("#employee-grid_processing").css("display","none");
				        }
				    },
				    'createdRow': function( row, data, dataIndex ) {
				        /*var no = 'row'+(dataIndex + 1);
				          $(row).attr('id', no);*/
				    },
				} );					
					
	}

	$(document).on('click','.btnreject',function () {
	    var token = $(this).attr('token');
	    var emailrequest = $(this).attr('emailrequest');
	    var emailkaprodi = $(this).attr('emailkaprodi');
	    var scheduleexchangeid = $(this).attr('scheduleexchangeid');
	    var td = $(this).closest('td');
	    var tdhtml = td.html();
	    td.attr('style','width : 350px;');
	    td.attr('tdhtml',tdhtml);
	    td.attr('token',token);
	    td.attr('emailrequest',emailrequest);
	    td.attr('emailkaprodi',emailkaprodi);
	    td.attr('scheduleexchangeid',scheduleexchangeid);
	    var btnbackward = '<div class = "row"><div class = "col-md-1"><button class = "btn-sm btn-primary back"><i class="fa fa-backward" aria-hidden="true"></i></button></div></div>';
	    var InputReason = '<div class = "row" style = "margin-top : 5px"><div class = "col-md-12"><label>Reason</label><input type = "text" class = "form-control InputReason" scheduleexchangeid = "'+scheduleexchangeid+'"></div></div>';
	    var btnsave = '<div class = "row" style = "margin-top : 5px"><div class = "col-md-12" align = "right"><button class = "btn btn-success btnsavedata" status = "-2" scheduleexchangeid = "'+scheduleexchangeid+'">Save</button></div></div>';
	    td.html(btnbackward+InputReason+btnsave);
	    $(".back").click(function(){
	    	var newtd = $(this).closest('td');
	    	var newtdhtml = newtd.attr('tdhtml');
	    	newtd.html(newtdhtml);
	    })

	    $('.btnsavedata[scheduleexchangeid="'+scheduleexchangeid+'"]').click(function(){
	    	if (confirm('Are you sure ?')) {
    			var newtd = $(this).closest('td');
    			var newtr = $(this).closest('tr');
    			var token = newtd.attr('token');
    			var emailrequest = newtd.attr('emailrequest');
    			var emailkaprodi = newtd.attr('emailkaprodi');
    			var scheduleexchangeid = newtd.attr('scheduleexchangeid');
    			var status = $(this).attr('status');
    			var reason = $('.InputReason[scheduleexchangeid="'+scheduleexchangeid+'"]').val();
    			if (reason == '') {toastr.error('Reason is required');return;}
    			loading_button('.btnsavedata[scheduleexchangeid="'+scheduleexchangeid+'"]');
    			var url = base_url_js+'ga/scheduleexchange/submit_change_status';
    		    var data = {
    		        token : token,
    		        emailrequest: emailrequest,
    		        emailkaprodi : emailkaprodi,
    		        scheduleexchangeid : scheduleexchangeid,
    		        reason : reason,
    		        status : status,
    		    }
    		    var token = jwt_encode(data,'UAP)(*');
    			$.post(url,{token:token},function (data_json) {
    				var response = jQuery.parseJSON(data_json);
    				if (response == '') {
    					var StatusWr = '';
    					if (status == '-2') {
    						newtr.find('td:eq(9)').html('Reject'+'<br>'+reason);
    						// var newtdhtml = newtd.attr('tdhtml');
    						// newtd.html(newtdhtml);
    						newtd.html('');
    					}
    					else if(status == 2)
    					{
    						newtr.find('td:eq(9)').html('Approve');
    						newtd.html('');
    					}
    				}
    				$('.btnsavedata[scheduleexchangeid="'+scheduleexchangeid+'"]').prop('disabled',false).html('Save');
    			}).done(function() {
    			          
    		    }).fail(function() {
    		      toastr.error('The Database connection error, please try again', 'Failed!!');
    		    });
	    	}
	    	
	    })
	});

	$(document).on('click','.btnapprove',function () {
	    var token = $(this).attr('token');
	    var emailrequest = $(this).attr('emailrequest');
	    var emailkaprodi = $(this).attr('emailkaprodi');
	    var scheduleexchangeid = $(this).attr('scheduleexchangeid');
	    var td = $(this).closest('td');
	    var tdhtml = td.html();
	    td.attr('style','width : 350px;');
	    td.attr('tdhtml',tdhtml);
	    td.attr('token',token);
	    td.attr('emailrequest',emailrequest);
	    td.attr('emailkaprodi',emailkaprodi);
	    td.attr('scheduleexchangeid',scheduleexchangeid);
	    var btnbackward = '<div class = "row"><div class = "col-md-1"><button class = "btn-sm btn-primary back"><i class="fa fa-backward" aria-hidden="true"></i></button></div></div>';
	    var InputSelectRoom = '<div class ="row" style = "margin-top : 5px"><div class = "col-md-12"><select class = "select2-select-00 full-width-fix SelectRoom" scheduleexchangeid = "'+scheduleexchangeid+'"></select></div></div>';
	    var btnsave = '<div class = "row" style = "margin-top : 5px"><div class = "col-md-12" align = "right"><button class = "btn btn-success btnsavedata" status = "2" scheduleexchangeid = "'+scheduleexchangeid+'">Save</button></div></div>';
	    td.html(btnbackward+InputSelectRoom+btnsave);

	    	var url = base_url_js+'api/__crudClassroom';
	        var data = {
	            action : 'read',
	        }
	        var token = jwt_encode(data,'UAP)(*');
	    	$.post(url,{token:token},function (data_json) {
	    		$('.SelectRoom[scheduleexchangeid="'+scheduleexchangeid+'"]').empty();
	    		for (var i = 0; i < data_json.length; i++) {
	    			var selected =(i==0) ? 'selected' : '';
	    			$('.SelectRoom[scheduleexchangeid="'+scheduleexchangeid+'"]').append(
	    					'<option value = "'+data_json[i].ID+'" '+selected+'>'+data_json[i].Room+'</option>'
	    				);
	    		}

	    		$('.SelectRoom[scheduleexchangeid="'+scheduleexchangeid+'"]').select2({
	    		   //allowClear: true
	    		});
	    	}).done(function() {
	    	          
	        }).fail(function() {
	          toastr.error('The Database connection error, please try again', 'Failed!!');
	        });

	    $(".back").click(function(){
	    	var newtd = $(this).closest('td');
	    	var newtdhtml = newtd.attr('tdhtml');
	    	newtd.html(newtdhtml);
	    })

	    $('.btnsavedata[scheduleexchangeid="'+scheduleexchangeid+'"]').click(function(){
	    	if (confirm('Are you sure ?')) {
    			var newtd = $(this).closest('td');
    			var newtr = $(this).closest('tr');
    			var token = newtd.attr('token');
    			var emailrequest = newtd.attr('emailrequest');
    			var emailkaprodi = newtd.attr('emailkaprodi');
    			var scheduleexchangeid = newtd.attr('scheduleexchangeid');
    			var status = $(this).attr('status');
    			var Room = $('.SelectRoom[scheduleexchangeid="'+scheduleexchangeid+'"]').val();
    			var roomname = $('.SelectRoom[scheduleexchangeid="'+scheduleexchangeid+'"] option:selected').text();
    			loading_button('.btnsavedata[scheduleexchangeid="'+scheduleexchangeid+'"]');
    			var url = base_url_js+'ga/scheduleexchange/submit_change_status';
    		    var data = {
    		        token : token,
    		        emailrequest: emailrequest,
    		        emailkaprodi : emailkaprodi,
    		        scheduleexchangeid : scheduleexchangeid,
    		        Room : Room,
    		        status : status,
    		        roomname : roomname,
    		    }
    		    var token = jwt_encode(data,'UAP)(*');
    			$.post(url,{token:token},function (data_json) {
    				var response = jQuery.parseJSON(data_json);
    				if (response == '') {
    					var StatusWr = '';
    					if (status == '-2') {
    						newtr.find('td:eq(9)').html('Reject');
    						// var newtdhtml = newtd.attr('tdhtml');
    						// newtd.html(newtdhtml);
    						newtd.html('');
    					}
    					else if(status == 2)
    					{
    						newtr.find('td:eq(9)').html('Approve');
    						var st = newtr.find('td:eq(7)').html();
    						newtr.find('td:eq(7)').html(st+' | '+roomname);
    						newtd.html('');
    					}
    				}
    				$('.btnsavedata[scheduleexchangeid="'+scheduleexchangeid+'"]').prop('disabled',false).html('Save');
    			}).done(function() {
    			          
    		    }).fail(function() {
    		      toastr.error('The Database connection error, please try again', 'Failed!!');
    		    });
	    	}
	    	
	    })
	});
</script>