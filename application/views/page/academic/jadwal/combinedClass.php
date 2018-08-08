<div class="col-md-12" style="text-align: center;">
    <h4 id="viewCC_Semester"></h4>
    <hr/>
    <input id="formCC_SemesterID" class="hide" readonly hidden>
</div>

<div class="col-md-4">
    <div class="widget box">
        <div class="widget-header">
            <h4><i class="icon-reorder"></i> Add Combined Class</h4>
        </div>
        <div class="widget-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Select Prodi</label>
                        <select class="form-control selc-prodi" id="filterCC_Prodi">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Course</label>
                        <div id="dvCC_Course">-</div>
                        <hr/>
                    </div>
                    <div class="form-group" style="text-align: center;background:#ffeb3bab;padding-top: 5px;">
                        <label>Combined with</label>
                    </div>
                    <div class="form-group">
                        <label>Select Prodi</label>
                        <select class="form-control selc-prodi" id="formCC_addProdi"></select>
                    </div>
                    <div class="form-group">
                        <label>Select Group Class</label>
                        <div id="div_formCC_ClassGroup">-</div>
<!--                        <select class="form-control" id="formCC_ClassGroup"></select>-->
                    </div>

                    <div class="form-group" style="text-align: right;">
                        <button class="btn btn-success" id="btnSaveAddCC">Save</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<div class="col-md-8">
    <div class="widget box">
        <div class="widget-header">
            <h4><i class="icon-reorder"></i> Remove Combined Class</h4>
        </div>
        <div class="widget-content">
            <div class="row">
                <div class="col-xs-5">

                    <div class="input-group">
                        <input type="text" class="form-control" id="searchClassGroup" placeholder="Search by group class">
                                        <span class="input-group-btn">
                        <button class="btn btn-primary" id="btnSearchGroup" type="button"><span class="glyphicon glyphicon-search" style="margin-right: 5px;"></span> Show timetables</button>
                      </span>
                    </div>
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                        <tr style="background: #607D8B;color: #fff;">
                            <th>Course</th>
                            <th style="width: 7%;">Action</th>
                        </tr>
                        </thead>
                        <tbody id="trCombinedCl"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        loadCC_AcademicYearOnPublish('');

        $('.selc-prodi').empty();
        $('.selc-prodi').append('<option value="" selected disabled>--- Select Prodi ---</option>');
        loadSelectOptionBaseProdi('.selc-prodi');

    });

    $('#btnSearchGroup').click(function () {
        var searchClassGroup = $('#searchClassGroup').val();

        if(searchClassGroup!='' && searchClassGroup!=null){
            var data = {
                action : 'searchByGroup',
                SemesterID : $('#formCC_SemesterID').val(),
                ClassGroup : searchClassGroup
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudStudyPlanning';
            $.post(url,{token:token},function (jsonResult) {
                if(jsonResult.length>0){

                    var btnAct = (jsonResult.length>1) ? '<button class="btn btn-danger">Del</button>' : '-';

                    $('#trCombinedCl').empty();

                    for(var i=0;i<jsonResult.length;i++){
                        var d = jsonResult[i];
                        $('#trCombinedCl').append('<tr>' +
                            '<td>' +
                            '<b>'+d.NameEng+'</b><br/><i>Semester '+d.Semester+'</i>' +
                            '</td>' +
                            '<td>'+btnAct+'</td>' +
                            '</tr>');
                    }
                }
            });
        }

    });

    $('#searchClassGroup').keyup(function () {
        var  SemesterID = $('#formCC_SemesterID').val();
        var url = base_url_js+'api/__getClassGroupAutoComplete/'+SemesterID+'/';
        $("#searchClassGroup").autocomplete({
            source: url,
            minLength: 2
        });
    });

    $('#btnSaveAddCC').click(function () {
        var SemesterID = $('#formCC_SemesterID').val();
        var ProdiA = $('#filterCC_Prodi').val();
        var Course = $('#formCC_MataKuliah').val();
        var ProdiB = $('#formCC_addProdi').val();
        var ScheduleID = $('#formCC_ClassGroup').val();

        if(
            SemesterID!='' && SemesterID!=null
            && ProdiA!='' && ProdiA!=null
            && Course!='' && Course!=null
            && ProdiB!='' && ProdiB!=null
            && ScheduleID!='' && ScheduleID!=null
        ){

            loading_buttonSm('#btnSaveAddCC');

            var data = {
              action : 'addCombine',
              dataInsert : {
                  ScheduleID : ScheduleID,
                  ProdiID : ProdiA.split('.')[0],
                  CDID : Course.split('|')[0],
                  MKID : Course.split('|')[1]
              }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudCombinedClass';
            $.post(url,{token:token},function (result) {
                toastr.success('Saved','Success');
                setTimeout(function () {
                    $('#btnSaveAddCC').prop('disabled',false).html('Save');
                    $('#filterCC_Prodi,#formCC_addProdi').val('');
                    $('#dvCC_Course').html('-');
                    $('#formCC_ClassGroup').empty();
                },500);
            });
        }


    });


    function loadCC_AcademicYearOnPublish(smt) {
        var url = base_url_js+"api/__getAcademicYearOnPublish";
        $.getJSON(url,{smt:smt},function (data_json) {
            if(smt=='SemesterAntara'){
                $('#formCC_SemesterID').val(data_json.SemesterID);
            } else {
                $('#formCC_SemesterID').val(data_json.ID);
            }

            $('#viewCC_Semester').html(data_json.Year+''+data_json.Code+' | '+data_json.Name);

        });
    }

    function getCC_CourseOfferings(ProdiID) {
        var url = base_url_js+'api/__crudCourseOfferings';
        var SemesterID = $('#formCC_SemesterID').val();
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
                $('#dvCC_Course').html('<select class="select2-select-00 full-width-fix" size="5" id="formCC_MataKuliah">' +
                    '                        <option value=""></option>' +
                    '                    </select><p>Mata kuliah yg tampil adalah yg belum diset jadwal</p>');

                for(var i=0;i<jsonResult.length;i++){
                    var semester = jsonResult[i].Offerings.Semester;

                    var mk = jsonResult[i].Details;
                    for(var m=0;m<mk.length;m++){
                        var dataMK = mk[m];
                        var schDisabled = (dataMK.ScheduleID!="") ? '' : '';

                        var asalSmt = (semester!=dataMK.Semester) ? '('+dataMK.Semester+')' : '';
                        $('#formCC_MataKuliah').append('<option value="'+dataMK.CDID+'|'+dataMK.ID+'" '+schDisabled+'>Smt '+semester+' '+asalSmt+' - '+dataMK.MKCode+' | '+dataMK.MKNameEng+'</option>');


                    }

                    // $('#formCC_MataKuliah').append('<option disabled>-------</option>');

                }

                $('#formCC_MataKuliah').select2({allowClear: true});
            } else {
                $('#dvCC_Course').html('<b>No Course To Offerings</b>')
            }
        });
    }

    function loadGroupClass(elm,ProdiID) {
        var data = {
            action : 'readGroupCalss',
            ProdiID : ProdiID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudCombinedClass';
        $.post(url,{token:token},function (jsonResult) {
            var div_em = '#div_'+elm;
            var opt_em = '#'+elm;

            console.log(div_em,opt_em);

            $(div_em).html('');
            if(jsonResult.length>0){

                $(div_em).html('<select class="select2-select-00 full-width-fix" size="5" id="'+elm+'">' +
                    '                        <option value=""></option>' +
                    '                    </select>');

                for(var c=0;c<jsonResult.length;c++){
                    var d = jsonResult[c];
                    $(opt_em).append('<option value="'+d.ID+'">'+d.ClassGroup+'</option>');
                }

                $(opt_em).select2({allowClear: true});
            }
        });
    }

    function loadScheduleFromGC() {
        var SemesterID = $('#formCC_SemesterID').val();
        var filterCC_ProdiDell = $('#filterCC_ProdiDell').val();
        var formCC_ClassGroupDell = $('#formCC_ClassGroupDell').val();

        if(
            SemesterID !='' && SemesterID!=null &&
            filterCC_ProdiDell !='' && filterCC_ProdiDell!=null &&
            formCC_ClassGroupDell !='' && formCC_ClassGroupDell!=null
        ) {
            var data = {
               action : 'getScheduleGC',
                SemesterID : SemesterID,
                ProdiID : filterCC_ProdiDell.split('.')[0],
                ScheduleID : formCC_ClassGroupDell
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudCombinedClass';
            $.post(url,{token:token},function (jsonResult) {
                $('#trCombinedCl').empty();
                if(jsonResult.length>0){

                    var btnDel = (jsonResult[0].TotalProdi>1) ? '<button class="btn btn-sm btn-danger" data-id="'+jsonResult[0].ScheduleID+'" data-sdcid="'+jsonResult[0].SDCID+'" id="btnDellCombined">Del</button>' : '-';

                    $('#trCombinedCl').append('<tr>' +
                        '<td>'+jsonResult[0].MKCode+' - '+jsonResult[0].MKNameEng+'</td>' +
                        '<td style="text-align: center;">'+btnDel+'</td>' +
                        '</tr>');
                }
            });
        }

    }

</script>