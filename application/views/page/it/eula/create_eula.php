
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
                    <label>Title</label>
                    <input class="form-control" id="formTitle" placeholder="Title">
                    <input class="hide" id="formID">
                    <input class="hide" id="formSummernoteID">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="formDescription"></textarea>
                </div>
            </div>
            <div class="panel-footer">
                <div style="text-align: right;">
                    <button class="btn btn-lg btn-success" id="btnSaveNQueue">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>





<script>
    $(document).ready(function () {

        var ID = getUrlParameter('id');
        var UnixMoment = moment().unix();

        if(typeof ID !== 'undefined'){
            loading_modal_show();
            getDataEula(ID);
        } else {
            $('#formSummernoteID').val(sessionNIP+'_'+UnixMoment);
            $('#viewAction').html('Create');
        }

        $('#formDescription').summernote({
            placeholder: 'Text your description',
            height: 400,
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

    function getDataEula(ID){
        var data = {
            action : 'getDataEula',
            ID : ID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudEula';
        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                $('#viewAction').html('Edit');

                var d = jsonResult[0];


                $('#formTitle').val(d.Title);
                $('#formID').val(d.ID);
                $('#formSummernoteID').val(d.SummernoteID);

                $('#formDescription').summernote('code',d.Description);

            } else {
                $('#viewAction').html('Create');
            }

            setTimeout(function () {
                loading_modal_hide();
            },1000);
        });
    }

    $('#btnSaveNQueue').click(function () {

        var formTitle = $('#formTitle').val();
        var formDescription = $('#formDescription').val();


            if(formTitle!='' && formTitle!=null &&
                formDescription!='' && formDescription!=null){

                loading_modal_show();

                var formID = $('#formID').val();
                var formSummernoteID = $('#formSummernoteID').val();

                var data = {
                    action : 'updateDataEula',
                    ID : (formID!='' && formID!=null) ? formID : '',
                    Title : formTitle,
                    Description : formDescription,
                    SummernoteID : formSummernoteID
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api4/__crudEula';

                $.post(url,{token:token},function (jsonResult) {

                    if(formID==''){
                        $('#formTitle').val('');
                        $('#formDescription').summernote('reset');
                    }

                    toastr.success('Data saved','Success');

                    setTimeout(function () {
                        loading_modal_hide();
                    },500);

                    // loadSetQuery(EmpDate,StdDate,jsonResult.ID);
                });

            } else {
                toastr.warning('Title & Description are required','Warning');
            }


    });



</script>