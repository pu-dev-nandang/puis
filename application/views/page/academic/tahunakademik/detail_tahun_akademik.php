

<style>
    .tr-invers {
        background: #0f1f4bc4;
        color: #fff;
    }

    #tableDetailTA .form-control[readonly], .form-tahun-akademik[readonly] {
        cursor: cell;
        background-color: #fff;
        color: #333;
    }
</style>

<a href="<?php echo base_url('academic/tahun-akademik'); ?>" id="btnBack" class="btn btn-info"><i class="fa fa-arrow-circle-left right-margin" aria-hidden="true"></i> Back</a>
<button class="btn btn-success" id="btnSaveDetail">Save</button>
<hr/>

<div class="alert alert-info" role="alert">
    <b><span id="nameTahunAkademik"></span></b>
</div>


<!--<input id="formSemesterID" value="--><?php //echo $ID; ?><!--" readonly />-->
<table class="table table-bordered table-striped" id="tableDetailTA">
    <thead>
    <tr class="tr-invers">
        <th rowspan="2" class="th-center">Keterangan</th>
        <th colspan="2" class="th-center">
            Global Setting
        </th>
        <th rowspan="2" class="th-center">Action</th>
    </tr>
    <tr class="tr-invers">
        <th class="th-center">Start</th>
        <th class="th-center">End</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Bayar BPP</td>
        <td>
            <input type="text" id="bpp_start" nextElement="bpp_end" name="regular" class="form-control form-tahun-akademik" readonly>
        </td>
        <td>
            <input type="text" id="bpp_end" name="regular" class="form-control form-tahun-akademik form-next">
        </td>
        <td>
<!--            <a href="javascript:void(0);" data-head="KRS" data-load="prodi" class="btn btn-sm btn-warning btn-block more_details">Special Case</a>-->
            -
        </td>
    </tr>
    <tr>
        <td>Krs</td>
        <td>
            <input type="text" id="krs_start" nextElement="krs_end" name="regular" class="form-control form-tahun-akademik" readonly>
        </td>
        <td>
            <input type="text" id="krs_end" name="regular" class="form-control form-tahun-akademik form-next">
        </td>
        <td>
<!--            <a href="javascript:void(0);" data-head="KRS" data-load="prodi" class="btn btn-sm btn-warning btn-block more_details">Special Case</a>-->
            -
        </td>
    </tr>
    <tr>
        <td>Bayar SKS</td>
        <td>
            <input type="text" id="bayar_start" nextelement="bayar_end" name="regular" class="form-control form-tahun-akademik">
        </td>
        <td>
            <input type="text" id="bayar_end" name="regular" class="form-control form-tahun-akademik form-next">
        </td>
        <td>
<!--            <a href="javascript:void(0);" data-head="Bayar" data-load="prodi" class="btn btn-sm btn-warning btn-block more_details">Special Case</a>-->
            -
        </td>
    </tr>
    <tr>
        <td>Kuliah</td>
        <td>
            <input type="text" id="kuliah_start" nextelement="kuliah_end" name="regular" class="form-control form-tahun-akademik">
        </td>
        <td>
            <input type="text" id="kuliah_end" name="regular" class="form-control form-tahun-akademik form-next">
        </td>
        <td>
<!--            <a href="javascript:void(0);" data-head="Kuliah" data-load="prodi" class="btn btn-sm btn-warning btn-block more_details">Special Case</a>-->
            -
        </td>
    </tr>
    <tr>
        <td>UTS</td>
        <td>
            <input type="text" id="uts_start" nextelement="uts_end" name="regular" class="form-control form-tahun-akademik">
        </td>
        <td>
            <input type="text" id="uts_end" name="regular" class="form-control form-tahun-akademik form-next">
        </td>
        <td>
