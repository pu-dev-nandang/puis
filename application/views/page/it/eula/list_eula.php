

<style>
    .panel-description {
        /*min-height: 150px;*/
        overflow: auto;
        max-height: 150px;
    }
    .panel-title {
        padding: 10px;
        border: 1px solid #f3e7e7;
        border-radius: 5px;
        margin-bottom: 10px;
        background: #fff9f9;
    }
    #tableData td:first-child {
        border-right: 1px solid #cccccc;
    }
    .form-control[readonly] {
        background: #ffffff;
        color: #333333;
        cursor: pointer;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div id="loadTable"></div>
    </div>

</div>

<script>

    $(document).ready(function () {

        loadDataTable();

    });




    function loadDataTable() {
        $('#loadTable').html('<table class="table table-centre" id="tableData">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Data Eula</th>' +
            '                <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
            '            </tr>' +
            '            </thead>' +
            '        </table>');


        var data = {
            action : 'getListMasterEULA'
        };
        var token = jwt_encode(data,'UAP)(*');

        var url = base_url_js+'api4/__crudEula';

        var dataTable = $('#tableData').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Title, Description"
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