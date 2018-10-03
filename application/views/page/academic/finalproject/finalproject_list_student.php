<style>
    #tableDataStd thead tr {
        background: #436888;
        color: #ffffff;
    }
    #tableDataStd thead tr th, #tableStudent tbody tr td{
        text-align: center;
    }
</style>


<div class="row" style="margin-top: 30px;">
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
        <div id="viewDataIjazah"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadSelectOptionCurriculumASC('#filterCurriculum','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');

        var loadpageF = setInterval(function () {
            var filterCurriculum = $('#filterCurriculum').val();
            if(filterCurriculum!='' && filterCurriculum!=null){
                loadDataStudent();
                clearInterval(loadpageF);
            }
        },1000);

    });

    function loadDataStudent() {
        var filterCurriculum = $('#filterCurriculum').val();
        var filterBaseProdi = $('#filterBaseProdi').val();

        if(filterCurriculum!='' && filterCurriculum!=null){
            $('#viewDataIjazah').html('<table class="table table-bordered" id="tableDataStd">' +
                '                <thead>' +
                '                <tr>' +
                '                    <th style="width: 1%;" rowspan="2">No</th>' +
                '                    <th style="width: 7%;" rowspan="2">NIM</th>' +
                '                    <th rowspan="2">Student</th>' +
                '                    <th style="width: 7%;" rowspan="2">Programme<br/>Study</th>' +
                '                    <th style="width: 60%;" colspan="2">Final Project</th>' +
                '                    <th style="width: 5%;" rowspan="2">Action</th>' +
                '                </tr>' +
                '                <tr>' +
                '                    <th style="width: 30%;">Title Indonesia</th>' +
                '                    <th style="width: 30%;">Title English</th>' +
                '                </tr>' +
                '                </thead>' +
                '                <tbody></tbody>' +
                '            </table>');

            var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null) ? filterBaseProdi.split('.')[0] : '' ;

            var token = jwt_encode({Year:filterCurriculum.split('.')[1], ProdiID:ProdiID},'UAP)(*');

            var dataTable = $('#tableDataStd').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "NIM, Name, Programme Study"
                },
                "ajax":{
                    url : base_url_js+'api/__getFinalProject', // json datasource
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

    }

    $(document).on('click','.btnEditFP',function () {
        var ID = $(this).attr('data-id');

        $('#viewTitleInd'+ID+', #viewTitleEng'+ID+',#btnEditFP'+ID).addClass('hide');
        $('#formTitleInd'+ID+', #formTitleEng'+ID+', #btnSaveFP'+ID).removeClass('hide');

    });

    $(document).on('click','.btnSaveEditFP',function () {
        var ID = $(this).attr('data-id');
        var NPM = $(this).attr('data-npm');

        loading_buttonSm('#btnSaveFP'+ID);

        var formTitleInd = $('#formTitleInd'+ID).val();
        var formTitleEng = $('#formTitleEng'+ID).val();

        var data = {
            action : 'updateFP',
            NPM : NPM,
            dataForm : {
                TitleInd : formTitleInd,
                TitleEng : formTitleEng
            }
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudFinalProject';

        $.post(url,{token:token},function (result) {
            toastr.success('Data saved','Success');
            setTimeout(function () {

                $('#btnSaveFP'+ID).html('Save').prop('disabled',false);

                $('#viewTitleInd'+ID).text(formTitleInd);
                $('#viewTitleEng'+ID).text(formTitleEng);

                $('#viewTitleInd'+ID+', #viewTitleEng'+ID+',#btnEditFP'+ID).removeClass('hide');
                $('#formTitleInd'+ID+', #formTitleEng'+ID+', #btnSaveFP'+ID).addClass('hide');

            },1000);
        });



    });
</script>