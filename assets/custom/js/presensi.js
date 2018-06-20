$(document).on('click','.inputLecturerAttd',function () {
    var ID = $(this).attr('data-id');
    var No = $(this).attr('data-no');

    var url = base_url_js+'api/__crudAttendance';
    var data = {
        action : 'getAttdLecturers',
        ID : ID,
        No : No
    };
    var token = jwt_encode(data,'UAP)(*');

    $.post(url,{token:token},function (jsonResult) {

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Attendance '+No+'</h4>');

        var attd_nip = jsonResult.NIP;
        var attd_bap = (jsonResult.BAP!='' && jsonResult.BAP!=null) ? jsonResult.BAP : '';
        // var attd_date = (jsonResult.Date!='' && jsonResult.Date!=null) ? jsonResult.Date : '';
        var attd_in = (jsonResult.In!='' && jsonResult.In!=null) ? jsonResult.In : '';
        var attd_out = (jsonResult.Out!='' && jsonResult.Out!=null) ? jsonResult.Out : '';


        var body_attd = '<div class="row">' +
            '                        <div class="col-xs-4">' +
            '                            <div class="form-group">' +
            '                                <label>Date</label>' +
            '                               <input type="text" id="formDate" class="form-control form-attd" readonly>' +
            '                            </div>' +
            '                            <div class="form-group">' +
            '                                <label>In</label>' +
            '                                <div id="inputIn" class="input-group">' +
            '                                    <input data-format="hh:mm:ss" type="text" id="formIn" class="form-control form-attd" value="'+attd_in+'" readonly/>' +
            '                                    <span class="add-on input-group-addon">' +
            '                                        <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>' +
            '                                    </span>' +
            '                                </div>' +
            '                            </div>' +
            '                            <div class="form-group">' +
            '                                <label>Out</label>' +
            '                                <div id="inputOut" class="input-group">' +
            '                                    <input data-format="hh:mm:ss" type="text" id="formOut" class="form-control form-attd" value="'+attd_out+'" readonly/>' +
            '                                    <span class="add-on input-group-addon">' +
            '                                      <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>' +
            '                                    </span>' +
            '                                </div>' +
            '                            </div>' +
            '                        </div>' +
            '                        <div class="col-xs-8">' +
            '                            <div class="form-group">' +
            '                                <label>BAP</label>' +
            '                                <textarea class="form-control" id="formBAP" rows="9">'+attd_bap+'</textarea>' +
            '                            </div>' +
            '                        </div>' +
            '                    </div>';

        $('#GlobalModal .modal-body').html(body_attd);



        $('#inputIn').datetimepicker({
            pickDate: false
        });

        $('#inputOut').datetimepicker({
            pickDate: false
        });

        $("#formDate").datepicker({
            showOtherMonths:true,
            autoSize: true,
            dateFormat: 'dd MM yy'
            // minDate: new Date(moment().year(),moment().month(),moment().date()),
            // onSelect : function () {
            //     var data_date = $(this).val().split(' ');
            //     var nextelement = $(this).attr('nextelement')
            //     nextDatePick(data_date,nextelement);
            // }
        });

        if(jsonResult.Date !=='0000-00-00' && jsonResult.Date != null){
            var d = new Date(jsonResult.Date);

            $('#formDate').datepicker('setDate',d);
        } else {

        }



        $('a.ui-state-default').attr('href','javascript:void(0)');

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> ' +
            '<button class="btn btn-success" id="btnSaveAttdLecturer" data-no="'+No+'" data-id="'+ID+'">Save</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

});

$(document).on('click','#btnSaveAttdLecturer',function () {
    var ID = $(this).attr('data-id');
    var No = $(this).attr('data-no');
    var NIP = '<?php echo $NIP; ?>';

    var formDate = $('#formDate').datepicker("getDate");
    var formIn = $('#formIn').val();
    var formOut = $('#formOut').val();
    var formBAP = $('#formBAP').val();


    if(formDate!=null && formDate!='' &&
        formIn!=null && formIn!='' &&
        formOut!=null && formOut!='' &&
        formBAP!=null && formBAP!=''){

        $('#formDate,#formIn,#formOut,#formBAP').prop('disabled',true);
        loading_buttonSm('#btnSaveAttdLecturer');

        var url = base_url_js+'api/__crudAttendance';
        var data = {
            action : 'UpdtAttdLecturers',
            ID : ID,
            No : No,
            formUpdate : {
                NIP : NIP,
                Date : moment(formDate).format('YYYY-MM-DD'),
                In : formIn,
                Out : formOut,
                BAP : formBAP
            }
        };
        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (resultJson) {
            getDataAttendance();
            toastr.success('Data Saved','Success');
            $('#GlobalModal').modal('hide');
        });

    } else {
        toastr.warning('Form Required','Warning');
    }


});
