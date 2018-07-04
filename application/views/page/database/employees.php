<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class=""><i class="icon-reorder"></i> Employees</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <a href="<?php echo base_url('database/employees/form_input_add'); ?>">
                            <span class="btn btn-xs" id="btn_addmk">
                                <i class="icon-plus"></i> Add Employees
                            </span>
                        </a> 
                    </div>
                </div>
            </div>
            <div class="widget-content no-padding">

                <div class="table-responsive">
                    <table id="tableLecturers" class="table table-striped table-bordered table-hover table-responsive">
                        <thead>
                        <tr class="tr-center" style="background: #20485A;color: #ffffff;">
                            <th class="th-center" style="width: 10%;">NIP</th>
                            <th class="th-center" style="width: 5%;">Photo</th>
                            <th class="th-center">Name</th>
                            <th class="th-center" style="width: 5%;">Gender</th>
                            <th class="th-center">Position</th>
                            <th class="th-center">Email PU</th>
                            <th class="th-center">Status</th>
                            <th class="th-center">Action</th>
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
        load_data();
    });

    function load_data() {
        var dataTable = $('#tableLecturers').DataTable( {
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api/__getEmployees", // json datasource
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

    $(document).on('click','.btn-edit', function () {
      var NIP = $(this).attr('data-smt');
      window.location.href = base_url_js+'database/employees/form_input_add/'+NIP;
    });

    $(document).on('click','.btn-Active', function () {
      var NIP = $(this).attr('data-smt');
      var Active = $(this).attr('data-active');
       $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Apakah anda yakin untuk melakukan request ini ?? </b> ' +
           '<button type="button" id="confirmYesActive" class="btn btn-primary" style="margin-right: 5px;" data-smt = "'+NIP+'" data-active = "'+Active+'">Yes</button>' +
           '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
           '</div>');
       $('#NotificationModal').modal('show');
       $("#confirmYesActive").click(function(){
            $('#NotificationModal .modal-header').addClass('hide');
            $('#NotificationModal .modal-body').html('<center>' +
                '                    <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>' +
                '                    <br/>' +
                '                    Loading Data . . .' +
                '                </center>');
            $('#NotificationModal .modal-footer').addClass('hide');
            $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
            });
            var url = base_url_js+'database/employees/changestatus';
            var data = {
                NIP : NIP,
                Active:Active,
            };
            var token = jwt_encode(data,"UAP)(*");
            $.post(url,{token:token},function (data_json) {
                setTimeout(function () {
                   toastr.options.fadeOut = 10000;
                   toastr.success('Data berhasil disimpan', 'Success!');
                   load_data();
                   $('#NotificationModal').modal('hide');
                },2000);
            });
       })
    });
</script>
