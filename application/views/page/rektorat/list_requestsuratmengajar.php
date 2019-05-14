<style>
  .btn-circle.btn-xl {
    width: 70px;
    height: 70px;
    padding: 10px 16px;
    border-radius: 35px;
    font-size: 24px;
    line-height: 1.33;
}

.btn-circle {
    width: 30px;
    height: 30px;
    padding: 6px 0px;
    border-radius: 15px;
    text-align: center;
    font-size: 12px;
    line-height: 1.42857;
}

.btn-round{
    border-radius: 10px;
}

.btn-group > .btn:first-child, .btn-group > .btn:last-child {
     border-radius: 17px;
}

</style>


<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class=""><i class="icon-reorder"></i> Lecturers</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <!-- <span class="btn btn-xs" id="btn_addmk">
                            <i class="icon-plus"></i> Add Lecturer
                        </span>
                    -->

                    </div>
                </div>
            </div>
            <div class="widget-content no-padding">

                <div class="table-responsive">
                    <table id="tableLecturers" class="table table-striped table-bordered table-hover table-responsive">
                        <thead>
                        <tr class="tr-center" style="background: #3968c6;color: #ffffff;">
                            <th class="th-center" style="width: 5%;">Photo</th>
                            <th class="th-center" style="width: 15%;">Name</th>
                            <th class="th-center" style="width: 12%;">NIP & NIDN</th>
                            <th class="th-center" style="width: 6%;">Gender</th>
                            <th class="th-center" style="width: 11%;">Position</th>
                            <th class="th-center" style="width: 20%;">Program Study</th>
                            <th class="th-center" style="width: 10%;">Semester</th>
                            <th class="th-center" style="width: 7%;">Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        load_lecturers();
        
    });

    function load_lecturers() {
        var dataTable = $('#tableLecturers').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api/__getLecturermengajar", // json datasource
                ordering : false,
                type: "post",
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );

        
    }
</script>

<script>

    $(document).on('click','#btnDownloadTugasMengajar',function () {
            
            var sesNIP = $(this).attr('NIP');
            var filterSemester = $('#filterSemester_'+sesNIP).val();
    
            var data = {
                SemesterID : filterSemester,
                NIP : sesNIP
            };
            var token = jwt_encode(data,'UAP)(*');
            window.open(base_url_js+'save2pdf/suratMengajar/'+token);

        });
</script>