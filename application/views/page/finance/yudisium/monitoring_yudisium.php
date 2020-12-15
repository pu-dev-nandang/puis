
<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }

    #tableData td {
        vertical-align: middle;
    }
    #tableData td:nth-child(1), #tableData td:nth-child(2), #tableData td:nth-child(3){
        vertical-align: top !important;
    }
</style>

<div class="row">
    <div class="col-md-8 col-md-offset-2" style="text-align: right;margin-top: 30px;">
        <div class="well">
            <div class="row">
                <div class="col-md-3">
                    <select class="form-control" id="filterSemester"></select>
                </div>
                <div class="col-md-5">
                    <select class="form-control" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                        <option disabled>------------------------------------------</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="filterStatus">
                        <option value="">-- All Status --</option>
                        <option disabled>-------------------------------------</option>
                        <optgroup label="Ijazah SMA">
                            <option value="i.0">Not yet upload</option>
                            <option value="i.1">Uploaded</option>
                        </optgroup>
                        <optgroup label="Academic">
                            <option value="a.0">Waiting Clearance</option>
                            <option value="a.1">Clearance</option>
                        </optgroup>
                        <optgroup label="Library">
                            <option value="l.0">Waiting Clearance</option>
                            <option value="l.1">Clearance</option>
                        </optgroup>
                        <optgroup label="Finance">
                            <option value="f.0">Waiting Clearance</option>
                            <option value="f.1">Clearance</option>
                        </optgroup>
                        <optgroup label="Student Life">
                            <option value="s.0">Waiting Clearance</option>
                            <option value="s.1">Clearance</option>
                        </optgroup>
                        <optgroup label="Kaprodi">
                            <option value="k.0">Waiting Approval</option>
                            <option value="k.1">Approved</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="viewData"></div>
    </div>
</div>

