<style>
    ul > li {
        display: inline-block;
        /* You can also add some margins here to make it look prettier */
        zoom:1;
        *display:inline;
        /* this fix is needed for IE7- */
    }
</style>


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
                <tbody id="listTeamSetting"></tbody>
            </table>
        </div>
    </div>


</div>

<script>
    $(document).ready(function () {
        loadSelectOptionEmployeesSingle('#formMember','');
        loadSelectOptionMenuAgregator('#formMenu','');

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

            $('#listTeamSetting').empty();
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var Member = '';
                    if(v.Member.length>0){
                        $.each(v.Member,function (i2,v2) {
                            Member = Member +', '+v2.Name;
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
                        '</tr>')
                });
            }

        });

    }

</script>