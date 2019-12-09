
<style>
    h3.header-blue{
        margin-top: 0px;
        border-left: 7px solid #2196F3;
        padding-left: 10px;
        font-weight: bold;
    }

    #tableTransfer tr th {
        text-align: center;
        background: #607d8b;
        color: #FFFFFF;
    }

    .btnNote {
        padding: 1px 5px 1px 5px;
    }

    .popover-title {
        padding: 8px 10px;
        font-size: 11px;
    }
</style>

<div class="row">
    <div class="col-md-3">
        <div class="thumbnail">
            <div class="row" style="min-height: 100px;">
                <div class="col-md-12">
                    <div style="padding: 15px;">
                        <h3 class="header-blue">Create NIM</h3>
                        <div style="background: lightyellow; border: 1px solid #ccc;padding: 15px;color: #f44336;margin-bottom: 20px;">
                            <b>Semua data</b> student lama akan <b>diduplikasi</b> ke NIM baru dan status NIM lama menjadi <b>"Pindah Prodi"</b>, status NIM baru <b>"Aktif"</b>
                        </div>
                    </div>


                    <div class="well">
                        <div style="text-align: center;">
                            <h4 style="margin-top: 0px;">From :</h4>
                        </div>
                        <div class="form-group">
                            <label>Class Of</label>
                            <select class="form-control" id="fromClassOf"></select>
                        </div>
                        <div class="form-group">
                            <label>Programme Study</label>
                            <select class="form-control" id="fromProdi"></select>
                        </div>
                        <div class="form-group">
                            <label>Select Student</label>
                            <div id="showStd">-</div>
                        </div>
                    </div>

                    <div class="well">
                        <div style="text-align: center;">
                            <h4 style="margin-top: 0px;">To :</h4>
                        </div>
                        <div class="form-group">
                            <label>Class Of</label>
                            <select class="form-control" id="toClassOf"></select>
                        </div>
                        <div class="form-group">
                            <label>Programme Study</label>
                            <select class="form-control" id="toProdi"></select>
                            <p style="color: #009688;">Last NIM : <b id="lastNIM">-</b></p>
                        </div>
                        <div class="form-group">
                            <label>New NIM</label>
                            <input class="form-control" id="toNewNPM"/>
                            <span id="viewStatusNewNPM" style="float: right;"></span>
                            <input class="hide" id="statusNewNPM" value="0"/>
                        </div>

                        <hr/>


                        <div class="form-group">
                            <label>Biaya sesuaikan dengan</label>
                            <select class="form-control" id="toReason"></select>
                        </div>

                        <div class="hide" id="otherProdi">
                            <div class="form-group">
                                <label>Prodi</label>
                                <select class="form-control" id="formPayemntProdiID"></select>
                            </div>
                            <div class="form_group">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label>Class Of</label>
                                        <select class="form-control" id="formPayemntClassOf"></select>
                                    </div>
                                    <div class="col-xs-6">
                                        <label>Bintang</label>
                                        <select class="form-control" id="formPayemntBintang"></select>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div style="padding: 5px;">
                        <button class="btn btn-block btn-success" id="btnCreateNPM">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-9">
        <div class="thumbnail" style="padding: 15px;">
<!--            <h3 class="header-blue">Course Conversion</h3>-->

            <h3 class="header-blue">List Transfer Student</h3>

            <div class="row">
                <div class="col-md-12">
                    <div id="viewTable"></div>
                </div>
            </div>


        </div>
    </div>
</div>