<!--            <a href="javascript:void(0);" data-head="UTS" data-load="prodi" class="btn btn-sm btn-warning btn-block more_details">Special Case</a>-->
            -
        </td>
    </tr>
    <tr>
        <td>Input Nilai UTS</td>
        <td>
            <input type="text" id="nilaiuts_start" nextelement="nilaiuts_end" name="regular" class="form-control form-tahun-akademik">
        </td>
        <td>
            <input type="text" id="nilaiuts_end" name="regular" class="form-control form-tahun-akademik form-next">
        </td>
        <td>
            <a href="javascript:void(0);" data-head="Input Nilai UTS" data-type="5.1" class="btn btn-sm btn-warning btn-block more_details">Special Case</a>
        </td>
    </tr>
    <tr>
        <td>Show Nilai UTS</td>
        <td>
            <input type="text" id="show_nilai_uts" nextelement="" name="regular" class="form-control form-tahun-akademik">
        </td>
        <td>

        </td>
        <td></td>
    </tr>
    <tr>
        <td>UAS</td>
        <td>
            <input type="text" id="uas_start" nextelement="uas_end" name="regular" class="form-control form-tahun-akademik">
        </td>
        <td>
            <input type="text" id="uas_end" name="regular" class="form-control form-tahun-akademik form-next">
        </td>
        <td>
            -
        </td>
    </tr>
    <tr>
        <td>Input Nilai UAS</td>
        <td>
            <input type="text" id="nilaiuas_start" nextelement="nilaiuas_end" name="regular" class="form-control form-tahun-akademik">
        </td>
        <td>
            <input type="text" id="nilaiuas_end" name="regular" class="form-control form-tahun-akademik form-next">
        </td>
        <td>
            <a href="javascript:void(0);" data-head="Input Nilai UAS" data-type="8.1" class="btn btn-sm btn-warning btn-block more_details">Special Case</a>
        </td>
    </tr>
    <tr>
        <td>Show Nilai UAS</td>
        <td>
            <input type="text" id="show_nilai_uas" nextelement="" name="regular" class="form-control form-tahun-akademik">
        </td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>Edom</td>
        <td>
            <input type="text" id="edom_start" nextelement="edom_end" name="regular" class="form-control form-tahun-akademik">
        </td>
        <td>
            <input type="text" id="edom_end" name="regular" class="form-control form-tahun-akademik form-next">
        </td>
        <td>
            -
        </td>
    </tr>
    </tbody>
</table>


