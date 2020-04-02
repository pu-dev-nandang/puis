
<style>
    .table-setting tr td {
       font-weight: bold;
    }
    #tableHonor tr th{
        text-align: center;
        background: #607D8B;
        color: #FFFFFF;
    }
    #tableHonor tr td, #tableEducation tr td {
        text-align: center;
    }

    #tableEducation tr th{
        text-align: center;
        background: #607D8B;
        color: #FFFFFF;
    }
</style>
<div class="row">
     <div class="col-md-60">
        <div class="thumbnail" style="padding: 0px">
            <span class="label-info" style="color: #ffffff;padding: 5px;padding-left:10px;padding-right:10px;font-weight: bold;"><i class="fa fa-cog margin-right"></i> Identitas Perguruan Tinggi</span>

            <div style="margin: 5px;margin-top: 20px;">
                <table class="table table-bordered" id="tableStudyAcc">
                    <tr>
                        <th style="width: 5%">Kode Hukum</th>
                        <th style="width: 5%">Kode PT</th>
                        <th style="width: 15%">Nama PT</th>
                        <th style="width: 5%">Tanggal Berdiri</th>
                        <th style="width: 5%">Nomor Akta</th>
                        <th style="width: 5%">Tanggal Akta</th>
                        <th style="width: 5%">Nomor Pengesahan</th>
                        <th style="width: 5%">Tanggal Pengesahan</th>
                        <th style="width: 15%">Alamat</th>
                        <th style="width: 5%">Kota</th>
                        <th style="width: 5%">Kode Pos </th>
                        <th style="width: 5%">Telepon </th>
                        <th style="width: 5%">Fax</th>
                    </tr>

                    <?php foreach ($identitas AS $itemX){ ?>
                        <tr>
                            <td><?php echo $itemX['KodeHukum']; ?></td>
                            <td><?php echo $itemX['KodePT']; ?></td>
                            <td><?php echo $itemX['Nama']; ?></td>
                            <td><?php echo $itemX['TglBerdiri']; ?></td>
                            <td><?php echo $itemX['NoAkta']; ?></td>
                            <td><?php echo $itemX['TglAkta']; ?></td>
                            <td><?php echo $itemX['NoSah']; ?></td>
                            <td><?php echo $itemX['TglSah']; ?></td>
                            <td><?php echo $itemX['Alamat']; ?></td>
                            <td><?php echo $itemX['Kota']; ?></td>
                            <td><?php echo $itemX['KodePos']; ?></td>
                            <td><?php echo $itemX['Telepon']; ?></td>
                            <td><?php echo $itemX['Fax']; ?></td>

                            <!-- <td>
                                <button class="btn btn-success btn-sm btnSaveEdStudy hide" id="btnSaveEdStudy<?php echo $itemX['ID']; ?>" data-id="<?php echo $itemX['ID']; ?>">Save</button>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-default dropdown-toggle btnDropDownEdStudy" id="btnDropDownEdStudy<?php echo $itemX['ID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-pencil-square-o"></i> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void(0);" class="btnEdit" data-accreditation="<?= $itemX['accreditationID']; ?>" data-id="<?php echo $itemX['ID']; ?>">Edit</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li class="disabled"><a href="javascript:void(0);" class="btnDelEd disabled" disabled="disabled" data-id="<?php echo $itemX['ID']; ?>">Delete</a></li>
                                    </ul>
                                </div>
                            </td> -->
                        </tr>
                    <?php } ?>

                    <!--                    <button class="btn btn-sm btn-default btn-default-danger"><i class="fa fa-trash"></i></button>-->
                </table>

            </div>
        </div>

    </div>

</div>
