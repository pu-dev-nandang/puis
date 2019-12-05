
<style>
    #tableEmployees tr th{
        text-align: center;
    }
</style>

<div class="filter-form" style="margin:0 auto;width:45%;text-align:center;margin-bottom:3em;">
    <div class="row">
        <div class="col-md-5">
            <div class="thumbnail">
                <select class="form-control" id="filterStatusEmployees">
                    <option value="">--- All Status Employees ---</option>
                </select>
            </div>
        </div>
        <div class="col-sm-2 col-md-2" style="padding:5px 0px">
            <button class="btn btn-default" type="button" id="btn-need-appv" data-status="close"><i class="fa fa-warning"></i> Need approval for request biodata</button>
        </div>
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
        <tr style="background: #20485A;color: #FFFFFF;">

            <th style="width: 5%;">Photo</th>
            <th style="width: 20%;">Name</th>
            <th style="width: 15%;">Position Main</th>
            <th>Address</th>
            <th style="width: 5%;">Status</th>
        </tr>
        </thead>
    </table>
</div>
<div id="fetchRequestDataEmp"></div>
<script>

    $(document).ready(function () {
        loadSelectOptionStatusEmployee('#filterStatusEmployees','');
        loadDataEmployees();

        $('#filterStatusEmployees').change(function () {
            var s = $(this).val();
            //UPDATED BY FEBRI @ NOV 2019
            var isAppv = ( ($('#btn-need-appv').data('status') == "close") ? false:true );
            loadDataEmployees(s,isAppv);
            //END UPDATED BY FEBRI @ NOV 2019
        });

    //UPDATED BY FEBRI @ NOV 2019
        $("#btn-need-appv").click(function(){
            var status = $(this).data("status");
            var filterStatus = $('#filterStatusEmployees').val();
            if(status == "close"){
                $(this).toggleClass("btn-default btn-info");
                $(this).data("status","open");
                loadDataEmployees(filterStatus,true);
            }else{
                $(this).toggleClass("btn-info btn-default");
                $(this).data("status","close");
                loadDataEmployees();            
            }
        });


        $("body #tableEmployees").on("click",".btn-appv",function(){
            var itsme = $(this);
            var NIP = itsme.data("nip");
            var data = {
                NIP : NIP
            };
            var token = jwt_encode(data,'UAP)(*');
            
            $.ajax({
                type : 'POST',
                url : base_url_js+"human-resources/employee-request",
                data: {token:token},
                dataType : 'html',
                beforeSend :function(){
                    $('#globalModal .modal-body').html('<i class="fa fa-spinner fa-pulse fa-fw" style="margin-right: 5px;"></i> Loading...');
                    itsme.prop("disabled",true);
                },error : function(jqXHR){
                    $("body #GlobalModal .modal-header").html("<h1>Error notification</h1>");
                    $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                    $("body #GlobalModal").modal("show");
                },success : function(response){
                    itsme.prop("disabled",false);
                    $("#fetchRequestDataEmp").html(response);
                }
            });
        });
        
    //END UPDATED BY FEBRI @ NOV 2019

    });


    function loadDataEmployees(status='',isappv=false) { //UPDATED BY FEBRI @ NOV 2019
        var dataTable = $('#tableEmployees').DataTable( {
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api/__getEmployeesHR?s="+status, // json datasource
                ordering : false,
                type: "post",  // method  , by default get
                data: {isappv:isappv},  // UPDATED BY FEBRI @ NOV 2019
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );
    }
</script>