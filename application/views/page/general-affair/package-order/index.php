<div id="general-affair">
	<div class="row">
		<div class="col-sm-12">
			<form id="form-package-order" action="<?=base_url('general-affair/save-package-order')?>" method="post" autocomplete="off">
			<div class="panel panel-default collapse" id="collapseFormPackage">
				<div class="panel-heading">
					<div class="pull-right">
                        <div class="btn-group">
                            <button class="btn btn-default btn-xs btn-add" type="button" title="Add row">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button class="btn btn-default btn-xs btn-remove" type="button" title="Remove row">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
					<h4 class="panel-title">
						<i class="fa fa-edit"></i>
						Package Order Form
					</h4>
				</div>
				<div class="panel-body">
					<div class="table-form">
						<table class="table table-bordered" id="table-list-form-package">
							<thead>
								<tr>
									<th width="2%">No</th>
									<th width="10%">Expedition Company</th>
									<th width="10%">Courir Expedition Name</th>
									<th width="10%">Receiver</th>
									<th width="10%">Receiver Date</th>
									<th width="10%">Package Note</th>
									<th width="10%">Package Owner</th>
									<th width="10%" class="hide">Package Receiverby</th>
									<th width="10%" class="hide">Package Accepted Date</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td><input type="hidden" name="ID[]" class="form-control ID">
										<input type="text" name="ExpeditionCom[]" class="ExpeditionCom form-control required" placeholder="exp:JNE/TIKI/dll">
										<small class="text-danger text-message"></small></td>
									<td><input type="text" name="ExpeditionCourier[]" class="ExpeditionCourier form-control required" placeholder="Nama Kurir">
									<small class="text-danger text-message"></small></td>
									<td><input type="text" name="Receiver[]" class="Receiver form-control required" value="<?=$receiver?>" readonly>
									<small class="text-danger text-message"></small></td>
									<td><input type="text" name="ReceiverDate[]" class="ReceiverDate form-control datepicker-tmp required" id="Datepicker-TMP" value="<?=date('Y-m-d')?>">
									<small class="text-danger text-message"></small></td>
									<td><textarea rows="1" name="Note[]" class="Note form-control" placeholder="Paket deskripsi"></textarea></td>
									<td><input type="text" name="PackageOwner[]" class="PackageOwner required form-control" placeholder="Nama pemilik paket">
									<small class="text-danger text-message"></small></td>
									<td class="hide"><input type="text" name="PackageReceiverby[]" class="PackageReceiverby form-control" placeholder="Nama pengambil paket"></td>
									<td class="hide"><input type="text" name="PackageReceiverDate[]" class="PackageReceiverDate datepicker-sd form-control" id="Datepicker-SD" placeholder="Tgl paket diambil"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="panel-footer text-right">
					<button class="btn btn-sm btn-default btn-clear" type="button">Cancel</button>
					<button class="btn btn-sm btn-success btn-submit" type="button">Save changes</button>
				</div>
			</div>
			</form>
		</div>
	</div>
	<div class="row">		
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading" style="padding:0px">
					<div class="row">
						<div class="col-sm-6">
							<h4 class="panel-title" style="padding-top:8px;padding-left:15px"><i class="fa fa-bars"></i> List of Package Order</h4>							
						</div>
						<div class="col-sm-6 text-right">
							<a class="btn btn-primary btn-sm btn-open-form" data-role="button"  data-toggle="collapse" data-target="#collapseFormPackage" aria-expanded="false" aria-controls="collapseFormPackage">
								<i class="fa fa-plus"></i>
								<span>Add new record</span>
							</a>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div id="fetch-data-tables">
						<table class="table table-bordered" id="table-list-data">
							<thead>
								<tr>
									<th width="5%">No</th>
									<th>Expedition</th>
									<th>Receiver</th>
									<th>Package Note</th>
									<th>Package Owner</th>
									<th>Package Received</th>
									<th width="2%"></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="10">No data available in table</td>
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
	function fetchPackageOrder() {
		//var filtering = $("#form-filter").serialize();		
		var filtering = null;
        var token = jwt_encode({Filter : filtering},'UAP)(*');

        var dataTable = $('#fetch-data-tables #table-list-data').DataTable( {
            "destroy": true,
            "retrieve":true,
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "responsive": true,
            "language": {
                "searchPlaceholder": "Name"
            },
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "ajax":{
                url : base_url_js+'general-affair/fetch-package-order', // json datasource
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
            		"data":"ExpeditionCom",
            		"render": function (data, type, row, meta) {
            			var label = '<p><span class="company"><i class="fa fa-compass"></i> '+data+'</span><br><span class="courier"><i class="fa fa-user"></i> '+row.ExpeditionCourier+'</span></p>';
            			return label;
            		}
            	},
            	{
            		"data":"Receiver",
            		"render": function (data, type, row, meta) {
            			var label = '<p><span class="receiver"><i class="fa fa-user"></i> '+data+'</span><br><span class="date"><i class="fa fa-calendar"></i> '+row.ReceiverDate+'</span></p>';
            			return label;
            		}
            	},
            	{
            		"data":"Note"            		
            	},
            	{
            		"data":"PackageOwner"            		
            	},
            	{
            		"data":"PackageReceiverby",
            		"render": function (data, type, row, meta) {
            			var label = "";
            			if(($.trim(row.PackageReceiverby).length > 0) && ($.trim(row.PackageReceiverDate).length > 0)){
            				label = '<p><span class="received"><i class="fa fa-user"></i> '+data+'</span><br><span class="date"><i class="fa fa-calendar"></i> '+row.PackageReceiverDate+'</span></p>';            				
            			}else{
            				label = '<p class="text-center text-danger"><i class="fa fa-exclamation-triangle"></i> Packet has not been taken</p>';
            			}
            			return label;
            		}
            	},      	
            	{
            		"data":"ID",
            		"render": function (data, type, row, meta) {
            			var label = '<button class="btn btn-warning btn-sm btn-edit" data-id="'+data+'" title="Edit"><i class="fa fa-edit"></i></button>';
            			return label;
            		}
            	},
        	]
        });
	}
	$(document).ready(function(){
		fetchPackageOrder();
		$("#Datepicker-TMP,#Datepicker-SD").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });

		$("#form-package-order .btn-submit").click(function(){
            var itsme = $(this);
            var itsform = itsme.parent().parent().parent();
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
                $("#form-package-order")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
        });
        
        $("#form-package-order").on("click",".btn-add",function(){
	        var itsme = $(this);
	        var parent = itsme.parent().parent().parent().parent();
	        var fieldName = parent.data("source");
	        $cloneRow = parent.find("#table-list-form-package tbody tr:first").clone();
	        var totalRow = parent.find("#table-list-form-package tbody tr").length;
	        var num = totalRow+1;
	        //$cloneRow.find("td:nth-child(7),td:last-child").addClass("hide");
	        $cloneRow.find("td input[type=text].datepicker-tmp").removeClass("hasDatepicker").attr("id","Datepicker-TMP-"+num);
        	$cloneRow.find("td input[type=text].datepicker-sd").removeClass("hasDatepicker").attr("id","Datepicker-SD-"+num);

	        $cloneRow.find("td:first").text(num);
	        $cloneRow.find(".form-control:not(.Receiver,.ReceiverDate)").val("");
	        parent.find("#table-list-form-package tbody").append($cloneRow);
	        
        	parent.find("#table-list-form-package tbody tr > td #Datepicker-TMP-"+num).datepicker({
	            dateFormat: 'yy-mm-dd',
	            changeYear: true,
	            changeMonth: true
	        });
	        parent.find("#table-list-form-package tbody tr > td #Datepicker-SD-"+num).datepicker({
	            dateFormat: 'yy-mm-dd',
	            changeYear: true,
	            changeMonth: true
	        });	        
    	});

    	$("#form-package-order").on("click",".btn-remove",function(){
    		var itsme = $(this);
	        var parent = itsme.parent().parent().parent().parent();
	        var totalRow = parent.find("#table-list-form-package tbody tr").length;
	        var lastRow = parent.find("#table-list-form-package tbody tr:last");
	        if(totalRow == 1){
                lastRow.find(".form-control:not(.Receiver,.ReceiverDate)").val("");
            }else{
                lastRow.remove();
            }
    	});

    	$("#form-package-order").on("click",".btn-clear",function(){
    		$.each($("#form-package-order .form-control:not(.Receiver,.ReceiverDate)"),function(){
    			$(this).val("");
    		});
    		//$("#form-package-order #collapseFormPackage").collapse('hide');
    	});

    	$("#general-affair #table-list-data tbody").on("click",".btn-edit",function(){
        	var itsme = $(this);
        	var ID = itsme.data("id");
        	var data = {
              ID : ID,
          	};
          	var token = jwt_encode(data,'UAP)(*');
			$.ajax({
			    type : 'POST',
			    url : base_url_js+"general-affair/detail-package-order",
			    data : {token:token},
			    dataType : 'json',
			    beforeSend :function(){loading_modal_show()},
	            error : function(jqXHR){
	            	loading_modal_hide();
	            	$("body #GlobalModal .modal-body").html(jqXHR.responseText);
		      	  	$("body #GlobalModal").modal("show");
			    },success : function(response){
	            	loading_modal_hide();
			    	$.each(response,function(k,v){
			    		$("#form-package-order").find("#table-list-form-package > tbody td ."+k).val(v);
			    	});
			    	$("#form-package-order").find("#table-list-form-package td,th").removeClass("hide");
			    	$("#form-package-order #collapseFormPackage").collapse('show');
			    }
			});
        });

        $(".btn-open-form").click(function(){
	      var isOpen = $(this).attr("aria-expanded");
	      if(isOpen == "false"){
	      	$(this).find("span").removeAttr("class");
	        $(this).attr("aria-expanded",true);
	        $(this).find("span").text("Close Form");
	        $(this).find("i.fa").toggleClass("fa-plus fa-times");
	      }else{
	      	$(this).find("span").removeAttr("class");
	        $(this).attr("aria-expanded",false);
	        $(this).find("span").text("Add New Form");        
	        $(this).find("i.fa").toggleClass("fa-times fa-plus");
	      }
	    });
	});
</script>