


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

    $(document).on('click','.btn-reset-password',function () {
        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Log Me Out </b><hr/> ' +
            '<button type="button" class="btn btn-primary btnActionLogOut" style="margin-right: 5px;">Yes</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
            '</div>');
        $('#NotificationModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
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
