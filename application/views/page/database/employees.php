
<style>
    #tableEmployees thead tr th {
        background: #20525a;
        color: #ffffff;
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="well">
            <div class="row">
                <div class="col-xs-12">
                    <select id="filterStatusEmployees" class="form-control">
                        <option value="">-- All Status --</option>
                        <option disabled>------------------------------</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="divDataEmployees"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadSelectOptionStatusEmployee('#filterStatusEmployees','');
        loadDataEmployees();
    });

    $('#filterStatusEmployees').change(function () {
        loadDataEmployees();
    });

    function loadDataEmployees() {
        loading_page('#divDataEmployees');

        setTimeout(function () {
            $('#divDataEmployees').html('<table class="table table-bordered table-striped" id="tableEmployees">' +
                '            <thead>' +
                '            <tr>' +
                '                <th style="width: 1%;">No</th>' +
                '                <th style="width: 7%;">NIP</th>' +
                '                <th style="width: 5%;">Photo</th>' +
                '                <th>Name</th>' +
                '                <th style="width: 5%;">Gender</th>' +
                '                <th style="width: 15%;">Position</th>' +
                '                <th style="width: 25%;">Address</th>' +
                '                <th style="width: 7%;">Action</th>' +
                '                <th style="width: 7%;">Status</th>' +
                '            </tr>' +
                '            </thead>' +
                '        </table>');

            var filterStatusEmployees = $('#filterStatusEmployees').val();

            var token = jwt_encode({StatusEmployeeID : filterStatusEmployees},'UAP)(*');

            var dataTable = $('#tableEmployees').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "NIP / NIK, Name"
                },
                "ajax":{
                    url : base_url_js+'api/database/__getListEmployees', // json datasource
                    ordering : false,
                    data : {token:token},
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                    }
                }
            } );

        },500);



    }

    // Reset Password
    $(document).on('click','.btn-reset-password',function () {

        if(confirm('Reset password ?')){
            var token = $(this).attr('data-token');
            var DataToken = jwt_decode(token,'UAP)(*');
            if(DataToken.Email!='' && DataToken.Email!=null){

                $('#NotificationModal .modal-body').html('<div style="text-align: center;">Reset Password has been send to : <b style="color: blue;">'+DataToken.Email+'</b><hr/><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>');
                $('#NotificationModal').modal('show');

                DataToken.DueDate = dateTimeNow();
                var newToken = jwt_encode(DataToken,'UAP)(*');

                var url = base_url_js+'database/sendMailResetPassword';
                $.post(url,{token:newToken},function (result) {

                });
            } else {
                toastr.error('Email Empty','Error');
            }
        }

    });

</script>
