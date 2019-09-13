
<style>
    #dataTablePD tr th, #dataTablePD tr td {
        text-align: center;
    }
</style>

<div class="well">

    <div class="row">
        <div class="col-md-3 form-data-edit" style="border-right: 1px solid #CCCCCC;">

            <div class="form-group">
                <label>Sumber Dana</label>
                <input class="hide" id="formID"/>
                <select class="form-control" id="formSumberDanaID"></select>
                <a style="float: right;" href="javascript:void(0);" id="btnCrud_SD"><i class="fa fa-edit margin-right"></i> sumber dana</a>
                <textarea id="viewSumberDana" class="hide"></textarea>
            </div>
            <div id="viewJenisDana"></div>

            <div class="form-group">
                <label>Year</label>
                <input class="form-control" type="number" id="formYear"/>
            </div>
            <div class="form-group">
                <label>Jumlah Dana</label>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control" id="formCost">
                </div>
            </div>

            <div class="form-group" style="text-align: right;">
                <button class="btn btn-primary" id="btnSavePerolehanDana">Save</button>
            </div>

        </div>
        <div class="col-md-9">
            <div id="viewLoad"></div>

        </div>
    </div>

</div>




<script>
    
    $(document).ready(function () {

        loadDataPerolehanDana();
        loadSumberDana();
        $('#formCost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
        $('#formCost').maskMoney('mask', '9894');
    });
    
    function loadSumberDana() {

        var data = {
            action : 'readSumberDana'
        };

        var token = jwt_encode(data,'UAP)(*');

        var url = base_url_js+'api3/__crudAgregatorTB4';

        $.post(url,{token:token},function (jsonResult) {

            $('#formSumberDanaID, #viewJenisDana,#filterSumberDanaID').empty();
            $('#viewSumberDana').val(JSON.stringify(jsonResult));
            if(jsonResult.length>0){
                $('#formSumberDanaID').append('<option disabled selected>Pilih sumber dana</option><option disabled>----------</option>');
                $.each(jsonResult,function (i,v) {
                    $('#formSumberDanaID').append('<option value="'+v.ID+'">'+v.SumberDana+'</option>');
                });
            }

        });
        
    }

    $('#formSumberDanaID').change(function () {

        var formSumberDanaID = $('#formSumberDanaID').val();
        if(formSumberDanaID!='' && formSumberDanaID!=null){

            $('#viewJenisDana').html('<div class="form-group">' +
                '                <label>Jenis Dana</label>' +
                '                <select class="form-control" id="formSumberDanaTypeID"></select>' +
                '                <a style="float: right;" href="javascript:void(0);" id="btnCrud_JD"><i class="fa fa-edit margin-right"></i> jenis dana</a>' +
                '            </div>');

            var data = {
                action : 'readSumberDanaType',
                SumberDanaID : formSumberDanaID
            };

            var token = jwt_encode(data,'UAP)(*');

            var url = base_url_js+'api3/__crudAgregatorTB4';

            $.post(url,{token:token},function (jsonResult) {
                
                $('#formSumberDanaTypeID').empty();
                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {
                        $('#formSumberDanaTypeID').append('<option value="'+v.ID+'">'+v.Label+'</option>');
                    });
                }

            })

        }

    });

    $(document).on('click','#btnCrud_JD',function () {
        
        var mobalBody = '<div class="row">' +
            '    <div class="col-md-5">' +
            '        <select class="form-control" id="filterSumberDanaID"></select>' +
            '    </div>' +
            '    <div class="col-md-5">' +
            '        <input class="form-control" id="formLabel">' +
            '        <input class="hide" id="formID">' +
            '    </div>' +
            '    <div class="col-md-2">' +
            '        <button class="btn btn-primary btn-block" id="btnSaveSDT">Save</button>' +
            '    </div>' +
            '</div>' +
            '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Sumber Dana</th>' +
            '                <th>Jenis Dana</th>' +
            '                <th style="width: 7%;"><i class="fa fa-cog"></i></th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody id="listDataOK"></tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Jenis Dana</h4>');
        $('#GlobalModal .modal-body').html(mobalBody);

        var viewSumberDana = $('#viewSumberDana').val();
        viewSumberDana = JSON.parse(viewSumberDana);

        if(viewSumberDana.length>0){
            $.each(viewSumberDana,function (i2,v2) {
               $('#filterSumberDanaID').append('<option value="'+v2.ID+'">'+v2.SumberDana+'</option>');
            });
        }


        loadAllJenisDana();


        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $('#btnSaveSDT').click(function () {

            var SumberDanaID = $('#filterSumberDanaID').val();
            var Label = $('#formLabel').val();
            var ID = $('#formID').val();

            if(Label!='' && Label!=null){

                var data = {
                    action : 'UpdateSumberDataType',
                    ID : (ID!='' && ID!=null) ? ID : '',
                    dataForm : {
                        SumberDanaID : SumberDanaID,
                        Label : Label
                    }
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api3/__crudAgregatorTB4';

                $.post(url,{token:token},function (result) {
                    loadAllJenisDana_byID();

                    loadAllJenisDana();
                    toastr.success('Data saved','Success');
                    setTimeout(function () {
                        $('#formLabel').val('');
                        $('#formID').val('');
                    },500);
                });

            }

        });
        
    });

    
    // =================================

    function loadAllJenisDana() {

        var data = {
            action : 'readSumberDanaType_All'
        };

        var token = jwt_encode(data,'UAP)(*');

        var url = base_url_js+'api3/__crudAgregatorTB4';

        $.post(url,{token:token},function (jsonResult) {

            $('#listDataOK').empty();
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {
                    $('#listDataOK').append('<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td>'+v.SumberDana+'</td>' +
                        '<td>'+v.Label+'</td>' +
                        '<td><button class="btn btn-sm btn-default btnEditDanaType" data-id="'+v.ID+'"><i class="fa fa-edit"></i></button>' +
                        '<textarea class="hide" id="viewEditType_'+v.ID+'">'+JSON.stringify(v)+'</textarea>' +
                        '</td>' +
                        '</tr>');
                });
            } else {

            }

        });

    }


    $('#btnCrud_SD').click(function () {

        var viewSumberDana = $('#viewSumberDana').val();
        viewSumberDana = JSON.parse(viewSumberDana);

        var tr = '';
        $.each(viewSumberDana,function (i,v) {
            tr = tr+'<tr>' +
                '<td>'+(i+1)+'</td>' +
                '<td id="viewSD_'+v.ID+'">'+v.SumberDana+'</td>' +
                '<td><button class="btn btn-sm btn-default btnEditSD" data-id="'+v.ID+'"><i class="fa fa-edit"></i></button>' +
                '<textarea class="hide" id="tr_'+v.ID+'">'+JSON.stringify(v)+'</textarea></td>' +
                '</tr>';
        });

        var bodyModal = '<div class="row">' +
            '    <div class="col-md-8">' +
            '            <input class="hide" id="formID">' +
            '            <input class="form-control" id="formSumberDana" placeholder="Sumber dana...">' +
            '    </div>' +
            '    <div class="col-md-4">' +
            '        <button class="btn btn-block btn-primary" id="btnSaveSD">Save</button>' +
            '    </div>' +
            '</div>' +
            '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <hr/><input class="hide" id="lastNumber" value="'+viewSumberDana.length+'"><table class="table">' +
            '            <thead>' +
            '            <tr>' +
            '                <th>No</th>' +
            '                <th>Sumber Dana</th>' +
            '                <th><i class="fa fa-cog"></i></th>' +
            '            </tr>' +
            '            </thead><tbody id="listSD">'+tr+'</tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Sumber Dana</h4>');
        $('#GlobalModal .modal-body').html(bodyModal);
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
        


        $('#btnSaveSD').click(function () {

            var formID = $('#formID').val();
            var formSumberDana = $('#formSumberDana').val();

            var data = {
                action : 'updateSumberDana',
                ID : (formID!='' && formID!=null) ? formID : '',
                SumberDana : formSumberDana
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB4';

            $.post(url,{token:token},function (jsonResult) {

                if(formID!='' && formID!=null){

                    $('#viewSD_'+formID).html(formSumberDana);
                    var nw = {ID:formID,SumberDana:formSumberDana};
                    $('#tr_'+formID).val(JSON.stringify(nw));

                }
                else {

                    var lastNumber = $('#lastNumber').val();
                    var newNum = parseInt(lastNumber) + 1;


                    $('#listSD').append('<tr>' +
                        '<td>'+newNum+'</td>' +
                        '<td id="viewSD_'+jsonResult.ID+'">'+formSumberDana+'</td>' +
                        '<td><button class="btn btn-sm btn-default btnEditSD" data-id="'+jsonResult.ID+'"><i class="fa fa-edit"></i></button>' +
                        '<textarea class="hide" id="tr_'+jsonResult.ID+'">'+JSON.stringify({ID:jsonResult.ID, SumberDana:formSumberDana})+'</textarea></td>' +
                        '</tr>');

                    $('#lastNumber').val(newNum);

                }

                toastr.success();


                $('#formID').val('');
                $('#formSumberDana').val('');

                loadSumberDana();


                
            });

        });
        
    });

    $(document).on('click','.btnEditSD',function () {

        var ID = $(this).attr('data-id');
        var tr_d = $('#tr_'+ID).val();
        tr_d = JSON.parse(tr_d);

        $('#formID').val(tr_d.ID);
        $('#formSumberDana').val(tr_d.SumberDana);

    });

    $(document).on('click','.btnEditDanaType',function () {

        var ID = $(this).attr('data-id');
        var viewEditType = $('#viewEditType_'+ID).val();
        var d = JSON.parse(viewEditType);

        $('#filterSumberDanaID').val(d.SumberDanaID);
        $('#formLabel').val(d.Label);
        $('#formID').val(ID);

    });

    function loadAllJenisDana_byID() {

        var formSumberDanaID = $('#formSumberDanaID').val();

        var data = {
            action : 'readSumberDanaType',
            SumberDanaID : formSumberDanaID
        };

        var token = jwt_encode(data,'UAP)(*');

        var url = base_url_js+'api3/__crudAgregatorTB4';

        $.post(url,{token:token},function (jsonResult) {

            $('#formSumberDanaTypeID').empty();
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {
                    $('#formSumberDanaTypeID').append('<option value="'+v.ID+'">'+v.Label+'</option>');
                });
            }

        });

    }


    // =============================================

    $('#btnSavePerolehanDana').click(function () {

        var formID = $('#formID').val();
        var formSumberDanaID = $('#formSumberDanaID').val();
        var formSumberDanaTypeID = $('#formSumberDanaTypeID').val();
        var formYear = $('#formYear').val();
        var formCost = $('#formCost').val();

        if(formSumberDanaID!='' && formSumberDanaID!=null &&
            formSumberDanaTypeID!='' && formSumberDanaTypeID!=null &&
        formYear!='' && formYear!=null &&
        formCost!='' && formCost!=null){

            loading_buttonSm('#btnSavePerolehanDana');

            var data = {
                action : 'updatePerolehanDana',
                ID : (formID!='' && formID!=null) ? formID : '',
                dataForm : {
                    SumberDanaID : formSumberDanaID,
                    SumberDanaTypeID : formSumberDanaTypeID,
                    Year : formYear,
                    Cost : clearDotMaskMoney(formCost)
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB4';

            $.post(url,{token:token},function (jsonResult) {
                loadDataPerolehanDana();
                toastr.success('Data saved','Success');
                setTimeout(function () {
                    $('#formYear').val('');
                    $('#formCost').val(0);
                    $('#btnSavePerolehanDana').html('Save').prop('disabled',false);
                },500);

            });

        } else {
            toastr.warning('All form required','Warning');
        }


    });
    
    function loadDataPerolehanDana() {

        var data = {
            action : 'readPerolehanDana'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB4';
        
        $.post(url,{token:token},function (jsonResult) {

            console.log(jsonResult);
            $('#viewLoad').html('<table class="table" id="dataTablePD">' +
                '                <thead>' +
                '                <tr>' +
                '                    <th style="width: 1%;">No</th>' +
                '                    <th>Sumber Dana</th>' +
                '                    <th>Jenis Dana</th>' +
                '                    <th style="width: 10%;">Year</th>' +
                '                    <th style="width: 15%;">Jumlah Dana</th>' +
                '                    <th style="width: 10%;"><i class="fa fa-cog"></i></th>' +
                '                </tr>' +
                '                </thead>' +
                '                <tbody id="listData"></tbody>' +
                '            </table>');

            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var btn = '<div class="btn-group">' +
                        '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        '    <i class="fa fa-edit"></i> <span class="caret"></span>' +
                        '  </button>' +
                        '  <ul class="dropdown-menu">' +
                        '    <li><a href="javascript:void(0);" class="btnEditPD" data-id="'+v.ID+'">Edit</a></li>' +
                        '    <li role="separator" class="divider"></li>' +
                        '    <li><a href="javascript:void(0);" class="btnRemovePD" data-id="'+v.ID+'">Remove</a></li>' +
                        '  </ul>' +
                        '</div>';

                   $('#listData').append('<tr>' +
                       '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                       '<td style="text-align: left;">'+v.SumberDana+'</td>' +
                       '<td style="text-align: left;">'+v.SumberDanaType+'</td>' +
                       '<td>'+v.Year+'</td>' +
                       '<td>'+formatRupiah(v.Cost)+'</td>' +
                       '<td style="border-left: 1px solid #ccc;">'+btn+'' +
                       '<textarea class="hide" id="viewDataPD_'+v.ID+'">'+JSON.stringify(v)+'</textarea></td>' +
                       '</tr>');
                });
            }

            $('#dataTablePD').dataTable();
            
        });
        
    }

    $(document).on('click','.btnEditPD',function () {
        var ID = $(this).attr('data-id');
        var viewDataPD = $('#viewDataPD_'+ID).val();
        var d = JSON.parse(viewDataPD);

        $('#formID').val(d.ID);
        $('#formSumberDanaID').val(d.SumberDanaID);

        $('#formYear').val(d.Year);
        $('#formCost').val(d.Cost);
        $('#formCost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
        $('#formCost').maskMoney('mask', '9894');



        $('#viewJenisDana').html('<div class="form-group">' +
            '                <label>Jenis Dana</label>' +
            '                <select class="form-control" id="formSumberDanaTypeID"></select>' +
            '                <a style="float: right;" href="javascript:void(0);" id="btnCrud_JD"><i class="fa fa-edit margin-right"></i> jenis dana</a>' +
            '            </div>');

        var data = {
            action : 'readSumberDanaType',
            SumberDanaID : d.SumberDanaID
        };

        var token = jwt_encode(data,'UAP)(*');

        var url = base_url_js+'api3/__crudAgregatorTB4';

        $.post(url,{token:token},function (jsonResult) {

            $('#formSumberDanaTypeID').empty();
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {
                    var sc = (v.ID = d.SumberDanaTypeID) ? 'selected' : '';
                    $('#formSumberDanaTypeID').append('<option value="'+v.ID+'" '+sc+'>'+v.Label+'</option>');
                });
            }

        });

    });

    $(document).on('click','.btnRemovePD',function () {

        if(confirm('Hapus data?')){

            var ID = $(this).attr('data-id');
            var data = {
                action : 'removePerolehanDana',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB4';

            $.post(url,{token:token},function (result) {

                toastr.success('Data removed','Success');
                loadDataPerolehanDana();

            })

        }

    });
    

</script>