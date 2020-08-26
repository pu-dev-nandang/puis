
<style>
    #sortEmp li ,
    #sortStd li {
        padding-top: 5px;
        padding-bottom: 5px;
        border: 1px solid rgb(214, 213, 213);
        padding-left: 10px;
        padding-right: 10px;
        margin-bottom: 5px;
        border-radius: 6px;
        cursor: grab;
    }
</style>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Action : <span id="viewAction">-</span></h4>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Type</label>
                            <select class="form-control" id="formType"></select>
                            <input class="hide" id="formID" value="">
                            <input class="hide" id="formDepartmentID" value="<?= $this->session->userdata('IDdepartementNavigation'); ?>">

                            <div id="dataFormEssay" class="hide" style="margin-top: 30px;">
                                <div class="form-group">
                                    <label>Answer Type</label>
                                    <select class="form-control" id="AnswerType" style="max-width: 200px;">
                                        <option value="textarea">Textarea</option>
                                        <option value="input">Input</option>
                                    </select>
                                </div>
                            </div>

                            <div id="showRate" class="" style="margin-top: 30px;">
                                <ul style="list-style-type:none;color: #ffa622;">
                                    <li><i class="fa fa-star" aria-hidden="true"></i> Kurang</li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i> Sedang</li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i> Cukup</li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i> Baik</li>
                                </ul>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <label>Question Category</label>
                            <select class="form-control" id="formQuestionCategory"></select>
                            <a href="javascript:void(0);" id="updateQuestionCategory">Manage question category</a>
                        </div>
                        <div class="col-md-4">
                            <label>Required</label>
                            <select class="form-control" id="formIsRequired">
                                <option value="1">Required</option>
                                <option value="0">Optional</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Description / Question</label>
                    <textarea id="formDescription"></textarea>
                    <input class="hide" id="formSummernoteID">
                </div>




            </div>
            <div class="panel-footer">
                <div style="text-align: right;">
                    <button class="btn btn-lg btn-success" id="btnSaveQuestion">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        loadSelectOptionSurvQuestionType('#formType',4);

        loadQuestionCategory();

        var tkn = getUrlParameter('tkn');
        var UnixMoment = moment().unix();

        if(typeof tkn !== 'undefined'){
            loading_modal_show();
            var dataToken = jwt_decode(tkn,'UAP)(*');
            getDataQuestion(dataToken.ID);
        } else {
            $('#formSummernoteID').val(sessionNIP+'_Survey_'+UnixMoment);
            $('#viewAction').html('Create');
        }

        $('#formDescription').summernote({
            placeholder: 'Text your description',
            height: 300,
            callbacks: {
                onImageUpload: function(image) {
                    var formSummernoteID = $('#formSummernoteID').val();
                    summernote_UploadImage('#formDescription',image[0],formSummernoteID);
                },
                onMediaDelete : function(target) {
                    summernote_DeleteImage(target[0].src);
                }
            }
        });

    });

    // ======= Question Category ========

    $('#updateQuestionCategory').click(function () {

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Simple Search</h4>');

        var htmlss = '<div class="row">' +
            '    <div class="col-md-12" style="margin-bottom: 15px;">' +
            '        <div class="well">' +
            '            <div class="row">' +
            '                <div class="col-md-10">' +
            '                    <input class="form-control" id="formQCategory" placeholder="Input Category...">' +
            '                    <input class="hide" id="formQID" /> ' +
            '                </div>' +
            '                <div class="col-md-2">' +
            '                    <button class="btn btn-success btn-block" id="btnSaceQCategory">Save</button>' +
            '                </div>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-12">' +
            '        <table class="table table-bordered table-centre">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 5%;">No</th>' +
            '                <th>Category</th>' +
            '                <th style="width: 10%;">Link</th>' +
            '                <th style="width: 20%;"><i class="fa fa-cog"></i></th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody id="listCategory"></tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';

        $('#GlobalModal .modal-body').html(htmlss);
        loadQuestionCategory();

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal').on('shown.bs.modal', function () {
            $('#formSimpleSearch').focus();
        });

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

    $(document).on('click','#btnSaceQCategory',function () {

        var formQCategory = $('#formQCategory').val();
        var formQID = $('#formQID').val();

        if(formQCategory!='' && formQCategory!=null){
            var data = {
                action : 'updateQuestionCategory',
                ID : (formQID!='' && formQID!=null) ? formQID : '',
                NIP : sessionNIP,
                DepartmentID : sessionIDdepartementNavigation,
                Description : formQCategory
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'apimenu/__crudSurvey';

            $.post(url,{token:token},function (jsonResult) {

                $('#formQCategory').val('');
                $('#formQID').val('');

                toastr.success('Data saved','Success');
                loadQuestionCategory();
            });

        }

    });

    $(document).on('click','.editQCategory',function () {
        var ID = $(this).attr('data-id');
        var Cat = $('#viewDesc_'+ID).text();
        $('#formQCategory').val(Cat);
        $('#formQID').val(ID);
    });

    $(document).on('click','.removeQCategory',function () {
        if(confirm('Are you sure?')){
            var ID = $(this).attr('data-id');
            var data = {
                action : 'removeQuestionCategory',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'apimenu/__crudSurvey';
            $.post(url,{token:token},function (jsonResult) {

                if(parseInt(jsonResult.Status)==1) {
                    toastr.success('Removed data','Success');
                } else {
                    toastr.warning('Can not removed','Warning');
                }

                loadQuestionCategory();


            });
        }
    });

    function loadQuestionCategory(){
        var data = {
            action : 'getQuestionCategory',
            DepartmentID : sessionIDdepartementNavigation
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';
        $('#listCategory,#formQuestionCategory').empty();

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var removeBtn = (parseInt(v.Link)>0) ? '' : ' <button class="btn btn-sm btn-danger removeQCategory" data-id="'+v.ID+'"><i class="fa fa-trash"></i></button>';

                    $('#listCategory').append('<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td style="text-align: left;" id="viewDesc_'+v.ID+'">'+v.Description+'</td>' +
                        '<td>'+v.Link+'</td>' +
                        '<td><button class="btn btn-primary btn-sm editQCategory" data-id="'+v.ID+'"><i class="fa fa-edit"></i></button>'+removeBtn+'</td>' +
                        '</tr>');

                    $('#formQuestionCategory').append('<option value="'+v.ID+'">'+v.Description+'</option>');
                })
            }

        });
    }

    // ======= Penutup Question Category ========

    $('#btnSaveQuestion').click(function () {

        var formType = $('#formType').val();
        var formDepartmentID = $('#formDepartmentID').val();
        var formQuestionCategory = $('#formQuestionCategory').val();
        var formIsRequired = $('#formIsRequired').val();
        var formSummernoteID = $('#formSummernoteID').val();
        var formDescription = $('#formDescription').val();
        var AnswerType = $('#AnswerType').val();

        if(formType!='' && formType!=null &&
        formQuestionCategory!='' && formQuestionCategory!=null &&
            formDescription!='' && formDescription!=null){

            loading_modal_show();

            var formID = $('#formID').val();

            var data = {
                action : 'updateDataQuestion',
                ID : (formID!='' && formID!=null) ? formID : '',
                NIP : sessionNIP,
                dataQuestion : {
                    DepartmentID : formDepartmentID,
                    QCID : formQuestionCategory,
                    QTID : formType,
                    SummernoteID : formSummernoteID,
                    Question : formDescription,
                    IsRequired : formIsRequired,
                    AnswerType : AnswerType
                }
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'apimenu/__crudSurvey';

            $.post(url,{token:token},function () {

                toastr.success('Question saved','Success');
                setTimeout(function () {
                    window.location.href='';
                },500);

            });

        } else {
            toastr.warning('All form are required','Warning');
        }

    });

    $('#formType').change(function () {
        loadQuestionType();
    });

    function loadQuestionType() {
        var formType = $('#formType').val();
        if(formType=='4'){
            $('#dataFormEssay').addClass('hide');
            $('#showRate').removeClass('hide');
        } else {
            $('#dataFormEssay').removeClass('hide');
            $('#showRate').addClass('hide');
        }

    }

    function getDataQuestion(ID) {

        var data = {
            action : 'readDataQuestion',
            ID : ID
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                var d = jsonResult[0];

                $('#formID').val(d.ID);

                $('#formType').val(d.QTID);
                $('#formDepartmentID').val(d.DepartmentID);

                $('#AnswerType').val(d.AnswerType);

                $('#formQuestionCategory').val(d.QCID);

                $('#formIsRequired').val(d.IsRequired);
                $('#formDescription').val(d.Question);
                $('#formSummernoteID').val(d.SummernoteID);

            } else {

            }

            setTimeout(function () {
                loading_modal_hide();
            },500);

        });

    }




</script>