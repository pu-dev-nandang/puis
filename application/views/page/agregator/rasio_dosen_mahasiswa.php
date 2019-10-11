

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
        <div class="col-md-2 col-md-offset-4">
            <select class="form-control" id="filterSemester"></select>
        </div>
        <div class="col-md-2">
            <select class="form-control" id="filterDosen">
                <option value="0">Semua</option>
                <option value="1">Dosen Tetap (NIDN/NIDK)</option>
                <option value="2">Dosen Tidak Tetap (NUP)</option>
            </select>
        </div>
        <div class="col-md-4">
            <div style="text-align: right;margin-bottom: 20px;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">


            <table class="table dataTable2Excel" data-name="Rasio_Dosen_terhadap_Mahasiswa" id="tableData">
                <thead>
                <tr>
                    <th colspan="5">2019/2020 Ganjil</th>
                </tr>
                <tr>
                    <th style="width: 1%;">No</th>
                    <th>Prodi</th>
                    <th style="width: 15%;">Jumlah Dosen</th>
                    <th style="width: 15%;">Jumlah Mahasiswa</th>
                    <th style="width: 15%;">Jumlah Mahasiswa TA</th>
                </tr>
                </thead>
                <tbody id="listData"></tbody>
            </table>

            <p style="color: orangered;">
                <br/>*) Mahasiswa yang terhitung adalah mahasiswa yang aktif (Tidak termasuk mahasiswa cuti / mangkir)
                <br/>*) Dosen yang terhitung adalah semua dosen kontrak maupun tetap
                <br/>*) Sarjana : Jumlah dosen tetap/ mahasiswa = 20 s/d 30 mahasiswa
                <br/>*) Vokasi : Jumlah dosen tetap/ mahasiswa = 12 s/d 24 mahasiswa
            </p>
        </div>
    </div>
</div>

