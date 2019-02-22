
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4> 
      </div>
      <div class="modal-body">
        <div style="text-align: center;">
<iframe src="" 
style="width:500px; height:500px;" frameborder="0"></iframe>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>


<style>
    .form-group {
        margin-bottom: 0px;
    }
</style>

<div class="row">

    <div class="col-md-4 col-md-offset-4">
        <div class="well">
            <select class="select2-select-00" style="max-width: 100% !important;" size="5" id="formEmployees">
                <option value=""></option>
            </select>
        </div>
    </div>

    <div class="col-md-6 col-md-offset-3">

        <div class="thumbnail" style="min-height: 100px;">
            <table class="table">
                <tr>
                    <td rowspan="3" style="width:10%;text-align: center;" id="viewPhoto">
                        -
                    </td>

                </tr>
                <tr>
                    <td style="width: 10%;">Name</td>
                    <td style="width: 1%;">:</td>
                    <th id="viewName">-</th>
                </tr>
                <tr>
                    <td>NIP</td>
                    <td>:</td>
                    <td id="viewNIP">-</td>
                </tr>
            </table>

            <hr/>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr style="background: #607d8b;color: #FFFFFF;">
                        <th style="width: 30%;text-align: center;">Description</th>
                        <th style="width: 15%;text-align: center;">Files</th>
                        <th style="text-align: center;">Files</th>
                        <th style="text-align: center;width: 5%;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>KTP</td>
                        <td>
                            <form id="tagFM_KTP" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <div class="form-group">
                                    <label class="btn btn-sm btn-default btn-upload">
                                        <i class="fa fa-upload margin-right"></i> Upload
                                        <input type="file" id="fileIjazah" name="userfile" class="upload_files" data-fm="tagFM_KTP"
                                               style="display: none;" accept="application/pdf">
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td id="viewKTP" data-toggle="modal" data-target="#myModal">-</td>
                        <td style="text-align: center;"><button id="btnDelete_KTP" class="btn btn-sm btn-danger btnDelete" disabled><i class="fa fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td>Curriculum Vitae</td>
                        <td>
                            <form id="tagFM_CV" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <div class="form-group">
                                    <label class="btn btn-sm btn-default btn-upload">
                                        <i class="fa fa-upload margin-right"></i> Upload
                                        <input type="file" id="fileIjazah" name="userfile" class="upload_files" data-fm="tagFM_CV"
                                               style="display: none;" accept="application/pdf">
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td id="viewCV">-</td>
                        <td style="text-align: center;"><button id="btnDelete_CV" class="btn btn-sm btn-danger btnDelete" disabled><i class="fa fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td>Ijazah S1</td>
                        <td>
                            <form id="tagFM_IjazahS1" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <div class="form-group">
                                    <label class="btn btn-sm btn-default btn-upload">
                                        <i class="fa fa-upload margin-right"></i> Upload
                                        <input type="file" id="fileIjazah" name="userfile" class="upload_files" data-fm="tagFM_IjazahS1"
                                               style="display: none;" accept="application/pdf">
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td id="viewIjazahS1">-</td>
                        <td style="text-align: center;"><button id="btnDelete_IjazahS1" class="btn btn-sm btn-danger btnDelete" disabled><i class="fa fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td>Transcript S1</td>
                        <td>
                            <form id="tagFM_TranscriptS1" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <div class="form-group">
                                    <label class="btn btn-sm btn-default btn-upload">
                                        <i class="fa fa-upload margin-right"></i> Upload
                                        <input type="file" id="fileIjazah" name="userfile" class="upload_files" data-fm="tagFM_TranscriptS1"
                                               style="display: none;" accept="application/pdf">
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td id="viewTranscriptS1">-</td>
                        <td style="text-align: center;"><button id="btnDelete_TranscriptS1" class="btn btn-sm btn-danger btnDelete" disabled><i class="fa fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td>Ijazah S2</td>
                        <td>
                            <form id="tagFM_IjazahS2" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <div class="form-group">
                                    <label class="btn btn-sm btn-default btn-upload">
                                        <i class="fa fa-upload margin-right"></i> Upload
                                        <input type="file" id="fileIjazah" name="userfile" class="upload_files" data-fm="tagFM_IjazahS2"
                                               style="display: none;" accept="application/pdf">
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td id="viewIjazahS2">-</td>
                        <td style="text-align: center;"><button id="btnDelete_IjazahS2" class="btn btn-sm btn-danger btnDelete" disabled><i class="fa fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td>Transcript S2</td>
                        <td>
                            <form id="tagFM_TranscriptS2" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <div class="form-group">
                                    <label class="btn btn-sm btn-default btn-upload">
                                        <i class="fa fa-upload margin-right"></i> Upload
                                        <input type="file" id="fileIjazah" name="userfile" class="upload_files" data-fm="tagFM_TranscriptS2"
                                               style="display: none;" accept="application/pdf">
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td id="viewTranscriptS2">-</td>
                        <td style="text-align: center;"><button id="btnDelete_TranscriptS2" class="btn btn-sm btn-danger btnDelete" disabled><i class="fa fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td>Ijazah S3</td>
                        <td>
                            <form id="tagFM_IjazahS3" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <div class="form-group">
                                    <label class="btn btn-sm btn-default btn-upload">
                                        <i class="fa fa-upload margin-right"></i> Upload
                                        <input type="file" id="fileIjazah" name="userfile" class="upload_files" data-fm="tagFM_IjazahS3"
                                               style="display: none;" accept="application/pdf">
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td id="viewIjazahS3">-</td>
                        <td style="text-align: center;"><button id="btnDelete_IjazahS3" class="btn btn-sm btn-danger btnDelete" disabled><i class="fa fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td>Transcript S3</td>
                        <td>
                            <form id="tagFM_TranscriptS3" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <div class="form-group">
                                    <label class="btn btn-sm btn-default btn-upload">
                                        <i class="fa fa-upload margin-right"></i> Upload
                                        <input type="file" id="fileIjazah" name="userfile" class="upload_files" data-fm="tagFM_TranscriptS3"
                                               style="display: none;" accept="application/pdf">
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td id="viewTranscriptS3">-</td>
                        <td style="text-align: center;"><button id="btnDelete_TranscriptS3" class="btn btn-sm btn-danger btnDelete" disabled><i class="fa fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td>Surat Pernyataan Dosen</td>
                        <td>
                            <form id="tagFM_SP_Dosen" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <div class="form-group">
                                    <label class="btn btn-sm btn-default btn-upload">
                                        <i class="fa fa-upload margin-right"></i> Upload
                                        <input type="file" id="fileIjazah" name="userfile" class="upload_files" data-fm="tagFM_SP_Dosen"
                                               style="display: none;" accept="application/pdf">
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td id="viewSP_Dosen">-</td>
                        <td style="text-align: center;"><button id="btnDelete_SP_Dosen" class="btn btn-sm btn-danger btnDelete" disabled><i class="fa fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td>SK Dosen</td>
                        <td>
                            <form id="tagFM_SK_Dosen" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <div class="form-group">
                                    <label class="btn btn-sm btn-default btn-upload">
                                        <i class="fa fa-upload margin-right"></i> Upload
                                        <input type="file" id="fileIjazah" name="userfile" class="upload_files" data-fm="tagFM_SK_Dosen"
                                               style="display: none;" accept="application/pdf">
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td id="viewSK_Dosen">-</td>
                        <td style="text-align: center;"><button id="btnDelete_SK_Dosen" class="btn btn-sm btn-danger btnDelete" disabled><i class="fa fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td>SK Pangkat</td>
                        <td>
                            <form id="tagFM_SK_Pangkat" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <div class="form-group">
                                    <label class="btn btn-sm btn-default btn-upload">
                                        <i class="fa fa-upload margin-right"></i> Upload
                                        <input type="file" id="fileIjazah" name="userfile" class="upload_files" data-fm="tagFM_SK_Pangkat"
                                               style="display: none;" accept="application/pdf">
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td id="viewSK_Pangkat">-</td>
                        <td style="text-align: center;"><button id="btnDelete_SK_Pangkat" class="btn btn-sm btn-danger btnDelete" disabled><i class="fa fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td>SK Jabatan Fungsional</td>
                        <td>
                            <form id="tagFM_SK_JJA" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <div class="form-group">
                                    <label class="btn btn-sm btn-default btn-upload">
                                        <i class="fa fa-upload margin-right"></i> Upload
                                        <input type="file" id="fileIjazah" name="userfile" class="upload_files" data-fm="tagFM_SK_JJA"
                                               style="display: none;" accept="application/pdf">
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td id="viewSK_JJA">-</td>
                        <td style="text-align: center;"><button id="btnDelete_SK_JJA" class="btn btn-sm btn-danger btnDelete" disabled><i class="fa fa-trash"></i></button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadSelectOptionEmployeesSingle('#formEmployees','');
        $('#formPengawas1,#formPengawas2').select2({allowClear: true});
    });

    $('#formEmployees').change(function () {
        loadDataEmployees();
    });

    function loadDataEmployees() {
        var formEmployees = $('#formEmployees').val();
        if(formEmployees!='' && formEmployees!=null){

            var url = base_url_js+'api/__crudEmployees';
            var data = {
                action : 'getEmployeesFiles',
                NIP : formEmployees.trim()
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

                var d = jsonResult[0];

                var Photo = base_url_img_employee+''+d.Photo;

                $('#viewPhoto').html('<img src="'+Photo+'" style="width: 100%;max-width: 40px;">');
                $('#viewName').html(d.Name);
                $('#viewNIP').html(d.NIPLec);


                // Load files
                var KTP = (d.KTP!='' && d.KTP!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.KTP+'">'+d.KTP+'</a>' : '-';
                var CV = (d.CV!='' && d.CV!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.CV+'">'+d.CV+'</a>' : '-';
                var IjazahS1 = (d.IjazahS1!='' && d.IjazahS1!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.IjazahS1+'">'+d.IjazahS1+'</a>' : '-';
                var TranscriptS1 = (d.TranscriptS1!='' && d.TranscriptS1!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.TranscriptS1+'">'+d.TranscriptS1+'</a>' : '-';
                var IjazahS2 = (d.IjazahS2!='' && d.IjazahS2!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.IjazahS2+'">'+d.IjazahS2+'</a>' : '-';
                var TranscriptS2 = (d.TranscriptS2!='' && d.TranscriptS2!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.TranscriptS2+'">'+d.TranscriptS2+'</a>' : '-';
                var IjazahS3 = (d.IjazahS3!='' && d.IjazahS3!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.IjazahS3+'">'+d.IjazahS3+'</a>' : '-';
                var TranscriptS3 = (d.TranscriptS3!='' && d.TranscriptS3!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.TranscriptS3+'">'+d.TranscriptS3+'</a>' : '-';
                var SP_Dosen = (d.SP_Dosen!='' && d.SP_Dosen!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.SP_Dosen+'">'+d.SP_Dosen+'</a>' : '-';
                var SK_Dosen = (d.SK_Dosen!='' && d.SK_Dosen!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.SK_Dosen+'">'+d.SK_Dosen+'</a>' : '-';
                var SK_Pangkat = (d.SK_Pangkat!='' && d.SK_Pangkat!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.SK_Pangkat+'">'+d.SK_Pangkat+'</a>' : '-';
                var SK_JJA = (d.SK_JJA!='' && d.SK_JJA!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.SK_JJA+'">'+d.SK_JJA+'</a>' : '-';


                $('#viewKTP').html(KTP);
                $('#viewCV').html(CV);
                $('#viewIjazahS1').html(IjazahS1);
                $('#viewTranscriptS1').html(TranscriptS1);
                $('#viewIjazahS2').html(IjazahS2);
                $('#viewTranscriptS2').html(TranscriptS2);
                $('#viewIjazahS3').html(IjazahS3);
                $('#viewTranscriptS3').html(TranscriptS3);
                $('#viewSP_Dosen').html(SP_Dosen);
                $('#viewSK_Dosen').html(SK_Dosen);
                $('#viewSK_Pangkat').html(SK_Pangkat);
                $('#viewSK_JJA').html(SK_JJA);



                var btnKTP = (d.KTP!='' && d.KTP!=null) ? false : true;
                var btnCV = (d.CV!='' && d.CV!=null) ? false : true;
                var btnIjazahS1 = (d.IjazahS1!='' && d.IjazahS1!=null) ? false : true;
                var btnTranscriptS1 = (d.TranscriptS1!='' && d.TranscriptS1!=null) ? false : true;
                var btnIjazahS2 = (d.IjazahS2!='' && d.IjazahS2!=null) ? false : true;
                var btnTranscriptS2 = (d.TranscriptS2!='' && d.TranscriptS2!=null) ? false : true;
                var btnIjazahS3 = (d.IjazahS3!='' && d.IjazahS3!=null) ? false : true;
                var btnTranscriptS3 = (d.TranscriptS3!='' && d.TranscriptS3!=null) ? false : true;
                var btnSP_Dosen = (d.SP_Dosen!='' && d.SP_Dosen!=null) ? false : true;
                var btnSK_Dosen = (d.SK_Dosen!='' && d.SK_Dosen!=null) ? false : true;
                var btnSK_Pangkat = (d.SK_Pangkat!='' && d.SK_Pangkat!=null) ? false : true;
                var btnSK_JJA = (d.SK_JJA!='' && d.SK_JJA!=null) ? false : true;


                $('#btnDelete_KTP').prop('disabled',btnKTP).attr('data-file',d.KTP);
                $('#btnDelete_CV').prop('disabled',btnCV).attr('data-file',d.CV);
                $('#btnDelete_IjazahS1').prop('disabled',btnIjazahS1).attr('data-file',d.IjazahS1);
                $('#btnDelete_TranscriptS1').prop('disabled',btnTranscriptS1).attr('data-file',d.TranscriptS1);
                $('#btnDelete_IjazahS2').prop('disabled',btnIjazahS2).attr('data-file',d.IjazahS2);
                $('#btnDelete_TranscriptS2').prop('disabled',btnTranscriptS2).attr('data-file',d.TranscriptS2);
                $('#btnDelete_IjazahS3').prop('disabled',btnIjazahS3).attr('data-file',d.IjazahS3);
                $('#btnDelete_TranscriptS3').prop('disabled',btnTranscriptS3).attr('data-file',d.TranscriptS3);
                $('#btnDelete_SP_Dosen').prop('disabled',btnSP_Dosen).attr('data-file',d.SP_Dosen);
                $('#btnDelete_SK_Dosen').prop('disabled',btnSK_Dosen).attr('data-file',d.SK_Dosen);
                $('#btnDelete_SK_Pangkat').prop('disabled',btnSK_Pangkat).attr('data-file',d.SK_Pangkat);
                $('#btnDelete_SK_JJA').prop('disabled',btnSK_JJA).attr('data-file',d.SK_JJA);


            });
        }
    }

    $('.upload_files').change(function () {

        var formEmployees = $('#formEmployees').val();
        var input = this;

        if(formEmployees!='' && formEmployees!=null && input.files && input.files[0]){
            var NIP = formEmployees;
            var fm = $(this).attr('data-fm');
            var type = fm.split('tagFM_')[1];

            var sz = parseFloat(input.files[0].size) / 1000000; // ukuran MB
            var ext = input.files[0].type.split('/')[1];

            var ds = true;
            if(Math.floor(sz)<=8){
                ds = false;

                var fileName = type+'_'+NIP+'.'+ext;

                var formData = new FormData( $("#"+fm)[0]);
                var url = base_url_js+'human-resources/employees/upload_files?fileName='+fileName+'&c='+type+'&u='+NIP;

                $.ajax({
                    url : url,  // Controller URL
                    type : 'POST',
                    data : formData,
                    async : false,
                    cache : false,
                    contentType : false,
                    processData : false,
                    success : function(data) {
                        toastr.success('Upload Success','Saved');
                        loadDataEmployees();

                        // var jsonData = JSON.parse(data);

                        // if(typeof jsonData.success=='undefined'){
                        //     toastr.error(jsonData.error,'Error');
                        //     // alert(jsonData.error);
                        // }
                        // else {
                        //     toastr.success('File Saved','Success!!');
                        // }

                    }
                });


            } else {
                alert('Maksimum size 8 Mb');
            }

        } else {
            toastr.error('Plase, Select User','Eror!');
        }


    });

    $('.btnDelete').click(function () {

        var formEmployees = $('#formEmployees').val();
        var ID = $(this).attr('id');
        var colom = ID.split('btnDelete_')[1];
        var files = $(this).attr('data-file');

        if(formEmployees!='' && formEmployees!=null && files!='' && files!=null){
            if(confirm('Remove data?')){
                var url = base_url_js+'human-resources/employees/remove_files?fileName='+files+
                    '&user='+formEmployees.trim()+'&colom='+colom;
                $.get(url,function (result) {
                    toastr.success('Data Removed','Success');
                    loadDataEmployees();
                });
            }
        }



    });

</script>