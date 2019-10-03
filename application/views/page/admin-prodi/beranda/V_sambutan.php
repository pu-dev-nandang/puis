<div class="row">

    
        <div class="col-md-6">
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <h4 class="panel-title"> ENGLISH OVERVIEW</h4>
                    </div>
                    <div class="panel-body">
                    <textarea class="form-control" rows="3" id="formWelcomingEng"></textarea>
                    </div>
                </div>
            
        </div>
        <div class="col-md-6">
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <h4 class="panel-title">INDONESIA OVERVIEW</h4>
                    </div>
                    <div class="panel-body">
                    <textarea class="form-control" rows="3" class="form-control" id="formWelcomingInd"></textarea>
                    </div>
                </div>
            
            <button id="btnSubmit" class="btn btn-primary" style="margin-top: 15px;float: right;">Save</button>
        </div>
   

</div>


<script type="text/javascript">

    $(document).ready(function(){
//=== menampilkan data summernote js=== //
        $('#formWelcomingEng,#formWelcomingInd').summernote({
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
        tampil_data_sambutan();

        
    });

    function tampil_data_sambutan(){

        var data = {action : 'viewDataProdi'};

        var token = jwt_encode(data,'UAP)(*');

        var url = base_url_js+'api-prodi/__crudDataProdi';

        $.post(url,{token:token},function(jsonResult){
            if(jsonResult.length>0){
                var d = jsonResult[0];
                $('#formWelcomingEng').summernote('code', d.WelcomingEng);
                $('#formWelcomingInd').summernote('code', d.WelcomingInd);
                // $('#formWelcomingEng').html(d.WelcomingEng);
                // $('#formWelcomingInd').html(d.WelcomingInd);
            }
        });
        
    }

    $('#btnSubmit').click(function () {

        var formWelcomingEng = $('#formWelcomingEng').val();
        var formWelcomingInd = $('#formWelcomingInd').val();
        // console.log(formWelcomingEng);return;
        if(formWelcomingEng!='' && formWelcomingEng!=null &&
            formWelcomingInd!='' && formWelcomingInd!=null){

            loading_button('#btnSubmit');

            var data = {
                action : 'updateDataProdi',
                dataForm : {
                    WelcomingEng : formWelcomingEng,
                    WelcomingInd : formWelcomingInd
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

