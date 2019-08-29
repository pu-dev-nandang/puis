

<style>
    #tableActF tr th, #tableActF tr td {
        text-align: center;
    }

    #tableActF td:nth-child(2){
        text-align: left;
    }

    .imgPoster {
        margin-top: 15px;
        width: 100%;
        border-radius: 15px;
    }

    #AbstrakInd,#AbstrakEng{
        overflow: auto;
        max-height: 550px;
    }
</style>


<div class="row">
    <div class="col-md-12" style="margin-bottom: 15px;">
        <a href="<?= base_url('library/yudisium/final-project'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left margin-right"></i> Back to list</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title" id="viewStd"></h4>
            </div>
            <div class="panel-body" style="min-height: 100px;">
                <table class="table" id="tableActF">
                    <thead>
                    <tr>
                        <th style="width: 1%;">No</th>
                        <th>Description</th>
                        <th style="width: 5%;"><i class="fa fa-cog"></i></th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>Cover</td>
                        <td id="Cover">-</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Surat Bebas Plagiat</td>
                        <td id="SuratBebasPlagiat">-</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Halaman Pengesahan</td>
                        <td id="HalamanPengesahan">-</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Kata Pengantar</td>
                        <td id="KataPengantar">-</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Halaman Pernyataan Persetujuan Publikasi</td>
                        <td id="PersetujuanPublikasi">-</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Abstrak</td>
                        <td id="Abstrak">-</td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>Daftar Isi</td>
                        <td id="DaftarIsi">-</td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Daftar Gambar</td>
                        <td id="DaftarGambar">-</td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>Daftar Tabel</td>
                        <td id="DaftarTabel">-</td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>Daftar Lampiran</td>
                        <td id="DaftrLampiran">-</td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td>BAB I</td>
                        <td id="BAB1">-</td>
                    </tr>
                    <tr>
                        <td>12</td>
                        <td>BAB II</td>
                        <td id="BAB2">-</td>
                    </tr>
                    <tr>
                        <td>13</td>
                        <td>BAB III</td>
                        <td id="BAB3">-</td>
                    </tr>
                    <tr>
                        <td>14</td>
                        <td>BAB IV</td>
                        <td id="BAB4">-</td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td>BAB V</td>
                        <td id="BAB5">-</td>
                    </tr>
                    <tr>
                        <td>16</td>
                        <td>Daftar Pustaka</td>
                        <td id="DaftarPustaka">-</td>
                    </tr>
                    <tr>
                        <td>17</td>
                        <td>Lampiran</td>
                        <td id="Lampiran">-</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">View <span id="viewDoc">-</span></h4>
            </div>
            <div class="panel-body" style="min-height: 100px;" id="panelShowDoc">
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-4">
        <div id="viewPoster"></div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
<!--            <div class="panel-heading">-->
<!--                <h4 class="panel-title">Skripsi</h4>-->
<!--            </div>-->
            <div class="panel-body" style="min-height: 100px;">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width: 10%;">Description</th>
                        <th style="width: 45%;">Indonesian</th>
                        <th style="width: 45%;">English</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Judul</td>
                        <td id="JudulInd"></td>
                        <td id="JudulEng"></td>
                    </tr>
                    <tr>
                        <td>Abstrak</td>
                        <td><div id="AbstrakInd"></div></td>
                        <td><div id="AbstrakEng"></div></td>
                    </tr>
                    <tr>
                        <td>Kata Kunci</td>
                        <td id="KataKunciInd"></td>
                        <td id="KataKunciEng"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr/>
        <div id="viewNoted">
            <textarea class="form-control" rows="3" id="formNoted" placeholder="Give notes (required)"></textarea>
        </div>
        <div id="divBtnAct" style="text-align: right;margin-top: 15px;">
            <button class="btn btn-lg btn-success btnAct" data-status="2">Approve</button> |
            <button class="btn btn-lg btn-danger btnAct" data-status="-2">Reject</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        getDataFinalProject();
    });

    function getDataFinalProject() {

        var NPM = "<?= $NPM; ?>";

        var data = {
            action : 'viewDetailsFileFinalProject',
            NPM : NPM
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+"api3/__crudFileFinalProject";

        $.post(url,{token:token},function (jsonReult) {
            if(jsonReult.length>0){
                var d = jsonReult[0];

                var keyNames = Object.keys(d);


                if(keyNames.length>0){

                    var path = base_url_js+'uploads/document/'+NPM+'/';

                    for(var i=0;i<keyNames.length;i++){

                        if( (i>1 && i<19) || (i==25) ){

                            var Files = (d[keyNames[i]]!='' && d[keyNames[i]]!=null)
                                ? '<button class="btn btn-sm btn-default showDoc" data-name="'+keyNames[i]+'" data-file="'+path+'/'+d[keyNames[i]]+'">' +
                                '<i class="fa fa-external-link-square"></i></button>'
                                : '-';
                            $('#'+keyNames[i]).html(Files);

                            if(i==25){
                                var FileImage = (d[keyNames[i]]!='' && d[keyNames[i]]!=null) ? '<div style="text-align: center;padding: 15px;" class="thumbnail"><h4>Poster</h4><hr/>' +
                                    '<a href="'+path+'/'+d[keyNames[i]]+'" target="_blank" class="btn btn-default">Download Poster</a>' +
                                    '<img class="imgPoster" src="'+path+'/'+d[keyNames[i]]+'" /></div>' : '';
                                $('#viewPoster').html(FileImage);
                            }
                        } else if(i>=19 && i<=24){
                            var ck =  (d[keyNames[i]]!='' && d[keyNames[i]]!=null) ? d[keyNames[i]] : '-';
                            $('#'+keyNames[i]).html(ck);
                        }

                    }
                }

                $('#viewStd').html(d.NPM+' - '+d.Name);



                if(parseInt(d.Status)!=1){
                    $('#viewNoted').html('<h4>*) Notes : '+d.Noted+'</h4>');
                    $('#divBtnAct').remove();
                }

            }
        });

    }

    $(document).on('click','.showDoc',function () {
        var file = $(this).attr('data-file');
        var n = $(this).attr('data-name');

        $('#viewDoc').html(' - '+n);
        $('#panelShowDoc').html('<iframe src="'+file+'" style="width: 100%;height: 700px;"></iframe>');

        $('#tableActF tr').css('background','#ffffff');

        $(this).parent().parent().css('background','#fff3cf');

    });

    $('.btnAct').click(function () {

        var formNoted = $('#formNoted').val();

        if(formNoted!='' && formNoted!=null){
            var status = $(this).attr('data-status');

            var s = (status==2 || status=='2') ? 'Approved' : 'Rejected';

            if(confirm('Are you sure to '+s)){

                var NPM = "<?= $NPM; ?>";

                var data = {
                    action : 'updateFileFinalProject',
                    NPM : NPM,
                    dataform : {
                        Noted : formNoted,
                        Status : status
                    }
                };
                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+"api3/__crudFileFinalProject";

                $.post(url,{token:token},function (result) {
                   toastr.success('Data submitted','Success');
                   setTimeout(function () {
                       window.location.href = '';
                   },500);
                });

            }
        } else {
            toastr.warning('Give notes is required','Warning');
            $('#formNoted').css('border','1px solid red');
            setTimeout(function (args) {
                $('#formNoted').css('border','1px solid #ccc');
            },5000);
        }




    });

</script>