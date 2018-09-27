
<style>
    #tableStudent thead tr {
        background: #436888;
        color: #ffffff;
    }
    #tableStudent thead tr th, #tableStudent tbody tr td{
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="well">
            <div class="row">
                <div class="col-xs-6">
                    <select class="form-control filter-transcript" id="filterCurriculum"></select>
                </div>
                <div class="col-xs-6">
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
                    '                    <th style="width: 3%;">No</th>' +
                    '                    <th style="width: 5%;">NIM</th>' +
                    '                    <th>Student</th>' +
                    '                    <th style="width: 13%;">Prodi</th>' +
                    '                    <th style="width: 5%;">IPK</th>' +
                    '                    <th style="width: 10%;">Transcript</th>' +
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
                        "searchPlaceholder": "Course, MKCode, Coordinator"
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