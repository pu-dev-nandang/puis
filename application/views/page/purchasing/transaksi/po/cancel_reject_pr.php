<style type="text/css">
	.thumbnail {
	    display: inline-block;
	    display: block;
	    height: auto;
	    max-width: 100%;
	    padding: 16px;
	    line-height: 1.428571429;
	    background-color: #fff;
	    border: 1px solid #8c4343;
	    border-radius: 20px;
	    -webkit-transition: all .2s ease-in-out;
	    transition: all .2s ease-in-out;
	}

	#datatablesServer.dataTable tbody tr:hover {
	   background-color:#71d1eb !important;
	   cursor: pointer;
	}

	h3.header-blue {
	    margin-top: 0px;
	    border-left: 7px solid #2196F3;
	    padding-left: 10px;
	    font-weight: bold;
	}
</style>
<div class="row">
	<div class="col-xs-4">
		<div class="thumbnail">
			<div id = "page_pr_list"></div>
		</div>	
	</div>
	<div class="col-xs-8">
		<div class="thumbnail">
			<div id = "page_pr_item_list"></div>
		</div>	
	</div>
</div>

<script type="text/javascript">
	var ClassDt = {
		ThisTableSelect : '',
		htmlPage_pr_list : function(){
			var html = '';
			html = '<div class = "row" style = "margin-right : 0px;margin-left:0px;">'+
					 '<div class col-md-12>'+
					 	'<div style="padding: 5px;">'+
					 		'<h3 class="header-blue">Choose PR</h3>'+
					 	'</div>'+
					 	'<div class = "table-responsive">'+
					 	'<table class="table table-bordered datatable2" id = "tableData_pr">'+
					 		'<thead>'+
					 			'<tr>'+
					 				'<th width = "3%" style = "text-align: center;background: rgb(90, 32, 32);color: #FFFFFF;">No</th>'+
					 				'<th style = "text-align: center;background: rgb(90, 32, 32);color: #FFFFFF;">PR Code</th>'+
					 				'<th style = "text-align: center;background: rgb(90, 32, 32);color: #FFFFFF;">Department</th>'+
					 				'<th style = "text-align: center;background: rgb(90, 32, 32);color: #FFFFFF;">Action</th>'+
					 			'</tr>'+
					 		'<thead>'+
					 		'<tbody id="dataRow" style="background-color: #8ED6EA;"></tbody>'+
		        		'</table>'+
		        		'</div>'+
		        	 '</div>'+
		        	'</div>';

		    return html;    	 				
		},	
	};

	$(document).ready(function() {
	    $('#page_pr_list').html(ClassDt.htmlPage_pr_list);
	    skip_error_dt_table();
		    Get_data_pr().then(function(data){
		    	loadingEnd(500);
		    	$('.C_radio_pr:first').prop('checked',true);
		    	$('.C_radio_pr:first').trigger('change');
		        
		    })
	}); // exit document Function

	function Get_data_pr(){
	   var action_edit = ''
       var def = jQuery.Deferred();
       var data = {
           PurchasingStatus : '!=2',
           auth : 's3Cr3T-G4N',
           Item_pending : '>0',
           action_edit : action_edit,
       };
       var token = jwt_encode(data,"UAP)(*");
       	var table = $('#tableData_pr').DataTable({
       		"fixedHeader": true,
       	    "processing": true,
       	    "destroy": true,
       	    "serverSide": true,
       	    "iDisplayLength" : 10,
       	    "ordering" : false,
       	    "ajax":{
       	        url : base_url_js+"rest/__get_data_pr/2", // json datasource
       	        ordering : false,
       	        type: "post",  // method  , by default get
       	        data : {token : token},
       	        error: function(){  // error handling
       	            $(".employee-grid-error").html("");
       	            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
       	            $("#employee-grid_processing").css("display","none");
       	            def.reject();

       	        },
       	    },
       	    "columns": [
       	                { "data": "No" },
       	                { "data": "PRCode" },
       	                { "data": "NameDepartement" },
       	                { "data": "NameDepartement" },
       	    ],
       	    'createdRow': function( row, data, dataIndex ) {
       	    		$(row).find('td:eq(1)').html('<label><input type="radio" name="optradio" prcode = "'+data['PRCode']+'" class = "C_radio_pr">&nbsp'+data['PRCode']+'</label>');
       	    		$( row ).find('td:eq(2)').attr('align','center');
       	    		$( row ).find('td:eq(3)').html('<button class = "btn btn-danger reject_pr" prcode = "'+data['PRCode']+'"><i class="fa fa-window-restore" aria-hidden="true"></i> Reject </button>');
       	    },
       	    "initComplete": function(settings, json) {
       	        def.resolve(json);
       	    }
       	});
       return def.promise();
	}

	function skip_error_dt_table()
	{
		$.fn.dataTable.ext.errMode = 'throw';
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
	}

	$(document).off('change', '.C_radio_pr:checked').on('change', '.C_radio_pr:checked',function(e) {
		var PRCode = $(this).attr('prcode');
		var url = base_url_js+'budgeting/GetDataPR';
   		var data = {
   		    PRCode : PRCode,
   		};
   		var token = jwt_encode(data,"UAP)(*");
   		$.post(url,{ token:token },function () {

		}).done(function(data_json) {
			data_json = jQuery.parseJSON(data_json);
	    	MakeDom_page_pr_item_list(data_json);
	    });

	})

	function MakeDom_page_pr_item_list(dt)
	{
		var pr_detail = dt.pr_detail;
		var IsiInputPR = '';
		var Dt_selection = ClassDt.Dt_selection;
		for (var i = 0; i < pr_detail.length; i++) {

			// for detail catalog
				var Desc = pr_detail[i]['Desc'];
				var EstimaValue = pr_detail[i]['EstimaValue'];
				var arr_Photo = pr_detail[i]['Photo'];
				htmlPhoto = '';
				if (arr_Photo != '' && arr_Photo != undefined && arr_Photo != null) {
					arr_Photo = arr_Photo.split(',');
					htmlPhoto = '<ul>';
					for (var j = 0; j < arr_Photo.length; j++) {
						htmlPhoto += '<li><a href = "'+base_url_js+'fileGetAny/budgeting-catalog-'+arr_Photo[j]+'" target="_blank">'+
											arr_Photo[j]+'</a></li>';
					}
					htmlPhoto += '</ul>';
				}
				
				var DetailCatalog = jQuery.parseJSON(pr_detail[i]['DetailCatalog']);
				var htmlDetailCatalog = '';
				for (var prop in DetailCatalog) {
					htmlDetailCatalog += prop + ' :  '+DetailCatalog[prop]+'<br>';
				}
				var Item = pr_detail[i]['Item'];
				var arr = Item+'@@'+Desc+'@@'+EstimaValue+'@@'+htmlPhoto+'@@'+htmlDetailCatalog;
				arr = findAndReplace(arr, "\"","'");

				var SpecAdd = (pr_detail[i]['Spec_add'] == '' || pr_detail[i]['Spec_add'] == null || pr_detail[i]['Spec_add'] == 'null') ? '' : pr_detail[i]['Spec_add'];
				var Need = (pr_detail[i]['Need'] == '' || pr_detail[i]['Need'] == null || pr_detail[i]['Need'] == 'null') ? '' : pr_detail[i]['Need'];
			
			var btn_cancel = '<button class = "btn btn-danger cancel_item_pr" id_pr_detail = "'+pr_detail[i].ID+'"> <i class="fa fa-times" aria-hidden="true"></i> Cancel </button>';
				// pr_detail[i]['ID']
			IsiInputPR += '<tr>'+
							'<td>'+(i+1)+'</td>'+
							'<td>'+pr_detail[i]['Item']+'</td>'+
							'<td>'+'<button class = "btn btn-primary Detail" data = "'+arr+'">Detail</button>'+'</td>'+
							'<td>'+	SpecAdd+'</td>'+
							'<td>'+	Need+'</td>'+
							'<td>'+	pr_detail[i]['Qty']+'</td>'+
							'<td>'+	formatRupiah(pr_detail[i]['UnitCost'])+'</td>'+
							'<td>'+	parseInt(pr_detail[i]['PPH'])+'</td>'+
							'<td>'+	formatRupiah(pr_detail[i]['SubTotal'])+'</td>'+
							'<td>'+	pr_detail[i]['DateNeeded']+'</td>'+
							'<td style = "text-align: center;">'+btn_cancel+'</td>'+
						  '</tr>';	

		}

		var  htmlInputPR= '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_PR">'+
							'<div style="padding: 5px;">'+
								'<h3 class="header-blue">'+$('.C_radio_pr:checked').attr('prcode')+'</h3>'+
							'</div>'+
							'<div class = "col-md-12">'+
								'<div class="table-responsive">'+
									'<table class="table table-bordered tableData" id ="table_data_pr_detail">'+
										'<thead>'+
										'<tr>'+
											'<th width = "3%" style = "text-align: center;background: #608b6f;color: #FFFFFF;">No</th>'+
				                            '<th style = "text-align: center;background: #608b6f;color: #FFFFFF;width : 150px;">Catalog</th>'+
				                            '<th style = "text-align: center;background: #608b6f;color: #FFFFFF;">Detail</th>'+
				                            '<th style = "text-align: center;background: #608b6f;color: #FFFFFF;">Spec+</th>'+
				                            '<th style = "text-align: center;background: #608b6f;color: #FFFFFF;">Desc</th>'+
				                            '<th width = "4%" style = "text-align: center;background: #608b6f;color: #FFFFFF;width : 78px;">Qty</th>'+
				                            '<th style = "text-align: center;background: #608b6f;color: #FFFFFF;width : 150px;">Cost</th>'+
				                            '<th style = "text-align: center;background: #608b6f;color: #FFFFFF;width : 78px;">PPN(%)</th>'+
				                            '<th style = "text-align: center;background: #608b6f;color: #FFFFFF;width : 150px;">Sub Total</th>'+
				                            '<th width = "150px" style = "text-align: center;background: #608b6f;color: #FFFFFF;">Date Needed</th>'+
				                            '<th width = "150px" style = "text-align: center;background: #608b6f;color: #FFFFFF;">Action</th>'+
										'</tr>'+
										'</thead>'+
										'<tbody style = "background-color : #eade8e;">'+IsiInputPR+'</tbody></table>'+
									'</div>'+
								'</div>'+
							'</div>';

		$('#page_pr_item_list').html(htmlInputPR);
	}

	$(document).off('click', '.Detail').on('click', '.Detail',function(e) {
		var data = $(this).attr('data');
		var arr = data.split('@@');
		var html = '';
			html ='<div class = "row">'+
					'<div class = "col-md-12">'+
						'<table id="example" class="table table-bordered display select" cellspacing="0" width="100%">'+
               '<thead>'+
                  '<tr>'+
                     '<th>Item</th>'+
                     '<th>Desc</th>'+
                     '<th>Estimate Value</th>'+
                     '<th>Photo</th>'+
                     '<th>DetailCatalog</th>'+
                  '</tr>'+
               '</thead>'+
               '<tbody><tr>';
               		for (var i = 0; i < arr.length; i++) {
               			var v = (i == 2) ? formatRupiah(arr[i]) : arr[i];
               			html += '<td>'+v+'</td>';
               		}
               		html += '</tr></tbody>';
         html += '</table></div></div>';
		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Catalog'+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});
	})

	$(document).off('click', '.reject_pr').on('click', '.reject_pr',function(e) {
		var PRCode = $(this).attr('prcode');
		var NIP = sessionNIP;
		var url = base_url_js+'rest2/__reject_pr_from_another';
   		
   		if (confirm('Are you sure ?')) {
	   		$('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Please Input Reason ! </b> <br>' +
	   		    '<input type = "text" class = "form-group" id ="NoteDel" style="margin: 0px 0px 15px; height: 30px; width: 329px;" maxlength="100"><br>'+
	   		    '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
	   		    '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
	   		    '</div>');
	   		$('#NotificationModal').modal('show');
	   		$("#confirmYes").click(function(){
	   			var NoteDel = $("#NoteDel").val();
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
	   			var data = {
	   			    PRCode : PRCode,
	   			    auth : 's3Cr3T-G4N',
	   			    NIP : NIP,
	   			    NoteDel : NoteDel,
	   			};
	   			var token = jwt_encode(data,"UAP)(*");
		   		$.post(url,{ token:token },function () {

				}).done(function(data_json) {
					if (data_json == '') {
					    $('#page_pr_list').html(ClassDt.htmlPage_pr_list);
					    skip_error_dt_table();
						    Get_data_pr().then(function(data){
						    	loadingEnd(500);
						    	$('.C_radio_pr:first').prop('checked',true);
						    	$('.C_radio_pr:first').trigger('change');
						    })
					}
					else
					{
						toastr.info(data_json);
					}

					$('#NotificationModal').modal('hide');
				    
			    });
	   		})
   		}
	})

		$(document).off('click', '.cancel_item_pr').on('click', '.cancel_item_pr',function(e) {
			var ID_pr_detail = $(this).attr('id_pr_detail');
			var NIP = sessionNIP;
			var url = base_url_js+'rest2/__cancel_pr_item_from_another';
	   		
	   		if (confirm('Are you sure ?')) {
		   		$('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Please Input Reason ! </b> <br>' +
		   		    '<input type = "text" class = "form-group" id ="NoteDel" style="margin: 0px 0px 15px; height: 30px; width: 329px;" maxlength="100"><br>'+
		   		    '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
		   		    '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
		   		    '</div>');
		   		$('#NotificationModal').modal('show');
		   		$("#confirmYes").click(function(){
		   			var NoteDel = $("#NoteDel").val();
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
		   			var data = {
		   			    ID_pr_detail : ID_pr_detail,
		   			    auth : 's3Cr3T-G4N',
		   			    NIP : NIP,
		   			    NoteDel : NoteDel,
		   			};
		   			var token = jwt_encode(data,"UAP)(*");
			   		$.post(url,{ token:token },function () {

					}).done(function(data_json) {
						if (data_json.reload == 0) {
							if (data_json.msg != '') {
								toastr.info(data_json.msg);
							}
						    $('.C_radio_pr').trigger('change');
						}
						else
						{
							if (data_json.msg != '') {
								toastr.info(data_json.msg);
							}
							    $('#page_pr_list').html(ClassDt.htmlPage_pr_list);
							    skip_error_dt_table();
								    Get_data_pr().then(function(data){
								    	loadingEnd(500);
								    	$('.C_radio_pr:first').prop('checked',true);
								    	$('.C_radio_pr:first').trigger('change');
								    })
						}

						$('#NotificationModal').modal('hide');
					    
				    });
		   		})
	   		}
		})
</script>