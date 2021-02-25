<div class="row">
    <div class="col-md-12" style="margin-bottom: 20px;text-align: right;">
        <a href="<?= base_url('academic/exam-schedule/exam-quiz-create'); ?>" target="_blank" class="btn btn-success">Create Exam Quiz</a>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">List Quiz</h4>
            </div>
            <div class="panel-body">
                <div id="divTable"></div>
            </div>
        </div>
    </div>
</div>




<script>
    $(document).ready(function() {
        getListQuiz();
    });

    function getListQuiz() {

        $('#divTable').html('<table id="tableShowingData" class="table table-bordered table-centre">' +
            '                    <thead>' +
            '                    <tr>' +
            '                        <th style="width: 3%;">No</th>' +
            '                        <th>Title</th>' +
            '                        <th style="width: 10%;">' +
            '                            <i class="fa fa-cog"></i>' +
            '                        </th>' +
            '                        <th style="width: 3%;">Question</th>' +
            '                        <th style="width: 3%;">Link</th>' +
            '                    </tr>' +
            '                    </thead>' +
            '                </table>');

        var data = {
            action: 'getQuizExam'
        };

        var token = jwt_encode(data, 'UAP)(*');
        var url = base_url_js + 'api4/__crudQuiz';

        window.dataTable = $('#tableShowingData').DataTable({
            "processing": true,
            "serverSide": true,
            "iDisplayLength": 10,
            "ordering": false,
            "language": {
                "searchPlaceholder": "Day, Room, Name / NIP Invigilator"
            },
            "ajax": {
                url: url, // json datasource
                data: {
                    token: token
                },
                ordering: false,
                type: "post", // method  , by default get
                error: function() { // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                }
            }
        });

    }
</script>