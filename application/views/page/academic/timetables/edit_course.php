
<style>
    .table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td, .table tbody>tr>td, .table tfoot>tr>td {
        border-top: none;
    }
    .table tbody+tbody {
        border-top: 1px solid #ddd;
    }
    #tableCourse thead tr th, #tableTeamTeaching thead tr th {
        text-align: center;
        background: #607d8b;
        color: #FFFFFF;
    }
    #tableCourse tbody tr td, #tableTeamTeaching tbody tr td {
         text-align: center;
     }

    #formProgramsCampusID, #formBaseProdi {
        max-width: 250px;
    }
</style>

<div class="row">


    <div class="col-md-8 col-md-offset-2">
        <a href="<?php echo base_url('academic/timetables/list'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left margin-right"></i> Back to List</a>
        <hr/>
        <table class="table">
            <tr>
                <td style="width: 20%;">Academic Year</td>
                <td style="width: 1%;">:</td>
                <td>
                    <b id="viewSemester">-</b>
                    <input id="formSemesterID" class="hide" type="hidden" readonly/>
                    <input id="formScheduleID" class="hide" type="hidden" readonly/>
                </td>
            </tr>
            <tr>
                <td>Program</td>
                <td>:</td>
                <td>
                    <select class="form-control" id="formProgramsCampusID"></select>
                </td>
            </tr>
            <tbody>
            <tr>
                <td>Programme Study</td>
                <td>:</td>
                <td>
                    <select class="form-control" id="formBaseProdi">
                        <option value="" selected disabled>--- Select Programme Study ---</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Course</td>
                <td>:</td>
                <td>
                    <div id="dataMK"></div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><button class="btn btn-sm btn-default btn-default-success" id="btnAddNewCourse">Add Course</button></td>
            </tr>
            </tbody>
        </table>

        <hr/>

        <table class="table table-bordered table-striped" id="tableCourse">
            <thead>
            <tr>
                <th rowspan="2">Course</th>
                <th rowspan="2" style="width: 20%;">Prodi</th>
                <th rowspan="2" style="width: 7%;">Semester</th>
                <th rowspan="2" style="width: 7%;">Offer to <br/> <small>(Semester)</small></th>
                <th colspan="2" style="width: 16%;">Student</th>
                <th rowspan="2" style="width: 7%;">Action</th>
            </tr>
            <tr>
                <th style="width: 8%;">Plan</th>
                <th style="width: 8%;">Apprv</th>
            </tr>
            </thead>
            <tbody id="dataRow"></tbody>
        </table>

        <hr/>
        <table class="table">
            <tr>
                <td style="width: 20%;">Class Group</td>
                <td style="width: 1%;">:</td>
                <td><input class="form-control" id="formClassGroup" style="max-width: 130px;"/> </td>
            </tr>
            <tr>
                <td>Coordinator</td>
                <td>:</td>
                <td>
                    <select class="select2-select-00 full-width-fix form-jadwal-edit-sc"
                            size="5" id="formCoordinator">
                        <option value=""></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Team Teaching</td>
                <td>:</td>
                <td>
                    <select class="select2-select-00 full-width-fix form-jadwal-edit-sc"
                            size="5" multiple id="formTeamTeaching"></select>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div style="text-align: right;">
                        <button class="btn btn-success" id="btnSaveEditInfo">Save</button>
                    </div>
                </td>
            </tr>

        </table>
    </div>

</div>

