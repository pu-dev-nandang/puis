
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="well">
            <div class="form-group">
                <label>Tahun Lulus</label>
                <select class="form-control" id="filterYear"></select>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div style="text-align: right;">
            <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="well">
            <table class="table table-centre table-bordered dataTable2Excel" data-name="sertifikat-kompetensi-profesi-industri">
                <thead>
                <tr>
                    <th rowspan="2" style="width: 1%;">No</th>
                    <th rowspan="2">Program Studi</th>
                    <th colspan="3">Jumlah Lulusan pada</th>
                    <th colspan="3">Jumlah Lulusan yang Mendapat Sertifikat Kompetensi / Profesi / Industri pada</th>
                </tr>
                <tr>
                    <th style="width: 10%;">TS-2 <span class="year3"></span></th>
                    <th style="width: 10%;">TS-1 <span class="year2"></span></th>
                    <th style="width: 10%;">TS <span class="year1"></span></th>

                    <th style="width: 10%;">TS-2 <span class="year3"></span></th>
                    <th style="width: 10%;">TS-1 <span class="year2"></span></th>
                    <th style="width: 10%;">TS <span class="year1"></span></th>
                </tr>
                </thead>
                <tbody id="listData"></tbody>
            </table>
        </div>
    </div>
</div>



<script>
    
    $(document).ready(function () {
        loadSelectOptionCurriculumForlap('#filterYear');

        var firstLoad = setInterval(function () {
            var filterYear = $('#filterYear').val();
            if(filterYear!='' && filterYear!=null){
                loadDataSertifikat();
                clearInterval(firstLoad);
            }
        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);

    });

    $('#filterYear').change(function () {
        var filterYear = $('#filterYear').val();
        if(filterYear!='' && filterYear!=null){
            loadDataSertifikat();
        }
    });
    
    function loadDataSertifikat() {

        var filterYear = $('#filterYear').val();
        if(filterYear!='' && filterYear!=null){

            filterYear = filterYear.split('.')[1];

            var url = base_url_js+'api3/__crudAgregatorTB5';
            var token = jwt_encode({action:'getDataStudentAcv',Year : filterYear},'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {

                var Year1 = filterYear;
                var Year2 = filterYear-1;
                var Year3 = filterYear-2;

                $('.year1').html('('+Year1+')');
                $('.year2').html('('+Year2+')');
                $('.year3').html('('+Year3+')');

                $("#listData").empty();
                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {

                        var viewL_Y1 = (v['L_'+Year1].length>0) ? '<a href="javascript:void(0);" data-title="'+v.Name+' - '+Year1+'" class="showDetailLulusan" data-token="'+jwt_encode(v['L_'+Year1],'UAP)(*')+'">'+v['L_'+Year1].length+'</a>' : '-';
                        var viewL_Y2 = (v['L_'+Year2].length>0) ? '<a href="javascript:void(0);" data-title="'+v.Name+' - '+Year2+'" class="showDetailLulusan" data-token="'+jwt_encode(v['L_'+Year2],'UAP)(*')+'">'+v['L_'+Year2].length+'</a>' : '-';
                        var viewL_Y3 = (v['L_'+Year3].length>0) ? '<a href="javascript:void(0);" data-title="'+v.Name+' - '+Year3+'" class="showDetailLulusan" data-token="'+jwt_encode(v['L_'+Year3],'UAP)(*')+'">'+v['L_'+Year3].length+'</a>' : '-';

                        var viewS_Y1 = (v['S_'+Year1].length>0) ? '<a href="javascript:void(0);" data-title="'+v.Name+' - '+Year1+'" class="showDetailSertifikat" data-token="'+jwt_encode(v['S_'+Year1],'UAP)(*')+'">'+v['S_'+Year1].length+'</a>' : '-';
                        var viewS_Y2 = (v['S_'+Year2].length>0) ? '<a href="javascript:void(0);" data-title="'+v.Name+' - '+Year2+'" class="showDetailSertifikat" data-token="'+jwt_encode(v['S_'+Year2],'UAP)(*')+'">'+v['S_'+Year2].length+'</a>' : '-';
                        var viewS_Y3 = (v['S_'+Year3].length>0) ? '<a href="javascript:void(0);" data-title="'+v.Name+' - '+Year3+'" class="showDetailSertifikat" data-token="'+jwt_encode(v['S_'+Year3],'UAP)(*')+'">'+v['S_'+Year3].length+'</a>' : '-';

                        $("#listData").append('<tr>' +
                            '<td>'+(i + 1)+'</td>' +
                            '<td style="text-align: left;">'+v.Name+'</td>' +
                            '<td>'+viewL_Y3+'</td>' +
                            '<td>'+viewL_Y2+'</td>' +
                            '<td>'+viewL_Y1+'</td>' +

                            '<td>'+viewS_Y3+'</td>' +
                            '<td>'+viewS_Y2+'</td>' +
                            '<td>'+viewS_Y1+'</td>' +
                            '</tr>');

                    });
                }

            });

        }

    }

    $(document).on('click','.showDetailLulusan',function () {
        var title = $(this).attr('data-title');
        var token = $(this).attr('data-token');
        var d = jwt_decode(token,'UAP)(*');

        var tr = '';
        if(d.length>0){
            $.each(d,function (i,v) {
                tr = tr+'<tr>' +
                    '<td>'+(i+1)+'</td>' +
                    '<td>'+v.NPM+'</td>' +
                    '<td style="text-align: left;">'+ucwords(v.Name)+'</td>' +
                    '</tr>';
            });
        }

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+title+'</h4>');

        var htmlss = '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table table-striped table-bordered table-centre">' +
            '            <thead>' +
            '            <tr>' +
            '                <td style="width: 1%;">No</td>' +
            '                <td style="width: 15%;">NPM</td>' +
            '                <td>Name</td>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody>'+tr+'</tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal').on('shown.bs.modal', function () {
            $('#formSimpleSearch').focus();
        })

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

    $(document).on('click','.showDetailSertifikat',function () {

        var title = $(this).attr('data-title');
        var token = $(this).attr('data-token');
        var d = jwt_decode(token,'UAP)(*');

        var tr = '';
        if(d.length>0){
            $.each(d,function (i,v) {
                tr = tr+'<tr>' +
                    '<td>'+(i + 1)+'</td>' +
                    '<td style="text-align: left;">'+v.Event+'</td>' +
                    '<td style="text-align: left;"><b>'+ucwords(v.Name)+'</b><br/>'+v.NPM+'</td>' +
                    '<td><a href="'+base_url_js+'uploads/certificate/'+v.Certificate+'" target="_blank"><i class="fa fa-download"></i> Sertifikat</a></td>' +
                    '</tr>';
            });
        }

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+title+'</h4>');

        var htmlss = '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table table-bordered table-striped table-centre">' +
            '            <thead>' +
            '            <tr>' +
            '                <th>No</th>' +
            '                <th>Even</th>' +
            '                <th>User</th>' +
            '                <th>Sertifikat</th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody id="listDataSert">'+tr+'</tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal').on('shown.bs.modal', function () {
            $('#formSimpleSearch').focus();
        })

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });


    });
    
</script>