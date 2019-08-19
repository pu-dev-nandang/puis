<form class="form-horizontal" id="formModal">
    <div class="form-group"> 
        <div class="row">
            <div class="col-sm-2">
                <label class="control-label">Choose Master :</label>
            </div>    
            <div class="col-sm-6" id="SelectMaster">
                
            </div>
        </div>
    </div>
    <div class="form-group"> 
        <div class="row">
            <div class="col-sm-2">
                <label class="control-label">StartDate :</label>
            </div>    
            <div class="col-sm-4">
                <div class="input-group input-append date datetimepicker">
                    <input data-format="yyyy-MM-dd" class="form-control StartDateTransaksi" type=" text" readonly="">
                    <span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group"> 
        <div class="row">
            <div class="col-sm-2">
                <label class="control-label">EndDate :</label>
            </div>    
            <div class="col-sm-4">
                <div class="input-group input-append date datetimepicker">
                    <input data-format="yyyy-MM-dd" class="form-control EndDateTransaksi" type=" text" readonly="">
                    <span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group"> 
        <div class="row">
            <div class="col-sm-2">
                <label class="control-label">Approval :</label>
            </div>    
            <div class="col-sm-10">
                <table class="table table-bordered tableData" id ="TableApprovalTransaksi">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <!-- <th>Type User</th>
                            <th>Visible</th> -->
                            <!-- <th>Action</th> -->
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
            <button type="button" id="ModalbtnCancleFormTransaksi" data-dismiss="modal" class="btn btn-default">Cancel</button>
            <button type="button" id="ModalbtnSaveFormTransaksi" class="btn btn-success" action = "<?php echo $action ?>" kodeuniq = "<?php echo $ID ?>">Save</button>
		</div>
    </div>    
