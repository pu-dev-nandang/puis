

<style>
    #tableViewLemabagaSurview tr th, #tableViewLemabagaSurview tr td {
        text-align: center;
    }

    #tableData tr th, #tableData tr td {
        text-align: center;
    }

</style>


<div class="well">
    <div class="row">

        <div class="col-md-3 form-data-edit" style="border-right: 1px solid #CCCCCC;" id = "inputForm">

            <div style="text-align: right;">
                <button class="btn btn-success" id="btnLembagaSurview"><i class="fa fa-cog margin-right"></i> Lembaga Akreditasi</button>
            </div>

            <div>
                <input class="hide" id="formEAID">
                <div class="form-group">
                    <label>Nama Lembaga</label>
                    <input class="hide" id="formAE_ID" />
                    <select class="form-control" id="formAE_LembagaID"></select>
                </div>
                <div class="form-group">
                    <label>Jenis Sertifikat / Akreditasi</label>
                    <input class="form-control" id="formAE_Type" />
                </div>
                <div class="form-group">
                    <label>Lingkup</label>
                    <select class="form-control" id="formAE_Scope">
                        <option value="PT">PT</option>
                        <option value="Fakultas">Fakultas</option>
                        <option value="Program Studi">Program Studi</option>
                        <option value="Unit">Unit</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tingkat</label>
                    <select class="form-control" id="formAE_Level">
                        <option value="Nasional">Nasional</option>
                        <option value="Internasional">Internasional</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Masa Berlaku</label>
                    <input class="form-control" id="formAE_DueDate" />
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea class="form-control" id="formAE_Description" rows="3"></textarea>
                </div>
                <div class="form-group" style="text-align: right;">
                    <button class="btn btn-primary" id="saveFormEA"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>
                </div>
            </div>
        </div>
        <div class="col-md-9" id = "ViewData">
            <div style="text-align: right;"> <button class="btn btn-success" id="btndownloaadExcel" title="Dowload Excel"><i class="fa fa-file-excel-o margin-right"></i> Excel </button></div> <p></p>
            <div style="min-height: 30px;" id="viewData" class="table-responsive"></div>
        </div>
  </div>
</div>


