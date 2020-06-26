

<div class="row">
    <div class="col-md-12" id="loadTable">

    </div>
</div>

<script>

    $(document).ready(function () {
        getDataLog();
    });

    function getDataLog() {


        $('#loadTable').html('<table id="tableData" class="table table-striped table-bordered table-centre">' +
            '            <thead>' +
            '            <tr style="background: #eceff1;">' +
            '                <th style="width: 3%;">No</th>' +
            '                <th style="width: 10%;">User ID</th>' +
            '                <th style="width: 25%;">User</th>' +
            '                <th style="width: 7%;">Type</th>' +
            '                <th style="width: 7%;">Login By</th>' +
            '                <th style="width: 13%;">IP Local</th>' +
            '                <th style="width: 13%;">IP Public</th>' +
            '                <th>Entred At</th>' +
            '            </tr>' +
            '            </thead>' +
            '        </table>');

        var data = {
            action : 'getListPublicationDate',
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__getLogLogin';

        var dataTable = $('#tableData').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Username, Name, Login by...."
            },
            "ajax":{
                url :url, // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    loading_modal_hide();
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );

    }

</script>