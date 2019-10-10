<style>
    .tb-agregator tr th, .tb-agregator tr td {
        text-align: center;
    }
</style>

<div class="col-md-3">
    <div class="thumbnail" style="padding: 15px;">
        <h3>Admin Agregator</h3>
        <hr/>
        <div class="well">
            <div class="row">
                <div class="col-md-9">
                    <select class=" full-width-fix" size="5" id="formNewAdmin">
                        <option value=""></option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-sm btn-block btn-success" id="btnAddAdminAgg">Add</button>
                </div>
            </div>
        </div>
        <table class="table table-striped tb-agregator">
            <thead>
            <tr>
                <th style="width: 1%;">No</th>
                <th>Admin</th>
                <th style="width: 15%;"><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody id="listAdminAg"></tbody>
        </table>
    </div>

    <hr/>

    <div class="thumbnail" style="padding: 15px;">
        <h3>Heading Menu Agregator</h3>
        <hr/>
        <div class="well">
            <div class="row">
                <div class="col-md-5">
                    <input class="hide" id="formID">
                    <input class="form-control" id="formName">
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="formType">
                        <option value="APT">APT</option>
                        <option value="APS">APS</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-block btn-sm btn-success" id="btnActSave">Save</button>
                </div>
            </div>
        </div>

        <table class="table table-striped tb-agregator">
            <thead>
            <tr>
                <th style="width: 1%;">No</th>
                <th>Name</th>
                <th style="width: 15%;"><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody id="listHeaderMenu"></tbody>
        </table>
    </div>
</div>

<div class="col-md-9">
    <div class="thumbnail" style="padding: 15px;">
        <h3>Menu Agregator</h3>
        <hr/>

        <div class="row">
            <div class="col-md-4">
                <div class="well" style="min-height: 100px;">

                    <div class="form-group">
                        <label>Select Header</label>
                        <select class="form-control" id="formMenuHeaderID"></select>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input class="hide" id="formMenuID" />
                        <input class="form-control" id="formMenuName" />
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea rows="3" class="form-control" id="formMenuDescription"></textarea>
                    </div>
                    <div class="form-group">
                        <label>URL (Sesuai nama uri di routes)</label>
                        <input class="form-control" id="formMenuURL" />
                    </div>
                    <div class="form-group">
                        <label>View (Sesuai nama file view)</label>
                        <input class="form-control" id="formMenuView" />
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="formHideMenu"> Hide Menu
                            </label>
                        </div>
                    </div>

                    <div class="form-group" style="text-align: right;">
                        <button class="btn btn-primary" id="btnSaveMenu">Save</button>
                    </div>
                </div>
            </div>
            <div class="col-md-8" id="divMenuAgregator">

            </div>
        </div>



    </div>
</div>


