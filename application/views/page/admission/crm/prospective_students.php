
<style>
    #formDateOfBirth {
        color: #333;
        background-color: #fff;
    }
</style>

<div class="row">
    <div class="col-md-3">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create / Update Form CRM</h4>
            </div>
            <div class="panel-body">

                <div class="alert alert-info" role="alert">
                    <b style="text-align: center;">Year - <span id="viewPeriod"></span></b>
                    <input class="hide" id="formID">
                    <input class="hide" id="formPeriodID">
                </div>

                <div class="form-group">
                    <label>Marketing Activity</label>
                    <select class="form-control" id="formMarketingActivity"></select>
                </div>
                <div class="form-group">
                    <label>Select Team</label>
                    <select class="form-control" id="formTeam"></select>
                </div>

                <hr/>

                <div class="form-group">
                    <label>Full Name</label>
                    <input class="form-control" id="formName"/>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input typeof="email" class="form-control" id="formEmail"/>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input typeof="number" data-form="phone" class="form-control" id="formPhone"/>
                </div>
                <div class="form-group">
                    <label>Line ID</label>
                    <input typeof="text" class="form-control" id="formLineID"/>
                </div>
            </div>
            <div class="panel-footer">
                <div style="text-align: right;">
                    <button class="btn btn-success" id="btnSavePS">Save</button>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-9">

        <div class="row">
            <div class="col-md-12">
                <div style="text-align: right;">
                    <button class="btn btn-default" id="btnStatus"><i class="fa fa-cog margin-right"></i> Status</button>
                </div>
                <hr/>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="well">
                    <select class="form-control" id="filterPeriod"></select>
                </div>
                <hr/>
            </div>
        </div>

        <div class="thumbnail" style="min-height: 100px;">
            <textarea class="hide" id="viewProspectiveStudents"></textarea>
            <div id="showTable">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 1%;">No</th>
                        <th>Prospective Students</th>
                        <th style="width: 20%;">Prospect By</th>
                        <th style="width: 15%;"><i class="fa fa-cog"></i></th>
                    </tr>
                    </thead>
                    <tbody id="listStd"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        loadMarketingActNow();
        loadActivePeriod();

        localWilayah();

        var firsLoad = setInterval(function () {

            var filterPeriod = $('#filterPeriod').val();
            if(filterPeriod!='' && filterPeriod!=null){

                loadDataCRM();
                clearInterval(firsLoad);

            }

        },1000);

    });

    function localWilayah() {
        var url = base_url_js+'api/__getWilayahURLJson';
        $.getJSON(url,function (jsonResult)  {

            localStorage.setItem('listWilayah',JSON.stringify(jsonResult));

        });
    }

    function loadSelectOptionDistrict_lokal(element,selected) {
        var jsonResult = JSON.parse(localStorage.getItem('listWilayah'));
        if(jsonResult.length>0){
            $.each(jsonResult,function (i,v) {
                $(element).append('<option value="'+v.RegionID+'">'+v.RegionName+'</option>')
                    .val(selected).trigger('change');
            });
        }
    }

    function loadActivePeriod() {
        var data = {
            action : 'activeCRMPeriode'
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'rest2/__crudCRMPeriode';

        $.post(url,{token:token},function (jsonResult) {
            var y = jsonResult[0].Year;
            var i = jsonResult[0].ID;
            $('#viewPeriod').html(y);
            $('#formPeriodID').val(i);

            loadTeamCRM(i);

            loadSelectOptionCRMPeriod('#filterPeriod',i);


        });

    }

    function loadTeamCRM(filterPeriod) {

        if(filterPeriod!='' && filterPeriod!=null) {
            var data = {
                action: 'readCRMTeam',
                PeriodID: filterPeriod
            };
            var token = jwt_encode(data, 'UAP)(*');
            var url = base_url_js + 'rest2/__crudCRMTeam';

            $.post(url,{token:token},function (jsonResult) {

               if(jsonResult.length>0){
                   $.each(jsonResult,function (i,v) {

                       var opt = '<option value="'+v.ID+'.'+v.Coordinator+'" style="color: blue;background: #f5f5f5;">(Co) '+v.CoordinatorName+'</option>';
                       var Member = v.Member;
                       if(Member.length>0){
                           $.each(Member,function (i2,v2) {
                               opt = opt+'<option value="'+v.ID+'.'+v2.NIP+'">'+v2.MemberName+'</option>';
                           });
                       }

                       $('#formTeam').append('<optgroup label="'+v.Name+'">'+opt+'</optgroup>');

                   });
               }
            });

        }
    }
    
    // ====
    
    function loadMarketingActNow() {

        var data = {
          action : 'readActiveNow_MA'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'rest2/__crudMarketingActivity';

        $.post(url,{token:token},function (jsonResult) {

            $('#formMarketingActivity').empty();
            $('#formMarketingActivity').append('<option value="">-- Not yet select --</option>');

            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    $('#formMarketingActivity').append('<option value="'+v.ID+'">'+v.Title+'</option>');


                });
            }


        });

    }

    $('#btnSavePS').click(function () {

        var formTeam = $('#formTeam').val();
        var formPeriodID = $('#formPeriodID').val();
        var formName = $('#formName').val();
        var formEmail = $('#formEmail').val();
        var formPhone = $('#formPhone').val();
        var formLineID = $('#formLineID').val();

        if(formTeam!='' && formTeam!=null &&
            formPeriodID!='' && formPeriodID!=null &&
        formName!='' && formName!=null &&
        formEmail!='' && formEmail!=null &&
        formPhone!='' && formPhone!=null){

            loading_buttonSm('#btnSavePS')

            var arrTeam = formTeam.split('.');

            var formID = $('#formID').val();
            var formMarketingActivity = $('#formMarketingActivity').val();
            var MAID = (formMarketingActivity!='' && formMarketingActivity!=null)
                ? formMarketingActivity
                : '';


            var data = {
                action : (formID!='' && formID!=null) ? 'update_PS' : 'insert_PS',
                ID : formID,
                    dataForm : {
                    PeriodID : formPeriodID,
                    CRMTeamID : arrTeam[0],
                    MAID : MAID,
                    Name : formName,
                    Email : formEmail,
                    Phone : formatPhone(formPhone),
                    LineID : formLineID,
                    NIP : arrTeam[1],
                    CreatedBy : sessionNIP
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'rest2/__crudProspectiveStudents';

            $.post(url,{token:token},function (jsonResult) {
                loadDataCRM();
                toastr.success('Data saved','Success');

                $('#formMarketingActivity').val('');
                $('#formTeam').val('');
                $('#formID').val('');
                $('#formName').val('');
                $('#formEmail').val('');
                $('#formPhone').val('');
                $('#formLineID').val('');

                setTimeout(function () {
                    $('#btnSavePS').html('Save').prop('disabled',false);
                },500);
            });

        }



    });
    
    function loadDataCRM() {

        var filterPeriod = $('#filterPeriod').val();

        if(filterPeriod!='' && filterPeriod!=null){

            var data = {
                action : 'read_PS',
                PeriodID : filterPeriod
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'rest2/__crudProspectiveStudents';

            $.post(url,{token:token},function (jsonResult) {

                $('#viewProspectiveStudents').val(JSON.stringify(jsonResult));


                $('#listStd').empty();
                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {


                        var btnAct = '<button class="btn btn-sm btn-primary btnActCRMEdit" data-id="'+v.ID+'"><i class="fa fa-edit"></i></button> ' +
                            '<button class="btn btn-sm btn-danger btnActCRMRemovet hide" data-id="'+v.ID+'"><i class="fa fa-trash"></i></button> ' +
                            '<button class="btn btn-sm btn-default btnFullForm" data-id="'+v.ID+'">Full Form</button>';

                        $('#listStd').append('<tr>' +
                            '<td>'+(i+1)+'</td>' +
                            '<td>'+v.Name+'' +
                            '<div>' +
                            '   '+v.Email+', '+
                            '   '+v.Phone+', ' +
                            '    '+v.LineID+' '+
                            '</div>' +
                            '</td>' +
                            '<td>'+v.NameProspect_by+'</td>' +
                            '<td>'+btnAct+'</td>' +
                            '</tr>');
                    });
                }

            });

        }


    }

    $(document).on('click','.btnActCRMEdit',function () {

        var viewProspectiveStudents = $('#viewProspectiveStudents').val();

        var viewProspectiveStudents = JSON.parse(viewProspectiveStudents);
        var ID = $(this).attr('data-id');

        var result = $.grep(viewProspectiveStudents, function(e){ return e.ID == ID; });

        var d = result[0];

        $('#formID').val(d.ID);
        $('#formName').val(d.Name);
        $('#formEmail').val(d.Email);
        $('#formPhone').val(d.Phone);
        $('#formLineID').val(d.LineID);

        var vMaid = '';
        if(d.MAID!='' && d.MAID!=null && d.MAID>0){
            vMaid = d.MAID;
        }

        $('#formMarketingActivity').val(vMaid);

        var vTeam = '';
        if(d.CRMTeamID!='' && d.CRMTeamID!=null){
            vTeam = d.CRMTeamID+'.'+d.NIP;
        }
        $('#formTeam').val(vTeam);


    });

    $(document).on('click','.btnFullForm',function () {
        var ID = $(this).attr('data-id');

        var data = {
            action : 'read2Full_PS',
            ID : ID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'rest2/__crudProspectiveStudents';

        $.post(url,{token:token},function (jsonResult) {

            var d_v = jsonResult[0];

            var body = '<div class="row">' +
                '    <div class="col-md-5" style="border-right: 1px solid #CCCCCC;">' +
                '' +
                '        <div class="form-group">' +
                '            <label>Name</label>' +
                '            <input type="text" value="'+ifNullChecking(d_v.ID)+'" class="hide" id="formID" />' +
                '            <input type="text" value="'+ifNullChecking(d_v.Name)+'" class="form-control" id="formName" />' +
                '        </div>' +
                '       <div class="row">' +
                '           <div class="col-xs-5">' +
                '               <div class="form-group">' +
                '                   <label>Gender</label>' +
                '                   <select class="form-control" id="formGender">' +
                '                       <option value="Male">Male</option>' +
                '                       <option value="Female">Female</option>' +
                '                   </select>' +
                '               </div>' +
                '           </div>' +
                '           <div class="col-xs-7">' +
                '               <div class="form-group">' +
                '                   <label>Phone</label>' +
                '                   <input type="number" data-form="phone" value="'+ifNullChecking(d_v.Phone)+'" id="formPhone" class="form-control"/>' +
                '               </div>' +
                '           </div>' +
                '       </div>' +

                '        <div class="form-group">' +
                '            <label>Email</label>' +
                '            <input type="email" value="'+ifNullChecking(d_v.Email)+'" id="formEmail" class="form-control"/>' +
                '        </div>' +
                '       <div class="row">' +
                '           <div class="col-xs-6">' +
                '               <div class="form-group">' +
                '                   <label>Line ID</label>' +
                '                  <input type="text" value="'+ifNullChecking(d_v.LineID)+'" id="formLineID" class="form-control"/>' +
                '               </div>' +
                '           </div>' +
                '           <div class="col-xs-6">' +
                '               <div class="form-group">' +
                '                   <label>Instagram</label>' +
                '                  <input type="text" value="'+ifNullChecking(d_v.Instagram)+'" id="formInstagram" class="form-control"/>' +
                '               </div>' +
                '           </div>' +
                '       </div>' +
                '        <div class="form-group">' +
                '            <label>Discritcs Of School</label>' +
                '            <select class="select2-select-00 full-width-fix" size="5" id="filterDistrict">' +
                '                        <option value=""></option>' +
                '                    </select>' +
                '        </div>' +
                '         <div id="viewSchoolName"></div>' +
                '       <div class="row">' +
                '           <div class="col-xs-5">' +
                '               <div class="form-group">' +
                '                   <label>Class</label>' +
                '                  <input type="number" value="'+ifNullChecking(d_v.Class)+'" id="formClass" class="form-control"/>' +
                '               </div>' +
                '           </div>' +
                '           <div class="col-xs-7">' +
                '               <div class="form-group">' +
                '                   <label>Pathway</label>' +
                '                   <select class="form-control" id="formPathwayID"><option></option></select>' +
                '               </div>' +
                '           </div>' +
                '       </div>' +
                '' +
                '        <div class="form-group">' +
                '            <label>Place Of Birth</label>' +
                '            <input type="text" value="'+ifNullChecking(d_v.PlaceOfBirth)+'" id="formPlaceOfBirth" class="form-control"/>' +
                '        </div>' +
                '        <div class="form-group">' +
                '            <label>Date Of Birth</label>' +
                '            <input type="text" value="" id="formDateOfBirth" readonly class="form-control"/>' +
                '        </div>' +
                '        <div class="form-group">' +
                '            <label>Address</label>' +
                '            <textarea class="form-control" id="formAddress" rows="3">'+ifNullChecking(d_v.Address)+'</textarea>' +
                '        </div>' +
                '        <div class="form-group">' +
                '            <label>Attachment</label>' +
                '            <input type="file">' +
                '        </div>' +
                '    </div>' +
                '' +
                '    <div class="col-md-4" style="border-right: 1px solid #CCCCCC;">' +
                '        <div class="form-group">' +
                '            <label>Father Name</label>' +
                '            <input type="text" value="'+ifNullChecking(d_v.FatherName)+'" class="form-control" id="formFatherName" />' +
                '        </div>' +
                '        <div class="form-group">' +
                '            <label>Father Education</label>' +
                '            <input type="text" value="'+ifNullChecking(d_v.FatherEduLevel)+'" class="form-control" id="formFatherEduLevel" />' +
                '        </div>' +
                '        <div class="form-group">' +
                '            <label>Father Job</label>' +
                '            <input type="text" value="'+ifNullChecking(d_v.FatherJob)+'" class="form-control" id="formFatherJob" />' +
                '        </div>' +
                '        <div class="form-group">' +
                '            <label>Father Phone</label>' +
                '            <input type="text" value="'+ifNullChecking(d_v.FatherPhone)+'" class="form-control" id="formFatherPhone" />' +
                '        </div>' +
                '        <div class="form-group">' +
                '            <label>Father Address</label>' +
                '            <textarea class="form-control" rows="3" id="formFatherAddress">'+ifNullChecking(d_v.FatherAddress)+'</textarea>' +
                '        </div>' +
                '        <hr/>' +
                '        <div class="form-group">' +
                '            <label>Mother Name</label>' +
                '            <input type="text" value="'+ifNullChecking(d_v.MotherName)+'" class="form-control" id="formMotherName" />' +
                '        </div>' +
                '        <div class="form-group">' +
                '            <label>Mother Education</label>' +
                '            <input type="text" value="'+ifNullChecking(d_v.MotherEduLevel)+'" class="form-control" id="formMotherEduLevel" />' +
                '        </div>' +
                '        <div class="form-group">' +
                '            <label>Mother Job</label>' +
                '            <input type="text" value="'+ifNullChecking(d_v.MotherJob)+'" class="form-control" id="formMotherJob" />' +
                '        </div>' +
                '        <div class="form-group">' +
                '            <label>Mother Phone</label>' +
                '            <input type="text" value="'+ifNullChecking(d_v.MotherPhone)+'" class="form-control" id="formMotherPhone" />' +
                '        </div>' +
                '        <div class="form-group">' +
                '            <label>Mother Address</label>' +
                '            <textarea class="form-control" rows="3" id="formMotherAddress">'+ifNullChecking(d_v.MotherAddress)+'</textarea>' +
                '        </div>' +
                '    </div>' +

                '    <div class="col-md-3">' +
                '        <div class="form-group">' +
                '            <label>Status</label>' +
                '            <select class="form-control" id="formStatusPS"><option></option></select>' +
                '        </div>' +
                '        <div id="viewListComment"></div>' +
                '        <div class="well">' +
                '            <label>Follow Up <span id="viewFollowUp"></span></label>' +
                '            <input class="hide" value="" id="formFollowUpID"/>' +
                '            <input class="hide" value="0" id="formFollowUp"/>' +
                '            <textarea class="form-control" id="formComment"></textarea>' +
                '            <textarea class="hide" id="dataListComment"></textarea>' +
                '<hr/>' +
                '            <div style="text-align: right;"><button id="btnSaveComment" class="btn btn-xs btn-primary">Save</button></div>' +
                '        </div>' +
                '        ' +
                '    </div>' +
                '</div>';

            $('#GlobalModalLarge .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Full Form CRM</h4>');
            $('#GlobalModalLarge .modal-body').html(body);

            loadSelectOptionStatusMarketing('#formStatusPS');
            loadFollowUp();

            $( "#formDateOfBirth")
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

            if(ifNullChecking(d_v.DateOfBirth)!=''){
                $('#formDateOfBirth').datepicker('setDate',new Date(ifNullChecking(d_v.DateOfBirth)));
            }

            loadSelectOptionDistrict_lokal('#filterDistrict',d_v.CityID);
            $('#filterDistrict').select2({allowClear: true});

            loadSelectOptionPathway('#formPathwayID',ifNullChecking(d_v.PathwayID));

            $('#formGender').val(ifNullChecking(d_v.Gender));

            if(d_v.CityID!=null && d_v.CityID!=''){
                console.log('masuk pak');
                $('#filterDistrict').select2('val',d_v.CityID);
                getSchoolList(d_v.SchoolID);
            }

            $('#filterDistrict').change(function () {
                getSchoolList();
            });

            $('#GlobalModalLarge .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
                '<button type="button" class="btn btn-success" id="btnSaveFullPS">Save</button>');
            $('#GlobalModalLarge').modal({
                'show' : true,
                'backdrop' : 'static'
            });

        });

    });

    $(document).on('click','.btnActCRMRemovet',function () {

        if(confirm('Are you sure?')){
            var ID = $(this).attr('data-id');

            var data = {
                action : 'removeCRM_PS',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'rest2/__crudProspectiveStudents';

        }

    });

    function ifNullChecking(value) {
        return (value!='' && value!=null) ? value : '';
    }

    function getSchoolList(SchoolID='') {
        var filterDistrict = $('#filterDistrict').val();
        if(filterDistrict!='' && filterDistrict!=null){
            $('#viewSchoolName').html('');
            $('#viewSchoolName').html('<div class="form-group"><label>School Name (Required)</label>' +
                '                           <select class="select2-select-00 full-width-fix" size="5" id="filterSchool">' +
                '                            </select></div>');


            var filterDistrict = $('#filterDistrict').val();
            // console.log(filterDistrict);
            $('#filterSchool').empty();
            loadSelectOptionScheoolBy(filterDistrict,'#filterSchool',SchoolID);
            $('#filterSchool').select2({allowClear: true});

            if(SchoolID!=''){
                $('#filterSchool').select2('val',SchoolID);
            }
        }
    }

    $(document).on('click','#btnSaveFullPS',function () {

        loading_button('#btnSaveFullPS');
        
        var formID = $('#formID').val();
        var formName = $('#formName').val();
        var formGender = $('#formGender').val();
        var formPhone = $('#formPhone').val();
        var formEmail = $('#formEmail').val();
        var formLineID = $('#formLineID').val();
        var formInstagram = $('#formInstagram').val();
        var formClass = $('#formClass').val();
        var formPathwayID = $('#formPathwayID').val();
        var formPlaceOfBirth = $('#formPlaceOfBirth').val();
        var formDateOfBirth = $('#formDateOfBirth').datepicker("getDate");
        var formAddress = $('#formAddress').val();
        var formFatherName = $('#formFatherName').val();
        var formFatherEduLevel = $('#formFatherEduLevel').val();
        var formFatherJob = $('#formFatherJob').val();
        var formFatherPhone = $('#formFatherPhone').val();
        var formFatherAddress = $('#formFatherAddress').val();
        var formMotherName = $('#formMotherName').val();
        var formMotherEduLevel = $('#formMotherEduLevel').val();
        var formMotherJob = $('#formMotherJob').val();
        var formMotherPhone = $('#formMotherPhone').val();
        var formMotherAddress = $('#formMotherAddress').val();

        var filterSchool = $('#filterSchool').val();

        var formStatusPS = $('#formStatusPS').val();

        var data = {
            action : 'update_PS',
            ID : formID,
            dataForm : {
                Name : formName,
                Gender : formGender,
                Phone : formPhone,
                Email : formEmail,
                LineID : formLineID,
                Instagram : formInstagram,
                SchoolID : filterSchool,
                Class : formClass,
                PathwayID : formPathwayID,
                PlaceOfBirth : formPlaceOfBirth,
                DateOfBirth : moment(formDateOfBirth).format('YYYY-MM-DD'),
                Address : formAddress,
                FatherName : formFatherName,
                FatherEduLevel : formFatherEduLevel,
                FatherJob : formFatherJob,
                FatherPhone : formFatherPhone,
                FatherAddress : formFatherAddress,
                MotherName : formMotherName,
                MotherEduLevel : formMotherEduLevel,
                MotherJob : formMotherJob,
                MotherPhone : formMotherPhone,
                MotherAddress : formMotherAddress,
                Status : formStatusPS
            }
        };
        
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'rest2/__crudProspectiveStudents';
        
        $.post(url,{token:token},function (result) {

            toastr.success('Data saved','Success');
            setTimeout(function () {
                $('#btnSaveFullPS').html('Save').prop('disabled',false);
            },500);
            
        })
        
    });

    $('#btnStatus').click(function () {


        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Status</h4>');

        var body = '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <div class="well">' +
            '            <div class="row">' +
            '                <div class="col-md-4">' +
            '                    <input class="hide" id="formID">' +
            '                    <input class="form-control" id="formStatus" placeholder="Status">' +
            '                </div>' +
            '                <div class="col-md-6">' +
            '                     <select class="form-control" id="formLabel">' +
            '                        <option value="" selected disabled>-- Color --</option>' +
            '                        <option value="1">Grey</option>' +
            '                        <option value="2">Green</option>' +
            '                        <option value="3">Blue</option>' +
            '                        <option value="4">Yellow</option>' +
            '                        <option value="5">Red</option>' +
            '                     </select>' +
            '                        <div style="margin-top: 10px;">' +
            '                           <span class="label label-default">Grey</span>' +
            '                           <span class="label label-success">Green</span>' +
            '                           <span class="label label-primary">Blue</span>' +
            '                           <span class="label label-warning">Yellow</span>' +
            '                           <span class="label label-danger">Red</span>' +
            '                        </div>' +
            '                </div>' +
            '                <div class="col-md-2">' +
            '                    <button class="btn btn-block btn-success" id="btnSaveStatus">Save</button>' +
            '                </div>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    ' +
            '</div>' +
            '' +
            '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <hr/>' +
            '        <table class="table table-striped">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Status</th>' +
            '                <th style="width: 15%;border-right: 1px solid #CCCCCC;">Color</th>' +
            '                <th style="width: 25%;">Preview</th>' +
            '                <th style="width: 25%;">' +
            '                    <i class="fa fa-cog"></i>' +
            '                </th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody id="listStatus"></tbody>' +
            '        </table>' +
            '       <textarea id="viewDataStatus" class="hide"></textarea>' +
            '    </div>' +
            '</div>';

        $('#GlobalModal .modal-body').html(body);

        loadDataStatus();

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });


    // Save Status
    $(document).on('click','#btnSaveStatus',function () {

        var formID = $('#formID').val();
        var formStatus = $('#formStatus').val();
        var formLabel = $('#formLabel').val();

        if(formStatus!='' && formStatus!=null &&
            formLabel!='' && formLabel!=null){

            loading_buttonSm('#btnSaveStatus');

            var data = {
                action : (formID!='' && formID!=null) ? 'updateStatus_PS' : 'insertStatus_PS',
                ID : formID,
                dataForm : {
                    Description : formStatus,
                    LabelID : formLabel
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'rest2/__crudProspectiveStudents';

            $.post(url,{token:token},function (result) {

                $('#formID').val('');
                $('#formStatus').val('');
                $('#formLabel').val('');

                loadDataStatus();
                toastr.success('Data saved','Success');
                setTimeout(function () {
                    $('#btnSaveStatus').html('Save').prop('disabled',false);
                },500);

            });

        }

    });

    function loadDataStatus() {
        var data = {
            action : 'status_PS'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'rest2/__crudProspectiveStudents';

        $.post(url,{token:token},function (jsonResult) {

            $('#viewDataStatus').val(JSON.stringify(jsonResult));

            $('#listStatus').empty();
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var btn = '<button class="btn btn-xs btn-default btn-default-danger btnStatusRemove" data-id="'+v.ID+'"><i class="fa fa-trash"></i></button> ' +
                        '<button class="btn btn-xs btn-default btn-default-success btnStatusEdit" data-id="'+v.ID+'"><i class="fa fa-edit"></i></button>';

                    $('#listStatus').append('<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td>'+v.Description+'</td>' +
                        '<td style="border-right: 1px solid #CCCCCC;">'+v.LabelName+'</td>' +
                        '<td><span class="'+v.LabelClass+'">'+v.Description+'</span></td>' +
                        '<td>'+btn+'</td>' +
                        '</tr>');
                })
            } else {
                $('#listStatus').append('<tr><td colspan="5">Data not yet</td></tr>');
            }

        })
    }

    $(document).on('click','.btnStatusEdit',function () {

        var viewDataStatus = $('#viewDataStatus').val();
        var DataStatus = JSON.parse(viewDataStatus);
        var ID = $(this).attr('data-id');

        var result = $.grep(DataStatus, function(e){ return e.ID == ID; });

        var d = result[0];

        $('#formID').val(d.ID);
        $('#formStatus').val(d.Description);
        $('#formLabel').val(d.LabelID);

    });

    $(document).on('click','.btnStatusRemove',function () {
       if(confirm('Are you sure?')){
           var ID = $(this).attr('data-id');

           var data = {
               action : 'removeStatus_PS',
               ID : ID
           };

           var token = jwt_encode(data,'UAP)(*');
           var url = base_url_js+'rest2/__crudProspectiveStudents';

           $.post(url,{token:token},function (jsonResult) {

               if(jsonResult.Status=='1' || jsonResult.Status==1){
                   toastr.success('Status removed','Success');
                   loadDataStatus();
               } else {
                   toastr.warning('Status can not removed','Warning');
               }

           })
       }
    });

    // Comment
    $(document).on('click','#btnSaveComment',function () {

        var CRMID = $('#formID').val();
        var formFollowUpID = $('#formFollowUpID').val();
        var formFollowUp = $('#formFollowUp').val();
        var formComment = $('#formComment').val();

        var data = {
            action : (formFollowUpID!='') ? 'updateCommentF_SP' : 'insertCommentF_SP',
            ID : formFollowUpID,
            dataForm : {
                CRMID : CRMID,
                No : formFollowUp,
                Comment : formComment,
                UpdatedBy : sessionNIP
            }
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'rest2/__crudProspectiveStudents';

        $.post(url,{token:token},function (result) {

            loadFollowUp();
            toastr.success('Data saved','Success');
            $('#formFollowUpID').val('');
            $('#formComment').val('');

        });

    });

    function loadFollowUp() {
        var CRMID = $('#formID').val();

        var data = {
            action : 'viewFollowUp_SP',
            CRMID : CRMID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'rest2/__crudProspectiveStudents';

        $.post(url,{token:token},function (jsonResult) {

            var dataList = '';
            $('#formFollowUp').val(parseInt(jsonResult.length) + 1);
            $('#viewFollowUp').html(parseInt(jsonResult.length) + 1);


            var tr ='';
            $.each(jsonResult,function (i,v) {

                tr = tr+'<tr>' +
                    '<td>' +
                    '<b>Follow Up - '+v.No+'</b>' +
                    '<p class="help-block">'+v.Comment+'</p>' +
                    '<div style="text-align: right;"><button class="btn btn-xs btn-default btnEditFollowUp" data-id="'+v.ID+'">Edit</button></div>' +
                    '</td>' +
                    '</tr>';
            });

            if(jsonResult.length>0){
                dataList = '<table class="table"><tbody class="table">'+tr+'</tbody></table>'
            }

            $('#dataListComment').val(JSON.stringify(jsonResult));

            $('#viewListComment').html(dataList);

        })
    }

    $(document).on('click','.btnEditFollowUp',function () {

        var dataListComment = $('#dataListComment').val();
        var dataListComment = JSON.parse(dataListComment);
        var ID = $(this).attr('data-id');

        var result = $.grep(dataListComment, function(e){ return e.ID == ID; });

        var d = result[0];

        $('#viewFollowUp').html(d.No);
        $('#formFollowUpID').val(d.ID);
        $('#formFollowUp').val(d.No);
        $('#formComment').val(d.Comment);

    });

</script>