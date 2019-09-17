

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
            <div style="text-align: right;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <table class="table table-bordered table-striped dataTable2Excel" id="dataTable" data-name="Jabatan_dosen_tetap">
                <thead>
                <tr style="background: #20485A;color: #FFFFFF;">
                    <th style="width: 1%;" rowspan="2">No</th>
                    <th rowspan="2">Pendidikan</th>
                    <th colspan="4">Jabatan Akademik</th>
                    <th style="width: 10%;" rowspan="2">Tenaga Pengajar</th>
                    <th style="width: 10%;" rowspan="2">Jumlah</th>
                </tr>
                <tr style="background: #20485A;color: #FFFFFF;">
                    <th style="width: 10%;">Asisten Ahli</th>
                    <th style="width: 10%;">Lektor</th>
                    <th style="width: 10%;">Lektor Kepala</th>
                    <th style="width: 10%;">Guru Besar</th>
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
    });

    function loadData() {
        var url = base_url_js+'api3/__getJabatanAkademikDosenTetap';
        $.getJSON(url,function (jsonResult) {

            $('#listData').empty();
            if(jsonResult.length>0){
                var AA = 0;
                var L = 0;
                var LK = 0;
                var GB = 0;
                var TP = 0;
                var J = 0;

                $.each(jsonResult,function (i,v) {

                    var td = '';
                    var total = 0;
                    $.each(v.details,function (i2, v2) {

                      var det = v2.dataEmployees.length; 
                        var toModal = {
                            Position : v2.Position,
                            Level : v.Description,
                            Details : v2.dataEmployees
                        };
                        var tokenLect = jwt_encode(toModal,'UAP)(*');

                        var viewLect = (v2.dataEmployees.length>0) ? '<a href="javascript:void(0);" class="showDetailLect" data-lec="'+tokenLect+'">'+v2.dataEmployees.length+'</a>' : v2.dataEmployees.length;

                       td = td+'<td>'+viewLect+'</td>';

                        total = total + parseInt(v2.dataEmployees.length);
                        
                        if(i2==0){
                            TP = TP+det; //tenaga pengajar
                        }
                        else if(i2==1){ // Asisten Ahli
                            AA = AA+det;
                        }
                        else if(i2==2){ // lektor
                            L = L + det;
                        }
                        else if(i2==3){ // lektor kepala
                            LK = LK+det;
                        }
                        else if(i2==4){ // Guru besar
                            GB = GB+det;
                        }

                    });

                    $('#listData').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.Level+' - '+v.Description+'</td> '+td+

                        '<td style="text-align: center;background: lightyellow;border-left: 1px solid #ccc;">'+total+'</td>' +
                        '</tr>');

                });

                $('#listData').append('<tr>' +
                    '<th colspan="2" class="tdJml">Jumlah</th>' +
                    '<th class="tdJml">'+TP+'</th>' +
                    '<th class="tdJml">'+AA+'</th>' +
                     '<th class="tdJml">'+L+'</th>' +
                    '<th class="tdJml">'+J+'</th>' +
                    '<th class="tdJml">'+GB+'</th>' +
                    '<th class="tdJml">'+LK+'</th>' +
                    
                   
                    
                                       '</tr>');

            }

        });
    }


    $(document).on('click','.showDetailLect',function () {
        var  tokenLect = $(this).attr('data-lec');
        var d = jwt_decode(tokenLect,'UAP)(*');



        var tr = '';
        if(d.Details.length>0){
            $.each(d.Details,function (i,v) {

                var NID = (v.NIDN!='' && v.NIDN!=null && v.NIDN!=0 && v.NIDN!='0') ? v.NIDN : '-';
                NID = (v.NIDK!='' && v.NIDK!=null && v.NIDK!=0 && v.NIDK!='0') ? v.NIDK : NID;

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
            '                <th style="width: 25%;">NIDN / NIDK</th>' +
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