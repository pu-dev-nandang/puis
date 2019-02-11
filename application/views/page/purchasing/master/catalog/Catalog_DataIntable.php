<style type="text/css">
	#datatablesServer thead th,#datatablesServer tfoot td {

	    text-align: center;
	    background: #20485A;
	    color: #FFFFFF;

	}

	#datatablesServer>thead>tr>th, #datatablesServer>tbody>tr>th, #datatablesServer>tfoot>tr>th, #datatablesServer>thead>tr>td, #datatablesServer>tbody>tr>td, #datatablesServer>tfoot>tr>td {
	    border: 1px solid #b7b7b7
	}
</style>
<div class="row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">
	<div class="col-md-2">
		<label>Import</label>
	</div>
	<div class="col-md-2">
		<input type="file" data-style="fileinput" id="ImportFile" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
		<br>
		<a href="<?php echo base_url('fileGetAny-budgeting-catalog-templateimport.xlsx') ?>">Template</a>
	</div>
	<div class="col-md-2">
		<button class="btn btn-inverse" id = "sbmtimportfile">Import</button>
	</div>
</div>
<div class="row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">
	<div class="col-xs-12">
		<div class="table-responsive">
			<table class="table table-bordered tableData" id ="datatablesServer">
				<thead>
					<tr>
						<th width = "3%">No</th>
						<th>Item</th>
						<th>Desc</th>
						<th>Estimate Value</th>
						<th>Photo</th>
						<th>Departement</th>
						<th>DetailCatalog</th>
						<th>CreatedBy</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$.fn.dataTable.ext.errMode = 'throw';
		//alert('hsdjad');
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

		var dataTable = $('#datatablesServer').DataTable( {
		    "processing": true,
		    "destroy": true,
		    "serverSide": true,
		    "iDisplayLength" : 10,
		    "ordering" : false,
		    "ajax":{
		        url : base_url_js+"purchasing/page/catalog/DataIntable/server_side", // json datasource
		        ordering : false,
		        type: "post",  // method  , by default get
		        data : {action : "<?php echo $action ?>"},
		        error: function(){  // error handling
		            $(".employee-grid-error").html("");
		            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
		            $("#employee-grid_processing").css("display","none");
		        },
		    },
		    'createdRow': function( row, data, dataIndex ) {
		        /*var no = 'row'+(dataIndex + 1);
		          $(row).attr('id', no);*/
		    },
		    // "fnDrawCallback": function(data) {
		    	   	            
		    // },
		} );

		$('#datatablesServer tbody').on('click', '.btn-edit-catalog', function () {
			$('.pageAnchor[page="DataIntable"]').trigger('click');
			var ID = $(this).attr('code');
	      	if (CountColapses == 0) {
	      		// $('.pageAnchor[page="FormInput"]').trigger('click');
	      		$('#FormInput').show();
	      		var page = 'FormInput';
	      		loading_page("#page"+page);
	      		var url = base_url_js+'purchasing/page/catalog/'+page;
	      		var data = {
	      			action : 'edit',
	      			ID : ID,
	      		}
	      		var token = jwt_encode(data,"UAP)(*");
	      		$.post(url,{token: token},function (resultJson) {
	      		    var response = jQuery.parseJSON(resultJson);
	      		    var html = response.html;
	      		    var jsonPass = response.jsonPass;
	      		    $("#page"+page).html(html);
	      		}); // exit spost
		    }
	      	else
	      	{
	      		var page = 'FormInput';
	      		loading_page("#page"+page);
	      		var url = base_url_js+'purchasing/page/catalog/'+page;
	      		var data = {
	      			action : 'edit',
	      			ID : ID,
	      		}
	      		var token = jwt_encode(data,"UAP)(*");
	      		$.post(url,{token: token},function (resultJson) {
	      		    var response = jQuery.parseJSON(resultJson);
	      		    var html = response.html;
	      		    var jsonPass = response.jsonPass;
	      		    $("#page"+page).html(html);
	      		}); // exit spost
	      	}
		});

		$('#datatablesServer tbody').on('click', '.btn-delete-catalog', function () {
			if (confirm("Are you sure?") == true) {
				var data = {
		  	                    Detail : '',
          		                Action : "delete",
          		                Departement : '',
          		                Item : '',
          		                Desc : '',
          		                EstimaValue : '',
          		                ID : $(this).attr('code'),
	  	                   };
			  	var token = jwt_encode(data,"UAP)(*");
			  	var url = base_url_js + "purchasing/page/catalog/saveFormInput";
			  	$.post(url,{token:token},function (data_json) {
  	               var obj = JSON.parse(data_json); 
  	               if(obj == "")
  	               {
  	               	var page = 'DataIntable';
  	               	LoadPageCatalog(page);
  	               	toastr.success("Done", 'Success!');
  	               }
  	               else
  	               {
  	               	toastr.error(obj,'Failed!!');
  	               }

  	           }).done(function() {
  	             
  	           }).fail(function() {
  	             toastr.error('The Database connection error, please try again', 'Failed!!');
  	           }).always(function() {
  	           		

  	           });
			}	
			else {
                return false;
            }
		});

		$('#datatablesServer tbody').on('click', '.btn-approve-catalog', function () {
			if (confirm("Are you sure?") == true) {
				var data = {
		  	                    Detail : '',
          		                Action : "approve",
          		                Departement : '',
          		                Item : '',
          		                Desc : '',
          		                EstimaValue : '',
          		                ID : $(this).attr('code'),
	  	                   };
			  	var token = jwt_encode(data,"UAP)(*");
			  	var url = base_url_js + "purchasing/page/catalog/saveFormInput";
			  	$.post(url,{token:token},function (data_json) {
  	               var obj = JSON.parse(data_json); 
  	               if(obj == "")
  	               {
  	               	var page = 'ApprovalCatalog';
  	               	LoadPage(page)
  	               	toastr.success("Done", 'Success!');
  	               }
  	               else
  	               {
  	               	toastr.error(obj,'Failed!!');
  	               }

  	           }).done(function() {
  	             
  	           }).fail(function() {
  	             toastr.error('The Database connection error, please try again', 'Failed!!');
  	           }).always(function() {
  	           		

  	           });
			}	
			else {
                return false;
            }
		});

	}); // exit document Function
</script>