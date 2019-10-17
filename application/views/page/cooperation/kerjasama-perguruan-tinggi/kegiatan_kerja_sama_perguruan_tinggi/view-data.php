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
									<label>Kategori Kerja Sama</label>
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
								<div class="form-group">
									<label>Kategori Kegiatan</label>
									<select class="form-control SearchKategoriKegiatan" name ="Kategori">
										<option selected value="%">--All--</option>
										<option value="PKM">PKM</option>
										<option value="Penelitian">Penelitian</option>
										<option value="Pendidikan">Pendidikan</option>
										<!-- <option value="Tridarma">Tridarma</option> -->
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
					<div class="row" style="margin-top: 10px;">
						<div class="col-md-4">
							<div class="thumbnail">
								<div class="form-group">
									<label>Pilih Lembaga</label>
									<div class="input-group">
										<input type="hidden" class="form-control inputSearch" readonly name="KerjasamaID">
										<input type="text" class="form-control inputshowSearch" readonly name="KerjasamaID">
										<span class="input-group-btn">
												<button class="btn btn-default SearchKerjasamaID" type="button" for = "search"><i class="fa fa-search" aria-hidden="true"></i>
												</button>
										</span>
									</div>
									<div align="right" style="margin-top:10px;">
										<button class="btn btn-warning SetEmpty">Set Empty</button>
									</div>
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
							<th>Judul & Kategori</th>
							<th>Bentuk</th>
							<th>Manfaat</th>
							<th style="width: 15%;">Date</th>
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
		        type: "post",  // method  , by default get
		        // data : {token : token},
		       data : function(token){
                       // Read values
               			var SearchPerjanjian = [];
               			$('.SearchPerjanjian:checked').each(function(){
               				var v = $(this).val();
               				SearchPerjanjian.push(v);
               			})

               			var StartDate = $('.SearchDate[name="StartDate"]').val();
               			var EndDate = $('.SearchDate[name="EndDate"]').val();

               			var SearchKategori = $('.SearchKategori option:selected').val();
               			var SearchKategoriKegiatan = $('.SearchKategoriKegiatan option:selected').val();
               			var SearchTingkat = $('.SearchTingkat option:selected').val();
               			var SearchLembaga = $('.inputSearch[name="KerjasamaID"]').val();
               			var data = {
               			    auth : 's3Cr3T-G4N',
               			    SearchPerjanjian : SearchPerjanjian,
               			    SearchKategori : SearchKategori,
               			    SearchTingkat : SearchTingkat,
               			    StartDate : StartDate,
               			    EndDate : EndDate,
               			    SearchLembaga : SearchLembaga,
               			    SearchKategoriKegiatan : SearchKategoriKegiatan,
               			    mode : 'DataKegiatan',
               			};
               			var get_token = jwt_encode(data,"UAP)(*");
               			token.token = get_token;
                },
		        error: function(){  // error handling
		            $(".employee-grid-error").html("");
		            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
		            $("#employee-grid_processing").css("display","none");
		        }
		    },
	   	    'createdRow': function( row, data, dataIndex ) {
	   	    	// console.log(data);
	   	    	var ID = data[1];
	   	    	var Departement = data[6];
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
	   	    	var selector = $( row ).find('td:eq(6)');
	   	    	HtmlPageDepartmentSelected(arr,selector,'listtbl');

	   	    	var tokenEdit = data[9];

	   	    	html = '<div class="btn-group btnaction">  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">    <i class="fa fa-pencil"></i> <span class="caret"></span>  </button>  <ul class="dropdown-menu" style="min-width:50px !important;">    <li><a href="javascript:void(0);" class="btnEdit" data-id="'+data[8]+'" tokenedit="'+tokenEdit+'"><i class="fa fa fa-edit"></i> </a></li>    <li role="separator" class="divider"></li>    <li><a href="javascript:void(0);" class="btnRemove" data-id="'+data[8]+'"><i class="fa fa fa-trash"></i> </a></li>  </ul></div>';
	   	    	$( row ).find('td:eq(7)').html(html);
	   	    },
	        dom: 'l<"toolbar">frtip',
	   	    "initComplete": function(settings, json) {

	   	    }
		});

		oTable = table;
	}

	$(document).off('change', '.SearchTingkat,.SearchKategori,.SearchKategoriKegiatan').on('change', '.SearchTingkat,.SearchKategori,.SearchKategoriKegiatan',function(e) {
		// LoadDataForTable();
		oTable.ajax.reload( null, false );
	})

	$(document).off('click', '.SearchPerjanjian').on('click', '.SearchPerjanjian',function(e) {
		// LoadDataForTable();
		oTable.ajax.reload( null, false );
	})

	$(document).off('change', '.inputSearch').on('change', '.inputSearch',function(e) {
		// LoadDataForTable();
		oTable.ajax.reload( null, false );
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
		// LoadDataForTable();
		oTable.ajax.reload( null, false );
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
		var url = base_url_js+'cooperation/Kerja_Sama_Perguruan_Tinggi_Kegiatan/Submit';
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
			  		// LoadDataForTable();
			  		oTable.ajax.reload( null, false );
			  		SetDataDefault();
			  		toastr.success('The data has been deleted');
				}
			}).fail(function() {
			  toastr.info('No Result Data'); 
			}).always(function() {
			                
			});	
		}
	});

	$(document).off('click', '.SetEmpty').on('click', '.SetEmpty',function(e) {
		$('.inputSearch').val('');
		$('.inputshowSearch').val('');
		// LoadDataForTable();
		oTable.ajax.reload( null, false );
	});
</script>