<script>
    var passToExcel = [];
    $(document).ready(function () {
        loSelectOptionSemester('#filterSemester','');

        var firstLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                loadLecturerCertificate();
                clearInterval(firstLoad);
            }
        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);


    });

    $('#filterSemester,#filterDosen').change(function () {
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){
            loadLecturerCertificate();
        }

    });

    function loadLecturerCertificate() {

        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){

            loading_modal_show();

            // console.log(filterSemester);
            var SemesterID = filterSemester.split('.')[0];
            var Year = filterSemester.split('.')[1];

            var SemesterText = $('#filterSemester option:selected').text();

            var filterDosen = $('#filterDosen').val();

            passToExcel = [];

            var url = base_url_js+'api3/__getRasioDosenMahasiswa?smt='+SemesterID+'&y='+Year+'&s='+filterDosen;
            $.getJSON(url,function (jsonResult) {

                $('#listData').empty();

                if(jsonResult.length>0){

                    var ds = 0;
                    var ds_c = 0;
                    var ds_x = 0;

                    $.each(jsonResult,function (i,v) {

                        var toModal = {
                            Prodi : v.Name,
                            Desc : 'Dosen',
                            Semester : SemesterText,
                            Details : v.Lecturer_Sch_Fix
                        };
                        var tokenLect = jwt_encode(toModal,'UAP)(*');

                        var viewLect = (v.Lecturer_Sch_Fix.length>0)
                            ? '<a href="javascript:void(0);" class="showDetailLect" data-lec="'+tokenLect+'">'+v.Lecturer_Sch_Fix.length+'</a>'
                            : v.Lecturer_Sch_Fix.length;


                        var toModal_mhs = {
                            Prodi : v.Name,
                            Desc : 'Mahasiswa',
                            Semester : SemesterText,
                            Details : v.dataMahasiwa
                        };
                        var tokenMhs = jwt_encode(toModal_mhs,'UAP)(*');

                        var viewMhs = (v.dataMahasiwa.length>0)
                            ? '<a href="javascript:void(0);" class="showDetailMhs" data-mhs="'+tokenMhs+'">'+v.dataMahasiwa.length+'</a>'
                            : v.dataMahasiwa.length;

                        var toModal_mhs_2 = {
                            Prodi : v.Name,
                            Desc : 'Mahasiswa TA',
                            Semester : SemesterText,
                            Details : v.dataMahasiwaTA
                        };
                        var tokenMhs_2 = jwt_encode(toModal_mhs_2,'UAP)(*');

                        var viewMhs_2 = (v.dataMahasiwaTA.length>0)
                            ? '<a href="javascript:void(0);" class="showDetailMhs" data-mhs="'+tokenMhs_2+'">'+v.dataMahasiwaTA.length+'</a>'
                            : v.dataMahasiwaTA.length;



                        $('#listData').append('<tr>' +
                            '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                            '<td style="text-align: left;">'+v.Name+'</td>' +
                            '<td style="border-left: 1px solid #ccc;">'+viewLect+'</td>' +
                            '<td style="border-left: 1px solid #ccc;">'+viewMhs+'</td>' +
                            '<td style="border-left: 1px solid #ccc;">'+viewMhs_2+'</td>' +
                            '</tr>');

                        ds = ds + parseInt(v.Lecturer_Sch_Fix.length);
                        ds_c = ds_c + parseInt(v.dataMahasiwa.length);
                        ds_x = ds_x + parseInt(v.dataMahasiwaTA.length);
                    });

                    $('#listData').append('<tr>' +
                        '<th colspan="2">Jumlah</th>' +
                        '<th>'+ds+'</th>' +
                        '<th>'+ds_c+'</th>' +
                        '<th>'+ds_x+'</th>' +
                        '</tr>');


                    passToExcel = jsonResult;
                }

                loading_modal_hide();

            });

        }

    }

    $(document).on('click','.showDetailLect',function () {
        var  tokenLect = $(this).attr('data-lec');
        var d = jwt_decode(tokenLect,'UAP)(*');



        var tr = '';
        if(d.Details.length>0){
            $.each(d.Details,function (i,v) {

                var NID = (v.NIDN!='' && v.NIDN!=null && v.NIDN!=0 && v.NIDN!='0') ? v.NIDN : '-';
                NID = (v.NIDK!='' && v.NIDK!=null && v.NIDK!=0 && v.NIDK!='0') ? v.NIDK : NID;

                var NUP = (v.NUP!='' && v.NUP!=null && v.NUP!=0 && v.NUP!='0') ? v.NUP : '-';

                var StatusForlap = '<label style="color: red;">Belum di set</label>';
                if(parseInt(v.StatusForlap)==1){
                    StatusForlap = 'NIDN';
                } else if(parseInt(v.StatusForlap)==2){
                    StatusForlap = 'NIDK';
                } else if(parseInt(v.StatusForlap)==0){
                    StatusForlap = 'NUP';
                }

                tr = tr+'<tr>' +
                    '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                    '<td>'+NID+'</td>' +
                    '<td>'+NUP+'</td>' +
                    '<td style="text-align: left;">'+v.Name+'</td>' +
                    '<td style="width: 1%;">'+StatusForlap+'</td>' +
                    '</tr>';
            });
        }


        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+d.Prodi+' - '+d.Desc+' - '+d.Semester+'</h4>');
        $('#GlobalModal .modal-body').html('<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table table-striped" id="tableLect" style="margin-bottom: 0px;">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 5%;">No</th>' +
            '                <th style="width: 25%;">NIDN / NIDK</th>' +
            '                <th style="width: 25%;">NUP</th>' +
            '                <th>Name</th>' +
            '                <th>Status Forlap</th>' +
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

    $(document).on('click','.showDetailMhs',function () {
        var  tokenMhs = $(this).attr('data-mhs');
        var d = jwt_decode(tokenMhs,'UAP)(*');



        var tr = '';
        if(d.Details.length>0){
            $.each(d.Details,function (i,v) {

                var Status = (v.Description!=null && v.Description!='') ? ucwords(v.Description) : '-';
                tr = tr+'<tr>' +
                    '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                    '<td>'+v.NPM+'</td>' +
                    '<td style="text-align: left;">'+ucwords(v.Name)+'</td>' +
                    '<td>'+Status+'</td>' +
                    '</tr>';
            });
        }


        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+d.Prodi+' - '+d.Desc+' - '+d.Semester+'</h4>');
        $('#GlobalModal .modal-body').html('<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table table-striped" id="tableLect" style="margin-bottom: 0px;">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 5%;">No</th>' +
            '                <th style="width: 25%;">NIM</th>' +
            '                <th>Name</th>' +
            '                <th style="width: 20%;">Status</th>' +
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
