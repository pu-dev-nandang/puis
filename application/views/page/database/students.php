


<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="thumbnail">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" id="filterCurriculum"></select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="filterBaseProdi"></select>
                </div>

                <div class="col-md-4">
                    <select class="form-control" id="filterStatus"></select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" style="padding: 10px;text-align: right;">
        <hr/>
        <div class="">
            <span style="color: #03a9f4;"><i class="fa fa-circle"></i> Lulus | </span>
            <span style="color: green;"><i class="fa fa-circle"></i> Aktif | </span>
            <span style="color: #ff9800;"><i class="fa fa-circle"></i> Cuti | </span>
            <span style="color: red;"><i class="fa fa-circle"></i> Non-Aktif / Mengundurkan Diri / DO</span>
        </div>

        <hr/>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="pageStudents"></div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#filterCurriculum,#filterBaseProdi').empty();
        // $('#filterCurriculum').append('<option value="" disabled selected>-- Curriculum--</option>' +
        //     '                <option disabled>------------------------------------------</option>');
        loadSelectOptionCurriculum('#filterCurriculum','');

        // $('#filterBaseProdi').append('<option value="">--- All Program Study ---</option>' +
        //     '<option disabled>------------------------------------------</option>');
        loadSelectOptionBaseProdi('#filterBaseProdi','');

        setTimeout(function () { loadPage(); },500);

        $('#filterStatus').append('<option value="">--- All Status ---</option>' +
            '<option disabled>------------------------------------------</option>');
        loadSelectOptionStatusStudent('#filterStatus','');

    });

    $(document).on('change','#filterCurriculum,#filterBaseProdi,#filterStatus',function () {
        loadPage();
    });

    $(document).on('click','.btnDetailStudent',function () {
        var ta = $(this).attr('data-ta');
        var NPM = $(this).attr('data-npm');

        // var url = base_url_js+'api/__crudeStudent';
        var url = base_url_js+'database/showStudent';
        var data = {
            action : 'read',
            formData : {
                ta : ta,
                NPM : NPM
            }
        };

        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (html) {
            // console.log(jsonResult);
            //
            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Detail Mahasiswa</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        });


    });

    $(document).on('click','.btnLoginPortalStudents',function () {

        var NPM = $(this).attr('data-npm');

        var token = jwt_encode({NPM:NPM},'s3Cr3T-G4N');

        var url = base_url_portal_students+'auth/loginFromAkademik?token='+token;
        PopupCenter(url,'xtf','1300','500');

    });


    // Change Password Students
    $(document).on('click','.btn-reset-password',function () {

        var Name = $(this).attr('data-name');
        var NPM = $(this).attr('data-npm');
        var StatusID = $(this).attr('data-statusid');

        if(StatusID=='3'){
            $('#NotificationModal .modal-body').html('<div style="text-align: center;">Reset Password - <b>'+Name+'</b><hr/> ' +
                '<input class="form-control" type="text" id="formNewPassword" placeholder="Input new password . . ." />' +
                '<p>Users must change their password at the next login</p>' +
                '<div style="text-align: right;margin-top: 15px;">' +
                '<button type="button" class="btn btn-default" id="btnCloseResetPassword" data-dismiss="modal">Close</button> ' +
                '<button type="button" class="btn btn-success" data-npm="'+NPM+'"  id="btnSaveResetPassword">Save</button>' +
                '</div></div>');
        } else {
            $('#NotificationModal .modal-body').html('<div style="text-align: center;">Reset Password - <b>'+Name+'</b><hr/> ' +
                '<h3>Student not active</h3>' +
                '<div style="text-align: right;margin-top: 15px;">' +
                '<button type="button" class="btn btn-default" id="btnCloseResetPassword" data-dismiss="modal">Close</button> ' +
                '</div></div>');
        }



        $('#NotificationModal').on('shown.bs.modal', function () {
            $('#formNewPassword').focus();
        })

        $('#NotificationModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });
    $(document).on('click','#btnSaveResetPassword',function () {
       var  formNewPassword = $('#formNewPassword').val();
       if(formNewPassword!='' && formNewPassword!=null){

           loading_buttonSm('#btnSaveResetPassword');
           $('#btnCloseResetPassword').prop('disabled',true);

           var data = {
             action : 'resetPassword',
               NewPassword : formNewPassword,
               NPM : $(this).attr('data-npm')
           };
           var token = jwt_encode(data,'UAP)(*');
           var url = base_url_js+'api/__crudStatusStudents';
           $.post(url,{token:token},function (result) {
                toastr.success('Password Reset','Success');
                setTimeout(function () {
                    $('#NotificationModal').modal('hide');
                },500);
           });
       }
    });

    // Change Status
    $(document).on('click','.btn-change-status',function () {
        var Name = $(this).attr('data-name');
        var NPM = $(this).attr('data-npm');
        var StatusID = $(this).attr('data-statusid');
        var dataYear = $(this).attr('data-year');
        var EmailPU = $(this).attr('data-emailpu');

        var usermail = (EmailPU!='' && EmailPU!=null) ? EmailPU.split('@')[0] : '';

        $('#NotificationModal .modal-body').html('<div style="text-align: center;">Change Status - <b>'+Name+'</b><hr/> ' +
            '<div class="form-group" style="text-align: left;">' +
            '<label>Status</label>' +
            '<select class="form-control" id="formChangeStatus"></select>' +
            '</div>' +
            '<div class="form-group" style="text-align: left;">' +
            '<label>Email PU</label>' +
            // '<input class="form-control" id="formEmailPU" value="'+EmailPU+'" />' +
            '<div class="input-group">' +
            '  <input type="text" class="form-control" placeholder="Username" id="formEmailPU" value="'+usermail+'">' +
            '  <span class="input-group-addon" id="basic-addon2">@podomorouniversity.ac.id</span>' +
            '</div>' +
            '</div>' +
            '<div style="text-align: right;margin-top: 15px;">' +
            '<button type="button" class="btn btn-default" id="btnCloseChangeStatus" data-dismiss="modal">Close</button> ' +
            '<button type="button" class="btn btn-success" data-npm="'+NPM+'" data-year="'+dataYear+'"  id="btnSaveChangeStatus">Save</button>' +
            '</div></div>');

        loadSelectOptionStatusStudent('#formChangeStatus',StatusID);


        $('#NotificationModal').on('shown.bs.modal', function () {
            $('#formNewPassword').focus();
        })

        $('#NotificationModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });
    $(document).on('click','#btnSaveChangeStatus',function () {

        var formChangeStatus = $('#formChangeStatus').val();
        var formEmailPU = $('#formEmailPU').val();

        if(formEmailPU!='' && formEmailPU!=null){

            loading_buttonSm('#btnSaveChangeStatus');
            $('#btnCloseChangeStatus').prop('disabled',true);

            var data = {
                action : 'changeStatus',
                StatusID : formChangeStatus,
                NPM : $(this).attr('data-npm'),
                EmailPU : formEmailPU+'@podomorouniversity.ac.id',
                dataYear : $(this).attr('data-year')
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudStatusStudents';
            $.post(url,{token:token},function (result) {
                load_students();
                toastr.success('Status Changed','Success');
                setTimeout(function () {
                    $('#NotificationModal').modal('hide');
                },500);
            });
        } else {
            toastr.warning('Email PU','is Required');
            $('#formEmailPU').css('border','1px solid red');
            setTimeout(function () {
                $('#formEmailPU').css('border','1px solid #ccc');
            },2000);

        }


    });

    function loadPage() {

        loading_page('#pageStudents');

        var filterCurriculum = $('#filterCurriculum').val();
        var filterBaseProdi = $('#filterBaseProdi').val();
        var filterStatus = $('#filterStatus').val();

        if(filterCurriculum!='' && filterCurriculum!=null
        && filterBaseProdi!='' && filterBaseProdi!=null){

            var data = {
                Year : filterCurriculum.split('.')[1],
                ProdiID : filterBaseProdi.split('.')[0],
                StatusStudents : filterStatus
            };

            var url = base_url_js+'database/loadPageStudents';
            $.post(url,{data:data},function (page) {
                setTimeout(function () {
                    $('#pageStudents').html(page);
                },500);
            });

        }


    }

</script>
