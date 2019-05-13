<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>IT Page</title>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<style>
    #tb tr th, #tb tr td {
        text-align: center;
    }
</style>

<?php $Student = $DataOk['Student'];  ?>


<div class="container-fluid" style="margin-top: 30px;">

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="well" style="text-align: center;">
                <h3 style="margin-top: 10px;">Kurikulum <?= $DataOk['TA'];?>, terdapat Kesalahan <?= $DataOk['Kesalahan']; ?> data</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped" id="tb">
                <thead>
                <tr>
                    <th style="width: 3%;border-right: 1px solid #CCCCCC">No</th>
                    <th style="width: 15%;">Student</th>
                    <th style="width: 5%;">Curriculum</th>
                    <th style="width: 10%;">Prodi</th>
                    <th style="width: 5%;">Smt</th>
                    <th style="border-right: 1px solid #CCCCCC">Course</th>
                    <th style="width: 5%;">Curriculum</th>
                    <th style="width: 10%;">Prodi</th>
                    <th style="width: 5%;">Smt</th>
                    <th>Course</th>
                </tr>
                </thead>
                <?php

                $arrData = [];
                $no =1;
                foreach ($Student AS $item){

                    if(count($item['Bener'])==0){
                        $dg = 'style="background:#ffe7e7;color:red;"';
                        $tdlu = '<td colspan="4" style="text-align: left;font-weight: bold;">MATA KULIAH INI BELUM DI TAMBAHKAN PADA KURIKULUM INI</td>';
                    } else if(count($item['Bener'])>1){
                        $dg = 'style="background:#dffbff;color:blue;"';
                        $tdlu = '<td colspan="4" style="text-align: left;font-weight: bold;">MATA KULIAH YG TAMBAHKAN DI KURIKULUM LEBIH DARI 1</td>';
                    } else if (count($item['Bener'])==1) {
                        $dtB = $item['Bener'][0];
                        $dg = '';
                        $tdlu = '<td>'.$dtB['Year'].'</td>
                                <td style="text-align: left;">'.$dtB['ProdiName'].'</td>
                                <td>'.$dtB['Semester'].'</td>
                                <td style="text-align: left;">'.$dtB['MKCode'].' - '.$dtB['NameEng'].'</td>';

                        $arr = array(
                                'SPID' => $item['SPID'],
                                'CDID' => $dtB['CDID'],
                                'MKID' => $dtB['MKID']
                        );

                        array_push($arrData,$arr);
                    }
                    ?>
                    <tr <?=$dg;?>>
                        <td style="border-right: 1px solid #CCCCCC""><?=$no;?></td>
                        <td style="text-align: left;"><?php echo "<b>".$item['Name']."</b><br/>".$item['NPM'];; ?></td>
                        <td style="text-align: left;"><?=$item['Year'];?></td>
                        <td style="text-align: left;"><?=$item['ProdiName'];?></td>
                        <td><?=$item['Semester'];?></td>
                        <td style="text-align: left;border-right: 1px solid #CCCCCC"><?=$item['MKCode'];?> - <?=$item['NameEng'];?></td>
                        <?= $tdlu; ?>
                    </tr>
                    <?php $no++; } ?>
            </table>
            <div style="text-align: right;">
                <button class="btn btn-lg btn-success" id="btnPerbaiki">Perbaiki <span class="badge"><?= count($arrData); ?> data</span></button>
                <hr/>
            </div>
            <?php $toUpdate = array('ta' => $DataOk['TA'],'arrData' => $arrData); ?>
            <textarea id="totalStd" class="hide"><?php echo json_encode($toUpdate);?></textarea>
        </div>
    </div>


</div>



<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script type="text/javascript" src="<?php echo base_url('assets/template/js/libs/jquery-1.10.2.min.js'); ?>"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script>
        $('#btnPerbaiki').click(function () {
            if(confirm('Apakah anda yakin?')){
                var totalStd = $('#totalStd').val();
                if(totalStd!=''){

                    $('#btnPerbaiki').prop('disabled',true).html('Loading...');


                    var dt = JSON.parse(totalStd);

                    var url = '<?= base_url(); ?>api2/_updateCurriculum';
                    $.post(url,{dataForm:dt},function (result) {
                        setTimeout(function (args) {
                            window.location.href='';
                        },500);
                    })

                }
            }
        });
    </script>


</body>
</html>