
<div class="row" style="margin-top: 30px;">
    <div class="col-md-6 col-md-offset-3">

        <a href="<?= base_url('announcement/list-announcement'); ?>" class="btn btn-warning">Back to list</a>
        <hr/>

        <div class="well" id="dataAnnc">

            <div class="form-group">
                <label>Title</label>
                <input class="form-control" id="formTitle" maxlength="200" placeholder="Maximum input is 200 characters">
            </div>

            <div class="form-group">
                <label>Message</label>
                <textarea id="formMessage"></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <input class="hide" id="formLastFile"/>
                    <form id="fileAnnouncement" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                        <div class="form-group">
                            <label class="btn btn-sm btn-default btn-upload">
                                <i class="fa fa-upload margin-right"></i> File (.pdf)
                                <input type="file" id="formFileAnnc" name="userfile" class="upload_files"
                                       style="display: none;" accept="application/pdf">
                            </label>
                            <p class="help-block" id="viewNameFile"></p>
                            <p class="help-block" id="viewZise"></p>

                        </div>
                    </form>

                    <div id="viewFile"></div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Publish Until</label>
                        <input type="text" id="formEnd" name="regular" class="form-control formcalendar">
                    </div>
                </div>
            </div>


            <div style="text-align: right;">
                <button class="btn btn-primary" id="btnSubmitAnnouncement">Update</button>
            </div>

        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        $('.formcalendar').datepicker({
            showOtherMonths:true,
            autoSize: true,
            dateFormat: 'dd MM yy',
            minDate: new Date(moment().format('YYYY-MM-DD')),
            onSelect : function () {
                // var data_date = $(this).val().split(' ');
                // var nextelement = $(this).attr('nextelement');
                // nextDatePick(data_date,nextelement);
            }
        });
        loadData();
    });

    function loadData() {
        var ID = "<?= $ID ?>";
        var url = base_url_js+'api2/__crudAnnouncement';

        var data = {
            action : 'loadAnnouncement',
            ID : ID
        };

        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){

                var d = jsonResult[0];
                $('#formTitle').val(d.Title);

                $('#formMessage').val(d.Message);
                $('#formMessage').summernote({
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

                $('#formEnd').datepicker('setDate',new Date(moment(d.End).format('YYYY-MM-DD')));

                if(d.File!=null && d.File!=''){
                    $('#formLastFile').val(d.File);

                    $('#viewFile').html('<hr/><iframe src="'+base_url_js+'uploads/announcement/'+d.File+'" height="200" width="100%"></iframe>' +
                        '<a target="_blank" href="'+base_url_js+'uploads/announcement/'+d.File+'">Full Screen</a>');

                }


            } else {
                $('#dataAnnc').html('-');
            }

        });
    }


    $('.upload_files').change(function () {

        var input = $('#formFileAnnc');
        var file = input[0].files[0];

        $('#btnSubmitAnnouncement').prop('disabled',true);

        if(file.type != 'application/pdf'){
            alert('The file must be PDF');
        } else {
            var fileNameOri = file.name;
            var fileName = fileNameOri.split(' ').join('_');
            $('#viewNameFile').html(fileName);
            $('#viewZise').html('Size : '+(parseFloat(file.size) / 1000000).toFixed(2)+' Mb');

            $('#btnSubmitAnnouncement').prop('disabled',false);
        }



    });
    
    $('#btnSubmitAnnouncement').click(function () {

        var ID = "<?= $ID ?>";
        var formTitle = $('#formTitle').val();
        var formMessage = $('#formMessage').val();
        var formEnd = $('#formEnd').datepicker("getDate");

        if(formTitle!=null && formTitle!='' &&
            formMessage!=null && formMessage!='' && formEnd!=null && formEnd!=''){

            loading_button('#btnSubmitAnnouncement');
            var End = moment(formEnd).format('YYYY-MM-DD');

            var data = {
                action : 'updateAnnouncement',
                ID : ID,
                dataUpdate : {
                    Title : formTitle,
                    Message : formMessage,
                    End : End,
                    UpdatedBy : sessionNIP,
                    UpdatedAt : dateTimeNow()
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api2/__crudAnnouncement';

            $.post(url,{token:token},function (result) {

                var IDAnnc = result;
                var input = $('#formFileAnnc');
                if(input[0].files.length>0){

                    var fileName = sessionNIP+'_'+moment().unix()+'.pdf';


                    var formData = new FormData( $("#fileAnnouncement")[0]);

                    var formLastFile = $('#formLastFile').val();
                    var lf = (formLastFile!='') ? '&lf='+formLastFile : '';

                    var url = base_url_js+'announcement/upload_files?IDAnnc='+IDAnnc+'&f='+fileName+''+lf;

                    $.ajax({
                        url : url,  // Controller URL
                        type : 'POST',
                        data : formData,
                        async : false,
                        cache : false,
                        contentType : false,
                        processData : false,
                        success : function(data) {
                            toastr.success('Announcement Created','Success');
                            setTimeout(function () {
                                window.location.href = '';
                            },500);
                        }
                    });
                }
                else {
                    toastr.success('Announcement Created','Success');
                    setTimeout(function () {
                        window.location.href = '';
                    },500);
                }
            });

        }

    });


</script>