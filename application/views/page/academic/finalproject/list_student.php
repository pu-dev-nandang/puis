
<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }
    .btn-upload {
        padding: 3px 5px 3px 5px;
        font-size: 10px !important;
        /*font-weight: bold;*/
    }

    #tableData td {
        vertical-align: middle;
    }
    #tableData td:nth-child(1), #tableData td:nth-child(2), #tableData td:nth-child(3){
        vertical-align: top !important;
    }
</style>

<div class="row">
    <div class="col-md-10 col-md-offset-1" style="text-align: right;margin-top: 30px;">
        <div class="well">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" id="filterSemester"></select>
                </div>
                <div class="col-md-5">
                    <select class="form-control" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                        <option disabled>------------------------------------------</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="filterStatus">
                        <option value="">-- All Status --</option>
                        <option disabled>-------------------------------------</option>
                        <optgroup label="Ijazah SMA">
                            <option value="i.0">Not yet upload</option>
                            <option value="i.1">Uploaded</option>
                        </optgroup>
                        <optgroup label="Academic">
                            <option value="a.0">Waiting Clearance</option>
                            <option value="a.1">Clearance</option>
                        </optgroup>
                        <optgroup label="Library">
                            <option value="l.0">Waiting Clearance</option>
                            <option value="l.1">Clearance</option>
                        </optgroup>
                        <optgroup label="Finance">
                            <option value="f.0">Waiting Clearance</option>
                            <option value="f.1">Clearance</option>
                        </optgroup>
                        <optgroup label="Student Life">
                            <option value="s.0">Waiting Clearance</option>
                            <option value="s.1">Clearance</option>
                        </optgroup>
                        <optgroup label="Kaprodi">
                            <option value="k.0">Waiting Approval</option>
                            <option value="k.1">Approved</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="viewData"></div>
    </div>
</div>

