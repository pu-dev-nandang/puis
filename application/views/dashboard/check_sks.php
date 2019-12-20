<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<style>
    .table-centre td, .table-centre th {
        text-align: center;
    }

    /*.table-centre td:nth-child(2), .table-centre td:nth-child(1), .table-centre td:nth-child(7){*/
    /*    text-align: left;*/
    /*}*/
</style>


<div class="container">
    <div class="row">
        <div class="col-md-12" style="margin-top: 50px;">
            <div class="well">
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-control" id="filterSemester">
                            <option value="17">2020/2021 Ganjil</option>
                            <option value="16">2019/2020 Genap</option>
                            <option value="15">2019/2020 Ganjil</option>
                            <option value="14">2018/2019 Genap</option>
                            <option value="13">2018/2019 Ganjil</option>
                            <option value="12">2017/2018 Genap</option>
                            <option value="11">2017/2018 Ganjil</option>
                            <option value="8">2016/2017 Genap</option>
                            <option value="7">2016/2017 Ganjil</option>
                            <option value="6">2015/2016 Genap</option>
                            <option value="5">2015/2016 Ganjil</option>
                            <option value="4">2014/2015 Genap</option>
                            <option value="3">2014/2015 Ganjil</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="formStatusEmployee">
                            <option value="2">Contract Employees</option>
                            <option value="1">Permanent Employees</option>
                            <option value="-1" style="color:red;">Non Active</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="formStatusLecturer">
                            <option value="6">Home Base Lecturer</option>
                            <option value="5">Part Time Lecturer</option>
                            <option value="4" selected="">Honorer Lecturer</option>
                            <option value="3">Permanent Lecturer</option>
                            <option value="-1" style="color:red;">Non Active</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-block btn-success" id="btnSubmit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table class="table  table-bordered table-centre">
                <thead>
                <tr style="background: #f3f3f3;">
                    <th style="width: 1%;">No</th>
                    <th>Lecturer</th>
                    <th>Course</th>
                    <th style="width: 30%;">Schedule</th>
                    <th style="width: 3%;">Credit MK</th>
                    <th style="width: 3%;">Credit BKD</th>
                    <th style="width: 20%;">Team</th>
                    <th style="width: 5%;">Credit Difference</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $TotalCreditAsli = 0;
                $TotalCreditBKD = 0;
                $TotalCreditJadwal = 0;
                $TotalCreditSelisih = 0;

                foreach ($dataLecturer AS $i => $item){

                    if(count($item['Course'])>0){ ?>

                        <tr>
                            <td rowspan="<?= count($item['Course']) + 1; ?>"><?= ($i+1) ?></td>
                            <td style="text-align: left;" rowspan="<?= count($item['Course']) + 1; ?>"><?= $item['Name']; ?></td>
                        </tr>
                        <?php for ($i=0;$i<count($item['Course']);$i++){

                            $d = $item['Course'][$i];

                            $team = '';
                            if(count($d['DetailTeam'])>0){
                                foreach ($d['DetailTeam'] AS $itm){
                                    $team = $team.'<div>- '.$itm['NIP'].' '.$itm['Name'].'</div>';
                                }
                            }

                            $schedule = '';
                            $totalSKS = 0;
                            if(count($d['Schedule'])>0){
                                $tr = '';

                                foreach ($d['Schedule'] AS $itm){
                                    $tr = $tr.'<tr>
                                                            <td>'.$itm['DayNameEng'].'</td>
                                                            <td>'.substr($itm['StartSessions'],0,5).' - '.substr($itm['EndSessions'],0,5).'</td>
                                                            <td style="border-right: 1px solid #ccc;">'.$itm['Room'].'</td>
                                                            <td><span style="color: blue;">'.$itm['Credit'].'</span></td>
                                                            </tr>';

                                    $totalSKS = $totalSKS + $itm['Credit'];
                                }

                                $schedule = '<table class="table" style="margin-bottom: 0px;">
                                                    <tbody>'.$tr.'</tbody><tr style="background: #e0f4ff;font-weight: bold;"><td colspan="3" style="border-right: 1px solid #ccc;">Total Credit</td><td>'.$totalSKS.'</td></tr></table>';
                            }

                            $TotalCreditAsli = $TotalCreditAsli + $d['CreditMK'];
                            $TotalCreditBKD = $TotalCreditBKD + $d['CreditBKD'];
                            $TotalCreditJadwal = $TotalCreditJadwal + $totalSKS;
                            $TotalCreditSelisih = $TotalCreditSelisih + ($totalSKS- $d['CreditMK']);

                            ?>

                            <tr style="<?= (count($d['DetailTeam'])>0) ? 'background: #ff000017;' : 'background: #fff;' ?>">
                                <td style="text-align: left;"><b style=""><?= $d['NameEng']; ?></b><br/>Code : <?= $d['MKCode']; ?><br/>Group : <?= $d['ClassGroup']; ?></td>
                                <td style="text-align: left;font-size: 12px;"><?= $schedule; ?></td>
                                <td><?= $d['CreditMK']; ?></td>
                                <td style="background: lightyellow;"><?= $d['CreditBKD']; ?></td>
                                <td style="text-align: left;font-size: 12px;"><?= $team; ?></td>
                                <td style="font-size: 12px;"><?= $totalSKS; ?> - <?= $d['CreditMK']; ?> = <?= ($totalSKS- $d['CreditMK']); ?></td>
                            </tr>


                            <?php


                        } ?>



                    <?php }  ?>



                <?php } ?>
                </tbody>

                <tr>
                    <td colspan="3"></td>
                    <td><?= $TotalCreditJadwal; ?></td>
                    <td><?= $TotalCreditAsli; ?></td>
                    <td><?= $TotalCreditBKD; ?></td>
                    <td>-</td>
                    <td><?= $TotalCreditSelisih; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>

    $(document).ready(function(){
        var SemesterID = "<?= $SemesterID; ?>";
        var StatusEmployeeID = "<?= $StatusEmployeeID; ?>";
        var StatusLecturerID = "<?= $StatusLecturerID; ?>";

        $('#filterSemester').val(SemesterID);
        $('#formStatusEmployee').val(StatusEmployeeID);
        $('#formStatusLecturer').val(StatusLecturerID);
    });

    $('#btnSubmit').click(function () {

        var filterSemester = $('#filterSemester').val();
        var formStatusEmployee = $('#formStatusEmployee').val();
        var formStatusLecturer = $('#formStatusLecturer').val();

        var base_url = "<?= base_url() ?>";
        var url = base_url+'checkSKS/'+filterSemester+'/'+formStatusEmployee+'/'+formStatusLecturer;

        window.location.href = url;


    });

</script>


</body>
</html>