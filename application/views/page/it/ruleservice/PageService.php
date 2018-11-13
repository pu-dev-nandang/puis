<div class="row">
	<div id = "loadPageTable">
		
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    LoadTableData();

    function LoadTableData()
    {
    	$("#loadPageTable").empty();
    	var TableGenerate = '<div class = "row" style = "margin-top : 15px;margin-left : 0px;margin-right : 0px">'+         
    							'<div class="col-md-12" id = "pageForTable">'+
    									'<div class="table-responsive">'+
    										'<table class="table table-bordered tableData" id ="tableData3">'+
    										'<thead>'+
    										'<tr>'+
    											'<th width = "3%">No</th>'+
    				                            '<th>Name</th>'+
    				                            '<th>Description</th>'+
    											'<th>Action</th>'+
    										'</tr></thead>'	
    								;
    	TableGenerate += '<tbody>';
    	var url = base_url_js+"rest/__getTableData/db_employees/service";
        var data = {
                        auth : 's3Cr3T-G4N',
                   };
        var token = jwt_encode(data,"UAP)(*");
		$.post(url,{token:token},function (resultJson) {
			var EditBtn = '';
			var SaveBtn = '';
			var DivFormAdd = '<div id = "FormAdd"></div>';
			if (resultJson.length > 0) {
				EditBtn = '<button type="button" class="btn btn-warning btn-edit"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>';
				SaveBtn = '<div class = "row" style = "margin-left : 0px;margin-right : 0px;margin-top:10px" id = "rowSaveEdit"></div>';
				
			}
	    	var Mode = '<div class = "row" style = "margin-left : 0px;margin-right : 0px">'+
							'<div class = "col-xs-4">'+
									'<span data-smt="" class="btn btn-add">'+
				                    	'<i class="icon-plus"></i> Add'+
				               		'</span>'+'&nbsp'+EditBtn+
					        '</div>'+
					    '</div>';
			for (var i = 0; i < resultJson.length; i++) {
				TableGenerate += '<tr>'+
									'<td width = "3%">'+ (parseInt(i) + 1)+'</td>'+
									'<td class = "Name" idget = "'+resultJson[i].ID+'">'+ resultJson[i].Name+'</td>'+
									'<td class = "Description">'+ resultJson[i].Description+'</td>'+
									'<td class = "Action">'+ '<button type="button" class="btn btn-danger btn-delete" data-sbmt="'+resultJson[i].ID+'"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>'+'</td>'+
								'</tr>';
			}
			TableGenerate += '</tbody></table></div></div></div>'; 		    
			 $("#loadPageTable").html(Mode+DivFormAdd+TableGenerate+SaveBtn);
			 var t = $('#tableData3').DataTable({
			 	"pageLength": 10
			 });

             $('#tableData3').on('click', '.btn-delete', function(){
                var ID = $(this).attr('data-sbmt');
                 $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Apakah anda yakin untuk melakukan request ini ?? </b> ' +
                     '<button type="button" id="confirmYesDelete" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
                     '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
                     '</div>');
                 $('#NotificationModal').modal('show');
                     $("#confirmYesDelete").click(function(){
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

                      var url = base_url_js+'it/saveService';
                      var aksi = "delete";
                      var data = {
                          Action : aksi,
                          CDID : ID,
                      };
                      var token = jwt_encode(data,"UAP)(*");
                      $.post(url,{token:token},function (data_json) {
                          setTimeout(function () {
                             toastr.options.fadeOut = 10000;
                             toastr.success('Data berhasil disimpan', 'Success!');
                             LoadTableData();
                             $('#NotificationModal').modal('hide');
                          },500);
                      });

                     })
             });
             
			 FuncAddClickFunction();

    	}).fail(function() {
		  toastr.info('No Result Data'); 
		}).always(function() {
		                
		});								
    }

    function FuncAddClickFunction()
    {
    	$(".btn-add").click(function(){
    		$("#FormAdd").empty();
            $("#btnSaveTable").remove();
            var Thumbnail = '<div class="thumbnail" style="height: 150px;margin-top:10px;margin-left:10px;margin-right:10px"><b>Form Add</b>';
    		var Btn = '<div class = "row" style = "margin-left:10px;margin-right:0px;margin-top : 0px">'+
	    						'<div clas = "col-xs-4">'+
	    							'<button type="button" id="btnSaveAdd" class="btn btn-success">Save</button>'+
	    							'&nbsp'+
	    							'<button type="button" id="btnCancelAdd" class="btn btn-danger">Cancel</button>'+
	    						'</div>'+
    						'</div>';
    					;				  	
    		var html = '<div class = "row" style = "margin-left:0px;margin-right:0px;margin-top : 10px">'+
    						'<div class = "form-group">'+
    							'<div class = "col-xs-12">'+
    								'<div class = "row">'+
    									'<div class = "col-xs-3">'+
    										'<label>Name</label>'+
    										'<input type = "text" class = "form-control" id = "addName">'+
    									'</div>'+
    									'<div class = "col-xs-3">'+
    										'<label>Description</label>'+
    										'<input type = "text" class = "form-control" id = "addDescription">'+
    									'</div>'+
    								'</div>'+
    							'</div>'+
    						'</div>'+
    					'</div>';
    		var EndThumbnail = '</div>';			
    		$("#FormAdd").html(Thumbnail+html+Btn+EndThumbnail);
    		$("#btnCancelAdd").click(function(){
    			$("#FormAdd").empty();
    		})	

    		$("#btnSaveAdd").click(function(){
                loading_button('#btnSaveAdd');
    			var Name = $("#addName").val();
    			var Description = $("#addDescription").val();
    			var Action = 'add';
    			var id = '';
    			var url = base_url_js+'it/saveService';
    			var SaveForm = {
    				Name:Name,
    				Description:Description,
    			}
    			var data = {
    			    Action : Action,
    			    CDID : id,
    			    SaveForm : SaveForm
    			};
                var token = jwt_encode(data,"UAP)(*");
                if (validationInput = validation(SaveForm)) {
                    $.post(url,{token:token},function (resultJson) {
                        LoadTableData();
                        $('#btnSaveAdd').prop('disabled',false).html('Save');
                    }).fail(function() {
                      toastr.info('Error Processing'); 
                    }).always(function() {
                                    
                    });
                }
                else
                {
                    $('#btnSaveAdd').prop('disabled',false).html('Save');
                } 

    		})						
    	}) // exit add click function
        
        var editi = 0;
        $(".btn-edit").click(function(){
            editi++;
            if ((editi % 2) == 0 ) {LoadTableData();return;}
            var SaveBtn = '<div class = "col-xs-1 col-md-offset-11"> <button type="button" id="btnSaveTable" class="btn btn-success">Save</button></div>';
            $("#rowSaveEdit").html(SaveBtn);
            $(".Name").each(function(){
                var valText = $(this).text();
                var idget = $(this).attr('idget');
                var Input = '<input type = "text" class = "form-control textName" value = "'+valText+'" idget="'+idget+'">';
                $(this).html(Input);
            })

            $(".Description").each(function(){
                var valText = $(this).text();
                var Input = '<input type = "text" class = "form-control textDescription" value = "'+valText+'" >';
                $(this).html(Input);
            })

            $("#btnSaveTable").click(function(){
                loading_button('#btnSaveTable');
                var textName = [];
                var textID = [];
                $(".textName").each(function(){
                    textName.push($(this).val());
                    textID.push($(this).attr('idget'));
                })

                var textDescription = [];
                $(".textDescription").each(function(){
                    textDescription.push($(this).val());
                })

                // get Push array
                var FormUpdate = [];
                for (var i = 0; i < textName.length; i++) {
                    var temp = {
                        Name : textName[i],
                        Description : textDescription[i],
                        ID : textID[i],
                    }
                    FormUpdate.push(temp);
                }

                var Action = 'edit';
                var url = base_url_js+'it/saveService';
                var data = {
                    Action : Action,
                    FormUpdate : FormUpdate
                };
                var token = jwt_encode(data,"UAP)(*");
                $.post(url,{token:token},function (resultJson) {
                    LoadTableData();
                    $('#btnSaveTable').prop('disabled',false).html('Save');
                }).fail(function() {
                  toastr.info('Error Processing'); 
                }).always(function() {
                                
                });

            })

        })
            
    }

}); // exit document Function

function validation(arr)
{
  var toatString = "";
  var result = "";
  for(var key in arr) {
     switch(key)
     {
      default :
            result = Validation_required(arr[key],key);
            if (result['status'] == 0) {
              toatString += result['messages'] + "<br>";
            }
     }

  }
  if (toatString != "") {
    toastr.error(toatString, 'Failed!!');
    return false;
  }

  return true;
}
</script>