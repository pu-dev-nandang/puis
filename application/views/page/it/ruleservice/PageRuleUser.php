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
      var DivFormAdd = '<div id = "FormAdd"></div>';
          EditBtn = '<button type="button" class="btn btn-warning btn-edit"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>';
          SaveBtn = '<div class = "row" style = "margin-left : 0px;margin-right : 0px;margin-top:10px" id = "rowSaveEdit"></div>';
          var Mode = '<div class = "row" style = "margin-left : 0px;margin-right : 0px">'+
                '<div class = "col-xs-4">'+
                    '<span data-smt="" class="btn btn-add">'+
                                '<i class="icon-plus"></i> Add'+
                            '</span>'+'&nbsp'+EditBtn+
                    '</div>'+
                '</div>';
      var table = Mode+DivFormAdd+'<div class = "row" style = "margin-top : 15px;margin-left : 0px;margin-right : 0px"><div class="col-md-12" id = "pageForTable"><div class="table-responsive"><table class="table table-bordered datatable2" id = "tableData4">'+
                  '<thead>'+
                  '<tr>'+
                      '<th width = "3%">No</th>'+
                      '<th>NIP || Nama</th>'+
                      '<th>Division Access</th>'+
                      '<th>Action</th>'+
                  '</tr>'+
                  '</thead>'+
                  '<tbody id="dataRow"></tbody>'+
              '</table></div></div></div>'+SaveBtn;
      $("#loadPageTable").html(table);
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

        var data = {
                        auth : 's3Cr3T-G4N',
                   };
        var token = jwt_encode(data,"UAP)(*");
        var table = $('#tableData4').DataTable( {
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"rest/__rule_users", // json datasource
                ordering : false,
                type: "post",  // method  , by default get
                data : {token:token},
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                  $(row).attr('class', 'rowData');
                  $(row).attr('NIP', data[4]);
                  $(row).attr('IDDivision', data[5]);
                  $(row).attr('IDPri', data[6]);

                  $( row ).find('td:eq(1)')
                              .attr('class', 'E_NIP')
                              .attr('NIP', data[4])
                              .attr('IDPri', data[6]);
                  $( row ).find('td:eq(2)')
                              .attr('class', 'IDDivision')            
                              .attr('IDDivision', data[5]);            
                              //.addClass('asset-context box');
            },
        } );

        $('#tableData4').on('click', '.btn-delete', function(){
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

                 var url = base_url_js+'it/saveRuleUser';
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
    } 

    function FuncAddClickFunction()
    {
    	$(".btn-add").click(function(){
    		$("#FormAdd").empty();
            $("#btnSaveTable").remove();
            // get data division & service
                var url = base_url_js+"rest/__getEmployees/aktif";
                var data = {
                                auth : 's3Cr3T-G4N',
                           };
                var token = jwt_encode(data,"UAP)(*");
                $.post(url,{token:token},function (resultJson) {
                    var OpEmployees = resultJson;
                    var url = base_url_js+"rest/__getTableData/db_employees/division";
                                    var data = {
                                                    auth : 's3Cr3T-G4N',
                                               };
                                    var token = jwt_encode(data,"UAP)(*");
                    $.post(url,{token:token},function (resultJson) {
                        var OpDiv = resultJson;
                        var Thumbnail = '<div class="thumbnail" style="height: 130px;margin-top:10px;margin-left:10px;margin-right:10px"><b>Form Add</b>';
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
                        
                        var selectEmployees= function(selected = null){
                          var aa = '';
                          for (var i = 0; i < OpEmployees.length; i++) {
                            var selected = (OpEmployees[i].NIP == selected) ? 'selected' : '';
                             aa += '<option value = "'+OpEmployees[i].NIP+'" '+selected+'>'+OpEmployees[i].NIP+' || '+OpEmployees[i].Name+'</option>';
                          } 
                          return aa;
                        }
                                        
                        var html = '<div class = "row" style = "margin-left:0px;margin-right:0px;margin-top : 10px">'+
                                '<div class = "form-group">'+
                                  '<div class = "col-xs-12">'+
                                    '<div class = "row">'+
                                      '<div class = "col-xs-3">'+
                                        '<label>Division</label>'+
                                        '<select ID="addEmployees">'+
                                        selectEmployees()+
                                        '</select>'+
                                      '</div>'+
                                      '<div class = "col-xs-3">'+
                                        '<label>Division Access</label>'+
                                        '<select ID="addDivision">'+
                                        selectDiv()+
                                        '</select>'+
                                      '</div>'+
                                    '</div>'+
                                  '</div>'+
                                '</div>'+
                              '</div>';
                        var EndThumbnail = '</div>';      
                        $("#FormAdd").html(Thumbnail+html+Btn+EndThumbnail);
                        $('select[tabindex!="-1"]').select2({
                            //allowClear: true
                        });


                        $("#btnCancelAdd").click(function(){
                          $("#FormAdd").empty();
                        })  

                        $("#btnSaveAdd").click(function(){
                          loading_button('#btnSaveAdd');
                          var NIP = $("#addEmployees").val();
                          var IDDivision = $("#addDivision").val();
                          var Action = 'add';
                          var id = '';
                          var url = base_url_js+'it/saveRuleUser';
                          var SaveForm = {
                            NIP:NIP,
                            IDDivision:IDDivision,
                            privilege : '1',
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
              var url = base_url_js+"rest/__getEmployees/aktif";
              var data = {
                              auth : 's3Cr3T-G4N',
                         };
              var token = jwt_encode(data,"UAP)(*");
              $.post(url,{token:token},function (resultJson) {
                var OpEmployees = resultJson;
                var selectDiv = function(selected = null){
                  var aa = '';
                  var cc = selected;
                  for (var i = 0; i < OpDiv.length; i++) {
                    var selected = (OpDiv[i].ID == cc) ? 'selected' : '';
                     aa += '<option value = "'+OpDiv[i].ID+'" '+selected+'>'+OpDiv[i].Division+'</option>';
                  }
                  return aa;
                }

                var selectEmployees= function(selected = null){
                  var aa = '';
                  var cc = selected;
                  for (var i = 0; i < OpEmployees.length; i++) {
                    var selected = (OpEmployees[i].NIP == cc) ? 'selected' : '';
                     aa += '<option value = "'+OpEmployees[i].NIP+'" '+selected+'>'+OpEmployees[i].NIP+' || '+OpEmployees[i].Name+'</option>';
                  } 
                  return aa;
                }

                $(".IDDivision").each(function(){
                    var IDDivision = $(this).attr('iddivision');
                    var Input = '<select class = "cmbdivision">'+selectDiv(IDDivision)+'</select>';
                    $(this).html(Input);
                })

                $(".E_NIP").each(function(){
                    var NIP = $(this).attr('nip');
                    var IDpri = $(this).attr('idpri');
                    var Input = '<select class = "cmbNIP" IDpri = "'+IDpri+'">'+selectEmployees(NIP)+'</select>';
                    $(this).html(Input);
                })

                $('select[tabindex!="-1"]').select2({
                    //allowClear: true
                });

                $("#btnSaveTable").click(function(){
                    loading_button('#btnSaveTable');
                    var IDDivision = [];
                    $("select.cmbdivision").each(function(){
                        IDDivision.push($(this).val());
                    })

                    var IDNIP = [];
                    var ID = [];
                    $("select.cmbNIP").each(function(){
                        IDNIP.push($(this).val());
                        ID.push($(this).attr('idpri'))
                    })

                    // get Push array
                    var FormUpdate = [];
                    for (var i = 0; i < IDDivision.length; i++) {
                        var temp = {
                            IDDivision : IDDivision[i],
                            NIP : IDNIP[i],
                            ID : ID[i],
                        }
                        FormUpdate.push(temp);
                    }

                    var Action = 'edit';
                    var url = base_url_js+'it/saveRuleUser';
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