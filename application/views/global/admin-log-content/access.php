<?php $message = $this->session->flashdata('message');
    if(!empty($message)){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        toastr.info("<?= $this->session->flashdata('message');?>",'Info!');
    });
    </script>
<?php } ?>
<style type="text/css">
	.checkbox-lb{font-weight: 100;cursor: pointer;}
</style>
<div id="access-logs">
	<div class="row" style="margin-bottom:15px">
		<div class="col-sm-4">
			<div class="btn-group">
				<button class="btn btn-warning btn-sm" type="button" onclick="window.history.go(-1); return false;"><i class="fa fa-angle-double-left"></i> Going back</button>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-3">
			<form id="form-access-control" action="<?=base_url('admin-log-config-save')?>" method="post" autocomplete="off">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"><i class="fa fa-edit"></i> Form Access control</h4>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<input type="hidden" name="ID">
							<label>Division</label>
							<select class="select2-select-00 full-width-fix select2-required" id="DivisionID" name="DivisiID">
			                  <option>Choose one</option>
			                  <?php for($i = 0; $i < count($G_division); $i++): ?>
			                    <option value="<?php echo $G_division[$i]['Code'] ?>" > <?php echo $G_division[$i]['Name2'] ?> </option>
			                  <?php endfor ?>
			                 </select>
			                 <span class="text-danger text-message"></span>
						</div>
						<div class="form-group">
							<label>Type Content</label>
							<select class="form-control required" name="TypeContent">
								<option value="">-choose one-</option>
								<option <?=($typecontent == 'user_qna') ? 'selected':''?> value="user_qna">Help</option>
								<option <?=($typecontent == 'knowledge_base') ? 'selected':''?> value="knowledge_base">Knowledge Base</option>
							</select>
							<span class="text-danger text-message"></span>
						</div>
						<div class="form-group">
							<label>Give an access :</label>
						</div>
						<div class="form-group">
							<label class="checkbox-lb"><input type="checkbox" name="IsLogEmp" value="Y"> access for Logs Employee</label>
						</div>
					</div>
					<div class="panel-footer text-right">
						<button class="btn btn-sm btn-primary btn-save" type="button">Save changes</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-sm-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fa fa-bars"></i> List of access control</h4>
				</div>
				<div class="panel-body">
					<div class="fetch-data table-responsive">
						<table class="table table-bordered" id="table-list-data">
							<thead>
								<tr>
									<th width="5%">No</th>
									<th>Division</th>
									<th>Type Content</th>
									<th>Access</th>
									<th>Remove</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="5">No data available in table</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>


<script type="text/javascript">
	function fetchLogActivity() {
		var filtering = $("#form-filter").serialize();		

        var token = jwt_encode({POST : 'fetching'},'UAP)(*');
        var dataTable = $('#table-list-data').DataTable( {
            "destroy": true,
            "ordering" : false,
            "retrieve":true,
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 5,
            "responsive": true,
            "ajax":{
                url : base_url_js+'admin-access-control', // json datasource
                ordering : false,
                data : {token:token},
                type: "post",  // method  , by default get
                error: function(jqXHR){  // error handling
                    loading_modal_hide();
                    $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        '<h4 class="modal-title">Error Fetch Student Data</h4>');
                    $('#GlobalModal .modal-body').html(jqXHR.responseText);
                    $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
                    $('#GlobalModal').modal({
                        'show' : true,
                        'backdrop' : 'static'
                    });
                }
            },
            "initComplete": function(settings, json) {
                //loading_modal_hide();
            },
            "columns": [
            	{
            		"data":"ID",
            		render: function (data, type, row, meta) {
				        return meta.row + meta.settings._iDisplayStart + 1;
				    }
            	},
            	{
            		"data":"DivisionName",
            		"render": function (data, type, row, meta) {
            			var label = data;
            			return label;
            		}
            	},
            	{
            		"data":"TypeContent",
            		"render": function (data, type, row, meta) {
            			var label = data;
            			var name = 'undefined';
            			if(data == 'user_qna'){
            				name = 'help';
            			}else if(data == 'knowledge_base'){
            				name = 'knowledge base';
            			}
            			label = '<span class="capitalize">'+name+'</span>'
            			return label;
            		}
            	},
            	{
            		"data":"IsLogEmp", 
            		"render": function (data, type, row, meta) {
            			var label = ((data == 'Y') ? "Has an access to ":"Doesn't have an access to")+" <b>List of Employee Log</b>";
            			return label;
            		}           		
            	},
            	{
            		"data":"ID", 
            		"render": function (data, type, row, meta) {
            			var label = '<button class="btn btn-sm btn-danger btn-remove" type="button" data-TypeContent="'+row.TypeContent+'" data-id="'+data+'" ><i class="fa fa-trash"></i></button>';
            			return label;
            		}           		
            	},
        	]
        });
	}
	$(document).ready(function(){
		fetchLogActivity();
		$("#form-access-control .btn-save").click(function(){
			var itsme = $(this);
			var itsform = itsme.parent().parent().parent();
            itsform.find(".select2-required").each(function(){
                var value = $(this).val();
                console.log(value);
                if($.trim(value) == ''){
                    $(this).addClass("error");
                    $(this).parent().find(".text-message").text("Please fill this field");
                    error = false;
                }else{
                    error = true;
                    $(this).removeClass("error");
                    $(this).parent().find(".text-message").text("");
                }
            });
            itsform.find(".required").each(function(){
                var value = $(this).val();
                if($.trim(value) == ''){
                    $(this).addClass("error");
                    $(this).parent().find(".text-message").text("Please fill this field");
                    error = false;
                }else{
                    error = true;
                    $(this).removeClass("error");
                    $(this).parent().find(".text-message").text("");
                }
            });
            
            var totalError = itsform.find(".error").length;
            if(error && totalError == 0 ){
                loading_modal_show();
                $("#form-access-control")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
		});
		$("#table-list-data").on("click",".btn-remove",function(){
			var itsme = $(this);
			var ID = itsme.data('id');
			var TypeContent = itsme.data('TypeContent');

			var dataPost = {
		        ID : ID,
		        TypeContent : TypeContent
	      	}
		        
	      	var token = jwt_encode(dataPost,'UAP)(*');

	      	$.ajax({
		        type : 'POST',
		        url : base_url_js+"admin-access-remove",
		        data : {token:token},
		        dataType : 'json',
		        beforeSend :function(){itsme.html('<i class="fa fa-spinner fa-spin"></i>');},
		        error : function(jqXHR){
					$('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
		                '<h4 class="modal-title">Error !</h4>');
					$("body #GlobalModal .modal-body").html(jqXHR.responseText);
					$('body #GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
					$("body #GlobalModal").modal("show");
		        },success : function(response){
		        	if(!jQuery.isEmptyObject(response)){
		        		toastr.success('Info',response.message);
		        		$('body #table-list-data').DataTable().destroy();
		        		fetchLogActivity();
		        	}
		        }
		    });

		});
	});
</script>