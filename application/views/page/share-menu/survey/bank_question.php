

<div class="">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="well">
                <div class="row">
                    <div class="col-md-6">
                        <label>Question Type</label>
                        <select class="form-control filter-table" id="filterType">
                            <option value="">--- All Type ---</option>
                            <option disabled>-----------------------</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Question Category</label>
                        <select class="form-control filter-table" id="filterQuestionCategory">
                            <option value="">--- All Category ---</option>
                            <option disabled>-----------------------</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="loadTable"></div>
        </div>
    </div>

</div>

<script>

    $(document).ready(function () {
        loadSelectOptionSurvQuestionType('#filterType','');
        loadSelectOptionSurvQuestionCategory('#filterQuestionCategory','');

        loadDataBankQuestion();
    });

    $('.filter-table').change(function () {
        loadDataBankQuestion();
    });

    function loadDataBankQuestion() {

        $('#loadTable').html('<table id="tableData" class="table table-bordered table-striped table-centre">' +
            '               <thead>' +
            '                <tr style="background: #eceff1;">' +
            '                    <th style="width: 5%;">No</th>' +
            '                    <th>Question</th>' +
            '                    <th style="width: 10%;"><i class="fa fa-cog"></i></th>' +
            '                    <th style="width: 20%;">Category</th>' +
            '                </tr>' +
            '                </thead>' +
            '           </table>');

        var filterType = $('#filterType').val();
        var filterQuestionCategory = $('#filterQuestionCategory').val();

        var data = {
            action : 'getBankQuestion',
            Type : filterType,
            QuestionCategory : filterQuestionCategory
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';

        var dataTable = $('#tableData').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Question..."
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