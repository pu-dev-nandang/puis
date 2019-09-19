<div class="thumbnail">
	<div style="padding: 15px;">
		<h3 style="color: red;">Data</h3>
	</div>
	<div class="row" style="margin-left: 0px;margin-right: 0px;">
		<div class="col-md-12">
			<div class="well">
				<div style="color: red;"><b>Filtering</b></div>
					<div class="row" style="margin-top: 10px;">
						<div class="col-md-3">
							<div class="thumbnail">
								<div class="form-group">
									<label>Kategori</label>
									<select class="form-control SearchKategori" name ="Kategori">
										<option selected value="%">--All--</option>
										<option value="PKM">PKM</option>
										<option value="Penelitian">Penelitian</option>
										<option value="Pendidikan">Pendidikan</option>
										<option value="Tridarma">Tridarma</option>
									</select>
								</div>
								<div class="form-group">
									<label>Tingkat</label>
									<select class="form-control SearchTingkat" name ="Kategori">
										<option selected value="%">--All--</option>
										<option value="Internasional">Internasional</option>
										<option value="Nasional">Nasional</option>
										<option value="Lokal">Wilayah/ Lokal</option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="thumbnail">
								<div class="form-group">
									<label>Perjanjian</label>
									<table class="">
										<tr>
											<td style="border-top: none;">MOU</td>
											<td style="border-top: none;"><input type="checkbox" name="Perjanjian" class="SearchPerjanjian" value="MOU"></td>
										</tr>
										<tr>
											<td style="border-top: none;">MOA</td>
											<td style="border-top: none;"><input type="checkbox" name="Perjanjian" class="SearchPerjanjian" value="MOA"></td>
										</tr>
										<tr>
											<td style="border-top: none;">IA</td>
											<td style="border-top: none;"><input type="checkbox" name="Perjanjian" class="SearchPerjanjian" value="IA"></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="thumbnail">
								<table class="table">
									<tr>
										<td>
											<label class="checkbox-inline">
												<input type="checkbox" class="dateOP" name="dateOP" id="dateOPRange" value="0">
												Date range
											</label>
										</td>
										<td>
											<label>Start Date</label>
											<div class="input-group input-append date datetimepicker">
					                            <input data-format="yyyy-MM-dd" class="form-control SearchDate" type=" text" readonly="" value = "" name = "StartDate">
					                            <span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
					                		</div>
										</td>
										<td>
											<label>End Date</label>
											<div class="input-group input-append date datetimepicker">
					                            <input data-format="yyyy-MM-dd" class="form-control SearchDate" type=" text" readonly="" value = "" name = "EndDate">
					                            <span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
					                		</div>
										</td>
									</tr>
								</table>
								<div align="right">
									<button class="btn btn-primary SearchDateBtn"><i class="fa fa-search"></i> Search</button>
								</div>
							</div>
						</div>
					</div>
				</div>
		</div>
	</div>
	<div class="row" style="margin-top: 5px;">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered" id="TblKerjaSama">
					<thead>
						<tr>
							<th>No</th>
							<th>Lembaga</th>
							<th>Bukti</th>
							<th>Date</th>
							<th>Perjanjian</th>
							<th>Department</th>
							<th><i class="fa fa-cog btnaction"></i></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		LoadDataForTable();
	})

	function LoadDataForTable()
	{
		var SearchPerjanjian = [];
		$('.SearchPerjanjian:checked').each(function(){
			var v = $(this).val();
			SearchPerjanjian.push(v);
		})

		var StartDate = $('.SearchDate[name="StartDate"]').val();
		var EndDate = $('.SearchDate[name="EndDate"]').val();

		var SearchKategori = $('.SearchKategori option:selected').val();
		var SearchTingkat = $('.SearchTingkat option:selected').val();
		var data = {
		    auth : 's3Cr3T-G4N',
		    SearchPerjanjian : SearchPerjanjian,
		    SearchKategori : SearchKategori,
		    SearchTingkat : SearchTingkat,
		    StartDate : StartDate,
		    EndDate : EndDate,
		    mode : 'DataKerjaSama',
		};
		var token = jwt_encode(data,"UAP)(*");
		$('#TblKerjaSama tbody').empty();

		var table = $('#TblKerjaSama').DataTable({
			"fixedHeader": true,
		    "processing": true,
		    "destroy": true,
		    "serverSide": true,
	      "lengthMenu": [[5], [5]],
		    "iDisplayLength" : 5,
		    "ordering" : false,
	      "language": {
	          "searchPlaceholder": "Search",
	      },
		    "ajax":{
		        url : base_url_js+"rest2/__get_data_kerja_sama_perguruan_tinggi", // json datasource
		        ordering : false,
		        type: "post",  // method  , by default get
		        data : {token : token},
		        error: function(){  // error handling
		            $(".employee-grid-error").html("");
		            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
		            $("#employee-grid_processing").css("display","none");
		        }
		    },
	   	    'createdRow': function( row, data, dataIndex ) {
	   	    	// console.log(data);
	   	    	var Bukti = data[2];
	   	    	var a = Bukti.split('--');
	   	    	var html = '';
	   	    	var File = jQuery.parseJSON(a[1]);
	   	    	html = a[0]+'<br>'+'<a href = "'+base_url_js+'fileGetAny/cooperation-'+File[0]+'" target="_blank" class = "Fileexist">Attachment</a>';
	   	    	$( row ).find('td:eq(2)').html(html);

	   	    	var Perjanjian = data[4];
	   	    	html = '';
	   	    	var cc = Perjanjian.split(',');
	   	    	for (var i = 0; i < cc.length; i++) {
	   	    		var zc = cc[i];
	   	    		a = zc.split('--');
	   	    		File = jQuery.parseJSON(a[1]);
	   	    		html += '<li>'+a[0]+'<br>'+'<a href = "'+base_url_js+'fileGetAny/cooperation-'+File[0]+'" target="_blank" class = "Fileexist" style="margin-left:19px;">Attachment</a></li>';
	   	    	}
	   	    	
	   	    	$( row ).find('td:eq(4)').html(html);

	   	    	var Departement = data[5];
	   	    	html = '';
	   	    	cc = Departement.split(',');
	   	    	var arr = [];
	   	    	for (var i = 0; i < cc.length; i++) {
	   	    		var zc = cc[i];
	   	    		a = zc.split('--');
	   	    		var temp = {
	   	    			Code : a[0],
	   	    			Name : a[1],
	   	    		}
	   	    		arr.push(temp);
	   	    	}
	   	    	var selector = $( row ).find('td:eq(5)');
	   	    	HtmlPageDepartmentSelected(arr,selector,'listtbl');

	   	    	var tokenEdit = data[7];

	   	    	html = '<div class="btn-group btnaction">  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">    <i class="fa fa-pencil"></i> <span class="caret"></span>  </button>  <ul class="dropdown-menu">    <li><a href="javascript:void(0);" class="btnEdit" data-id="'+data[6]+'" tokenedit="'+tokenEdit+'"><i class="fa fa fa-edit"></i> Edit</a></li>    <li role="separator" class="divider"></li>    <li><a href="javascript:void(0);" class="btnRemove" data-id="'+data[6]+'"><i class="fa fa fa-trash"></i> Remove</a></li>  </ul></div>';
	   	    	$( row ).find('td:eq(6)').html(html);
	   	    },
	        dom: 'l<"toolbar">frtip',
	   	    "initComplete": function(settings, json) {

	   	    }
		});
	}

	$(document).off('change', '.SearchTingkat,.SearchKategori').on('change', '.SearchTingkat,.SearchKategori',function(e) {
		LoadDataForTable();
	})

	$(document).off('click', '.SearchPerjanjian').on('click', '.SearchPerjanjian',function(e) {
		LoadDataForTable();
	})

	$(document).off('click', '#dateOPRange').on('click', '#dateOPRange',function(e) {
		if (this.checked) {
			var datee = "<?php echo date('Y-m-d') ?>";
			$('.SearchDate').val(datee);
			// $('.SearchDateBtn').prop('disabled',false);
		}
		else
		{
			$('.SearchDate').val('');
			// $('.SearchDateBtn').prop('disabled',true);
		}
	})

	$(document).off('click', '.SearchDateBtn').on('click', '.SearchDateBtn',function(e) {
		LoadDataForTable();
	})

	$(document).off('click', '.btnEdit').on('click', '.btnEdit',function(e) {
		var ID = $(this).attr('data-id');
		var tokenedit = $(this).attr('tokenedit');
		// decode data
		var dt = jwt_decode(tokenedit);
		FormEditSelected(dt);
	});

	$(document).off('click', '.btnRemove').on('click', '.btnRemove',function(e) {
		var ID = $(this).attr('data-id');
		var url = base_url_js+'cooperation/Kerja_Sama_Perguruan_Tinggi_Master/Submit';
		var data = {
		    ID : ID,
		    mode : 'delete',
		};
		if (confirm('Are you sure ?')) {
			var token = jwt_encode(data,"UAP)(*");
			$.post(url,{ token:token },function (resultJson) {
			  	if (data.Status == 0) {
			  		toastr.error("Connection Error, Please try again", 'Error!!');
				}
			  	else{
			  		LoadDataForTable();
			  		SetDataDefault();
			  		toastr.success('The data has been deleted');
				}
			}).fail(function() {
			  toastr.info('No Result Data'); 
			}).always(function() {
			                
			});	
		}
	});
</script>