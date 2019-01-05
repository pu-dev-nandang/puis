<div class="row">
	<div class="col-md-12">
		<div class="col-md-8 col-md-offset-2">
			<div class="thumbnail">
				<div class="row" style="margin-top: 10px">
					<div class="col-md-3 col-md-offset-1">
						<div class="well">
							<div class="form-group">
								<label class="control-label">Year</label>
								<select class = "select2-select-00 full-width-fix" id = "Year">

								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Department</label>
								<select class = "select2-select-00 full-width-fix" id = "DepartementPost">

								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Category / Group</label>
								<select class = "select2-select-00 full-width-fix" id = "PostBudget">

								</select>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-md-offset-1">
						<div class="well">
							<div style="margin-top: -15px">
								<label>Budget These Month</label>
							</div>
							<div id = "Page_Budget">
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
 <!-- <pre> -->
	<?php 
	//print_r($this->session->all_userdata());
	 ?>
<!-- </pre>  -->
<div id ="Page_Input_PR" style="margin-top: 10px">
	
</div>
<script type="text/javascript">
	var arr_Year = <?php echo json_encode($arr_Year) ?>;
	$(document).ready(function() {
		LoadFirstLoad();

		function LoadFirstLoad()
		{
			loadYear();
			getAllDepartementPU();
		}

		function loadYear()
		{
			$("#Year").empty();
			var OPYear = '';
			OPYear = '';
			for (var i = 0; i < arr_Year.length; i++) {
				var selected = (arr_Year[i].Year == "<?php echo $Year ?>") ? 'selected' : '';
				OPYear += '<option value ="'+arr_Year[i].Year+'" '+selected+'>'+arr_Year[i].Year+'</option>';
			}
			$("#Year").append(OPYear);
			$('#Year').select2({
			   //allowClear: true
			});
			$( "#Year" ).prop( "disabled", true );
			$("#Year").change(function(){
				loadSelectPostRealiasi();
			})
		}

		function getAllDepartementPU()
		{
		  var Div = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";
		  var url = base_url_js+"api/__getAllDepartementPU";
		  $('#DepartementPost').empty();
		  $.post(url,function (data_json) {
		    for (var i = 0; i < data_json.length; i++) {
		        var selected = (data_json[i]['Code']==Div) ? 'selected' : '';
		        $('#DepartementPost').append('<option value="'+ data_json[i]['Code']  +'" '+selected+'>'+data_json[i]['Name2']+'</option>');
		    }
		   
		    $('#DepartementPost').select2({
		       //allowClear: true
		    });
		    $( "#DepartementPost" ).prop( "disabled", true );
		    $("#DepartementPost").change(function(){
		    	loadSelectPostRealiasi();
		    })

		    loadSelectPostRealiasi();

		  })
		}

		function loadSelectPostRealiasi()
		{
			var Year = $("#Year").val();
			var Departement = $("#DepartementPost").val();
			var url = base_url_js+"budgeting/getPostBudgetDepartement";

			var data = {
					    Year : Year,
						Departement : Departement,
					};
			var token = jwt_encode(data,'UAP)(*');
			$.post(url,{token:token},function (resultJson) {
				var response = jQuery.parseJSON(resultJson);
				$("#PostBudget").empty();
				if (response.length > 0) {
					var PostBudget = '';
					var abc = 0;
					for (var i = 0; i < response.length; i++) {
						var selected = (i == 0) ? 'selected' : '';
						PostBudget += '<option value ="'+response[i].CodePost+'" '+selected+'>'+response[i].PostName+'</option>';
						abc++;
						
					}

					if (abc > 0) {
						$("#PostBudget").append(PostBudget);
						$('#PostBudget').select2({
						   //allowClear: true
						});
						loadPostBudgetThisMonth();
						$("#PostBudget").change(function(){
							loadPostBudgetThisMonth();
						})
					}
					else
					{
						toastr.info('No Result Data in category, please add Post Budget by Finance'); 
					}
					
				}
				else
				{
					toastr.info('No Result Data in category, please add Post Budget by Finance'); 
				}

			}).fail(function() {
			  toastr.info('No Result Data'); 
			}).always(function() {
			                
			});
		}

		function loadPostBudgetThisMonth()
		{
			var Departement = $("#DepartementPost").val();
			var PostBudget = $('#PostBudget').val();
			var url = base_url_js+"budgeting/PostBudgetThisMonth_Department";
			var data = {
						Departement : Departement,
						PostBudget : PostBudget
					};
			var token = jwt_encode(data,'UAP)(*');
			$.post(url,{token:token},function (resultJson) {
				var response = jQuery.parseJSON(resultJson);
				if (response.length > 0) {
					load_budget(response)
				}
				else
				{
					toastr.info('Budget doesn\'t exist'); 
				}
			}).fail(function() {
			  toastr.info('No Result Data'); 
			}).always(function() {
			                
			});
		}

		function load_budget(response)
		{
			$("#Page_Budget").empty();
			var html = '<div class = row>'+
							'<div class = "col-md-12">'+
								'<div class = "table-responsive">'+
									'<table class="table table-bordered tableData" id ="tableData3">'+
										'<thead>'+
										'<tr>'+
											'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">Choose</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Budget</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Remaining</th>'+
										'</tr></thead>'+
										'<tbody></tbody></table></div></div></div>';
			$("#Page_Budget").html(html);
			var isi = '';
			for (var i = 0; i < response.length; i++) {
				isi += '<tr>';
				isi += '<td><input type="checkbox" class="uniform" value="'+response[i]['Value']+'" id_table="'+response[i]['ID']+'">'+
						'<td>'+response[i]['RealisasiPostName']+'</td>'+
						'<td>'+formatRupiah(response[i]['Value'])+'</td>';
				isi += '</tr>';		

			}

			$("#tableData3 tbody").append(isi);
			var table = $("#tableData3").DataTable({
			    'iDisplayLength' : 5,
			    'ordering' : true,
			});

			loading_page("#Page_Input_PR");
			setTimeout(function () {
			    Load_input_PR();
			},1000);
		}

		function Load_input_PR()
		{
			var html = '<div class = "row">'+
							'<div class = "col-md-12">'+
								'<div class = "table-responsive">'+
									'<table class="table table-bordered tableData" id ="table_input_pr">'+
									'<thead>'+
									'<tr>'+
										'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Item</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Desc</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Qty</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Unit Cost</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Sub Total</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Date Needed</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Budget Status</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Upload Files</th>'+
									'</tr></thead>'+
									'<tbody></tbody></table></div></div></div>';
			$("#Page_Input_PR").html(html);						
			AddingTable();

		}

		function AddingTable()
		{
			var fill = '';
			var No = 1;
			var getfill = function(No){
				var a = '<tr>'+
							'<td>'+No+'</td>'+
							'<td>'+
								'<div class="input-group">'+
									'<input type="text" class="form-control Item" readonly>'+
									'<span class="input-group-btn">'+
										'<button class="btn btn-default SearchItem" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
									'</span>'+
								'</div>'+
							'</td>'+
							'<td><button class = "btn btn-primary Detail">Detail</button></td>'+
							'<td><input type="number" min = "1" class="form-control qty"  value="1"></td>'+
							'<td><input type="text" class="form-control UnitCost"></td>'+
							'<td><input type="text" class="form-control SubTotal"></td>'+
							'<td>'+
								'<div id="datetimepicker1'+No+'" class="input-group input-append date datetimepicker">'+
		                            '<input data-format="yyyy-MM-dd" class="form-control" id="tgl'+No+'" type=" text" readonly="" value = "<?php echo date('Y-m-d') ?>">'+
		                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
                        		'</div>'+
                        	'</td>'+
                        	'<td></td>'+
                        	'<td><input type="file" data-style="fileinput" class = "BrowseFile"></td>'+
                        '</tr>';	

				return a;				
			}		
			if ($("#table_input_pr tbody").children().length == 0) {
				fill = getfill(No);
				$('#table_input_pr tbody').append(fill);
			}

			eventTableFunction();
		}

		function eventTableFunction()
		{
			$(".SearchItem").click(function(){
				var ev = $(this);
				var html = '';
					html ='<div class = "row">'+
							'<div class = "col-md-12">'+
								'<table id="example" class="table table-bordered display select" cellspacing="0" width="100%">'+
                       '<thead>'+
                          '<tr>'+
                             // '<th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>'+
                             '<th></th>'+
                             '<th>Item</th>'+
                             '<th>Desc</th>'+
                             '<th>Estimate Value</th>'+
                             '<th>Photo</th>'+
                             '<th>DetailCatalog</th>'+
                          '</tr>'+
                       '</thead>'+
                  '</table></div></div>';
				$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Catalog'+'</h4>');
				$('#GlobalModalLarge .modal-body').html(html);
				$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
                '<button type="button" id="ModalbtnSaveForm" class="btn btn-success">Save</button>');
				$('#GlobalModalLarge').modal({
				    'show' : true,
				    'backdrop' : 'static'
				});

				var url = base_url_js+'rest/Catalog/__Get_Item';
				var data = {
					action : 'choices',
					auth : 's3Cr3T-G4N',
					department : "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>"
				};
	    	    var token = jwt_encode(data,"UAP)(*");
				var table = $('#example').DataTable({
				      'ajax': {
				         'url': url,
				         'type' : 'POST',
				         'data'	: {
				         	token : token,
				         },
				         dataType: 'json'
				      },
				      'columnDefs': [{
				         'targets': 0,
				         'searchable': false,
				         'orderable': false,
				         'className': 'dt-body-center',
				         'render': function (data, type, full, meta){
				             // console.log(full)
				             // return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
				             	return '<input type="checkbox" name="id[]" value="' + full[6] + '" estvalue="' + full[7] + '">';
				             // if(full[2] == 0)
				             // {
				             //  return '<input type="checkbox" name="id[]" value="' + full[0] + '">';
				             // }
				             // else
				             // {
				             //  return '<input type="checkbox" name="id[]" value="' + full[0] + '" checked>';
				             // }
				             
				         }
				      }],
				      'order': [[1, 'asc']]
				   });

				   // Handle click on "Select all" control
				   // $('#example-select-all').on('click', function(){
				   //    // Get all rows with search applied
				   //    var rows = table.rows({ 'search': 'applied' }).nodes();
				   //    // Check/uncheck checkboxes for all rows in the table
				   //    $('input[type="checkbox"]', rows).prop('checked', this.checked);
				   // });

				   // Handle click on checkbox to set state of "Select all" control
				   $('#example tbody').on('change', 'input[type="checkbox"]', function(){
				   	$('input[type="checkbox"]').prop('checked', false);
				   	$(this).prop('checked',true);
				      // If checkbox is not checked
				      // if(!this.checked){
				      //    var el = $('#example-select-all').get(0);
				      //    // If "Select all" control is checked and has 'indeterminate' property
				      //    if(el && el.checked && ('indeterminate' in el)){
				      //       // Set visual state of "Select all" control
				      //       // as 'indeterminate'
				      //       el.indeterminate = true;
				      //    }
				      // }
				   });


				   $("#ModalbtnSaveForm").click(function(){
				   		var chkbox = $('input[type="checkbox"]:checked');
				   		var checked = chkbox.val();
				   		var estvalue = chkbox.attr('estvalue');
				   		 var n = estvalue.indexOf(".");
				   		estvalue = estvalue.substring(0, n);
				   		var row = chkbox.closest('tr');
				   		var Item = row.find('td:eq(1)').text();
				   		var Desc = row.find('td:eq(2)').text();
				   		var Est = row.find('td:eq(3)').text();
				   		var Photo = row.find('td:eq(4)').html();
				   		var DetailCatalog =  row.find('td:eq(5)').html();
				   		var arr = Item+'@@'+Desc+'@@'+Est+'@@'+Photo+'@@'+DetailCatalog;
				   		var fillItem = ev.closest('tr');
				   		fillItem.find('td:eq(1)').find('.Item').val(Item);
				   		fillItem.find('td:eq(1)').find('.Item').attr('savevalue',checked);
				   		fillItem.find('td:eq(1)').find('.Item').attr('estvalue',estvalue);
				   		fillItem.find('td:eq(2)').find('.Detail').attr('data',arr);
				   		fillItem.find('td:eq(4)').find('.UnitCost').val(estvalue);
				   		fillItem.find('td:eq(4)').find('.UnitCost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
				   		fillItem.find('td:eq(4)').find('.UnitCost').maskMoney('mask', '9894');
				   		$('#GlobalModalLarge').modal('hide');
				   })

			})

			$(".Detail").click(function(){
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
                       			html += '<td>'+arr[i]+'</td>';
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
		}
	}); // exit document Function
</script>