<script>
    $(document).ready(function () {
        window.ID = '<?php echo $ID; ?>';
        loadData(ID);
        $('.form-tahun-akademik').prop('readonly',true);
        $( "#bpp_start,#krs_start ,#bayar_start,#kuliah_start,#edom_start," +
            "#uts_start,#show_nilai_uts,#nilaiuts_start," +
            "#uas_start,#nilaiuas_start,#show_nilai_uas" )
            .datepicker({
            showOtherMonths:true,
            autoSize: true,
            dateFormat: 'dd MM yy',
            // minDate: new Date(moment().year(),moment().month(),moment().date()),
            onSelect : function () {
                var data_date = $(this).val().split(' ');
                var nextelement = $(this).attr('nextelement')
                nextDatePick(data_date,nextelement);
            }
        });
    });

    $('#btnSaveDetail').click(function () {
        // var k = $('#krs_start').datepicker("getDate");
        // log(k);
        //
        // if(k==null){
        //     alert(123)
        // }
        //
        // return false;

        var data = {
            action : 'edit',
            SemesterID : ID,
            dataForm : {
                SemesterID : ID,
                bayarBPPStart : ($('#bpp_start').datepicker("getDate")!=null) ? moment($('#bpp_start').datepicker("getDate")).format('YYYY-MM-DD') : '',
                bayarBPPEnd : ($('#bpp_end').datepicker("getDate")!=null) ? moment($('#bpp_end').datepicker("getDate")).format('YYYY-MM-DD') : '',
                krsStart : ($('#krs_start').datepicker("getDate")!=null) ? moment($('#krs_start').datepicker("getDate")).format('YYYY-MM-DD') : '',
                krsEnd : ($('#krs_end').datepicker("getDate")!=null) ? moment($('#krs_end').datepicker("getDate")).format('YYYY-MM-DD') : '',
                bayarStart : ($('#bayar_start').datepicker("getDate")!=null) ? moment($('#bayar_start').datepicker("getDate")).format('YYYY-MM-DD') : '',
                bayarEnd : ($('#bayar_end').datepicker("getDate")!=null) ? moment($('#bayar_end').datepicker("getDate")).format('YYYY-MM-DD') : '',
                kuliahStart : ($('#kuliah_start').datepicker("getDate")!=null) ? moment($('#kuliah_start').datepicker("getDate")).format('YYYY-MM-DD') : '',
                kuliahEnd : ($('#kuliah_end').datepicker("getDate")!=null) ? moment($('#kuliah_end').datepicker("getDate")).format('YYYY-MM-DD') : '',
                utsStart : ($('#uts_start').datepicker("getDate")!=null) ? moment($('#uts_start').datepicker("getDate")).format('YYYY-MM-DD') : '',
                utsEnd : ($('#uts_end').datepicker("getDate")!=null) ? moment($('#uts_end').datepicker("getDate")).format('YYYY-MM-DD') : '',
                utsInputNilaiStart : ($('#nilaiuts_start').datepicker("getDate")!=null) ? moment($('#nilaiuts_start').datepicker("getDate")).format('YYYY-MM-DD') : '',
                utsInputNilaiEnd : ($('#nilaiuts_end').datepicker("getDate")!=null) ? moment($('#nilaiuts_end').datepicker("getDate")).format('YYYY-MM-DD') : '',
                showNilaiUts : ($('#show_nilai_uts').datepicker("getDate")!=null) ? moment($('#show_nilai_uts').datepicker("getDate")).format('YYYY-MM-DD') : '',
                uasStart : ($('#uas_start').datepicker("getDate")!=null) ? moment($('#uas_start').datepicker("getDate")).format('YYYY-MM-DD') : '',
                uasEnd : ($('#uas_end').datepicker("getDate")!=null) ? moment($('#uas_end').datepicker("getDate")).format('YYYY-MM-DD') : '',
                uasInputNilaiStart : ($('#nilaiuas_start').datepicker("getDate")!=null) ? moment($('#nilaiuas_start').datepicker("getDate")).format('YYYY-MM-DD') : '',
                uasInputNilaiEnd : ($('#nilaiuas_end').datepicker("getDate")!=null) ? moment($('#nilaiuas_end').datepicker("getDate")).format('YYYY-MM-DD') : '',
                showNilaiUas : ($('#show_nilai_uas').datepicker("getDate")!=null) ? moment($('#show_nilai_uas').datepicker("getDate")).format('YYYY-MM-DD') : '',
                edomStart : ($('#edom_start').datepicker("getDate")!=null) ? moment($('#edom_start').datepicker("getDate")).format('YYYY-MM-DD') : '',
                edomEnd : ($('#edom_end').datepicker("getDate")!=null) ? moment($('#edom_end').datepicker("getDate")).format('YYYY-MM-DD') : ''
            }
        };

        // console.log(data);
        // return false;

        loading_button('#btnSaveDetail');
        $('.form-tahun-akademik,#btnBack').prop('disabled',true);

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudDataDetailTahunAkademik';
        $.post(url,{token:token},function (data) {
            setTimeout(function () {
                $('#btnSaveDetail').prop('disabled',false).html('Save');
                $('.form-tahun-akademik,#btnBack').prop('disabled',false);
            },2000);
        });

        // console.log(data);

        // var krsStart = ;
        // log(krsStart);
        // log();

    });

    function loadData(ID) {
        var url = base_url_js+'api/__crudDataDetailTahunAkademik';
        var data = {
            action : 'read',
            ID : ID
        }
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token}, function (data) {
            // console.log(data);
            $('#nameTahunAkademik').html(data.TahunAkademik.Name);

            (data.DetailTA.bayarBPPStart!=='0000-00-00' && data.DetailTA.bayarBPPStart!==null) ? $('#bpp_start').datepicker('setDate',new Date(data.DetailTA.bayarBPPStart)) : '';
            (data.DetailTA.bayarBPPEnd!=='0000-00-00' && data.DetailTA.bayarBPPEnd!==null) ? $('#bpp_end').datepicker({showOtherMonths:true,autoSize: true,dateFormat: 'dd MM yy',
                minDate: new Date(data.DetailTA.bayarBPPStart)}).datepicker('setDate',new Date(data.DetailTA.bayarBPPEnd)) : '';

            (data.DetailTA.krsStart!=='0000-00-00' && data.DetailTA.krsStart!==null) ? $('#krs_start').datepicker('setDate',new Date(data.DetailTA.krsStart)) : '';
            (data.DetailTA.krsEnd!=='0000-00-00' && data.DetailTA.krsEnd!==null) ? $('#krs_end').datepicker({showOtherMonths:true,autoSize: true,dateFormat: 'dd MM yy',
                minDate: new Date(data.DetailTA.krsStart)}).datepicker('setDate',new Date(data.DetailTA.krsEnd)) : '';

            (data.DetailTA.bayarStart!=='0000-00-00' && data.DetailTA.bayarStart!==null) ? $('#bayar_start').datepicker('setDate',new Date(data.DetailTA.bayarStart)) : '';
            (data.DetailTA.bayarEnd !=='0000-00-00' && data.DetailTA.bayarEnd!==null) ? $('#bayar_end').datepicker({showOtherMonths:true,autoSize: true,dateFormat: 'dd MM yy',
                minDate: new Date(data.DetailTA.bayarStart)}).datepicker('setDate',new Date(data.DetailTA.bayarEnd)) : '';

            (data.DetailTA.kuliahStart !=='0000-00-00' && data.DetailTA.kuliahStart !== null) ? $('#kuliah_start').datepicker('setDate',new Date(data.DetailTA.kuliahStart)):'';
            (data.DetailTA.kuliahEnd !=='0000-00-00' && data.DetailTA.kuliahEnd!== null) ? $('#kuliah_end').datepicker({showOtherMonths:true,autoSize: true,dateFormat: 'dd MM yy',
                minDate: new Date(data.DetailTA.kuliahStart)}).datepicker('setDate',new Date(data.DetailTA.kuliahEnd)) : '';

            (data.DetailTA.utsStart !=='0000-00-00' && data.DetailTA.utsStart!==null) ? $('#uts_start').datepicker('setDate',new Date(data.DetailTA.utsStart)) : '';
            (data.DetailTA.utsEnd !=='0000-00-00' && data.DetailTA.utsEnd!==null) ? $('#uts_end').datepicker({showOtherMonths:true,autoSize: true,dateFormat: 'dd MM yy',
                minDate: new Date(data.DetailTA.utsStart)}).datepicker('setDate',new Date(data.DetailTA.utsEnd)) : '';

            (data.DetailTA.utsInputNilaiStart!=='0000-00-00' && data.DetailTA.utsInputNilaiStart!==null) ? $('#nilaiuts_start').datepicker('setDate',new Date(data.DetailTA.utsInputNilaiStart)) : '';
            (data.DetailTA.utsInputNilaiEnd !=='0000-00-00' && data.DetailTA.utsInputNilaiEnd!==null) ? $('#nilaiuts_end').datepicker({showOtherMonths:true,autoSize: true,dateFormat: 'dd MM yy',
                minDate: new Date(data.DetailTA.utsInputNilaiStart)}).datepicker('setDate',new Date(data.DetailTA.utsInputNilaiEnd)) : '';

            (data.DetailTA.showNilaiUts !=='0000-00-00' && data.DetailTA.showNilaiUts!==null) ? $('#show_nilai_uts').datepicker('setDate',new Date(data.DetailTA.showNilaiUts)) : '';

            (data.DetailTA.uasStart!=='0000-00-00' && data.DetailTA.uasStart!==null) ? $('#uas_start').datepicker('setDate',new Date(data.DetailTA.uasStart)) : '';
            (data.DetailTA.uasEnd !=='0000-00-00' && data.DetailTA.uasEnd!==null) ? $('#uas_end').datepicker({showOtherMonths:true,autoSize: true,dateFormat: 'dd MM yy',
                minDate: new Date(data.DetailTA.uasStart)}).datepicker('setDate',new Date(data.DetailTA.uasEnd)) : '';

            (data.DetailTA.uasInputNilaiStart !=='0000-00-00' && data.DetailTA.uasInputNilaiStart!==null) ? $('#nilaiuas_start').datepicker('setDate',new Date(data.DetailTA.uasInputNilaiStart)) : '';
            (data.DetailTA.uasInputNilaiEnd !=='0000-00-00' && data.DetailTA.uasInputNilaiEnd!==null) ? $('#nilaiuas_end').datepicker({showOtherMonths:true,autoSize: true,dateFormat: 'dd MM yy',
                minDate: new Date(data.DetailTA.uasInputNilaiStart)}).datepicker('setDate',new Date(data.DetailTA.uasInputNilaiEnd)) : '';

            (data.DetailTA.showNilaiUas !=='0000-00-00' && data.DetailTA.showNilaiUas!==null) ? $('#show_nilai_uas').datepicker('setDate',new Date(data.DetailTA.showNilaiUas)) : '';

            (data.DetailTA.edomStart !=='0000-00-00' && data.DetailTA.edomStart!==null) ? $('#edom_start').datepicker('setDate',new Date(data.DetailTA.edomStart)) : '';
            (data.DetailTA.edomEnd !=='0000-00-00' && data.DetailTA.edomEnd !==null) ? $('#edom_end').datepicker({showOtherMonths:true,autoSize: true,dateFormat: 'dd MM yy',
                minDate: new Date(data.DetailTA.edomStart)}).datepicker('setDate',new Date(data.DetailTA.edomEnd)) : '';

        });
    }

    function nextDatePick(value,nextElement) {

        // $('#'+nextElement).prop('disabled',false);

        var data_date = value;
        var CustomMoment = moment(data_date[2]+'-'+(parseInt(convertDateMMtomm(data_date[1])) + 1)+'-'+data_date[0]).add(1,'days');
        var CustomMomentYear = CustomMoment.year();
        var CustomMomentMonth = CustomMoment.month();
        var CustomMomentDate = CustomMoment.date();

        $( "#"+nextElement ).val('');
        $( "#"+nextElement ).datepicker( "destroy" );
        $( "#"+nextElement ).datepicker({
            showOtherMonths:true,
            autoSize: true,
            dateFormat: 'dd MM yy',
            minDate: new Date(CustomMomentYear,CustomMomentMonth,CustomMomentDate)
        });
    }