</form>
<script type="text/javascript">
    var cfg_m_type_approval = <?php echo json_encode($cfg_m_type_approval) ?>;
    var cfg_m_userrole = <?php echo json_encode($cfg_m_userrole) ?>;
    var m_template = <?php echo json_encode($m_template) ?>;
    $(document).ready(function() {
         <?php if ($action == 'edit'): ?>
            LoadExistingDataTransaksi(); 
         <?php else: ?>
            LoadDataTransaksiAdd();         
         <?php endif ?>   
    }); // exit document Function

    function LoadExistingDataTransaksi()
    {
        <?php if ($action == 'edit'): ?>
        $('#SelectMaster').html(OPcmbMaster('<?php echo $getData[0]['ID_m_template'] ?>'));
        $('.StartDateTransaksi').val('<?php echo $getData[0]['StartDate'] ?>');
        $('.EndDateTransaksi').val('<?php echo $getData[0]['EndDate'] ?>');
        var html =  '';
        var  JsonStatus = jQuery.parseJSON(<?php echo json_encode($getData[0]['JsonStatusDefault']) ?>);
        for (var i = 1; i < cfg_m_userrole.length; i++) {
            var getSelected = false;
            for (var j = 0; j < JsonStatus.length; j++) {
                if (cfg_m_userrole[i].ID == JsonStatus[j].ID_m_userrole) {
                    getSelected = true;
                    // var cmb = OPcmbTypeUser(JsonStatus[j].NameTypeDesc);
                    // var visible = OPcmbVisibel(JsonStatus[j].Visible);
                    var input = '<input type = "checkbox" value = "'+JsonStatus[j].ID_m_userrole+'" class="chk_MuserRole" checked> '+JsonStatus[j].Name;
                    break;

                }
            }

            var btnDelete = '<button type="button" class="btn btn-danger btn-delete btn-delete-setRoleUserMaster" code=""> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';

            if (!getSelected) {
                // var cmb = OPcmbTypeUser();
                // var visible = OPcmbVisibel();
                var input = '<input type = "checkbox" value = "'+cfg_m_userrole[i].ID+'" class="chk_MuserRole"> '+cfg_m_userrole[i].NameUserRole;
            }

           html += '<tr>'+
                       '<td></td>'+
                       '<td>'+input+'</td>'+
                       // '<td>'+cmb+'</td>'+
                       // '<td>'+visible+'</td>'+
                       // '<td>'+btnDelete+'</td>'+
                   '</tr>'; 
        }
        $('#TableApprovalTransaksi tbody').html(html);          
        MakeAutoNumbering();
        <?php endif ?> 
    }

    function LoadDataTransaksiAdd()
    {
        $('#SelectMaster').html(OPcmbMaster());
        $('.datetimepicker').datetimepicker({
         format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
        });
    }

    function OPcmbMaster(IDselected = null,Dis='')
    {
        var h = '';
        h = '<select class = " form-control cmbMaster" '+Dis+'>';
        h += '<option value="" selected disabled>--Choose--</option>';
            for (var i = 0; i < m_template.length; i++) {
                if (IDselected != null) {
                    var selected = (IDselected == m_template[i].ID) ? 'selected' : '';
                }
                h += '<option value = "'+m_template[i].ID+'" '+selected+' >'+m_template[i].Name+'</option>';
            }
        h += '</select>';   

        return h;
    }

    function FindDataWhere(ID_m_template)
    {
        var arr = [];
        for (var i = 0; i < m_template.length; i++) {
            if (ID_m_template == m_template[i].ID) {
                arr.push(m_template[i]);
                break;
            }
        }

        return arr;
    }

    
    $(document).off('change', '.cmbMaster').on('change', '.cmbMaster',function(e) {
        var html = '';
        var ID_m_template = $(this).val();
        var FindData = FindDataWhere(ID_m_template);
        var JsonStatus = jQuery.parseJSON(FindData[0].JsonStatusDefault);
        // adding combo Type User
            for (var i = 1; i < cfg_m_userrole.length; i++) {
                var getSelected = false;
               for (var j = 0; j < JsonStatus.length; j++) {
                   if (cfg_m_userrole[i].ID == JsonStatus[j].ID_m_userrole) {
                       getSelected = true;
                       var input = '<input type = "checkbox" value = "'+JsonStatus[j].ID_m_userrole+'" class="chk_MuserRole" checked> '+JsonStatus[j].Name;
                       break;

                   }
               }
               var btnDelete = '<button type="button" class="btn btn-danger btn-delete btn-delete-setRoleUserTransaksi" code=""> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
               if (!getSelected) {
                   // var cmb = OPcmbTypeUser();
                   // var visible = OPcmbVisibel();
                   var input = '<input type = "checkbox" value = "'+cfg_m_userrole[i].ID+'" class="chk_MuserRole"> '+cfg_m_userrole[i].NameUserRole;
               }
               html += '<tr>'+
                           '<td></td>'+
                           '<td>'+input+'</td>'+
                           // '<td>'+cmb+'</td>'+
                           // '<td>'+visible+'</td>'+
                           // '<td>'+btnDelete+'</td>'+
                       '</tr>'; 
            }
               
            $('#TableApprovalTransaksi tbody').html(html);          
            MakeAutoNumbering();
    })

    function MakeAutoNumbering()
    {
        var no = 1;
        $("#TableApprovalTransaksi tbody tr").each(function(){
            var a = $(this);
            a.find('td:eq(0)').html(no);
            no++;
        })
    }

    $(document).off('click', '#ModalbtnSaveFormTransaksi').on('click', '#ModalbtnSaveFormTransaksi',function(e) {
        var ID = $(this).attr('kodeuniq');
        var action = $(this).attr('action');
        if (validationFormTransaksi()) {
            loading_button('#ModalbtnSaveFormTransaksi');
            var JsonStatus = [];
            $('.chk_MuserRole:checked').each(function(){
                var ev = $(this).closest('tr');
                var ID_m_userrole = $(this).val();
                var temp = {
                    ID_m_userrole : ID_m_userrole,
                }
                JsonStatus.push(temp);
            })

            var ID_m_template = $('.cmbMaster option:selected').val();
            var StartDate = $('.StartDateTransaksi').val();
            var EndDate = $('.EndDateTransaksi').val();
            var data = {
                ID_m_template : ID_m_template,
                StartDate : StartDate,
                EndDate : EndDate,
                JsonStatus : JsonStatus,
                action : action,
                ID : ID,
            }
            var url = base_url_js+'budgeting/template_transaksi_save';
            var token = jwt_encode(data,"UAP)(*");
            $.post(url,{ token:token },function (resultJson) {
                LoadTableTemlateTransaksi();
                $('#GlobalModalLarge').modal('hide');
                toastr.success('Saved');
            }).fail(function() {
              toastr.info('No Result Data'); 
            }).always(function() {
                            
            });

        }
    })

    function validationFormTransaksi()
    {
        var bool =true;
        var Count = 0;
        $('.chk_MuserRole:checked').each(function(){
            Count++;
        })

        if (Count == 0) {
            bool = false;
        }

        if (bool) {
            var ID_m_template = $('.cmbMaster option:selected').val();
            if (ID_m_template == '' || ID_m_template == null || ID_m_template == undefined ) {
                toastr.error('Choose Master','!Failed');
                bool = false;
            }

            var StartDate = $('.StartDateTransaksi').val();
            if (StartDate == '' || StartDate == null || StartDate == undefined ) {
                toastr.error('StartDate Required','!Failed');
                bool = false;
            }

            var EndDate = $('.EndDateTransaksi').val();
            if (EndDate == '' || EndDate == null || EndDate == undefined ) {
                toastr.error('EndDate Required','!Failed');
                bool = false;
            }
        }

        return bool;
    }
</script>