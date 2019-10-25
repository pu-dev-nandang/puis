

<div class="row">
    <div class="col-md-4">
        <table class="table">
            <tbody>
            <tr>
                <td colspan="3">
                    <input class="form-control" id="formSearchAlumni" placeholder="Search by Name or NIM">
                </td>
            </tr>
            <tr>
                <td colspan="3" id="viewStudent"></td>
            </tr>
            <tr>
                <td style="width: 35%;">Year</td>
                <td style="width: 1%">:</td>
                <td>
                    <input class="form-control" id="Year">
                </td>
            </tr>
            <tr>
                <td>Alumni</td>
                <td>:</td>
                <td id="viewSelectAlumni"></td>
            </tr>
            <tr>
                <td>Position</td>
                <td>:</td>
                <td>
                    <select class="form-control" id="listJob"></select>
                </td>
            </tr>
            </tbody>

            <tr>
                <td colspan="3" style="text-align: center;background: lightyellow;">Form Kepuasan Pengguna Lulusan</td>
            </tr>
            <tbody id="showListPenggunaLulusan"></tbody>
            <tr>
                <td colspan="3" style="text-align: right;">
                    <button class="btn btn-success" id="btnSaveForm">Save</button>
                </td>
            </tr>
        </table>
    </div>

    <div class="col-md-8" style="border-left: 1px solid #CCCCCC;">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="well">
                    <select class="form-control" id="filterYearAlumniForm"></select>
                </div>
            </div>
        </div>
        <table class="table table-centre">
            <thead>
            <tr>
                <th style="width: 2%;">No</th>
                <th style="width: 10%;">Graduation Year</th>
                <th style="width: 20%;">Alumni</th>
                <th style="width: 1%;"><i class="fa fa-cog"></i></th>
                <th>Position</th>
            </tr>
            </thead>
            <tbody id="listDataTb"></tbody>
        </table>
    </div>
</div>



