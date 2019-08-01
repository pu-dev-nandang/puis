
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

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

<style>
    .thumbnail {
        min-height: 100px;
        padding: 15px;
    }
</style>

<body>

<?php
//
//print_r($SP);
//
//?>

<div class="row" style="margin-top: 50px;">
    <div class="col-md-4 col-md-offset-4">
        <div class="well">
            <div class="row">

                <div class="col-md-8">
                    <select class="form-control" id="filterTahun">
                        <option>2019</option>
                        <option>2018</option>
                        <option>2017</option>
                        <option>2016</option>
                        <option>2015</option>
                        <option>2014</option>
                    </select>
                </div>
<!--                <div class="col-md-4">-->
<!--                    <select class="form-control">-->
<!--                        <option value="1">Arsitektur</option>-->
<!--                        <option value="2">Manajemen Rekayasa dan Konstruksi</option>-->
<!--                        <option value="3">Kewirausahaan</option>-->
<!--                        <option value="4">Akuntansi</option>-->
<!--                        <option value="5">Bisnis Perhotelan</option>-->
<!--                        <option value="6">Hukum Bisnis</option>-->
<!--                        <option value="8">Teknik Konstruksi Bangunan</option>-->
<!--                        <option value="9">Perencanaan Wilayah dan Kota</option>-->
<!--                        <option value="10">Teknik Lingkungan</option>-->
<!--                        <option value="11">Desain Produk</option>-->
<!--                    </select>-->
<!--                </div>-->
                <div class="col-md-4">
                    <button id="btnSubmit" class="btn btn-block btn-success">Submit</button>
                </div>


            </div>
        </div>
        <hr/>
    </div>
</div>

<?php foreach ($SP AS $item){

    $krs_A = $item['A'];
    $krs_B = $item['B'];

    if(count($krs_A)!=count($krs_B)){



    ?>

    <div class="row">
        <div class="container-fluid">
            <div class="col-md-6">
                <div class="thumbnail">
                    <?= $item['Name'].'<br/>'.$item['NPM']; ?>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;">No</th>
                            <th>ScheduleID</th>
                            <th>Group</th>
                            <th style="width: 5%;">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                         $no = 0;
                        foreach ($krs_A AS $item2){ ?>
                            <tr>
                                <td style="border-right: 1px solid #CCCCCC;"><?= $no+=1; ?></td>
                                <td><?= $item2['ScheduleID']; ?></td>
                                <td><?= $item2['ClassGroup']; ?></td>
                                <td><button class="btn btn-sm btn-danger">Remove</button></td>
                            </tr>
                        <?php }

                        ?>
                        </tbody>
                    </table>
                </div>
                <hr/>
            </div>
            <div class="col-md-6">
                <div class="thumbnail">
                    <?= $item['Name'].'<br/>'.$item['NPM']; ?>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;">No</th>
                            <th>ScheduleID</th>
                            <th>Group</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                         $no = 0;
                        foreach ($krs_B AS $item2){ ?>
                            <tr>
                                <td style="border-right: 1px solid #CCCCCC;"><?= $no+=1; ?></td>
                                <td><?= $item2['ScheduleID']; ?></td>
                                <td><?= $item2['ClassGroup']; ?></td>
                            </tr>
                        <?php }

                        ?>
                        </tbody>
                    </table>
                </div>
                <hr/>
            </div>

            <div class="col-md-12"><hr/></div>
        </div>
    </div>

<?php } } ?>



<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


<script>

    $(document).ready(function () {

        $('#filterTahun').val('<?= $Year; ?>');

    });

    $('#btnSubmit').click(function () {
        var filterTahun = $('#filterTahun').val();

        window.location.href = "<?= base_url('cekKRS'); ?>/"+filterTahun;

    });

</script>
</body>
</html>

