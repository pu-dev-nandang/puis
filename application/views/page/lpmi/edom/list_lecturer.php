<style>
    #tableELecturer thead tr th {
        text-align: center;
        background: #20485A;
        color: #FFFFFF;
    }
    #tableELecturer tbody tr td {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="well">
            <div class="row">
                <div class="col-xs-6">
                    <select class="form-control" id="filterSemester"></select>
                </div>
                <div class="col-xs-6">
                    <select class="form-control" id="filterProdi"></select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr/>
        <h3 class="left-warning">Prodi</h3>
        <div id="divTableLec"></div>

    </div>
</div>

<script>
    $(document).ready(function () {
        loSelectOptionSemester('#filterSemester','');

        // $('#filterProdi').append('<option value="">-- All Programme Study --</option>' +
        //     '<option disabled>------------------------------------------</option>');
        loadSelectOptionBaseProdi('#filterProdi','');

        var firsLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            var filterProdi = $('#filterProdi').val();

            if(filterSemester!='' && filterSemester!=null
                && filterProdi!='' && filterProdi!=null){
                loadDataLEcturer();
                clearInterval(firsLoad);
            }

        },1000);

    });

    $('#filterSemester,#filterProdi').change(function () {
        loadDataLEcturer();
    });

    function loadDataLEcturer() {
        var filterSemester = $('#filterSemester').val();
        var filterProdi = $('#filterProdi').val();

        $('#divTableLec').html('');

        if(filterSemester!='' && filterSemester!=null
            && filterProdi!='' && filterProdi!=null){

            var SemesterID = filterSemester.split('.')[0];
            var ProdiID = filterProdi.split('.')[0];

            var url = base_url_js+"api/__getLecturerEvaluation";
            $.getJSON(url,{SemesterID:SemesterID,ProdiID:ProdiID},function (jsonResult) {
                console.log(jsonResult);

                $('#divTableLec').html('<table class="table table-bordered" id="tableELecturer">' +
                    '                <thead>' +
                    '                <tr>' +
                    '                    <th rowspan="2" style="width: 1%;">No</th>' +
                    '                    <th rowspan="2" style="width: 15%;">Lecturer</th>' +
                    '                    <th colspan="2" style="width: 20%;">Course</th>' +
                    '                    <th rowspan="2" style="width: 10%;">Total<br/>Student</th>' +
                    '                    <th rowspan="2" style="width: 10%;">Filled<br/>Edom</th>' +
                    '                    <th rowspan="2" style="width: 5%;">Rate</th>' +
                    '                </tr>' +
                    '               <tr>' +
                    '                   <th style="width: 3%;">Group</th>' +
                    '                   <th>Course</th>' +
                    '               </tr>' +
                    '                </thead>' +
                    '                <tbody id="rwEdom"></tbody>' +
                    '            </table>');

                if(jsonResult.length>0){
                    var no = 1;
                    for(var i=0;i<jsonResult.length;i++){
                        var d = jsonResult[i];

                        var rwSpan = d.Course.length + 1;

                        $('#rwEdom').append('<tr>' +
                            '<td rowspan="'+rwSpan+'">'+(no++)+'</td>' +
                            '<td style="text-align: left;" rowspan="'+rwSpan+'"><b>'+d.Name+'</b><br/>'+d.NIP+'</td>' +
                            '</tr>');

                        // Course
                        for(var c=0;c<d.Course.length;c++){
                            var dc = d.Course[c];
                            $('#rwEdom').append('<tr>' +
                                '<td>'+dc.ClassGroup+'</td>' +
                                '<td style="text-align: left;">' +
                                '   <b>'+dc.CourseEng+'</b><br/><i>'+dc.Course+'</i>' +
                                '</td>' +
                                '<td>'+dc.TotalStudent+'</td>' +
                                '<td>'+dc.TotalAnswer+'</td>' +
                                '<td></td>' +
                                '</tr>');
                        }



                    }
                }


            });


        }
    }
    
    function loadDataLEcturer2() {
        var filterSemester = $('#filterSemester').val();
        var filterProdi = $('#filterProdi').val();

        $('#divTableLec').html('');

        if(filterSemester!='' && filterSemester!=null
            && filterProdi!='' && filterProdi!=null){

            $('#divTableLec').html('<table class="table table-bordered" id="tableELecturer">' +
                '                <thead>' +
                '                <tr>' +
                '                    <th style="width: 1%;">No</th>' +
                '                    <th style="width: 15%;">Lecturer</th>' +
                '                    <th style="width: 20%;">Course</th>' +
                '                    <th style="width: 30%;">Pernyataan</th>' +
                '                    <th style="width: 5%;">Rate</th>' +
                '                </tr>' +
                '                </thead>' +
                '                <tbody></tbody>' +
                '            </table>');

            var SemesterID = filterSemester.split('.')[0];
            var ProdiID = filterProdi.split('.')[0];

            var token = jwt_encode({SemesterID:SemesterID, ProdiID:ProdiID },'UAP)(*');

            var dataTable = $('#tableELecturer').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 25,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "Room, Insert by, Invigilator"
                },
                "ajax":{
                    url : base_url_js+"api/__getLecturerEvaluation", // json datasource
                    ordering : false,
                    data : {token : token},
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                    }
                }
            } );

        }
    }
</script>