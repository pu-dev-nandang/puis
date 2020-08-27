

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <h1
            style="text-align: center;margin-top: 0px;
            margin-bottom: 30px;"><b>Manage Question</b></h1>
        <table class="table table-striped">
            <tbody>
            <tr>
                <td style="width: 15%;">Titile</td>
                <td style="width: 1%;">:</td>
                <td><?= $dataSurvey['Title']; ?></td>
            </tr>
            <tr>
                <td>Publication Date</td>
                <td>:</td>
                <td>
                    <?= date('d M Y',strtotime($dataSurvey['StartDate'])) ?> -
                    <?= date('d M Y',strtotime($dataSurvey['EndDate'])) ?>
                </td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td><?= $dataSurvey['StatusLabel']; ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<!--<pre>-->
<!--    --><?php //print_r($dataSurvey); ?>
<!--</pre>-->

<div class="row" style="margin-top: 20px;">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">List Question</h4>
            </div>
            <div class="panel-body" style="min-height: 100px;">

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-database margin-right"></i> Master Question</h4>
            </div>
            <div class="panel-body" style="min-height: 100px;">
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

                <div id="loadTable" style="margin-top: 20px;"></div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        setLoadFullPage();
        loadSelectOptionSurvQuestionType('#filterType','');
        loadSelectOptionSurvQuestionCategory('#filterQuestionCategory','');

        loadMasterQuestion();

        loadListQuestion();
    });
    $('.filter-table').change(function () {
        loadMasterQuestion();
    });

    function loadMasterQuestion() {

        $('#loadTable').html('<table id="tableData" class="table table-bordered table-striped table-centre">' +
            '               <thead>' +
            '                <tr style="background: #eceff1;">' +
            '                    <th style="width: 5%;">No</th>' +
            '                    <th>Question</th>' +
            '                    <th style="width: 20%;">Category</th>' +
            '                </tr>' +
            '                </thead>' +
            '           </table>');

        var filterType = $('#filterType').val();
        var filterQuestionCategory = $('#filterQuestionCategory').val();

        var data = {
            action : 'getMasterQuestion',
            DepartmentID : sessionIDdepartementNavigation,
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

    $(document).on('click','.btnAddToSurvey',function () {

        var ID = $(this).attr('data-id');

        var data = {
            action : 'addQuestionToSurvey',
            SurveyID : '<?= $dataSurvey['ID']; ?>',
            QuestionID : ID,
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';

        $.post(url,{token:token},function (jsonResult) {

            if(parseInt(jsonResult.Status)>0){
                toastr.success('Question added','Success');
            } else {
                toastr.warning('Question already exist','Success');
            }

        });


    });
    
    function loadListQuestion() {

        var data = {
            action : 'QuestionInMySurvey',
            SurveyID : '<?= $dataSurvey['ID']; ?>',
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';

        $.post(url,{token:token},function (jsonResult) {



        });

    }

</script>