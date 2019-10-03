<div class="table-responsive">
  <table class="table table-bordered">
    <tr>
      <th class="text-center">NO</th>
      <th>IMAGES</th>
      <th>TITLE</th>
      <th>PRODI</th>
      <th>DATE</th>
      <th colspan="2" class="text-center"><span class="glyphicon glyphicon-cog"></span></th>
    </tr>
    <?php
        $no = 1;
    	foreach($tampil as $data){
    ?>
      <tr>
        <td class="align-middle text-center"><?php echo $no; ?></td>
        <td class="align-middle"><?php echo $data->Images; ?></td>
        <td class="align-middle"><?php echo $data->TitleImages; ?></td>
        <td class="align-middle"><?php echo $data->Prodi; ?></td>
        <td class="align-middle"><?php echo $data->Date; ?></td>
        <td class="align-middle text-center">
          <a href="javascript:void();" data-id="<?php echo $data->ID_Slider; ?>" data-toggle="modal" data-target="#form-modal" class="btn btn-default btn-form-ubah"><span class="glyphicon glyphicon-pencil"></span></a>
                    <!-- Membuat sebuah textbox hidden yang akan digunakan untuk form ubah -->
              <input type="hidden" class="images-value" value="<?php echo $data->Images; ?>">
              <input type="hidden" class="title-value" value="<?php echo $data->TitleImages; ?>">
              <input type="hidden" class="prodi-value" value="<?php echo $data->Prodi; ?>">
              <input type="hidden" class="date-value" value="<?php echo $data->Date; ?>">
              <input type="hidden" class="user-value" value="<?php echo $data->User; ?>">
              <input type="hidden" class="kaprodi-value" value="<?php echo $data->KaprodiID; ?>">
              <input type="hidden" class="status-value" value="<?php echo $data->StatusTittReg; ?>">

        </td>
        <td class="align-middle text-center">
          <a href="javascript:void();" data-id="<?php echo $data->ID_Slider; ?>" data-toggle="modal" data-target="#delete-modal" class="btn btn-danger btn-alert-hapus"><span class="glyphicon glyphicon-trash"></span></a>
        </td>
      </tr>
    <?php
      $no++; // Tambah 1 setiap kali looping
    }
    ?>
  </table>
</div>