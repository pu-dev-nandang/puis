

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="well">
            <select id="filterCurriculum" class="form-control"></select>
        </div>
    </div>
    <div class="col-md-4">
        <div style="text-align: right;">
            <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            <!-- <button id="saveToExcel" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button> -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-centre table-bordered dataTable2Excel" data-name="2_C_Pembelajaran_praktikum_praktik_praktik_lapangan" id="tableCourse">
            <thead>
            <tr>
                <th style="width: 1%;" rowspan="2">No</th>
                <th rowspan="2">Program Studi</th>
                <th colspan="3">Bobot Kredit Mata Kuliah</th>
                <th style="width: 10%;" rowspan="2">Total</th>
            </tr>
            <tr>
                <th style="width: 10%;">Teori</th>
                <th style="width: 10%;">Praktikum / Praktik</th>
                <th style="width: 10%;">Praktik Lapangan</th>
            </tr>
            </thead>
            <tbody id="listData"></tbody>
        </table>
    </div>
</div>




<script>

    $(document).ready(function () {
        loadSelectOptionCurriculum('#filterCurriculum','');
        var firsLoad = setInterval(function () {
            var filterCurriculum = $('#filterCurriculum').val();
            if(filterCurriculum!='' && filterCurriculum!=null){
                loadDataProdi();
                clearInterval(firsLoad);
            }
        },1000);

        setTimeout(function (args) {
            clearInterval(firsLoad);
        },5000);

    });

    $('#filterCurriculum').change(function () {
        loadDataProdi();
    });

    function loadDataProdi() {
        var filterCurriculum = $('#filterCurriculum').val();
        if(filterCurriculum!='' && filterCurriculum!=null){
            var CurriculumID = filterCurriculum.split('.')[0];
            var data = {
                action : 'getAllCourse',
                CurriculumID : CurriculumID
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB2';
            $.post(url,{token:token},function (jsonResult) {

                console.log(jsonResult);

                $('#listData').empty();
                if(jsonResult.length>0){

                    var t_r = 0;
                    var p_r = 0;
                    var pl_r = 0;
                    var total_r = 0;

                    $.each(jsonResult,function (i,v) {

                        var Details = v.Details;
                        var t =0;
                        var t_data =[];
                        var p =0;
                        var p_data =[];
                        var pl =0;
                        var pl_data =[];
                        if(Details.length>0){
                            $.each(Details,function (i2,v2) {
                                if(parseInt(v2.CourseType)==1){
                                    t = t + parseInt(v2.TotalSKS);
                                    t_data.push(v2);
                                }
                                else if(parseInt(v2.CourseType)==2){
                                    p = p + parseInt(v2.TotalSKS);
                                    p_data.push(v2);
                                }
                                else if(parseInt(v2.CourseType)==3){
                                    pl = pl + parseInt(v2.TotalSKS);
                                    pl_data.push(v2);
                                }

                            });
                        }

                        var total = t + p + pl;
                        t_r = t_r + parseInt(t);
                        p_r = p_r + parseInt(p);
                        pl_r = pl_r + parseInt(pl);
                        total_r = total_r + parseInt(total);

                        var view_t = (t>0) ? '<a href="javascript:void(0);" class="btnLoadData" data-title="'+v.Name+' - Teori" data-token="'+jwt_encode(t_data,'UAP)(*')+'">'+t+'</a>' : 0;
                        var view_p = (p>0) ? '<a href="javascript:void(0);" class="btnLoadData" data-title="'+v.Name+' - Praktikum / Praktik" data-token="'+jwt_encode(p_data,'UAP)(*')+'">'+p+'</a>' : 0;
                        var view_pl = (pl>0) ? '<a href="javascript:void(0);" class="btnLoadData" data-title="'+v.Name+' - Praktik Lapangan" data-token="'+jwt_encode(pl_data,'UAP)(*')+'">'+pl+'</a>' : 0;


                        $('#listData').append('<tr>' +
                            '<td>'+(i+1)+'</td>' +
                            '<td style="text-align: left;">'+v.Name+'</td>' +
                            '<td>'+view_t+'</td>' +
                            '<td>'+view_p+'</td>' +
                            '<td>'+view_pl+'</td>' +
                            '<td>'+total+'</td>' +
                            '</tr>');


                        if((i+1)==jsonResult.length){
                            $('#listData').append('<tr>' +
                                '<td colspan="2">Jumlah</td>' +
                                '<td>'+t_r+'</td>' +
                                '<td>'+p_r+'</td>' +
                                '<td>'+pl_r+'</td>' +
                                '<td>'+total_r+'</td>' +
                                '</tr>');
                        }
                    });
                }

            });
        }
    }

    $(document).on('click','.btnLoadData',function () {

        var title = $(this).attr('data-title');
        var token = $(this).attr('data-token');
        var data = jwt_decode(token,'UAP)(*');

        var td = '';
        $.each(data,function (i,v) {
           td = td+'<tr>' +
               '<td style="border-right: 1px solid #CCCCCC;">'+(i+1)+'</td>' +
               '<td style="text-align: left;"><b>'+v.Name+'</b><br/>'+v.MKCode+' | Semester : '+v.Semester+'</td>' +
               '<td>'+v.TotalSKS+'</td>' +
               '</tr>';
        });

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+title+'</h4>');

        $('#GlobalModal .modal-body').html('<table class="table table-centre table-striped">' +
            '    <thead>' +
            '    <tr>' +
            '        <th style="width: 1%;">No</th>' +
            '        <th>Mata kuliah</th>' +
            '        <th style="width: 5%;">Kredit</th>' +
            '    </tr>' +
            '    </thead>' +
            '    <tbody>'+td+'</tbody>' +
            '</table>');

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

</script>