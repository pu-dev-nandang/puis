<style type="text/css">
	.thumbnail {
	    height: 80px;
	}

	.row {
	    margin-right: 0px;
	    margin-left: 0px;
	}

	#tableData1 thead th,#tableData1 tfoot td {

	    text-align: center;
	    background: #20485A;
	    color: #FFFFFF;

	}
</style>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="thumbnail">
			<div class="col-xs-6">
				<div class="form-group">
					<label>Year</label>
					<select class="select2-select-00 full-width-fix" id="Year">
					     <!-- <option></option> -->
					 </select>
				</div>	
			</div>
			<div class="col-xs-6">
				<div class="form-group">
					<label>Departement</label>
					<select class="select2-select-00 full-width-fix" id="Departement">
					     <!-- <option></option> -->
					 </select>
				</div>	
			</div>		
		</div>
	</div>
</div>
<br>
<div class="row" id = "loadPageTable">

</div>
<script type="text/javascript">
	$(document).ready(function() {
		LoadFirstLoad()
	    
	}); // exit document Function

	function LoadFirstLoad()
	{
		// load Year
		$("#Year").empty();
		var url = base_url_js+'budgeting/table_all/cfg_dateperiod/1';
		var thisYear = (new Date()).getFullYear();
		$.post(url,function (resultJson) {
			var response = jQuery.parseJSON(resultJson);
			for(var i=0;i<response.length;i++){
			    //var selected = (i==0) ? 'selected' : '';
			    var selected = (response[i].Year==thisYear) ? 'selected' : '';
			    $('#Year').append('<option value="'+response[i].Year+'" '+selected+'>'+response[i].Year+'</option>');
			}
			$('#Year').select2({
			   //allowClear: true
			});

			getAllDepartementPU();
		}); 
	}

	function getAllDepartementPU()
	{
	  var url = base_url_js+"api/__getAllDepartementPU";
	  $('#Departement').empty();
	  $.post(url,function (data_json) {
	    for (var i = 0; i < data_json.length; i++) {
	        var selected = (i==0) ? 'selected' : '';
	        $('#Departement').append('<option value="'+ data_json[i]['Code']  +'" '+selected+'>'+data_json[i]['Name2']+'</option>');
	    }
	   
	    $('#Departement').select2({
	       //allowClear: true
	    });

	    loadPageTable();

	  })
	}

	function loadPageTable()
	{
		var Year = $("#Year").val();
		var Departement = $("#Departement").val();
		var url = base_url_js+"budgeting/getPostDepartement";

		$("#loadPageTable").empty();

		var Export = '<div class="col-lg-3 col-md-3 col-xs-4">'+
							'<h4 class="header"><i class="icon-reorder"></i> Export</h4>'+
							'<div class="col-xs-12">'+
								'<button class = "btn btn-default"><i class="fa fa-download" aria-hidden="true"></i> Excel</button>&nbsp'+
								'<button class = "btn btn-default"><i class="fa fa-download" aria-hidden="true"></i> PDF</button>'+
							'</div>'+
					 '</div>';	      

		var setLastYear = '<div class="col-md-12">'+
								'<div class="thumbnail" style="min-height: 130px;padding: 10px;">'+
									'<div class="col-lg-3 col-md-3 col-xs-4">'+
		                                '<h4 class="header"><i class="icon-reorder"></i> Get Budget Last Year</h4>'+
		                                '<div class = "col-xs-12"> <br>'+
		                                      '<button class = "btn btn-success btn-edit" id = "generateBudgetLastYear">Take</button>'+
		                                '</div>'+
		                            '</div>'+
		                            '<div class="col-lg-3 col-md-3 col-xs-4">'+
		                                '<h4 class="header"><i class="icon-reorder"></i> Add</h4>'+
		                                '<div class = "col-xs-12"> <br>'+
		                                      '<button class = "btn btn-default btn-add" id = "addRow"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>'+
		                                '</div>'+
		                            '</div>'+      
	                                Export+  
                                '</div>'+
                           '</div>';

		var TableGenerate = '<div class="col-md-12">'+
								'<div class="table-responsive">'+
									'<table class="table table-bordered tableData" id ="tableData1">'+
									'<thead>'+
									'<tr>'+
										'<th width = "3%">No</th>'+
			                            '<th>Departement</th>'+
			                            '<th>Code</th>'+
										'<th>Post Realization</th>'+
										'<th>Year</th>'+
										'<th>Budget</th>'+
										'<th>Action</th>'+
									'</tr></thead>'	
							;
		TableGenerate += '<tbody>';
		var data = {
		    Year : Year,
			Departement : Departement,
		};
		var token = jwt_encode(data,'UAP)(*');
		$.post(url,{token:token},function (resultJson) {
			var response = jQuery.parseJSON(resultJson);
			for (var i = 0; i < response.length; i++) {
				TableGenerate += '<tr>'+
									'<td width = "3%">'+ (parseInt(i) + 1)+'</td>'+
									'<td>'+ response[i].Departement+'</td>'+
									'<td>'+ response[i].CodePostBudget+'</td>'+
									'<td>'+ response[i].CodeSubPost+'<br>'+response[i].PostName+'-'+response[i].RealisasiPostName+'</td>'+
									'<td>'+ response[i].Year+'</td>'+
									'<td>'+ response[i].Budget+'</td>'+
								'</tr>';	

			}

			TableGenerate += '</tbody></table></div></div>';
			
			$("#loadPageTable").html(setLastYear+'<br>'+TableGenerate);
			// LoaddataTableStandard("#tableData1");
			var t = $('#tableData1').DataTable();
			    var counter = 1;
			 	
			    $('#addRow').on( 'click', function () {
			    	console.log(response);
			    	var No = counter;
			    	t
			    	    .clear()
			    	    .draw();
			        t.row.add( [
			            1,
			            $("#Departement").find(":selected").text(),
			            'Automatic after submit',
			            counter +'.4',
			            counter +'.5',
			            $("#Year").find(":selected").text(),
			            counter +'.7',
			        ] ).draw( false );
			 
			        counter++;
			    } );
			 
			    // Automatically add a first row of data
			    // $('#addRow').click();

		}).fail(function() {
		  toastr.info('No Result Data'); 
		}).always(function() {
		                
		});	
		
	}
</script>
