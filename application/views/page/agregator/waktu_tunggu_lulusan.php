


<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="well">
            <select class="form-control" id="filterYear"></select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="well">
            <table class="table table-striped table-centre">
                <thead>
                <tr>
                    <th rowspan="2" style="width: 1%;">No</th>
                    <th rowspan="2">Program Pendidikan</th>
                    <th colspan="3">Rata-rata Masa Tunggu Lulusan <br/>(bulan)</th>
                </tr>
                <tr>
                    <th style="width: 15%;">TS <span class="viewTH3"></span></th>
                    <th style="width: 15%;">TS <span class="viewTH2"></span></th>
                    <th style="width: 15%;">TS <span class="viewTH1"></span></th>
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
        var firstLoad = setInterval(function () {
            var filterYear = $('#filterYear').val();
            if(filterYear!='' && filterYear!=null){
                loadData();
                clearInterval(firstLoad);
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
            action : 'readTableWaktuTungguLulus',
            Year : Year
        };
        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {

            var Y1 = Year;
            var Y2 = (parseInt(Year) - 1);
            var Y3 = (parseInt(Year) - 2);

            $('.viewTH3').html(' - '+Y3);
            $('.viewTH2').html(' - '+Y2);
            $('.viewTH1').html(' - '+Y1);

            $('#listData').empty();

            if(jsonResult.length>0) {
                $.each(jsonResult, function (i, v) {


                    var viewBL3 = (parseFloat(v['BL_'+Y3].RataRata)>0)
                        ? '<a href="javascript:void(0)" data-token="'+jwt_encode(v['BL_'+Y3],'UAP)(*')+'" class="showDetailData">'+v['BL_'+Y3].RataRata.toFixed(2)+'</a>' : 0;
                    var viewBL2 = (parseFloat(v['BL_'+Y2].RataRata)>0)
                        ? '<a href="javascript:void(0)" data-token="'+jwt_encode(v['BL_'+Y2],'UAP)(*')+'" class="showDetailData">'+v['BL_'+Y2].RataRata.toFixed(2)+'</a>' : 0;
                    var viewBL1 = (parseFloat(v['BL_'+Y1].RataRata)>0)
                        ? '<a href="javascript:void(0)" data-token="'+jwt_encode(v['BL_'+Y1],'UAP)(*')+'" class="showDetailData">'+v['BL_'+Y1].RataRata.toFixed(2)+'</a>' : 0;


                    var viewDess = (v.Description!=null && v.Description!='') ? v.Description : v.Name;

                    $('#listData').append('<tr>' +
                        '<td style="border-right: 1px solid #CCCCCC;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+viewDess+'</td>' +
                        '<td>'+viewBL3+'</td>' +
                        '<td>'+viewBL2+'</td>' +
                        '<td>'+viewBL1+'</td>' +
                        '</tr>');
                });
            }

        });

    }



    $(document).on('click','.showDetailData',function () {
       var token = $(this).attr('data-token');
       var d = jwt_decode(token,'UAP)(*');

       console.log(d);

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Announcement</h4>');


        var tr = '';
        if(d.DetailStudent.length>0){
            $.each(d.DetailStudent,function (i,v) {

                var YudisiumDate = (v.YudisiumDate!='' && v.YudisiumDate!=null)
                    ? moment(v.YudisiumDate).format('DD MMM YYYY')
                    : '-';

                var Experience = (v.Experience.length>0)
                    ? moment().month(parseInt(v.Experience[0].StartMonth) - 1).format('MMM')+' '+v.Experience[0].StartYear
                    : '-';

                var LamaWaktuTunggu = (v.LamaWaktuTunggu!='') ? v.LamaWaktuTunggu : '';

                tr = tr+'<tr>' +
                    '<td>'+(i+1)+'</td>' +
                    '<td>'+v.Name+'</td>' +
                    '<td>'+YudisiumDate+'</td>' +
                    '<td>'+Experience+'</td>' +
                    '<td>'+LamaWaktuTunggu+'</td>' +
                    '</tr>';
            });
        }

        $('#GlobalModal .modal-body').html('<table class="table table-striped table-centre">' +
            '    <thead>' +
            '    <tr>' +
            '        <th>No</th>' +
            '        <th>Nama</th>' +
            '        <th>Yudisium</th>' +
            '        <th>Tanggal Pekerjaan Pertama</th>' +
            '        <th>Waktu Tunggu <br/>(Bulan)</th>' +
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
