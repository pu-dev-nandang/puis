<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="thumbnail">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Period</label>
						<select class="select2-select-00 full-width-fix" id="YearsBudgetRemaining">
						     <!-- <option></option> -->
						 </select>
					</div>	
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Departement</label>
						<select class="select2-select-00 full-width-fix" id="DepartementBudgetRemaining">
							
						</select>	
					</div>	
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<p style="color: red;font-size: 20px">(.000)</p>	
				</div>
			</div>
		</div>
	</div>
</div>
<div id = "BudgetRemainingContent" style="margin-top: 10px;margin-left: 10px;margin-right: 10px;">
	
</div>
<script type="text/javascript">
	// get Departmentpu
	var IDDepartementPUBudget = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";
	var SThisTable = '';
	$(document).ready(function() {
		LoadFirstLoad();
		loadingEnd(500);
	}); // exit document Function

	function LoadFirstLoad()
	{
		load_table_activated_period_years();
	}

	function load_table_activated_period_years()
	{
	   // load Year
	   $("#YearsBudgetRemaining").empty();
	   var url = base_url_js+'budgeting/table_all/cfg_dateperiod/1';
	   var thisYear = (new Date()).getFullYear();
	   $.post(url,function (resultJson) {
	    var response = jQuery.parseJSON(resultJson);
	    for(var i=0;i<response.length;i++){
	        //var selected = (i==0) ? 'selected' : '';
	        var selected = (response[i].Activated==1) ? 'selected' : '';
	        $('#YearsBudgetRemaining').append('<option value="'+response[i].Year+'" '+selected+'>'+response[i].Year+' - '+(parseInt(response[i].Year) + 1)+'</option>');
	    }
	    $('#YearsBudgetRemaining').select2({
	       //allowClear: true
	    });
	    getAllDepartementPU__();
	   }); 
	}

	function getAllDepartementPU__()
	{
	  var url = base_url_js+"api/__getAllDepartementPU";
	  $('#DepartementBudgetRemaining').empty();
	  $.post(url,function (data_json) {
	    for (var i = 0; i < data_json.length; i++) {
	    	if (IDDepartementPUBudget== 'NA.9') {
	    		$('#DepartementBudgetRemaining').append('<option value="'+ data_json[i]['Code']  +'" '+selected+'>'+data_json[i]['Name2']+'</option>');
	    	}
	    	else
	    	{
	    		if (data_json[i]['Code']==IDDepartementPUBudget) {
	    			var selected = (data_json[i]['Code']==IDDepartementPUBudget) ? 'selected' : '';
	    			$('#DepartementBudgetRemaining').append('<option value="'+ data_json[i]['Code']  +'" '+selected+'>'+data_json[i]['Name2']+'</option>');
	    			break;
	    		}
	    	}
	    }
	   
	    $('#DepartementBudgetRemaining').select2({
	       //allowClear: true
	    });
	    makeDomBudgetRemaining();
	  })
	}

	$(document).off('click', '#DepartementBudgetRemaining,#YearsBudgetRemaining').on('click', '#DepartementBudgetRemaining,#YearsBudgetRemaining',function(e) {
		makeDomBudgetRemaining();
	})

	function makeDomBudgetRemaining()
	{
		var Departement = $('#DepartementBudgetRemaining option:selected').val();
		var Year = $('#YearsBudgetRemaining option:selected').val();
		var se_content = $('#BudgetRemainingContent');
		load_budget_remaining__(Year,Departement).then(function(response){
			var dt = response.data;
			var html = '';
			html = '<div class = "row">'+
						'<div class = "col-xs-12">'+
							'<div class = "table-responsive">'+
							'<table id="tblBudgetRemaining" class="table table-bordered" cellspacing="0" width="100%">'+
						           '<thead>'+
						              '<tr>'+
						                 '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 3%;">No</th>'+
						                 '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Post Budget</th>'+
						                 '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Budget Awal</th>'+
						                 '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Budget Real</th>'+
						                 '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Budget Remaining(Real-Process)</th>'+
						                 '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Budget On Process</th>'+
						              '</tr>'+
						           '</thead>'+
						           '<tbody></tbody>'+
		      				'</table>'+
		      				'</div>'+
		      			'</div>'+
		      		'</div>';

		    se_content.html(html);

		    var table = $('#tblBudgetRemaining').DataTable({
		          "data" : dt,
		          'columnDefs': [
		    	      {
		    	         'targets': 0,
		    	         'searchable': false,
		    	         'orderable': false,
		    	         'className': 'dt-body-center',
		    	         'render': function (data, type, full, meta){
		    	             return '';
		    	         }
		    	      },
		    	      {
		    	         'targets': 1,
		    	         'render': function (data, type, full, meta){
		    	             return full.NameHeadAccount+'-'+full.RealisasiPostName;
		    	         }
		    	      },
		    	      {
		    	         'targets': 2,
		    	         'render': function (data, type, full, meta){
		    	             return formatRupiah(full.PriceBudgetAwal);
		    	         }
		    	      },
		    	      {
		    	         'targets': 3,
		    	         'render': function (data, type, full, meta){
		    	             return '<a href = "javascript:void(0)" class = "ShowReal" IDData = "'+full.ID+'">'+formatRupiah(full.Value)+'</a>';
		    	         }
		    	      },
		    	      {
		    	         'targets': 4,
		    	         'render': function (data, type, full, meta){
		    	             return formatRupiah(full.Value-full.Using);
		    	         }
		    	      },
		    	      {
		    	         'targets': 5,
		    	         'render': function (data, type, full, meta){
		    	             return '<a href = "javascript:void(0)" class = "ShowProcess" IDData = "'+full.ID+'">'+formatRupiah(full.Using)+'</a>';
		    	         }
		    	      },
		          ],
		          'createdRow': function( row, data, dataIndex ) {
		          		
		          },
		          // 'order': [[1, 'asc']]
		    });

		    table.on( 'order.dt search.dt', function () {
		            table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		                cell.innerHTML = i+1;
		            } );
		        } ).draw();

		   	SThisTable = table;

		})

	}

	function load_budget_remaining__(Year,Departement)
	{
		var def = jQuery.Deferred();
		var url = base_url_js+"budgeting/detail_budgeting_remaining";
		var data = {
				    Year : Year,
					Departement : Departement,
				};
		var token = jwt_encode(data,'UAP)(*');
		$.post(url,{token:token},function (resultJson) {
			
		}).done(function(resultJson) {
			var response2 = jQuery.parseJSON(resultJson);
			def.resolve(response2);
		}).fail(function() {
		  toastr.info('No Result Data'); 
		  def.reject();
		}).always(function() {
		                
		});
		return def.promise();
	}


	$(document).off('click', '.ShowReal').on('click', '.ShowReal',function(e) {
		var ID_budget_left = $(this).attr('IDData');
		var CodeURL = jwt_encode(ID_budget_left,"UAP)(*");
		var url = base_url_js+'budgeting_real_detail/'+CodeURL;
		FormSubmitAuto(url, 'POST', [
		    {},
		]);
	})
</script>