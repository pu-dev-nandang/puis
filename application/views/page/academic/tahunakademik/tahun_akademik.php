
<style>
    #tableTahunAkademik tr th {
        text-align: center;
    }
</style>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class=""><i class="icon-reorder"></i> Academic Year</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs btn-th-action" data-action="add" id="btn_addTahunAkademik">
                            <i class="icon-plus"></i> Academic Year
                        </span>
                        <span class="btn btn-xs" id="btnAcademicCalendar">
                            <i class="fa fa-calendar"></i> Academic Calendar
                        </span>
                    </div>
                </div>
            </div>
            <div class="widget-content">
                <div id="loadTable"></div>
            </div>
        </div>
    </div>
</div>


<script>
var oTable;
    $(document).ready(function () {
        loadTable();
    });

    $(document).on('click','.btn-th-action',function () {

        var action = $(this).attr('data-action');
        var id = $(this).attr('data-id');

        if(action=='add'){
            var url = base_url_js+"academic/modal-tahun-akademik";

            var btn_delete = '<button class="btn btn-danger btn-delete-master" style="float: left;" modal-id="'+id+'" id="modalBtnDelete" modal-action="delete">Delete</button>';

            $.post(url,{action:action,id:id},function (html) {

                $('#GlobalModal .modal-header').html('<h4 class="modal-title">Tahun Akademik</h4>');
                $('#GlobalModal .modal-body').html(html);
                $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" id="modalBtnClose" data-dismiss="modal">Close</button>' +
                    '<button type="button" class="btn btn-success" modal-id="'+id+'" modal-action="'+action+'" id="modalBtnSave">Save</button>');
                // '<button type="button" class="btn btn-success btn-th-action" data-action="add1" id="modalBtnSave">Save</button>');
                if(action=='edit'){
                    $('#GlobalModal .modal-footer').append(btn_delete);
                }
                $('#GlobalModal').modal({
                    'show' : true,
                    'backdrop' : 'static'
                });

            });
        }
        else if(action=='publish') {

            $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Publish ?? </b> ' +
                '<button type="button" id="btnActionPublish" data-id="'+id+'" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
                '<button type="button" id="btnActionNo" class="btn btn-default" data-dismiss="modal">No</button>' +
                '</div>');
            $('#NotificationModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });

        }
        else if(action=='add_db'){

            var url = base_url_js+'api/__crudYearAcademic';
            var token = jwt_encode({action:'read'},'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

                $('#NotificationModal .modal-body').html('' +
                    '<table class="table table-striped" id="tbdataTA">' +
                    '<tr>' +
                    '<td>Database</td>' +
                    '<td>Status</td>' +
                    '</tr>' +
                    '<tbody id="dataDbTA"></tbody>' +
                    '</table> ' +
                    '<div style="text-align: right;"><hr/>' +
                    '<button type="button" id="btnActionClose" class="btn btn-default" data-dismiss="modal">Close</button>' +
                    '</div>');

                var yp = 1;
                for(var i=0;i<parseInt(jsonResult.length)+2;i++){

                    if(i<=(parseInt(jsonResult.length) - 1)){
                        var year = jsonResult[i].YearAcademic;
                        $('#dataDbTA').append('<tr><td>'+year+'</td><td><i class="fa fa-check-circle" style="color: green;"></i> Exist</td></tr>');
                    } else {
                        var year = jsonResult[parseInt(jsonResult.length) - 1].YearAcademic;
                        var y = parseInt(year)+yp;

                        $('#dataDbTA').append('<tr><td>'+y+'</td><td id="acttd'+y+'"><button class="btn btn-default btn-default-primary btn-sm btn-createdb" data-db="'+y+'">Create DB</button></td></tr>');
                        yp +=1;
                    }

                }

                $('#tbdataTA tr td').css('text-align','center');

                $('#NotificationModal').modal({
                    'show' : true,
                    'backdrop' : 'static'
                });


            });


        }



    });

    $(document).on('click','.btn-createdb',function () {
        var dataDB = $(this).attr('data-db');
        var data = {
          action : 'add',
            dataInsert : {
                YearAcademic : parseInt(dataDB),
                CreateBy : sessionNIP,
                CreateAt : dateTimeNow()
            }
        };

        var btn = 'button[data-db='+dataDB+']';
        loading_buttonSm(btn);
        $('.btn-createdb,#btnActionClose').prop('disabled',true);

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudYearAcademic';
        $.post(url,{token:token},function (result) {
            setTimeout(function () {
                $('#acttd'+dataDB).html('<i class="fa fa-check-circle" style="color: green;"></i> Exist</td></tr>');
                $('.btn-createdb,#btnActionClose').prop('disabled',false);
            },500)
        });
    });

    $(document).on('click','#modalBtnSave, #modalBtnDelete',function () {

        var action = $(this).attr('modal-action');
        var ID = (action=='add')? '' : $(this).attr('modal-id');
        var ProgramCampusID = $('#modalProgram').find(':selected').val();
        var tahun = $('#modalTahun').find(':selected').val().split('.');
        var semester = $('input[name=semester]:checked').val();

        var s = (semester==1) ? 'Ganjil' : 'Genap';
        var Name = tahun[1].trim()+' '+s;

        var process = true;

        if(action=='delete'){
            if(window.confirm('Haous data ?')){
                process = true;
            } else {
                process = false;
            }
        }

        if(process){
            var btn_act = '#'+$(this).attr('id');
            var data = {
                action : action,
                ID : ID,
                dataForm : {
                    ProgramCampusID : ProgramCampusID,
                    Year : tahun[0].trim(),
                    Code : semester,
                    Name : Name,
                    Status : 0,
                    UpdateBy : sessionNIP,
                    UpdateAt : dateTimeNow()
                }
            };

            loading_button(btn_act);
            $('#modalBtnSave, #modalBtnDelete, #modalCurriculum, #modalBtnClose, #modalProgram, #modalTahun, input[name=semester]').prop('disabled',true);

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudTahunAkademik';
            $.post(url,{token:token},function (result) {
                // console.log(result);
                if(result==0){
                    setTimeout(function () {
                        $(btn_act).prop('disabled',false).html('Save');
                        $('#modalBtnSave, #modalBtnDelete, #modalCurriculum, #modalBtnClose, #modalProgram, #modalTahun, input[name=semester]').prop('disabled',false);
                        toastr.warning('Data Is Exist','Warning!');
                    },500);
                } else {
                    loadTable();
                    setTimeout(function () {
                        toastr.success('Data tersimpan','Success!!');
                        $('#GlobalModal').modal('hide');
                        // $('#modalBtnSave').html('Save');
                        // $('#modalBtnSave, #modalBtnDelete, #modalBtnClose, #modalProgram, #modalTahun, input[name=semester]').prop('disabled',false);
                    },500);
                }


            });
        }


    });

    $(document).on('click','.btn-detail-tahun-akademik',function () {
        var ID = $(this).attr('data-id');
        loadDetailPageTahunAkademik(ID);
    });

    $(document).on('click','#btnActionPublish',function () {
       var url = base_url_js+'api/__crudDataDetailTahunAkademik';
       var ID = $(this).attr('data-id');
       var token = jwt_encode({action:'publish',ID:ID},'UAP)(*');
       loading_buttonSm('#btnActionPublish');
       $('#btnActionNo').prop('disabled',true);
       $.post(url,{token:token},function (result) {
           loadTable();
           setTimeout(function () {
               toastr.success('Data Update','Success');
               $('#btnActionPublish').html('Yes');
               $('#btnActionNo,#btnActionPublish').prop('disabled',false);
               $('#NotificationModal').modal('hide');
           },1000);


       });
    });
    
    
    function loadDetailPageTahunAkademik(ID) {
        loading_page('#loadTable');
        var url = base_url_js+'academic/detail-tahun-akademik';
        $.post(url,{ID:ID},function (html) {
            setTimeout(function () {
                $('#loadTable').html(html);
            },500);
        });
    }

    function loadTable() {

        loading_page('#loadTable');
        var url = base_url_js+'academic/tahun-akademik-table';
        $.get(url,function (html) {
            setTimeout(function () {
                $('#loadTable').html(html);
            },500);

        });

    }

