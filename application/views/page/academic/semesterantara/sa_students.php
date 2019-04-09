

<style>
    #tableStudent th, #tableStudent td {
        text-align: center;
    }

    .sp-sts {
        font-size: 12px;
    }
</style>

<div class="row">

    <div class="col-xs-6" style="border-right: 1px solid #CCCCCC;">
        <div class="">

            <div id="viewStudent"></div>

        </div>
    </div>

</div>

<script>

    $(document).ready(function () {

        loadStudents();

    });

    function loadStudents() {

        $('#viewStudent').html('<table class="table table-striped" id="tableStudent">' +
            '                <thead>' +
            '                <tr>' +
            '                    <th style="width: 1%;">No</th>' +
            '                    <th style="width: 30%;">Student</th>' +
            '                    <th>Course</th>' +
            '                    <th style="width: 1%;"><i class="fa fa-cog"></i></th>' +
            '                </tr>' +
            '                </thead>' +
            '            </table>');

        var data = {
            SASemesterID : '<?=$SASemesterID; ?>'
        };

        var token = jwt_encode(data,'UAP)(*');

        var dataTable = $('#tableStudent').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Group, Lecturer"
            },
            "ajax":{
                url : base_url_js+"api2/__getStudentSA", // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        });

    }

</script>