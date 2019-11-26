<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class=""><i class="icon-reorder"></i> Lecturers</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <?php if($this->session->userdata('IDdepartementNavigation') == 13){ ?>
                        <button class="btn btn-xs btn-default btn-f-appv" type="button" data-status="close" ><i class="fa fa-warning"></i> Need Approve for request updating biodata</button>
                        <?php } ?>
                        <span class="btn btn-xs" id="btn_addmk">
                            <i class="icon-plus"></i> Add Lecturer
                        </span>
                    </div>
                </div>
            </div>
            <div class="widget-content no-padding">

                <div class="table-responsive">
                    <table id="tableLecturers" class="table table-striped table-bordered table-hover table-responsive">
                        <thead>
                        <tr class="tr-center" style="background: #20485A;color: #ffffff;">
                            <th class="th-center" style="width: 10%;">NIP</th>
                            <th class="th-center" style="width: 10%;">NIDN</th>
                            <th class="th-center" style="width: 5%;">Photo</th>
                            <th class="th-center">Name</th>
                            <th class="th-center" style="width: 5%;">Gender</th>
                            <th class="th-center">Position</th>
                            <th class="th-center">Program Study</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="fetchRequestDataLec"></div>

<script>
    $(document).ready(function () {
        load_lecturers();

        /*ADDED BY FEBRI @ NOV 2019*/
        $("body #tableLecturers").on("click",".btn-appv",function(){
            var itsme = $(this);
            var NIP = itsme.data("nip");
            var data = {
                NIP : NIP
            };
            var token = jwt_encode(data,'UAP)(*');
            
            $.ajax({
                type : 'POST',
                url : base_url_js+"database/lecturers/request",
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
                    $("#fetchRequestDataLec").html(response);
                }
            });
        });
        
        $("body").on("click",".btn-f-appv",function(){
            var status = $(this).data("status");
            if(status == "close"){
                load_lecturers(true);                
                $(this).toggleClass("btn-default btn-info");
                $(this).data("status","open");
            }else{
                load_lecturers();          
                $(this).toggleClass("btn-info btn-default");
                $(this).data("status","close");
            }
            
        });
        
        /*END ADDED BY FEBRI @ NOV 2019*/
    });

    function load_lecturers(isappv=false) { // UPDATED BY FEBRI @ NOV 2019
        var dataTable = $('#tableLecturers').DataTable( {
            "destroy": true,  // UPDATED BY FEBRI @ NOV 2019
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api/__getLecturer", // json datasource
                ordering : false,
                type: "post",  // method  , by default get
                data: {isappv:isappv},  // UPDATED BY FEBRI @ NOV 2019
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        });
    }
</script>
