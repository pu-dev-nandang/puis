<div class="row">
	<div class="col-md-12">
		<div class="col-md-6 col-md-offset-3">
			<div class="thumbnail" style="height: 100px">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<div class="form-group">
							<label>Year</label>
							<select class="select2-select-00 full-width-fix" id="Years">
							     <!-- <option></option> -->
							 </select>
						</div>	
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<p style="color: red;font-size: 20px">(.000)</p>
					</div>
					<div class="col-md-8 col-md-offset-1">
						<b>Status Budget: </b><i class="fa fa-circle" style="color:#8ED6EA;"></i> Approve | <i class="fa fa-circle" style="color: #eade8e;"></i> Not Approve | <i class="fa fa-circle" style="color: #da2948;"></i> Not Set
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row" id = "PageTable" style="margin-top: 10px;margin-right: 0px;margin-left: 10px">

</div>
<script type="text/javascript">
	$(document).ready(function() {
		LoadFirstLoad()
	    loadingEnd(500);
	}); // exit document Function

	function LoadFirstLoad()
	{
		$("#pageInputApproval").remove();
		$("#pageInput").remove();
		// load Year
		$("#Years").empty();
		var url = base_url_js+'budgeting/table_all/cfg_dateperiod/1';
		var thisYear = (new Date()).getFullYear();
		$.post(url,function (resultJson) {
			var response = jQuery.parseJSON(resultJson);
			for(var i=0;i<response.length;i++){
			    //var selected = (i==0) ? 'selected' : '';
			    var selected = (response[i].Activated==1) ? 'selected' : '';
			    $('#Years').append('<option value="'+response[i].Year+'" '+selected+'>'+response[i].Year+' - '+(parseInt(response[i].Year) + 1)+'</option>');
			}
			$('#Years').select2({
			   //allowClear: true
			});

			// get change function
			$("#Years").change(function(){
				loadPageData();
			})

			loadPageData();
		}); 
	}

	function loadPageData()
	{
		loading_page("#PageTable");
		var url = base_url_js+"budgeting/getListBudgetingDepartement";
		var data = {
				    Year : $("#Years").val() ,
				};
		var token = jwt_encode(data,'UAP)(*');
		$.post(url,{token:token},function (resultJson) {
			var rs = jQuery.parseJSON(resultJson);
			var response = rs['dt'];
			var arr1 = rs['dt_Year'];
			// console.log(arr1);
			var filee = (arr1[0].BudgetApproveUpload != '' && arr1[0].BudgetApproveUpload != null && arr1[0].BudgetApproveUpload != undefined) ? '<a href = "'+base_url_js+'fileGetAny/budgeting-'+arr1[0].BudgetApproveUpload+'" target="_blank" class = "btn btn-warning Fileexist">File '+'</a>&nbsp' : '';
			// console.log(response);
			var test = '<div class = "row"><div class="col-md-12"><div class="col-md-2 col-md-offset-10" align = "right">'+filee+'<label class="btn btn-primary" style="color: #ffff;">Upload Budget File <input id="file-upload" type="file" style="display: none;" Year = "'+data['Year']+'" accept="image/*,application/pdf"></label>&nbsp<button class = "btn btn-excel-all" Year = "'+data['Year']+'" ><i class="fa fa-download"></i> Excel</button></div></div></div>';
			var TableGenerate = '<div class = "row"style = "margin-top : 10px"><div class="col-md-12" id = "pageForTable">'+
									'<div class="table-responsive">'+
										'<table class="table table-bordered tableData" id ="tableData3">'+
										'<thead>'+
										'<tr>'+
											'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Departement</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Grand Total Budget</th>'+
											'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Status</th>'+
											'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Print</th>'+
										'</tr></thead>'	
								;
			TableGenerate += '<tbody>';
			var total = 0;
			for (var i = 0; i < response.length; i++) {
				var st = '';
				Print = '';
				if(response[i].Status == 2)
				{
					st = '<i class="fa fa-circle" style="color:#8ED6EA;"></i>';
					Print = '<button class = "btn btn-excel" id_creator_budget = "'+response[i].ID_creator_budget+'"><i class="fa fa-file-excel-o"></i> Excel</button>';
				}
				else if(response[i].Status == 0 || response[i].Status == 1 || response[i].Status == 3)
				{
					st = '<i class="fa fa-circle" style="color: #eade8e;"></i>';
				}
				else
				{
					st = '<i class="fa fa-circle" style="color: #da2948;"></i>';
				}
				var GrandTotal = parseInt(response[i].GrandTotal) / 1000;
				TableGenerate += '<tr>'+
									'<td width = "3%">'+ (parseInt(i) + 1)+'</td>'+
									'<td>'+ response[i].NameDepartement+'</td>'+
									'<td>'+ formatRupiah(GrandTotal) +'</td>'+
									'<td>'+ st+'</td>'+
									'<td>'+ Print+'</td>'+
								'</tr>';
				total = parseInt(total) + parseInt(response[i].GrandTotal);				
			}

			TableGenerate += '</tbody></table></div></div></div>';
			var SumTotal = '<div class = "row" style = "margin-top : 10px"><div class="col-md-12">'+
								'<div class="col-md-3 col-md-offset-9" style="background-color : #20485A; min-height : 50px;color: #FFFFFF;" align="center"><h4>Total : '+formatRupiah(total)+'</h4>'+
								'</div>'+
							'</div></div>';
			$("#PageTable").html(test+TableGenerate+SumTotal);
			var t = $('#tableData3').DataTable({
				"pageLength": 10
			});
			// console.log(response);

			funcExportExcel();
		});
	}

	function funcExportExcel()
	{
		$('#tableData3 tbody').on('click', '.btn-excel', function () {
		// $(".btn-excel").click(function(){
			var id_creator_budget_approval = $(this).attr('id_creator_budget');

			var url = base_url_js+'budgeting/export_excel_budget_creator';
			data = {
			  id_creator_budget_approval : id_creator_budget_approval,
			}
			var token = jwt_encode(data,"UAP)(*");
			FormSubmitAuto(url, 'POST', [
			    { name: 'token', value: token },
			]);
		})

		$(".btn-excel-all").click(function(){
			var Year = $(this).attr('Year');

			var url = base_url_js+'budgeting/export_excel_budget_creator_all';
			data = {
			  Year : Year,
			}
			var token = jwt_encode(data,"UAP)(*");
			FormSubmitAuto(url, 'POST', [
			    { name: 'token', value: token },
			]);
		})
	}

	$(document).off('change', '#file-upload').on('change', '#file-upload',function(e) {
		var year = $(this).attr('year');
		var ID_element = $(this).attr('id');
		var attachName = year+'_FileBudgeting';
		if (file_validation(ID_element)) {
		  UploadFile_approve(ID_element,year,attachName);
		}
	})

	function UploadFile_approve(ID_element,year,attachName)
	{
		var form_data = new FormData();
		//var fileData = document.getElementById(ID_element).files[0];
		var url = base_url_js + "budgeting/Upload_File_Creatorbudget_all";
		var files = $('#'+ID_element)[0].files;
		    var nm = files[0].name;
			var extension = nm.split('.').pop().toLowerCase();
		var DataArr = {
		                year : year,
		                attachName : attachName,
		                extension : extension,
		              };
		var token = jwt_encode(DataArr,"UAP)(*");
		form_data.append('token',token);

		form_data.append("fileData", files[0]);
		$.ajax({
		  type:"POST",
		  url:url,
		  data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
		  contentType: false,       // The content type used when sending data to the server.
		  cache: false,             // To unable request pages to be cached
		  processData:false,
		  dataType: "json",
		  success:function(data)
		  {
		    if(data.status == 1) {
		      // show file in html before content_button find btn btn-primary
		      $('.Fileexist').remove();
		      var filee = '<a href = "'+base_url_js+'fileGetAny/budgeting-'+data.filename +'" target="_blank" class = "btn btn-warning Fileexist">File '+'</a>&nbsp';
		      var rr = $('.btn-excel-all').closest('.col-md-offset-10');
		      rr.find('.btn-primary').before(filee);
		      toastr.options.fadeOut = 100000;
		      toastr.success(data.msg, 'Success!');
		    }
		    else
		    {
		      toastr.options.fadeOut = 100000;
		      toastr.error(data.msg, 'Failed!!');
		    }
		  setTimeout(function () {
		      toastr.clear();
		    },1000);

		  },
		  error: function (data) {
		    toastr.error(data.msg, 'Connection error, please try again!!');
		  }
		})
	}

	function file_validation(ID_element)
	{
	    var files = $('#'+ID_element)[0].files;
	    var error = '';
	    var msgStr = '';
	    var name = files[0].name;
		  // console.log(name);
		  var extension = name.split('.').pop().toLowerCase();
		  if(jQuery.inArray(extension, ['pdf','jpg','png','jpeg']) == -1)
		  {
		   msgStr += 'Invalid Type File<br>';
		  }

		  var oFReader = new FileReader();
		  oFReader.readAsDataURL(files[0]);
		  var f = files[0];
		  var fsize = f.size||f.fileSize;
		  // console.log(fsize);

		  if(fsize > 5000000) // 5mb
		  {
		   msgStr += 'Image File Size is very big<br>';
		   //toastr.error("Image File Size is very big", 'Failed!!');
		   //return false;
		  }

	    if (msgStr != '') {
	      toastr.error(msgStr, 'Failed!!');
	      return false;
	    }
	    else
	    {
	      return true;
	    }
	}

</script>