<script>

    let noteAction;

    $(document).ready(function () {
        loSelectOptionSemester('#filterSemester','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');

        var firstLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                loadData();
                clearInterval(firstLoad);
            }
        },1000);
    });

    $('#filterSemester,#filterBaseProdi,#filterStatus').change(function () {
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){
            loadData();
        }
    });

    function loadData() {
        var filterSemester = $('#filterSemester').val();
        var filterBaseProdi = $('#filterBaseProdi').val();
        var filterStatus = $('#filterStatus').val();

        if(filterSemester!='' && filterSemester!=null){

            var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null)
                ? filterBaseProdi.split('.')[0] : '';

            var StatusTA = (filterStatus!='' && filterStatus!=null) ? filterStatus : '';

            var SemesterID = filterSemester.split('.')[0];

            $('#viewData').html('<table class="table table-striped table-bordered" id="tableData">' +
                '            <thead>' +
                '            <tr>' +
                '                <th rowspan="2" style="width: 1%;">No</th>' +
                '                <th rowspan="2" style="width: 15%;">Student</th>' +
                '                <th rowspan="2">Course</th>' +
                '                <th rowspan="2" style="width: 10%;">Ijazah SMA / SKHUN</th>' +
                '                <th colspan="5">Clearance</th>'+
                '            </tr>' +
                '           <tr>' +
                '               <th style="width: 10%;">Academic</th>' +
                '               <th style="width: 10%;">Library</th>' +
                '               <th style="width: 10%;">Finance</th>' +
                '               <th style="width: 10%;">Student Life</th>' +
                '               <th style="width: 10%;">Kaprodi</th>' +
                '           </tr>' +
                '            </thead>' +
                '        </table>');


            var token = jwt_encode({action : 'viewYudisiumList',SemesterID:SemesterID,ProdiID : ProdiID, StatusTA : StatusTA},'UAP)(*');
            var url = base_url_js+'api3/__crudYudisium';

            window.dataTable = $('#tableData').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "NIM, Name"
                },
                "ajax":{
                    url : url, // json datasource
                    data : {token:token},
                    ordering : false,
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                    }
                }
            });

        }
    }

    $(document).on('click','.btnClearnt',function () {

        if(confirm('Are you sure?')){
            var NPM = $(this).attr('data-npm');
            var C = $(this).attr('data-c');
            var token = jwt_encode({action : 'updateClearent',NPM:NPM,C:C},'UAP)(*');
            var url = base_url_js+'api3/__crudYudisium';

            $.post(url,{token:token},function (result) {

                // loadData();
                window.dataTable.ajax.reload(null, false);
                toastr.success('Data saved','Success');

            });
        }

    });

    const noteFileShow = (NPM,thefile,selector) => {
        let html = '<label class="btn btn-sm btn-default note-btn-upload">'+
                        'Upload file'+
                        '<input type="file" name="userfile" class="note-uploadFile" accept="application/pdf" style="display: none;">'+
                    '</label>';
        if (selector && thefile) {
            html = '<a href = "'+base_url_js+'uploads/document/'+NPM+'/'+thefile+'" class = "btn btn-primary" target="_blank">File</a> &nbsp <button class = "btn btn-danger NotedeleteFile" npm = "'+NPM+'" >Delete File</button>';
        }

        selector.html(html);
    }


    // === Adding Note Button ===
    $(document).on('click','.btnNote',function () {
        noteAction = $(this);
        var dept = $(this).attr('data-dept');
        var NPM = $(this).attr('data-npm');

        const thefile =  $(this).attr('thefile');

        var v = $('#'+dept+'_viewValueNote_'+NPM).val();
        var valNote = (typeof v !== "undefined") ? v : '';

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Note to '+NPM+'</h4>');

        var htmlss = '<div class="row">' +
            '        <div class="col-md-12">' +
            '              <input class="hide" id="formNote_NPM" value="'+NPM+'">' +
            '              <input class="hide" id="formNote_Dept" value="'+dept+'">' +
            '             <textarea class="form-control" id="formNote_Note" rows="5" maxlength="255" placeholder="Please enter notes here...">'+valNote+'</textarea>' +
            '               <p class="help-block">Maximum 255 character</p>' +
            '        </div>' +
            '<div class = "col-md-4 dom_file"></div>'+
            '    </div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" id="submitNoteForm" class="btn btn-success">Submit</button>');

        $('#GlobalModal').on('shown.bs.modal', function () {
            $('#formSimpleSearch').focus();
        })

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        // set show hide upload file
        const selectorDOM = $('#GlobalModal').find('.dom_file');
        noteFileShow(NPM,thefile,selectorDOM);

    });

    $(document).on('click','.NotedeleteFile',function(e){
        const itsme = $(this);
        const NPM = itsme.attr('npm');
        if (confirm('are you sure ?')) {
            deleteFileNoteForm(NPM,itsme);
        }
        
    })

    const deleteFileNoteForm = async(npm,selector) => {
        var token = jwt_encode({
            action : 'delete_fileNotetoClearent',
            NPM : npm,
        },'UAP)(*');
        var url = base_url_js+'api3/__crudYudisium';
        loading_button2(selector)
        try{
            var response =  await AjaxSubmitFormPromises(url,token);
            if (response.status == 1) {
                 const selectorDOM = $('#GlobalModal').find('.dom_file');
                 noteFileShow(npm,'',selectorDOM);

                 noteAction.attr('thefile','');
            }
            else
            {
                 toastr.info(response.msg)
                 end_loading_button2(selector,'Delete File')
            }
        }
        catch(err){
            toastr.info('something wrong, please contact IT'); // failed
            end_loading_button2(selector,'Delete File')
        }

    }

    const submitNoteForm = async(itsme) => {
        var NPM = $('#formNote_NPM').val();
        var Dept = $('#formNote_Dept').val();
        var Note = $('#formNote_Note').val();

        if(Note!='') {
            // updated
                var ArrUploadFilesSelector = [];
                var UploadFile = $('.note-uploadFile');
                var valUploadFile = UploadFile.val();
                if (valUploadFile) {
                    var NameField = UploadFile.attr('name');
                    var temp = {
                        NameField : NameField,
                        Selector : UploadFile,
                    };
                    ArrUploadFilesSelector.push(temp);
                }

                // validation ekstension file
                var FilesValidation = ValidationGenerate.file_validation(ArrUploadFilesSelector[0].Selector,'Note',1,['pdf'],8000000);
                if (FilesValidation) {
                    toastr.error(FilesValidation, 'Failed!!'); // failed
                }
                else
                {
                    var token = jwt_encode({
                        action : 'updateNotetoClearent',
                        NPM : NPM,
                        Dept : Dept,
                        Note : Note,
                        User : sessionNIP,
                        DateTime : getDateTimeNow()
                    },'UAP)(*');
                    var url = base_url_js+'api3/__crudYudisium';

                    loading_button2(itsme)
                    try{
                       var response =  await AjaxSubmitFormPromises(url,token,ArrUploadFilesSelector);
                       if (response.status == 1) {
                            $('#'+Dept+'_viewNote_'+NPM).html('<textarea class="form-control" style="color: #333;" id="'+Dept+'_viewValueNote_'+NPM+'" readonly>'+Note+'</textarea><hr style="margin-bottom: 5px;margin-top: 5px;"/>');
                            
                            noteAction.attr('thefile',response.callback.filename );

                            toastr.success('Data saved','Success');

                            setTimeout(function () {
                                $('#GlobalModal').modal('hide');
                            },500);
                       }
                       else
                       {
                            toastr.info(response.msg)
                            end_loading_button2(itsme,'Submit')
                       }
                       
                    }
                    catch(err) {
                        toastr.info('something wrong, please contact IT'); // failed
                        end_loading_button2(itsme,'Submit')
                    }
                }   
            // updated
        } else {
            toastr.warning('Form note required','Warning');
        }
    }

    $(document).on('click','#submitNoteForm',function () {
        const itsme = $(this);
        submitNoteForm(itsme);

    });


</script>