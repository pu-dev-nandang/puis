
<style>
    #tbInput td {
        /*text-align: center;*/
    }
    .form-datetime[readonly] {
        background-color: #ffffff;
        color: #333333;
        cursor: text;
    }
    #tableEditExamStd thead tr th, #tableEditExamStd tbody tr td {
        text-align: center;
    }
</style>


<div class="row">
    <div class="col-md-8">
        <table class="table table-hover" id="tbInput">
            <tr>
                <th style="width: 20%;">Exam</th>
                <td style="width: 1%;">:</td>
                <td style="text-align: left;">
                    <label class="radio-inline">
                        <input type="radio" name="formExam" id="formUTS" value="uts" class="formExam" checked> UTS
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="formExam" id="formUAS" value="uas" class="formExam"> UAS
                    </label>
                </td>
            </tr>
            <tr>
                <th>Group & Tanggal</th>
                <td>:</td>
                <td>
                    <div class="row">
                        <div class="col-md-6">
                            <input id="formSemesterID" type="hidden" class="hide" hidden readonly>
                            <div id="viewGroup"></div>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="formDate" readonly class="form-control form-datetime">
                            <input id="formDayID" type="hidden" class="hide" hidden readonly>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Students</th>
                <td>:</td>
                <td style="text-align: left;">
                    <div class="row">
                        <div class="col-xs-5">
                            <b class="label label-primary"> <span id="dataTotalStudent">0</span> of <span id="OfDataTotalStudent">0</span></b> Students |
                            <a href="javascript:void(0);" data-classgroup="" id="btnEditExamStudents">Edit</a>
                        </div>
                        <div class="col-xs-4" style="text-align: right;">
                            <label>Row Of Seats : </label>
                        </div>
                        <div class="col-xs-3">
                            <select class="form-control" id="formRowOfSeats"></select>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Waktu</th>
                <td>:</td>
                <td>
                    <div class="row">
                        <div class="col-md-4">
                            <div id="inputStart" class="input-group">
                                <input data-format="hh:mm" type="text" id="formStart" class="form-control form-attd" value=""/>
                                <span class="add-on input-group-addon">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                            </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div id="inputEnd" class="input-group">
                                <input data-format="hh:mm" type="text" id="formEnd" class="form-control form-attd" value=""/>
                                <span class="add-on input-group-addon">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                            </span>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Room</th>
                <td>:</td>
                <td>
                    <select id="formClassroom" class="form-control" style="max-width: 300px;"></select>
                </td>
            </tr>
            <tr>
                <th>Pengawas 1</th>
                <td>:</td>
                <td style="text-align: left;">
                    <select class="select2-select-00" style="max-width: 300px !important;" size="5" id="formPengawas1">
                        <option value=""></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Pengawas 2</th>
                <td>:</td>
                <td style="text-align: left;">
                    <select class="select2-select-00" style="max-width: 300px !important;" size="5" id="formPengawas2">
                        <option value=""></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td id="trAlertJadwal" class="hide" colspan="3">
                    <div class="alert alert-warning" role="alert">
                        <b>Group Class sudah dibuatkan <b id="jmlJadwal"></b> Jadwal Ujian</b>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div style="text-align: right;">
                        <button id="btnSave" class="btn btn-primary">Save</button>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-4">
        <div class="well" style="min-height:150px;padding: 15px;">
            <div id="divDetails">-</div>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {

        window.dataStudentForExamDisabled = []; // Untuk mhs yang sudah masuk di jadwal lain
        window.dataStudentForExam = []; // Data mhs yang sudah di set di jadwal ini
        window.dataAllStudentForExam = [];


        getDataCourse();
        dateInputJadwal();
    });


    $(document).on('change','.formExam',function () {
        $('#formCourse').val('');
        dataStudentForExam = [];
        dataAllStudentForExam = [];


        $('#trAlertJadwal').addClass('hide');

        $('#dataTotalStudent').html(0);
        $('#OfDataTotalStudent').html(0);
        $('#btnEditExamStudents').attr('data-classgroup','');
        dateInputJadwal();
    });


    function dateInputJadwal() {
        var dataForm = $('input[name=formExam]:checked').val();
        var url = base_url_js+'api/__crudJadwalUjian';
        var token = jwt_encode({action:'checkDateExam'},'UAP)(*');

        $( "#formDate" ).val('');
        $( "#formDate" ).datepicker( "destroy" );

        $.post(url,{token:token},function (jsonResult) {

            $('#formSemesterID').val(jsonResult.SemesterID);

            var dateStart = jsonResult.utsStart;
            var dateEnd = jsonResult.utsEnd;

            if(dataForm=='uas'){
                dateStart = jsonResult.uasStart;
                dateEnd = jsonResult.uasEnd;
            }

            var splitStart = dateStart.split('-');
            var C_dateStart_Y = splitStart[0].trim();
            var C_dateStart_M = parseInt(splitStart[1].trim())-1;
            var C_dateStart_D = splitStart[2].trim();

            var splitEnd = dateEnd.split('-');
            var C_dateEnd_Y = splitEnd[0].trim();
            var C_dateEnd_M = parseInt(splitEnd[1].trim())-1;
            var C_dateEnd_D = splitEnd[2].trim();

            $('#formDate').datepicker({
                showOtherMonths:true,
                autoSize: true,
                dateFormat: 'dd MM yy',
                minDate : new Date(C_dateStart_Y,C_dateStart_M,C_dateStart_D),
                maxDate : new Date(C_dateEnd_Y,C_dateEnd_M,C_dateEnd_D),
                onSelect : function () {
                    var data_date = $(this).val().split(' ');
                    var CustomMoment = moment(data_date[2]+'-'+(parseInt(convertDateMMtomm(data_date[1])) + 1)+'-'+data_date[0]).day();
                    var day = (CustomMoment==0) ? 7 : CustomMoment;
                    $('#formDayID').val(day);
                }
            });
        });
    }

    function getDataCourse() {
        var url = base_url_js+'api/__crudJadwalUjian';
        var token = jwt_encode({action:'read'},'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {

            $('#viewGroup').html('<select class="select2-select-00 full-width-fix"' +
                '                            multiple size="5" id="formCourse"></select>');

            $('#formCourse').empty();
            $('#formCourse').append('<option value=""></option>');
            for(var i=0;i<jsonResult.length;i++){
                var data = jsonResult[i];
                $('#formCourse').append('<option value="'+data.ID+'">'+data.ClassGroup+' - '+data.CoordinatorName+'</option>');
            }

            $('#formCourse').select2({allowClear: true});
        });
    }
</script>