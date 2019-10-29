

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="well">
            <label>Tahun Kuesioner</label>
            <select class="form-control" id="filterYear"></select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div style="text-align: right;">
            <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
        </div>
        <div class="well">
            <table class="table table-centre dataTable2Excel" data-name="kepuasan-pengguna-lulusan">
                <thead>
                <tr>
                    <th rowspan="2" style="width: 1%;">No</th>
                    <th rowspan="2">Aspek Penilaian</th>
                    <th colspan="4">Hasil Penlaian (%)</th>
                </tr>
                <tr>
                    <th style="width: 10%;">Sangat Baik</th>
                    <th style="width: 10%;">Baik</th>
                    <th style="width: 10%;">Cukup</th>
                    <th style="width: 10%;">Kurang</th>
                </tr>
                </thead>
                <tbody id="listData"></tbody>
            </table>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        loadData();
        loadSelectOptionCurriculumForlap('#filterYear','');

        var firstLoad = setInterval(function (args) {
            var filterYear = $('#filterYear').val();
            if(filterYear!='' && filterYear!=null){
                loadData();
                clearInterval(firstLoad);
            }
        },1000);

        setTimeout(function () { clearInterval(firstLoad); },5000);
    });

    $('#filterYear').change(function () {
        loadData();
    });

    function loadData() {

        var filterYear = $('#filterYear').val();
        if(filterYear!='' && filterYear!=null){

            var Year = filterYear.split('.')[1];
            var url = base_url_js+'api3/__crudAgregatorTB5';
            var data = {
                action : 'readTableKepuasanPenggunaLulusan',
                Year : Year
            };
            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {

                $('#listData').empty();

                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {

                        var Total_SB_D_rate = (v.Total_SB_D.length>0) ? ((parseInt(v.Total_SB_D.length) / parseInt(v.Details.length))  * 100 ) : '';
                        var Total_B_D_rate = (v.Total_B_D.length>0) ? ((parseInt(v.Total_B_D.length) / parseInt(v.Details.length))  * 100 ) : '';
                        var Total_C_D_rate = (v.Total_C_D.length>0) ? ((parseInt(v.Total_C_D.length) / parseInt(v.Details.length))  * 100 ) : '';
                        var Total_K_D_rate = (v.Total_K_D.length>0) ? ((parseInt(v.Total_K_D.length) / parseInt(v.Details.length))  * 100 ) : '';

                        var Total_SB_D = (v.Total_SB_D.length>0) ? '<a href="javascript:void(0);" class="btnShowingData" data-title="'+v.Description+' | Sangat Baik" data-token="'+jwt_encode(v.Total_SB_D,'UAP)(*')+'">'+Total_SB_D_rate+'</a>' : '-';
                        var Total_B_D = (v.Total_B_D.length>0) ? '<a href="javascript:void(0);" class="btnShowingData" data-title="'+v.Description+' | Baik" data-token="'+jwt_encode(v.Total_B_D,'UAP)(*')+'">'+Total_B_D_rate+'</a>' : '-';
                        var Total_C_D = (v.Total_C_D.length>0) ? '<a href="javascript:void(0);" class="btnShowingData" data-title="'+v.Description+' | Cukup" data-token="'+jwt_encode(v.Total_C_D,'UAP)(*')+'">'+Total_C_D_rate+'</a>' : '-';
                        var Total_K_D = (v.Total_K_D.length>0) ? '<a href="javascript:void(0);" class="btnShowingData" data-title="'+v.Description+' | Kurang" data-token="'+jwt_encode(v.Total_K_D,'UAP)(*')+'">'+Total_K_D_rate+'</a>' : '-';

                        $('#listData').append('<tr>' +
                            '<td style="border-right: 1px solid #CCCCCC;">'+(i+1)+'</td>' +
                            '<td style="text-align: left;">'+v.Description+'</td>' +
                            '<td>'+Total_SB_D+'</td>' +
                            '<td>'+Total_B_D+'</td>' +
                            '<td>'+Total_C_D+'</td>' +
                            '<td>'+Total_K_D+'</td>' +
                            '</tr>');
                    });
                }

            });

        }


    }

    $(document).on('click','.btnShowingData',function () {

        var tahun = $('#filterYear option:selected').text();

       var token = $(this).attr('data-token');
       var title = $(this).attr('data-title');
       var dataToken = jwt_decode(token,'UAP)(*');

       var tr = '';
       if(dataToken.length>0){
           $.each(dataToken,function (i,v) {
              tr = tr+'<tr>' +
                  '<td style="border-left: 1px solid #CCCCCC;">'+(i+1)+'</td>' +
                  '<td style="text-align: left;"><b>'+v.Name+'</b><br/>'+v.NPM+'</td>' +
                  '<td style="text-align: left;">'+v.Company+'</td>' +
                  '</tr>';
           });
       }


        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+tahun+' | '+title+'</h4>');

        var htmlss = '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table table-striped table-centre">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th style="width: 30%;">Student</th>' +
            '                <th>Company</th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody>'+tr+'</tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

</script>