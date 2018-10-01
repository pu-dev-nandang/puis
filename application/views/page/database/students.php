
<style>
    #tableStudent thead tr th {
        background: #20525a;
        color: #ffffff;
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="well">
            <div class="row">
                <div class="col-xs-4">
                    <select class="form-control filter-db-std" id="filterCurriculum">
                        <option value="">-- All Curriculum --</option>
                        <option disabled>------------------------</option>
                    </select>
                </div>
                <div class="col-xs-5">
                    <select class="form-control filter-db-std" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                        <option disabled>------------------------</option>
                    </select>
                </div>
                <div class="col-xs-3">
                    <select class="form-control filter-db-std" id="filterStatus">
                        <option value="">-- All Status --</option>
                        <option disabled>------------------------</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="divDataStudent">

        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        loadSelectOptionCurriculum('#filterCurriculum','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');
        loadSelectOptionStatusStudent('#filterStatus','');
        loadStudent();
    });

    $('.filter-db-std').change(function () {
        loadStudent();
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
    
    function loadStudent() {
        loading_page('#divDataStudent');
        var filterCurriculum = $('#filterCurriculum').val();
        var filterBaseProdi = $('#filterBaseProdi').val();
        var filterStatus = $('#filterStatus').val();

        var Year = (filterCurriculum!='' && filterCurriculum!=null)
            ? filterCurriculum.split('.')[1] : '';
        var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null)
            ? filterBaseProdi.split('.')[0] : '';
        var StatusStudents = (filterStatus!='' && filterStatus!=null)
            ? filterStatus : '';

        setTimeout(function () {
            $('#divDataStudent').html('<table class="table table-bordered" id="tableStudent">' +
                '                <thead>' +
                '                <tr>' +
                '                    <th style="width: 1%;">No</th>' +
                '                    <th style="width: 7%;">NIM</th>' +
                '                    <th style="width: 5%;">Photo</th>' +
                '                    <th style="">Name</th>' +
                '                    <th style="width: 15%;">Progamme Study</th>' +
                '                    <th style="width: 5%;">Upload Photo</th>' +
                '                    <th style="width: 5%;">Action</th>' +
                '                    <th style="width: 7%;">Login Portal</th>' +
                '                    <th style="width: 5%;">Status</th>' +
                '                </tr>' +
                '                </thead>' +
                '            </table>');

            var data = {
                Year : Year,
                ProdiID : ProdiID,
                StatusStudents : StatusStudents
            };
            var token = jwt_encode(data,'UAP)(*');

            var dataTable = $('#tableStudent').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "NIM, Name, Programme Study"
                },
                "ajax":{
                    url : base_url_js+'api/database/__getListStudent', // json datasource
                    ordering : false,
                    data : {token:token},
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                    }
                }
            } );
        },1000);

    }

    $(document).on('change','.uploadPhotoEmp',function () {
        // uploadPhoto();
        var NPM = $(this).attr('data-npm');
        viewImageBeforeUpload(this,'#imgThum'+NPM,'','','','#formTypeImage'+NPM);
        var Type = $('#formTypeImage'+NPM).val();

        var FileName = NPM+'.'+Type;
        var db = $(this).attr('data-db');
        uploadPhoto(db,NPM,FileName);

    });

    function uploadPhoto(db,NPM,fileName) {

        if(fileName!='' && fileName!=null){

            var formData = new FormData( $("#fmPhoto"+NPM)[0]);
            var url = base_url_js+'api/database/upload_photo_student?f='+db+'&&fileName='+fileName;

            $.ajax({
                url : url,  // Controller URL
                type : 'POST',
                data : formData,
                async : false,
                cache : false,
                contentType : false,
                processData : false,
                success : function(data) {

                    var jsonData = JSON.parse(data);

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
            toastr.error('NIK / NIK is empty','Error');
        }

    }
</script>