</script>

<script>
    $(document).on('click','.more_details',function () {
        var head = $(this).attr('data-head');
        var Type = $(this).attr('data-type');

        if(Type!=''){
            var AcademicDescID = Type.split('.')[0].trim();
            var Status = Type.split('.')[1].trim();
        }

        var body = '<div class="well"><select class="select2-select-00 full-width-fix" size="5" id="formTeaching"><option></option></select>' +
            '<input id="formAcademicDescID" value="'+AcademicDescID+'" class="hide" hidden /> ' +
            '<input id="formStatus" value="'+Status+'" class="hide" hidden /> ' +
            '<hr/>' +
            '<div id="divMK"></div> ' +
            '<hr/>' +
            '<div class="row">' +
            '   <div class="col-xs-6">' +
            '       <input type="text" id="formSc_start" nextelement="formSc_end" name="regular" class="form-control form-tahun-akademik" readonly>' +
            '   </div>' +
            '   <div class="col-xs-6">' +
            '       <input type="text" id="formSc_end" name="regular" class="form-control form-tahun-akademik" readonly>' +
            '   </div>' +
            '</div>' +
            '<div class="row">' +
            '   <div class="col-md-12" style="text-align: right;">' +
            '       <button class="btn btn-success" id="btnSaveSc" style="margin-top: 15px;">Save</button>' +
            '   </div>' +
            '   <div class="col-md-12">' +
            '       <div id="divMsg"></div>' +
            '   </div>' +
            '</div>' +
            '</div>' +
            '<hr/>' +
            '<div id="divtableSC"></div>';


        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Special Case - '+head+'</h4>');
        $('#GlobalModal .modal-body').html(body);
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        loadSelectOptionLecturersSingle('#formTeaching','');
        $('#formTeaching').select2({allowClear: true});
        $('#formSc_start').datepicker({
            showOtherMonths:true,
            autoSize: true,
            dateFormat: 'dd MM yy',
            // minDate: new Date(moment().year(),moment().month(),moment().date()),
            onSelect : function () {
                var data_date = $(this).val().split(' ');
                var nextelement = $(this).attr('nextelement')
                nextDatePick(data_date,nextelement);
            }
        });

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        loadDataSc(ID,AcademicDescID);


    });

    $(document).on('change','#formTeaching',function () {
        var formTeaching = $('#formTeaching').val();
        if(formTeaching!=''){
            //var ID = "<?php //echo $CDID; ?>//";
            var token = jwt_encode({action:'schedule',SemesterID:ID,NIP:formTeaching},'UAP)(*');
            var url = base_url_js+'api/__crudDataDetailTahunAkademik';
            $('#divMK').html('');
            $.post(url,{token:token},function (jsonResult) {
                if(jsonResult.length>0){
                    for(var i=0;i<jsonResult.length;i++){
                        var dt = jsonResult[i];
                        $('#divMK').append('<div class="checkbox">' +
                            '  <label>' +
                            '    <input type="checkbox" value="'+dt.ScheduleID+'|'+dt.NameEng+'">'+dt.ClassGroup+' | '+dt.NameEng+
                            '  </label>' +
                            '</div>');
                    }
                } else {
                    $('#divMK').html('<b>--- No Course ---</b>');
                }

            });
        }
    });

    $(document).on('click','#btnSaveSc',function () {
        var c = checkAllCourse();
        var NIP = $('#formTeaching').val();

        var dataStart = $('#formSc_start').datepicker("getDate");
        var dataEnd = $('#formSc_end').datepicker("getDate");

        if(dataStart!=null && dataEnd!=null && NIP!='' && c.length>0){

            loading_buttonSm('#btnSaveSc');
            $('#divMsg').html('');

            var Start = moment(dataStart).format('YYYY-MM-DD');
            var End = moment(dataEnd).format('YYYY-MM-DD');
            var formAcademicDescID = $('#formAcademicDescID').val();
            var formStatus = $('#formStatus').val();

            var dataSc = [];
            for(var s=0;s<c.length;s++){
                var ex = c[s].split('|');
                var d = {
                    Course : ex[1],
                    Details : {
                        SemesterID : ID,
                        AcademicDescID : formAcademicDescID,
                        UserID : NIP,
                        DataID : ex[0].trim(),
                        Start : Start,
                        End : End,
                        Status : ''+formStatus
                    }
                };
                dataSc.push(d);
            }

            var token = jwt_encode({action:'insertSC',dataForm:dataSc},'UAP)(*');
            var url = base_url_js+'api/__crudDataDetailTahunAkademik';

            $.post(url,{token:token},function (jsonResult) {

                setTimeout(function () {
                    if(jsonResult.length>0){
                        $('#divMsg').html('<div class="thumbanial" style="margin-top: 15px;padding: 10px;background: #ffffff;border: 1px solid #FF9800;"><div id="msg"></div></div>');
                        for(var d=0;d<jsonResult.length;d++){
                            var data = jsonResult[d];
                            var color = (data.Status=='1') ? 'style="color: green;"' : 'style="color: red;"';
                            var icn = (data.Status=='1') ? '<i '+color+' class="fa fa-check-circle"></i>' : '<i '+color+' class="fa fa-times-circle"></i>';

                            $('#msg').append('<div style="border-bottom: 1px solid #ddd;padding-bottom: 5px;margin-bottom: 5px;">' +
                                '<span>'+icn+' '+data.Course+'</span><br/>' +
                                '<i '+color+'>'+data.Msg+'</i>' +
                                '</div>');

                            if(data.Status=='1'){
                                var dc = data.Details;
                                $('#dtSC').append('<tr id="trSC'+dc.ID+'">' +
                                    '<td><b>'+dc.Lecturers+'</b><br/>'+dc.ClassGroup+' | '+dc.NameEng+'</td>' +
                                    '<td style="text-align: center;">'+moment(dc.Start).format('DD MMM YYYY')+' - '+moment(dc.End).format('DD MMM YYYY')+'</td>' +
                                    '<td style="text-align: center;"><button class="btn btn-danger btn-delete-aysco" data-id="'+dc.ID+'"><i class="fa fa-trash" aria-hidden="true"></i></button></td>' +
                                    '</tr>');
                            }
                        }
                    }
                    $('#btnSaveSc').html('Save').prop('disabled',false);
                },1000);
            });

        } else {
            toastr.error('Form Required','Error');
        }

    });

    function checkAllCourse() {
        var allVals = [];
        $('#divMK :checked').each(function() {
            allVals.push($(this).val());
        });

        return allVals;
    }

    function loadDataSc(ID,AcademicDescID) {
        var token = jwt_encode({action:'dataSC',SemesterID:ID,AcademicDescID:AcademicDescID},'UAP)(*');
        var url = base_url_js+'api/__crudDataDetailTahunAkademik';
        $.post(url,{token:token},function (jsonResult) {

            $('#divtableSC').html('<table id="tableSC" class="table table-bordered">' +
                '<thead>' +
                '   <tr style="background: #437e88;color: #ffffff;">' +
                '       <th>User</th>' +
                '       <th style="width: 45%;">Detail</th>' +
                '       <th style="width: 5%;">Action</th>' +
                '   </tr>' +
                '</thead>' +
                '<tbody id="dtSC"></tbody>' +
                '</table>');

            if(jsonResult.length>0){

                // $('#dtSC').empty();
                for(var t=0;t<jsonResult.length;t++){
                    var dc = jsonResult[t];
                    $('#dtSC').append('<tr id="trSC'+dc.ID+'">' +
                        '<td><b>'+dc.Lecturers+'</b><br/>'+dc.ClassGroup+' | '+dc.NameEng+'</td>' +
                        '<td style="text-align: center;">'+moment(dc.Start).format('DD MMM YYYY')+' - '+moment(dc.End).format('DD MMM YYYY')+'</td>' +
                        '<td style="text-align: center;"><button class="btn btn-danger btn-delete-aysco" data-id="'+dc.ID+'"><i class="fa fa-trash" aria-hidden="true"></i></button></td>' +
                        '</tr>');
                }

                $('#tableSC').DataTable({
                    "sDom": "<'row'<'dataTables_header clearfix'<'col-md-3'><'col-md-9'f>r>>t<'row'<'dataTables_footer clearfix'<'col-md-12'p>>>", // T is new
                    'bLengthChange' : false,
                    'bInfo' : false,
                    'pageLength' : 7
                });
            }


        });

    }

    $(document).on('click','.btn-delete-aysco',function () {
        var ID = $(this).attr('data-id');
        var token = jwt_encode({action:'deleteSC',ID:ID},'UAP)(*');
        var url = base_url_js+'api/__crudDataDetailTahunAkademik';

        var el = '.btn-delete-aysco[data-id='+ID+']';
        loading_buttonSm(el);
        $.post(url,{token:token},function (jsonResult) {
            setTimeout(function () {
                $('#trSC'+ID).remove();
            },1000);
        });

    });
</script>