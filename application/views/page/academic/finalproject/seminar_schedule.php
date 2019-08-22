

<style>
    #tableList tr th {
        text-align: center;
    }
</style>

<div class="row" style="margin-top: 30px;">

    <div class="col-md-4 col-md-offset-4">
        <div class="well">

            <div class="row">
                <div class="col-md-7">
                    <select id="filterSemester" class="form-control"></select>
                </div>
                <div class="col-md-5">
                    <select id="filterSeminar" class="form-control">
                        <option value="1">Seminar Proposal</option>
                        <option value="2">Seminar Hasil</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-12">

        <table class="table table-bordered table-striped" id="tableList">
            <thead>
            <tr>
                <th style="width: 1%;">No</th>
                <th>Date Time</th>
                <th style="width: 10%;">Room</th>
                <th>Penguji</th>
                <th style="width: 3%;">
                    <i class="fa fa-cog"></i>
                </th>
                <th>Student</th>
            </tr>
            </thead>
            <tbody id="listSch"></tbody>
        </table>

    </div>
</div>

<script>

    $(document).ready(function () {
        loSelectOptionSemester('#filterSemester','');

        var loadFirst = setInterval(function (args) {
            var filterSemester = $('#filterSemester').val();
            var filterSeminar = $('#filterSeminar').val();
            if(filterSemester!='' && filterSemester!=null && filterSeminar!='' && filterSeminar!=null){
                loadSchedule();
                clearInterval(loadFirst);
            }

        },1000);


    });

    $('#filterSeminar,#filterSemester').change(function () {
        loadSchedule();
    });
    
    function loadSchedule() {

        var filterSemester = $('#filterSemester').val();
        var filterSeminar = $('#filterSeminar').val();

        if(filterSemester!='' && filterSemester!=null &&
            filterSeminar!='' && filterSeminar!=null){
            var SemesterID = filterSemester.split('.')[0];

            var data = {
                action : 'readDataSchFP',
                SemesterID : SemesterID,
                Type : filterSeminar
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudFinalProject';

            $('#listSch').empty();

            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {

                        var date = moment(v.Date).format('dddd, DD MMM YYYY');
                        var time = v.Start.substr(0,5)+' - '+v.End.substr(0,5);

                        var Examiner = '';
                        if(v.Examiner.length>0){
                            $.each(v.Examiner,function (i2,v2) {
                                Examiner = Examiner+'<div>'+v2.NIP+' - '+v2.Name+'</div>';
                            });
                        }

                        var Std = '';
                        if(v.Student.length>0){
                            $.each(v.Student,function (i3,v3) {
                                Std = Std+'<div>'+v3.NPM+' - '+v3.Name+'</div>';
                            });
                        }

                        var btn = '<div class="btn-group">' +
                            '  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                            '    <i class="fa fa-edit"></i> <span class="caret"></span>' +
                            '  </button>' +
                            '  <ul class="dropdown-menu">' +
                            '    <li><a href="'+base_url_js+'academic/final-project/scheduling-final-project?id='+v.ID+'" >Edit</a></li>' +
                            '    <li role="separator" class="divider"></li>' +
                            '    <li><a href="javascript:void(0);" class="btnRemove" data-id="'+v.ID+'">Remove</a></li>' +
                            '  </ul>' +
                            '</div>';

                        $('#listSch').append('<tr>' +
                            '<td>'+(i+1)+'</td>' +
                            '<td>'+date+'<br/>'+time+'</td>' +
                            '<td>'+v.Room+'</td>' +
                            '<td>'+Examiner+'</td>' +
                            '<td>'+btn+'</td>' +
                            '<td>'+Std+'</td>' +
                            '</tr>');
                    })
                }

            });

        }
    }

    $(document).on('click','.btnRemove',function () {

        if(confirm('Are you sure?')){

            var ID = $(this).attr('data-id');

            var data = {
                action : 'removeDataSchFP',
                ID : ID
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudFinalProject';

            $.post(url,{token:token},function (result) {
                toastr.success('Data removed','Success');
                loadSchedule();
            });

        }



    });

</script>