<script>
    $(document).ready(function () {
        window.SemesterAntara = 0;
        loadEditCourseSchedule();
        loadEditCourse();
        loadSelectOptionBaseProdi('#formBaseProdi');
    });


    // ==== Control Edit Course ====

    $(document).on('change','#formBaseProdi',function () {

        var Prodi = $(this).val();
        if(Prodi!=''){
            var ProdiID = Prodi.split('.')[0];
            getCourseOfferings(ProdiID,'');
        }

    });

    $('#btnAddNewCourse').click(function () {
        var formScheduleID = $('#formScheduleID').val();
        var formBaseProdi = $('#formBaseProdi').val();
        var formMataKuliah = $('#formMataKuliah').val();

        if(formBaseProdi!='' && formBaseProdi!=null &&
            formMataKuliah!='' && formMataKuliah!=null){

            var ProdiID = formBaseProdi.split('.')[0];
            var CDID = formMataKuliah.split('|')[0];
            var MKID = formMataKuliah.split('|')[1];

            var data = {
                action : 'checktoAddNewCourse',
                dataWhere : {
                    ScheduleID : formScheduleID,
                    ProdiID : ProdiID,
                    CDID : CDID,
                    MKID : MKID
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudSchedule';
            $.post(url,{token:token},function (jsonResult) {
                if(jsonResult.Status==0 || jsonResult.Status=='0'){
                    toastr.warning('This course exist in schedule','Warning');
                } else {
                    loadEditCourse();
                    toastr.success('Data saved','Success');
                }
            });

        }

    });

    $(document).on('click','.btnDelEditCourse',function () {
        var SDCID = $(this).attr('data-sdc');
        var TotalCourse = $(this).attr('data-totalcourse');

        if(parseInt(TotalCourse)==1){
            $('#NotificationModal .modal-body').html('' +
                '<div style="text-align: center;"><div style="background:lightyellow;padding:10px;border:1px solid red;margin-bottom:15px;"><span style="color:red;">This course cannot delete</span></div> ' +
                '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
                '</div>');
        } else {
            $('#NotificationModal .modal-body').html('<div style="text-align: center;">' +
                '<div style="background:lightyellow;padding:10px;border:1px solid red;margin-bottom:15px;"><span style="color:red;">If you delete this data, then the data student in the study planning will be deleted too</span></div> ' +
                '<button type="button" class="btn btn-danger" id="btnActionDeleteEditCourse" data-id="'+SDCID+'" style="margin-right: 5px;">Yes</button>' +
                '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
                '</div>');
        }

        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });

    });

    $(document).on('click','#btnActionDeleteEditCourse',function () {
        var SemesterID = parseInt("<?php echo $SemesterID ?>");
        var ScheduleID = parseInt("<?php echo $ScheduleID ?>");
        var SDCID = $(this).attr('data-id');

        if(SemesterID!='' && SemesterID!=null &&
            ScheduleID!='' && ScheduleID!=null &&
            SDCID!='' && SDCID!=null){

            loading_button('#btnActionDeleteEditCourse');
            $('button[data-dismiss=modal]').prop('disabled',true);

            var data = {
                action : 'deleteInEditCourse',
                SemesterID : SemesterID,
                ScheduleID : ScheduleID,
                SDCID : SDCID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudSchedule';
            $.post(url,{token:token},function (result) {
                loadEditCourse();
                setTimeout(function () {
                    $('#NotificationModal').modal('hide');
                },500);
            });
        }

    });
    
    function loadEditCourse() {

        var SemesterID = parseInt("<?php echo $SemesterID ?>");
        var ScheduleID = parseInt("<?php echo $ScheduleID ?>");

        var data = {
            action : 'loadEditCourse',
            SemesterID : SemesterID,
            ScheduleID : ScheduleID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudSchedule';

        $.post(url,{token:token},function (jsonResult) {

           if(jsonResult.ScheduleDetails.length>0){
               $('#dataRow').empty();
               for(var i=0;i<jsonResult.ScheduleDetails.length;i++){
                   var d = jsonResult.ScheduleDetails[i]
                   $('#dataRow').append('<tr>' +
                       '<td style="text-align: left;"><b>'+d.MKNameEng+'</b><br/><i>'+d.MKNameEng+'</i></td>' +
                       '<td style="text-align: left;">'+d.Prodi+'</td>' +
                       '<td>'+d.Semester+'</td>' +
                       '<td style="background: lightyellow;">'+d.Offerto+'</td>' +
                       '<td>'+d.TotalStd_Plan.length+'</td>' +
                       '<td>'+d.TotalStd_Approve.length+'</td>' +
                       '<td><button class="btn btn-sm btn-default btn-default-danger btnDelEditCourse" data-totalcourse="'+jsonResult.ScheduleDetails.length+'" data-sdc="'+d.SDCID+'"><i class="fa fa-trash"></i></button></td>' +
                       '</tr>');
               }
           }

        });

    }

    function getCourseOfferings(ProdiID,divNum) {
        var url = base_url_js+'api/__crudCourseOfferings';
        var SemesterID = $('#formSemesterID').val();
        var data = {
            action : 'readToSchedule',
            formData : {
                SemesterID : SemesterID,
                ProdiID : ProdiID,
                IsSemesterAntara : ''+SemesterAntara
            }
        };
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {

            // console.log(jsonResult);

            if(jsonResult.length>0){
                $('#dataMK'+divNum).html('<select class="select2-select-00 full-width-fix" size="5" id="formMataKuliah'+divNum+'">' +
                    '                        <option value=""></option>' +
                    '                    </select>');

                for(var i=0;i<jsonResult.length;i++){
                    var semester = jsonResult[i].Offerings.Semester;

                    var mk = jsonResult[i].Details;
                    for(var m=0;m<mk.length;m++){
                        var dataMK = mk[m];
                        var asalSmt = (semester!=dataMK.Semester) ? '('+dataMK.Semester+')' : '';
                        var schDisabled = (dataMK.ScheduleID!="") ? '' : '';
                        var schMK = (dataMK.ScheduleID!="") ? 'highlighted' : '';
                        $('#formMataKuliah'+divNum).append('<option value="'+dataMK.CDID+'|'+dataMK.ID+'|'+dataMK.TotalSKS+'" class="'+schMK+'" '+schDisabled+'>Smt '+semester+' '+asalSmt+' - '+dataMK.MKCode+' | '+dataMK.MKNameEng+'</option>');
                    }

                    $('#formMataKuliah'+divNum).append('<option disabled>-------</option>');

                }

                $('#formMataKuliah'+divNum).select2({allowClear: true});
            } else {
                $('#dataMK'+divNum).html('<b>No Course To Offerings</b>')
            }
        });
    }

    // ===========================



    // ===== Control Edit Group, Coordinator, Team Teaching

    $('#btnSaveEditInfo').click(function () {

        var SemesterID = parseInt("<?php echo $SemesterID ?>");
        var ScheduleID = parseInt("<?php echo $ScheduleID ?>");

        var formClassGroup = $('#formClassGroup').val();
        var formCoordinator = $('#formCoordinator').val();
        var formTeamTeaching = $('#formTeamTeaching').val();

        if(formClassGroup!='' && formClassGroup!=null &&
            formCoordinator!='' && formCoordinator!=null){

            loading_button('#btnSaveEditInfo');

            var TeamTeaching = '0';
            var dataTeamTeaching = [];
            if(formTeamTeaching!=null){
                TeamTeaching = '1';
                for(var i=0;i<formTeamTeaching.length;i++){
                    var d = formTeamTeaching[i];
                    var arr = {
                        ScheduleID : ScheduleID,
                        NIP : d,
                        Status : '0'
                    };
                    dataTeamTeaching.push(arr);
                }
            }

            var data = {
                action : 'updateInfoInEditCourse',
                SemesterID : SemesterID,
                ScheduleID : ScheduleID,
                UpdateForm : {
                    ClassGroup : formClassGroup,
                    Coordinator : formCoordinator,
                    TeamTeaching : TeamTeaching
                },
                dataTeamTeaching : dataTeamTeaching
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudSchedule';
            $.post(url,{token:token},function (jsonResult) {

                $('#formClassGroup').css('border','1px solid #ccc');
                setTimeout(function () {
                    if(jsonResult.Status==0 || jsonResult.Status=='0'){
                        toastr.warning('Class Group is exist','Warning');
                        $('#formClassGroup').css('border','1px solid red');
                    } else {
                        toastr.success('Update data saved','Success');
                    }
                    $('#btnSaveEditInfo').prop('disabled',false).html('Save');
                },500);
            });

        }

    });

    function loadEditCourseSchedule() {

        var SemesterID = parseInt("<?php echo $SemesterID ?>");
        var ScheduleID = parseInt("<?php echo $ScheduleID ?>");

        var data = {
            action : 'loadEditCourseSchedule',
            SemesterID : SemesterID,
            ScheduleID : ScheduleID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudSchedule';

        $.post(url,{token:token},function (jsonResult) {

            var s = jsonResult.Schedule[0];
            loadSelectOptionConf('#formProgramsCampusID','programs_campus',s.ProgramsCampusID);
            $('#viewSemester').text(s.SemesterName);
            $('#formSemesterID').val(s.SemesterID);
            $('#formClassGroup').val(s.ClassGroup);
            $('#formScheduleID').val(s.ScheduleID);

            loadSelectOptionLecturersSingle('#formCoordinator',s.Coordinator);
            $('#formCoordinator').select2({allowClear: true});

            var team = '';
            if(s.TeamTeaching==1) {
                team = s.detailTeamTeaching;
            }

            loadSelectOptionLecturersSingle('#formTeamTeaching',team);
            $('#formTeamTeaching').select2({allowClear: true});


        });

    }



</script>