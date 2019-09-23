

<div class="row">
    <div class="col-md-12">
        <div style="text-align: right;margin-bottom: 20px;">
            <button class="btn btn-default" id="btnReloadTalbe"><i class="fa fa-refresh margin-right"></i> Reload table</button>
        </div>
        <div id="loadTable"></div>
    </div>
</div>

<script>

    $(document).ready(function () {
        getDataLog();
    });

    function getDataLog() {

        $('#loadTable').html('<table class="table table-striped" id="tableDataLog">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th style="width: 25%;">Name</th>' +
            '                <th style="width: 15%;">Accessed At</th>' +
            '                <th style="width: 25%;">Accessed Lecturer</th>' +
            '                <th style="width: 25%;">Accessed Student</th>' +
            '                <th>URL</th>' +
            '            </tr>' +
            '            </thead>' +
            '        </table>');

        // var url = base_url_js+'api3/__getDataLogEmployees?u=2017090';
        var url = base_url_js+'api3/__getDataLogEmployees';

        var dataTable = $('#tableDataLog').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Type NIP, NPM, Name, URL..."
            },
            "ajax":{
                url :url, // json datasource
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

    $('#btnReloadTalbe').click(function () {

        loading_page_simple('#loadTable','center');

        setTimeout(function () {
            getDataLog();
        },1000);

    });

</script>