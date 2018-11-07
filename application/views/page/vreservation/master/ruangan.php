<div class="" style="margin-top: 30px;">
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> Category Room</h4>
                    <div class="toolbar no-padding">
                        <div class="btn-group">
                          <span data-smt="" class="btn btn-xs btn-add btn-Categoryclassroom" data-action="add">
                            <i class="icon-plus"></i> Add
                           </span>
                        </div>
                    </div>
                </div>
                <div class="widget-content no-padding" id="viewCategoryClassroom"></div>
            </div>
        </div>
    </div>
</div>
<div class="" style="margin-top: 30px;">
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> Classroom</h4>
                    <div class="toolbar no-padding">
                        <div class="btn-group">
                            <span class="btn btn-xs" style="background: #083f88;color: #fff;">
                                <strong>
                                    <span id="totalRoom"></span> Room
                                </strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="widget-content no-padding" id="viewClassroom"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var CategoryRoom = <?php echo json_encode($CategoryRoom)  ?>;
    var employees = <?php echo json_encode($employees)  ?>;
    var division = <?php echo json_encode($division)  ?>;
    var GroupUser = <?php echo json_encode($GroupUser)  ?>;
    var PositionUser = <?php echo json_encode($PositionUser)  ?>;
    $(document).ready(function () {
        // console.log(employees);
        loadDataCategoryClassroom();
        loadDataClassroom();
    });

    // ----- Classroom --------
    $(document).on('click','.btn-classroom',function () {
        var action = $(this).attr('data-action');
        var classroom = (action=='edit' || action=='delete') ? $(this).attr('data-form').split('|') : '';
        var ID = (action=='edit' || action=='delete') ? classroom[0] : '';
        var Room = (action=='edit' || action=='delete') ? classroom[1] : '';
        var Seat = (action=='edit') ? parseInt(classroom[2]) : '';
        var SeatForExam = (action=='edit') ? parseInt(classroom[3]) : '';
        var DeretForExam = (action=='edit') ? parseInt(classroom[4]) : '';
        var LectureDesk = (action=='edit') ? classroom[5] : '';
        var ID_CategoryRoom = (action=='edit') ? classroom[6] : '';
        
        if(action=='add' || action=='edit'){
            <?php $positionMain = $this->session->userdata('PositionMain'); 
                $positionMain = $positionMain['IDDivision'];
            ?>
            <?php if ($positionMain == 12): ?>
                var readonly = '';
            <?php else: ?>
                var readonly = (action=='edit')? 'readonly' : '';
            <?php endif ?>

            // get CategoryRoom
                var OptionCategoryRoom = '';
                for (var i = 0; i < CategoryRoom.length; i++) {
                    var selected =  (action=='edit' && ID_CategoryRoom == CategoryRoom[i]['ID']) ? 'selected' : '';
                    OptionCategoryRoom += '<option value = "'+CategoryRoom[i]['ID']+'" '+selected+'>'+CategoryRoom[i]['NameEng']+'</option>';
                }
            $('#GlobalModal .modal-header').html('<h4 class="modal-title">Classroom</h4>');
            $('#GlobalModal .modal-body').html('<div class="row">' +
                '                            <div class="col-xs-4">' +
                '                                <div class="form-group"><label>CategoryRoom</label>' +
                '                                   <select id = "formCategoryRoom" class="form-control">'+
                                                        OptionCategoryRoom+
                '                                   </select>'+
                '                            </div></div>' +
                '                            <div class="col-xs-4">' +
                '                                <div class="form-group"><label>Room</label>' +
                '                                <input type="text" class="form-control" value="'+Room+'" '+readonly+' style="color:#333;" id="formRoom">' +
                '                            </div></div>' +
                '                            <div class="col-xs-4">' +
                '                                <div class="form-group"><label>Seat</label>' +
                '                                <input type="number" class="form-control" value="'+Seat+'" id="formSeat">' +
                '                            </div></div>' +
                '                            <div class="col-xs-4">' +
                '                                <div class="form-group"><label>Seat For Exam</label>' +
                '                                <input type="number" class="form-control" value="'+SeatForExam+'" id="formSeatForExam">' +
                '                            </div></div>' +
                '                            <div class="col-xs-4">' +
                '                                <div class="form-group"><label>Deret For Exam</label>' +
                '                                <input type="number" class="form-control" value="'+DeretForExam+'" id="formDeretForExam" min = "2" max = "10">' +
                '                            </div></div>' +
                '                            <div class="col-xs-4">' +
                '                                <div class="form-group"><label>Lecture Desk</label>' +
                '                                   <select id = "formLectureDesk" class="form-control">'+
                '                                          <option value = "left">Left</option>'+
                '                                          <option value = "right">Right</option>'+
                '                                   </select>'+
                '                            </div></div>' +
                                             '<div class="col-xs-4">'+
                                                '<div class="form-group"><label class="control-label">Layout:</label>'+
                                                 '<input type="file" data-style="fileinput" id="ExFile">'+
                                                 '</div>'+
                                             '</div>'+
                '                        </div>');
            $('#GlobalModal .modal-footer').html('<button type="button" id="btnCloseClassroom" class="btn btn-default" data-dismiss="modal">Close</button>' +
                '<button type="button" class="btn btn-success" data-id="'+ID+'" data-action="'+action+'" id="btnSaveClassroom">Save</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });

            // console.log(action);

            if(action == 'edit')
            {
                console.log(LectureDesk);
                $("#formLectureDesk option").filter(function() {
                   //may want to use $.trim in here
                   return $(this).val() == LectureDesk; 
                 }).prop("selected", true);
            }
        }
        else {
            $('#NotificationModal .modal-body').html('<div style="text-align: center;">Hapus <b style="color: red;">'+Room+'</b>  ?? | ' +
                '<button type="button" id="btnDeleteClassroom" data-id="'+ID+'" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
                '<button type="button" id="btnTidak" class="btn btn-default" data-dismiss="modal">No</button>' +
                '</div>');
            $('#NotificationModal').modal('show');
        }

    });
    $(document).on('click','#btnSaveClassroom',function () {

        var action = $(this).attr('data-action');
        var ID = $(this).attr('data-id');

        var process = true;

        var Room = $('#formRoom').val(); process = (Room=='') ? errorInput('#formRoom') : true ;
        var Seat = $('#formSeat').val(); var processSeat = (Seat!='' && $.isNumeric(Seat) && Math.floor(Seat)==Seat) ? true : errorInput('#formSeat') ;
        var SeatForExam = $('#formSeatForExam').val(); var processSeatForExam = (SeatForExam!='' && $.isNumeric(SeatForExam) && Math.floor(SeatForExam)==SeatForExam) ? true : errorInput('#formSeatForExam') ;
        var DeretForExam = $('#formDeretForExam').val(); var processDeretForExam = (DeretForExam!='' && $.isNumeric(DeretForExam) && Math.floor(DeretForExam)==DeretForExam) ? true : errorInput('#formDeretForExam') ;
        var LectureDesk = $('#formLectureDesk').val(); process = (LectureDesk=='') ? errorInput('#formLectureDesk') : true ;
        var formCategoryRoom = $("#formCategoryRoom").val();
        if(Room!='' && processSeat && processSeatForExam){
            $('#formRoom,#formSeat,#formSeatForExam,#btnCloseClassroom').prop('disabled',true);
            loading_button('#btnSaveClassroom');
            loading_page('#viewClassroom');

            var data = {
                action : action,
                ID : ID,
                formData : {
                    Room : Room,
                    Seat : Seat,
                    SeatForExam : SeatForExam,
                    DeretForExam : DeretForExam,
                    Status : 0,
                    UpdateBy : sessionNIP,
                    UpdateAt : dateTimeNow(),
                    LectureDesk : LectureDesk,
                    ID_CategoryRoom : formCategoryRoom,
                }
            };

            var form_data = new FormData();
            var fileData = document.getElementById("ExFile").files[0];
            var url = base_url_js + "api/__crudClassroomVreservation"
            var token = jwt_encode(data,"UAP)(*");
            form_data.append('token',token);
            form_data.append('fileData',fileData);
            $.ajax({
              type:"POST",
              url:url,
              data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
              contentType: false,       // The content type used when sending data to the server.
              cache: false,             // To unable request pages to be cached
              processData:false,
              dataType: "json",
              success:function(data_result)
              {
                    loadDataClassroom();

                   setTimeout(function () {

                       if(data_result.inserID!=0) {
                           toastr.success('Data tersimpan','Success!');
                           $('#GlobalModal').modal('hide');
                           // if(action=='add'){$('#formRoom,#formSeat,#formSeatForExam').val('');}
                       } else {
                           $('#formRoom,#formSeat,#formSeatForExam,#btnCloseClassroom').prop('disabled',false);
                           $('#btnSaveClassroom').prop('disabled',false).html('Save');
                           toastr.warning('Room is exist','Warning');
                       }
                   },1000);

              },
              error: function (data) {
                toastr.error("Connection Error, Please try again", 'Error!!');
                $('#btnSaveClassroom').prop('disabled',false).html('Save');
              }
            })

        } else {
            toastr.error('Form Required','Error!');
        }
    });

    $(document).on('click','#btnDeleteClassroom',function () {
        var ID = $(this).attr('data-id');
        var token = jwt_encode({action:'delete',ID:ID},'UAP)(*');
        var url = base_url_js+"api/__crudClassroomVreservation";

        $('#btnTidak').prop('disabled',true);
        loading_buttonSm('#btnDeleteClassroom');
        $.post(url,{token:token},function () {
            loadDataClassroom();
            setTimeout(function () {
                toastr.success('Data Terhapus','Success!');
                $('#NotificationModal').modal('hide');
            });
        });
    });

    $(document).on('click','.btn-Categoryclassroom',function () {
        var action = $(this).attr('data-action');
        var classroom = (action=='edit' || action=='delete') ? $(this).attr('data-form').split('|') : '';
        var ID = (action=='edit' || action=='delete') ? classroom[0] : '';
        var Name = (action=='edit' || action=='delete') ? classroom[1] : '';
        var NameEng = (action=='edit' || action=='delete') ? classroom[2] : '';
        var Approver1 = (action=='edit' || action=='delete') ? classroom[3].split(',') : '';
        var Approver2 = (action=='edit' || action=='delete') ? classroom[4].split(',') : '';
        if(action=='add' || action=='edit'){
            // option value employees
            var selected1 = (action=='add') ? '' :Approver1[0]; 
            var selected2 = (action=='add') ? '' :Approver2[0];
                var getPositionUser = (function(){
                    var aa = '';
                    for (var i = 0; i < PositionUser.length; i++) {
                        aa+= '<option value="'+PositionUser[i].ID+'" '+''+'>'+PositionUser[i].Position+'</option>';
                    }
                    return aa;
                })

                var getGroupUser = (function(){
                    var aa = '';
                    for (var i = 0; i < GroupUser.length; i++) {
                        aa+= '<option value="'+GroupUser[i].ID+'" '+''+'>'+GroupUser[i].GroupAuth+'</option>';
                    }
                    return aa;
                })

                var getEmployees = function(selected1){
                    var aa = '';
                    for(var i=0;i< employees.length;i++){
                        if (selected1 == '') {
                            aa+= '<option value="'+employees[i].NIP+'" '+''+'>'+employees[i].NIP+' | '+employees[i].Name+'</option>';
                        }
                        else
                        {
                            if (employees[i].NIP == selected1) {
                                aa+= '<option value="'+employees[i].NIP+'" '+'selected'+'>'+employees[i].NIP+' | '+employees[i].Name+'</option>';
                            }
                            else
                            {
                                aa+= '<option value="'+employees[i].NIP+'" '+''+'>'+employees[i].NIP+' | '+employees[i].Name+'</option>';
                            }
                        }
                        
                    }
                    return aa;
                }

                var getDivision = function(selected2){
                    var aa = '';
                    for(var i=0;i< division.length;i++){
                        if (selected2 == '') {
                            aa+= '<option value="'+division[i].ID+'" '+''+'>'+division[i].Division+'</option>';
                        }
                        else
                        {
                            if (division[i].ID == selected2) {
                                aa+= '<option value="'+division[i].ID+'" '+'selected'+'>'+division[i].Division+'</option>';
                            }
                            else
                            {
                                aa+= '<option value="'+division[i].ID+'" '+''+'>'+division[i].Division+'</option>';
                            }
                        }
                        
                    }
                    return aa;
                }


            $('#GlobalModal .modal-header').html('<h4 class="modal-title">Category Classroom</h4>');
            $('#GlobalModal .modal-body').html('<div class="row">' +
                '                            <div class="col-xs-6">' +
                '                                <div class="form-group"><label>Name</label>' +
                '                                <input type="text" class="form-control" value="'+Name+'" '+''+' style="color:#333;" id="formName">' +
                '                            </div></div>' +
                '                            <div class="col-xs-6">' +
                '                                <div class="form-group"><label>NameEng</label>' +
                '                                <input type="text" class="form-control" value="'+NameEng+'" '+''+' style="color:#333;" id="formNameEng">' +
                '                            </div></div>' +
                '                        </div>'+
                '                         <div class = "row">'+
                '                           <div class = "col-xs-8">'+
                                                '<div class = "row">'+
                                                    '<div class = "col-xs-12">'+
                                                        '<label>Approver 1</label>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class = "row" style = "margin-top : 10px">'+
                                                    '<div class = "col-xs-12">'+
                                                        '<div class = "form-group">'+
                                                            '<label>User Type</label>'+
                                                            '<select class=" form-control UserTypeApprover1">'+
                                                                '<option value = "0" selected>-- Choice Group User --</option>'+
                                                                getGroupUser()+    
                                                            '</select>'+
                                                        '</div>'+    
                                                    '</div>'+
                                                '</div>'+
                                                '<div class = "row" style = "margin-top : 5px">'+
                                                    '<div class = "col-xs-12">'+
                                                        '<div class = "form-group">'+
                                                            '<label>Approver Type</label>'+
                                                            '<select class=" form-control TypeApprover1">'+
                                                                '<option value = "0" selected>-- Choice Type Approver--</option>'+
                                                                '<option value ="Position">Position</option>'+
                                                                '<option value = "Employees">Employees</option>'+
                                                                '<option value = "Division">Division</option>'+      
                                                            '</select>'+
                                                        '</div>'+    
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+    
                '                           <div class = "col-xs-4">'+
                '                               <button class="btn btn-default" id = "addApprover1" style = "margin-top : 57px"><i class="icon-plus"></i> Add</button>'+
                '                           </div>'+
                '                        </div>'+
                '                         <div id = "AddApprover1"></div><hr>'+
                '                         <div class = "row" style="margin-top: 10px">'+
                '                           <div class = "col-xs-8">'+
                '                                <div class="form-group"><label>Approver 2</label>' +
                '                               <select class=" form-control Approver2">'+
                '                                   <option value = "0" selected>-- No Selected --</option>'+getDivision(selected2)+
                '                               </select></div>'+
                '                           </div>'+
                '                           <div class = "col-xs-4">'+
                '                               <button class="btn btn-default" id = "addApprover2" style = "margin-top : 23px"><i class="icon-plus"></i> Add</button>'+
                '                           </div>'+
                '                        </div>'+
                '                         <div id = "AddApprover2"></div>'        

                                    );
            $('#GlobalModal .modal-footer').html('<button type="button" id="btnCloseCategoryClassroom" class="btn btn-default" data-dismiss="modal">Close</button>' +
                '<button type="button" class="btn btn-success" data-id="'+ID+'" data-action="'+action+'" id="btnSaveCategoryClassroom">Save</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });

            $('.UserTypeApprover1').select2({
               //allowClear: true
            });

            $('.TypeApprover1').select2({
               //allowClear: true
            });

            $('.Approver2').select2({
               //allowClear: true
            });

            if (action=='edit') {
                // approver 1
                for (var i = 1; i < Approver1.length; i++) {
                    var Input = '<div class = "row" style="margin-top: 5px">'+
                                    '<div class="col-xs-12">'+
                                        '<div class="col-xs-8" style = "margin-left : -15px">'+
                                            '                               <select class=" form-control Approver1">'+
                                            '                                   <option value = "0" selected>-- No Selected --</option>'+getEmployees(Approver1[i])+
                                            '                               </select>'+
                                        '</div>'+
                                        '<div class="col-xs-4">'+
                                            '<button type="button" class="btn btn-danger btn-deleteAuto"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>'+
                                        '</div>'+
                                    '</div>'+       
                                '</div>';

                    $("#AddApprover1").append(Input); 

                    $('select[tabindex!="-1"]').select2({
                        //allowClear: true
                    });

                    $(".btn-deleteAuto").click(function(){
                        $(this)
                          .parentsUntil( 'div[class="row"]' ).remove();
                    })
                }

                // approver 2
                for (var i = 1; i < Approver2.length; i++) {
                    var Input = '<div class = "row" style="margin-top: 5px">'+
                                    '<div class="col-xs-12">'+
                                        '<div class="col-xs-8" style = "margin-left : -15px">'+
                                            '                               <select class=" form-control Approver2">'+
                                            '                                   <option value = "0" selected>-- No Selected --</option>'+getDivision(Approver2[i])+
                                            '                               </select>'+
                                        '</div>'+
                                        '<div class="col-xs-4">'+
                                            '<button type="button" class="btn btn-danger btn-deleteAuto2"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>'+
                                        '</div>'+
                                    '</div>'+       
                                '</div>';

                    $("#AddApprover2").append(Input); 

                    $('select[tabindex!="-1"]').select2({
                        //allowClear: true
                    });

                    $(".btn-deleteAuto2").click(function(){
                        $(this)
                          .parentsUntil( 'div[class="row"]' ).remove();
                    })
                }
            } // edit


            $("#addApprover1").click(function(){
                var Input = '<div class = "row" style="margin-top: 5px">'+
                                '<div class="col-xs-12">'+
                                    '<div class="col-xs-8" style = "margin-left : -15px">'+
                                        '                               <select class=" form-control Approver1">'+
                                        '                                   <option value = "0" selected>-- No Selected --</option>'+getEmployees('')+
                                        '                               </select>'+
                                    '</div>'+
                                    '<div class="col-xs-4">'+
                                        '<button type="button" class="btn btn-danger btn-deleteAuto"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>'+
                                    '</div>'+
                                '</div>'+       
                            '</div>';

                $("#AddApprover1").append(Input); 

                $('select[tabindex!="-1"]').select2({
                    //allowClear: true
                });

                $(".btn-deleteAuto").click(function(){
                    $(this)
                      .parentsUntil( 'div[class="row"]' ).remove();
                })      
            })

            $("#addApprover2").click(function(){
                var Input = '<div class = "row" style="margin-top: 5px">'+
                                '<div class="col-xs-12">'+
                                    '<div class="col-xs-8" style = "margin-left : -15px">'+
                                        '                               <select class=" form-control Approver2">'+
                                        '                                   <option value = "0" selected>-- No Selected --</option>'+getDivision('')+
                                        '                               </select>'+
                                    '</div>'+
                                    '<div class="col-xs-4">'+
                                        '<button type="button" class="btn btn-danger btn-deleteAuto2"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>'+
                                    '</div>'+
                                '</div>'+       
                            '</div>';

                $("#AddApprover2").append(Input); 

                $('select[tabindex!="-1"]').select2({
                    //allowClear: true
                });

                $(".btn-deleteAuto2").click(function(){
                    $(this)
                      .parentsUntil( 'div[class="row"]' ).remove();
                })      
            })

            $(".TypeApprover1").change(function(){
                var aa = $(this).val();
                var Op = getDivision('');
                switch(aa) {
                    case 'Position':
                        Op = getPositionUser();
                        break;
                    case 'Employees':
                        Op = getEmployees('');
                        break;
                    case 'Division':
                       Op = getDivision('');
                        break;
                }
                var Input = '<div class = "row" style="margin-top: 5px">'+
                                '<div class="col-xs-8">'+
                                    '<div class = "form-group">'+
                                        '<label>Choice Approver 1</label>'+
                                        '<select class=" form-control Approver1">'+
                                        '   <option value = "0" selected>-- No Selected --</option>'+Op+
                                        '</select>'+
                                    '</div>'+    
                                '</div>'+       
                            '</div>';

                $("#AddApprover1").html(Input); 

                $('select[tabindex!="-1"]').select2({
                    //allowClear: true
                });
            })  
             

        }
        else {
            $('#NotificationModal .modal-body').html('<div style="text-align: center;">Hapus <b style="color: red;">'+Name+' / '+NameEng+'</b>  ?? | ' +
                '<button type="button" id="btnDeleteCategoryClassroom" data-id="'+ID+'" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
                '<button type="button" id="btnTidak" class="btn btn-default" data-dismiss="modal">No</button>' +
                '</div>');
            $('#NotificationModal').modal('show');
        }

    });

    $(document).on('click','#btnSaveCategoryClassroom',function () {

        var action = $(this).attr('data-action');
        var ID = $(this).attr('data-id');

        var process = true;

        var Name = $('#formName').val(); process = (Name=='') ? errorInput('#formName') : true ;
        var NameEng = $('#formNameEng').val(); process = (Name=='') ? errorInput('#formNameEng') : true ;
        var Approver1 = [];
        $(".Approver1").each(function(){
            if ($(this).val() != 0) {
                Approver1.push($(this).val());
            }
            
        })
        
        var Approver2 = [];
        $(".Approver2").each(function(){
            if ($(this).val() != 0) {
                Approver2.push($(this).val());
            }
            
        })
        
        if(Name!='' && NameEng != '' && Approver1.length > 0 && Approver2.length > 0){
            $('#formName,#formNameEng').prop('disabled',true);
            loading_button('#btnSaveCategoryClassroom');
            loading_page('#viewCategoryClassroom');

            var data = {
                action : action,
                ID : ID,
                formData : {
                    Name : Name,
                    NameEng : NameEng,
                },
                Approver1 : Approver1,
                Approver2 : Approver2,
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+"api/__crudCategoryClassroomVreservation";

            $.post(url,{token:token},function (data_result) {
                $('#GlobalModal').modal('hide');
                loadDataCategoryClassroom();

            });

        } else {
            toastr.error('Form Required','Error!');
        }
    });

    $(document).on('click','#btnDeleteCategoryClassroom',function () {
        var ID = $(this).attr('data-id');
        var token = jwt_encode({action:'delete',ID:ID},'UAP)(*');
        var url = base_url_js+"api/__crudCategoryClassroomVreservation";

        $('#btnTidak').prop('disabled',true);
        loading_buttonSm('#btnDeleteCategoryClassroom');
        $.post(url,{token:token},function () {
            loadDataCategoryClassroom();
            setTimeout(function () {
                toastr.success('Data Terhapus','Success!');
                $('#NotificationModal').modal('hide');
            });
        });
    });

    function loadDataClassroom() {
        var token = jwt_encode({action:'read'},"UAP)(*");
        var url = base_url_js+'api/__crudClassroomVreservation';
        $.post(url,{token:token},function (json_result) {
            // console.log(json_result);

            if(json_result.length>0){
                $('#viewClassroom').html('<table class="table table-bordered" id="tbClassroom">' +
                    '                        <thead>' +
                    '                        <tr>' +
                    '                            <th class="th-center" style="width:5px;">No</th>' +
                    '                            <th class="th-center" style="width: ">Category</th>' +
                    '                            <th class="th-center" style="width: ">Class</th>' +
                    '                            <th class="th-center">Seat</th>' +
                    '                            <th class="th-center">Seat For Exam</th>' +
                    '                            <th class="th-center">Deret For Exam</th>' +
                    '                            <th class="th-center">Lecture Desk</th>' +
                    '                            <th class="th-center">Layout</th>' +
                    '                            <th class="th-center" style="width: 110px;">Action</th>' +
                    '                        </tr>' +
                    '                        </thead>' +
                    '                        <tbody id="dataClassroom"></tbody>' +
                    '                    </table>');

                var tr = $('#dataClassroom');
                var no=1;
                for(var i=0;i<json_result.length;i++){
                    var data = json_result[i];

                    $('#totalRoom').text(json_result.length);
                    tr.append('<tr>' +
                        '<td class="td-center">'+(no++)+'</td>' +
                        '<td class="td-center">'+data.NameEng+'</td>' +
                        '<td class="td-center">'+data.Room+'</td>' +
                        '<td class="td-center">'+data.Seat+'</td>' +
                        '<td class="td-center">'+data.SeatForExam+'</td>' +
                        '<td class="td-center">'+data.DeretForExam+'</td>' +
                        '<td class="td-center">'+data.LectureDesk+'</td>' +
                        '<td class="td-center">'+'<a href="'+base_url_js+'fileGetAny/vreservation-'+data.Layout+'" target="_blank"></i>Click Default Layout</a>'+'</td>' +
                        '<td class="td-center">' +
                        '<button class="btn btn-default btn-default-success btn-classroom btn-edit" data-action="edit" data-form="'+data.ID+'|'+data.Room+'|'+data.Seat+'|'+data.SeatForExam+'|'+data.DeretForExam+'|'+data.LectureDesk+'|'+data.ID_CategoryRoom+'"><i class="fa fa-pencil" aria-hidden="true"></i></button> ' +
                        ' <button class="btn btn-default btn-default-danger btn-classroom btn-delete" data-action="delete" data-form="'+data.ID+'|'+data.Room+'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>' +
                        '</td>' +
                        '</tr>');
                }

                $('#tbClassroom').DataTable({
                    "sDom": "<'row'<'dataTables_header clearfix'<'col-md-3'><'col-md-9'f>r>>t<'row'<'dataTables_footer clearfix'<'col-md-12'p>>>", // T is new
                    'bLengthChange' : false,
                    'bInfo' : false,
                    'pageLength' : 7
                });

                $('.dataTables_header .col-md-3').html('<button class="btn btn-default btn-default-primary btn-classroom btn-add" data-action="add"><i class="fa fa-plus-circle fa-right" aria-hidden="true"></i> Add Classroom</button>');
            }


        });
    }


    function loadDataCategoryClassroom() {
        var token = jwt_encode({action:'read'},"UAP)(*");
        var url = base_url_js+'api/__crudCategoryClassroomVreservation';
        $.post(url,{token:token},function (json_result) {
            // console.log(json_result);
            if(json_result.length>0){
                $('#viewCategoryClassroom').html('<table class="table table-bordered" id="tbCategoryClassroom">' +
                    '                        <thead>' +
                    '                        <tr>' +
                    '                            <th class="th-center" style="width:5px;">No</th>' +
                    '                            <th class="th-center" style="width:15px;">Name</th>' +
                    '                            <th class="th-center" style="width:15px;">Name Eng</th>' +
                    '                            <th class="th-center" style="width:15px;">Approver 1</th>' +
                    '                            <th class="th-center" style="width:15px;">Approver 2(Div)</th>' +
                    '                            <th class="th-center" style="width: 110px;">Action</th>' +
                    '                        </tr>' +
                    '                        </thead>' +
                    '                        <tbody id="dataCategoryClassroom"></tbody>' +
                    '                    </table>');

                var tr = $('#dataCategoryClassroom');
                var no=1;
                for(var i=0;i<json_result.length;i++){
                    var data = json_result[i];
                    tr.append('<tr>' +
                        '<td class="td-center">'+(no++)+'</td>' +
                        '<td class="td-left">'+data.Name+'</td>' +
                        '<td class="td-left">'+data.NameEng+'</td>' +
                        '<td class="td-left">'+data.Approver1+'</td>' +
                        '<td class="td-left">'+data.Approver2+'</td>' +
                        '<td class="td-left">' +
                        '<button class="btn btn-default btn-default-success btn-Categoryclassroom btn-edit" data-action="edit" data-form="'+data.ID+'|'+data.Name+'|'+data.NameEng+'|'+data.Approver1_ori+'|'+data.Approver2_ori+'"><i class="fa fa-pencil" aria-hidden="true"></i></button> ' +
                        ' <button class="btn btn-default btn-default-danger btn-Categoryclassroom btn-delete" data-action="delete" data-form="'+data.ID+'|'+data.Name+'|'+data.NameEng+'|'+data.Approver1+'|'+data.Approver2+'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>' +
                        '</td>' +
                        '</tr>');
                }

                $('#tbCategoryClassroom').DataTable({
                    'pageLength' : 5
                });
            }
        });
    }
</script>