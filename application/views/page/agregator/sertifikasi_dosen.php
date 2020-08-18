

<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }

    #tableLect tr th, #tableLect tr td {
        text-align: center;
    }
</style>

<div class="well">




    <div class="row">
        <div class="col-md-12">
            <div style="text-align: right;margin-bottom: 20px;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <table class="table table-striped dataTable2Excel" id="tableData" data-name="setifikasi_dosen">
                <thead>
                <tr>
                    <th style="width: 1%;" rowspan="2">No</th>
                    <th rowspan="2">Unit Pengelola (Fakultas/Departemen/Jurusan)</th>
                    <th style="width: 15%;" rowspan="2">Jumlah Dosen</th>
                    <th style="width: 15%;" rowspan="2">Jumlah Dosen Bersertifikat **)</th>
                    <th style="width: 15%;" colspan="4">Sertifikat</th>
                </tr>
                <tr>
                    <th>Profesional</th>
                    <th>Profesi</th>
                    <th>Industri</th>
                    <th>Kompetensi</th>
                </tr>
                </thead>
                <tbody id="listData"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadLecturerCertificate();
    });


    function loadLecturerCertificate() {


        var url = base_url_js+'api3/__getLecturerCertificate';
        $.getJSON(url,function (jsonResult) {

            $('#listData').empty();

            if(jsonResult.length>0){

                var ds = 0;
                var ds_c = 0;

                let totCertificate_Profesional = 0;
                let totCertificate_Profesi = 0;
                let totCertificate_Kompetensi = 0;
                let totCertificate_Industri = 0;

                $.each(jsonResult,function (i,v) {


                    var toModal = {
                        Prodi : v.Name,
                        Desc : 'Dosen',
                        Details : v.TotalLecturer
                    };
                    var tokenLect = jwt_encode(toModal,'UAP)(*');

                    var viewLect = (v.TotalLecturer.length>0) ? '<a href="javascript:void(0);" class="showDetailLect" data-lec="'+tokenLect+'">'+v.TotalLecturer.length+'</a>' : v.TotalLecturer.length;


                    var toModal_2 = {
                        Prodi : v.Name,
                        Desc : 'Dosen Bersertifikat',
                        Details : v.TotalLecturerCertifies
                    };
                    var tokenLect_2 = jwt_encode(toModal_2,'UAP)(*');
                    var viewLectCerd = (v.TotalLecturerCertifies.length>0) ? '<a href="javascript:void(0);" class="showDetailLect" data-lec="'+tokenLect_2+'">'+v.TotalLecturerCertifies.length+'</a>' : v.TotalLecturerCertifies.length;
                    
                    var tokenCertificate_Profesional = jwt_encode(v.Certificate_Profesional,'UAP)(*');
                    var viewCertificate_Profesional = (v.Certificate_Profesional.length>0) ? '<a href="javascript:void(0);" class="showDetailSertifikat" data-lec="'+tokenCertificate_Profesional+'">'+v.Certificate_Profesional.length+'</a>' : v.Certificate_Profesional.length;

                    var tokenCertificate_Profesi = jwt_encode(v.Certificate_Profesi,'UAP)(*');
                    var viewCertificate_Profesi = (v.Certificate_Profesi.length>0) ? '<a href="javascript:void(0);" class="showDetailSertifikat" data-lec="'+tokenCertificate_Profesi+'">'+v.Certificate_Profesi.length+'</a>' : v.Certificate_Profesi.length;

                    var tokenCertificate_Kompetensi = jwt_encode(v.Certificate_Kompetensi,'UAP)(*');
                    var viewCertificate_Kompetensi = (v.Certificate_Kompetensi.length>0) ? '<a href="javascript:void(0);" class="showDetailSertifikat" data-lec="'+tokenCertificate_Kompetensi+'">'+v.Certificate_Kompetensi.length+'</a>' : v.Certificate_Kompetensi.length;

                    var tokenCertificate_Industri = jwt_encode(v.Certificate_Industri,'UAP)(*');
                    var viewCertificate_Industri = (v.Certificate_Industri.length>0) ? '<a href="javascript:void(0);" class="showDetailSertifikat" data-lec="'+tokenCertificate_Industri+'">'+v.Certificate_Industri.length+'</a>' : v.Certificate_Industri.length;

                    $('#listData').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.Name+'</td>' +
                        '<td style="border-left: 1px solid #ccc;">'+viewLect+'</td>' +
                        '<td style="border-left: 1px solid #ccc;">'+viewLectCerd+'</td>' +
                        '<td style="border-left: 1px solid #ccc;">'+viewCertificate_Profesional+'</td>' +
                        '<td style="border-left: 1px solid #ccc;">'+viewCertificate_Profesi+'</td>' +
                        '<td style="border-left: 1px solid #ccc;">'+viewCertificate_Kompetensi+'</td>' +
                        '<td style="border-left: 1px solid #ccc;">'+viewCertificate_Industri+'</td>' +
                        '</tr>');

                    ds = ds + parseInt(v.TotalLecturer.length);
                    ds_c = ds_c + parseInt(v.TotalLecturerCertifies.length);

                    totCertificate_Profesional += parseInt(v.Certificate_Profesional.length);
                    totCertificate_Profesi += parseInt(v.Certificate_Profesi.length);
                    totCertificate_Kompetensi += parseInt(v.Certificate_Kompetensi.length);
                    totCertificate_Industri += parseInt(v.Certificate_Industri.length);

                });

                $('#listData').append('<tr>' +
                    '<th colspan="2">Jumlah</th>' +
                    '<th>'+ds+'</th>' +
                    '<th>'+ds_c+'</th>' +
                    '<th>'+totCertificate_Profesional+'</th>' +
                    '<th>'+totCertificate_Profesi+'</th>' +
                    '<th>'+totCertificate_Kompetensi+'</th>' +
                    '<th>'+totCertificate_Industri+'</th>' +
                    '</tr>')
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
            '<h4 class="modal-title">'+d.Prodi+' - '+d.Desc+'</h4>');
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

    $(document).on('click','.showDetailSertifikat',function () {
        var  tokenLect = $(this).attr('data-lec');
        var d = jwt_decode(tokenLect,'UAP)(*');

        let tr  = '';

        for (var i = 0; i < d.length; i++) {
            const r  = d[i];
            tr += '<tr>'+
                        '<td>'+(i+1)+'</td>'+
                        '<td>'+r.NIDN+'</td>'+
                        '<td>'+r.NameDosen+'</td>'+
                        '<td>'+((r.PublicationYear!='' && r.PublicationYear!=null)
                        ? moment(r.PublicationYear).format('DD MMM YYYY') : '')+'</td>'+
                        '<td>'+((r.Duedate!='' && r.Duedate!=null)
                        ? moment(r.Duedate).format('DD MMM YYYY') : '')+'</td>'+
                        '<td>'+((r.File!='' && r.File!=null) ? '<a class="btn btn-sm btn-default" target="_blank" href="'+base_url_js+'uploads/certificate/'+r.File+'">Download</a>' : '')+'</td>'+
                  '</tr>';
        }

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+d.Prodi+' - '+d.Desc+'</h4>');
        $('#GlobalModal .modal-body').html('<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table table-striped" id="tableLect" style="margin-bottom: 0px;">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 5%;">No</th>' +
            '                <th style="width: 25%;">NIDN</th>' +
            '                <th>Name</th>' +
            '                <th>Publication Year</th>' +
            '                <th>Due Date</th>' +
            '                <th>File</th>' +
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
