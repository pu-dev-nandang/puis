

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
            '                    <th style="width: 7%;">Link</th>' +
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

    $(document).on('click','.removeThisQuestion',function () {

        if(confirm('Are you sure?')){
            var ID = $(this).attr('data-id');

            var data = {
                action : 'removeQuestion',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'apimenu/__crudSurvey';

            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.Status==1){
                    loadDataBankQuestion();
                    toastr.success('Removed','Success');
                } else {
                    toastr.warning('Questions cannot be deleted','Warning');
                }

            });
        }

    });

    $(document).on('click','.showLinkSurvey',function () {
        var ID = $(this).attr('data-id');
        var data = {
            action : 'showLinkQuestion',
            ID : ID
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';

        $.post(url,{token:token},function (jsonResult) {

            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Link Survey</h4>');

            var tr = '';
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {
                    // 0 = Unpublish, 1 = Publish, 2 = Close
                    var status = 'Unpublish';
                    if(v.Status==1 || v.Status=='1'){
                        status = 'Publish';
                    } else if(v.Status==2 || v.Status=='2'){
                        status = 'Close';
                    }

                    var range = moment(v.StartDate).format('DD MMM YYYY')+
                        ' - '+moment(v.EndDate).format('DD MMM YYYY');
                   tr = tr+'<tr>' +
                       '<td>'+(i+1)+'</td>' +
                       '<td style="text-align: left;"><b>'+v.Title+'</b><br/>'+range+'</td>' +
                       '<td>'+status+'</td>' +
                       '</tr>';
                });
            } else {
                tr = '<tr><td colspan="3">No link</td></tr>';
            }

            var htmlss = '<div class="table-responsive">' +
                '    <table class="table table-bordered table-striped table-centre">' +
                '        <thead>' +
                '        <tr>' +
                '            <th style="width: 1%;">No</th>' +
                '            <th>Title</th>' +
                '            <th style="width: 10%;">Status</th>' +
                '        </tr>' +
                '        </thead>' +
                '        <tbody>'+tr+'</tbody>' +
                '    </table>' +
                '</div>';

            $('#GlobalModal .modal-body').html(htmlss);

            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

            $('#GlobalModal').on('shown.bs.modal', function () {
                $('#formSimpleSearch').focus();
            });

            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });

        });

    });

</script>