

<?php

if($access=='1'){ ?>

<div class="well">


    <div class="row">
        <div class="col-md-3" style="border-right: 1px solid #CCCCCC;">
            <div class="form-group">
                <label>Team</label>
                <input type="text" class="hide" id="formID" />
                <input type="text" class="form-control" id="formName" />
            </div>
            <div class="form-group">
                <label>Member</label>
                <select class="select2-select-00 full-width-fix" multiple size="5" id="formMember">
                </select>
            </div>
            <div class="form-group">
                <label>Menu</label>
                <select class="select2-select-00 full-width-fix" multiple size="5" id="formMenu">
                </select>
            </div>
            <div class="form-group">
                <label>Input Access</label>
                <select class="form-control" id = "formInputAccess">
                    <option value="false" selected>False</option>
                    <option value="true">True</option>
                </select>
            </div>
            <div class="form-group">
                <label>View Access</label>
                <select class="form-control" id = "formViewAccess">
                    <option value="prodi" selected>Choose Prodi</option>
                    <option value="all">All Prodi</option>
                </select>
            </div>
            <div class="form-group">
                <label>Program Study Access</label>
                <select class="select2-select-00 full-width-fix" multiple size="5" id="formProdiAccess">
                </select>
            </div>
            <div class="form-group" style="text-align: right;">
                <button class="btn btn-primary" id="btnSaveSetting">Save</button>
            </div>
        </div>
        <div class="col-md-9">
            <table class="table">
                <thead>
                <tr>
                    <th style="width: 1%;">No</th>
                    <th style="width: 25%;">Team</th>
                    <th>Menu</th>
                    <th>Access</th>
                    <th style="width: 15%;"><i class="fa fa-cog"></i></th>
                </tr>
                </thead>
                <tbody id="listTeamSetting"></tbody>
            </table>
            <textarea class="hide" id="viewListAll"></textarea>
        </div>
    </div>


</div>

<script>
    $(document).ready(function () {
        loadSelectOptionEmployeesSingle('#formMember','');
        loadSelectOptionMenuAgregator('#formMenu','','APS');
        loadSelectOptionBaseProdi('#formProdiAccess','');
        $('#formMember,#formMenu,#formProdiAccess').select2({allowClear: true});
        $('#formViewAccess').trigger('change');
        loadSetting();

    });

    $(document).on('change','#formViewAccess',function () {
        var v = $(this).val();
        if (v== 'all') {
            var PRAccess = [];
            $('#formProdiAccess').find('option').each(function(){
                var vOp = $(this).val();
                PRAccess.push(vOp);
            })
            $('#formProdiAccess').select2('val',PRAccess);
            $('#formProdiAccess').prop('disabled',true);
            // $('#formProdiAccess').prop('readonly',true);
        }
        else
        {
            $('#formProdiAccess').select2('val',[]);
            $('#formProdiAccess').prop('disabled',false);
            // $('#formProdiAccess').prop('readonly',false);
        }
    });

    $('#btnSaveSetting').click(function () {

        var formID = $('#formID').val();
        var formName = $('#formName').val();
        var formMember = $('#formMember').val();
        var formMenu = $('#formMenu').val();
        var formInputAccess = $('#formInputAccess option:selected').val();
        var formProdiAccess = $('#formProdiAccess').val();
        var formViewAccess = $('#formViewAccess option:selected').val();
        // console.log(formProdiAccess);return;
        if(formName!='' && formName!=null &&
            formMember!='' && formMember!=null &&
        formMenu!='' && formMenu!=null && formProdiAccess != '' && formProdiAccess != null){

            loading_buttonSm('#btnSaveSetting');

            var data = {
                action : (formID!='' && formID!=null) ? 'updateTeamAggr_APS' : 'insertTeamAggr_APS',
                ID : formID,
                dataForm : {
                    Name : formName,
                    Menu : JSON.stringify(formMenu),
                },
                input : formInputAccess,
                ProdiID : formProdiAccess,
                view : formViewAccess,
                Member : formMember
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudTeamAgregagor';
            $.post(url,{token:token},function (jsonResult) {

                loadSetting();
                $('#formName,#formID').val('');
                $('#formMember,#formMenu,#formProdiAccess').val(null).trigger('change');

                setTimeout(function () {
                    $('#btnSaveSetting').html('Save').prop('disabled',false);
                },500);

            });

        }

    });

    function loadSetting() {

        var data = {
            action : 'readTeamAggr_APS',
            Type : 'APS',
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudTeamAgregagor';
        $.post(url,{token:token},function (jsonResult) {

            $('#viewListAll').val(JSON.stringify(jsonResult));
            $('#listTeamSetting').empty();
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var Member = '';
                    if(v.Member.length>0){
                        $.each(v.Member,function (i2,v2) {
                            var koma = (i2!=0) ? ', ' : '';
                            Member = Member +''+koma+''+v2.Name;
                        });
                    }

                    var Menu = '';
                    if(v.DetailMenu.length>0){
                        $.each(v.DetailMenu,function (i2,v2) {

                            Menu = Menu+'<li>'+v2.Name+'</li>'
                        });
                    }

                    var AccessWr = '<ul>';
                    var dtP = v.Access;
                    dtp =  jQuery.parseJSON(dtP);
                    // console.log(dtp);
                    AccessWr += '<li>Input : '+dtp.input+'</li>';
                    AccessWr += '<li>View : '+dtp.view+'</li>';
                    AccessWr += '<li><b>Program Study</b><ul>';
                    var PR = dtp.ProdiID;
                    for (var z = 0; z < PR.length; z++) {
                        var spPR = PR[z].split('.');
                        // console.log(dtP[i]);
                        AccessWr += '<li>'+spPR[1]+'</li>';
                    }

                    AccessWr += '</ul></li></ul>';
                    var jwtPass = jwt_encode(dtp,'UAP)(*');
                    $('#listTeamSetting').append('<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td><b>'+v.Name+'</b><div>'+Member+'</div></td>' +
                        '<td><ul>'+Menu+'</ul></td>' +
                        '<td>'+AccessWr+'</td>' +
                        '<td>' +
                        '<button class="btn btn-sm btn-default btnEdit" data-id="'+v.ID+'" dtother = "'+jwtPass+'"><i class="fa fa-edit"></i></button> ' +
                        '<button class="btn btn-sm btn-danger btnRemove_aps" data-id="'+v.ID+'"><i class="fa fa-trash"></i></button> ' +
                        '</td>' +
                        '</tr>')
                });
            }

        });

    }

    $(document).on('click','.btnEdit',function () {
        var dtother = $(this).attr('dtother');
        dtother = jwt_decode(dtother);
        var viewListAll = $('#viewListAll').val();
        var viewListAll = JSON.parse(viewListAll);

        var ID = $(this).attr('data-id');

        var result = $.grep(viewListAll, function(e){ return e.ID == ID; });

        var d = result[0];

        $('#formID').val(d.ID);
        $('#formName').val(d.Name);

        var member = [];
        if(d.Member.length>0){
            $.each(d.Member,function (i,v) {
                member.push(v.NIP);
            });
        }

        $('#formMember').select2('val',member);

        var menu = [];
        if(d.DetailMenu.length>0){
            $.each(d.DetailMenu,function (i,v) {
                menu.push(v.ID);
            });
        }

        $('#formMenu').select2('val',menu);

        // dtother
        $("#formInputAccess option").filter(function() {
           //may want to use $.trim in here
           return $(this).val() == dtother.input; 
        }).prop("selected", true);

        $("#formViewAccess option").filter(function() {
           //may want to use $.trim in here
           return $(this).val() == dtother.view; 
        }).prop("selected", true);
        $('#formViewAccess').trigger('change');
        var arr_prodi = dtother.ProdiID;
        var pass_arr_prodi = [];
        for (var i = 0; i < arr_prodi.length; i++) {
            pass_arr_prodi.push(arr_prodi[i]);
        }

        $('#formProdiAccess').select2('val',pass_arr_prodi);
        
    });

    $(document).on('click','.btnRemove_aps',function () {

        if(confirm('Are you sure?')){

            var ID = $(this).attr('data-id');

            var data = {
                action : 'removeTeamAggr_APS',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudTeamAgregagor';
            $.post(url,{token:token},function (result) {
                loadSetting();
            });

        }

    });

</script>

<?php } else { ?>

    <div class="well">
        <div class="row">
            <div class="col-md-12" style="text-align: center;padding-bottom: 20px;">
                <h3>You don't have access to this menu</h3>
            </div>
        </div>
    </div>

<?php } ?>