</script>

<!-- Untuk detail tahun akademik -->
<script>
    var AppJQ = {
        setDefaultInput :function(){
            $('.input[name="Year"]').val('');
            $('.input[name="YearName"]').val('');
            $('#UploadFile').val('');
            $('#btnSave').attr('action','add');
            $('#btnSave').attr('data-id','');
            $('.fileShow').find('li').remove();
        },
        loaded : function(){
            AppJQ.LoadAjaxData();

        },
        
        LoadAjaxData : function(){
                var data = {
                    action : 'read',
                    auth : 's3Cr3T-G4N',
                };
                 var token = jwt_encode(data,'UAP)(*');
                 var recordTable = $('#TblCalendar').DataTable({
                     "processing": true,
                     "serverSide": false,
                     "ajax":{
                         url : base_url_js+"rest3/__crud_calendar", // json datasource
                         ordering : false,
                         type: "post",  // method  , by default get
                         data : {token : token}                                    
                     },
                           'columnDefs': [
                              {
                                 'targets': 0,
                                 'searchable': false,
                                 'orderable': false,
                                 'className': 'dt-body-center',
                              },
                              {
                                 'targets': 3,
                                 'searchable': false,
                                 'orderable': false,
                                 'className': 'dt-body-center',
                                 'render': function (data, type, full, meta){
                                    var FileJson = jQuery.parseJSON(full[3]);
                                     var fileAhref =(full[3] == '' || full[3] == null || FileJson.length == 0) ? '' : '<a class="btn btn-xs btn-primary" href = "'+base_url_js+'fileGetAny/calendar-'+FileJson[0]+'" target="_blank" class = "Fileexist">  <i class="fa fa-file-pdf-o"></i> View PDF'+'</a>';
                                     return fileAhref;
                                 }
                              },
                              {
                                 'targets': 6,
                                 'searchable': false,
                                 'orderable': false,
                                 'className': 'dt-body-center',
                                 'render': function (data, type, full, meta){
                                    if (full[6]== 1) {
                                        return '<span class="label label-danger">Unpublish</span>'
                                    }
                                    else {
                                        return '<span class="label label-success">publish</span>'
                                    }
                                 }
                              },      
                              {
                                 'targets': 7,
                                 'searchable': false,
                                 'orderable': false,
                                 'className': 'dt-body-center',
                                 'render': function (data, type, full, meta){
                                     var btnAction = '<div class="btn-group">' +
                                         '  <button type="button" class="btn btn-sm btn-default btn-default-primary  btn-th-action" >' +
                                         '    <a href="javascript:void(0);" class="btnPublish" data-id="'+full[7]+'" data = "'+full[8]+'"><i class="fa fa-bullhorn" aria-hidden="true"></i> Publish</a>' +
                                         '  </button>' +
                                         // '  <ul class="dropdown-menu">' +
                                         // '    <li><a href="javascript:void(0);" class="btnPublish" data-id="'+full[7]+'" data = "'+full[8]+'"><i class="fa fa-share-square "></i> Publish</a></li>' +
                                         // '    <li role="separator" class="divider"></li>' +
                                         // '    <li><a href="javascript:void(0);" class="btnRemove" data-id="'+full[7]+'"><i class="fa fa fa-trash"></i> Remove</a></li>' +
                                         // '  </ul>' +
                                         '</div>';
                                     return btnAction;
                                 }
                              },
                           ],
                     'createdRow': function( row, data, dataIndex ) {
                             
                     },
                     dom: 'l<"toolbar">frtip',
                     initComplete: function(){
                       
                    }  
                 });

                 oTable = recordTable;
        },
     
        ActionData : function(selector,action="add",ID=""){
            var form_data = new FormData();
            var data = {};
            $('.input').each(function(){
                var field = $(this).attr('name');
                data[field] = $(this).val();
            })
            // console.log(data); return
            data['Entered_by'] = sessionNIP;
            var dataform = {
                ID : ID,
                data : data,
                action : action,
                auth : "s3Cr3T-G4N"
            };
            var token = jwt_encode(dataform,"UAP)(*");
            form_data.append('token',token);


            if ( $( '#'+'UploadFile').length ) {
                var UploadFile = $('#'+'UploadFile')[0].files;
                for(var count = 0; count<UploadFile.length; count++)
                {
                 form_data.append("FileUpload[]", UploadFile[count]);
                }
            }
            if (confirm('Are you sure ?')) {
                loading_button2(selector);
                var url = base_url_js + "rest3/__crud_calendar";
                        $.ajax({
                          type:"POST",
                          url:url,
                          data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                          contentType: false,       // The content type used when sending data to the server.
                          cache: false,             // To unable request pages to be cached
                          processData:false,
                          dataType: "json",
                          success:function(data)
                          {
                            AppJQ.setDefaultInput();
                            end_loading_button2(selector);
                            oTable.ajax.reload( null, false );
                          },
                          error: function (data) {
                            toastr.error("Connection Error, Please try again", 'Error!!');
                            end_loading_button2(selector);
                            
                          }
                        })
            }
            
        },
        DeleteFile : function(selector,filePath,idtable,fieldwhere,table,field,typefield,delimiter){
            var li = selector.closest('li');
            var DeleteDb = {
                auth : 'Yes',
                detail : {
                    idtable : idtable,
                    fieldwhere : fieldwhere,
                    table : table,
                    field : field,
                    typefield : typefield,
                    delimiter : delimiter,
                },
            }

            if (confirm('Are you sure ?')) {
                 loading_button2(selector);
                 var url = base_url_js + 'rest2/__remove_file';
                 var data = {
                     filePath : filePath,
                     auth : 's3Cr3T-G4N',
                     DeleteDb :DeleteDb,
                 }

                 var token = jwt_encode(data,"UAP)(*");
                 $.post(url,{ token:token },function (resultJson) {
                     if (resultJson == 1) {
                         li.remove();
                         oTable.ajax.reload( null, false );
                     }
                     else{
                         toastr.error('', '!!!Failed');
                     }
                 }).fail(function() {
                   toastr.error('The Database connection error, please try again', 'Failed!!');
                 }).always(function() {
                     end_loading_button2(selector);
                 });
            }
        },
        validation_file : function(selector,TheName = ''){
            var files = selector[0].files;
            var error = '';
            var msgStr = '';
            var max_upload_per_file = 4;
            if (files.length > max_upload_per_file) {
              msgStr += TheName +' should not be more than 4 Files<br>';

            }
            else
            {
              for(var count = 0; count<files.length; count++)
              {
               var no = parseInt(count) + 1;
               var name = files[count].name;
               var extension = name.split('.').pop().toLowerCase();
               if(jQuery.inArray(extension, ['pdf']) == -1)
               {
                // msgStr += TheName +' which file Number '+ no + ' Invalid Type File<br>';
                msgStr += TheName +' Invalid Type File<br>';
                //toastr.error("Invalid Image File", 'Failed!!');
                // return false;
               }

               var oFReader = new FileReader();
               oFReader.readAsDataURL(files[count]);
               var f = files[count];
               var fsize = f.size||f.fileSize;
               // console.log(fsize);

               if(fsize > 2000000) // 2mb
               {
                // msgStr += TheName + ' which file Number '+ no + ' Image File Size is very big<br>';
                msgStr += TheName + ' Image File Size is very big<br>';
                //toastr.error("Image File Size is very big", 'Failed!!');
                //return false;
               }
               
              }
            }

            if (msgStr != '') {
              toastr.error(msgStr, 'Failed!!');
              return false;
            }
            else
            {
              return true;
            }
        },

    }


    $(document).on('click','.more_details',function () {
        var head = $(this).attr('data-head');
        var Type = $(this).attr('data-type');

        if(Type!=''){
            var AcademicDescID = Type.split('.')[0].trim();
            var Status = Type.split('.')[1].trim();
        }

        var body = '<div class="well"><select class="select2-select-00 full-width-fix" size="5" id="formTeaching"><option></option></select>' +
            '<input id="formAcademicDescID" value="'+AcademicDescID+'" class="hide" hidden /> ' +
            '<input id="formStatus" value="'+Status+'" class="hide" hidden /> ' +
            '<hr/>' +
            '<div id="divMK"></div> ' +
            '<hr/>' +
            '<div class="row">' +
            '   <div class="col-xs-6">' +
            '       <input type="text" id="formSc_start" nextelement="formSc_end" name="regular" class="form-control form-tahun-akademik" readonly>' +
            '   </div>' +
            '   <div class="col-xs-6">' +
            '       <input type="text" id="formSc_end" name="regular" class="form-control form-tahun-akademik" readonly>' +
            '   </div>' +
            '</div>' +
            '<div class="row">' +
            '   <div class="col-md-12" style="text-align: right;">' +
            '       <button class="btn btn-success" id="btnSaveSc" style="margin-top: 15px;">Save</button>' +
            '   </div>' +
            '   <div class="col-md-12">' +
            '       <div id="divMsg"></div>' +
            '   </div>' +
            '</div>' +
            '</div>' +
            '<hr/>' +
            '<div id="divtableSC"></div>';

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Special Case - '+head+'</h4>');
        $('#GlobalModal .modal-body').html(body);
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        loadSelectOptionLecturersSingle('#formTeaching','');
        $('#formTeaching').select2({allowClear: true});
        $('#formSc_start').datepicker({
            showOtherMonths:true,
            autoSize: true,
            dateFormat: 'dd MM yy',
            // minDate: new Date(moment().year(),moment().month(),moment().date()),
            onSelect : function () {
                var data_date = $(this).val().split(' ');
                var nextelement = $(this).attr('nextelement')
                nextDatePick(data_date,nextelement);
            }
        });

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        loadDataSc(ID,AcademicDescID);


    });

    $(document).on('change','#formTeaching',function () {
        var formTeaching = $('#formTeaching').val();
        if(formTeaching!=''){
            //var ID = "<?php //echo $CDID; ?>//";
            var token = jwt_encode({action:'schedule',SemesterID:ID,NIP:formTeaching},'UAP)(*');
            var url = base_url_js+'api/__crudDataDetailTahunAkademik';
            $('#divMK').html('');
            $.post(url,{token:token},function (jsonResult) {
                if(jsonResult.length>0){
                    for(var i=0;i<jsonResult.length;i++){
                        var dt = jsonResult[i];
                        $('#divMK').append('<div class="checkbox">' +
                            '  <label>' +
                            '    <input type="checkbox" value="'+dt.ScheduleID+'|'+dt.NameEng+'">'+dt.ClassGroup+' | '+dt.NameEng+
                            '  </label>' +
                            '</div>');
                    }
                } else {
                    $('#divMK').html('<b>--- No Course ---</b>');
                }

            });
        }
    });

    $(document).on('click','#btnSaveSc',function () {
        var c = checkAllCourse();
        var NIP = $('#formTeaching').val();

        var dataStart = $('#formSc_start').datepicker("getDate");
        var dataEnd = $('#formSc_end').datepicker("getDate");

        if(dataStart!=null && dataEnd!=null && NIP!='' && c.length>0){

            loading_buttonSm('#btnSaveSc');
            $('#divMsg').html('');

            var Start = moment(dataStart).format('YYYY-MM-DD');
            var End = moment(dataEnd).format('YYYY-MM-DD');
            var formAcademicDescID = $('#formAcademicDescID').val();
            var formStatus = $('#formStatus').val();

            var dataSc = [];
            for(var s=0;s<c.length;s++){
                var ex = c[s].split('|');
                var d = {
                    Course : ex[1],
                    Details : {
                        SemesterID : ID,
                        AcademicDescID : formAcademicDescID,
                        UserID : NIP,
                        DataID : ex[0].trim(),
                        Start : Start,
                        End : End,
                        Status : ''+formStatus
                    }
                };
                dataSc.push(d);
            }

            var token = jwt_encode({action:'insertSC',dataForm:dataSc},'UAP)(*');
            var url = base_url_js+'api/__crudDataDetailTahunAkademik';

            $.post(url,{token:token},function (jsonResult) {

                setTimeout(function () {
                    if(jsonResult.length>0){
                        $('#divMsg').html('<div class="thumbanial" style="margin-top: 15px;padding: 10px;background: #ffffff;border: 1px solid #FF9800;"><div id="msg"></div></div>');
                        for(var d=0;d<jsonResult.length;d++){
                            var data = jsonResult[d];
                            var color = (data.Status=='1') ? 'style="color: green;"' : 'style="color: red;"';
                            var icn = (data.Status=='1') ? '<i '+color+' class="fa fa-check-circle"></i>' : '<i '+color+' class="fa fa-times-circle"></i>';

                            $('#msg').append('<div style="border-bottom: 1px solid #ddd;padding-bottom: 5px;margin-bottom: 5px;">' +
                                '<span>'+icn+' '+data.Course+'</span><br/>' +
                                '<i '+color+'>'+data.Msg+'</i>' +
                                '</div>');

                            if(data.Status=='1'){
                                var dc = data.Details;
                                $('#dtSC').append('<tr id="trSC'+dc.ID+'">' +
                                    '<td><b>'+dc.Lecturers+'</b><br/>'+dc.ClassGroup+' | '+dc.NameEng+'</td>' +
                                    '<td style="text-align: center;">'+moment(dc.Start).format('DD MMM YYYY')+' - '+moment(dc.End).format('DD MMM YYYY')+'</td>' +
                                    '<td style="text-align: center;"><button class="btn btn-danger btn-delete-aysco" data-id="'+dc.ID+'"><i class="fa fa-trash" aria-hidden="true"></i></button></td>' +
                                    '</tr>');
                            }
                        }
                    }
                    $('#btnSaveSc').html('Save').prop('disabled',false);
                },1000);
            });

        } else {
            toastr.error('Form Required','Error');
        }

    });

    $(document).on('click','.btn-delete-aysco',function () {
        var ID = $(this).attr('data-id');
        var token = jwt_encode({action:'deleteSC',ID:ID},'UAP)(*');
        var url = base_url_js+'api/__crudDataDetailTahunAkademik';

        var el = '.btn-delete-aysco[data-id='+ID+']';
        loading_buttonSm(el);
        $.post(url,{token:token},function (jsonResult) {
            setTimeout(function () {
                $('#trSC'+ID).remove();
            },1000);
        });

    });

    // ===== Save BTN Special Case KRS ======
    $(document).on('click','#btnSave_SPKRS',function () {

        var formSPClassOf = $('#formSPClassOf').val();
        var formStart_SPKRS = $('#formStart_SPKRS').datepicker("getDate");
        var formEnd_SPKRS = $('#formEnd_SPKRS').datepicker("getDate");

        if(formSPClassOf!=null && formSPClassOf!=''
            && formStart_SPKRS!=null && formStart_SPKRS!=''
            && formEnd_SPKRS!=null && formEnd_SPKRS!=''){

            var formSPProdiID = $('#formSPProdiID').val();
            var ProdiID = (formSPProdiID!='' && formSPProdiID!=null)
                ? formSPProdiID.split('.')[0]
                : null ;

            var formSPGroupProdiID = $('#formSPGroupProdiID').val();
            var ProdiGroupID = (formSPGroupProdiID!='' && formSPGroupProdiID!=null)
                ? formSPGroupProdiID : null;

            var CurriculumID = formSPClassOf.split('.')[0];
            var ClassOf = formSPClassOf.split('.')[1];

            var Start = moment(formStart_SPKRS).format('YYYY-MM-DD');
            var End = moment(formEnd_SPKRS).format('YYYY-MM-DD');

            var data = {
                action : 'insertSP_KRS',
                dataForm : {
                    SemesterID : ID,
                    CurriculumID : CurriculumID,
                    ClassOf : ClassOf,

                    ProdiID : ProdiID,
                    ProdiGroupID : ProdiGroupID,

                    StartDate : Start,
                    EndDate : End,
                    CreateBy : sessionNIP,
                    CreateAt : dateTimeNow()
                }
            };

            loading_buttonSm('#btnSave_SPKRS');
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudSpecialCaseKRS';

            $.post(url,{token:token},function (jsonResult) {
                $('#formProdi_SPKRS').val('');
                loadDataSPKRS();
                toastr.success('Data Saved','Success');
                setTimeout(function () {
                    $('#btnSave_SPKRS').prop('disabled',false).html('Save');
                },1000);
            });

        }
    });

    $(document).on('click','.btn-delete-aysc-krs',function () {

        if(confirm('Are you sure to delete this data?')){

            var ID = $(this).attr('data-id');
            var data = {
                action : 'deleteSP_KRS',
                ID : ID
            };

            var url = base_url_js+'api/__crudSpecialCaseKRS';
            var token = jwt_encode(data,'UAP)(*');
            loading_buttonSm('button[data-id='+ID+']');
            $.post(url,{token:token},function (result) {
                toastr.success('Data deleted','Success');
                setTimeout(function () {
                    loadDataSPKRS();
                },500);
            });

        }

    });
    //=====================

    function checkAllCourse() {
        var allVals = [];
        $('#divMK :checked').each(function() {
            allVals.push($(this).val());
        });

        return allVals;
    }

    function loadDataSc(ID,AcademicDescID) {
        var token = jwt_encode({action:'dataSC',SemesterID:ID,AcademicDescID:AcademicDescID},'UAP)(*');
        var url = base_url_js+'api/__crudDataDetailTahunAkademik';
        $.post(url,{token:token},function (jsonResult) {

            $('#divtableSC').html('<table id="tableSC" class="table table-bordered">' +
                '<thead>' +
                '   <tr style="background: #437e88;color: #ffffff;">' +
                '       <th>User</th>' +
                '       <th style="width: 45%;">Detail</th>' +
                '       <th style="width: 5%;">Action</th>' +
                '   </tr>' +
                '</thead>' +
                '<tbody id="dtSC"></tbody>' +
                '</table>');

            if(jsonResult.length>0){

                // $('#dtSC').empty();
                for(var t=0;t<jsonResult.length;t++){
                    var dc = jsonResult[t];
                    $('#dtSC').append('<tr id="trSC'+dc.ID+'">' +
                        '<td><b>'+dc.Lecturers+'</b><br/>'+dc.ClassGroup+' | '+dc.NameEng+'</td>' +
                        '<td style="text-align: center;">'+moment(dc.Start).format('DD MMM YYYY')+' - '+moment(dc.End).format('DD MMM YYYY')+'</td>' +
                        '<td style="text-align: center;"><button class="btn btn-danger btn-delete-aysco" data-id="'+dc.ID+'"><i class="fa fa-trash" aria-hidden="true"></i></button></td>' +
                        '</tr>');
                }

                $('#tableSC').DataTable({
                    "sDom": "<'row'<'dataTables_header clearfix'<'col-md-3'><'col-md-9'f>r>>t<'row'<'dataTables_footer clearfix'<'col-md-12'p>>>", // T is new
                    'bLengthChange' : false,
                    'bInfo' : false,
                    'pageLength' : 7
                });
            }


        });

    }

    // === Load Prodi Group di Special Case KRS ===
    $(document).on('change','#formSPProdiID',function () {

        $('#formSPGroupProdiID').empty();
        $('#formSPGroupProdiID').append('<option value="" selected>All Group</option>' +
            '<option value="" disabled>-------------</option>');
        var formSPProdiID = $('#formSPProdiID').val();
        if(formSPProdiID!='' && formSPProdiID!=null){
            load_SO_ProdiGroup(formSPProdiID.split('.')[0],'#formSPGroupProdiID');
        }


    });

    // ==== Academic Calendar ===
    // $('#btnAcademicCalendar').click(function () {
    $(document).off('click', '#btnAcademicCalendar').on('click', '#btnAcademicCalendar',function(e) {
        
        $('#GlobalModalLarge .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Academic Calendar</h4>');

        // var ck = "<?= $_SERVER['SERVER_NAME']; ?>";


        // var htmlss = (ck!='localhost')
        var htmlss = (ck='')
            ? '<div style="text-align: center;margin-top: 50px;"><h2>This module is being developed by the IT team<br/><small>We made with <i style="color: indianred;" class="fa fa-heart"></i> & <i style="color: cornflowerblue;" class="fa fa-coffee"></i> for  a better system</small></h2></div>'
            : '<div class="row">' +
            '    <div class="col-md-3" style="border-right: 1px solid #ccc;">' +
            '        <div class="form-group">' +
            '            <label>Year</label>' +
            '            <input class="form-control input" type="number" name="Year" placeholder="Ex : 2018">' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Year Name</label>' +
            '            <input class="form-control input" name="YearName" placeholder="Ex : 2018/2019">' +
            '        </div>' +
            '<form id="fileAnnouncement" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">' +
            '    <div class="form-group">' +
            '            <label><i class="fa fa-upload margin-right"></i> File .pdf | max 2 Mb </label>' +
            '        <input type="file" id="UploadFile" name="FileUpload" class="form-control" ' +
            '        <p class="help-block" id="fileShow"></p>' +
            // '        <p class="help-block" id="viewZise"></p>' +
            '    </div>' +
            '</form>' +
            '        <div class="form-group" style="text-align: right;">' +
            '            <button class="btn btn-sm btn-success" action= "add" id="btnSave">Save</button>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-9">' +
             // '    <div class="table-responsive">' +
            '        <table class="table table-striped" id = "TblCalendar">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Year</th>' +
            '                <th>Year Name</th>' +
            '                <th>File Upload</th>' +
            '                <th>Entered by</th>' +
            '                <th>Entered at</th>' +
            '                <th>Status Publish</th>' +
            '                <th> Action</th>' +
            '            </tr>' +
            '            </thead>' +
            '        </table>' +
            '    </div>' +
            '    </div>' +
            '</div>';

        $('#GlobalModalLarge .modal-body').html(htmlss);
        $('#GlobalModalLarge .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

                $('#GlobalModalLarge').modal({
                    'show' : true,
                    'backdrop' : 'static'
                });

        AppJQ.loaded();

    })

        $(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
           var selector = $(this);
           var action = selector.attr('action');
           var ID = selector.attr('data-id');
           // cek validation file
           var S_upload = $('#UploadFile');
           var cekFile =  AppJQ.validation_file(S_upload,'Upload File');
           if (cekFile) {
            AppJQ.ActionData(selector,action,ID);
           }
        })

        $(document).off('click', '.btnPublish').on('click', '.btnPublish',function(e) {
            var selector = $(this);
            var ID = $(this).attr('data-id');
            var Token = $(this).attr('data');
            var data = jwt_decode(Token);          
            AppJQ.ActionData(selector,'publishcalendar',ID);
        })

        $(document).off('click', '.btn-delete-file').on('click', '.btn-delete-file',function(e) {
            var Sthis = $(this);
            var filePath = Sthis.attr('filepath');
            var idtable = Sthis.attr('idtable');
            var fieldwhere = Sthis.attr('fieldwhere');
            var table = Sthis.attr('table');
            var field = Sthis.attr('field');
            var typefield = Sthis.attr('typefield');
            var delimiter = Sthis.attr('delimiter');
            AppJQ.DeleteFile(Sthis,filePath,idtable,fieldwhere,table,field,typefield,delimiter);
        })

        $(document).off('click', '.btnRemove').on('click', '.btnRemove',function(e) {
            var ID = $(this).attr('data-id');
            var selector = $(this);
            AppJQ.ActionData(selector,'delete',ID);
        })


            
       
   


</script>