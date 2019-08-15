<form class="form-horizontal" id="formModal">
    <div class="form-group"> 
        <div class="row">
            <div class="col-sm-2">
                <label class="control-label">Name :</label>
            </div>    
            <div class="col-sm-10">
                <input type="text" id="NameMaster"  class="form-control" placeholder="" value="<?php echo ($action == 'add') ? '' : $getData[0]['Name'] ?>">
            </div>
        </div>
    </div>
    <div class="form-group"> 
        <div class="row">
            <div class="col-sm-2">
                <label class="control-label">Approval :</label>
            </div>    
            <div class="col-sm-10">
                <div class="pull-left">
                    <span data-smt="" class="btn btn-add btn-write btn-add-m_template_form">
                        <i class="icon-plus"></i> Add
                   </span>
                </div>
                <br><br>
                <table class="table table-bordered tableData" id ="TableApprovalMaster">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Type User</th>
                            <th>Visible</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div style="text-align: center;">       
		<div class="col-sm-12" id="BtnFooter">
            <button type="button" id="ModalbtnCancleFormMaster" data-dismiss="modal" class="btn btn-default">Cancel</button>
            <button type="button" id="ModalbtnSaveFormMaster" class="btn btn-success" action = "<?php echo $action ?>" kodeuniq = "<?php echo $ID ?>">Save</button>
		</div>
    </div>    
