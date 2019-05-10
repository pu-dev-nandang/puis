
<style>
    #tableStudent thead tr {
        background: #436888;
        color: #ffffff;
    }
    #tableStudent thead tr th, #tableStudent tbody tr td{
        text-align: center;
    }
</style>

<style>
  .btn-circle.btn-xl {
    width: 70px;
    height: 70px;
    padding: 10px 16px;
    border-radius: 35px;
    font-size: 24px;
    line-height: 1.33;
}

.btn-circle {
    width: 30px;
    height: 30px;
    padding: 6px 0px;
    border-radius: 15px;
    text-align: center;
    font-size: 12px;
    line-height: 1.42857;
}

.btn-round{
    border-radius: 10px;
}

.btn-group > .btn:first-child, .btn-group > .btn:last-child {
     border-radius: 17px;
}

</style>

<div class="row" style="margin-top: 10px;">
    <div class="col-md-12" style="text-align: right;">
        <a href="<?php echo base_url('academic/transcript/setting-transcript'); ?>" class="btn btn-info btn-round"><i class="fa fa-cog margin-right"></i> Setting</a>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="well">
            <div class="row">
                <div class="col-xs-4">
                    <select class="form-control filter-transcript" id="filterCurriculum"></select>
                </div>
                <div class="col-xs-4">
                    <select class="form-control option-filter" id="filterSemester"></select>
                </div>
                <div class="col-xs-4">
                    <select class="form-control filter-transcript" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="viewDataTr"></div>
    </div>
</div>

<script>
    $(document).ready(function () {

        loadSelectOptionCurriculumASC('#filterCurriculum','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');
        loSelectOptionSemester('#filterSemester','');

        var loadFirstPage = setInterval(function () {

            var filterCurriculum = $('#filterCurriculum').val();

            if(filterCurriculum!='' && filterCurriculum!=null){

                loadStudent();
                clearInterval(loadFirstPage);

            }

        },1000);

    });

    $('.filter-transcript').change(function () {
        loadStudent();
    });

    $(document).on('click','.btnDowloadTranscript',function () {
        var NPM = $(this).attr('data-npm');
        var DBStudent = $(this).attr('data-db');

        var token = jwt_encode({NPM:NPM,DBStudent:DBStudent},'UAP)(*');
        $('#formGlobalToken').attr('action',base_url_js+'save2pdf/transcript');
        $('#dataToken').val(token);
        $('#formGlobalToken').submit();
    });

    $(document).on('click','.btnDowloadTempTranscript',function () {
        var NPM = $(this).attr('data-npm');
        var DBStudent = $(this).attr('data-db');

        var token = jwt_encode({NPM:NPM,DBStudent:DBStudent},'UAP)(*');
        $('#formGlobalToken').attr('action',base_url_js+'save2pdf/temp_transcript');
        $('#dataToken').val(token);
        $('#formGlobalToken').submit();
    });

    $(document).on('click','.btnDownloadIjazah',function () {
        var NPM = $(this).attr('data-npm');
        var DBStudent = $(this).attr('data-db');

        var token = jwt_encode({NPM:NPM,DBStudent:DBStudent},'UAP)(*');
        var url = base_url_js+'save2pdf/ijazah';
        FormSubmitAuto(url,'POST',[{name : 'token', value : token}]);
    });

//===================tambahan SKLS tgl 17-01-2019 =======================
//=======================================================================
    $(document).on('click','.btnDownloadSkls',function () {
        var NPM = $(this).attr('data-npm');
        var DBStudent = $(this).attr('data-db');
        var filterSemester = $('#filterSemester').val();
        var Semester = filterSemester.split('.')[0];
        //alert(filterSemester);
        //alert(semester);

        var token = jwt_encode({NPM:NPM,DBStudent:DBStudent,Semester:Semester},'UAP)(*');
        var url = base_url_js+'save2pdf/skls';
        FormSubmitAuto(url,'POST',[{name : 'token', value : token}]);
    });