<script>

    $(document).ready(function () {
        loSelectOptionSemester('#filterSemester','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');

        var firstLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                loadData();
                clearInterval(firstLoad);
            }
        },1000);
    });

    $('#filterSemester,#filterBaseProdi,#filterStatus').change(function () {
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){
            loadData();
        }
    });

    function loadData() {
        var filterSemester = $('#filterSemester').val();
        var filterBaseProdi = $('#filterBaseProdi').val();
        var filterStatus = $('#filterStatus').val();


        if(filterSemester!='' && filterSemester!=null){

            var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null)
                ? filterBaseProdi.split('.')[0] : '';

            var StatusTA = (filterStatus!='' && filterStatus!=null) ? filterStatus : '';

            var SemesterID = filterSemester.split('.')[0];

            $('#viewData').html('<table class="table table-striped table-bordered" id="tableData">' +
                '            <thead>' +
                '            <tr>' +
                '                <th rowspan="2" style="width: 1%;">No</th>' +
                '                <th rowspan="2" style="width: 15%;">Student</th>' +
                '                <th rowspan="2">Course</th>' +
                '                <th rowspan="2" style="width: 10%;">Ijazah SMA / SKHUN</th>' +
                '                <th colspan="5">Clearance</th>'+
                '            </tr>' +
                '           <tr>' +
                '               <th style="width: 10%;">Academic</th>' +
                '               <th style="width: 10%;">Library</th>' +
                '               <th style="width: 10%;">Finance</th>' +
                '               <th style="width: 10%;">Student Life</th>' +
                '               <th style="width: 10%;">Kaprodi</th>' +
                '           </tr>' +
                '            </thead>' +
                '        </table>');


            var token = jwt_encode({action : 'viewYudisiumList',SemesterID:SemesterID,ProdiID : ProdiID, StatusTA : StatusTA},'UAP)(*');
            var url = base_url_js+'api3/__crudYudisium';

            // window.dataTable.ajax.reload(null, false);
            window.dataTable = $('#tableData').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "NIM, Name"
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
    }

    $(document).on('click','.btnClearnt',function () {

        if(confirm('Are you sure?')){
            var NPM = $(this).attr('data-npm');
            var C = $(this).attr('data-c');
            var token = jwt_encode({action : 'updateClearent',NPM:NPM,C:C},'UAP)(*');
            var url = base_url_js+'api3/__crudYudisium';

            $.post(url,{token:token},function (result) {

                // loadData();
                window.dataTable.ajax.reload(null, false);
                toastr.success('Data saved','Success');

            });
        }

    });

    $(document).on('change','.uploadIjazahStudentFile',function () {
        var ID = $(this).attr('data-id');
        var NPM = $(this).attr('data-npm');
        var FileNameOld = $(this).attr('data-old');
        UploadFile(ID,NPM,FileNameOld);
    });


    function UploadFile(ID,NPM,FileNameOld) {

        var input = $('#upload_files_'+ID);
        var files = input[0].files[0];

        var sz = parseFloat(files.size) / 1000000; // ukuran MB
        var ext = files.type.split('/')[1];

        if(Math.floor(sz)<=8){

            var fileName = 'Ijazah_'+NPM+'_'+moment().unix()+'.'+ext;
            var formData = new FormData( $("#formupload_files_"+ID)[0]);
            var url = base_url_js+'academic/final-project/uploadIjazahStudent?fileName='+fileName+'&old='+FileNameOld+'&&NPM='+NPM;

            $.ajax({
                url : url,  // Controller URL
                type : 'POST',
                data : formData,
                async : false,
                cache : false,
                contentType : false,
                processData : false,
                success : function(data) {
                    toastr.success('Upload Success','Saved');
                    setTimeout(function () {
                        window.dataTable.ajax.reload(null, false);
                        // loadData();
                    },500);

                }
            });

        }

    }

    $(document).on('click','.btnAddMentor',function () {

        var STD = $(this).attr('data-std');
        var m1 = $(this).attr('data-m1');
        var m2 = $(this).attr('data-m2');
        var ID = $(this).attr('data-id');

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+STD+'</h4>');
        $('#GlobalModal .modal-body').html('<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table">' +
            '            <tr>' +
            '                <td style="width: 15%;">Mentor 1</td>' +
            '                <td style="width: 1%">:</td>' +
            '                <td><select class="select2-select-00 full-width-fix" size="5" id="formMentor1"><option></option></select></td>' +
            '            </tr>' +
            '            <tr>' +
            '                <td>Mentor 2</td>' +
            '                <td>:</td>' +
            '                <td><select class="select2-select-00 full-width-fix" size="5" id="formMentor2"><option></option></select></td>' +
            '            </tr>' +
            '        </table>' +
            '    </div>' +
            '</div>');

        loadSelectOptionLecturersSingle('#formMentor1',m1);
        loadSelectOptionLecturersSingle('#formMentor2',m2);
        $('#formMentor1,#formMentor2').select2({allowClear: true});


        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '<button type="button" class="btn btn-success" id="btnSaveMentor">Save</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $('#btnSaveMentor').click(function () {

            var formMentor1 = $('#formMentor1').val();
            var formMentor2 = $('#formMentor2').val();

            if(formMentor1!='' && formMentor1!=null){

                if(formMentor1!=formMentor2){

                    loading_buttonSm('#btnSaveMentor');

                    var data = {
                        action : 'updateMentorFP',
                        ID : ID,
                        dataForm : {
                            MentorFP1 : formMentor1,
                            MentorFP2 : formMentor2
                        }
                    };
                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api3/__crudYudisium';

                    $.post(url,{token:token},function (result) {
                        toastr.success('Data saved','Success');
                        // loadData();
                        window.dataTable.ajax.reload(null, false);

                        setTimeout(function () {
                            $('#GlobalModal').modal('hide');
                        },500);

                    });

                } else {
                    toastr.warning('Mentor 1 & 2 cannot same','Warning');
                }




            } else {
                toastr.error('Mentor 1 is required','Error');
            }

        });

    });

</script>