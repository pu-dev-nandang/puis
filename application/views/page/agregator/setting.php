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
                </tr>
                </thead>
            </table>
        </div>
    </div>


</div>

<script>
    $(document).ready(function () {
        loadSelectOptionEmployeesSingle('#formMember','');
        loadSelectOptionMenuAgregator('#formMenu','');

        $('#formMember,#formMenu').select2({allowClear: true});

    });

    $('#btnSaveSetting').click(function () {

        var formID = $('#formID').val();
        var formName = $('#formName').val();
        var formMember = $('#formMember').val();
        var formMenu = $('#formMenu').val();

        if(formName!='' && formName!=null &&
            formMember!='' && formMember!=null &&
        formMenu!='' && formMenu!=null){

            var data = {
                action : (formID!='' && formID!=null) ? 'updateTeamAggr' : 'insertTeamAggr',
                ID : formID,
                dataForm : {
                    Name : formName,
                    Menu : formMenu
                }
                Member : formMember
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudTeamAgregagor';
            $.post(url,{token:token},function (jsonResult) {



            });

        }




    });

</script>