<div class="row">
	<div class="col-md-12">
		<div class="col-xs-10 col-md-offset-1">
			<div class="widget box">
			    <div class="widget-header">
			        <h4 class="header"><i class="icon-reorder"></i>LAPORAN - Export to Excel</h4>
			    </div>
			    <div class="widget-content">
			        <!--  -->
			        <div class="row" style="margin-left: 0px;margin-right: 0px">
			          	<div class="thumbnail" style="height: 250px">
  				          	<div class="col-xs-6">
  				          		<div class="form-group">
  				          			<div class="row" style="margin-top: 10px">
  				          				<div class="col-xs-4">
  				          					<label class="checkbox-inline">
  				          					     <input type="checkbox" class = "dateOP" name="dateOP" id = "dateOPRange" value = "0"> Date range
  				          					</label>
  				          				</div>
  				          				<div class="col-xs-4">
  				          					<input type="text" name="dateRange1" id= "dateRange1" data-date-format="yyyy-mm-dd" placeholder="Date Start" class="form-control" readonly="true" value="<?php echo date('Y-m-d') ?>">
  				          				</div>
  				          				<div class="col-xs-4">
  				          					<input type="text" name="dateRange2" id= "dateRange2" data-date-format="yyyy-mm-dd" placeholder="Date End" class="form-control" readonly="true" value="<?php echo date('Y-m-d') ?>">
  				          				</div>
  				          			</div>
  				          			<div class="row" style="margin-top: 10px">
  				          				<div class="col-xs-4">
  				          					<label class="checkbox-inline">
  				          					     <input type="checkbox" class = "dateOP" name="dateOP" id = "dateOPMonth" value = "1"> By Month
  				          					</label>
  				          				</div>
  				          				<div class="col-xs-4">
  				          					<select class="select2-select-00 full-width-fix" id="SelectMonth">
  		                                         <option></option>
  		                                    </select>
  				          				</div>
  				          				<div class="col-xs-4">
  				          					<select class="select2-select-00 full-width-fix" id="SelectYear">
  		                                         <option></option>
  		                                    </select>
  				          				</div>
  				          			</div>
  				          			<div class="row" style="margin-top: 10px">
  				          				<div class="col-xs-4 col-md-offset-4">
  				          					<label>Angkatan</label>
  				          					<select class="select2-select-00 full-width-fix" id="SelectSetTa">
  		                                         <option></option>
  		                                    </select>
  				          				</div>
  				          			</div>
  				          			<div class="row" style="margin-top: 10px">
  				          				<div class="col-xs-4 col-md-offset-4">
  				          					<label>Sort By</label>
  				          					<select class="select2-select-00 full-width-fix" id="SelectSortBy">
  		                                         <option value="a.No_Ref" selected>No Ref</option>
  		                                         <option value="a.FormulirCode">Form Number</option>
  		                                         <option value="b.DateSale">Date</option>
  		                                    </select>
  				          				</div>
  				          			</div>
  				          		</div>
  				          	</div>
  				          	<div class="col-xs-6">
  				          		<div class="form-group">
  				          			<div class="row" style="margin-top: 10px">
  				          				<div class="col-xs-4 col-md-offset-4">
  				          					<label>Data</label>
  				          				</div>
  				          				<div class="col-xs-4">
  				          					<label>Finance</label>
  				          				</div>
  				          			</div>
  				          			<div class="row">
  				          				<div class="col-xs-4">
  				          					<label>Penjualan Formulir</label>
  				          				</div>
  				          				<div class="col-xs-4">
  				          					<button class="btn btn-primary" id= "btnPenjualanFormulirData"> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button>
  				          				</div>
  				          				<div class="col-xs-4">
  				          					<button class="btn btn-primary" id= "btnPenjualanFormulirFinance"> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button>
  				          				</div>
  				          			</div>
  				          			<div class="row" style="margin-top: 10px">
  				          				<div class="col-xs-4">
  				          					<label>Registrasi (Pengembalian Form)</label>
  				          				</div>
  				          				<div class="col-xs-4">
  				          					<button class="btn btn-primary" id= "btnPengembalianFormulirData"> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button>
  				          				</div>
  				          			</div>
  				          		</div>		
  				          	</div>
			          	</div>
			        </div>
			        <div class="row" style="margin-left: 0px;margin-right: 0px;margin-top: 10px">
			        	<div class="thumbnail" style="height: 150px">
			        		<div class="col-xs-6">
			        			<div class="form-group">
			        				<div class="row" style="margin-top: 10px">
			        					<div class="col-xs-4">
			        						<label>Angkatan & Date</label>
			        					</div>
			        					<div class="col-xs-4">
			        						<select class="select2-select-00 full-width-fix" id="SelectYearDataMHS">
  		                                         <option></option>
  		                                    </select>
			        					</div>
			        					<div class="col-xs-4">
  				          					<input type="text" name="datechoose" id= "datechoose" data-date-format="yyyy-mm-dd" placeholder="Date Start" class="form-control" readonly="true" value="<?php echo date('Y-m-d') ?>">
			        					</div>
			        				</div>
			        				<div class="row" style="margin-top: 10px">
			        					<div class="col-xs-4">
			        						<label>Major</label>
			        					</div>
			        					<div class="col-xs-4">
			        						<select class="select2-select-00 full-width-fix" id="Prodi">
  		                                         <option></option>
  		                                    </select>
			        					</div>
			        				</div>
			        			</div>
			        		</div>
			        		<div class="col-xs-6">
			        			<div class="form-group">
			        				<div class="row" style="margin-top: 10px">
			        					<div class="col-xs-4">
			        						<label>Tuition Fee</label>
			        					</div>
			        					<div class="col-xs-4">
			        						<button class="btn btn-primary" id= "btnTuitionFee"> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button>
			        					</div>	
			        				</div>
			        			</div>
			        			<div class="form-group">
			        				<div class="row" style="margin-top: 10px">
			        					<div class="col-xs-4">
			        						<label>Intake</label>
			        					</div>
			        					<div class="col-xs-4">
			        						<button class="btn btn-primary" id= "btnIntake"> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button>
			        					</div>	
			        				</div>
			        			</div>		
			        		</div>
			        	</div>
			        </div>
			        <!-- end widget -->
			    </div>
			    <hr/>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		FuncDisabledInput();
		FuncfirstLoad();
		FuncClickDateOP();
		FuncClickbtnPenjualanFormulirData();
		FuncClickbtnPenjualanFormulirFinance();
		FuncClickbtnPengembalianFormulirData();
		FuncClickBtnTuitionFee();
		FuncClickBtnIntake();
	});

	function FuncClickBtnIntake()
	{
		$("#btnIntake").click(function(){
			var url = base_url_js+'admission/intake_Excel';
			var SelectYearDataMHS = $("#SelectYearDataMHS").val();
			var Prodi = $("#Prodi").val();
			var datechoose = $('#datechoose').val();
			data = {
			  Year : SelectYearDataMHS,
			  Prodi : Prodi,
			  datechoose : datechoose,
			}
			var token = jwt_encode(data,"UAP)(*");
			FormSubmitAuto(url, 'POST', [
			    { name: 'token', value: token },
			]);
		})
	}

	function FuncClickBtnTuitionFee()
	{
		$("#btnTuitionFee").click(function(){
			var url = base_url_js+'admission/TuitionFee_Excel';
			var SelectYearDataMHS = $("#SelectYearDataMHS").val();
			var Prodi = $("#Prodi").val();
			data = {
			  Year : SelectYearDataMHS,
			  Prodi : Prodi,
			}
			var token = jwt_encode(data,"UAP)(*");
			FormSubmitAuto(url, 'POST', [
			    { name: 'token', value: token },
			]);
		})
	}

	function FuncClickbtnPenjualanFormulirFinance()
	{
		$("#btnPenjualanFormulirFinance").click(function(){
			var cf = $(".dateOP:checked").val();
			if (cf == '' || cf == null) {
				toastr.error('Mohon pilih Date Range atau By Month','Failed!')
			} else {
				var url = base_url_js+'admission/export_PenjualanFormulirFinance';
				data = {
				  cf : cf,
				  dateRange1 : $("#dateRange1").val(),
				  dateRange2 : $("#dateRange2").val(),
				  SelectMonth : $("#SelectMonth").val(),
				  SelectYear : $("#SelectYear").val(),
				  SelectSetTa : $("#SelectSetTa").val(),
				  SelectSortBy : $("#SelectSortBy").val(),
				}
				var token = jwt_encode(data,"UAP)(*");
				FormSubmitAuto(url, 'POST', [
				    { name: 'token', value: token },
				]);
			}
			
		})
	}

	function FuncClickbtnPenjualanFormulirData()
	{
		$("#btnPenjualanFormulirData").click(function(){
			var cf = $(".dateOP:checked").val();
			if (cf == '' || cf == null) {
				toastr.error('Mohon pilih Date Range atau By Month','Failed!')
			} else {
				var url = base_url_js+'admission/export_PenjualanFormulirData';
				data = {
				  cf : cf,
				  dateRange1 : $("#dateRange1").val(),
				  dateRange2 : $("#dateRange2").val(),
				  SelectMonth : $("#SelectMonth").val(),
				  SelectYear : $("#SelectYear").val(),
				  SelectSetTa : $("#SelectSetTa").val(),
				  SelectSortBy : $("#SelectSortBy").val(),
				}
				var token = jwt_encode(data,"UAP)(*");
				FormSubmitAuto(url, 'POST', [
				    { name: 'token', value: token },
				]);
			}
			
		})
	}

	function FuncClickbtnPengembalianFormulirData()
	{
		$("#btnPengembalianFormulirData").click(function(){
			var cf = $(".dateOP:checked").val();
			if (cf == '' || cf == null) {
				toastr.error('Mohon pilih Date Range atau By Month','Failed!')
			} else {
				var url = base_url_js+'admission/export_PengembalianFormulirData';
				data = {
				  cf : cf,
				  dateRange1 : $("#dateRange1").val(),
				  dateRange2 : $("#dateRange2").val(),
				  SelectMonth : $("#SelectMonth").val(),
				  SelectYear : $("#SelectYear").val(),
				  SelectSetTa : $("#SelectSetTa").val(),
				  SelectSortBy : $("#SelectSortBy").val(),
				}
				var token = jwt_encode(data,"UAP)(*");
				FormSubmitAuto(url, 'POST', [
				    { name: 'token', value: token },
				]);
			}
			
		})
	}

	function FuncDisabledInput()
	{
		$("#dateRange1").attr('disabled','true');
		$("#dateRange2").attr('disabled','true');
		$("#SelectMonth").attr('disabled','true');
		$("#SelectYear").attr('disabled','true');
	}

	function FuncClickDateOP()
	{
		$(".dateOP").click(function(){
			FuncDisabledInput();
			$('input.dateOP').prop('checked', false);
			$(this).prop('checked',true);
			$(this).closest(".row").find("input").removeAttr("disabled");
			$(this).closest(".row").find("select").removeAttr("disabled");
		})
	}

	function FuncfirstLoad()
	{
	      $("#dateRange1").datepicker({
			    dateFormat: 'yy-mm-dd',

		  });
	      $("#dateRange2").datepicker({
	  		    dateFormat: 'yy-mm-dd',

	  	  });

  	      $("#datechoose").datepicker({
  	  		    dateFormat: 'yy-mm-dd',

  	  	  });
		FuncLoadMajor();
		FuncLoadMonth();
		FuncLoadYears();
	}

	function FuncLoadYears()
	{
	    var thisYear = (new Date()).getFullYear();
	    var startTahun = parseInt('2014');

	    var selisih = parseInt(thisYear) + parseInt(1) - startTahun;
	    $('#SelectYear').empty();
	    for (var i = 0; i <= selisih; i++) {
	      var selected = (i==(selisih)) ? 'selected' : '';
	      var selected2 = (i==(selisih - 1)) ? 'selected' : '';
	      $('#SelectYear').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected2+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
	      $('#SelectSetTa').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
	      $('#SelectYearDataMHS').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
	    }

	    $('#SelectYear').select2({
	     // allowClear: true
	    });

	    $('#SelectSetTa').select2({
	      // allowClear: true
	    });

	    $('#SelectYearDataMHS').select2({
	      // allowClear: true
	    });
	}

	function FuncLoadMonth()
	{
		$('#SelectMonth').empty();
		var month = {
			01 : 'Jan',
			02 : 'Feb',
			03 : 'Mar',
			04 : 'April',
			05 : 'Mei',
			06 : 'Jun',
			07 : 'Jul',
			08 : 'Aug',
			09 : 'Sep',
			10 : 'Okt',
			11 : 'Nov',
			12 : 'Des'
		}

		for(var key in month) {
			var selected = (key==1) ? 'selected' : '';
			var getKey = key.toString();
			if (getKey.length == 1) {
				var value = '0' + getKey;
			}
			else
			{
				var value = key;
			}
			$('#SelectMonth').append('<option value="'+ value +'" '+selected+'>'+month[key]+'</option>');
		}

		$('#SelectMonth').select2({
		  // allowClear: true
		});
	}

	function FuncLoadMajor()
	{
		var url = base_url_js+"api/__getBaseProdiSelectOption";
		$('#Prodi').empty();
        $('#Prodi').append('<option value="'+0+'" '+'selected'+'>'+'--All--'+'</option>');
		$.post(url,function (data_json) {
		      for(var i=0;i<data_json.length;i++){
		          $('#Prodi').append('<option value="'+data_json[i].ID+'" '+''+'>'+data_json[i].Name+'</option>');
		      }
		      $('#Prodi').select2({
		         //allowClear: true
		      });
		}).done(function () {
		  
		});
	}
</script>