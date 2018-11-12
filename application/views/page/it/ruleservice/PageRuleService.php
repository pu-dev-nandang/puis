<div class="row">
	<div id = "loadPageTable">
		
	</div>
</div>

<script type="text/javascript">
  var dttbl = [];
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
    				                            '<th>Division</th>'+
    				                            '<th>Service</th>'+
    											'<th>Action</th>'+
    										'</tr></thead>'	
    								;
    	TableGenerate += '<tbody>';
    	var url = base_url_js+"rest/__rule_service";
        var data = {
                        auth : 's3Cr3T-G4N',
                   };
        var token = jwt_encode(data,"UAP)(*");
		$.post(url,{token:token},function (resultJson) {
      dttbl = resultJson;
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
									'<td class = "Division" idget = "'+resultJson[i].IDPri+'" IDDivision = "'+resultJson[i].IDDivision+'">'+ resultJson[i].Division+'</td>'+
									'<td class = "Name" IDService= "'+resultJson[i].IDService+'">'+ resultJson[i].Name+'</td>'+
									'<td class = "Action">'+ '<button type="button" class="btn btn-danger btn-delete" data-sbmt="'+resultJson[i].IDPri+'"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>'+'</td>'+
								'</tr>';
			}
			TableGenerate += '</tbody></table></div></div></div>'; 		    
			 $("#loadPageTable").html(Mode+DivFormAdd+TableGenerate+SaveBtn);
			 var t = $('#tableData3').DataTable({
			 	"pageLength": 10,
        // "order": [[ 1, "asc" ]]
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

                      var url = base_url_js+'it/saveRuleService';
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
            // get data division & service
                var url = base_url_js+"rest/__getTableData/db_employees/division";
                var data = {
                                auth : 's3Cr3T-G4N',
                           };
                var token = jwt_encode(data,"UAP)(*");
                $.post(url,{token:token},function (resultJson) {
                    var OpDiv = resultJson;
                    var url = base_url_js+"rest/__getTableData/db_employees/service";
                                    var data = {
                                                    auth : 's3Cr3T-G4N',
                                               };
                                    var token = jwt_encode(data,"UAP)(*");
                    $.post(url,{token:token},function (resultJson) {
                        var OpService = resultJson;
                        var Thumbnail = '<div class="thumbnail" style="height: 200px;margin-top:10px;margin-left:10px;margin-right:10px"><b>Form Add</b>';
                        var Btn = '<div class = "row" style = "margin-left:10px;margin-right:0px;margin-top : 0px">'+
                                  '<div clas = "col-xs-4">'+
                                    '<button type="button" id="btnSaveAdd" class="btn btn-success">Save</button>'+
                                    '&nbsp'+
                                    '<button type="button" id="btnCancelAdd" class="btn btn-danger">Cancel</button>'+
                                  '</div>'+
                                '</div>';
                              ;
                        var selectDiv = function(selected = null){
                          var aa = '';
                          for (var i = 0; i < OpDiv.length; i++) {
                            var selected = (OpDiv[i].ID == selected) ? 'selected' : '';
                             aa += '<option value = "'+OpDiv[i].ID+'" '+selected+'>'+OpDiv[i].Division+'</option>';
                          }
                          return aa;
                        }
                        
                        var selectService = function(selected = null){
                          var aa = '';
                          for (var i = 0; i < OpService.length; i++) {
                            var selected = (OpService[i].ID == selected) ? 'selected' : '';
                             aa += '<option value = "'+OpService[i].ID+'" '+selected+'>'+OpService[i].Name+'</option>';
                          } 
                          return aa;
                        }
                                        
                        var html = '<div class = "row" style = "margin-left:0px;margin-right:0px;margin-top : 10px">'+
                                '<div class = "form-group">'+
                                  '<div class = "col-xs-12">'+
                                    '<div class = "row">'+
                                      '<div class = "col-xs-3">'+
                                        '<label>Division</label>'+
                                        '<select ID="addDivision">'+
                                        selectDiv()+
                                        '</select>'+
                                      '</div>'+
                                      '<div class = "col-xs-3">'+
                                        '<label>Service</label>'+
                                        '<select ID="addName">'+
                                        selectService()+
                                        '</select>'+
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
                          var IDService = $("#addName").val();
                          var IDDivision = $("#addDivision").val();
                          var Action = 'add';
                          var id = '';
                          var url = base_url_js+'it/saveRuleService';
                          var SaveForm = {
                            IDService:IDService,
                            IDDivision:IDDivision,
                            Status : '1',
                          }
                          var data = {
                              Action : Action,
                              CDID : id,
                              SaveForm : SaveForm
                          };
                          var token = jwt_encode(data,"UAP)(*");
                          if (validationInput = validation(SaveForm)) {
                              $.post(url,{token:token},function (resultJson) {
                                  var response = jQuery.parseJSON(resultJson);
                                  if (response == '') {
                                    LoadTableData();
                                  }
                                  else
                                  {
                                    toastr.error(response,'!Failed');
                                  }
                                  
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

                        })  // exit save add

                    }) // exit spost service
                })
        					
    	}) // exit add click function
      
      var editi = 0;
      $(".btn-edit").click(function(){
          editi++;
          if ((editi % 2) == 0 ) {LoadTableData();return;}
          var SaveBtn = '<div class = "col-xs-1 col-md-offset-11"> <button type="button" id="btnSaveTable" class="btn btn-success">Save</button></div>';
          $("#rowSaveEdit").html(SaveBtn);

          var url = base_url_js+"rest/__getTableData/db_employees/division";
          var data = {
                          auth : 's3Cr3T-G4N',
                     };
          var token = jwt_encode(data,"UAP)(*");
          $.post(url,{token:token},function (resultJson) {
              var OpDiv = resultJson;
              var url = base_url_js+"rest/__getTableData/db_employees/service";
              var data = {
                              auth : 's3Cr3T-G4N',
                         };
              var token = jwt_encode(data,"UAP)(*");
              $.post(url,{token:token},function (resultJson) {
                var OpService = resultJson;
                var selectDiv = function(selected = null){
                  var aa = '';
                  var cc = selected;
                  for (var i = 0; i < OpDiv.length; i++) {
                    var selected = (OpDiv[i].ID == cc) ? 'selected' : '';
                     aa += '<option value = "'+OpDiv[i].ID+'" '+selected+'>'+OpDiv[i].Division+'</option>';
                  }
                  return aa;
                }
                
                var selectService = function(selected = null){
                  var aa = '';
                  var cc = selected;
                  for (var i = 0; i < OpService.length; i++) {
                    var selected = (OpService[i].ID == cc) ? 'selected' : '';
                     aa += '<option value = "'+OpService[i].ID+'" '+selected+'>'+OpService[i].Name+'</option>';
                  } 
                  return aa;
                }

                $(".Division").each(function(){
                    var IDDivision = $(this).attr('iddivision');
                    var idget = $(this).attr('idget');
                    var Input = '<select class = "iddivision" idget = "'+idget+'">'+selectDiv(IDDivision)+'</select>';
                    $(this).html(Input);
                })

                $(".Name").each(function(){
                    var IDService = $(this).attr('idservice');
                    var Input = '<select class = "idservice">'+selectService(IDService)+'</select>';
                    $(this).html(Input);
                })

                $("#btnSaveTable").click(function(){
                    loading_button('#btnSaveTable');
                    var IDDivision = [];
                    var ID = [];
                    $(".iddivision").each(function(){
                        IDDivision.push($(this).val());
                        ID.push($(this).attr('idget'));
                    })

                    var IDService = [];
                    $(".idservice").each(function(){
                        IDService.push($(this).val());
                    })


                    // get Push array
                    var FormUpdate = [];
                    for (var i = 0; i < IDDivision.length; i++) {
                        var temp = {
                            IDDivision : IDDivision[i],
                            IDService : IDService[i],
                            ID : ID[i],
                        }
                        FormUpdate.push(temp);
                    }

                    var Action = 'edit';
                    var url = base_url_js+'it/saveRuleService';
                    var data = {
                        Action : Action,
                        FormUpdate : FormUpdate
                    };
                    var token = jwt_encode(data,"UAP)(*");
                    $.post(url,{token:token},function (resultJson) {
                      var response = jQuery.parseJSON(resultJson);
                      if (response == '') {
                        LoadTableData();
                      }
                      else{
                        toastr.error(response,'!Failed');
                      }
                        $('#btnSaveTable').prop('disabled',false).html('Save');
                    }).fail(function() {
                      toastr.info('Error Processing'); 
                    }).always(function() {
                                    
                    });

                }) // exit btn save edit
              }) // exit spost service
          })

      }) // exit btn edit  

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