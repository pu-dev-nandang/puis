

<style>
    #dataTable tr th, #dataTable tr td {
        text-align: center;
    }

    #tableLect tr th, #tableLect tr td {
        text-align: center;
    }
</style>

<div class="well">
    <div class="row">
        <div class="col-md-12">
            <div style="text-align: right"> <b>Download File : </b><button class="btn btn-success btn-circle" id="btndownloaadExcel" title="Dowload Excel"><i class="fa fa-file-excel-o"></i> </button></div>
            <table class="table" id="dataTable">
                <thead>
                <tr>
                    <th style="width: 1%;" rowspan="2">No</th>
                    <th rowspan="2">Pendidikan</th>
                    <th colspan="4">Jabatan Akademik</th>
                    <th style="width: 10%;" rowspan="2">Tenaga Pengajar</th>
                    <th style="width: 10%;" rowspan="2">Jumlah</th>
                </tr>
                <tr>
                    <th style="width: 10%;">Asisten Ahli</th>
                    <th style="width: 10%;">Lektor</th>
                    <th style="width: 10%;">Lektor Kepala</th>
                    <th style="width: 10%;">Guru Besar</th>
                </tr>
                </thead>
                <tbody id="listData"></tbody>
            </table>
            <p style="color: orangered;">*) Dosen yang terhitung adalah dosen mempunyai (NUP)</p>
        </div>
    </div>
</div>

<script>
    var passToExcel = [];
    $(document).ready(function () {
        loadData();
    });

    function loadData() {
        passToExcel = [];
        var url = base_url_js+'api3/__getJabatanAkademikDosenTidakTetap';
        $.getJSON(url,function (jsonResult) {

            $('#listData').empty();
            if(jsonResult.length>0){

                $.each(jsonResult,function (i,v) {

                    var td = '';
                    var total = 0;
                    $.each(v.details,function (i2, v2) {

                        var toModal = {
                            Level : v.Description,
                            Position : v2.Position,
                            Details : v2.dataEmployees
                        };
                        var tokenLect = jwt_encode(toModal,'UAP)(*');
                        var viewLect = (v2.dataEmployees.length>0) ? '<a href="javascript:void(0);" class="showDetailLect" data-lec="'+tokenLect+'">'+v2.dataEmployees.length+'</a>' : v2.dataEmployees.length;

                        td = td+'<td>'+viewLect+'</td>';
                        total = total + parseInt(v2.dataEmployees.length);
                    });

                    $('#listData').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.Level+' - '+v.Description+'</td> '+td+
                        '<td style="text-align: center;background: lightyellow;border-left: 1px solid #ccc;">'+total+'</td>' +
                        '</tr>');

                });

                passToExcel = jsonResult
            }

        });
    }


    $(document).off('click', '#btndownloaadExcel').on('click', '#btndownloaadExcel',function(e) {
        if (passToExcel.length > 0) {
            var url = base_url_js+'agregator/excel-dosen-tidak-tetap';
            data = {
              passToExcel : passToExcel,
            }
            var token = jwt_encode(data,"UAP)(*");
            FormSubmitAuto(url, 'POST', [
                { name: 'token', value: token },
            ]);
        }

    });


    $(document).on('click','.showDetailLect',function () {
        var  tokenLect = $(this).attr('data-lec');
        var d = jwt_decode(tokenLect,'UAP)(*');



        var tr = '';
        if(d.Details.length>0){
            $.each(d.Details,function (i,v) {

                var NID = (v.NUP!='' && v.NUP!=null && v.NUP!=0 && v.NUP!='0') ? v.NUP : '-';

                tr = tr+'<tr>' +
                    '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                    '<td>'+NID+'</td>' +
                    '<td style="text-align: left;">'+v.Name+'</td>' +
                    '</tr>';
            });
        }


        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+d.Level+' - '+d.Position+'</h4>');
        $('#GlobalModal .modal-body').html('<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table table-striped" id="tableLect" style="margin-bottom: 0px;">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 5%;">No</th>' +
            '                <th style="width: 25%;">NUP</th>' +
            '                <th>Name</th>' +
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