<script>
    $(document).ready(function () {


        getListStudentTransfer();

        loadSelectOptionClassOf_ASC('#fromClassOf','');
        loadSelectOptionBaseProdi('#fromProdi','');
        loadSelectOptionBaseProdi('#formPayemntProdiID','');

        loadSelectOptionClassOf_ASC('#toClassOf','');
        loadSelectOptionBaseProdi('#toProdi','');
        loadSelectOptionReasonTransferStudent('#toReason','');

        $('#filterStudent').select2();

        var firsLoad = setInterval(function () {
            var fromClassOf = $('#fromClassOf').val();
            var fromProdi = $('#fromProdi').val();
            if(fromClassOf != '' && fromClassOf!=null &&
                fromProdi != '' && fromProdi!=null){
                loadFromStudent();
                clearInterval(firsLoad);
            }
        },1000);

        var loadlastNIM = setInterval(function () {
            var toClassOf = $('#toClassOf').val();
            var toProdi = $('#toProdi').val();

            if(toClassOf != '' && toClassOf != null &&
                toProdi != '' && toProdi != null){
                loadLastNIM();
                clearInterval(loadlastNIM);
            }

        },1000);

    });

    $('#toClassOf, #toProdi').change(function () {
        // Load Last NIM
        loadLastNIM();
    });

    function loadLastNIM() {
        var toProdi = $('#toProdi').val();
        var toClassOf = $('#toClassOf').val();

        if(toProdi!='' && toProdi!=null && toClassOf!='' && toClassOf!=null){

            var ProdiID = toProdi.split('.')[0];
            var ClassOf = toClassOf.split('.')[1];
            var url = base_url_js+'api/__crudTransferStudent';
            var token = jwt_encode({action : 'getLastNIMTransferStudent', ProdiID : ProdiID, ClassOf : ClassOf},'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {
                $('#lastNIM').html('-');
               if(jsonResult.length>0){
                   $('#lastNIM').html(jsonResult[0].NPM);
               }
            });

        }


    }

    $('#toNewNPM').keyup(function () {
        var toNewNPM = $('#toNewNPM').val();
        if(toNewNPM!='' && toNewNPM!=null){
            checkNPMTransferStudent();
        }
    });

    function checkNPMTransferStudent() {
        var toNewNPM = $('#toNewNPM').val();
        if(toNewNPM!='' && toNewNPM!=null){
            var url = base_url_js+'api/__crudTransferStudent';
            var token = jwt_encode({action : 'checkNPMTransferStudent', NPM : toNewNPM},'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {

                $('#statusNewNPM').val(0);
                $('#viewStatusNewNPM').html('<span style="color: red;">NPM can\'t use</span>');
                if(jsonResult.Status==1 || jsonResult.Status=='1'){
                    $('#statusNewNPM').val(1);
                    $('#viewStatusNewNPM').html('<span style="color: green;">NPM can use</span>');
                }
            });

        }
    }

    $('#fromClassOf,#fromProdi').change(function () {
        var fromClassOf = $('#fromClassOf').val();
        var fromProdi = $('#fromProdi').val();
        if(fromClassOf != '' && fromClassOf!=null &&
        fromProdi != '' && fromProdi!=null){
            loadFromStudent();
        }
    });

    function loadFromStudent() {

        var fromClassOf = $('#fromClassOf').val();
        var fromProdi = $('#fromProdi').val();
        var elSt = $('#showStd');
        if(fromClassOf != '' && fromClassOf!=null &&
            fromProdi != '' && fromProdi!=null){

            var url = base_url_js+'api/__crudTransferStudent';
            var ClassOf = fromClassOf.split('.')[1];
            var ProdiID = fromProdi.split('.')[0];
            var token = jwt_encode({action : 'readFromStudentTransfer', ClassOf : ClassOf, ProdiID : ProdiID},'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

                elSt.html('<select class="select2-select-00 full-width-fix form-jadwal"' +
                    '                                    size="5" id="fromStudent"><option></option></select>');

                if(jsonResult.length>0){
                    for(var i=0;i<jsonResult.length; i++){
                        var d = jsonResult[i];
                        $('#fromStudent').append('<option value="'+d.NPM+'">'+d.NPM+' - '+ucwords(d.Name)+'</option>');
                    }
                }

                $('#fromStudent').select2({allowClear: true});

            });

        } else {
            elSt.html('-');
        }

    }

    $('#toReason').change(function () {

        var toReason = $('#toReason').val();

        $('#otherProdi').addClass('hide');

        if(toReason==2 || toReason=='2'){
            $('#otherProdi').removeClass('hide');
            loadNewClassOf();
        }

    });

    $(document).on('change','#formPayemntProdiID',function () {
        var formPayemntProdiID = $('#formPayemntProdiID').val();
        // Read Class Of
        if(formPayemntProdiID!='' && formPayemntProdiID!=null){
            loadNewClassOf();
        }
    });

    function loadNewClassOf() {
        var formPayemntProdiID = $('#formPayemntProdiID').val();
        // Read Class Of
        if(formPayemntProdiID!='' && formPayemntProdiID!=null){
            var data = {
                action : 'readClassOfTransferStd',
                ProdiID : formPayemntProdiID.split('.')[0]
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudTransferStudent';
            $.post(url,{token:token},function (jsonResult) {

                $('#formPayemntClassOf').empty();
                if(jsonResult.length>0){
                    for(var i=0;i<jsonResult.length;i++){
                        $('#formPayemntClassOf').append('<option value="'+jsonResult[i].ClassOf+'">'+jsonResult[i].ClassOf+'</option>');
                    }

                    loadBintang();
                }
            });
        }
    }

    $('#formPayemntClassOf').change(function () {
        loadBintang();
    });
    
    function loadBintang() {
        var formPayemntProdiID = $('#formPayemntProdiID').val();
        var formPayemntClassOf = $('#formPayemntClassOf').val();

        if(formPayemntProdiID!='' && formPayemntProdiID!=null &&
            formPayemntClassOf!='' && formPayemntClassOf!=null){

            var data = {
                action : 'readBintangTransferStd',
                ProdiID : formPayemntProdiID.split('.')[0],
                ClassOf : formPayemntClassOf
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudTransferStudent';

            $.post(url,{token:token},function (jsonResult) {

                $('#formPayemntBintang').empty();
                if(jsonResult.length>0){
                    for(var i=0;i<jsonResult.length;i++){
                        $('#formPayemntBintang').append('<option value="'+jsonResult[i].Pay_Cond+'">'+jsonResult[i].Pay_Cond+'</option>');
                    }
                }
            });

        }
    }

    $('#btnCreateNPM').click(function () {
        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Create NIM ?</b><hr/> ' +
            '<button type="button" class="btn btn-success" id="btnActionCreateNPM" style="margin-right: 5px;">Yes</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
            '</div>');
        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });
    });

    $(document).on('click','#btnActionCreateNPM',function () {
        var fromClassOf = $('#fromClassOf').val();
        var fromProdi = $('#fromProdi').val();
        var fromStudent = $('#fromStudent').val();

        var toClassOf = $('#toClassOf').val();
        var toProdi = $('#toProdi').val();
        var toNewNPM = $('#toNewNPM').val();
        var toReason = $('#toReason').val();

        if(fromClassOf!='' && fromClassOf!=null &&
            fromProdi!='' && fromProdi!=null &&
            fromStudent!='' && fromStudent!=null &&
            toClassOf!='' && toClassOf!=null &&
            toProdi!='' && toProdi!=null &&
            toNewNPM!='' && toNewNPM!=null &&
            toReason!='' && toReason!=null){

            var statusNewNPM = $('#statusNewNPM').val();
            if(statusNewNPM==1 || statusNewNPM=='1'){



                loading_button('#btnCreateNPM');
                loading_buttonSm('#btnActionCreateNPM');
                $('button[data-dismiss=modal]').prop('disabled',true);

                var formPayemntProdiID = $('#formPayemntProdiID').val();
                var formPayemntClassOf = $('#formPayemntClassOf').val();
                var formPayemntBintang = $('#formPayemntBintang').val();

                var ProdiID_f = fromProdi.split('.')[0];
                var ClassOf_f = fromClassOf.split('.')[1];

                var ProdiID_t = toProdi.split('.')[0];
                var ClassOf_t = toClassOf.split('.')[1];

                var data = {
                    action : 'addingTransferStudent',
                    fromClassOf : ClassOf_f,
                    fromProdi : ProdiID_f,
                    fromStudent : fromStudent,
                    toClassOf : ClassOf_t,
                    toProdi : ProdiID_t,
                    toNewNPM : toNewNPM,
                    TransferTypeID : toReason,
                    PaymentProdiID : (formPayemntProdiID!='' && formPayemntProdiID!=null)
                        ? formPayemntProdiID.split('.')[0] : '',
                    PaymentClassOf : formPayemntClassOf,
                    PaymentBintang : formPayemntBintang,
                    CreateAt : dateTimeNow(),
                    CreateBy : sessionNIP
                };

                var token = jwt_encode(data,'UAP)(*');

                var url = base_url_js+'api/__crudTransferStudent';
                $.post(url,{token:token},function (jsonResult) {

                    toastr.success('Create NIM','Success');
                    $('#NotificationModal').modal('hide');
                    setTimeout(function () {
                        window.location.href = '';
                    },500);
                });


            } else {
                toastr.warning('NIM canot to use','Warning');
            }

        } else {
            toastr.error('All Form Required','Error');
        }
    });



    // LIST TRANSFER STUDENT
    function getListStudentTransfer() {

        $('#viewTable').html('<table class="table table-bordered table-striped" id="tableTransfer">' +
            '                        <thead>' +
            '                        <tr>' +
            '                            <th style="width: 1%;" rowspan="2">No</th>' +
            '                            <th style="width: 15%;" rowspan="2">Name</th>' +
            '                            <th style="background: #985a55;" colspan="3">From</th>' +
            '                            <th rowspan="2" style="width: 7%;">Action</th>' +
            '                            <th colspan="3" style="background: #608862;">To</th>' +
            '                        </tr>' +
            '                        <tr>' +
            '                            <th style="background: #985a55;width: 7%;">NIM</th>' +
            '                            <th style="background: #985a55;width: 7%;">Class Of</th>' +
            '                            <th style="background: #985a55;">Prodi</th>' +
            '                            <th style="width: 7%;background: #608862;">NIM</th>' +
            '                            <th style="width: 7%;background: #608862;">Class Of</th>' +
            '                            <th style="background: #608862;">Prodi</th>' +
            '                        </tr>' +
            '                        </thead>' +
            '                    </table>');


        var dataTable = $('#tableTransfer').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "NIM, Name, Programme Study"
            },
            "ajax":{
                url : base_url_js+'academic/transfer-student/__loadListTransferStudent', // json datasource
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );

        setTimeout(function (args) {
            $('[data-toggle="popover"]').popover();
            $('.btnNote').prop('disabled',false);
            $('.btnNote').html('View Note');
        },3000);
    }

    $(document).on('click','.btnRemoveData',function () {

        var ID = $(this).attr('data-id');

        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Remove data ?</b><hr/> ' +
            '<button type="button" class="btn btn-primary" id="btnActRemoveTransferStd" data-id="'+ID+'" style="margin-right: 5px;">Yes</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
            '</div>');
        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });

    });

    $(document).on('click','#btnActRemoveTransferStd',function () {

        loading_buttonSm('#btnActRemoveTransferStd');
        $('button[data-dismiss=modal]').prop('disabled',true);

        var ID = $(this).attr('data-id');
        var url = base_url_js+'api/__crudTransferStudent';
        var token = jwt_encode({action : 'removeTransverStudent', ID : ID},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            toastr.success('Remove Student Transfer','Success');
            getListStudentTransfer();
            setTimeout(function () {
                $('#NotificationModal').modal('hide');
            },500);
        });
    });

    $(document).on('click','.showModalNote',function () {

        var Name = $(this).attr('data-name');
        var Note = $(this).attr('data-note');
        var TSID = $(this).attr('data-id');

        $('#GlobalModalSmall .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Note for <span style="color: royalblue;">'+Name+'</span></h4>');

        var htmlss = '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <div class="form-group">' +
            '            <label>Note</label>' +
            '            <textarea class="form-control" id="formNote" rows="5">'+Note+'</textarea>' +
            '        </div>' +
            '    </div>' +
            '</div>';

        $('#GlobalModalSmall .modal-body').html(htmlss);

        $('#GlobalModalSmall .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> ' +
            '<button type="button" class="btn btn-success" id="btnSaveNote">Save</button>');

        $('#GlobalModalSmall').on('shown.bs.modal', function () {
            $('#formNote').focus();
        });

        $('#GlobalModalSmall').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $('#btnSaveNote').click(function () {

            var formNote = $('#formNote').val();

            if(formNote!='' && formNote!=null){

                loading_buttonSm('#btnSaveNote');

                var data ={
                    action : 'addNoteInTransferStd',
                    TSID : TSID,
                    dataUpdate : {
                        Note : formNote,
                        NotedBy : sessionNIP,
                        NotedAt : dateTimeNow()
                    }
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api/__crudTransferStudent';

                $.post(url,{token:token},function (result) {

                    getListStudentTransfer();

                    toastr.success('Data saved','Success');
                    setTimeout(function () {
                        $('#btnSaveNote').prop('disabled',false).html('Save');
                    },500);

                });

            } else {
                toastr.warning('Form note is required','Warning');
            }


        });



    });

</script>