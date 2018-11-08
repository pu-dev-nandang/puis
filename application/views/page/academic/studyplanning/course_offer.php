
<style>
    #tableCourse thead tr th {
        text-align: center;
        background: #438882;
        color: #fff;
    }
    #tableCourse tbody tr td {
        text-align: center;
    }

    #tableRequired tr th, #tableRequired tr td,
    #tableOption tr th, #tableOption tr td {
        text-align: center;
    }

    #tableRequired tr th {
        color: #428bca;
        background: #e4e4e4;
    }
    #tableOption tr th {
        color: #b78337;
        background: #e4e4e4;
    }



    .alert {
        margin-bottom: 3px;
        padding : 7px 5px 7px 5px;
    }

    .alert-info {
        border-color: #7fb1bb;
    }

    .alert-warning {
        border-color: #dcb275;
    }

    .seat {
        background: #ff5722c2;
        color: #fff;
        padding: 0px;
        padding-right: 7px;
        padding-left: 7px;
        border-radius: 16px;
        font-size: 12px;
        margin-left: 5px;
    }

    .btnCustom {
        padding: 1px 4px 1px 4px;
    }
</style>


<div class="row">

    <div class="col-md-12">
        <a href="<?php echo base_url('academic/study-planning/list-student'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left margin-right"></i> Back to list</a>
        <div style="text-align: center;">
            <h3 style="font-weight: bold;margin-top: 0px;margin-bottom: 5px;" id="Name">-</h3>
            <h5 style="margin-top: 0px;"><span id="NIM">-</span> | <span id="Prodi">-</span> | <span id="Mentor">-</span></h5>
            <input id="formDBStudent" readonly class="hide">
            <input id="formMhswID" readonly class="hide">
        </div>
        <hr style="margin-top: 5px;" />
    </div>

    <div class="col-md-6">

        <h4>
            <b>Study Plan</b>
            <div style="float: right;margin-bottom: 5px;">
                <button class="btn btn-sm btn-default btn-default-primary"><span id="loadMyDraf">0</span> of 16 Credit</button>
            </div>
        </h4>

        <div id="dataDraf"></div>

    </div>

    <div class="col-md-6">
        <div id="divLoadCourse"></div>

    </div>
</div>

