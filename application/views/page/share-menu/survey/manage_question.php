

<style>

    #listQuestion {
        padding-inline-start: 15px;
    }

    .item-question:hover {
        cursor: pointer;
    }

    .item-question:hover div {
        background: lightyellow;
    }

    .item-question {
        position: relative;
    }

    .item-question div {
        border: 1px solid #ccc;
        padding: 10px 10px 0px 10px;
        border-radius: 5px;
        width: 90%;
        margin-bottom: 9px;

        max-height: 100px;
        overflow: auto;
    }

    .item-question button {
        position: absolute;
        top: 0px;
        right: 0px;
    }
</style>

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

        <div class="alert alert-info" style="margin-top: 20px;">
            Questions can only be changed if the survey status is <b>unpublished</b>
        </div>

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
                <div id="viewListQuestion"></div>
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

        var Status = "<?= $dataSurvey['Status']; ?>";
        if(parseInt(Status)<=0){

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
                    loadListQuestion();
                } else {
                    toastr.warning('Question already exist','Success');
                }
            });

        } else {
            toastr.error('The list of questions cannot be changed','Error');
        }




    });
    
    function loadListQuestion() {

        var data = {
            action : 'QuestionInMySurvey',
            SurveyID : '<?= $dataSurvey['ID']; ?>',
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';

        loading_page_simple('#viewListQuestion','center');

        $.post(url,{token:token},function (jsonResult) {

            console.log(jsonResult);

            var li = '';
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    li = li+'<li class="item-question" data-id="'+v.ID+'">' +
                        '<div>'+
                        '<span class="label label-default">'+v.Category+'</span>' +
                        v.Question+'</div>' +
                        '<button data-id="'+v.ID+'" class="btn btn-sm btn-danger btnRemoveQuestion">' +
                        '<i class="fa fa-trash"></i></button></li>';

                });
            }

            setTimeout(function () {

                if(jsonResult.length>0){
                    $('#viewListQuestion').html('<ol id="listQuestion">'+li+'</ol>');

                    var StatusSurvey = "<?= $dataSurvey['Status']; ?>";

                    if(parseInt(StatusSurvey)<=0){
                        $('#listQuestion').sortable({
                            axis: 'y',
                            update: function (event, ui) {
                                var No = 1;
                                $('#listQuestion li.item-question').each(function () {
                                    var ID = $(this).attr('data-id');
                                    updateQueue(ID,No);
                                    No += 1;
                                });

                                // $('#dataTempQuiz').val(JSON.stringify(dataUpdate));

                            }
                        });
                    }


                } else {
                    $('#viewListQuestion').html('No question');
                }

            },500);

        });

    }

    $(document).on('click','.btnRemoveQuestion',function () {

        var Status = "<?= $dataSurvey['Status']; ?>";

        if(parseInt(Status)<=0){
            if(confirm('Are you sure?')){

                var ID = $(this).attr('data-id');

                var data = {
                    action : 'removeQUestionFromSurvey',
                    ID : ID
                };
                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'apimenu/__crudSurvey';

                $.post(url,{token:token},function (result) {
                    loadListQuestion();
                    toastr.success('Question removed from survey','Success');
                });

            }
        } else {
            toastr.error('The list of questions cannot be changed','Error');
        }



    });

    function updateQueue(ID,Queue) {

        var data = {
            action : 'updateQueueQuestion',
            ID : ID,
            Queue : Queue
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';

        $.post(url,{token:token},function (result) {
            // toastr.success('Question removed from survey','Success');
        });

    }
</script>