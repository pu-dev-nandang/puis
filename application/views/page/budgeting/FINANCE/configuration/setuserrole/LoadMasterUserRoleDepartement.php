<style type="text/css">
	#tableData9 thead th,#tableData9 tfoot td {

	    text-align: center;
	    background: #20485A;
	    color: #FFFFFF;

	}

	#tableData9>thead>tr>th, #tableData9>tbody>tr>th, #tableData9>tfoot>tr>th, #tableData9>thead>tr>td, #tableData9>tbody>tr>td, #tableData9>tfoot>tr>td {
	    border: 1px solid #b7b7b7
	}

	.btn span.glyphicon {    			
		opacity: 0;				
	}
	.btn.active span.glyphicon {				
		opacity: 1;				
	}
</style>
<div class="row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px" id = "loadPageTable">
	
</div>

<script type="text/javascript">
	$(document).ready(function() {
		LoadFirstLoad();
	}); // exit document Function

	function LoadFirstLoad()
	{
		loadingStart();
		var url = base_url_js+'budgeting/table_all/cfg_post/1';
	        // var data = {
	        //                 auth : 's3Cr3T-G4N',
	        //            };
	        // var token = jwt_encode(data,"UAP)(*");
		$.post(url,function (resultJson) {
			$("#loadPageTable").empty();
			var ActionAdd = '<div class = "row">'+
								'<div class = "col-md-12">'+
									'<span data-smt="" class="btn btn-add">'+
	                    				'<i class="icon-plus"></i> Add'+
	               					'</span>'+
	               				'</div>'	
               				'</div>';	
			var TableGenerate = '<div class = "row" style = "margin-top : 10px;margin-left : 0px ; margin-right : 0px">'+
									'<div class = "col-md-12">'+
										'<div class="table-responsive">'+
											'<table class="table table-bordered tableData" id ="tableData9">'+
											'<thead>'+
											'<tr>'+
												'<th width = "3%">Post</th>'+
					                            '<th>Amount Limit</th>'+
					                            '<th>Admin</th>'+
					                            '<th>Approver 1</th>'+
					                            '<th>Approver 2</th>'+
					                            '<th>Approver 3</th>'+
					                            '<th>Approver 4</th>'+
					                            '<th>Action</th>'+
											'</tr></thead>'	
								;
			TableGenerate += '<tbody></tbody></table></div></div></div>';
			var ActionSave = '<div class = "row sdsadasd" style = "margin-top : 10px;margin-left : 0px ; margin-right : 0px">'+
								'<div class = "col-md-2 col-md-offset-10" align = "right">'+
									'<button type="button" id="SaveForm" class="btn btn-success">Save</button>'+
								'</div>'+
							'</div>';		
			$("#loadPageTable").html(ActionAdd+TableGenerate+ActionSave);
			var resultJson = jQuery.parseJSON(resultJson);
			funcFillTbody(resultJson);
			funcAddbtn(resultJson);
			funcsavebtn(resultJson);
		}).fail(function() {
		  toastr.info('No Result Data'); 
		}).always(function() {
		    loadingEnd(500);         	   
		});
	}

	function funcFillTbody(resultJson)
	{
		var fill = '<tr>';
			var fillPost = '';
			for (var i = 0; i < resultJson.length; i++) {
				var Style = (i != 0) ? 'style = "margin-top : 10px"' : '';
				fillPost += '<div class = "row" '+Style+'><div class = "col-md-12">'+resultJson[i]['PostName']+'</div></div>';
			}
			fill += '<td class = "post">'+fillPost+'</td>';
			fill += '<td>'+'<input type = "text" class = "form-control AmountLimit" value = "0">'+'</td>';
			for (var i = 0; i < 5; i++) {
				var ID = i + 1;
				var chk = '<div class = "row">'+
							'<div class = "col-md-12">'+
								'<div class = "form-group">'+
									'<label>Entry</label>'+
									'<input type="checkbox" class = "form-control user" id_table ="'+ID+'" useraction = "'+'Entry'+'" style = "height : 15px">'+
								'</div>'+	
							'</div>'+
						  '</div>';	
				chk += '<div class = "row" '+'style = "margin-top : 10px"'+'>'+
							'<div class = "col-md-12">'+
								'<div class = "form-group">'+
									'<label>Approve</label>'+
									'<input type="checkbox" class = "form-control user" id_table ="'+ID+'" useraction = "'+'Approved'+'" style = "height : 15px">'+
								'</div>'+	
							'</div>'+
						  '</div>';	
				chk += '<div class = "row" '+'style = "margin-top : 10px"'+'>'+
							'<div class = "col-md-12">'+
								'<div class = "form-group">'+
									'<label>Cancel</label>'+
									'<input type="checkbox" class = "form-control user" id_table ="'+ID+'" useraction = "'+'Cancel'+'" style = "height : 15px">'+
								'</div>'+	
							'</div>'+
						  '</div>';		  		  
				fill += '<td align = "center">'+chk+'</td>';
			}
			var action = '<button type="button" class="btn btn-danger btn-delete"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
			fill += '<td>'+action+'</td>';
			fill += '</tr>';
		$('#tableData9 tbody').append(fill);
		// $('.AmountLimit').val(0);
		$('.AmountLimit').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		$('.AmountLimit').maskMoney('mask', '9894');

		$(".btn-delete").click(function(){
			$( this )
			  .closest( 'tr'  )
			  .remove();
		})
	}

	function funcAddbtn(resultJson)
	{
		$(".btn-add").click(function(){
			funcFillTbody(resultJson);
		})
	}

	function funcsavebtn(resultJson)
	{
		$("#SaveForm").click(function(){
			var AmountLimit = [];
			$(".AmountLimit").each(function(){
				AmountLimit.push($(this).val());
			})

			var Admin = [];
			$('input[id_table="1"]').each(function(){
				if ($(this).is(':checked')) {
					aa = 1;
				}
				else
				{
					aa = 0;
				}
				var temp = {};
				temp[$(this).attr('useraction')] = aa;
				Admin.push(temp);
			})

			var Approver1 = [];
			$('input[id_table="2"]').each(function(){
				if ($(this).is(':checked')) {
					aa = 1;
				}
				else
				{
					aa = 0;
				}
				var temp = {};
				temp[$(this).attr('useraction')] = aa;
				Approver1.push(temp);
			})

			var Approver2 = [];
			$('input[id_table="3"]').each(function(){
				if ($(this).is(':checked')) {
					aa = 1;
				}
				else
				{
					aa = 0;
				}
				var temp = {};
				temp[$(this).attr('useraction')] = aa;
				Approver2.push(temp);
			})

			var Approver3 = [];
			$('input[id_table="4"]').each(function(){
				if ($(this).is(':checked')) {
					aa = 1;
				}
				else
				{
					aa = 0;
				}
				var temp = {};
				temp[$(this).attr('useraction')] = aa;
				Approver3.push(temp);
			})

			var Approver4 = [];
			$('input[id_table="5"]').each(function(){
				if ($(this).is(':checked')) {
					aa = 1;
				}
				else
				{
					aa = 0;
				}
				var temp = {};
				temp[$(this).attr('useraction')] = aa;
				Approver4.push(temp);
			})

		})
	}
</script>