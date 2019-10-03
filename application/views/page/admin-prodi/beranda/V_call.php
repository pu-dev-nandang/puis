<div class="row">


        <div class="col-md-6">
            
                <div class="panel panel-default ">
                    <div class="panel-heading">
                    <h4 class="panel-title">Call To Action</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            
                            <label>ENGLISH Content</label>
                            <textarea class="form-control" id="formContentEng"></textarea>
                        </div>
                        <div class="form-group">
                            <label>INDONESIA Content</label>
                            <textarea class="form-control" id="formContentInd"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Number to call</label>
                            <input type="text" id="formDataCall" class="form-control" placeholder="Ex: 081234567890">
                        </div>
                        <div class="form-group">
                        <button id="btnSubmit" class="btn btn-primary" style="margin-top: 15px;float: right;">Save</button>
                        </div>

                    </div>
                </div>
            
           
        </div>
       
   

</div>


<script type="text/javascript">

    $(document).ready(function(){
//=== menampilkan data summernote js=== //
        $('#formContentEng,#formContentInd').summernote({
            placeholder: 'Text your announcement',
            tabsize: 2,
            height: 300,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });

        // console.log(base_url);
        view_data_call();

        
    });

    function view_data_call(){

        var data = {action : 'viewDataProdi'};

        var token = jwt_encode(data,'UAP)(*');

        var url = base_url_js+'api-prodi/__crudDataProdi';

        $.post(url,{token:token},function(jsonResult){
            if(jsonResult.length>0){
                var d = jsonResult[0];
                $('#formContentEng').summernote('code', d.ContentCallEng);
                $('#formContentInd').summernote('code', d.ContentCallInd);
                $('#formDataCall').val(d.CallAction);
            }
        });
        
    }

    $('#btnSubmit').click(function () {

        var formContentEng = $('#formContentEng').val();
        var formContentInd = $('#formContentInd').val();
        var formDataCall = $('#formDataCall').val();

        if(formContentEng!='' && formContentEng!=null &&
            formContentInd!='' && formContentInd!=null &&
            formDataCall!='' && formDataCall!=null){

            loading_button('#btnSubmit');

            var data = {
                action : 'updateDataProdi',
                dataForm : {
                    ContentCallEng : formContentEng,
                    ContentCallInd : formContentInd,
                    CallAction : formDataCall
                }
            };

            var token = jwt_encode(data,'UAP)(*');

            var url = base_url_js+'api-prodi/__crudDataProdi';

            $.post(url,{token:token},function (jsonResult) {

                toastr.success('Data saved','Success');
                setTimeout(function () {
                    $('#btnSubmit').html('Save').prop('disabled',false);
                },500);

            });

        } else {
            toastr.error('Form required','Error');
        }


    });




</script>