<script>


    $(document).ready(function () {
        loadSelectOptionEmployeesSingle('#formNewAdmin','');
        $('#formNewAdmin').select2({allowClear: true});
        readListadmin();

        // ====

        loadHeaderMenu();

        loadMenuAgregator();

        $('#formMenuDescription').summernote({
            placeholder: 'Text your announcement',
            tabsize: 2,
            height: 300,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });

    });

    // === ADMIN AGREGATOR ===
    function readListadmin() {
        var data = {
            action : 'readAgregatorAdmin'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregator';
        $('#listAdminAg').empty();
        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){

                $.each(jsonResult,function (i,v) {
                    $('#listAdminAg').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.Name+'<br/>'+v.NIP+'</td>' +
                        '<td><button class="btn btn-sm btn-danger btnRemoveAggrAdmin" data-id="'+v.ID+'"><i class="fa fa-trash"></i></button></td>' +
                        '</tr>');
                });
            }
        });

    }

    $('#btnAddAdminAgg').click(function () {
        var formNewAdmin = $('#formNewAdmin').val();

        if(formNewAdmin!='' && formNewAdmin!=null){

            var data = {
                action : 'addAgregatorAdmin',
                NIP : formNewAdmin
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregator';

            $.post(url,{token:token},function (result) {

                var result = parseInt(result);

                if(result>0){
                    toastr.success('Data added','Success');
                    readListadmin();
                } else {
                    toastr.error('Data already exist','Error');
                }

            });

        } else {
            toastr.warning('Must choose employees','Warning');
        }


    });

    $(document).on('click','.btnRemoveAggrAdmin',function () {

        if(confirm('Are you sure?')){
            var ID = $(this).attr('data-id');
            var data = {
                action : 'removeAgregatorAdmin',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregator';

            $.post(url,{token:token},function (result) {
                toastr.success('Data removed','Success');
                readListadmin();
            });
        }

    });

    // === PENUTUP ADMIN AGREGATOR ===


    // === HEADER MENU AGREGATOR ===
    function loadHeaderMenu() {
        var data = {
            action : 'readAgregatorHeaderMenu'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregator';
        $('#listHeaderMenu,#formMenuHeaderID').empty();
        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var btnAct = '<div class="btn-group">' +
                        '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        '    <i class="fa fa-edit"></i> <span class="caret"></span>' +
                        '  </button>' +
                        '  <ul class="dropdown-menu">' +
                        '    <li><a href="javascript:void(0);" class="btnEditHeader" data-id="'+v.ID+'" data-name="'+v.Name+'" data-type="'+v.Type+'">Edit</a></li>' +
                        '    <li><a href="javascript:void(0);" data-id="'+v.ID+'" class="btnRemoveHeader">Remove</a></li>' +
                        '  </ul>' +
                        '</div>';

                    $('#listHeaderMenu').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.Name+'<br/>'+v.Type+'</td>' +
                        '<td>'+btnAct+'</td>' +
                        '</tr>');

                    $('#formMenuHeaderID').append('<option value="'+v.ID+'">'+v.Type+' - '+v.Name+'</option>');

                });
            }

        });
    }

    $(document).on('click','.btnRemoveHeader',function () {
       if(confirm('Jika header dihapus, maka data menu yang ada di dalamnya juga akan terhapus, lanjutkan?')){
           var ID = $(this).attr('data-id');
           var data = {
               action : 'removeAgregatorHeaderMenu',
               ID : ID
           };

           var token = jwt_encode(data,'UAP)(*');
           var url = base_url_js+'api3/__crudAgregator';
           $.post(url,{token:token},function (result) {

               toastr.success('Data removed','Success');
               loadHeaderMenu();

           });
       }
    });

    $(document).on('click','.btnEditHeader',function () {

        $('#formID').val($(this).attr('data-id'));
        $('#formName').val($(this).attr('data-name'));
        $('#formType').val($(this).attr('data-type'));

    });

    $('#btnActSave').click(function () {
        var formID = $('#formID').val();
        var formName = $('#formName').val();
        var formType = $('#formType').val();

        if(formName!='' && formName!=null){
            var data = {
                action : 'updateAgregatorHeaderMenu',
                ID : (formID!='' && formID!=null) ? formID : '',
                dataForm : {
                    Name : formName,
                    Type : formType
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregator';

            $.post(url,{token:token},function (result) {
                toastr.success('Data saved','Success');
                $('#formID').val('');
                $('#formName').val('');
                loadHeaderMenu();
            })

        }

    });

    // === PENUTUP HEADER MENU AGREGATOR ===


    function loadMenuAgregator() {
        var data = {
            action : 'readAgregatorMenu'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregator';
        $('#divMenuAgregator').html('<textarea id="dataRowMenu" class="hide"></textarea><table class="table tb-agregator" id="tableMenu">' +
            '                    <thead>' +
            '                    <tr>' +
            '                        <th style="width: 1%;">No</th>' +
            '                        <th style="width: 20%;">Header</th>' +
            '                        <th style="width: 25%;">Name</th>' +
            '                        <th>URI</th>' +
            '                        <th style="width: 10%;"><i class="fa fa-cog"></i></th>' +
            '                    </tr>' +
            '                    </thead>' +
            '                    <tbody id="listMenuAgregator"></tbody>' +
            '                </table>');

        $.post(url,{token:token},function (jsonResult) {

            $('#dataRowMenu').val(JSON.stringify(jsonResult));

            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var btnAct = '<div class="btn-group">' +
                        '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        '    <i class="fa fa-edit"></i> <span class="caret"></span>' +
                        '  </button>' +
                        '  <ul class="dropdown-menu">' +
                        '    <li><a href="javascript:void(0);" class="btnMenuEdit" data-i="'+i+'">Edit</a></li>' +
                        '    <li><a href="javascript:void(0);" class="btnMenuRemove" data-id="'+v.ID+'">Remove</a></li>' +
                        '  </ul>' +
                        '</div>';

                    var Description = (v.Description!=null && v.Description!='') ? v.Description : '-';

                    var colorHide = (parseInt(v.HideMenu)==1) ? 'style="color:red;"' : '';

                    $('#listMenuAgregator').append('<tr '+colorHide+'>' +
                        '<td style="border-right: 1px solid #CCCCCC;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.H_Name+'<br/>'+v.H_Type+'</td>' +
                        '<td style="text-align: left;">'+v.Name+'<div style="margin-top: 10px;padding: 10px;" class="well"><div style="max-height: 70px;overflow: auto;">'+Description+'</div></div></td>' +
                        '<td style="text-align: left;">URL : '+v.URL+' <br/>' +
                        'View : '+v.View+'</td>' +
                        '<td>'+btnAct+'</td>' +
                        '</tr>');

                });
            }

            $('#tableMenu').dataTable({
                "pageLength" : 10
            });

        });
    }

    $(document).on('click','.btnMenuRemove',function () {

        if(confirm('Hapus data menu?')){
            var ID = $(this).attr('data-id');
            var data = {
                action : 'removedAgregatorMenu',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregator';

            $.post(url,{token:token},function (result) {
                toastr.success('Data removed','Success');
                loadMenuAgregator();
            });
        }

    });

    $(document).on('click','.btnMenuEdit',function () {
       var i = $(this).attr('data-i');
       var dataRowMenu = JSON.parse($('#dataRowMenu').val());
       var d = dataRowMenu[i];

        $('#formMenuHeaderID').val(d.MHID);
        $('#formMenuID').val(d.ID);
        $('#formMenuName').val(d.Name);

        if(d.Description!='' && d.Description!=null){
            $('#formMenuDescription').summernote('code', d.Description);
        } else {
            $('#formMenuDescription').summernote('code', '');
        }

        $('#formMenuURL').val(d.URL);
        $('#formMenuView').val(d.View);

        if(d.HideMenu=='1' || d.HideMenu==1){
            $('#formHideMenu').prop('checked',true);
        } else {
            $('#formHideMenu').prop('checked',false);
        }

    });

    $('#btnSaveMenu').click(function () {

        var formMenuHeaderID = $('#formMenuHeaderID').val();
        var formMenuID = $('#formMenuID').val();
        var formMenuName = $('#formMenuName').val();
        var formMenuDescription = $('#formMenuDescription').val();
        var formMenuURL = $('#formMenuURL').val();
        var formMenuView = $('#formMenuView').val();

        var formHideMenu = ( $('#formHideMenu').is(':checked')) ? '1' : '0';

        if(formMenuHeaderID!='' && formMenuHeaderID!=null &&
            formMenuName!='' && formMenuName!=null &&
        formMenuURL!='' && formMenuURL!=null &&
        formMenuView!='' && formMenuView!=null){

            var data = {
                action : 'updateAgregatorMenu',
                ID : (formMenuID!='' && formMenuID!=null) ? formMenuID : '',
                dataForm : {
                    MHID : formMenuHeaderID,
                    Name : formMenuName,
                    Description : formMenuDescription,
                    URL : formMenuURL,
                    View : formMenuView,
                    HideMenu : formHideMenu
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregator';

            $.post(url,{token:token},function () {
               toastr.success('Data saved','Success');
                loadMenuAgregator();
                $('#formMenuID').val('');
                $('#formMenuName').val('');
                $('#formMenuDescription').summernote('code', '');
                $('#formMenuURL').val('');
                $('#formMenuView').val('');
                $('#formHideMenu').prop('checked',false);
            });

        }

    });

</script>