<script>
    $(document).ready(function () {
       window.SemesterIDinKRS = "<?php echo $SemesterID; ?>";
       window.NPMinKRS = "<?php echo $NPM; ?>";

       var loadFirst = setInterval(function (args) {
           if(SemesterIDinKRS!='' && NPMinKRS!=''){
               loadAvailable();
               clearInterval(loadFirst);
           }
       },1000);

    });

    function loadAvailable() {

        var data = {
            action : 'readAvailableKRS',
            SemesterID : SemesterIDinKRS,
            NPM : NPMinKRS
        };

        loading_page('#divLoadCourse');

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudStudyPlanning';
        $.post(url,{token:token},function (jsonResult) {

            var inputMaxCredit = jsonResult.Student.dataCredit.MaxCredit.Credit;

            var CreditInDraf = 0;

            var Semester = jsonResult.Student.dataCredit.Semester;
            var Course = jsonResult.Course.Schedule;
            var CourseDraf = jsonResult.Course.ScheduleDraf;

            var Student = jsonResult.Student;

            $('#Name').html(Student.Name);
            $('#NIM').html(Student.NPM);
            $('#Prodi').html(Student.NameEng);
            $('#Mentor').html(Student.Mentor);
            $('#formDBStudent').val('ta_'+Student.Year);
            $('#formMhswID').val(Student.MhswID);

            $('#dataDraf').html('<table class="table table-bordered table-striped" id="tableCourse">' +
                '            <thead>' +
                '            <tr>' +
                // '                <th style="width: 1%;">No</th>' +
                '                <th style="width: 5%;">Smt</th>' +
                '                <th style="width: 5%;">Group</th>' +
                '                <th>Course</th>' +
                '                <th style="width: 27%;">Schedule</th>' +
                '                <th style="width: 3%;">Type</th>' +
                '                <th style="width: 3%;">Crdt</th>' +
                '                <th style="width: 1%;"><i class="fa fa-cog"></i></th>' +
                '            </tr>' +
                '            </thead>' +
                '            <tbody id="dataRowDraf"></tbody>' +
                '        </table>');


            // Course Draf
            if(CourseDraf.length>0){

                for(var t=0;t<Course.length;t++){

                    var d = Course[t];

                        var draf = CourseDraf.findIndex(x => x.ScheduleID === d.ID);

                        if(draf!=-1){

                        var viewSchedule = '';
                        var detailSchedule = d.ScheduleDetails;
                        if(detailSchedule.length>0){
                            for(var ds=0;ds<detailSchedule.length;ds++){
                                var d_ds = detailSchedule[ds];

                                var datetime = d_ds.DayNameEng+', '+d_ds.StartSessions.substr(0,5)+' - '+d_ds.EndSessions.substr(0,5);

                                // var classAlert = (parseInt(d.Semester)==parseInt(Semester)) ? 'alert-info' : 'alert-warning';
                                viewSchedule = viewSchedule+''+datetime+' | ' +
                                    '<span style="color: blue;">'+d_ds.Room+'</span><br/>';
                            }
                        }

                        $('#dataRowDraf').append('<tr>' +
                            '<td>'+d.Semester+'</td>' +
                            '<td>'+d.ClassGroup+'</td>' +
                            '<td style="text-align: left;"><b>'+d.MKCode+' - '+d.MKNameEng+'</b></td>' +
                            '<td style="text-align: right;">'+viewSchedule+'</td>' +
                            '<td>'+CourseDraf[draf].TypeSP+'</td>' +
                            '<td>'+d.Credit+'</td>' +
                            '<td><button class="btn btn-sm btn-danger btnCustom btnDelete" data-id="'+CourseDraf[draf].SKID+'"><i class="fa fa-trash"></i></button></td>' +
                            '</tr>');

                        CreditInDraf = CreditInDraf + parseInt(d.Credit);
                    }


                }
            } 
            else {
                $('#dataRowDraf').append('<tr>' +
                    '<td colspan="7">--- Data not yrt ---</td>' +
                    '</tr>');
            }

            $('#loadMyDraf').html(CreditInDraf);

            if(Course.length>0){

                $('#divLoadCourse').html('<div class="panel panel-primary">' +
                    '            <div class="panel-heading" style="border-radius: 0px;">' +
                    '                <h4 class="panel-title">Required Course</h4>' +
                    '            </div>' +
                    '            <div class="panel-body" style="min-height: 60px;padding: 0px;">' +
                    '                <table class="table table-striped" id="tableRequired">' +
                    '                    <thead>' +
                    '                    <tr>' +
                    '                        <th style="width: 5%;">Group</th>' +
                    '                        <th>Course</th>' +
                    '                        <th style="width: 3%;">Type</th>' +
                    '                        <th style="width: 3%;">Crd</th>' +
                    '                        <th style="width: 35%;">Scheduel</th>' +
                    '                        <th style="width: 1%;"><i class="fa fa-cog"></i></th>' +
                    '                    </tr>' +
                    '                    </thead>' +
                    '                </table>' +
                    '            </div>' +
                    '        </div>' +
                    '' +
                    '<div class="panel panel-warning" style="border: 1px solid #b78337;">' +
                    '            <div class="panel-heading" style="border-radius: 0px;background: #b78337;color: #FFFFFF;">' +
                    '                <h4 class="panel-title">Optional Course</h4>' +
                    '            </div>' +
                    '            <div class="panel-body" style="min-height: 60px;padding: 0px;">' +
                    '                <table class="table table-striped" id="tableOption">' +
                    '                    <thead>' +
                    '                    <tr>' +
                    '                        <th style="width:1%">Smt</th>' +
                    '                        <th style="width:5%">Group</th>' +
                    '                        <th>Course</th>' +
                    '                        <th style="width:3%;">Type</th>' +
                    '                        <th style="width:3%;">Crd</th>' +
                    '                        <th style="width:35%;">Scheduel</th>' +
                    '                        <th style="width: 1%;"><i class="fa fa-cog"></i></th>' +
                    '                    </tr>' +
                    '                    </thead>' +
                    '                </table>' +
                    '            </div>' +
                    '        </div>');

                for(var i=0;i<Course.length;i++){
                    var d = Course[i];

                    var draf = CourseDraf.findIndex(x => x.ScheduleID === d.ID);

                    var mkType = (d.SPID!=null && d.SPID!='') ? 'Ul' : 'Br';
                    mkType = (draf!=-1) ? CourseDraf[draf].TypeSP : mkType;

                    var viewSchedule = '';
                    var detailSchedule = d.ScheduleDetails;
                    var dataAvSeat = 0;
                    if(detailSchedule.length>0){
                        for(var ds=0;ds<detailSchedule.length;ds++){
                            var d_ds = detailSchedule[ds];

                            var datetime = d_ds.DayNameEng+', '+d_ds.StartSessions.substr(0,5)+' - '+d_ds.EndSessions.substr(0,5);

                            var classAlert = (parseInt(d.Semester)==parseInt(Semester)) ? 'alert-info' : 'alert-warning';
                            viewSchedule = viewSchedule+'<div class="alert '+classAlert+' alert-schedule"><div class="row" style="margin-right:0px;margin-left:0px;">' +
                                '<div style="float: left;">'+datetime+'</div>' +
                                '<div style="float: right;">'+d_ds.Room+'<span class="seat"> '+d_ds.CountSeat+' of '+d_ds.Seat+' Seat</span></div></div></div>';

                            if(parseInt(d_ds.Seat)<=parseInt(d_ds.CountSeat)){
                                dataAvSeat = 1;
                            }
                        }
                    }


                    // ======
                    var datatoToken = {
                        SemesterID : SemesterIDinKRS,
                        NPM : Student.NPM,
                        ScheduleID : d.ID,
                        CDID : d.CDID,
                        TypeSP : mkType,
                        Insert_By : sessionNIP,
                        Input_At : dateTimeNow(),
                        Status : '3',
                        Flag : '1'
                    };

                    var dataTokenID = jwt_encode(datatoToken,'UAP)(*');

                    var btnIFCredit = ((CreditInDraf+ parseInt(d.Credit)) <= parseInt(inputMaxCredit)) ? '<button class="btn btn-sm btn-default btnCustom btnActionAdd" data-token="'+dataTokenID+'"><i class="fa fa-plus-circle"></i></button>'
                        : '<i class="fa fa-exclamation-triangle" style="color: #ff9800;"></i> Credit';
                    var buttonAct = btnIFCredit;
                    if(draf!=-1) {
                        buttonAct = '<div><i class="fa fa-check-square-o" style="color: green;"></i><div>';
                    }  else if(dataAvSeat==1){
                        buttonAct = '<i class="fa fa-exclamation-triangle fa-2x" style="color: #ff9800;"></i><div style="font-size: 10px;">Class Full</div>';
                    }

                    if(parseInt(d.Semester)==parseInt(Semester)){
                        $('#tableRequired').append('<tr>' +
                            '<td id="viewGroup'+d.ID+'">'+d.ClassGroup+'</td>' +
                            '<td style="text-align: left;"><b id="viewCourse'+d.ID+'">'+d.MKCode+' - '+d.MKNameEng+'</b><br/><i>'+d.MKName+'</i></td>' +
                            '<td>'+mkType+'</td>' +
                            '<td>'+d.Credit+'</td>' +
                            '<td>'+viewSchedule+'</td>' +
                            '<td>'+buttonAct+'</td>' +
                            '</tr>');
                    } else {
                        $('#tableOption').append('<tr>' +
                            '<td>'+d.Semester+'</td>' +
                            '<td id="viewGroup'+d.ID+'">'+d.ClassGroup+'</td>' +
                            '<td style="text-align: left;"><b id="viewCourse'+d.ID+'">'+d.MKCode+' - '+d.MKNameEng+'</b><br/><i>'+d.MKName+'</i></td>' +
                            '<td>'+mkType+'</td>' +
                            '<td>'+d.Credit+'</td>' +
                            '<td>'+viewSchedule+'</td>' +
                            '<td>'+buttonAct+'</td>' +
                            '</tr>');
                    }
                }
            }



        });
    }


    // === ADD KRS ====
    $(document).on('click','.btnActionAdd',function () {

        var TokenBtn = $(this).attr('data-token');
        var dataToken = jwt_decode(TokenBtn);

        // console.log(dataToken);

        var data = {
            action : 'chekSeat',
            dataWhere : {
                SemesterID : dataToken['SemesterID'],
                ScheduleID : dataToken['ScheduleID'],
                CDID : dataToken['CDID']
            }
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudStudyPlanning';

        $.post(url,{token:token},function (jsonResult) {
            console.log(jsonResult);
            if(jsonResult.Status==1 || jsonResult.Status=='1'){

                var Group = $('#viewGroup'+dataToken['ScheduleID']).text();
                var Course = $('#viewCourse'+dataToken['ScheduleID']).text();

                $('#NotificationModal .modal-body').html('<input id="formDataTokenAvailableCourse" class="hide" readonly value="'+TokenBtn+'">' +
                    '<div style="text-align: center;"><h3 style="margin: 0px;">'+Group+'</h3><b>'+Course+'</b><hr/> ' +
                    '<button type="button" class="btn btn-primary" id="addbtnTOStdKRS">Add to study plan</button> | ' +
                    '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
                    '</div>');
            } else {
                loadAvailable();
                $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Class Room is full, please select another course</b><hr/> ' +
                    '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
                    '</div>');
            }

            $('#NotificationModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        });

    });

    $(document).on('click','#addbtnTOStdKRS',function () {
        var formDataTokenAvailableCourse = $('#formDataTokenAvailableCourse').val();
        var formDBStudent = $('#formDBStudent').val();
        var formMhswID = $('#formMhswID').val();

        if(formDataTokenAvailableCourse!='' && formDataTokenAvailableCourse!=null
            && formDBStudent!='' && formDBStudent!=null
            && formMhswID!='' && formMhswID!=null ){

            loading_button('#addbtnTOStdKRS');
            $('button[data-dismiss=modal]').prop('disabled',true);

            var dataInsert = jwt_decode(formDataTokenAvailableCourse,'UAP)(*');
            var data = {
                action : 'addAvailabelCourse',
                DBStudent : formDBStudent,
                MhswID : formMhswID,
                dataInsert : dataInsert
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudStudyPlanning';

            $.post(url,{token:token},function (jsonResult) {
                loadAvailable();
                setTimeout(function (a) {
                    $('#NotificationModal').modal('hide');
                },500);
            });


        }
    });


    // === Delete KRS ====
    $(document).on('click','.btnDelete',function () {
        
        if(confirm('Delete this course?')){
            var SKID = $(this).attr('data-id');
            var data = {
                action : 'deleteAvailabelCourse',
                SKID : SKID
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudStudyPlanning';

            $.post(url,{token:token},function (result) {
                loadAvailable();
            });
        }
        
    });
    
    

</script>