


<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="well">
            <select class="form-control" id="filterYear"></select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="">
            <table class="table table-responsive table-bordered table-centre">

                <thead>
                <tr>
                    <th rowspan="2" style="width: 1%;">No</th>
                    <th rowspan="2">Program Pendidikan</th>
                    <th colspan="3">Banyaknya Lulusan</th>
                    <th colspan="3">Banyaknya Lulusan yang memberikan jawaban</th>
                </tr>
                <tr>
                    <th style="width: 10%;">TS <span class="viewTH3"></span></th>
                    <th style="width: 10%;">TS <span class="viewTH2"></span></th>
                    <th style="width: 10%;">TS <span class="viewTH1"></span></th>

                    <th style="width: 10%;">TS <span class="viewTH3"></span></th>
                    <th style="width: 10%;">TS <span class="viewTH2"></span></th>
                    <th style="width: 10%;">TS <span class="viewTH1"></span></th>
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

        var firstLoad = setInterval(function (args) {
            var filterYear = $('#filterYear').val();
            if(filterYear!='' && filterYear!=null){
                loadData();
                clearInterval(firstLoad);
            }
        },1000);


    });
    
    function loadData() {

        var filterYear = $('#filterYear').val();
        if(filterYear!='' && filterYear!=null){

            var Year = filterYear.split('.')[1];
            var url = base_url_js+'api3/__crudAgregatorTB5';
            var data = {
                action : 'readTableRef',
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

                if(jsonResult.length>0){

                    $.each(jsonResult,function (i,v) {

                        var viewDess = (v.Description!=null && v.Description!='') ? v.Description : v.Name;


                        var ket = 'Banyaknya Lulusan - ';
                        var viewBL3 = (v['BL_'+Y3].length > 0)
                            ? '<a href="javascript:void(0);" class="btnShowData" data-ket="'+ket+' '+Y3+'" data-token="'+(jwt_encode(v['BL_'+Y3],'UAP)(*'))+'">'+(v['BL_'+Y3].length)+'</a>'
                            : 0;
                        var viewBL2 = (v['BL_'+Y2].length > 0)
                            ? '<a href="javascript:void(0);" class="btnShowData" data-ket="'+ket+' '+Y2+'" data-token="'+(jwt_encode(v['BL_'+Y2],'UAP)(*'))+'">'+(v['BL_'+Y2].length)+'</a>'
                            : 0;
                        var viewBL1 = (v['BL_'+Y1].length > 0)
                            ? '<a href="javascript:void(0);" class="btnShowData" data-ket="'+ket+' '+Y1+'" data-token="'+(jwt_encode(v['BL_'+Y1],'UAP)(*'))+'">'+(v['BL_'+Y1].length)+'</a>'
                            : 0;

                        var ket = 'Banyaknya Lulusan yang memberikan jawaban - ';
                        var viewBJ3 = (v['BJ_'+Y3].length > 0)
                            ? '<a href="javascript:void(0);" class="btnShowData" data-ket="'+ket+' '+Y2+'" data-token="'+(jwt_encode(v['BJ_'+Y3],'UAP)(*'))+'">'+(v['BJ_'+Y3].length)+'</a>'
                            : 0;
                        var viewBJ2 = (v['BJ_'+Y2].length > 0)
                            ? '<a href="javascript:void(0);" class="btnShowData" data-ket="'+ket+' '+Y2+'" data-token="'+(jwt_encode(v['BJ_'+Y2],'UAP)(*'))+'">'+(v['BJ_'+Y2].length)+'</a>'
                            : 0;
                        var viewBJ1 = (v['BJ_'+Y1].length > 0)
                            ? '<a href="javascript:void(0);" class="btnShowData" data-ket="'+ket+' '+Y2+'" data-token="'+(jwt_encode(v['BJ_'+Y1],'UAP)(*'))+'">'+(v['BJ_'+Y1].length)+'</a>'
                            : 0;

                        $('#listData').append('<tr>' +
                            '<td>'+(i+1)+'</td>' +
                            '<td style="text-align: left;">'+viewDess+'</td>' +
                            '<td>'+viewBL3+'</td>' +
                            '<td>'+viewBL2+'</td>' +
                            '<td>'+viewBL1+'</td>' +

                            '<td>'+viewBJ3+'</td>' +
                            '<td>'+viewBJ2+'</td>' +
                            '<td>'+viewBJ1+'</td>' +
                            '</tr>');

                    });

                }

            });

        }




    }

    $('#filterYear').change(function () {
        var filterYear = $('#filterYear').val();
        if(filterYear!='' && filterYear!=null){
            loadData();
        }
    });

    $(document).on('click','.btnShowData',function () {
       var ket = $(this).attr('data-ket');
       var token = $(this).attr('data-token');
       var dataToken = jwt_decode(token,'UAP)(*');

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+ket+'</h4>');


        if(dataToken.length>0){
            var tr = '';
            $.each(dataToken,function (i,v) {
                tr = tr+'<tr>' +
                    '<td style="border-right: 1px solid #CCCCCC;">'+(i+1)+'</td>' +
                    '<td>'+v.NPM+'</td>' +
                    '<td style="text-align: left;">'+v.Name+'</td>' +
                    '<td style="text-align: left;">'+v.Prodi+'</td>' +
                    '</tr>';
            })
        }

        $('#GlobalModal .modal-body').html('<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table table-striped table-centre">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th style="width: 15%;">NIM</th>' +
            '                <th style="width: 35%;">Name</th>' +
            '                <th>Prodi</th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody>'+tr+'</tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>');

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });
</script>