<style>
    #tableDataScore thead tr th,#tableDataScore tbody tr td {
        text-align: center;
    }

    #tableDataScore thead tr {
        background-color: #436888;color: #ffffff;
    }
</style>

<div class="col-md-12">
    <div class="row">
        <div class="col-md-4">
            <div class="">
                <label>Semester Antara</label>
                <input type="checkbox" id="formSemesterAntara" data-toggle="toggle" data-style="ios"/>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="thumbnail" style="margin-top: 30px;">
                <div class="row">
                    <div class="col-md-4">
                        <select id="filterSemester" class="form-control filter-score"></select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control filter-score" id="filterCombine">
                            <option value="0">Combine Class No</option>
                            <option value="1">Combine Class Yes</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="filterBaseProdi" class="form-control filter-score"></select>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered" id="tableDataScore">
                <thead>
                <tr>
                    <th rowspan="2" style="width: 2%;">No</th>
                    <th rowspan="2" style="width: 10%;">Classgroup</th>
                    <th rowspan="2">Course</th>
                    <th rowspan="2" style="width: 5%;">Credit</th>
                    <th rowspan="2" style="width: 20%;">Lecturer</th>
<!--                    <th rowspan="2" style="width: 7%;">Students</th>-->
                    <th rowspan="2" style="width: 5%;">Act</th>
                    <th colspan="2">Schedule</th>
                </tr>
                <tr>
                    <th style="width: 25%;">Day, time | Room</th>
                </tr>
                </thead>
                <tbody id="dataCourse"></tbody>
            </table>
        </div>
    </div>

</div>


<script>
    $(document).ready(function () {

        $('#filterSemester,#filterBaseProdi').empty();
        $('#filterSemester').append('<option value="" disabled selected>-- Year Academic--</option>' +
            '                <option disabled>------------------------------------------</option>');
        loSelectOptionSemester('#filterSemester','');


        $('#filterBaseProdi').append('<option value="" disabled selected>-- Select Program Study --</option>' +
            '<option disabled>------------------------------------------</option>');
        loadSelectOptionBaseProdi('#filterBaseProdi','');
    });

    $(document).on('change','.filter-score',function () {
        loadCourse();
    });


    function loadCourse() {
        var filterSemester = $('#filterSemester').val();
        var filterCombine = $('#filterCombine').val();
        var filterBaseProdi = $('#filterBaseProdi').val();
        var IsSemesterAntara = '0';

        if(filterSemester!='' && filterSemester!=null && filterBaseProdi!='' && filterBaseProdi!=null){

            var SemesterID = filterSemester.split('.')[0];
            var ProdiID = filterBaseProdi.split('.')[0];

            var data = {
                action : 'dataCourse',
                SemesterID : SemesterID,
                ProdiID : ProdiID,
                CombinedClasses : filterCombine,
                IsSemesterAntara : IsSemesterAntara
            };

            var token = jwt_encode(data,'UAP)(*');

            var url = base_url_js+'api/__crudScore';

            $.post(url,{token:token},function (jsonResult) {

                var tr = $('#dataCourse');
                tr.empty();

                if(jsonResult.length>0){
                    var no = 1;
                    for(var i=0;i<jsonResult.length;i++){
                        var dataC = jsonResult[i];

                        var schedule = '';
                        for(var c=0;c<dataC.DetailSchedule.length;c++){
                            var dd_c = dataC.DetailSchedule[c];
                            var sc_ = dd_c['DayEng']+', '+dd_c['StartSessions'].substr(0,5)+' - '+dd_c['EndSessions'].substr(0,5)+' | '+dd_c['Room'];

                            var br = (c!=0 && c!= (dataC.DetailSchedule.length)) ? '<br/>' : '';

                            schedule = schedule+''+br+''+sc_;

                        }

                        var Team = '';
                        if(dataC.DetailTeamTeaching.length>0){
                            for(var t=0;t<dataC.DetailTeamTeaching.length;t++){
                                var tc =  dataC.DetailTeamTeaching[t];
                                var br = '<br/> - ';
                                Team = Team+''+br+''+tc.Name;
                            }
                        }

                        var btnAct = '<div class="btn-group">' +
                            '  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                            '    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="caret"></span>' +
                            '  </button>' +
                            '  <ul class="dropdown-menu">' +
                            '    <li><a href="javascript:void(0);" class="inputLecturerAttd" data-no="'+i+'" data-id="">Input Score</a></li>' +
                            '    <li role="separator" class="divider"></li>' +
                            '    <li><a href="javascript:void(0);" class="inputScheduleExchange" data-no="'+i+'" data-id="">Cetak Report UTS</a></li>' +
                            '    <li><a href="javascript:void(0);" class="inputScheduleExchange" data-no="'+i+'" data-id="">Cetak Report UAS</a></li>' +
                            '  </ul>' +
                            '</div>';



                        tr.append('<tr>' +
                            '<td>'+(no++)+'</td>' +
                            '<td>'+dataC.Classgroup+'</td>' +
                            '<td style="text-align: left;"><b>'+dataC.MKNameEng+'</b><br/><i>'+dataC.MKName+'</i></td>' +
                            '<td>'+dataC.Credit+'</td>' +
                            '<td style="text-align: left;">(C) '+dataC.CoordinatorName+''+Team+'</td>' +
                            '<td>'+btnAct+'</td>' +
                            // '<td>'+dataC.Classgroup+'</td>' +
                            '<td style="text-align: right;">'+schedule+'</td>' +
                            '</tr>');
                    }
                } else {
                    tr.append('<tr><td colspan="7" style="text-align: center;">Data Not Yet</td></tr>');
                }

            })
        }


    }
</script>