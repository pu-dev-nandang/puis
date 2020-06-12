<style type="text/css">
	.biodata .profile-info > h3{margin:0px;padding: 5px}
    .biodata .profile-info > h3:first-child{font-weight: bold;text-transform: uppercase; }
    .bgx{border:1px solid #ddd;padding: 6px 13px;font-weight: normal;border: 1px solid rgba(0, 0, 0, 0.13);}
    .bgx.green{background-color: #51a351;color:#fff;}
    .bgx.red{background-color: #bd362f;color:#fff;}
    .bgx.blue{background-color: #3968c6;color:#fff;}
</style>
<div id="detail-log">
	<div class="biodata">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <?php 
                    $today = date("Y-m-d");
                    $birthDate = $employee->DateOfBirth;
                    $diff = date_diff(date_create($birthDate), date_create($today));
                    $myAge = $diff->format('%y');
                    ?>
                    <div class="col-sm-1 text-center">
                        <img class="img-thumbnail" id="ProfilePicture" style="max-width: 100px;width: 100%;" src="<?=$employee->ProfilePic?>">
                    </div>
                    <div class="col-sm-4">
                        <div class="profile-info">
                            <h3><?=(!empty($employee->TitleAhead) ? $employee->TitleAhead.' ' : '').$employee->Name.(!empty($employee->TitleBehind) ? ', '.$employee->TitleBehind : '')?></h3>
                            <h3><?=$employee->NIP?></h3>
                                                        
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="profile-info">
                            <h3>Department <?=$employee->DivisionMain.'-'.$employee->PositionMain?></h3>
                            <h3><?=$employee->EmailPU?></h3>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="text-right">
                            <p><span class="bgx <?=(($employee->StatusEmployeeID == 2) ? 'green': ( ($employee->StatusEmployeeID == 1) ? 'blue':'red' ) )?>">
                            <i class="fa fa-handshake-o"></i> <?=strtoupper($employee->EmpStatus)?>
                            </span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<div class="row">
		<div class="col-sm-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fa fa-filter"></i> Filter</h4>
				</div>
				<div class="panel-body">
					<form id="form-filter-log" action="" method="post" autocomplete="off">
						<div class="form-group">
							<input type="hidden" name="TypeContent" value="<?=$TypeContent?>">
							<input type="hidden" name="NIP" value="<?=$employee->NIP?>">
							<label>Type</label>
							<select class="form-control" name="type">
								<option value="">-choose one-</option>
								<?php if(!empty($FType)){
								foreach ($FType as $v) { ?>
								<option value="<?=$v->Type?>"><?=$v->Type?></option>
								<?php }	} ?>
							</select>
						</div>
						<div class="form-group">
							<label>Question</label>
							<input type="text" name="question" class="form-control">
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
							       <label>Date</label>
							       <input type="text" name="startDate" id="startDate" class="form-control" placeholder="Start Date">
						        </div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
							       <label>Date</label>
							       <input type="text" name="endDate" id="endDate" class="form-control" placeholder="until end date">
						        </div>
							</div>
						</div>
						<button class="btn btn-block btn-primary btn-filter" type="button"><i class="fa fa-search"></i> Search</button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fa fa-bars"></i> List history</h4>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered" id="table-log-emp">
							<thead>
								<tr>
									<th width="5%">No</th>
									<th>Type</th>
									<th>Question</th>
									<th>Last Time read</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="4">No data available in table</td>
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
	function fetchLogActivityEmp() {
		var filtering = $("#form-filter-log").serialize();		

        var token = jwt_encode({Filter : filtering},'UAP)(*');
        var dataTable = $('#table-log-emp').DataTable( {
            "destroy": true,
            "ordering" : false,
            "retrieve":true,
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 5,
            "responsive": true,
            "ajax":{
                url : base_url_js+'admin-fetch-log-employee', // json datasource
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
            		"data":"NIP",
            		render: function (data, type, row, meta) {
				        return meta.row + meta.settings._iDisplayStart + 1;
				    }
            	},
            	{
            		"data":"Type",
            		"render": function (data, type, row, meta) {
            			var label = data;
            			return label;
            		}
            	},
            	{
            		"data":"Questions",
            		"render": function (data, type, row, meta) {
            			var label = data;
            			return label;
            		}
            	},
            	{
            		"data":"ViewedAt", 
            		"render": function (data, type, row, meta) {
            			var label = data;
            			return label;
            		}           		
            	},
        	]
        });
	}

	$(document).ready(function(){
		fetchLogActivityEmp();
		$("#startDate,#endDate").datepicker({
			dateFormat: 'dd-mm-yy',
		    changeYear: true,
		    changeMonth: true,
		});

		$("#form-filter-log .btn-filter").click(function(){
	    	$('body #table-log-emp').DataTable().destroy();
	        fetchLogActivityEmp();
	    });

	});
</script>