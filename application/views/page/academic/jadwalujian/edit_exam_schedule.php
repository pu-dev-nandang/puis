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
    #ulCurrentGroup {
        list-style-type: none;
        padding-left: 0px;
        margin-bottom: 0px;
    }

    #ulCurrentGroup li .btnDeleteGroup {
        /*color: red;*/
        border-radius: 60px;
        padding: 1px;
        padding-left: 5px;
        padding-right: 6px;
    }
    #ulCurrentGroup li .btnEditStudent {
        padding: 1px;
        padding-left: 5px;
        padding-right: 6px;
    }

    #ulCurrentGroup li {
        padding-bottom: 10px;
    }
</style>

<!--<pre>-->
<!--    --><?php //print_r($arrExam); ?>
<!--</pre>-->

<?php if(count($arrExam)>0){
    $d = $arrExam[0];
    ?>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <table class="table" id="tbInput">
                <tr>
                    <th style="width: 10%;">Exam | Date</th>
                    <td style="width: 1%;">:</td>
                    <td style="text-align: left;">
                        <div class="row">
                            <div class="col-xs-3">
                                <label class="radio-inline">
                                    <input type="radio" name="formExam" id="formUTS" value="uts" class="formExam form-exam" <?php if($d['Type']=='uts'){echo 'checked'; } ?>> UTS
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="formExam" id="formUAS" value="uas" class="formExam form-exam" <?php if($d['Type']=='uas'){echo 'checked'; } ?>> UAS
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <input type="text" id="formDate" readonly class="form-control form-exam form-datetime">
                                <span id="viewDate"></span>
                                <input id="formInputDate" value="<?php echo $d['ExamDate']; ?>" class="hide" readonly hidden>
                                <input id="formDayID" value="<?php echo $d['DayID']; ?>" class="hide" readonly hidden>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr style="background: lightyellow;">
                    <th>Current Group</th>
                    <td>:</td>
                    <td>
                        <ul id="ulCurrentGroup">
                            <?php for($t=0;$t<count($d['Course']);$t++){ $dc = $d['Course'][$t]; ?>
                                <li id="liGr<?php echo $dc['ScheduleID']; ?>"><b><?php echo $dc['ClassGroup'].' - '.$dc['CourseEng']; ?></b> |
                                    <button class="btn btn-sm btn-default btn-default-primary btnEditStudent" data-examid="<?php echo $d['ID']; ?>" data-id="<?php echo $dc['ScheduleID']; ?>"><?php echo count($dc['DetailStudent']); ?> Student</button> |
                                    <button class="btn btn-sm btn-default btn-default-danger btnDeleteGroup" data-examid="<?php echo $d['ID']; ?>" data-id="<?php echo $dc['ScheduleID']; ?>"><i class="fa fa-trash"></i></button></li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <th>Group</th>
                    <td>:</td>
                    <td>
                        <div class="row">
                            <div class="col-xs-8">
                                <input id="formSemesterID" value="<?php echo $d['SemesterID']; ?>" class="hide" readonly hidden>
                                <div id="viewGroup"></div>
                            </div>
                            <div class="col-xs-2" style="padding-top: 5px;">
                                <textarea id="formStudent" class="hide" hidden readonly></textarea>
                                <textarea id="AllStudent" class="hide" hidden readonly></textarea>
                                <b class="label label-primary"> <span id="dataTotalStudent">0</span> of <span id="OfDataTotalStudent">0</span></b> Students |
                                <a href="javascript:void(0);" class="btnEditStudent form-exam" data-classgroup="" data-notr="">Edit</a>
                            </div>
                            <div class="col-xs-2" style="text-align: right;">
                                <button class="btn btn-default btn-default-success form-exam" id="addNewGroup"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                <button class="btn btn-default btn-default-danger form-exam" id="deleteNewGroup" disabled><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </td>
                </tr>

                <tbody id="trNewGroup"></tbody>

                <tr>
                    <th>Waktu</th>
                    <td>:</td>
                    <td>
                        <div class="row">
                            <div class="col-md-4">
                                <div id="inputStart" class="input-group">
                                    <input data-format="hh:mm" type="text" id="formStart" class="form-control form-exam" value="<?php echo $d['ExamStart']; ?>"/>
                                    <span class="add-on input-group-addon">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                            </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div id="inputEnd" class="input-group">
                                    <input data-format="hh:mm" type="text" id="formEnd" class="form-control form-exam" value="<?php echo $d['ExamEnd']; ?>"/>
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
                        <select class="select2-select-00 form-exam" style="max-width: 300px !important;" size="5" id="formClassroom">
                            <option value=""></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Pengawas 1</th>
                    <td>:</td>
                    <td style="text-align: left;">
                        <select class="select2-select-00 form-exam" style="max-width: 300px !important;" size="5" id="formPengawas1">
                            <option value=""></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Pengawas 2</th>
                    <td>:</td>
                    <td style="text-align: left;">
                        <select class="select2-select-00 form-exam" style="max-width: 300px !important;" size="5" id="formPengawas2">
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
            </table>

            <div style="text-align: right;">
                <a href="<?php echo base_url('academic/exam-schedule/list-exam'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left margin-right"></i> Back to List</a>
                <button id="btnSaveSetSchedule" class="btn btn-primary">Save</button>
            </div>
            <hr/>

            <div id="divAlertBentrok"></div>
        </div>

    </div>

    <script>
        $(document).ready(function () {
            var formDate = "<?php echo $d['ExamDate']; ?>";
            $('#viewDate').html(moment(formDate).format('dddd, DD MMM YYYY'));
            // console.log(new Date(formDate));
            // $('#formDate').datepicker('setDate',new Date(formDate));

            dateInputJadwal_();
            loadSelect2OptionClassroom('#formClassroom','<?php echo $d['ExamClassroomID']; ?>');

            loadSelectOptionEmployeesSingle('#formPengawas1','<?php echo $d['Pengawas1']; ?>');
            loadSelectOptionEmployeesSingle('#formPengawas2','<?php echo $d['Pengawas2']; ?>');

            $('#formClassroom,#formPengawas1,#formPengawas2').select2({allowClear: true});

            getDataCourse('#viewGroup','');


        });

        $(document).on('click','.btnDeleteGroup',function () {

            var ScheduleID = $(this).attr('data-id');
            var ExamID = $(this).attr('data-examid');


            $('#NotificationModal .modal-header').addClass('hide');
            $('#NotificationModal .modal-body').html('<div style="text-align: center;">' +
                '<h4>Menghapus group akan menghapus data student</h4>' +
                '<hr/>' +
                '<button type="button" class="btn btn-danger" id="btnDeleteGroup">Yes</button> ' +
                '<button type="button" class="btn btn-default" data-dismiss="modal">No</button> ' +
                '</div>');
            $('#NotificationModal .modal-footer').addClass('hide');
            $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
            });

            $('#btnDeleteGroup').click(function () {

                loading_buttonSm('#btnDeleteGroup');
                $('.btn-default[data-dismiss=modal]').prop('disabled',true);

                var url = base_url_js+'api/__crudJadwalUjian';
                var token = jwt_encode({action:'deleteGroupExam',ExamID:ExamID,ScheduleID:ScheduleID},'UAP)(*');
                $.post(url,{token:token},function (result) {
                    $('#liGr'+ScheduleID).remove();
                    toastr.success('Data removed','Success');
                    setTimeout(function () {
                        $('#NotificationModal').modal('hide');
                        if(result==-1 || result=='-1'){
                            window.location.href = base_url_js+'academic/exam-schedule/list-exam';
                        }
                    },500);
                });
            });

        });



        function dateInputJadwal_() {
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
                        var momentDate = moment(data_date[2]+'-'+(parseInt(convertDateMMtomm(data_date[1])) + 1)+'-'+data_date[0]);
                        var CustomMoment = momentDate.day();
                        var day = (CustomMoment==0) ? 7 : CustomMoment;
                        $('#formDayID').val(day);
                        $('#formInputDate').val(momentDate.format('YYYY-MM-DD'));

                        // $('#viewDate').html(momentDate.format('dddd, DD MMM YYYY'));
                    }
                });
            });
        }

        function getDataCourse(element,notr) {

            var nor = (notr!='' && notr!=null && typeof notr !== 'undefined') ? notr : '';
            var idC = 'formCourse'+nor;

            var url = base_url_js+'api/__crudJadwalUjian';
            var token = jwt_encode({action:'read'},'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

                $(element).html('<select class="select2-select-00 full-width-fix showStudent form-exam"' +
                    '                            size="5" data-tr="'+nor+'" id="'+idC+'"></select>');

                $('#'+idC).empty();
                $('#'+idC).append('<option value=""></option>');
                for(var i=0;i<jsonResult.length;i++){
                    var data = jsonResult[i];

                    $('#'+idC).append('<option value="'+data.ID+'">'+data.ClassGroup+' - '+data.CourseEng+' ( '+data.CoordinatorName+' )</option>');
                }

                $('#'+idC).select2({allowClear: true});
            });
        }
    </script>

<?php } else {

} ?>