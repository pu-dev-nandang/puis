<div class="row">

    
        <div class="col-md-6">
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <h4 class="panel-title"> ENGLISH WHY</h4>
                    </div>
                    <div class="panel-body">
                    <textarea class="form-control" id="formWhyEng"></textarea>
                    </div>
                </div>
            
        </div>
        <div class="col-md-6">
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <h4 class="panel-title">INDONESIA WHY</h4>
                    </div>
                    <div class="panel-body">
                    <textarea class="form-control" id="formWhyInd"></textarea>
                    </div>
                </div>
            
            
        </div>
        <div class="col-md-6">
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <h4 class="panel-title"> ENGLISH ABOUT</h4>
                    </div>
                    <div class="panel-body">
                    <textarea class="form-control" id="formTentangEng"></textarea>
                    </div>
                </div>
            
        </div>
        <div class="col-md-6">
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <h4 class="panel-title">INDONESIA TENTANG</h4>
                    </div>
                    <div class="panel-body">
                    <textarea class="form-control" id="formTentangInd"></textarea>
                    </div>
                </div>
            
            
        </div>
        <div class="col-md-6">
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <h4 class="panel-title"> ENGLISH PROFILE LULUSAN</h4>
                    </div>
                    <div class="panel-body">
                    <textarea class="form-control" id="formProfileLulusanEng"></textarea>
                    </div>
                </div>
            
        </div>
        <div class="col-md-6">
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <h4 class="panel-title">INDONESIA PROFILE LULUSAN</h4>
                    </div>
                    <div class="panel-body">
                    <textarea class="form-control" id="formProfileLulusanInd"></textarea>
                    </div>
                </div>
            
            
        </div>
        <div class="col-md-6">
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <h4 class="panel-title"> ENGLISH ADVANTAGE</h4>
                    </div>
                    <div class="panel-body">
                    <textarea class="form-control" id="formKeunggulanEng"></textarea>
                    </div>
                </div>
            
        </div>
        <div class="col-md-6">
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <h4 class="panel-title">INDONESIA KEUNGGULAN</h4>
                    </div>
                    <div class="panel-body">
                    <textarea class="form-control" id="formKeunggulanInd"></textarea>
                    </div>
                </div>
            
            
        </div>
        <div class="col-md-6">
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <h4 class="panel-title"> ENGLISH PELUANG KARIR</h4>
                    </div>
                    <div class="panel-body">
                    <textarea class="form-control" id="formKarirEng"></textarea>
                    </div>
                </div>
            
        </div>
        <div class="col-md-6">
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <h4 class="panel-title">INDONESIA PELUANG KARIR</h4>
                    </div>
                    <div class="panel-body">
                    <textarea class="form-control" id="formKarirInd"></textarea>
                    </div>
                </div>
            
            <button id="btnSubmit" class="btn btn-primary" style="margin-top: 15px;float: right;">Save</button>
        </div>
        
        

   

</div>


<script type="text/javascript">

    $(document).ready(function(){
//=== menampilkan data summernote js=== //
        $('#formWhyEng,#formWhyInd,#formTentangEng,#formTentangInd,#formKarirEng,#formKarirInd,#formProfileLulusanEng,#formProfileLulusanInd,#formKeunggulanEng,#formKeunggulanInd').summernote({
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
        tampil_data_Why();
        // tampil_data_Tentang();
        
    });

    function tampil_data_Why(){

        var data = {action : 'viewDataProdi'};

        var token = jwt_encode(data,'UAP)(*');

        var url = base_url_js+'api-prodi/__crudDataProdi';

        $.post(url,{token:token},function(jsonResult){
            if(jsonResult.length>0){
                var d = jsonResult[0];
                $('#formWhyEng').summernote('code', d.WhyEng);
                $('#formWhyInd').summernote('code', d.WhyInd);
                $('#formTentangEng').summernote('code', d.TentangEng);
                $('#formTentangInd').summernote('code', d.TentangInd);
                $('#formProfileLulusanEng').summernote('code', d.ProfileLulusanEng);
                $('#formProfileLulusanInd').summernote('code', d.ProfileLulusanInd);
                $('#formKarirEng').summernote('code', d.PeluangKarirEng);
                $('#formKarirInd').summernote('code', d.PeluangKarirInd);
                $('#formKeunggulanEng').summernote('code', d.KeunggulanEng);
                $('#formKeunggulanInd').summernote('code', d.KeunggulanInd);
            }
        });
        
    }

    $('#btnSubmit').click(function () {

        var formWhyEng = $('#formWhyEng').val();
        var formWhyInd = $('#formWhyInd').val();
        var formTentangEng = $('#formTentangEng').val();
        var formTentangInd = $('#formTentangInd').val();
        var formProfileLulusanEng = $('#formProfileLulusanEng').val();
        var formProfileLulusanInd = $('#formProfileLulusanInd').val();
        var formKeunggulanEng = $('#formKeunggulanEng').val();
        var formKeunggulanInd = $('#formKeunggulanInd').val();
        var formKarirEng = $('#formKarirEng').val();
        var formKarirInd = $('#formKarirInd').val();
        

        if(
            formWhyEng!='' && formWhyEng!=null &&
            formWhyInd!='' && formWhyInd!=null &&
            formTentangEng!='' && formTentangEng!=null &&
            formTentangInd!='' && formTentangInd!=null &&
            formProfileLulusanEng!='' && formProfileLulusanEng!=null &&
            formProfileLulusanInd!='' && formProfileLulusanInd!=null &&
            formKeunggulanEng!='' && formKeunggulanEng!=null &&
            formKeunggulanInd!='' && formKeunggulanInd!=null &&
            formKarirEng!='' && formKarirEng!=null &&
            formKarirInd!='' && formKarirInd!=null){

            loading_button('#btnSubmit');

            var data = {
                action : 'updateDataProdi',
                dataForm : {
                    WhyEng : formWhyEng,
                    WhyInd : formWhyInd,
                    TentangEng : formTentangEng,
                    TentangInd : formTentangInd,
                    ProfileLulusanEng : formProfileLulusanEng,
                    ProfileLulusanInd : formProfileLulusanInd,
                    KeunggulanEng : formKeunggulanEng,
                    KeunggulanInd : formKeunggulanInd,
                    PeluangKarirEng : formKarirEng,
                    PeluangKarirInd : formKarirInd

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

