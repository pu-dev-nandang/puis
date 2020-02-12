
<div class="row" style="margin-top: 40px;">
    <div class="col-md-4 col-md-offset-4">
        <div class="well">
            <select class="form-control" id="filterStatus">
                <option value="">--- All Status ---</option>
                <option value="0">Open</option>
                <option value="1">On Process</option>
                <option value="2">Done</option>
            </select>
        </div>
    </div>
</div>





<div class="row">
    <div class="col-md-12" id="loadTable"></div>
</div>

<script>

    $(document).ready(function () {
        loadDataReport();
    });

    $('#filterStatus').change(function () {
        loadDataReport();
    });

    function loadDataReport() {

        $('#loadTable').html('<table class="table table-bordered table-centre table-left-body" id="tableData">' +
            '   <thead>' +
            '            <tr>' +
            '                <th style="width: 1%">No</th>' +
            '                <th style="width: 20%;">Student</th>' +
            '                <th>Report</th>' +
            '                <th style="width: 7%;">Status</th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody></tbody>' +
            '</table>');


        var filterStatus = $('#filterStatus').val();

        var data = {
            action : 'admin_getListReport',
            Status : filterStatus
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudStudentReport';

        var dataTable = $('#tableData').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "NIM, Name, No Report, Title"
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

    $(document).on('click','.showDataResponse',function () {
        var ID = $(this).attr('data-id');

        showingDetailResponse(ID);

    });

    function showingDetailResponse(ID) {

        var data = {
            action : 'getStudentReportResponse',
            ID : ID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudStudentReport';

        $.post(url,{token:token},function (jsonResult) {

            var d = jsonResult[0];

            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title"><i class="fa fa-hashtag"></i> '+d.ReportNumber+' | '+d.Name+'</h4>');

            var dataResponse = '<hr/>';
            if(d.Response.length>0){
                $.each(d.Response, function (i,v) {
                    var Cls = (v.EntredType=='1') ? '<span class="label label-default" style="float: right;"><i class="fa fa-check-circle"></i> Admin</span>' : '';
                    var ClsBg = (v.EntredType=='1') ? '#00bdd624' : '#9e9e9e0f';
                    var rsBy = (v.UpdatedAdmin!='' && v.UpdatedAdmin!=null) ? v.UpdatedAdmin : v.UpdatedUser;
                    var FilesUpload = '';
                    if (v.Files != null && v.Files != '') {
                        var pathfolders = "<?php echo ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') ? 'pcam/ticketing/' : 'localhost/ticketing/' ?>";
                        var filePath = pathfolders+v.Files;
                        // console.log(filePath);
                        var tokenFiles  = jwt_encode(filePath,'UAP)(*');
                        // console.log(tokenFiles);
                        FilesUpload = '<hr/><a class="btn btn-sm btn-default" href = "'+base_url_files+'fileGetAnyToken/'+tokenFiles+'" target="_blank" >Download File</a>';
                    }
                    dataResponse = dataResponse + '<table class="table table-bordered table-response">' +
                        '                <tr style="background: '+ClsBg+';">' +
                        '                    <td style="position: relative;border-left: none;" colspan = "2">' +
                        '                        <h5 style="margin-bottom: 1px;margin-top: 3px;">'+rsBy+Cls+'</h5>'+
                        '                        <div style="font-size: 11px;color: #999;">'+moment(v.EntredAt).format('dddd, DD MMM YYYY HH:mm')+'</div>'+
                        '                    </td>' +
                        '                </tr>' +
                        '                <tr>' +
                        '                    <td colspan = "2">'+v.Response+FilesUpload+'</td>' +
                        '                </tr>' +
                        '            </table>';

                });
            }

            var responsePanel = (d.Status!='2')
                ? '<hr/><div class="form-group">' +
                '    <textarea id="formResponse" class="form-control" rows="5" placeholder="Enter your response..."></textarea>' +
                '</div>' +
                '<div class="form-group">'+
                 '<label>Upload</label>'+
                 '<input type="file" id = "UploadFile" name = "Files">'+
                 '<div style="color: red;">'+
                     '<p>Note : </p>'+
                     '<p> * Max 2 mb</p>'+
                 '</div>'+
                '</div>'+
                '<div class="form-group" style="text-align: right;">' +
                '    <button class="btn btn-success" id="btnSubmitResponse">Submit</button>' +
                '    ' +
                '</div>'
                : '';

            var htmlss = '<h3 style="margin-top: 0px;color: #3f51b5;font-weight: bold;">'+d.Title+'</h3><p>'+d.Description+'</p>'+dataResponse+'' +responsePanel;


            $('#GlobalModal .modal-body').html(htmlss);

            $('#GlobalModal .modal-footer').html('<button class="btn btn-default" id="btnSubmitClose" style="color: red;float: left;font-weight: bold;">Close the report</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

            $('#GlobalModal').on('shown.bs.modal', function () {
                $('#formResponse').focus();
            });

            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });


            $('#btnSubmitResponse').click(function () {
                var selector =  $(this);
                var Response = $('#formResponse').val();

                if(Response!='' && Response!=null){

                    if(confirm('Are you sure?')){

                        var ArrUploadFilesSelector = [];
                        var UploadFile = $('#UploadFile');
                        var valUploadFile = UploadFile.val();
                        if (valUploadFile) {
                            var NameField = UploadFile.attr('name');
                            var temp = {
                                NameField : NameField,
                                Selector : UploadFile,
                            };
                            ArrUploadFilesSelector.push(temp);
                        }
                        var validationFile = validationFileResponse(ArrUploadFilesSelector);
                        if (validationFile) {
                            loading_button('#btnSubmitResponse');
                            var data = {
                                action : 'studentReportInsertRespinse',
                                dataForm : {
                                    IDReport : d.ID,
                                    Response : Response,
                                    EntredType : '1',
                                    EntredAt : dateTimeNow(),
                                    EnrtedBy : sessionNIP
                                }
                            };

                            var token = jwt_encode(data,'UAP)(*');
                            var url = base_url_js+'api3/__crudStudentReport';
                            AjaxSubmitRestTicketing2(url,token,ArrUploadFilesSelector).then(function(result){
                                toastr.success('Response sent','Success');
                                setTimeout(function () {
                                    showingDetailResponse(result);
                                },500);
                            }).fail(function(response){
                               toastr.error('Connection error,please try again');
                               end_loading_button2(selector,'Submit');     
                            })
                        }
                    }

                } else {
                    toastr.warning('Response form are required','Warning');
                }

            });

            $('#btnSubmitClose').click(function () {
                if(confirm('Are you sure?')){

                    loading_button('#btnSubmitClose');

                    var data = {
                        action : 'studentReportClose',
                        IDReport : d.ID
                    };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api3/__crudStudentReport';

                    $.post(url,{token:token},function (result) {
                        toastr.success('Report clesed','Success');
                        setTimeout(function () {
                            window.location.href="";
                        },500);
                    });
                }
            });

        });

    }

    function validationFileResponse(ArrUploadFilesSelector){
        var toatString = "";
        // validation files
        if (ArrUploadFilesSelector.length>0 && ArrUploadFilesSelector[0].Selector.length) {
          var selectorfile = ArrUploadFilesSelector[0].Selector
          var FilesValidation = file_validation_ticketing(selectorfile,'Upload File');
          if (FilesValidation != '') {
              toatString += FilesValidation + "<br>";
          }
          
        }

        if (toatString != "") {
          toastr.error(toatString, 'Failed!!');
          return false;
        }
        return true
    }

    $(document).off('click', '.CreateTicketFromStd').on('click', '.CreateTicketFromStd',function(e) {
        var selector = $(this);
        var ID = selector.attr('data-id');
        loadingStart();
        var htmlss = '<table class="table" id="tableNewTicket">' +
            '    <tr>' +
            '        <td>Category</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <select class="select2-select-00 full-width-fix input_form" name = "CategoryID"></select>' +
            '        </td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Department</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <label class="lblDepartment">Auto selected by Category</label>' +
            '        </td>' +
            '    </tr>' +
            '    <tr class="hide" id = "tr_ticket_number">' +
            '        <td>Ticket Number</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <label class ="TicketNumber"></label>' +
            '        </td>' +
            '    </tr>' +
            '</table>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button class = "btn btn-success BtnModalCreateTicket" data-id ="'+ID+'" > Submit </button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '');

        var selector =  $('.input_form[name="CategoryID"]');
        LoadSelectOptionCategory(selector);
        var firstLoad = setInterval(function () {
            var SelectCategoryID = $('.input_form[name="CategoryID"]').find('option:selected').val();
            if(SelectCategoryID!='' && SelectCategoryID!=null && SelectCategoryID !='' && SelectCategoryID!=null){
                loadingEnd(1);
                $('#GlobalModal').modal({
                    'show' : true,
                    'backdrop' : 'static'
                });
                $('.input_form[name="CategoryID"]').trigger('change');
                clearInterval(firstLoad);
            }
        },200);
        setTimeout(function () {
            clearInterval(firstLoad);
            loadingEnd(500);
        },5000);
        
    })

    $(document).off('change', '.input_form[name="CategoryID"]').on('change', '.input_form[name="CategoryID"]',function(e) {
        var ToDepartmentSelected = $(this).find('option:selected').attr('department');
        $('.lblDepartment').html(ToDepartmentSelected);
    })

    $(document).off('click', '.BtnModalCreateTicket').on('click', '.BtnModalCreateTicket',function(e) {
        var selector = $(this);
        var ID = selector.attr('data-id');
        if ($('.input_form[name="CategoryID"] option:selected').val() != '-') {
            var data = {
                action : 'studentReportCreateTicket',
                dataForm : {
                    IDReport : ID,
                    CategoryID : $('.input_form[name="CategoryID"] option:selected').val(),
                    RequestedBy : sessionNIP,
                    DepartmentTicketID : DepartmentID,
                    DepartmentAbbr : DepartmentAbbr,
                    Apikey : '?apikey='+Apikey,
                    Hjwtkey : Hjwtkey,
                }
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudStudentReport';
            loading_button2(selector);
            AjaxSubmitRestTicketing2(url,token).then(function(response){
                if (response.status == 1) {
                    selector.remove();
                    var response_callback = response.callback;
                    $('.TicketNumber').html(response_callback.NoTicket);
                    $('#tr_ticket_number').removeClass('hide');
                    toastr.success('Ticket Created');
                    loadDataReport();
                }
                else
                {
                    toastr.error(response.msg);
                    end_loading_button2(selector,'Submit');
                }
            }).fail(function(response){
               toastr.error('Connection error,please try again');
               end_loading_button2(selector,'Submit');     
            })
        }
        
    })
    
</script>