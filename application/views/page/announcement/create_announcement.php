
<div class="row" style="margin-top: 30px;">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Students</h4>
            </div>
            <div class="panel-body" style="min-height: 100px;">

                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="well">
                            <select class="form-control" id="fpStudent">
                                <option value="0">Not for student</option>
                                <option disabled>---------</option>
                                <option value="1">Class Of</option>
                                <option value="2">Course</option>
                                <option value="3">Any Student</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12"><hr/></div>
                </div>

                <div id="viewPanelStd"></div>

                <textarea class="form-control hide" id="dataStudent"></textarea>

            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Employees</h4>
            </div>
            <div class="panel-body" style="min-height: 100px;">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="well">
                            <select class="form-control" id="fpLecturer">
                                <option value="0">Not for Employees</option>
                                <option disabled>---------</option>
<!--                                <option value="1">Status Employees</option>-->
                                <option value="2">Any Employees</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12"><hr/></div>
                </div>


                <div id="viewPanelLec"></div>

                <textarea class="form-control hide" id="dataLecturer"></textarea>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="well">

            <div class="form-group">
                <button class="btn btn-default btn-default-primary" id="btnViewToStd"><span id="viewToStd">0</span> Students</button>
                <button class="btn btn-default btn-default-danger" id="btnViewToLec"><span id="viewToLec">0</span> Employees</button>
            </div>

            <div class="form-group">
                <label>Title</label>
                <input class="form-control" id="formTitle" maxlength="200" placeholder="Maximum input is 200 characters">
            </div>

            <div class="form-group">
                <label>Message</label>
                <textarea id="formMessage"></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <form id="fileAnnouncement" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                        <div class="form-group">
                            <label class="btn btn-sm btn-default btn-upload">
                                <i class="fa fa-upload margin-right"></i> File (.pdf | max 2 Mb)
                                <input type="file" id="formFileAnnc" name="userfile" class="upload_files"
                                       style="display: none;" accept="application/pdf">
                            </label>
                            <p class="help-block" id="viewNameFile"></p>
                            <p class="help-block" id="viewZise"></p>

                            <div id="alertFile"></div>


                        </div>
                    </form>

                </div>
                <div class="col-md-4 hide">
                    <div class="form-group">
                        <label>Start</label>
                        <input type="text" id="formStart" name="regular" class="form-control formcalendar">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Publish Until</label>
                        <input type="text" id="formEnd" name="regular" class="form-control formcalendar">
                    </div>
                </div>
            </div>


            <div style="text-align: right;">
                <button class="btn btn-primary" id="btnSubmitAnnouncement">Submit</button>
            </div>

        </div>
    </div>
</div>

