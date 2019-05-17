

<style>
    #tableLec tr th, #tableLec tr td {
        text-align: center;
    }
    #tableLec tr td span{
        font-size: 12px;
        color: #9e9e9e;
    }

    #tableStd tr th, #tableStd tr td {
        text-align: center;
    }
    #tableLec tr th, #tableStd tr th {
        background: #eaeaea;

    }
</style>

<div class="row" style="margin-top: 20px;">

    <div class="col-md-3">
        <a href="<?= base_url('academic/semester-antara/timetable/'.$SASemesterID); ?>" class="btn btn-default btn-default-warning"><i class="fa fa-arrow-left margin-right"></i> Back timetables</a>
    </div>

    <div class="col-md-6">
        <div class="well">
            <h3 style="margin-top: 10px;text-align: center;"><?= $dataCourse['MKCode']; ?> - <?= $dataCourse['CourseEng']; ?></h3>
        </div>
    </div>

</div>

<div class="row">



    <div class="col-md-3">
        <h3 class="heading-sa">Lecturers</h3>
        <table class="table table-bordered table-striped" id="tableLec">
            <thead>
            <tr>
                <th style="width: 1%;">Sess.</th>
                <th>Lecturer</th>
            </tr>
            </thead>
            <tbody>
            <?php for($i=0;$i<count($dataLec);$i++){
                $d = $dataLec[$i];
                $LecAttd = '';
                if(count($d)>0){
                    foreach ($d AS $item){
                        $LecAttd = $LecAttd.'<div>'.$item['Name'].'<br/><span>'.date('l, d M Y H:i:s',strtotime($item['EntredAt'])).'</span></div>';
                    }
                }

                ?>
                <tr>
                    <td><?= $i+1; ?></td>
                    <td style="text-align: left;"><?= $LecAttd; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="col-md-9">
        <h3 class="heading-sa">Students</h3>
        <table class="table table-bordered table-striped" id="tableStd">
            <thead>
            <tr>
                <th rowspan="2" style="width: 1%;"">No</th>
                <th rowspan="2">Students</th>
                <th colspan="14">Sessions</th>
            </tr>
            <tr>
                <th style="width: 5%;">1</th>
                <th style="width: 5%;">2</th>
                <th style="width: 5%;">3</th>
                <th style="width: 5%;">4</th>
                <th style="width: 5%;">5</th>
                <th style="width: 5%;">6</th>
                <th style="width: 5%;">7</th>
                <th style="width: 5%;">8</th>
                <th style="width: 5%;">9</th>
                <th style="width: 5%;">10</th>
                <th style="width: 5%;">11</th>
                <th style="width: 5%;">12</th>
                <th style="width: 5%;">13</th>
                <th style="width: 5%;">14</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $no=1;
            foreach ($dataStd AS $item2){ ?>

                <tr>
                    <td><?=$no?></td>
                    <td style="text-align: left;"><?=$item2['Name']?><br/><span><?=$item2['NPM'];?></span></td>
                    <?php

                    for ($a=0;$a<count($item2['DataAttd']);$a++){
                        $item3 = $item2['DataAttd'][$a];
                        $sts = '';
                        if($item3==1 || $item3=='1'){
                            $sts = '<div style="color: green;"><i class="fa fa-check-circle" aria-hidden="true"></i></div>';
                        } else if($item3==2 || $item3=='2'){
                            $sts = '<div style="color: red;"><i class="fa fa-times-circle" aria-hidden="true"></i></div>';
                        }

                        echo "<td>".$sts."</td>";

                    }
                    ?>
                </tr>

                <?php  $no++; }
            ?>
            </tbody>
        </table>
    </div>
</div>