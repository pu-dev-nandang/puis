
<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-4 col-md-offset-4" style="text-align: right;margin-top: 30px;">
        <div class="well">
            <div class="row">
                <div class="col-md-12">
                    <select class="form-control" id="filterSemester"></select>
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

        var firstLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                loadData();
                clearInterval(firstLoad);
            }
        },1000);
    });

    $('#filterSemester').change(function () {
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){
            loadData();
        }
    });

    function loadData() {
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){

            var SemesterID = filterSemester.split('.')[0];

            $('#viewData').html('<table class="table table-striped table-bordered" id="tableData">' +
                '            <thead>' +
                '            <tr>' +
                '                <th style="width: 2%;">No</th>' +
                '                <th style="width: 20%;">Student</th>' +
                '                <th>Course</th>' +
                '                <th style="width: 10%;">Score</th>' +
                '                <th style="width: 10%;">Ijazah SMA / SKHUN</th>' +
                '                <th style="width: 10%;">Library Clearent</th>' +
                '                <th style="width: 10%;">Finance Clearent</th>' +
                '                <th style="width: 10%;">Kaprodi</th>' +
                '            </tr>' +
                '            </thead>' +
                '        </table>');


            var token = jwt_encode({action : 'viewYudisiumList',SemesterID:SemesterID},'UAP)(*');
            var url = base_url_js+'api3/__crudYudisium';

            var dataTable = $('#tableData').DataTable( {
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
            var ID = $(this).attr('data-id');
            var C = $(this).attr('data-c');
            var token = jwt_encode({action : 'updateClearent',ID:ID,C:C},'UAP)(*');
            var url = base_url_js+'api3/__crudYudisium';

            $.post(url,{token:token},function (result) {

                loadData();
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
        console.log(input);
        var files = input[0].files[0];

        var sz = parseFloat(files.size) / 1000000; // ukuran MB
        var ext = files.type.split('/')[1];

        if(Math.floor(sz)<=8){

            var fileName = moment().unix()+'_'+NPM+'.'+ext;
            var formData = new FormData( $("#formupload_files_"+ID)[0]);
            var url = base_url_js+'academic/final-project/uploadIjazahStudent?fileName='+fileName+'&old='+FileNameOld+'&&id='+ID;

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
                        loadData();
                    },500);

                }
            });

        }

    }

</script>