<style type="text/css">
	/* Button Upload */
	input[type="file"] {
	    display: none;
	}
	.custom-file-upload {
	    border: 1px solid #ccc;
	    display: inline-block;
	    padding: 3px 3px;
	    cursor: pointer;
	} 

	.table thead {
	    background: #d91f2d;
	    color: #fff;
	}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="row" style="margin-left: 0px;margin-right: 0px">
			<div class="col-md-1">
				<label class="btn btn-default"><i class="fa fa-upload" aria-hidden="true"></i> Upload <input class = "file-upload" type="file" style="display: none;"></label>
				</br>
				<a href="<?php echo base_url('download_template/admisi-t_crm_import.xlsx'); ?>">File Template</a>
			</div>
			<!-- <div class="col-md-1">
				<button class="btn btn-default" id = "ExportEx"> <i class="fa fa-download" aria-hidden="true"></i> Download</button>
			</div> -->
		</div>
	</div>
</div>
<div class="row" style="margin-top: 10px;margin-left: 0px;margin-right: 0px">
	<div class="col-md-12">
		<div class="widget box">
		    <div class="widget-header">
		        <h4 class="header"><i class="icon-reorder"></i></h4>
		        <!-- <div class="toolbar no-padding">
		            <div class="btn-group">
		              <span data-smt="" class="btn btn-xs btn-add-event btn-add">
		                <i class="icon-plus"></i> Add
		               </span>
		            </div>
		        </div> -->
		    </div>
		    <div class="widget-content">
		        <div class="row">
		        	<div class="col-md-12">
		        		<div class="table-responsive">
		        			<table class="table table-bordered tableData" id ="tableData3">
		        				<thead>
		        					<tr>
		        						<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">ID</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Candidate Name</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Regional</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">School</th>
		        						<!-- <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Class</th> -->
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Pathway</th>
		        						<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">Gender</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Prospect Year</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Phone</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">MobilePhone</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Email</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">ParentName</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Action</th>
		        					</tr>
		        				</thead>
		        				<tbody>
		        				</tbody>
		        			</table>
		        		</div>
		        	</div>
		        </div>
		        <!-- -->
		    </div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		loadtable();
		$(document).off('change', '.file-upload').on('change', '.file-upload',function(e) {
			loadingStart();
			var files = $(this)[0].files;
			files = files[0];
			var validation_file = validation_files(files);
			if (validation_file) {
				var form_data = new FormData();
				var url = base_url_js + "admission/crm/import";
				form_data.append("fileData", files);
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
				  	if (data != '') {
				  		toastr.error(data,'!!!Failed')
				  	}
				  	else
				  	{
				  		loadtable();
				  	}

				    loadingEnd(1000);
				  },
				  error: function (data) {
				    toastr.error(data.msg, 'Connection error, please try again!!');
				  }
				})
			}
			else
			{
				 loadingEnd(1);
			}
		})

		function validation_files(files)
		{
			var name = files.name;
			var extension = name.split('.').pop().toLowerCase();
			var msgStr = '';
			if(jQuery.inArray(extension, ['xls','xlsx']) == -1)
			{
			 msgStr += 'File Number '+ no + ' Invalid Type File<br>';
			}

			var oFReader = new FileReader();
			oFReader.readAsDataURL(files);
			var f = files;
			var fsize = f.size||f.fileSize;

			if(fsize > 2000000) // 2mb
			{
			 msgStr += 'File Number '+ no + ' Image File Size is very big<br>';
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

		function loadtable()
		{
			$("#tableData3 tbody").empty();
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

			var table = $('#tableData3').DataTable( {
				"fixedHeader": true,
			    "processing": true,
			    "destroy": true,
			    "serverSide": true,
			    "iDisplayLength" : 25,
			    "ordering" : false,
			    "ajax":{
			        url : base_url_js+"admission/crm/showdata", // json datasource
			        ordering : false,
			        type: "post",  // method  , by default get
			        // data : {length : $("select[name='tableData4_length']").val()},
			        error: function(){  // error handling
			            $(".employee-grid-error").html("");
			            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
			            $("#employee-grid_processing").css("display","none");
			        }
			    },
			    'createdRow': function( row, data, dataIndex ) {
			    	  var btndel = '<button type="button" class="btn btn-danger btn-delete btn-delete-data" data = "'+data[13]+'"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
			    	  $( row ).find('td:eq(12)').html(btndel);
			    },
			} );


			table.on('click', '.btn-delete-data',function(e) {
				var IDTable = $(this).attr('data');
				if (confirm("Are you sure ?") == true) {
					var url = base_url_js+"admission/crm/delete/byid";
					var data = {
						ID : IDTable
					}
					var token = jwt_encode(data,'UAP)(*');
					$.post(url,{token : token},function(a,b,c){
						loadtable();
					})
				}
			})
		}
	})
</script>