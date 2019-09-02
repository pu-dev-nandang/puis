

<style>
    #tableFileFP tr th, #tableFileFP tr td {
        text-align: center;
    }

    #tableFileFP td:first-child {
        border-right: 1px solid #CCCCCC;
    }

    #tableFileFP td:last-child {
        border-left: 1px solid #CCCCCC;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div id="divLoadTable"></div>

    </div>
</div>

<script>

    $(document).ready(function () {
        loadDataTable();
    });

    function loadDataTable() {

        $('#divLoadTable').html('<table id="tableFileFP" class="table table-striped">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th style="width: 20%;">Student</th>' +
            '                <th>Title</th>' +
            '                <th style="width: 25%;">Note</th>' +
            '                <th style="width: 10%;">Status</th>' +
            '            </tr>' +
            '            </thead>' +
            '        </table>');

        var data = {
            action : 'viewFileFinalProject'
        };

        var token = jwt_encode(data,'UAP)(*');

        var dataTable = $('#tableFileFP').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "NIM, Name Student, Titile TA"
            },
            "ajax":{
                url : base_url_js+"api3/__crudFileFinalProject", // json datasource
                data : {token:token},
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