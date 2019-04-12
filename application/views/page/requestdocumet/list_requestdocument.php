<div class="row">
    <div class="col-md-2 col-md-offset-4">
        <div class="thumbnail">
            <select class="form-control" id="filterSemester"></select>
        </div>
        <hr/>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class=""><i class="icon-reorder"></i> Data Lecturers</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group"></div>
                </div>
            </div>
            <div class="widget-content no-padding">

                <div id="viewTbale"></div>

            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        
        loSelectOptionSemester('#filterSemester','');

        var loadFisrt = setInterval(function () {
                var filterSemester = $('#filterSemester').val();            
                if(filterSemester!='' && filterSemester!=null) {
                    load_lecturers();
                    clearInterval(loadFisrt);
                }
        },1000);

    });

    $('#filterSemester').change(function () {
        load_lecturers();
    });

    function load_lecturers() {



        $('#viewTbale').html('<table id="tableLecturers" class="table table-striped table-bordered table-hover"><thead><tr class="tr-center" style="background: #3968c6;color: #ffffff;"><th class="th-center" style="width: 5%;">Photo</th><th class="th-center">Name</th><th class="th-center" style="width: 10%;">NIP</th><th class="th-center" style="width: 10%;">NIDN</th><th class="th-center" style="width: 5%;">Gender</th><th class="th-center">Position</th><th class="th-center">Program Study</th><th class="th-center" style="width: 5%;"> Action</th></tr></thead><tbody></tbody></table>');

    

        var filterSemester = $('#filterSemester').val();
        var SemsterID = filterSemester.split('.')[0];

        var dataTable = $('#tableLecturers').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api/__getreqLecturer?s="+SemsterID, // json datasource
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


    function loSelectOptionSemester() {
        var url = base_url_js+'api/__crudSemester';
        var token = jwt_encode({action:'read',order:'DESC'},'UAP)(*');

        $.post(url,{token:token},function (data_json) {

            if(data_json.length>0){
                for(var i=0;i<data_json.length;i++){
                    $('#filterSemester').append('<option value="'+data_json[i].ID+'.'+data_json[i].Year+'.'+data_json[i].Code+'"> '+data_json[i].Name+' </option>');
                }
            }
        });
    }


</script>