</form>
<script type="text/javascript">
    var cfg_m_type_approval = <?php echo json_encode($cfg_m_type_approval) ?>;
    $(document).ready(function() {
         <?php if ($action == 'edit'): ?>
            LoadExistingDataMaster();       
         <?php endif ?>   
    }); // exit document Function

    function LoadExistingDataMaster()
    {
       <?php if ($action == 'edit'): ?>
        $('#NameMaster').val("<?php echo $getData[0]['Name'] ?>");
        var html =  '';
        var  JsonStatus = jQuery.parseJSON(<?php echo json_encode($getData[0]['JsonStatusDefault']) ?>);
        for (var i = 0; i < JsonStatus.length; i++) {
            var cmb = OPcmbTypeUser(JsonStatus[i].NameTypeDesc);
            var visible = OPcmbVisibel(JsonStatus[i].Visible);
            var btnDelete = '<button type="button" class="btn btn-danger btn-delete btn-delete-setRoleUserMaster" code=""> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
            var input = '<div class = "row">'+
                        '<div class = "col-xs-8">'+
                            '<input type = "text" class = "form-control PeopleApprovalMaster" placeholder = "Input..." value="'+JsonStatus[i].NIP+'">'+
                        '</div>'+
                        '<div class = "col-xs-4">'+
                            '<label id = "labelNameApprovalMaster">'+JsonStatus[i].Name+'</label>'+
                        '</div>'+
                    '</div>';
            html += '<tr>'+
                        '<td></td>'+
                        '<td>'+input+'</td>'+
                        '<td>'+cmb+'</td>'+
                        '<td>'+visible+'</td>'+
                        '<td>'+btnDelete+'</td>'+
                    '</tr>'; 
        }
        $('#TableApprovalMaster tbody').append(html);          
        MakeAutoNumbering();
       <?php endif ?>  
    }

    $(document).off('click', '.btn-delete-setRoleUserMaster').on('click', '.btn-delete-setRoleUserMaster',function(e) {
        $(this).closest('tr').remove();
        MakeAutoNumbering();
    })
    

    $(document).off('click', '.btn-add-m_template_form').on('click', '.btn-add-m_template_form',function(e) {
        var html = '';
        // adding combo Type User
            var cmb = OPcmbTypeUser();
            var visible = OPcmbVisibel();
            var btnDelete = '<button type="button" class="btn btn-danger btn-delete btn-delete-setRoleUserMaster" code=""> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
            var input = '<div class = "row">'+
                        '<div class = "col-xs-8">'+
                            '<input type = "text" class = "form-control PeopleApprovalMaster" placeholder = "Input...">'+
                        '</div>'+
                        '<div class = "col-xs-4">'+
                            '<label id = "labelNameApprovalMaster"></label>'+
                        '</div>'+
                    '</div>';
            html = '<tr>'+
                        '<td></td>'+
                        '<td>'+input+'</td>'+
                        '<td>'+cmb+'</td>'+
                        '<td>'+visible+'</td>'+
                        '<td>'+btnDelete+'</td>'+
                    '</tr>';    
            $('#TableApprovalMaster tbody').append(html);          
            MakeAutoNumbering();
    })

    function MakeAutoNumbering()
    {
        var no = 1;
        $("#TableApprovalMaster tbody tr").each(function(){
            var a = $(this);
            a.find('td:eq(0)').html(no);
            no++;
        })
    }

    function OPcmbVisibel(IDselected = null,Dis='')
    {
        var h = '';
        var temp = ['Yes','No'];
        h = '<select class = " form-control cmbVisibel" '+Dis+'>';
            for (var i = 0; i < temp.length; i++) {
                var selected = (IDselected == temp[i]) ? 'selected' : '';
                h += '<option value = "'+temp[i]+'" '+selected+' >'+temp[i]+'</option>';
            }
        h += '</select>';   

        return h;
    } 

    function OPcmbTypeUser(IDselected = null,Dis='')
    {
        var h = '';
        h = '<select class = " form-control cmbTypeUser" '+Dis+'>';
            for (var i = 0; i < cfg_m_type_approval.length; i++) {
                var selected = (IDselected == cfg_m_type_approval[i].Name) ? 'selected' : '';
                h += '<option value = "'+cfg_m_type_approval[i].ID+'" '+selected+' >'+cfg_m_type_approval[i].Name+'</option>';
            }
        h += '</select>';   

        return h;
    }

    $(document).off('keypress', '.PeopleApprovalMaster').on('keypress', '.PeopleApprovalMaster',function(e) { 
        var ev = $(this).closest('td');
        var Nama = $(this).val();
        var thiss = $(this);
        $(this).autocomplete({
          minLength: 3,
          appendTo: "#TableApprovalMaster",
          select: function (event, ui) {
            event.preventDefault();
            var selectedObj = ui.item;
            thiss.val(selectedObj.value);
            ev.find('label[id="labelNameApprovalMaster"]').html(selectedObj.label);
          },
          /*select: function (event,  ui)
          {

          },*/
          source:
          function(req, add)
          {
            var url = base_url_js+'autocompleteAllUser';
            var data = {
                        Nama : Nama,
                        };
            var token = jwt_encode(data,"UAP)(*");          
            $.post(url,{token:token},function (data_json) {
                var obj = JSON.parse(data_json);
                add(obj.message) 
            })
          } 
        })
    })

    
    $(document).off('click', '#ModalbtnSaveFormMaster').on('click', '#ModalbtnSaveFormMaster',function(e) {
        var ID = $(this).attr('kodeuniq');
        var action = $(this).attr('action');
        if (validationFormMaster()) {
            loading_button('#ModalbtnSaveFormMaster');
            var JsonStatus = [];
            $('.PeopleApprovalMaster').each(function(){
                var ev = $(this).closest('tr');
                var NIP = $(this).val();
                var Status = 0;
                var ApproveAt = '';
                var Representedby = '';
                var Visible = ev.find('.cmbVisibel option:selected').val();
                var NameTypeDesc = ev.find('.cmbTypeUser option:selected').text();
                var temp = {
                    NIP : NIP,
                    Status : Status,
                    ApproveAt : ApproveAt,
                    Representedby : Representedby,
                    Visible : Visible,
                    NameTypeDesc : NameTypeDesc,
                }
                JsonStatus.push(temp);
            })

            var Name = $('#NameMaster').val();
            var data = {
                Name : Name,
                JsonStatusDefault : JsonStatus,
                action : action,
                ID : ID,
            }
            var url = base_url_js+'budgeting/template_master_save';
            var token = jwt_encode(data,"UAP)(*");
            $.post(url,{ token:token },function (resultJson) {
                LoadTableTemlateMaster();
                $('#GlobalModalLarge').modal('hide');
                toastr.success('Saved');
            }).fail(function() {
              toastr.info('No Result Data'); 
            }).always(function() {
                            
            });

        }
    })

    function validationFormMaster()
    {
        var bool =true;
        $('.PeopleApprovalMaster').each(function(){
            if ($(this).val() == '') {
                bool = false;
                toastr.error('Input Approval Required','!Failed');
                return;
            }
        })

        if (bool) {
            var Name = $('#NameMaster').val();
            if (Name == '') {
                toastr.error('Input Name Required','!Failed');
                bool = false;
            }
        }

        return bool;
    } 
</script>