

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
        loadSelectOptionMenuAgregator('#formMenu','','APT');

        $('#formMember,#formMenu').select2({allowClear: true});

        loadSetting();

    });

    $('#btnSaveSetting').click(function () {

        var formID = $('#formID').val();
        var formName = $('#formName').val();
        var formMember = $('#formMember').val();
        var formMenu = $('#formMenu').val();

        if(formName!='' && formName!=null &&
            formMember!='' && formMember!=null &&
        formMenu!='' && formMenu!=null){

            loading_buttonSm('#btnSaveSetting');

            var data = {
                action : (formID!='' && formID!=null) ? 'updateTeamAggr' : 'insertTeamAggr',
                ID : formID,
                dataForm : {
                    Name : formName,
                    Menu : JSON.stringify(formMenu)
                },
                Member : formMember
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudTeamAgregagor';
            $.post(url,{token:token},function (jsonResult) {

                loadSetting();
                $('#formName,#formID').val('');
                $('#formMember,#formMenu').val(null).trigger('change');

                setTimeout(function () {
                    $('#btnSaveSetting').html('Save').prop('disabled',false);
                },500);

            });

        }

    });

    function loadSetting() {

        var data = {
            action : 'readTeamAggr'
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

                    $('#listTeamSetting').append('<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td><b>'+v.Name+'</b><div>'+Member+'</div></td>' +
                        '<td><ul>'+Menu+'</ul></td>' +
                        '<td>' +
                        '<button class="btn btn-sm btn-default btnEdit" data-id="'+v.ID+'"><i class="fa fa-edit"></i></button> ' +
                        '<button class="btn btn-sm btn-danger btnRemove" data-id="'+v.ID+'"><i class="fa fa-trash"></i></button> ' +
                        '</td>' +
                        '</tr>')
                });
            }

        });

    }

    $(document).on('click','.btnEdit',function () {

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

    });

    $(document).on('click','.btnRemove',function () {

        if(confirm('Are you sure?')){

            var ID = $(this).attr('data-id');

            var data = {
                action : 'removeTeamAggr',
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