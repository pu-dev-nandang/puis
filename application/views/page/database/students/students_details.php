
<?php //print_r($dataForm); ?>

<style>
    #tableStudents thead tr th {
        text-align: center;
    }
</style>

<div class="widget box">
    <div class="widget-header">
        <h4 class=""><i class="icon-reorder"></i> Students</h4>
        <div class="toolbar no-padding">
            <div class="btn-group">
                        <span class="btn btn-xs" id="btn_addmk">
                            <i class="icon-plus"></i> Add Students
                        </span>
            </div>
        </div>
    </div>
    <div class="widget-content no-padding">

        <div class="table-responsive">
            <table id="tableStudents" class="table table-striped table-bordered table-hover table-responsive">
                <thead>
                <tr class="tr-center" style="background: #20525a;color: #ffffff;">
<!--                    <th style="width: 10%;">NIM</th>-->
                    <th style="width: 5%;">Photo</th>
                    <th>Name</th>
                    <th style="width: 5%;">Gender</th>
                    <th style="width: 5%;">Action</th>
                    <th style="width: 10%;">Login Portal</th>
<!--                    <th class="th-center">Program Study</th>-->
                    <th style="width: 5%;">Status</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>

<script>

    $(document).ready(function () {
        setTimeout(function (args) { load_students(); },500);
    });

    function load_students() {

        var dataYear = '<?php echo $dataForm["Year"] ?>';
        var dataProdiID = '<?php echo $dataForm["ProdiID"] ?>';
        var dataStatus = '<?php echo $dataForm["StatusStudents"] ?>';

        var dataTable = $('#tableStudents').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api/__getStudents?dataYear="+dataYear+"&&dataProdiID="+dataProdiID+'&&s='+dataStatus, // json datasource
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );
    }
</script>
