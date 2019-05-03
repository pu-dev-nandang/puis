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
	<div class="col-xs-12">
		<div class="table-responsive">
			<table class="table table-bordered tableData" id ="datatablesServer">
				<thead>
					<tr>
						<th width = "3%">No</th>
						<th>CodeSupplier</th>
						<th>Supplier</th>
						<!-- <th>Website</th>
						<th>PIC</th> -->
						<!-- <th>Alamat</th> -->
						<th>NoTelp & NoHp</th>
						<th>CategorySupplier</th>
						<th>DetailInfo</th>
						<th>DetailItem</th>
						<th>CreatedBy</th>
						<th width="15%">Action</th>
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
		        url : base_url_js+"purchasing/page/supplier/DataIntable/server_side", // json datasource
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

		$('#datatablesServer tbody').on('click', '.btn-edit-supplier', function () {
			$('.pageAnchor[page="DataIntable"]').trigger('click');
			var ID = $(this).attr('code');
	      	// if (CountColapses == 0) {
	      	if ($('#FormInput').attr('class') == 'collapse') {	
	      		// $('.pageAnchor[page="FormInput"]').trigger('click');
	      		// $('#FormInput').show();

	      		$('#FormInput').attr('class','in');
	      		$('#FormInput').attr('style','height: auto;');

	      		var page = 'FormInput';
	      		loading_page("#page"+page);
	      		var url = base_url_js+'purchasing/page/supplier/'+page;
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
	      		// $('.pageAnchor[page="FormInput"]').trigger('click');
	      		var page = 'FormInput';
	      		loading_page("#page"+page);
	      		var url = base_url_js+'purchasing/page/supplier/'+page;
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

	      		// CountColapses = 0;
	      	}
		});

		$('#datatablesServer tbody').on('click', '.btn-delete-supplier', function () {
			if (confirm("Are you sure?") == true) {
				var data = {
								NeedPrefix : '',
					            CodeSupplier : '',
					            NamaSupplier : '',
					            PICName : '',
					            Alamat : '',
					            Website : '',
					            NoTelp : '',
					            NoHp : '',
					            DetailInfo : '',
					            CategorySupplier : '',
					            DetailItem : '',
          		                Action : "delete",
          		                ID : $(this).attr('code'),
	  	                   };
			  	var token = jwt_encode(data,"UAP)(*");
			  	var url = base_url_js + "purchasing/page/supplier/saveFormInput";
			  	$.post(url,{token:token},function (data_json) {
  	               var obj = JSON.parse(data_json); 
  	               if(obj == "")
  	               {
  	               	var page = 'DataIntable';
  	               	LoadPageSupplier(page);
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

		$('#datatablesServer tbody').on('click', '.btn-approve-supplier', function () {
			if (confirm("Are you sure?") == true) {
				var data = {
                    			NeedPrefix : '',
                                CodeSupplier : '',
                                NamaSupplier : '',
                                PICName : '',
                                Alamat : '',
                                Website : '',
                                NoTelp : '',
                                NoHp : '',
                                DetailInfo : '',
                                CategorySupplier : '',
                                DetailItem : '',	
          		                Action : "approve",
          		                ID : $(this).attr('code'),
	  	                   };
			  	var token = jwt_encode(data,"UAP)(*");
			  	var url = base_url_js + "purchasing/page/supplier/saveFormInput";
			  	$.post(url,{token:token},function (data_json) {
  	               var obj = JSON.parse(data_json); 
  	               if(obj == "")
  	               {
  	               	var page = 'ApprovalSupplier';
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