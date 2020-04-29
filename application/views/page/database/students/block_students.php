
<style>
    #viewStudent .label {
        line-height: 2.5;
        font-size: 12px;
        border-radius: 10px;
        padding: 7px;
        padding-top: 3.5px;
    }

    #viewStudent .btn-sm {
        text-align: right;
        border-radius: 10px;
        padding: 0px 4px;
    }
</style>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Form Block</h4>
            </div>

            <div class="panel-body" style="min-height: 200px;">
                <div class="form-group">
                    <label>Title</label>
                    <input class="hide" id="formID">
                    <input class="form-control" id="formTitle" maxlength="200">
                    <p class="help-block"><span id="showChar">0</span> / 200</p>
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <textarea class="form-control" id="formMessage" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label>List Student | <a href="javascript:void(0);" class="btn btn-sm btn-default btnAddStudent">Add Student</a></label>
                    <div id="viewStudent"></div>
                    <textarea class="form-control hide" id="dataStudent"></textarea>
                </div>
                <div class="form-group" style="text-align: right;">
                    <button class="btn btn-success" id="btnSubmit">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">List Block</h4>
            </div>
            <div class="panel-body" style="min-height: 200px;">
                <div id="showListBlock"></div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        loadDataBlock();
    });

    function loadDataBlock(){
        var url = base_url_js+'api4/__crudBlockStudent';
        var data = {
            action : 'getDataBlock'
        };

        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {

            $('#showListBlock').empty();
            if(jsonResult.length>0){

                $.each(jsonResult,function (i,v) {


                    var viewCreatedAt = moment(v.CreatedAt).format('DD MMM YYYY HH:mm');
                    var viewUpdatedAt = moment(v.UpdatedAt).format('DD MMM YYYY HH:mm');

                    var viewStudent = '';
                    $.each(v.Students,function (i2,v2) {
                        viewStudent = viewStudent+'<span style="border-radius: 3px;margin-right: 5px;background: #0c5fa1;color: #ffffff;padding: 5px;font-size: 10px;border: 1px solid #fff;line-height: 3;">'+v2.NPM+' - '+v2.Name+'</span>';
                    });

                    var tkn = jwt_encode(v,'UAP)(*');

                    $('#showListBlock').append('<div class="thumbnail" style="margin-bottom: 15px;">' +
                        '                    <table class="table table-striped" style="margin-top: 10px;">' +
                        '                        <tr>' +
                        '                            <td style="width: 15%;border-top: none;">Titel</td>' +
                        '                            <td style="width:1%;border-top: none;">:</td>' +
                        '                            <td style="border-top: none;">'+v.Title+'</td>' +
                        '                        </tr>' +
                        '                        <tr>' +
                        '                            <td>Message</td>' +
                        '                            <td>:</td>' +
                        '                            <td>'+v.Message+'</td>' +
                        '                        </tr>' +
                        '                        <tr>' +
                        '                            <td>Students ('+v.Students.length+') </td>' +
                        '                            <td>:</td>' +
                        '                            <td><div>'+viewStudent+'</div></td>' +
                        '                        </tr>' +
                        '                        <tr>' +
                        '                            <td>Action</td>' +
                        '                            <td>:</td>' +
                        '                            <td>' +
                        '                                <button class="btn btn-sm btn-danger removeBlock" data-id="'+v.ID+'"><i class="fa fa-trash"></i></button>' +
                        '                                <button class="btn btn-sm btn-info editBlock" data-tkn="'+tkn+'"><i class="fa fa-edit"></i></button>' +
                        '                                <span style="font-size: 10px;"><br/> Created By : '+v.CreatedByName+' '+viewCreatedAt+' | Updated By : '+v.UpdatedByName+' '+viewUpdatedAt+'</span>' +
                        '                            </td>' +
                        '                        </tr>' +
                        '                    </table>' +
                        '                </div>');
                });

            } else {
                $('#showListBlock').html('<div>Data not yet</div>');
            }

        });
    }

    $(document).on('click','.removeBlock',function () {
        if(confirm('Are you sure?')){
            var ID = $(this).attr('data-id');
            var data = {
                action : 'removeDateBlock',
                ID : ID
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api4/__crudBlockStudent';

            $.post(url,{token:token},function (jsonResult) {
                toastr.success('Data removed','Success');
                loadDataBlock();
            });

        }

    });

    $(document).on('click','.editBlock',function () {
       var tkn = $(this).attr('data-tkn');
       var dataToken = jwt_decode(tkn,'UAP)(*');
        $('#formID').val(dataToken.ID);
        $('#formTitle').val(dataToken.Title);
        $('#showChar').html(dataToken.Title.length);
        $('#formMessage').val(dataToken.Message);
        $('#dataStudent').val(JSON.stringify(dataToken.Students));
        viewStudentSelected();
    });

    $('#btnSubmit').click(function () {

        var formTitle = $('#formTitle').val();
        var formMessage = $('#formMessage').val();
        var dataStudent = $('#dataStudent').val();
        var ds = (dataStudent!='' && dataStudent!=null) ? JSON.parse(dataStudent) : [];

        if(formTitle!='' && formTitle!=null &&
            formMessage!='' && formMessage!=null && ds.length > 0){

            loading_modal_show();

            var formID = $('#formID').val();

            var data = {
                action : 'EditingDateBlock',
                ID : (formID!='') ? formID : '',
                dataForm : {
                    Title : formTitle,
                    Message : formMessage,
                    UpdatedBy : sessionNIP,
                    UpdatedAt : getDateTimeNow()
                },
                dataStudent : ds
            };
            var token = jwt_encode(data,'UAP)(*');

            var url = base_url_js+'api4/__crudBlockStudent';
            $.post(url,{token:token},function (result) {
                toastr.success('Data saved','Success');
                loadDataBlock();
                $('#formID').val('');
                $('#formTitle').val('');
                $('#formMessage').val('');
                $('#dataStudent').val('');
                $('#viewStudent').empty();

                setTimeout(function () {
                    loading_modal_hide();
                },500);
            });


        } else {
            toastr.warning('All form are requored','Warning');
        }

    });

    $('#formTitle').keyup(function () {
        var formTitle = $('#formTitle').val();
        $('#showChar').html(formTitle.length);
    });

    $('.btnAddStudent').click(function () {
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">List Student</h4>');

        var htmlss = '' +
            '<input id="getStudent" placeholder="NIM, Student Name" class="form-control">' +
            '<p class="help-block">Type minimum 4 character</p>' +
            '<div id="viewListStdC"></div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal').on('shown.bs.modal', function () {
            $('#getStudent').focus();
        });

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    $(document).on('keyup','#getStudent',function () {
        var getStudent = $('#getStudent').val();
        var std = getStudent.trim();
        if(std.length>=4){

            var data = {
                action : 'getStudentServerSide',
                Key : std
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api2/__crudAnnouncement';

            $.post(url,{token:token},function (jsonResult) {

                $('#viewListStdC').html('<table class="table">' +
                    '    <thead>' +
                    '    <tr>' +
                    '        <th style="width: 1%;">No</th>' +
                    '        <th style="width: 10%;">NIM</th>' +
                    '        <th>Name</th>' +
                    '        <th style="width: 10%;">Act.</th>' +
                    '    </tr>' +
                    '    </thead>' +
                    '    <tbody id="viewCStd"></tbody>' +
                    '</table>');

                if(jsonResult.length>0){

                    var dataStudent = $('#dataStudent').val();
                    var ds = (dataStudent!='' && dataStudent!=null) ? JSON.parse(dataStudent) : [];

                    var no =1;
                    $.each(jsonResult,function (i,v) {

                        var obj = ds.find(o => o.NPM === v.NPM);
                        var indxOf = ds.indexOf(obj);

                        var btnAct = (indxOf!=-1)
                            ? '<button class="btn btn-sm btn-danger rmvStd" data-name="'+v.Name+'" data-nim="'+v.NPM+'"><i class="fa fa-times-circle"></i></button>'
                            : '<button class="btn btn-sm btn-success addStd" data-name="'+v.Name+'" data-nim="'+v.NPM+'"><i class="fa fa-download"></i></button>';

                        $('#viewCStd').append('<tr>' +
                            '<td>'+no+'</td>' +
                            '<td>'+v.NPM+'</td>' +
                            '<td>'+v.Name+'</td>' +
                            '<td id="td_'+v.NPM+'">'+btnAct+'</td>' +
                            '</tr>');
                        no += 1;
                    });
                } else {
                    $('#viewCStd').append('<tr><td colspan="4">Not yet student</td></tr>');
                }

                // $('#dataStudent').val(JSON.stringify(jsonResult));
                // $('#viewToStd').html(jsonResult.length);
            });


        }
    });

    $(document).on('click','.addStd',function () {

        $('.addStd,.rmvStd').prop('disabled',true);

        var Name = $(this).attr('data-name');
        var NPM = $(this).attr('data-nim');

        var dataStudent = $('#dataStudent').val();
        var ds = (dataStudent!='' && dataStudent!=null) ? JSON.parse(dataStudent) : [];

        var arr = {
            Name : Name,
            NPM : NPM
        };
        ds.push(arr);

        $('#dataStudent').val(JSON.stringify(ds));
        $('#viewToStd').html(ds.length);

        // Action In Modal
        $('#trModalStd_'+NPM).css('background','#ffffff');
        $('#tdModalStd_'+NPM).html('<button class="btn btn-sm btn-danger rmvLec" data-name="'+Name+'" data-nim="'+NPM+'"><i class="fa fa-times-circle"></i></button>');

        viewStudentSelected();

        setTimeout(function () {
            $('.addStd,.rmvStd').prop('disabled',false);
            $('#td_'+NPM).html('<button class="btn btn-sm btn-danger rmvStd" data-name="'+Name+'" data-nim="'+NPM+'"><i class="fa fa-times-circle"></i></button>');
        },500);
    });

    $(document).on('click','.rmvStd',function () {
        var Name = $(this).attr('data-name');
        var NPM = $(this).attr('data-nim');
        var dataStudent = $('#dataStudent').val();
        var ds = (dataStudent!='' && dataStudent!=null) ? JSON.parse(dataStudent) : [];

        if(ds.length>0){

            $('.addStd,.rmvStd').prop('disabled',true);

            var newArr = [];
            for(var i=0;i<ds.length;i++){
                if(ds[i].NPM!=NPM){
                    newArr.push(ds[i]);
                } else {
                    $('#td_'+ds[i].NPM).html('<button class="btn btn-sm btn-success addStd" data-name="'+Name+'" data-nim="'+NPM+'"><i class="fa fa-download"></i></button>');
                }
            }
            $('#dataStudent').val(JSON.stringify(newArr));
            $('#viewToStd').html(newArr.length);

            // Action In Modal
            $('#trModalStd_'+NPM).css('background','#ffdfdf');
            $('#tdModalStd_'+NPM).html('<button class="btn btn-sm btn-success addStd" data-name="'+Name+'" data-nim="'+NPM+'"><i class="fa fa-download"></i></button>');

            viewStudentSelected();

            setTimeout(function () {
                $('.addStd,.rmvStd').prop('disabled',false);
            },500);
        }

    });

    function viewStudentSelected() {

        var dataStudent = $('#dataStudent').val();
        var ds = (dataStudent!='' && dataStudent!=null) ? JSON.parse(dataStudent) : [];

        $('#viewStudent').empty();

        if(ds.length>0){
            $.each(ds,function (i,v) {
                $('#viewStudent').append('<span class="label label-primary"><span style="padding-top: 7px;padding-right: 7px;">'+v.NPM+' - '+v.Name+'</span>' +
                    '<button class="btn btn-sm btn-default rmvStd" data-name="'+v.Name+'" data-nim="'+v.NPM+'"><i class="fa fa-times-circle"></i></button></span> ');
            })
        } else {
            // $('#viewStudent').html('<div style="text-align: center;font-size: 10px;color: #CCCCCC;">--Data not yet--</div>');
        }

    }


</script>
