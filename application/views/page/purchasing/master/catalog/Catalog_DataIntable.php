
<div class="row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px" id ="PageImport">
	<div class="well">
		<div class="row">
			<div class="col-md-2">
				<label>Import</label>
			</div>
			<div class="col-md-2">
				<input type="file" data-style="fileinput" id="ImportFile" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,.xlsm">
				<br>
				<a href="<?php echo base_url('download_template/budgeting-catalog-m_catalog.xlsm') ?>">Template</a>
			</div>
			<div class="col-md-2">
				<button class="btn btn-inverse" id = "sbmtimportfile">Import</button>
			</div>
		</div>
	</div>
	
</div>
<div class="row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">
	<div class="col-xs-12">
		<div class="table-responsive">
			<table class="table table-bordered tableData" id ="datatablesServer">
				<thead>
					<tr>
						<th width = "3%">No</th>
						<th>Item & Category</th>
						<th>Desc</th>
						<th>Estimate Value</th>
						<th>Photo</th>
						<th>Department</th>
						<th>DetailCatalog</th>
						<th>CreatedBy</th>
						<th>Status</th>
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
	      	// if (CountColapses == 0) {
	      	if ($('#FormInput').attr('class') == 'collapse') {
	      		//$('.pageAnchor[page="FormInput"]').trigger('click');
	      		//$('#FormInput').show();
	      		$('#FormInput').attr('class','in');
	      		$('#FormInput').attr('style','height: auto;');
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

		$('#datatablesServer tbody').on('click', '.btn-reason', function () {
			var Reason = $(this).attr('reason');
			var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>'+
			    '';
			$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Reason'+'</h4>');
			$('#GlobalModalLarge .modal-body').html(Reason);
			$('#GlobalModalLarge .modal-footer').html(footer);
			$('#GlobalModalLarge').modal({
			    'show' : true,
			    'backdrop' : 'static'
			});
		});	

		$('#datatablesServer tbody').on('click', '.btn-reject-catalog', function () {
			var ID = $(this).attr('code');

			$('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Please Input Reason ! </b> <br>' +
			    '<input type = "text" class = "form-group" id ="NoteDel" style="margin: 0px 0px 15px; height: 30px; width: 329px;" maxlength="30"><br>'+
			    '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
			    '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
			    '</div>');
			$('#NotificationModal').modal('show');
			$("#confirmYes").click(function(){
				var Reason = $("#NoteDel").val();
				$('#NotificationModal .modal-header').addClass('hide');
				$('#NotificationModal .modal-body').html('<center>' +
				    '                    <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>' +
				    '                    <br/>' +
				    '                    Loading Data . . .' +
				    '                </center>');
				$('#NotificationModal .modal-footer').addClass('hide');
				$('#NotificationModal').modal({
				    'backdrop' : 'static',
				    'show' : true
				});

				var data = {
		  	                    Detail : '',
          		                Action : "reject",
          		                Departement : '',
          		                Item : '',
          		                Desc : '',
          		                EstimaValue : '',
          		                ID : ID,
          		                Reason : Reason,
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
  	             $('#NotificationModal').modal('hide');
  	           }).fail(function() {
  	             toastr.error('The Database connection error, please try again', 'Failed!!');
  	           }).always(function() {
  	           		

  	           });

			})
			
		});

	}); // exit document Function
</script>