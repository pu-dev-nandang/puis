
<div class="row">
    <div class="col-md-12">

    </div>

    <div class="col-md-8 col-md-offset-2">
        <a href="<?php echo base_url('lpmi/lecturer-evaluation/list-question'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left margin-right"></i> Back to list</a>
        <hr/>
        <table class="table">
            <tr>
                <td>Category</td>
                <td>:</td>
                <td>
                    <select class="form-control" id="formCategory" style="max-width: 250px;"></select>
                    <a href="javascript:void(0)" id="newCategory">Manage category</a>
                </td>
            </tr>
            <tr>
                <td>Order & Type</td>
                <td>:</td>
                <td>
                    <div class="row">
                        <div class="col-md-4">
                            <input class="form-control" id="formOrder" style="max-width: 100px;">
                        </div>
                        <div class="col-md-4" style="border-left: 1px solid #ccc;">
                            <label class="radio-inline">
                                <input type="radio" name="formType" id="tp0" value="0" checked> Multiple
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="formType" id="tp1" value="1"> Essay
                            </label>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Question</td>
                <td>:</td>
                <td>
                    <div class="row">
                        <div class="col-md-6">
                            Indonesia :
                            <textarea class="form-control" id="formQInd" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            English :
                            <textarea class="form-control" id="formQEng" rows="3"></textarea>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;">
                    <a href="<?php echo base_url('lpmi/lecturer-evaluation/list-question'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left margin-right"></i> Back to list</a>
                    <button class="btn btn-success" id="btnSaveQuestion" data-action="insertQuestion">Save</button>
                </td>
            </tr>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        var ID = "<?php echo $this->uri->segment(5); ?>";

        if(ID=='0' || ID==0){
            $('#formCategory').append('<option value="">-- Select Category --</option>');
            $('#formCategory').append('<option disabled>-------</option>');
            loadSelectOptionCategoryLecturerEvaluation('#formCategory','');
        } else {
            loadDataEdit();
        }

    });

    function loadDataEdit(){
        var ID = "<?php echo $this->uri->segment(5); ?>";
        var token = jwt_encode({action:'loadToEdit',ID:parseInt(ID)},'UAP)(*');
        var url = base_url_js+'api/__crudLecturerEvaluation';

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                var d = jsonResult[0];
                $('input[type=radio][name=formType]').prop('checked', false);
                $('#formOrder').val(d.Order);
                var qInd = (d.Question!='' && d.Question!=null) ? d.Question : '';
                var qEng = (d.QuestionEng!='' && d.QuestionEng!=null) ? d.QuestionEng : '';
                $('#formCategory').append('<option value="">-- Select Category --</option>');
                $('#formCategory').append('<option disabled>-------</option>');
                loadSelectOptionCategoryLecturerEvaluation('#formCategory',d.CategoryID);
                $('#formQInd').val(qInd);
                $('#formQEng').val(qEng);
                $('input[type=radio][name=formType][value='+d.Type+']').prop('checked', true);
                $('#btnSaveQuestion').attr('data-action','editQuestion');
            }
        });
    }

    $('#btnSaveQuestion').click(function () {

        var formCategory = $('#formCategory').val();

        var formOrder = $('#formOrder').val();
        var formType = $('input[type=radio][name=formType]:checked').val();
        var formQInd = $('#formQInd').val();
        var formQEng = $('#formQEng').val();

        if(formOrder!='' && formOrder!=null
            && formType!='' && formType!=null
            && formQInd!='' && formQInd!=null){

            var ID = "<?php echo $this->uri->segment(5); ?>";

            loading_button('#btnSaveQuestion');
            var action = $('#btnSaveQuestion').attr('data-action');

            var data = {
                action : action,
                ID : ID,
                dataForm : {
                    CategoryID : formCategory,
                    Order : formOrder,
                    Question : formQInd,
                    QuestionEng : formQEng,
                    Type : formType,
                    Status : '1'
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudLecturerEvaluation';

            $.post(url,{token:token},function (result) {
                toastr.success('Data saved','Success');
                setTimeout(function () {
                    window.location.href='';
                },500);
            });

        }
    });

    $(document).on('click','#newCategory',function () {
        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Coming Soon</b><hr/> ' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '</div>');
        $('#NotificationModal').modal('show');
    });
</script>