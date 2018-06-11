
<style>
    .toggle-group .btn-default {
        z-index: 1000 !important;
    }
    .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
    .toggle.ios .toggle-handle { border-radius: 20px; }

    #tableDataStudents tr th,#tableDataStudents tr td {
        text-align: center;
    }
    #tableStd>tbody>tr>td {
        border-top: none;
        padding-top: 3px;
        padding-bottom: 3px;
    }
    .list-scd {
        list-style-type: none;
        padding-left: 0px;
    }

    table.table-krs th, table.table-krs td {
        text-align: center;
    }
</style>

<div class="row" style="margin-top: 30px;">

    <div class="col-md-3">
        <div class="">
            <label>Semester Antara</label>
            <input type="checkbox" id="formSemesterAntara" data-toggle="toggle" data-style="ios"/>
        </div>
    </div>
    <div class="col-md-9">
        <div class="thumbnail">
            <div class="row">
                <div class="col-xs-3">
                    <select class="form-control" id="filterProgramCampus"></select>
                </div>
                <div class="col-xs-3">
                    <select class="form-control" id="filterSemester"></select>
                </div>
                <div class="col-xs-4">
                    <select class="form-control" id="filterBaseProdi"></select>
                </div>
                <div class="col-xs-2">
                    <select class="form-control" id="filterSemesterSchedule"></select>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="row">
    <div class="col-md-12">
        <hr/>
        <div id="divPage"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        window.SemesterAntara = 0;

        $('input[type=checkbox][data-toggle=toggle]').bootstrapToggle();
        loadSelectOptionProgramCampus('#filterProgramCampus','');

        $('#filterSemester').append('<option value="" disabled selected>-- Year Academic--</option>' +
            '                <option disabled>-----------------</option>');
        loSelectOptionSemester('#filterSemester','');

        loadSelectOptionBaseProdi('#filterBaseProdi','');
        // loadSelectOPtionAllSemester('#filterSemesterSchedule','');

        getStudents();
    });

    $(document).on('change','#filterSemester',function () {
        var Semester = $('#filterSemester').val();
        var SemesterID = (Semester!='' && Semester!= null) ? Semester.split('.')[0] : '';
        $('#filterSemesterSchedule').empty();
        $('#filterSemesterSchedule').append('<option value="" disabled selected>-- Semester --</option>' +
            '                <option disabled>------</option>');
        loadSelectOPtionAllSemester('#filterSemesterSchedule','',SemesterID,SemesterAntara);
        // filterSchedule();

    });

    $(document).on('change','#filterBaseProdi,#filterSemesterSchedule',function () {
        getStudents();
    });

    $(document).on('click','#btnBack',function () {
        getStudents();
    })

    $(document).on('click','.detailStudyPlan',function () {

        var NPM = $(this).attr('data-npm');
        var ta = $(this).attr('data-ta');
        var data = {
            action : 'detailStudent',
            NPM : NPM,
            ta : ta
        };
        var url = base_url_js+'api/__crudStudyPlanning';
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {

            var dataStd = jsonResult;

            var emailMhs = (dataStd.EmailPU!=null && dataStd.EmailPU!='') ? '' : 'hide';
            var emailDsn = (dataStd.MentorEmailPU!=null && dataStd.MentorEmailPU!='') ? '' : 'hide';

            $('#divPage').html('<div class="col-md-8 col-md-offset-2" style="margin-bottom: 15px;">' +
                '            <div class="row">' +
                '<div class="col-md-12" style="margin-bottom: 15px;"><button class="btn btn-warning" id="btnBack"><i class="fa fa-arrow-left right-margin" aria-hidden="true"></i> Back</button></div>' +
                '                <div class="col-xs-2">' +
                '                    <img class="img-thumbnail" style="width: 100%;" src="'+base_url_img_student+''+dataStd.Student_DB+'/'+dataStd.Photo+'">' +
                '                </div>' +
                '                <div class="col-xs-9">' +
                '                    <b>'+dataStd.NPM+' - '+dataStd.Name+'</b>' +
                '                    <div class="'+emailMhs+'"><br/><span>'+dataStd.EmailPU+'</span><br/>' +
                '                    <a style="font-size: 10px;"><i class="fa fa-envelope-o" aria-hidden="true"></i> Send Message</a>' +
                '                    </div>' +
                '                    <div class="thumbnail" style="margin-bottom: 10px;margin-top: 10px;">' +
                '                        <table class="table" id="tableStd">' +
                '                            <tr>' +
                '                                <td style="width: 15%;">Mentor</td>' +
                '                                <td style="width: 1%;">:</td>' +
                '                                <td><b>'+dataStd.Mentor+'</b>' +
                '                                    <div class="'+emailDsn+'"><br/>' + dataStd.MentorEmailPU +
                '                                           <br/><a style="font-size: 10px;"><i class="fa fa-envelope-o" aria-hidden="true"></i> Send Message</a>' +
                '                                    </div>' +
                '                                </td>' +
                '                            </tr>' +
                '                            <tr>' +
                '                                <td>IPK</td>' +
                '                                <td>:</td>' +
                '                                <td>'+parseFloat(dataStd.DetailSemester.IPK).toFixed(2)+'</td>' +
                '                            </tr>' +
                '                            <tr>' +
                '                                <td>Last IPS</td>' +
                '                                <td>:</td>' +
                '                                <td>'+parseFloat(dataStd.DetailSemester.LastIPS).toFixed(2)+' | '+dataStd.DetailSemester.MaxCredit.Credit+' Credit</td>' +
                '                            </tr>' +
                '                        </table>' +
                '                    </div>' +
                '                    <hr/>' +
                '                </div>' +
                '            </div>' +
                '<div class="col-xs-12"> <div class="thumbnail" style="padding: 10px;">' +
                '            <b>Status : </b>' +
                '            <i class="fa fa-circle" style="color:#d8d8d8;"></i> Student has not sent KRS |' +
                '            <i class="fa fa-circle" style="color:#00BCD4;"></i> Waiting Approval Mentor |' +
                '            <i class="fa fa-circle" style="color: #369c3a;"></i> KRS Approved Mentor |' +
                '            <i class="fa fa-check-circle" style="color: #369c3a;"></i> KRS Approved Kaprodi' +
                '        </div></div>' +
                '        </div>' +

                '        <table class="table table-striped table-bordered table-krs">' +
                '            <thead>' +
                '            <tr style="background:#437e88;color:#ffffff;">' +
                '                <th style="width: 7%;">Code</th>' +
                '                <th>Course</th>' +
                '                <th style="width: 5%;">Type</th>' +
                '                <th style="width: 5%;">Credit</th>' +
                '                <th style="width: 5%;">Group</th>' +
                '                <th>Schedule</th>' +
                '                <th style="width: 5%;">Status</th>' +
                '            </tr>' +
                '            </thead><tbody id="dataSchedule"></tbody>' +
                '        </table><hr/>');

            var tr = $('#dataSchedule');
            var totalCredit = 0;
            for(var i=0;i<dataStd.Schedule.length;i++){
                var dataSc = dataStd.Schedule[i];
                var status = '<i class="fa fa-circle" style="color:#d8d8d8;"></i>';
                if(dataSc.KRSStatus==1){
                    status = '<i class="fa fa-circle" style="color:#00BCD4;"></i>';
                } else if(dataSc.KRSStatus==2){
                    status = '<i class="fa fa-circle" style="color:#369c3a;"></i>';
                } else if(dataSc.KRSStatus==3){
                    status = '<i class="fa fa-check-circle" style="color: #369c3a;"></i>';
                }

                tr.append('<tr>' +
                        '<td>'+dataSc.MKCode+'</td>' +
                        '<td style="text-align: left;"><b>'+dataSc.NameEng+'</b><br/><i>'+dataSc.Name+'</i></td>' +
                        '<td>'+dataSc.TypeSP+'</td>' +
                        '<td>'+dataSc.Credit+'</td>' +
                        '<td>'+dataSc.ClassGroup+'</td>' +
                        '<td><ul id="sc'+i+'" class="list-scd"></ul></td>' +
                        '<td>'+status+'</td>' +
                    '</tr>');

                var sc = $('#sc'+i);
                for(var s=0;s<dataSc.DetailSchedule.length;s++){
                    var dataSCD = dataSc.DetailSchedule[s];
                    var st = dataSCD.StartSessions.split(':');
                    var en = dataSCD.EndSessions.split(':');

                    var start = st[0]+':'+st[1];
                    var end = en[0]+':'+en[1];
                    sc.append('<li>R.'+dataSCD.Room+' | <span style="text-align: right;">'+dataSCD.DayNameEng+', '+start+' - '+end+'<span></li>');
                }

                totalCredit = totalCredit + parseInt(dataSc.Credit);
                if(i==(dataStd.Schedule.length - 1)){
                    tr.append('<tr style="background: lightyellow;font-weight: bold;">' +
                        '<td colspan="3">Total Credit</td>' +
                        '<td>'+totalCredit+'</td>' +
                        '</tr>');
                }
            }
        });

    });


    
    function getStudents() {

        var ProgramID = $('#filterProgramCampus').val();
        // var ProdiID = $('#filterBaseProdi').val().split('.')[0];
        var filterBaseProdi = $('#filterBaseProdi').val();
        var filterSemesterSchedule = $('#filterSemesterSchedule').val();
        var ClassOf = (filterSemesterSchedule != '' && filterSemesterSchedule != null) ? filterSemesterSchedule.split('|')[1].split('.')[1] : '';

        if (ProgramID != null && filterBaseProdi != null && filterSemesterSchedule != null && ClassOf != "") {
            var ProdiID = filterBaseProdi.split('.')[0];

            var data = {
                action: 'read',
                dataWhere: {
                    ProgramID: ProgramID,
                    ProdiID: ProdiID,
                    ClassOf: ClassOf
                }
            };

            $('#divPage').html('<div class="table-responsive"><table class="table table-striped table-bordered" id="tableDataStudents">' +
                '            <thead style="background: #007475;color: #ffffff;">' +
                '            <tr>' +
                '                <th rowspan="2" style="width: 1%;">No</th>' +
                '                <th rowspan="2" style="width: 7%;">NPM</th>' +
                '                <th rowspan="2">Student</th>' +
                '                <th rowspan="2" style="width: 15%;">Mentor</th>' +
                '                <th colspan="2" style="width: 10%;">Payment</th>' +
                '                <th rowspan="2" style="width: 10%;">Last IPS</th>' +
                '                <th rowspan="2" style="width: 10%;">IPK</th>' +
                '                <th rowspan="2" style="width: 5%;">Credit Taken</th>' +
                '                <th rowspan="2" style="width: 5%;">Max Credit</th>' +
                '            </tr>' +
                '            <tr>' +
                '               <th style="width: 10%;">BPP</th>' +
                '               <th style="width: 10%;">Credit</th>' +
                '            </tr>' +
                '            </thead>' +
                '            <tbody id="dataStudents"></tbody>' +
                '        </table></div>');

            var token = jwt_encode(data, 'UAP)(*');

            var url = base_url_js + 'api/__crudStudyPlanning';
            $.post(url, {token: token}, function (jsonResult) {
            // console.log(jsonResult);

            var tr = $('#dataStudents');
            var no = 1;
            for (var i = 0; i < jsonResult.length; i++) {

                var CreditUnit = 0;
                var StudyPlanning = jsonResult[i].StudyPlanning;
                for (var c = 0; c < StudyPlanning.length; c++) {
                    var stp = StudyPlanning[c];
                    CreditUnit = CreditUnit + parseInt(stp.TotalSKS);
                }

                // console.log(CreditUnit);

                var Student = jsonResult[i].Student;

                var sendMailStd = (Student.EmailPU!=null && Student.EmailPU!='') ? '<br/><a style="color: #03a9f4;" href="javascript:void(0);" class="sendEmail" data-email="'+Student.EmailPU+'"><i class="fa fa-envelope-o" aria-hidden="true"></i> Send Email</a>' : '';

                tr.append('<tr>' +
                    '<td>' + no + '</td>' +
                    '<td>' + Student.NPM + '</td>' +
                    '<td style="text-align: left;">' +
                    '   <b>' +
                    '       <a href="javascript:void(0)" class="detailStudyPlan" data-npm="' + Student.NPM + '" data-ta="' + Student.ClassOf + '">' + Student.Name + '</a></b>' + sendMailStd +
                    '</td>' +
                    '<td id="mentorData'+no+'" style="text-align: left;">-</td>' +
                    '<td id="bpp'+no+'">-</td>' +
                    '<td id="credit'+no+'">-</td>' +
                    '<td>' +parseFloat(Student.DetailSemester.LastIPS).toFixed(2)+ '</td>' +
                    '<td>' + parseFloat(Student.DetailSemester.IPK).toFixed(2) + '</td>' +
                    '<td>' + CreditUnit + '</td>' +
                    '<td>' + Student.DetailSemester.MaxCredit.Credit + '</td>' +
                    '</tr>');

                if(Student.DetailPayment.length>0){
                    for(var p=0;p<Student.DetailPayment.length;p++){
                        var dt = Student.DetailPayment[p];
                        if(dt.PTID=='2'){
                            $('#bpp'+no).html('<i class="fa fa-check-circle" style="color: green;"></i>');
                        }
                        if(dt.PTID=='3'){
                            $('#credit'+no).html('<i class="fa fa-check-circle" style="color: green;"></i>');
                        }
                    }
                }

                if(Student.DetailMentor.length>0){
                    var dataMentor = Student.DetailMentor[0];
                    var spDsn = dataMentor.Mentor.split(' ');
                    var dsn = (spDsn.length>2) ? spDsn[0]+' '+spDsn[1] : dataMentor.Mentor;
                    var divMentor = dsn+'<br/><i>'+dataMentor.NIP+'</i>';
                    $('#mentorData'+no).html(divMentor);
                }

                no++;
            }

            $('#tableDataStudents').DataTable({
                'pageLength': 25
            });
        });

        }



    }

    $(document).on('click','.sendEmail',function () {
        var email = $(this).attr('data-email');
        var url = 'https://mail.google.com/mail/?view=cm&fs=1&to='+email;
        PopupCenter(url,'xtf','900','500')

    });
</script>