<script>

    $(document).ready(function () {



        load2Student();
        load2Lecturer();
        $('#formMessage').summernote({
            placeholder: 'Text your announcement',
            tabsize: 2,
            height: 300,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });
        $('.formcalendar').datepicker({
                showOtherMonths:true,
                autoSize: true,
                dateFormat: 'dd MM yy',
                minDate: new Date(moment().format('YYYY-MM-DD')),
                onSelect : function () {
                    // var data_date = $(this).val().split(' ');
                    // var nextelement = $(this).attr('nextelement');
                    // nextDatePick(data_date,nextelement);
                }
            });

        $('#formStart').datepicker('setDate',new Date(moment().format('YYYY-MM-DD')));
    });

    // ===== STUDENT =====
    $('#fpStudent').change(function () {
        load2Student();
    });

    function load2Student() {
        var fpStudent = $('#fpStudent').val();

        var viewPanel = '<div class="row">' +
            '                    <div class="col-md-12" style="background-color: #f5f5f5;text-align: center;padding: 15px;"><h3>--- Not For Student ---</h3></div>' +
            '                </div>';

        $('#dataStudent').val('');
        $('#viewToStd').html(0);

        if(fpStudent==1 || fpStudent=='1'){
            viewPanel ='<div class="row">' +
                '                    <div class="col-md-3">' +
                '                        <div class="form-group">' +
                '                            <label>Class Of</label>' +
                '                            <select class="form-control form-filter-student" id="filterCurriculum"></select>' +
                '                        </div>' +
                '                    </div>' +
                '                    <div class="col-md-6">' +
                '                        <div class="form-group">' +
                '                            <label>Programme Study</label>' +
                '                            <select class="form-control form-filter-student" id="filterBaseProdi"></select>' +
                '                        </div>' +
                '                    </div>' +
                '                    <div class="col-md-3 hide">' +
                '                        <div class="form-group">' +
                '                            <label>Status Students</label>' +
                '                            <select class="form-control" id="filterStatus"></select>' +
                '                        </div>' +
                '                    </div>' +
                '                </div>';

            $('#viewPanelStd').html(viewPanel);

            $('#filterCurriculum').append('<option disabled selected>Select Class Of</option><option disabled>--------</option>');
            $('#filterBaseProdi').append('<option value="0">All Programme Study</option>');

            loadSelectOptionClassOf_ASC('#filterCurriculum','');
            loadSelectOptionBaseProdi('#filterBaseProdi','');
            // loadSelectOptionStatusStudent('#filterStatus',3);

            // console.log($('#filterStatus').val());

            loadStudent2Annc();

        }
        else if(fpStudent==2 || fpStudent=='2'){
            viewPanel = '<div class="row">' +
                '                    <div class="col-md-4">' +
                '                        <div class="form-group">' +
                '                            <label>Semester</label>' +
                '                            <select class="form-control" id="filterSemester">' +
                '                               ' +
                '                            </select>' +
                '                        </div>' +
                '                    </div>' +
                '                    <div class="col-md-8">' +
                '                        <div class="form-group">' +
                '                            <label>Class Group</label>' +
                '                            <div id="viewGroup"></div>' +
                '                        </div>' +
                '                    </div>' +
                '                </div>';

            $('#viewPanelStd').html(viewPanel);

            loSelectOptionSemester('#filterSemester','');

            setTimeout(function () {
                loadClassGroup();
            },500);

        }
        else if(fpStudent==3 || fpStudent=='3'){

            viewPanel = '<div class="row">' +
                '                    <div class="col-md-12">' +
                '                        <div class="form-group">' +
                '                            <label>Any Student</label>' +
                '                            <input id="getStudent" placeholder="NIM, Student Name" class="form-control">' +
                '                        </div>' +
                '                       <div id="viewListStdC"></div>' +
                '                    </div>' +
                '                </div>';

            $('#viewPanelStd').html(viewPanel);

        }
        else {
            $('#viewPanelStd').html(viewPanel);

        }

    }

    // View Student
    $('#btnViewToStd').click(function () {

        var dataStudent = $('#dataStudent').val();
        var ds = (dataStudent!='' && dataStudent!=null) ? JSON.parse(dataStudent) : [];

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">List Student</h4>');
        $('#GlobalModal .modal-body').html('<table class="table">' +
            '    <thead>' +
            '    <tr>' +
            '        <th style="text-align: center;width: 1%;">No</th>' +
            '        <th style="text-align: center;width: 15%;">NIM</th>' +
            '        <th style="text-align: center;">Name</th>' +
            '        <th style="text-align: center;width: 5%;"">Act.</th>' +
            '    </tr>' +
            '    </thead>' +
            '    <tbody id="viewStd"></tbody>' +
            '</table>');

        if(ds.length>0){
            var no = 1;
            $.each(ds,function (i,v) {
                $('#viewStd').append('<tr id="trModalStd_'+v.NPM+'">' +
                    '<td style="text-align: center;">'+no+'</td>' +
                    '<td style="text-align: center;">'+v.NPM+'</td>' +
                    '<td>'+v.Name+'</td>' +
                    '<td id="tdModalStd_'+v.NPM+'"><button class="btn btn-sm btn-danger rmvStd" data-name="'+v.Name+'" data-nim="'+v.NPM+'"><i class="fa fa-times-circle"></i></button></td>' +
                    '</tr>');

                no+=1;
            });
        } else {
            $('#viewStd').append('<tr>' +
                '<td colspan="4" style="text-align: center;">Student Not Yet</td>' +
                '</tr>');
        }

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    // Load Class Group
    $(document).on('change','#filterSemester',function () {
        loadClassGroup();
    });

    // Load Student By Class Group
    $(document).on('change','#filterClassGroup',function () {

        var filterSemester = $('#filterSemester').val();
        var filterClassGroup = $('#filterClassGroup').val();

        if(filterSemester!='' && filterSemester!=null && filterClassGroup!='' && filterClassGroup!=null){
            var SemesterID = filterSemester.split('.')[0];
            var ScheduleID = filterClassGroup.split('.')[0];

            var url = base_url_js+'api/__crudAttendance';
            var token = jwt_encode(
                {
                    action : 'getStdAttendance',
                    SemesterID : SemesterID ,
                    ScheduleID : ScheduleID
                },'UAP)(*');

            $('#dataStudent').val('');
            $('#viewToStd').html(0);

            $.post(url,{token:token},function (jsonResult) {
                console.log(jsonResult);
                var arrStd = [];
                if(jsonResult.Student.length>0){
                    $.each(jsonResult.Student,function (v, i) {
                        var arr = {
                            Name : i.Name,
                            NPM : i.NPM
                        };
                        arrStd.push(arr);
                    });

                    $('#dataStudent').val(JSON.stringify(arrStd));
                    $('#viewToStd').html(arrStd.length);
                }
            });
        }
    });

    function loadClassGroup() {
        $('#viewGroup').html('');
        $('#viewGroup').html('<select class="select2-select-00 full-width-fix"' +
            '                                size="5" id="filterClassGroup"><option></option></select>');
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){
            var SemesterID = filterSemester.split('.')[0];
            loadSelectOptionClassGroupAttendance(SemesterID,'#filterClassGroup','');
            $('#filterClassGroup').select2({allowClear: true});
        }
    }

    $(document).on('change','.form-filter-student',function () {
        loadStudent2Annc();
    });
    
    function loadStudent2Annc() {

        var fpStudent = $('#fpStudent').val();
        var filterCurriculum = $('#filterCurriculum').val();
        if((fpStudent==1 || fpStudent=='1') && (filterCurriculum!=null && filterCurriculum!='')){


            var filterBaseProdi = $('#filterBaseProdi').val();
            // var Status = $('#filterStatus').val();

            var Year = (filterCurriculum!=0) ? filterCurriculum.split('.')[1] : filterCurriculum;
            var ProdiID = (filterBaseProdi!=0) ? filterBaseProdi.split('.')[0] : filterBaseProdi;

            var data = {
                action : 'readStudent2Annc',
                Year : Year,
                ProdiID : ProdiID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api2/__crudAnnouncement';

            $.post(url,{token:token},function (jsonResult) {
                $('#dataStudent').val(JSON.stringify(jsonResult));
                $('#viewToStd').html(jsonResult.length);
            });

        }
    }

    $(document).on('keyup','#getStudent',function () {
        var getStudent = $('#getStudent').val();
        var std = getStudent.trim();
        if(std.length>=4){

            var data = {
                action : 'getStudentServerSide',
                Key : std
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api2/__crudAnnouncement';

            $.post(url,{token:token},function (jsonResult) {

                $('#viewListStdC').html('<table class="table">' +
                    '    <thead>' +
                    '    <tr>' +
                    '        <th style="width: 1%;">No</th>' +
                    '        <th style="width: 10%;">NIM</th>' +
                    '        <th>Name</th>' +
                    '        <th style="width: 10%;">Act.</th>' +
                    '    </tr>' +
                    '    </thead>' +
                    '    <tbody id="viewCStd"></tbody>' +
                    '</table>');

                if(jsonResult.length>0){

                    var dataStudent = $('#dataStudent').val();
                    var ds = (dataStudent!='' && dataStudent!=null) ? JSON.parse(dataStudent) : [];

                    var no =1;
                    $.each(jsonResult,function (i,v) {

                        var obj = ds.find(o => o.NPM === v.NPM);
                        var indxOf = ds.indexOf(obj);

                        var btnAct = (indxOf!=-1)
                            ? '<button class="btn btn-sm btn-danger rmvStd" data-name="'+v.Name+'" data-nim="'+v.NPM+'"><i class="fa fa-times-circle"></i></button>'
                            : '<button class="btn btn-sm btn-success addStd" data-name="'+v.Name+'" data-nim="'+v.NPM+'"><i class="fa fa-download"></i></button>';

                        $('#viewCStd').append('<tr>' +
                            '<td>'+no+'</td>' +
                            '<td>'+v.NPM+'</td>' +
                            '<td>'+v.Name+'</td>' +
                            '<td id="td_'+v.NPM+'">'+btnAct+'</td>' +
                            '</tr>');
                        no += 1;
                    });
                } else {
                    $('#viewCStd').append('<tr><td colspan="4">Not yet student</td></tr>');
                }

                // $('#dataStudent').val(JSON.stringify(jsonResult));
                // $('#viewToStd').html(jsonResult.length);
            });


        }
    });

    $(document).on('click','.addStd',function () {

        $('.addStd,.rmvStd').prop('disabled',true);

        var Name = $(this).attr('data-name');
        var NPM = $(this).attr('data-nim');

        var dataStudent = $('#dataStudent').val();
        var ds = (dataStudent!='' && dataStudent!=null) ? JSON.parse(dataStudent) : [];

        var arr = {
            Name : Name,
            NPM : NPM
        };
        ds.push(arr);

        $('#dataStudent').val(JSON.stringify(ds));
        $('#viewToStd').html(ds.length);

        // Action In Modal
        $('#trModalStd_'+NPM).css('background','#ffffff');
        $('#tdModalStd_'+NPM).html('<button class="btn btn-sm btn-danger rmvLec" data-name="'+Name+'" data-nim="'+NPM+'"><i class="fa fa-times-circle"></i></button>');

        setTimeout(function () {
            $('.addStd,.rmvStd').prop('disabled',false);
            $('#td_'+NPM).html('<button class="btn btn-sm btn-danger rmvStd" data-name="'+Name+'" data-nim="'+NPM+'"><i class="fa fa-times-circle"></i></button>');
        },500);
    });

    $(document).on('click','.rmvStd',function () {
        var Name = $(this).attr('data-name');
        var NPM = $(this).attr('data-nim');
        var dataStudent = $('#dataStudent').val();
        var ds = (dataStudent!='' && dataStudent!=null) ? JSON.parse(dataStudent) : [];

        if(ds.length>0){

            $('.addStd,.rmvStd').prop('disabled',true);

            var newArr = [];
            for(var i=0;i<ds.length;i++){
                if(ds[i].NPM!=NPM){
                    newArr.push(ds[i]);
                } else {
                    $('#td_'+ds[i].NPM).html('<button class="btn btn-sm btn-success addStd" data-name="'+Name+'" data-nim="'+NPM+'"><i class="fa fa-download"></i></button>');
                }
            }
            $('#dataStudent').val(JSON.stringify(newArr));
            $('#viewToStd').html(newArr.length);

            // Action In Modal
            $('#trModalStd_'+NPM).css('background','#ffdfdf');
            $('#tdModalStd_'+NPM).html('<button class="btn btn-sm btn-success addStd" data-name="'+Name+'" data-nim="'+NPM+'"><i class="fa fa-download"></i></button>');

            setTimeout(function () {
                $('.addStd,.rmvStd').prop('disabled',false);
            },500);
        }

    });

    // ++++++++ btnSubmitAnnouncement ++++
    $('#formStart').html(function () {

    });

    // ======= Lecturer =====
    $('#fpLecturer').change(function () {
        load2Lecturer();
    });

    $('#btnViewToLec').click(function () {

        var dataLecturer = $('#dataLecturer').val();
        var dl = (dataLecturer!='' && dataLecturer!=null) ? JSON.parse(dataLecturer) : [];

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">List Lecturer</h4>');
        $('#GlobalModal .modal-body').html('<table class="table">' +
            '    <thead>' +
            '    <tr>' +
            '        <th style="text-align: center;width: 1%;">No</th>' +
            '        <th style="text-align: center;width: 15%;">NIP</th>' +
            '        <th style="text-align: center;">Name</th>' +
            '        <th style="text-align: center;width: 1%;">Act.</th>' +
            '    </tr>' +
            '    </thead>' +
            '    <tbody id="viewStd"></tbody>' +
            '</table>');

        if(dl.length>0){
            var no = 1;
            $.each(dl,function (v,i) {
                $('#viewStd').append('<tr id="trModal_'+i.NIP+'">' +
                    '<td style="text-align: center;">'+no+'</td>' +
                    '<td style="text-align: center;">'+i.NIP+'</td>' +
                    '<td>'+i.Name+'</td>' +
                    '<td id="tdModal_'+i.NIP+'"><button class="btn btn-sm btn-danger rmvLec" data-name="'+i.Name+'" data-nip="'+i.NIP+'"><i class="fa fa-times-circle"></i></button></td>' +
                    '</tr>');

                no+=1;
            });
        } else {
            $('#viewStd').append('<tr>' +
                '<td colspan="4" style="text-align: center;">Lecturer Not Yet</td>' +
                '</tr>');
        }

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    function load2Lecturer() {
        var fpLecturer = $('#fpLecturer').val();

        var viewPanel = '<div class="row">' +
            '                    <div class="col-md-12" style="background-color: #f5f5f5;text-align: center;padding: 15px;"><h3>--- Not For Employee ---</h3></div>' +
            '                </div>';

        $('#dataLecturer').val('');
        $('#viewToLec').html(0);

        if(fpLecturer==1 || fpLecturer=='1'){
            viewPanel = '<div class="row">' +
                '                    <div class="col-md-6 col-md-offset-3">' +
                '                        <div class="form-group">' +
                '                            <label>Status Employees</label>' +
                '                            <select class="form-control"></select>' +
                '                        </div>' +
                '                    </div>' +
                '                </div>';
            $('#viewPanelLec').html(viewPanel);
        }
        else if(fpLecturer==2 || fpLecturer=='2'){
            viewPanel = '<div class="row">' +
                '                    <div class="col-md-12">' +
                '                        <div class="form-group">' +
                '                            <label>Any Employees</label>' +
                '                            <input id="getLecturer" placeholder="NIP, Employee Name" class="form-control">' +
                '                        </div>' +
                '                       <div id="viewListLecC"></div>' +
                '                    </div>' +
                '                </div>';
            $('#viewPanelLec').html(viewPanel);
        }
        else {
            $('#viewPanelLec').html(viewPanel);
        }


    }

    $(document).on('keyup','#getLecturer',function () {
        var getLecturer = $('#getLecturer').val();
        var lec = getLecturer.trim();
        if(lec.length>=4){
            var data = {
                action : 'getLecturerServerSide',
                Key : lec
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api2/__crudAnnouncement';

            $.post(url,{token:token},function (jsonResult) {
                $('#viewListLecC').html('<table class="table">' +
                    '    <thead>' +
                    '    <tr>' +
                    '        <th style="width: 1%;">No</th>' +
                    '        <th style="width: 10%;">NIP</th>' +
                    '        <th>Name</th>' +
                    '        <th style="width: 10%;">Act.</th>' +
                    '    </tr>' +
                    '    </thead>' +
                    '    <tbody id="viewCLec"></tbody>' +
                    '</table>');
                if(jsonResult.length>0){

                    var dataLecturer = $('#dataLecturer').val();
                    var dl = (dataLecturer!='' && dataLecturer!=null) ? JSON.parse(dataLecturer) : [];

                    var no =1;
                    $.each(jsonResult,function (i,v) {

                        var obj = dl.find(o => o.NIP === v.NIP);
                        var indxOf = dl.indexOf(obj);

                        var btnAct = (indxOf!=-1)
                            ? '<button class="btn btn-sm btn-danger rmvLec" data-name="'+v.Name+'" data-nip="'+v.NIP+'"><i class="fa fa-times-circle"></i></button>'
                            : '<button class="btn btn-sm btn-success addLec" data-name="'+v.Name+'" data-nip="'+v.NIP+'"><i class="fa fa-download"></i></button>';

                        $('#viewCLec').append('<tr>' +
                            '<td>'+no+'</td>' +
                            '<td>'+v.NIP+'</td>' +
                            '<td>'+v.Name+'</td>' +
                            '<td id="td_'+v.NIP+'">'+btnAct+'</td>' +
                            '</tr>');
                        no += 1;
                    });
                } else {
                    $('#viewCLec').append('<tr><td colspan="4">Not yet student</td></tr>');
                }
            });
        }
    });

    $(document).on('click','.addLec',function () {

        $('.addLec,.rmvLec').prop('disabled',true);

        var Name = $(this).attr('data-name');
        var NIP = $(this).attr('data-nip');

        var dataLecturer = $('#dataLecturer').val();
        var dl = (dataLecturer!='' && dataLecturer!=null) ? JSON.parse(dataLecturer) : [];

        var arr = {
            Name : Name,
            NIP : NIP
        };
        dl.push(arr);

        $('#dataLecturer').val(JSON.stringify(dl));
        $('#viewToLec').html(dl.length);

        // Action In Modal
        $('#trModal_'+NIP).css('background','#ffffff');
        $('#tdModal_'+NIP).html('<button class="btn btn-sm btn-danger rmvLec" data-name="'+Name+'" data-nip="'+NIP+'"><i class="fa fa-times-circle"></i></button>');

        setTimeout(function () {
            $('.addLec,.rmvLec').prop('disabled',false);
            $('#td_'+NIP).html('<button class="btn btn-sm btn-danger rmvLec" data-name="'+Name+'" data-nip="'+NIP+'"><i class="fa fa-times-circle"></i></button>');
        },500);
    });

    $(document).on('click','.rmvLec',function () {

        var Name = $(this).attr('data-name');
        var NIP = $(this).attr('data-nip');
        var dataLecturer = $('#dataLecturer').val();
        var dl = (dataLecturer!='' && dataLecturer!=null) ? JSON.parse(dataLecturer) : [];

        if(dl.length>0){

            $('.addLec,.rmvLec').prop('disabled',true);

            var newArr = [];
            for(var i=0;i<dl.length;i++){
                if(dl[i].NIP!=NIP){
                    newArr.push(dl[i]);
                } else {
                    $('#td_'+dl[i].NIP).html('<button class="btn btn-sm btn-success addLec" data-name="'+Name+'" data-nip="'+NIP+'"><i class="fa fa-download"></i></button>');
                }
            }
            $('#dataLecturer').val(JSON.stringify(newArr));
            $('#viewToLec').html(newArr.length);

            // Action In Modal
            $('#trModal_'+NIP).css('background','#ffdfdf');
            $('#tdModal_'+NIP).html('<button class="btn btn-sm btn-success addLec" data-name="'+Name+'" data-nip="'+NIP+'"><i class="fa fa-download"></i></button>');

            setTimeout(function () {
                $('.addLec,.rmvLec').prop('disabled',false);
            },500);
        }

    });

    // =================================================

    $('#btnSubmitAnnouncement').click(function () {

        var dataStudent = $('#dataStudent').val();
        var ds = (dataStudent!='' && dataStudent!=null) ? JSON.parse(dataStudent) : [];

        var dataLecturer = $('#dataLecturer').val();
        var dl = (dataLecturer!='' && dataLecturer!=null) ? JSON.parse(dataLecturer) : [];

        var formTitle = $('#formTitle').val();
        var formMessage = $('#formMessage').val();

        var formStart = $('#formStart').datepicker("getDate");
        var formEnd = $('#formEnd').datepicker("getDate");


        if(formTitle!=null && formTitle!='' && formMessage!=null && formMessage!='' && formStart!=null && formEnd!=null){

            if(ds.length>0 || dl.length>0){

                loading_button('#btnSubmitAnnouncement');

                var Start = moment(formStart).format('YYYY-MM-DD');
                var End = moment(formEnd).format('YYYY-MM-DD');

                var data = {
                    action : 'createAnnouncement',
                    dataAnnc : {
                        Title : formTitle,
                        Message : formMessage,
                        Start : Start,
                        End : End,
                        CreatedBy : sessionNIP,
                        CreatedAt : dateTimeNow()
                    },
                    anncStd : ds,
                    anncLec : dl
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api2/__crudAnnouncement';

                $.post(url,{token:token},function (result) {

                    if(ds.length>0){
                        var listNPM = [];
                        $.each(ds,function (i,v) {
                            listNPM.push(v.NPM);
                        });
                        socket.emit('mobile_notif', {
                            Title: formTitle,
                            Message: $('#formMessage').val().replace(/<\/?[^>]+(>|$)/g, "").trim(),
                            dataUser : listNPM
                        });
                    }

                    // cek apakah file kosong atau tidak
                    var IDAnnc = result;

                    var input = $('#formFileAnnc');
                    var file = input[0].files[0];
                    // cek apakah file lebih dari 2 mb ?
                    var fileSize = (parseFloat(file.size) / 1000000).toFixed(2);

                    if(input[0].files.length>0 && fileSize<=2){

                        var fileName = sessionNIP+'_'+moment().unix()+'.pdf';
                        var formData = new FormData( $("#fileAnnouncement")[0]);

                        var url = base_url_js+'announcement/upload_files?IDAnnc='+IDAnnc+'&f='+fileName;

                        $.ajax({
                            url : url,  // Controller URL
                            type : 'POST',
                            data : formData,
                            async : false,
                            cache : false,
                            contentType : false,
                            processData : false,
                            success : function(data) {
                                toastr.success('Announcement Created','Success');
                                setTimeout(function () {
                                    window.location.href = '';
                                },500);
                            }
                        });
                    }
                    else {
                        toastr.success('Announcement Created','Success');
                        setTimeout(function () {
                            window.location.href = '';
                        },500);
                    }





                });
            } else {
                toastr.error('User Not Yet','Error');
            }



        } else {
            toastr.error('All form is required','Error');
        }

    });

    $('.upload_files').change(function () {

        var input = $('#formFileAnnc');
        var file = input[0].files[0];

        $('#btnSubmitAnnouncement').prop('disabled',true);

        if(file.type != 'application/pdf'){
            alert('The file must be PDF');
        } else {
            var fileNameOri = file.name;
            var fileName = fileNameOri.split(' ').join('_');
            var fileSize = (parseFloat(file.size) / 1000000).toFixed(2);
            $('#viewNameFile').html(fileName);
            $('#viewZise').html('Size : '+fileSize+' Mb');
            $('#alertFile').html('');
            if(fileSize>2){
                alert('File lebih dari 2 MB');
                $('#alertFile').html('<div class="alert alert-danger" role="alert">File lebih dari 2 MB, jika di submit maka file <b>tidak akan diunggah</b></div>');
            }

            $('#btnSubmitAnnouncement').prop('disabled',false);




        }

    });


</script>