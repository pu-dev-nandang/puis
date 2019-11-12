

<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }
</style>

<div class="well">
    <div class="row">

        <div class="col-md-3 form-data-edit" style="border-right: 1px solid #CCCCCC;" id = "inputForm">

          <div style="text-align: right;">
              <button class="btn btn-success" id="btnLembagaSurview"><i class="fa fa-cog margin-right"></i> Lembaga Akreditasi Internasional</button>
          </div>
            <div class="form-group">
                <label>Lembaga</label>
                <input class="hide" id="formID" />
                <select class="form-control" id="formLembagaID"></select>
            </div>
            <div class="form-group">
                <label>Program Studi</label>
                <select class="form-control" id="formProdiID"></select>
            </div>
            <div class="form-group">
                <label>Status / Peringkat</label>
                <input class="form-control" id="formStatus">
            </div>
            <div class="form-group">
                <label>Masa berlaku</label>
                <input class="form-control" id="formDueDate" />
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" id="formDescription" rows="3"></textarea>
            </div>
            <div class="form-group" style="text-align: right;">
                <button class="btn btn-primary" id="saveForm"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>
            </div>
        </div>

        <div class="col-md-9" id = "ViewData">
            <div style="text-align: right;"><button class="btn btn-success" id="btndownloaadExcel" title="Dowload Excel"><i class="fa fa-file-excel-o margin-right"></i> Excel </button></div> <p></p>
            <div style="min-height: 30px;" id="viewData" class="table-responsive"></div>

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
                    // loadSelectOptionLembaga('#formLembagaID','');
                    loadDataLembagaSurview();
                    loadSelectOptionBaseProdi('#formProdiID','');
                    $( "#formDueDate" )
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
        var url = base_url_js+'agregator/excel-akreditasi-international';
        data = {
          akred : akred
        }
        var token = jwt_encode(data,"UAP)(*");
        FormSubmitAuto(url, 'POST', [
            { name: 'token', value: token },
        ]);
    })

    $('#saveForm').click(function () {

        var formID = $('#formID').val();
        var formLembagaID = $('#formLembagaID').val();
        var formProdiID = $('#formProdiID').val();
        var formStatus = $('#formStatus').val();
        var formDueDate = $('#formDueDate').datepicker("getDate");
        var formDescription = $('#formDescription').val();

        if(formLembagaID !='' && formLembagaID!=null &&
            formProdiID !='' && formProdiID!=null &&
        formStatus !='' && formStatus!=null &&
        formDueDate !='' && formDueDate!=null &&
        formDescription !='' && formDescription!=null){

            loading_button('#saveForm');
            var url = base_url_js+'api3/__crudInternationalAccreditation';
            var data = {
                action : 'updateIAP',
                ID : formID,
                dataForm : {
                    LembagaID : formLembagaID,
                    ProdiID : formProdiID,
                    Status : formStatus,
                    DueDate : moment(formDueDate).format('YYYY-MM-DD'),
                    Description : formDescription,
                    EntredBy : sessionNIP
                }
            };

            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (result) {

                $('#formID').val('');
                $('#formLembagaID').val('');
                $('#formProdiID').val('');
                $('#formStatus').val('');
                $('#formDueDate').val('');
                $('#formDescription').val('');

                toastr.success('Data saved','Success');

                loadDataTable();

                setTimeout(function () {
                    $('#saveForm').html('Save').prop('disabled',false);
                },500);

            });

        }

    });

    function loadDataTable() {

        $('#viewData').html('<table class="table table-striped table-bordered" id="tableData">' +
            '                    <thead>' +
            '                    <tr style="background: #20485A;color: #FFFFFF;">' +
            '                        <th style="width: 1%;">No</th>' +
            '                        <th style="width: 15%;">Lembaga</th>' +
            '                        <th style="width: 15%;">Program Studi</th>' +
            '                        <th style="width: 10%;">Status/ Peringkat</th>' +
            '                        <th style="width: 15%;">Masa Berlaku</th>' +
            '                        <th style="width: 1%;"><i class="fa fa-cog"></i></th>' +
            '                        <th>Keterangan</th>' +
            '                    </tr>' +
            '                    </thead>' +
            '                </table>');

        var token = jwt_encode({action:'viewListIA',Previlege:act},'UAP)(*');
        var url = base_url_js+'api3/__crudInternationalAccreditation';

        var dataTable = $('#tableData').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Lembaga, Sertifikat, Lingkup, Tingkat"
            },
            "ajax":{
                url : url, // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );

    }

    $(document).on('click','.btnEdit',function () {

        var no = $(this).attr('data-no');
        var dataDetail = $('#viewDetail_'+no).val();
        dataDetail = JSON.parse(dataDetail);


        $('#formID').val(dataDetail.ID);
        $('#formLembagaID').val(dataDetail.LembagaID);
        $('#formProdiID').val(dataDetail.ProdiID+'.'+dataDetail.ProdiCode);
        $('#formStatus').val(dataDetail.Status);
        $('#formDescription').val(dataDetail.Description);

        (dataDetail.DueDate!=='0000-00-00' && dataDetail.DueDate!==null)
            ? $('#formDueDate').datepicker('setDate',new Date(dataDetail.DueDate))
            : '';

    });


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
                    Type : '1'
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
        var token = jwt_encode({action : 'readLembagaSurview',Type:'1'},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            $('#listLembaga,#formLembagaID').empty();

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

                    $('#formLembagaID').append('<option value="'+v.ID+'">'+v.Lembaga+'</option>');
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



</script>