<script>
    $(document).ready(function () {
        var firstLoad = setInterval(function () {
            if(WaitForLoading == 1 ){
               window.act = "<?= $accessUser; ?>";
               if(parseInt(act)<=0){
                   $('.form-data-edit').remove();
               } else {
                   loadDataLembagaSurview();
                   $( "#formAE_DueDate" )
                       .datepicker({
                           showOtherMonths:true,
                           autoSize: true,
                           dateFormat: 'dd MM yy',
                           // minDate: new Date(moment().year(),moment().month(),moment().date()),
                           onSelect : function () {
                               // var data_date = $(this).val().split(' ');
                               // var nextelement = $(this).attr('nextelement');
                               // nextDatePick(data_date,nextelement);
                           }
                       });
               }
                loadDataTable();
                clearInterval(firstLoad);
            }

        },1000);
        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);

    });

    $("#btndownloaadExcel").click(function(){

        var akred = "0";
        var url = base_url_js+'agregator/excel_akreditasi_eksternal';
        data = {
          akred : akred
        }
        var token = jwt_encode(data,"UAP)(*");
        FormSubmitAuto(url, 'POST', [
            { name: 'token', value: token },
        ]);
    })

    $('#btnLembagaSurview').click(function () {

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Lembaga Survei </h4>');

        var body = '<div class="row">' +
            '    <div class="col-md-5">' +
            '        <div class="well">' +
            '            <div class="form-group">' +
            '                <input class="hide" id="formID">' +
            '                <input class="form-control" id="formLembaga" placeholder="Nama Lembaga...">' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <textarea class="form-control" id="formDescription" placeholder="Description..."></textarea>' +
            '            </div>' +
            '            <div style="text-align:right;">' +
            '                <button class="btn btn-success btn-round" id="btnSaveLembaga"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    ' +
            '    <div class="col-md-7">' +
            '        <table class="table table-bordered table-striped" id="tableViewLemabagaSurview">' +
            '            <thead>' +
            '            <tr style="background: #20485A;color: #FFFFFF;">' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Nama Lembaga</th>' +
            '                <th style="width: 2%;"><i class="fa fa-cog"></i></th>' +
            '            </tr>' +
            '            </thead>' +
            '           <tbody id="listLembaga"></tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';

        $('#GlobalModal .modal-body').html(body);

        loadDataLembagaSurview();

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-primary btn-round" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $('#btnSaveLembaga').click(function () {

            var formID = $('#formID').val();
            var formLembaga = $('#formLembaga').val();
            var formDescription = $('#formDescription').val();

            if(formLembaga!='' && formLembaga!=null &&
                formDescription!='' && formDescription!=null){

                loading_buttonSm('#btnSaveLembaga');

                var data = {
                    action : 'updateLembagaSurview' ,
                    ID : (formID!='' && formID!=null) ? formID : '',
                    Lembaga : formLembaga,
                    Description : formDescription,
                    Type : '0'
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api3/__crudLembagaSurview';

                $.post(url,{token:token},function (jsonResult) {

                    if(jsonResult==0 || jsonResult=='0') {
                        toastr.error('Maaf nama Lembaga sudah Ada!','Error');
                        $('#btnSaveLembaga').html('Save').prop('disabled',false);

                    } else {

                        $('#formID').val('');
                        $('#formLembaga').val('');
                        $('#formDescription').val('');
                        toastr.success('Data saved','Success');
                        loadDataLembagaSurview();

                        setTimeout(function () {
                            $('#btnSaveLembaga').html('Save').prop('disabled',false);
                        },500);
                    }
                });

            } else {
                toastr.warning('All form is required','Warning');
            }

        });

    });


    function loadDataLembagaSurview() {  //tabel master survei

        var url = base_url_js+'api3/__crudLembagaSurview';
        var token = jwt_encode({action : 'readLembagaSurview',Type:'0'},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            $('#listLembaga,#formAE_LembagaID').empty();

            if(jsonResult.length>0){

                $.each(jsonResult,function (i,v) {
                    var no = i+1;
                    $('#listLembaga').append('<tr>' +
                        '<td>'+no+'</td>' +
                        '<td style="text-align: left;"><b>'+v.Lembaga+'</b><br/>'+v.Description+'</td>' +
                        '<td style="text-align: left;"><div class="btn-group btnAction"> '+
                        '        <button type="button" class="btn btn-sm btn-default dropdown-toggle dropdown-menu-left" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '+
                        '            <i class="fa fa-pencil"></i> <span class="caret"></span> '+
                        '        </button>  '+
                        '        <ul class="dropdown-menu"> '+
                        '            <li><a class="btnEditLV_" data-no="'+v.ID+'" data-lembaga="'+v.Lembaga+'"  data-desc="'+v.Description+'"><i class="fa fa fa-edit"></i> Edit</a></li> '+
                        '            <li role="separator" class="divider"></li> '+
                        '            <li><a class="btnDeleteLV" data-no="'+v.ID+'"><i class="fa fa fa-trash"></i> Delete</a></li> '+
                        '        </ul> '+
                        '</div> </td>' +
                        '<textarea id="btnEditLV_'+no+'" class="hide">'+JSON.stringify(v)+'</textarea></td>' +
                        '</tr>');

                    $('#formAE_LembagaID').append('<option value="'+v.ID+'">'+v.Lembaga+'</option>');
                });

            } else {
                $('#listLembaga').append('<tr><td colspan="3">-- Tidak ada data --</td></tr>');
            }

        });

    }



    $(document).on('click','.btnEditLV_',function () {
       // alert('aa');

        var ID = $(this).attr('data-no');
        var Lembaga = $(this).attr('data-lembaga');
        var Description = $(this).attr('data-desc');
        //var dataForm = $('#btnEditLV_'+no).val();
        //var dataForm = JSON.parse(dataForm);

        $('#formID').val(ID);
        $('#formLembaga').val(Lembaga);
        $('#formDescription').val(Description);

    });

    $(document).on('click','.btnDeleteLV',function () {

        if(confirm('Yakin Hapus data?')) {

            $('.btnDeleteLV').prop('disabled',true);

            var no = $(this).attr('data-no');
            var url = base_url_js+'api3/__crudAgregatorTB1';

            var data = {
                action: 'removeDataMasterSurvey',
                ID : no
            };

            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (result) {

                $('#formID').val('');
                $('#formLembaga').val('');
                $('#formDescription').val('');

                toastr.success('Data saved','Success');
                loadDataLembagaSurview();

                setTimeout(function () {
                    //loadDataTable();
                },500);

           });
        }
    });

    $(document).on('click','.btnRemove',function () {

        if(confirm('Yakin Hapus data?')) {

            $('.btnDeleteLV').prop('disabled',true);

            var no = $(this).attr('data-id');
            var url = base_url_js+'api3/__crudAgregatorTB1';

            var data = {
                action: 'removeAkreditasi_eks',
                ID : no
            };

            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (result) {

                toastr.success('Data removed','Success');
                loadDataTable();

                $('#formAE_ID,#formAE_DueDate').val('');
                $('#formAE_LembagaID').val('');
                $('#formAE_Type').val('');
                $('#formAE_Scope').val('');
                $('#formAE_Level').val('');
                $('#formAE_Description').val('');

                setTimeout(function () {
                    //loadDataTable();
                },500);

           });
        }
    });


    // Save Form
    $('#saveFormEA').click(function () {

        var formAE_ID = $('#formAE_ID').val();
        var formAE_LembagaID = $('#formAE_LembagaID').val();
        var formAE_Type = $('#formAE_Type').val();
        var formAE_Scope = $('#formAE_Scope').val();
        var formAE_Level = $('#formAE_Level').val();
        var formAE_DueDate = $('#formAE_DueDate').datepicker("getDate");
        var formAE_Description = $('#formAE_Description').val();

        // ($('#bpp_start').datepicker("getDate")!=null) ? moment($('#bpp_start').datepicker("getDate")).format('YYYY-MM-DD') : '',

        if(formAE_LembagaID!='' && formAE_LembagaID!=null &&
            formAE_Type!='' && formAE_Type!=null &&
        formAE_Scope!='' && formAE_Scope!=null &&
        formAE_Level!='' && formAE_Level!=null &&
        formAE_DueDate!='' && formAE_DueDate!=null &&
        formAE_Description!='' && formAE_Description!=null){

            loading_button('#saveFormEA');

            var url = base_url_js+'api3/__crudExternalAccreditation';
            var data = {
                action: 'updateNewAE',
                ID : (formAE_ID!='' && formAE_ID!=null) ? formAE_ID : '',
                dataForm : {
                    LembagaID : formAE_LembagaID,
                    Type : formAE_Type,
                    Scope : formAE_Scope,
                    Level : formAE_Level,
                    DueDate :  moment(formAE_DueDate).format('YYYY-MM-DD'),
                    Description : formAE_Description,
                    EntredBy : sessionNIP
                }
            };

            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (result) {

                toastr.success('Data saved','Success');

                // var formAE_DueDate = $('#formAE_DueDate').datepicker("getDate");

                loadDataTable();

                $('#formAE_ID,#formAE_DueDate').val('');
                $('#formAE_LembagaID').val('');
                $('#formAE_Type').val('');
                $('#formAE_Scope').val('');
                $('#formAE_Level').val('');
                $('#formAE_Description').val('');

                setTimeout(function () {
                    $('#saveFormEA').html('Save').prop('disabled',false);
                },500);

            });

        } else {
            toastr.warning('Please, Fill in form requered','Warning');
        }

    });


    // Get Table
    function loadDataTable() {

        $('#viewData').html('<table class="table table-bordered table-striped table-responsive" id="tableData">' +
            '                    <thead>' +
            '                    <tr style="background: #20485A;color: #FFFFFF;">' +
            '                        <th style="width: 1%">No</th>' +
            '                        <th style="width: 15%">Lembaga</th>' +
            '                        <th style="width: 5%">Jenis Sertifikat</th>' +
            '                        <th style="width: 10%">Lingkup</th>' +
            '                        <th style="width: 15%">Tingkat</th>' +
            '                        <th style="width: 15%">Masa Berlaku</th>' +
            '                        <th style="width: 5%"><i class="fa fa-cog"></i></th>' +
            '                        <th>Keterangan</th>' +
            '                    </tr>' +
            '                    </thead>' +
            '                   <tbody id="listData"></tbody>' +
            '                </table>');


        var token = jwt_encode({action:'viewListAE',Previlege:act},'UAP)(*');

        var dataTable = $('#tableData').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Lembaga, Sertifikat, Lingkup, Tingkat"
            },
            "ajax":{
                url : base_url_js+"api3/__crudExternalAccreditation/", // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        });

    }

    $(document).on('click','.btnEditAE',function () {

        var no = $(this).attr('data-no');
        var dataDetail = $('#viewDetail_'+no).val();
        dataDetail = JSON.parse(dataDetail);


        $('#formAE_ID').val(dataDetail.ID);
        $('#formAE_LembagaID').val(dataDetail.LembagaID);
        $('#formAE_Type').val(dataDetail.Type);
        $('#formAE_Scope').val(dataDetail.Scope);
        $('#formAE_Level').val(dataDetail.Level);
        $('#formAE_Description').val(dataDetail.Description);

        (dataDetail.DueDate!=='0000-00-00' && dataDetail.DueDate!==null)
            ? $('#formAE_DueDate').datepicker('setDate',new Date(dataDetail.DueDate))
            : '';

    });

    // $(document).on('click','.btnRemove',function () {
    //
    //    if(confirm('Hapus data?')){
    //
    //        $('.btnAction').prop('disabled',true);
    //
    //        var ID = $(this).attr('data-id');
    //        var table = $(this).attr('data-tb');
    //
    //        var url = base_url_js+'api3/__crudAgregatorTB1';
    //
    //        var data = {
    //            action: 'rmeoveDataAgg',
    //            ID : ID,
    //            table : table
    //        };
    //
    //        var token = jwt_encode(data,'UAP)(*');
    //
    //        $.post(url,{token:token},function (result) {
    //
    //            toastr.success('Data removed','Success');
    //            setTimeout(function () {
    //                loadDataTable();
    //            },500);
    //
    //        });
    //    }
    //
    //
    // });

</script>
