<style type="text/css">
	.label-content{text-transform: uppercase;}
	.detail-log:hover > b{text-decoration: underline;color: #0f1f4b}
	.detail-log{cursor: pointer; }
</style>
<div id="log-content">
	<div class="row" style="margin-bottom:15px">
		<div class="col-sm-4">
			<div class="btn-group">
				<button class="btn btn-warning btn-sm" type="button" onclick="window.history.go(-1); return false;"><i class="fa fa-angle-double-left"></i> Going back</button>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<div class="panel panel-default" id="filter-form">
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fa fa-filter"></i> Filter</h4>
				</div>
				<div class="panel-body">
					<form id="form-filter" action="<?=base_url()?>" method="post" autocomplete="off">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Content</label>
									<select class="form-control" name="TypeContent" id="TypeContent">
										<option value="">Choose one</option>
										<option <?=($typecontent == 'user_qna') ? 'selected':''?> value="user_qna">Help</option>
										<option <?=($typecontent == 'knowledge_base') ? 'selected':''?> value="knowledge_base">Knowledge Base</option>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Division</label>
									<select class="select-required" style="width:100%" name="DivisiID" id="DivisionID"></select>
								</div>
							</div>

						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Type</label>
									<select class="form-control required" name="Type" id="Type">
										<option value="0">Choose one</option>
									</select>
								</div>		
							</div>	
							<div class="col-sm-6">
								<div class="form-group">
									<label>Question</label>
									<select class="form-control required" name="Question">
										<option value="0">Choose one</option>
									</select>
								</div>		
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 text-left">
								<button class="btn btn-primary btn-sm btn-filter" type="button"><i class="fa fa-search"></i> Search</button>
							</div>
						</div>
					</form>			
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="panel panel-default" id="result-table">
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="fa fa-bars"></i> List content <span class="label-content"><?=trim(preg_replace('/_/', ' ', $typecontent))?></span>
					</h4>
				</div>
				<div class="panel-body">
					<div class="fetch-data table-responsive">
						<table class="table table-bordered" id="table-list-data">
							<thead>
								<tr>
									<th width="5%">No</th>
									<th width="20%">Employee</th>
									<th>Type</th>
									<th>Question</th>
									<th width="15%">Total Viewed</th>
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

        var token = jwt_encode({Filter : filtering},'UAP)(*');
        var dataTable = $('#table-list-data').DataTable( {
            "destroy": true,
            "ordering" : false,
            "retrieve":true,
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 5,
            "responsive": true,
            language: {
		        searchPlaceholder: "Search by NIP or name"
		    },
            "ajax":{
                url : base_url_js+'admin-fetch-log', // json datasource
                ordering : false,
                data : {token:token},
                type: "post",  // method  , by default get
                error: function(jqXHR){  // error handling
                    loading_modal_hide();
                    $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        '<h4 class="modal-title">Error Fetch Data</h4>');
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
            		"data":"NIP",
            		"render": function (data, type, row, meta) {
            			var label = '<span class="detail-log" data-nip="'+data+'" data-content="'+row.ContentTable+'" ><b>'+data+"</b><span class='load'></span><br>"+row.Name+'</span>';
            			return label;
            		}
            	},
            	{
            		"data":"TypeName",
            		"render": function (data, type, row, meta) {
            			var label = '<b>'+row.DivisionName+'</b><br>'+data;            			
            			return label;
            		}
            	},
            	{
            		"data":"Description", 
            		"render": function (data, type, row, meta) {
            			var label = data;
            			return label;
            		}           		
            	},{
            		"data":"totalReadCtn", 
            		"render": function (data, type, row, meta) {
            			var label = data+" times read article";
            			return label;
            		}           		
            	},
        	]
        });
	}


	function typeSelect(TypeContent,DivisionID,TypeSelect,TypeQuest) {
		var result = [];
		var dataPost = {
	        TypeContent : TypeContent,
	        DivisionID : DivisionID,
	        SelectBox : TypeSelect,
	        TypeQuest : TypeQuest
      	}
	        
      	var token = jwt_encode(dataPost,'UAP)(*');

      	$.ajax({
	        type : 'POST',
	        url : base_url_js+"get-type-question",
	        data : {token:token},
	        dataType : 'json',
	        async: false,
	        beforeSend :function(){},
	        error : function(jqXHR){
				$('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
	                '<h4 class="modal-title">Error !</h4>');
				$("body #GlobalModal .modal-body").html(jqXHR.responseText);
				$('body #GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
				$("body #GlobalModal").modal("show");
	        },success : function(response){
	        	result = response;
	        }
	    });
		return result;
	}


	function loadSelectOptionAllDivisionProdFac(element,selected) {
		var url = base_url_js+'api/__getAllDepartementPU';
        $.getJSON(url,function (jsonResult) {
        	$(element).append('<option value="0" >Choose one</option>');
            for(var i=0;i<jsonResult.length;i++){
                var d = jsonResult[i];
                var sc = ( selected!='' && typeof selected !== "undefined" && selected==d.Code) ? 'selected' : '';
                $(element).append('<option value="'+d.Code+'" '+sc+'>'+d.Name2+'</option>');
            }
            $(element).select2({'width':'100%'});
        });
    }


	$(document).ready(function(){
		fetchLogActivity();

		<?php if($typecontent == "user_qna"){ ?>
		loadSelectOptionDivision("#DivisionID",12);
		//$("#DivisionID").select2({'width':'100%'});
		<?php }else{ ?>
		loadSelectOptionAllDivisionProdFac("#DivisionID");
		<?php } ?>


		$("#form-filter .btn-filter").click(function(){
	    	$('body #table-list-data').DataTable().destroy();
	        fetchLogActivity();
	        var labelname = $("#TypeContent option:selected").text();
			$(".label-content").text(labelname);
	    });

	    $("#table-list-data").on("click",".detail-log",function(){
	    	var itsme = $(this);
	    	var NIP = itsme.data("nip");
	    	var TYPE = itsme.data("content");

	    	var dataPost = {
		        NIP : NIP,
		        TypeContent : TYPE
	      	}
		        
	      	var token = jwt_encode(dataPost,'UAP)(*');

	      	$.ajax({
		        type : 'POST',
		        url : base_url_js+"admin-log-detail",
		        data : {token:token},
		        dataType : 'html',
		        beforeSend :function(){itsme.find(".load").html('<i class="fa fa-spinner fa-spin"></i>');},
		        error : function(jqXHR){
					$('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
		                '<h4 class="modal-title">Error !</h4>');
					$("body #GlobalModal .modal-body").html(jqXHR.responseText);
					$('body #GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
					$("body #GlobalModal").modal("show");
		        },success : function(response){
		        	itsme.find(".load").empty();
		        	$("#GlobalModal .modal-dialog").css({"width":"80%"});
		          	$('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
		                '<h4 class="modal-title">Historical log</h4>');
		          	$('body #GlobalModal .modal-footer').hide();
	            	$('#GlobalModal .modal-body').html(response);
		        }
		    });


			
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });	    	
	    });
		
		$("#TypeContent").change(function(){
			var itsme = $(this);
			var value = itsme.val();
			$("#form-filter select:not(#TypeContent)").empty();
			if(value == "user_qna"){
				loadSelectOptionDivision("#DivisionID");
			}else{
				loadSelectOptionAllDivisionProdFac("#DivisionID",'0');
			}
		});

		$("#DivisionID").change(function(){
			var itsme = $(this);
			var value = itsme.val();
			var content = $("#TypeContent").val();
			var result = typeSelect(content,value,"Type");
			if(!jQuery.isEmptyObject(result)){

				$("#form-filter select[name=Type]").empty();
					var options = "<option>Choose one</option>";
					if(content == "knowledge_base"){
					var iniID = 0;
					$.each(result,function(k,v){
						$.each(v,function(key, val){
							if(iniID != parseInt(val.ID)){
								options += '<option value="'+val.ID+'">'+val.Type+'</option>';
							}
							iniID = parseInt(val.ID);
						});
					});
				}else{
					$.each(result,function(k,v){
						options += '<option value="'+v.Type+'">'+v.Type+'</option>';
					});
				}
				$("#form-filter select[name=Type]").append(options);
			}
		});

		$("#Type").change(function(){
			var itsme = $(this);
			var value = itsme.val();
			var content = $("#TypeContent").val();
			var TypeQuest = $("#Type").val();
			var result = typeSelect(content,value,"Questions",TypeQuest);
			if(!jQuery.isEmptyObject(result)){
				$("#form-filter select[name=Question]").empty();
				var options = "<option>Choose one</option>";
				$.each(result,function(k,v){
					options += '<option value="'+v.ID+'">'+v.Desc+'</option>';
				});
				$("#form-filter select[name=Question]").append(options);
			}
		});

	});
</script>
