
<style>
    #tableTimeTalbesOnline a {
        /*text-decoration: none;*/
    }
</style>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="well">
            <div class="row">
                <div class="col-md-6">
                    <select class="form-control" id="filterSemester"></select>
                </div>
                <div class="col-md-6">
                    <select class="form-control" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="viewTable"></div>






<script>
    
    $(document).ready(function () {

        loSelectOptionSemester('#filterSemester','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');

        var firstLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                getOnlineClass();
                clearInterval(firstLoad);
            }
        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);


    });

    $('#filterSemester,#filterBaseProdi').change(function () {
        getOnlineClass();
    });

    function getOnlineClass() {

        $('#viewTable').html('<table class="table table-bordered table-striped table-centre" id="tableTimeTalbesOnline">' +
            '    <thead>' +
            '    <tr style="background: #f3f1f1;">' +
            '        <th style="width: 3%;">No</th>' +
            '        <th style="width: 15%;">Course</th>' +
            '        <th>1</th>' +
            '        <th>2</th>' +
            '        <th>3</th>' +
            '        <th>4</th>' +
            '        <th>5</th>' +
            '        <th>6</th>' +
            '        <th>7</th>' +
            '        <th>8</th>' +
            '        <th>9</th>' +
            '        <th>10</th>' +
            '        <th>11</th>' +
            '        <th>12</th>' +
            '        <th>13</th>' +
            '        <th>14</th>' +
            '    </tr>' +
            '    </thead>' +
            '</table>');

        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){

            var SemesterID = filterSemester.split('.')[0];
            var filterBaseProdi = $('#filterBaseProdi').val();
            var ProdiID = (filterBaseProdi!='') ? filterBaseProdi.split('.')[0] : '';

            var data = {
                SemesterID : SemesterID,
                ProdiID : ProdiID
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api4/__getDataOnlineClass';

            var dataTable = $('#tableTimeTalbesOnline').DataTable({
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "Group, (Co)Lecturer, Classroom"
                },
                "ajax":{
                    url : url, // json datasource
                    data : {token:token},
                    ordering : false,
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                    }
                }
            });

        }

    }

    $(document).on('click','.btnAdmShowAttendance',function () {

        var ScheduleID = $(this).attr('data-schid');
        var Session = $(this).attr('data-session');

        var dataRow = JSON.parse($('#text_'+ScheduleID).val());


        var data = {
            action : 'getMonitoringAttd',
            ScheduleID : ScheduleID,
            Session : Session
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudOnlineClass';

        $.post(url,{token:token},function (jsonResult) {

            var modalID = '#GlobalModal';
            $(modalID+' .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">'+dataRow.ClassGroup+' - '+dataRow.CourseEng+' | Session '+Session+'</h4>');

            var viewLec = '';
            if(jsonResult.Lecturer.length>0){
                $.each(jsonResult.Lecturer,function (i,v) {
                    var Forum = (parseInt(v.Forum)>0) ? '<i class="fa fa-check" style="color: green;"></i>' : '-';
                    var Task = (parseInt(v.Task)>0) ? '<i class="fa fa-check" style="color: green;"></i>' : '-';

                    var UploadMaterialBy = (v.Material.length>0) ? 'Uploaded by : '+v.Material[0].Name : '';
                    var Material = (v.Material.length>0)
                        ? '<i class="fa fa-check" style="color: green;"></i> | <span style="font-size: 10px;">'+UploadMaterialBy+'</span>' : '-';

                    viewLec = viewLec+'<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td style="text-align: left;"><b>'+v.Name+'</b><br/>'+v.NIP+'</td>' +
                        '<td>'+Forum+'</td>' +
                        '<td>'+Task+'</td>' +
                        '<td>'+Material+'</td>' +
                        '<td><button class="btn btn-sm btn-success" disabled>Set Attd.</button></td>' +
                        '</tr>';
                });
            }

            var tmLec = '<h3>Lecturer</h3>' +
                '<table class="table table-bordered table-striped table-centre">' +
                '    <thead>' +
                '    <tr>' +
                '        <th style="width: 1%;">No</th>' +
                '        <th>Lecturer</th>' +
                '        <th style="width: 10%;">Forum</th>' +
                '        <th style="width: 10%;">Task</th>' +
                '        <th style="width: 20%;">Material</th>' +
                '        <th style="width: 7%;"><i class="fa fa-cog"></i></th>' +
                '    </tr>' +
                '    </thead>' +
                '    <tbody>'+viewLec+'</tbody>' +
                '</table>';

            var viewStd = '';
            if(jsonResult.Student.length>0){
                $.each(jsonResult.Student,function (i,v) {
                    var Forum = (parseInt(v.TotalComment)>0) ? '<i class="fa fa-check" style="color: green;"></i>' : '-';
                    var Task = (parseInt(v.TotalTask)>0) ? '<i class="fa fa-check" style="color: green;"></i>' : '-';
                    viewStd = viewStd+'<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td style="text-align: left;"><b>'+v.Name+'</b><br/>'+v.NPM+'</td>' +
                        '<td>'+Forum+'</td>' +
                        '<td>'+Task+'</td>' +
                        '<td><button class="btn btn-sm btn-success" disabled>Set Attd.</button></td>' +
                        '</tr>';
                });
            }

            var tmStd = '<h3>Student</h3>' +
                '<table class="table table-bordered table-striped table-centre">' +
                '    <thead>' +
                '    <tr>' +
                '        <th style="width: 1%;">No</th>' +
                '        <th>Student</th>' +
                '        <th style="width: 10%;">Forum</th>' +
                '        <th style="width: 10%;">Task</th>' +
                '        <th style="width: 7%;"><i class="fa fa-cog"></i></th>' +
                '    </tr>' +
                '    </thead>' +
                '    <tbody>'+viewStd+'</tbody>' +
                '</table>';

            var htmlss = tmLec+tmStd;

            $(modalID+' .modal-body').html(htmlss);

            $(modalID+' .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

            $(modalID).on('shown.bs.modal', function () {
                $('#formSimpleSearch').focus();
            });

            $(modalID).modal({
                'show' : true,
                'backdrop' : 'static'
            });

        });

    });

</script>