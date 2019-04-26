<div class="row">
	<div class="col-xs-4">
		<div class="thumbnail">
			<div id = "page_pr_list"></div>
		</div>	
	</div>
	<div class="col-xs-6">
		<div id = "page_pr_item_list"></div>
	</div>
	<div class="col-xs-2">
		<div id = "page_pr_selected_list"></div>
	</div>	
</div>

<script type="text/javascript">
	var ClassDt = {
		Dt_selection : [],
		ThisTableSelect : '',
		htmlPage_pr_list : function(){
			var html = '';
			html = '<div class = "row" style = "margin-right : 0px;margin-left:0px;">'+
					 '<div class col-md-12>'+
					 	'<table class="table table-bordered datatable2" id = "tableData_pr">'+
					 		'<thead>'+
					 			'<tr>'+
					 				'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">PR Code</th>'+
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Department</th>'+
					 			'</tr>'+
					 		'<thead>'+
					 		'<tbody id="dataRow"></tbody>'+
		        		'</table>'+
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
		    })
	}); // exit document Function

	function Get_data_pr(){
       var def = jQuery.Deferred();
       var data = {
           PurchasingStatus : '!=2',
           auth : 's3Cr3T-G4N',
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
       	    ],
       	    'createdRow': function( row, data, dataIndex ) {
       	    		$(row).find('td:eq(1)').html('<label><input type="radio" name="optradio" prcode = "'+data['PRCode']+'" class = "C_radio_pr">&nbsp'+data['PRCode']+'</label>');
       	    		$( row ).find('td:eq(2)').attr('align','center');
       	    		$( row ).find('td:eq(0)').attr('align','center');
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
		var url = base_url_js+'rest/__show_pr_detail';
   		var data = {
   		    PRCode : PRCode,
   		    auth : 's3Cr3T-G4N',
   		};
   		var token = jwt_encode(data,"UAP)(*");
   		$.post(url,{ token:token },function () {

		}).done(function(data_json) {
	    	MakeDom_page_pr_item_list(data_json);
	    });

	})

	function MakeDom_page_pr_item_list(dt)
	{
		var pr_detail = dt.pr_detail;
		var IsiInputPR = '';
		for (var i = 0; i < pr_detail.length; i++) {
			
		}
		var  htmlInputPR= 'div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_PR">'+
							'<div class = "col-md-12">'+
								'<div class="table-responsive">'+
									'<table class="table table-bordered tableData" id ="table_data_pr_detail">'+
										'<thead>'+
										'<tr>'+
											'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 150px;">Budget</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 150px;">Catalog</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Desc</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Spec+</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Need</th>'+
				                            '<th width = "4%" style = "text-align: center;background: #20485A;color: #FFFFFF;width : 78px;">Qty</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 150px;">Cost</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 78px;">PPH(%)</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 150px;">Sub Total</th>'+
				                            '<th width = "150px" style = "text-align: center;background: #20485A;color: #FFFFFF;">Date Needed</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">File</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Combine Budget</th>'+
										'</tr>'+
										'</thead>'+
										'<tbody>'+IsiInputPR+'</tbody></table>'+
									'</div>'+
								'</div>'+
							'</div>';		


	}
</script>