
<style>
    #tableEmployees tr th{
        text-align: center;
    }
</style>

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
border-radius: 17px;
}
</style> 

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="thumbnail">
            <select class="form-control" id="filterStatusEmployees">
                <option value="">--- All Status Employees ---</option>
            </select>
        </div>
        <hr/>
    </div>
    
</div>


<div class="thumbnail" style="padding: 10px; text-align: right;margin-bottom: 10px;">
    <span style="color: #4CAF50;margin-right: 5px;margin-left: 5px;"><i class="fa fa-circle" style="margin-right: 5px;"></i> Permanent Employees </span> |
    <span style="color: #FF9800;margin-right: 5px;margin-left: 5px;"><i class="fa fa-circle" style="margin-right: 5px;"></i> Contract Employees </span> |
    <span style="color: #03A9F4;margin-right: 5px;margin-left: 5px;"><i class="fa fa-circle" style="margin-right: 5px;"></i> Permanent Lecturer </span> |
    <span style="color: #9e9e9e;margin-right: 5px;margin-left: 5px;"><i class="fa fa-circle" style="margin-right: 5px;"></i> Contract Lecturer </span> |
    <span style="color: #F44336;margin-right: 5px;margin-left: 5px;"><i class="fa fa-warning" style="margin-right: 5px;"></i> Non Active </span>
</div>
<hr/>
<div class="">
    <table class="table table-bordered table-striped" id="tableEmployees">
        <thead>
        <tr style="background: #1E90FF;color: #FFFFFF;">
            <th style="width: 1%;">No</th>
            <th style="width: 5%;">Foto</th>
            <th class="th-center" style="width: 12%;">Name</th>
            <th style="width: 12%;">Position Main</th>
            <th style="width: 5%;">Status</th>
            <th style="width: 49%;">Status Files</th>
            <th>Total</th>
            <th style="width: 5%;">Action</th>
        </tr>
        </thead>
    </table>
</div>

<script>

    $(document).ready(function () {
        loadSelectOptionStatusEmployee('#filterStatusEmployees','');
        loadDataEmployees('');
    });

    $('#filterStatusEmployees').change(function () {
        var s = $(this).val();
        loadDataEmployees(s);
    });

    function loadDataEmployees(status) {
        var dataTable = $('#tableEmployees').DataTable( {
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api/__getfileEmployeesHR?s="+status, // json datasource
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