//=======================================================================
//=======================================================================

    function loadStudent() {
        var filterCurriculum = $('#filterCurriculum').val();
        var filterBaseProdi = $('#filterBaseProdi').val();

        loading_page('#viewDataTr');

        setTimeout(function () {
            if(filterCurriculum!='' && filterCurriculum!=null){

                $('#viewDataTr').html('' +
                    '            <table id="tableStudent" class="table table-bordered">' +
                    '                <thead>' +
                    '                <tr>' +
                    '                    <th style="width: 3%;" >No</th>' +
                    '                    <th style="width: 5%;">NIM</th>' +
                    '                    <th>Student</th>' +
                    '                    <th style="width: 13%;">Prodi</th>' +
                    '                    <th style="width: 25%;">Certificate Serial Number</th>' +
                    '                    <th style="width: 20%;">SKL Number</th>' +
                    '                    <th style="width: 15%;">SKPI</th>' +
                    '                    <th style="width: 15%;">Transcript</th>' +
                    '                    <th style="width: 7%;">Ijazah</th>' +
                    '                </tr>' +
                    '                </thead>' +
                    '                <tbody></tbody>' +
                    '            </table>');


                var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null) ? filterBaseProdi.split('.')[0] : '' ;

                var token = jwt_encode({Year:filterCurriculum.split('.')[1], ProdiID:ProdiID},'UAP)(*');

                var dataTable = $('#tableStudent').DataTable( {
                    "processing": true,
                    "serverSide": true,
                    "iDisplayLength" : 10,
                    "ordering" : false,
                    "language": {
                        "searchPlaceholder": "NIM, Name, Programme Study"
                    },
                    "ajax":{
                        url : base_url_js+'api/__getTranscript', // json datasource
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

            }
        },500);

    }


    // CRUD CSN =======
    // Edit
    $(document).on('click','.btnEditCSN',function () {
        var NPM = $(this).attr('data-npm');

        $('#formCSN'+NPM+',.btnSaveCSN[data-npm='+NPM+']').removeClass('hide');
        $('#viewCSN'+NPM+',.btnEditCSN[data-npm='+NPM+']').addClass('hide');

        $('#formCSN'+NPM).focus();

        $('.btnEditCSN').prop('disabled',true);
        $('.btnEditCNN').prop('disabled',true);

    });

    // Save
    $(document).on('click','.btnSaveCSN',function () {
       var NPM = $(this).attr('data-npm');

       var formCSN = $('#formCSN'+NPM).val();
       if(formCSN!='' && formCSN!=null){

           loading_buttonSm('.btnSaveCSN[data-npm='+NPM+']');

           var data = {
             action : 'updateCSN',
             NPM : NPM,
             CSN : formCSN
           };

           var token = jwt_encode(data,'UAP)(*');
           var url = base_url_js+'api/__crudTranscript';

           $.post(url,{token:token},function (result) {

               toastr.success('Data saved','Saved');
               setTimeout(function () {
                   $('#formCSN'+NPM+',.btnSaveCSN[data-npm='+NPM+']').addClass('hide');
                   $('#viewCSN'+NPM+',.btnEditCSN[data-npm='+NPM+']').removeClass('hide');

                   $('#viewCSN'+NPM).html(formCSN);
                   $('.btnSaveCSN[data-npm='+NPM+'], .btnEditCSN').prop('disabled',false);
                   $('.btnSaveCSN[data-npm='+NPM+']').html('<i class="fa fa-check-circle"></i>');
               },500);

           });

       } else {
           toastr.error('Form required','Error');
           $('#formCSN'+NPM).css('border','1px solid red');
       }

    });

    // CRUD CNN =======
    // Edit
    $(document).on('click','.btnEditCNN',function () {
        var NPM = $(this).attr('data-npm');

        $('#formCNN'+NPM+',.btnSaveCNN[data-npm='+NPM+']').removeClass('hide');
        $('#viewCNN'+NPM+',.btnEditCNN[data-npm='+NPM+']').addClass('hide');

        $('#formCNN'+NPM).focus();

        $('.btnEditCNN').prop('disabled',true);
        $('.btnEditCSN').prop('disabled',true);

    });

    // Save
    $(document).on('click','.btnSaveCNN',function () {
       var NPM = $(this).attr('data-npm');

       var formCNN = $('#formCNN'+NPM).val();
       if(formCNN!='' && formCNN!=null){

           loading_buttonSm('.btnSaveCNN[data-npm='+NPM+']');

           var data = {
             action : 'updateCNN',
             NPM : NPM,
             CNN : formCNN
           };

           var token = jwt_encode(data,'UAP)(*');
           var url = base_url_js+'api/__crudTranscript';

           $.post(url,{token:token},function (result) {

               toastr.success('Data saved','Saved');
               setTimeout(function () {
                   $('#formCNN'+NPM+',.btnSaveCNN[data-npm='+NPM+']').addClass('hide');
                   $('#viewCNN'+NPM+',.btnEditCNN[data-npm='+NPM+']').removeClass('hide');

                   $('#viewCNN'+NPM).html(formCNN);
                   $('.btnSaveCNN[data-npm='+NPM+'], .btnEditCNN').prop('disabled',false);
                   $('.btnSaveCNN[data-npm='+NPM+']').html('<i class="fa fa-check-circle"></i>');
               },500);

           });

       } else {
           toastr.error('Form required','Error');
           $('#formCSN'+NPM).css('border','1px solid red');
       }

    });

    // CRUD SKLNumber =======
    // Edit
    $(document).on('click','.btnEditSKLN',function () {
        var NPM = $(this).attr('data-npm');

        $('#formSKLN'+NPM+',.btnSaveSKLN[data-npm='+NPM+']').removeClass('hide');
        $('#viewSKLN'+NPM+',.btnEditSKLN[data-npm='+NPM+']').addClass('hide');

        $('#formSKLN'+NPM).focus();

        $('.btnEditSKLN').prop('disabled',true);

    });

    // Save
    $(document).on('click','.btnSaveSKLN',function () {
       var NPM = $(this).attr('data-npm');

       var formSKLN = $('#formSKLN'+NPM).val();
       if(formSKLN!='' && formSKLN!=null){

           loading_buttonSm('.btnSaveSKLN[data-npm='+NPM+']');

           var data = {
             action : 'updateSKLN',
             NPM : NPM,
             SKLN : formSKLN
           };

           var token = jwt_encode(data,'UAP)(*');
           var url = base_url_js+'api/__crudTranscript';

           $.post(url,{token:token},function (result) {

               toastr.success('Data saved','Saved');
               setTimeout(function () {
                   $('#formSKLN'+NPM+',.btnSaveSKLN[data-npm='+NPM+']').addClass('hide');
                   $('#viewSKLN'+NPM+',.btnEditSKLN[data-npm='+NPM+']').removeClass('hide');

                   $('#viewSKLN'+NPM).html(formSKLN);
                   $('.btnSaveSKLN[data-npm='+NPM+'], .btnEditSKLN').prop('disabled',false);
                   $('.btnSaveSKLN[data-npm='+NPM+']').html('<i class="fa fa-check-circle"></i>');
               },500);

           });

       } else {
           toastr.error('Form required','Error');
           $('#formSKLN'+NPM).css('border','1px solid red');
       }

    });

    function loadStudent2() {

        var filterCurriculum = $('#filterCurriculum').val();
        var filterBaseProdi = $('#filterBaseProdi').val();

        if(filterCurriculum!='' && filterCurriculum!=null
            && filterBaseProdi!='' && filterBaseProdi!=null){

            var token = jwt_encode({
                action : 'readStudent',
                Year : filterCurriculum.split('.')[1],
                ProdiID : filterBaseProdi.split('.')[0]
            },'UAP)(*');
            var url = base_url_js+'api/__crudTranscript';
            
            $.post(url,{token:token},function (jsonResult) {
                console.log(jsonResult);
            });



        }

    }
</script>