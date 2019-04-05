<style type="text/css">
    #tableData1 thead th,#tableData1 tfoot td {

        text-align: center;
        background: #20485A;
        color: #FFFFFF;

    }

    #tableData2 thead th,#tableData2 tfoot td {

        text-align: center;
        background: #20485A;
        color: #FFFFFF;

    }
</style>
<div class="col-xs-12" >
    <div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Sub Account</h4>
            <div class="toolbar no-padding pull-right">
                <span data-smt="" class="btn btn-add btn-add-realization-Post">
                    <i class="icon-plus"></i> Add
               </span>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive" id = "loadTable2">

            </div>  
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    loadTable2();

    $(".btn-add-realization-Post").click(function(){
        modal_generate2('add','Add');
    });

    $('script[src="<?php echo base_url('assets/custom/xprototype.js');?>"]').remove()


    
    function modal_generate2(action,title,ID='') {
        var url = base_url_js+"budgeting/postrealisasi/modalform";
        var data = {
            Action : action,
            CDID : ID,
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token }, function (html) {
            $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+title+'</h4>');
            $('#GlobalModalLarge .modal-body').html(html);
            $('#GlobalModalLarge .modal-footer').html(' ');
            $('#GlobalModalLarge').modal({
                'show' : true,
                'backdrop' : 'static'
            });

            $('#ModalbtnSaveForm2').click(function(){
                if (confirm("Are you sure?") == true) {
                    loading_button('#ModalbtnSaveForm2');
                    var url = base_url_js+'budgeting/postrealisasi/modalform/save';

                    var NeedPrefix = $('.NeedPrefix:checked').val();
                    var CodePostRealisasi = $("#CodePostRealisasi").val();
                    var HeadAccount = $("#HeadAccount").val();
                    var RealisasiPostName = $("#RealisasiPostName").val();
                    var Departement = $("#Departement2").val();
                    var Desc = $("#Desc").val();

                    var action = $(this).attr('action');
                    var id = $("#ModalbtnSaveForm2").attr('kodeuniq');
                    var data = {
                                NeedPrefix : NeedPrefix,
                                CodePostRealisasi : CodePostRealisasi,
                                HeadAccount : HeadAccount,
                                RealisasiPostName : RealisasiPostName,
                                UnitDiv : Departement,
                                Desc : Desc,
                                Action : action,
                                CDID : id
                                };
                    var token = jwt_encode(data,"UAP)(*");
                    if (validationInput = validation2(data)) {
                        $.post(url,{token:token},function (data_json) {
                            var response = jQuery.parseJSON(data_json);
                            if (response == '') {
                                toastr.success('Saved', 'Success!');
                            }
                            else
                            {
                                toastr.error(response, 'Failed!!');
                            }
                            loadTable2();
                            $('#GlobalModalLarge').modal('hide');
                        }).done(function() {
                          // loadTable();
                        }).fail(function() {
                          toastr.error('The Database connection error, please try again', 'Failed!!');
                        }).always(function() {
                         $('#ModalbtnSaveForm2').prop('disabled',false).html('Save');

                        });
                    } // if validation
                    else
                    {
                        $('#ModalbtnSaveForm2').prop('disabled',false).html('Save');
                    }// exit validation
                  } 
                  else {
                    return false;
                  }
                   
            });
        })

    }

    
    function validation2(arr)
    {
      var toatString = "";
      var result = "";
      for(var key in arr) {
         switch(key)
         {
          case  "Action" :
          case  "CDID" :
                break;
          case  "NeedPrefix" :
                result = Validation_required(arr[key],key);
                  if (result['status'] == 0) {
                    toatString += 'The Code is Required' + "<br>";
                }
                break;
          case  "RealisasiPostName" :
                    result = Validation_required(arr[key],key);
                      if (result['status'] == 0) {
                        toatString += 'The SubAccountName is Required' + "<br>";
                    }
                break;
          case  "CodePostRealisasi" :
                // console.log(arr['NeedPrefix']);
                if(arr['NeedPrefix'] == 0)
                {
                    result = Validation_required(arr[key],key);
                      if (result['status'] == 0) {
                        toatString += result['messages'] + "<br>";
                    }
                }
                break;      
         }

      }
      if (toatString != "") {
        toastr.error(toatString, 'Failed!!');
        return false;
      }

      return true;
    }

    function loadTable2()
    {
        $("#loadTable2").empty();
        var TableGenerate = '<table class="table table-bordered" id ="tableData2">'+
                            '<thead>'+
                            '<tr>'+
                                '<th width = "3%">No</th>'+
                                '<th>Sub Account</th>'+
                                '<th>Budget Category</th>'+
                                '<th>HeadAccount</th>'+
                                '<th>Department</th>'+
                                '<th>User</th>'+
                                '<th>Desc</th>'+
                                '<th>Action</th>'+
                            '</tr></thead>' 
                            ;
        TableGenerate += '<tbody>';

        var dataForTable = [];
        var url = base_url_js+'budgeting/get_cfg_postrealisasi';
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            dataForTable = response;
            // console.log(dataForTable);
            var No = 1;
            for (var i = 0; i < dataForTable.length; i++) {
                var CodeDepartment = dataForTable[i].Departement;
                var sessIDDepartementPUBudget = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";
                if (CodeDepartment == sessIDDepartementPUBudget) {
                  var btn_edit = '<button type="button" class="btn btn-warning btn-edit btn-edit-postrealization" code = "'+dataForTable[i].CodePostRealisasi+'"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>';
                  var btn_del = ' <button type="button" class="btn btn-danger btn-delete btn-delete-postrealization"  code = "'+dataForTable[i].CodePostRealisasi+'"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
                  TableGenerate += '<tr>'+
                                      '<td width = "3%">'+ No+'</td>'+
                                      '<td>'+ dataForTable[i].CodePostRealisasi+'<br>'+dataForTable[i].RealisasiPostName+'</td>'+
                                      '<td>'+ dataForTable[i].CodePost+'<br>'+dataForTable[i].PostName+'</td>'+ // plus name
                                      '<td>'+ dataForTable[i].CodeHeadAccount+'<br>'+dataForTable[i].NameHeadAccount+'</td>'+
                                      '<td>'+ dataForTable[i].DepartementName+'</td>'+
                                      '<td>'+ dataForTable[i].UnitDivName+'</td>'+
                                      '<td>'+ dataForTable[i].Desc+'</td>'+
                                      '<td>'+ btn_edit + ' '+' &nbsp' + btn_del+'</td>'+
                                   '</tr>';
                    No++;                  
                }
                 
            }

            TableGenerate += '</tbody></table>';
            $("#loadTable2").html(TableGenerate);
            LoaddataTable("#tableData2");

            // $(".btn-edit-postrealization").click(function(){
            $(document).off('click', '.btn-edit-postrealization').on('click', '.btn-edit-postrealization',function(e) {  
                var ID = $(this).attr('code');
                 modal_generate2('edit','Edit',ID);
            });

            // $(".btn-delete-postrealization").click(function(){
            $(document).off('click', '.btn-delete-postrealization').on('click', '.btn-delete-postrealization',function(e) {  
                var ID = $(this).attr('code');
                 $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Are you sure ? </b> ' +
                     '<button type="button" id="confirmYesDelete" class="btn btn-primary" style="margin-right: 5px;" data-smt = "'+ID+'">Yes</button>' +
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
                     var url = base_url_js+'budgeting/postrealisasi/modalform/save';
                     var aksi = "delete";
                     var ID = $(this).attr('data-smt');
                     var data = {
                         Action : aksi,
                         CDID : ID,
                     };
                     var token = jwt_encode(data,"UAP)(*");
                     $.post(url,{token:token},function (data_json) {
                         setTimeout(function () {
                            var response = jQuery.parseJSON(data_json);
                            if (response == '') {
                                toastr.success('Deleted', 'Success!');
                            }
                            else
                            {
                                toastr.error(response, 'Failed!!');
                            }
                            loadTable2();
                            $('#NotificationModal').modal('hide');
                         },500);
                     });
                });

            });

            loadingEnd(500);
        }); 
                        
    }
   
}); // exit document Function
</script>