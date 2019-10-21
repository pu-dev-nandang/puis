
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="well">
            <label>Tahun Lulus</label>
            <select class="form-control" id="filterYear"></select>
        </div>
    </div>
</div>

<div class="row">
    <div class="well">
        <div class="col-md-12">
            <table class="table table-centre">
                <thead>
                <tr>
                    <th rowspan="2" style="width: 1%;">No</th>
                    <th rowspan="2">Program Pendidikan</th>
                    <th rowspan="2" style="width: 15%;">Banyaknya Lulusan yang Telah Bekerja/ Berwirausaha</th>
                    <th colspan="3">Tingkat/Ukuran Tempat Kerja/Berwirausaha</th>
                </tr>
                <tr>
                    <th style="width: 15%;">Lokal / Wilayah / Berwirausaha tidak Berbadan Hukum</th>
                    <th style="width: 15%;">Nasional / Berwirausaha Berbadan Hukum</th>
                    <th style="width: 15%;">Multinasiona / Internasional</th>
                </tr>
                </thead>
                <tbody id="listData"></tbody>
            </table>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {
        loadSelectOptionCurriculumForlap('#filterYear','');
        var firsLoad = setInterval(function () {
            var filterYear = $('#filterYear').val();
            if(filterYear!='' && filterYear!=null){
                loadData();
                clearInterval(firsLoad);
            }
        },1000);
    });

    $('#filterYear').change(function () {
        loadData();
    });

    function loadData() {
        var filterYear = $('#filterYear').val();
        var Year = filterYear.split('.')[1];
        var url = base_url_js+'api3/__crudAgregatorTB5';
        var data = {
            action : 'readTableTempatKerjaLulusan',
            Year : Year
        };
        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {

            $('#listData').empty();
            if(jsonResult.length>0) {
                $.each(jsonResult, function (i, v) {

                    var viewDess = (v.Description!=null && v.Description!='') ? v.Description : v.Name;

                    var Lulusan = (parseInt(v.StudentTotal)>0) ? '<a href="javascript:void(0);" class="showDetailStudent" data-title="'+viewDess+'" data-token="'+jwt_encode(v.StudentDetail,'UAP)(*')+'">'+v.StudentTotal+'</a>' : '-';

                    var Exp_L = (v.Exp_L.length>0) ? '<a href="javascript:void(0);" class="showDetailStudent" data-token="'+jwt_encode(v.Exp_L,'UAP)(*')+'" data-title="'+viewDess+' | Lokal / Wilayah / Berwirausaha tidak Berbadan Hukum">'+v.Exp_L.length+'</a>' : '-';
                    var Exp_N = (v.Exp_N.length>0) ? '<a href="javascript:void(0);" class="showDetailStudent" data-token="'+jwt_encode(v.Exp_N,'UAP)(*')+'" data-title="'+viewDess+' | Nasional / Berwirausaha Berbadan Hukum">'+v.Exp_N.length+'</a>' : '-';
                    var Exp_M = (v.Exp_M.length>0) ? '<a href="javascript:void(0);" class="showDetailStudent" data-token="'+jwt_encode(v.Exp_M,'UAP)(*')+'" data-title="'+viewDess+' | Multinasiona / Internasional">'+v.Exp_M.length+'</a>' : '-';

                    $('#listData').append('<tr>' +
                        '<td style="border-right: 1px solid #CCCCCC;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+viewDess+'</td>' +
                        '<td style="border-right: 1px solid #CCCCCC;">'+Lulusan+'</td>' +
                        '<td>'+Exp_L+'</td>' +
                        '<td>'+Exp_N+'</td>' +
                        '<td>'+Exp_M+'</td>' +
                        '</tr>');

                });
            }

        });
    }

    $(document).on('click','.showDetailStudent',function () {

        var fy = $('#filterYear option:selected').text();
        var token = $(this).attr('data-token');
        var title = $(this).attr('data-title');
        var dataToken = jwt_decode(token,'UAP)(*');

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+fy+' - '+title+'</h4>');

        var tr = '';
        if(dataToken.length>0){
            $.each(dataToken,function (i,v) {

                var YudisiumDate = (v.YudisiumDate!='' && v.YudisiumDate!=null)
                    ? moment(v.YudisiumDate).format('MMM YYYY')
                    : '-';

                var Job = (v.Job!='') ? v.Job : '';
                var JobDescription = (v.JobDescription!='') ? ' - '+v.JobDescription : '';

                tr = tr+'<tr>' +
                    '<td>'+(i+1)+'</td>' +
                    '<td style="text-align: left;"><b>'+ucwords(v.Name)+'</b><br/>'+v.NPM+'<br/>'+v.Prodi+'</td>' +
                    '<td>'+YudisiumDate+'</td>' +
                    '<td style="text-align: left;">'+Job+'<br/>'+JobDescription+'</td>' +
                    '</tr>';
            });
        }

        $('#GlobalModal .modal-body').html('<table class="table table-striped table-centre">' +
            '    <thead>' +
            '    <tr>' +
            '        <th style="width: 1%;">No</th>' +
            '        <th>Nama</th>' +
            '        <th style="width: 19%;">Yudisium</th>' +
            '        <th style="width: 30%;">Job</th>' +
            '    </tr>' +
            '    </thead><tbody>'+tr+'</tbody>' +
            '</table>');
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });


    });

</script>