<script>

    $(document).ready(function () {
        getListYear();

        loadAspekPenilaian();

        var firstLoad = setInterval(function () {
            var filterYearAlumniForm = $('#filterYearAlumniForm').val();
            if(filterYearAlumniForm!='' && filterYearAlumniForm!=null){
                getDataYear();
                clearInterval(firstLoad);
            }
        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);

    });

    $('#formSearchAlumni').keyup(function () {
        var formSearchAlumni = $('#formSearchAlumni').val().trim();
        if(formSearchAlumni!='' && formSearchAlumni!=null){

            var url = base_url_js+'api3/__crudAlumni';
            var data = {
                action : 'searchAlumni',
                key : formSearchAlumni

            };

            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.length>0){

                    var tr = '';
                    $.each(jsonResult,function (i,v) {
                        tr = tr+'<tr>' +
                            '<td>'+(i+1)+'</td>' +
                            '<td><div id="view_'+v.NPM+'">'+ucwords(v.Name)+'</div>'+v.NPM+'</td>' +
                            '<td><button class="btn btn-sm btn-default btnShowJobs" data-npm="'+v.NPM+'"><i class="fa fa-cloud-download"></i></button></td>' +
                            '</tr>';
                    });

                    $('#viewStudent').html('<div class="well">' +
                        '                        <table class="table">' +
                        '                            <thead>' +
                        '                            <tr>' +
                        '                                <th style="width: 1%;">No</th>' +
                        '                                <th>Alumni</th>' +
                        '                                <th style="width: 7%;"><i class="fa fa-cog"></i></th>' +
                        '                            </tr>' +
                        '                            </thead>' +
                        '                            <tbody>'+tr+'</tbody>' +
                        '                        </table>' +
                        '                    </div>');


                }

            });

        }
    });

    $('#filterYearAlumniForm').change(function () {
        getDataYear();
    });

    $(document).on('click','.btnShowJobs',function () {

        var NPM = $(this).attr('data-npm');

        var url = base_url_js+'api3/__crudAlumni';
        var data = {
            action : 'jobLoadAlumni',
            NPM : NPM

        };
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {

            var Name = $('#view_'+NPM).text();
            $('#viewSelectAlumni').html('<input value="'+NPM+'" class="hide" id="NPM" />'+Name+'<br/>'+NPM);
            $('#listJob').empty();

            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    $('#listJob').append('<option value="'+v.ID+'">'+v.Title+'</option>');

                });
            }

        });


    });
    
    $('#btnSaveForm').click(function () {

        var arrAspek = [];
        $('.formAspek').each(function (i,v) {
            var IDForm = v.id;
            var Rate = $('#'+IDForm).val();
            var arr = {
                APKID : IDForm.split('_')[1],
                Rate : Rate
            }
            arrAspek.push(arr);
        });


        var Year = $('#Year').val();
        var NPM = $('#NPM').val();
        var listJob = $('#listJob').val();

        if(Year!='' && Year!=null &&
            NPM!='' && NPM!=null &&
            listJob!='' && listJob!=null){

            loading_buttonSm('#btnSaveForm');

            var url = base_url_js+'api3/__crudAlumni';
            var data = {
                action : 'insert2AlumniForm',
                dataForm : {
                    Year : Year,
                    NPM : NPM,
                    IDAE : listJob
                },
                dataAspek : arrAspek
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (result) {

                toastr.success('Data saved','Success');
                setTimeout(function () {
                    $('#btnSaveForm').html('Save').prop('disabled',false);
                    window.location.href='';
                },500);

            });

        } else {
            toastr.warning('All form are required','Warning')
        }

    });

    function getListYear() {

        var url = base_url_js+'api3/__crudAlumni';
        var data = {
            action : 'ListYearAlumniForm'
        };
        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            $('#filterYearAlumniForm').empty();

            if(jsonResult.length>0){

                $.each(jsonResult,function (i,v) {
                    $('#filterYearAlumniForm').append('<option value="'+v.Year+'">Form '+v.Year+'</option>');
                });

            }

        })

    }

    function getDataYear() {

        var filterYearAlumniForm = $('#filterYearAlumniForm').val();
        if(filterYearAlumniForm!='' && filterYearAlumniForm!=null){

            var url = base_url_js+'api3/__crudAlumni';
            var data = {
                action : 'ListDataAlumniForm',
                Year : filterYearAlumniForm
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

                $('#listDataTb').empty();
                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {

                        var GraduationYear = (v.GraduationYear!='' && v.GraduationYear!=null) ? v.GraduationYear : '';

                        var month = (v.StartMonth!='' && v.StartMonth!=null) ? moment().months((parseInt(v.StartMonth)-1)).format('MMMM') : '';
                        var year = (v.StartYear!='' && v.StartYear!=null) ? v.StartYear : '';

                        var Position = '<b>'+v.Title+'</b><br/>' +
                            '<i class="fa fa-map-marker margin-right"></i> '+v.Company+' | '+v.Position+'<br/>' +
                            '<i class="fa fa-clock-o margin-right"></i> '+month+' '+year+' ';

                        var btnAct = '<div class="btn-group">' +
                            '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                            '    <i class="fa fa-edit"></i> <span class="caret"></span>' +
                            '  </button>' +
                            '  <ul class="dropdown-menu">' +
                            '    <li><a href="javascript:void(0);" class="btnShowKepuasanPenggunaLulusan" data-token="'+jwt_encode(v,'UAP)(*')+'">Kepuasan Pengguna Lulusan</a></li>' +
                            '    <li role="separator" class="divider"></li>' +
                            '    <li><a href="javascript:void(0);" class="btnRemoveList" data-id="'+v.ID+'">Remove</a></li>' +
                            '  </ul>' +
                            '</div>';

                        $('#listDataTb').append('<tr>' +
                            '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                            '<td>'+GraduationYear+'</td>' +
                            '<td style="text-align: left;">'+ucwords(v.Name)+'<br/>'+v.NPM+'</td>' +
                            '<td>'+btnAct+'</td>' +
                            '<td style="text-align: left;">'+Position+'</td>' +
                            '</tr>');

                    });
                }

            });

        }
    }

    $(document).on('click','.btnRemoveList',function () {

        if(confirm('Are you sure?')){
            var ID = $(this).attr('data-id');
            var url = base_url_js+'api3/__crudAlumni';
            var data = {
                action : 'removeAlumniForm',
                ID : ID
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (result) {

            });
        }

    });

    $(document).on('click','.btnShowKepuasanPenggunaLulusan',function () {
       var token =  $(this).attr('data-token');
       var d = jwt_decode(token,'UAP)(*');


        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Form '+d.Year+' - '+d.Name+'</h4>');



        var tr = '';
        if(d.DetailForm.length>0){
            $.each(d.DetailForm,function (i,v) {

                var opt = '<option value="1" '+((parseInt(v.Rate)==1) ? 'selected' : '' )+'>Kurang</option>' +
                    '<option value="2" '+((parseInt(v.Rate)==2) ? 'selected' : '' )+'>Cukup</option>' +
                    '<option value="3" '+((parseInt(v.Rate)==3) ? 'selected' : '' )+'>Baik</option>' +
                    '<option value="4" '+((parseInt(v.Rate)==4) ? 'selected' : '' )+'>Sangat Baik</option>';

                tr = tr+'<tr>' +
                    '<td>'+v.Description+'</td>' +
                    '<td>:</td>' +
                    '<td><select class="form-control" data-id="'+v.ID+'">'+opt+'</select></td>' +
                    '</tr>';
            });
        }
        var htmlss = '<table class="table" id="formEditRate">' +
            '<thead>' +
            '   <tr>' +
            '       <th>Form</th>' +
            '       <th></th>' +
            '       <th>Rate</th>' +
            '   </tr>' +
            '</thead>' +
            '<tbody>'+tr+'</tbody>' +
            '</table>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button class="btn btn-success" id="btnEditRate">Save</button>');

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

    $(document).on('click','#btnEditRate',function () {

        if(confirm('Are you sure?')){

            var res = [];

            $('#formEditRate .form-control').each(function (i,v) {

                console.log(v);

                var formID = $(v).attr('data-id');
                var Rate = v.value;

                var arr = {ID : formID,Rate : Rate};
                res.push(arr);

            });

            var url = base_url_js+'api3/__crudAlumni';
            var data = {
                action : 'updateAlumniFormRate',
                dataForm : res
            };
            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (result) {
                toastr.success('Data saved','Success');
            });

        }

    });
    
    function loadAspekPenilaian() {
        var url = base_url_js+'api3/__crudAlumni';
        var data = {
            action : 'loadAspekPenilaian'
        };
        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {

            $('#showListPenggunaLulusan').empty();
            if(jsonResult.length>0){
                var opt = '<option value="1">Kurang</option>' +
                    '<option value="2">Cukup</option>' +
                    '<option value="3">Baik</option>' +
                    '<option value="4">Sangat Baik</option>';

                $.each(jsonResult,function (i,v) {
                    $('#showListPenggunaLulusan').append('<tr>' +
                        '<td>'+v.Description+'</td>' +
                        '<td>:</td>' +
                        '<td><select class="form-control formAspek" id="fm_'+v.ID+'">'+opt+'</select></td>' +
                        '</tr>');
                });
            }

        });
    }

</script>