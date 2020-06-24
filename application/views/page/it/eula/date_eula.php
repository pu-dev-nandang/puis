
<style>
    #panelCreatePD .table tbody>tr>td {
        border-top: 1px solid #fff;
    }

    #sortEULA li {
        padding-top: 5px;
        padding-bottom: 5px;
        border: 1px solid rgb(214, 213, 213);
        padding-left: 10px;
        padding-right: 10px;
        margin-bottom: 5px;
        border-radius: 6px;
        cursor: grab;
    }
</style>

<div class="row">
    <div class="col-md-12" style="text-align: right;">
        <button class="btn btn-default" id="btnCreateDate">Create Publication Date</button>
    </div>
</div>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-12" id="loadTable"></div>
</div>

<script>

    $(document).ready(function () {
        loadDataPD();
    });

    $(document).on('click','.btnEditDate',function () {
        var PDID = $(this).attr('data-pdid');
        loadModalUpdatePD(PDID);
    });

    function loadDataPD(){

        $('#loadTable').html('<table id="tableData" class="table table-bordered table-centre">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Start</th>' +
            '                <th>End</th>' +
            '                <th style="width: 10%;">To</th>' +
            '                <th style="width: 10%;">Status</th>' +
            '                <th style="width: 10%;" ><i class="fa fa-cog"></i></th>' +
            '                <th style="width: 10%;">EULA</th>' +
            '            </tr>' +
            '            </thead>' +
            '        </table>');


        var data = {
            action : 'getListPublicationDate',
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudEula';

        var dataTable = $('#tableData').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Title, Description"
            },
            "ajax":{
                url :url, // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    loading_modal_hide();
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );

    }

    $('#btnCreateDate').click(function () {
        loadModalUpdatePD('');
    });

    function loadModalUpdatePD(PDID){

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Publication Date</h4>');

        var htmlss = '<table class="table">' +
            '    <tr>' +
            '        <td style="width: 15%;">Start</td>' +
            '        <td style="width: 1%;">:</td>' +
            '        <td>' +
            '            <input id="formDateStart" class="form-control" readonly style="background: #fff;color:#333;">' +
            '            <input id="formID" class="hide">' +
            '        </td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>End</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <input id="formDateEnd" class="form-control" readonly style="background: #fff;color:#333;">' +
            '        </td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>To</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <select id="formTo" class="form-control">' +
            '                <option value="std">Students</option>' +
            '                <option value="emp">Employees</option>' +
            '            </select>' +
            '        </td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Status</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <div class="checkbox" style="margin: 0px;">' +
            '                <label>' +
            '                    <input id="formPublished" type="checkbox" value="1"> Publish' +
            '                </label>' +
            '            </div>' +
            '        </td>' +
            '    </tr>' +
            '</table>';

        $('#GlobalModal .modal-body').html('<div id="panelCreatePD">'+htmlss+'</div><div id="showAlertPD"></div>');

        $('#formDateStart,#formDateEnd').datepicker({
            showOtherMonths:true,
            autoSize: true,
            dateFormat: 'dd MM yy'
        });

        if(PDID!=''){
            var dataPD = $('#dataPD_'+PDID).val();
            var d = JSON.parse(dataPD);

            $('#formDateStart').datepicker('setDate',new Date(d.RangeStart));
            $('#formID').val(d.ID);
            $('#formDateEnd').datepicker('setDate',new Date(d.RangeEnd));
            $('#formTo').val(d.To);

            $('#formPublished').prop('checked',(d.Published=='1')? true : false);

        }

        $('#GlobalModal .modal-footer').html('<button id="btnSavePublicationDate" class="btn btn-success">Save</button> ' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    }

    $(document).on('click','#btnSavePublicationDate',function () {

        var formDateStart = ($('#formDateStart').datepicker("getDate")!=null)
                            ? moment($('#formDateStart').datepicker("getDate")).format('YYYY-MM-DD')
                            : '';
        var formDateEnd = ($('#formDateEnd').datepicker("getDate")!=null)
                            ? moment($('#formDateEnd').datepicker("getDate")).format('YYYY-MM-DD')
                            : '';

        if(formDateStart!='' && formDateEnd!=''){

            var formTo = $('#formTo').val();
            var formPublished = ($('#formPublished').is(':checked')) ? '1' : '0';

            var ID = $('#formID').val();

            var data = {
                action : 'updatePublicationDate',
                ID : (ID!='' && ID!=null) ? ID : '',
                dataForm : {
                    RangeStart : formDateStart,
                    RangeEnd : formDateEnd,
                    To : formTo,
                    Published : formPublished
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api4/__crudEula';

            $.post(url,{token:token},function (jsonResult) {

                $('#showAlertPD').html('');

                if(parseInt(jsonResult.Status)=='1') {

                    toastr.success(jsonResult.Message,'Success');
                    loadDataPD();

                    if(ID==''){
                        $('#GlobalModal').modal('hide');
                    }

                } else {
                    toastr.error(jsonResult.Message,'Error');

                    var dataC = '';
                    $.each(jsonResult.Details,function (i,v) {
                        var viewRange = moment(v.RangeStart).format('DD MMM YYYY')+' - '+
                                        moment(v.RangeEnd).format('DD MMM YYYY');
                        dataC = dataC+'<li>'+viewRange+'</li>';
                    });

                    $('#showAlertPD').html('<div class="alert alert-danger" role="alert"><b>Conflict with : </b><ul>'+dataC+'</ul></div>');
                }

            })


        }

    });

    $(document).on('click','.btnEditListEula',function () {

        var ID = $(this).attr('data-pdid');

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Data EULA</h4>');

        var htmlss = '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <div class="well">' +
            '            <div class="row">' +
            '                <div class="col-md-12">' +
            '                     <form autocomplete="off" >' +
            '                    <input class="form-control" id="formEULASearch" placeholder="Search eula by title...">' +
            '                    <input class="hide" value="'+ID+'" id="formEDID">' +
            '                       <p class="help-block">Type at least 4 characters</p>' +
            '                       </form>' +
            '                </div>' +
            '            </div>' +
            '            <div id="viewListEULASearch"></div>' +
            '        </div>' +
            '    </div>' +
            '</div>' +
            '<div id="showListEULA"></div>';

        $('#GlobalModal .modal-body').html(htmlss);

        getListEULAInPD();

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');


        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

    $(document).on('keyup','#formEULASearch',function () {
       var key = $('#formEULASearch').val();
       if(key.length>=4){
           searchEDID();
       }
    });

    function searchEDID(){
        var key = $('#formEULASearch').val();
        var EDID = $('#formEDID').val();

        var data = {
            action : 'getEULASearch',
            EDID : EDID,
            key : key
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudEula';

        // $('#viewListEULASearch').html('');
        loading_page_simple('#viewListEULASearch','left');

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                var dataEULA = '';
                $.each(jsonResult,function (i,v) {
                    var btnAct = '<button data-id="'+v.ID+'" class="btn btn-sm btn-success btnACTAdd">Add</button>';
                    dataEULA = dataEULA+'<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.Title+'</td>' +
                        '<td>'+btnAct+'</td>' +
                        '</tr>';
                });

                setTimeout(function () {
                    $('#viewListEULASearch').html('<table class="table table-bordered table-centre">' +
                        '    <thead>' +
                        '    <tr>' +
                        '        <th style="width: 1%;">No</th>' +
                        '        <th>Title</th>' +
                        '        <th style="width: 10%;">Action</th>' +
                        '    </tr>' +
                        '    </thead>' +
                        '    <tbody>'+dataEULA+'</tbody>' +
                        '</table>');
                },500);

            } else {
                setTimeout(function () {
                    $('#viewListEULASearch').html('<div>-- No data --</div>');
                },500);
            }

        });
    }

    $(document).on('click','.btnACTAdd',function () {
        var EID = $(this).attr('data-id');
        var EDID = $('#formEDID').val();

        var data = {
            action : 'addTOListPD',
            EID : EID,
            EDID : EDID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudEula';

        $.post(url,{token:token},function (result) {
            toastr.success('Data saved','Success');
            searchEDID();
            getListEULAInPD();
        });


    });

    function getListEULAInPD() {

        var ID = $('#formEDID').val();
        var data = {
            action : 'getListEULAInPD',
            ID : ID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudEula';

        $('#showListEULA').html('');

        $.post(url,{token:token},function (jsonResult) {

            $('#viewDetailEULA_'+ID).html(jsonResult.length);

            if(jsonResult.length>0){
                var ls = '';
                $.each(jsonResult,function (i,v) {
                    ls = ls+'<li  data-id="'+v.ID+'">'+v.Title+' | <a href="javascript:void(0);" class="btnRemoveFromLE" data-id="'+v.ID+'">Remove</a></li>';
                });

                $('#showListEULA').html('<div class="row">' +
                    '    <div class="col-md-12">' +
                    '           <hr/>' +
                    '        <ol id="sortEULA">'+ls+'</ol>' +
                    '        <div style="text-align: right;">' +
                    '           <textarea class="hide" id="dataQueueEula"></textarea>' +
                    '           <button class="btn btn-sm btn-success" id="btnUpdateQueue">Update queue</button>' +
                    '        </div>' +
                    '    </div>' +
                    '</div>');

                $('#sortEULA').sortable({
                    axis: 'y',
                    update: function (event, ui) {
                        var dataUpdate = [];
                        $('#sortEULA li').each(function () {
                            dataUpdate.push($(this).attr('data-id'));
                        });

                        $('#dataQueueEula').val(JSON.stringify(dataUpdate));

                    }
                });

            }

        });
    }

    $(document).on('click','.btnRemoveFromLE',function () {

        if(confirm('Are you sure?')){
            var ID = $(this).attr('data-id');
            var data = {
                action : 'removeFromListEULAInPD',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api4/__crudEula';

            $.post(url,{token:token},function (result) {
                getListEULAInPD();
                var key = $('#formEULASearch').val();
                if(key.length>=4){
                    searchEDID();
                }
            });

        }

    });

    $(document).on('click','#btnUpdateQueue',function () {
        var dataQueueEula = $('#dataQueueEula').val();
        var d = JSON.parse(dataQueueEula);
        // var EDID = $('#formEDID').val();

        var data = {
            action : 'updateQueueEula',
            dataForm : d
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudEula';

        $.post(url,{token:token},function () {
            toastr.success('Queue saved','Success');
        });

    });

    $(document).on('click','.viewDetailEULA',function () {

        var ID = $(this).attr('data-edid');

        var data = {
            action : 'getDetailListEULA',
            EDID : ID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudEula';

        $.post(url,{token:token},function (jsonResult) {

            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Details Publication EULA</h4>');

            var listb = '';
            $.each(jsonResult,function (i,v) {
                listb = listb + '<tr>' +
                    '<td>'+(i+1)+'</td>' +
                    '<td style="text-align: left;">'+v.Title+'</td>' +
                    '<td><a>'+v.TotalUser+'</a></td>' +
                    '</tr>';
            });

            var htmlss = '<input value="'+ID+'" id="viewEDID" class="hide" />' +
                '<div class="row">' +
                '    <div class="col-md-12">' +
                '        <table class="table table-bordered table-centre">' +
                '            <thead>' +
                '            <tr>' +
                '                <th style="width: 3%;">No</th>' +
                '                <th>EULA</th>' +
                '                <th style="width: 13%;">Read</th>' +
                '            </tr>' +
                '            </thead>' +
                '            <tbody>'+listb+'</tbody>' +
                '        </table>' +
                '    </div>' +
                '</div>';

            $('#GlobalModal .modal-body').html(htmlss);

            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });

        });



    });
    
    function eula_detailsListEULA() {

        var viewEDID = $('#viewEDID').val();
        
    }

</script>