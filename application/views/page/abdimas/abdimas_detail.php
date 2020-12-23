<style>
    .btn-circle.btn-xl {
    width: 70px;
    height: 70px;
    padding: 10px 16px;
    border-radius: 35px;
    font-size: 24px;
    line-height: 1.33;
}

.btn-circle {
    width: 30px;
    height: 30px;
    padding: 6px 0px;
    border-radius: 15px;
    text-align: center;
    font-size: 12px;
    line-height: 1.42857;
}

.btn-round{
    border-radius: 17px;
}

.btn-round2{
    border-radius: 12px 0px 8px 0px;
}

</style> 

<style>
  
  .form-horizontal .control-labels {
    padding-top: 7px;
    margin-bottom: 0;
    text-align: left;
  }
  .panel-default > .panel-heading-custom {
        background: #3968c6; color: #fff;
  }

  .element-style {
    width: 147px;
}
.left-panel {
    position: absolute;
    top: 196px;
    left: 32px;
  }
.panel-group {
    margin-bottom: 0px;
}
</style>


<div class="container">
  <div class="panel panel-default">
    <div class="panel-heading panel-heading-custom">Detail data Pengabdian Masyarakat</div>
    <div class="table-responsive">
      <table class="table table-striped">
         <tbody>
            <tr>
               <td colspan="1" style="width: 50%;">
                  <form class="well form-horizontal">
                     <fieldset>

                      <div class="form-group">
                           <label class="col-md-3 control-labels">Kategori Kegiatan <span style="color: red;">*</span></label>
                           <div class="col-md-9 inputGroupContainer">
                              <div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                               
                                <div class="panel-group" id="accordion">   
                                  <div class="panel panel-default">
                                        <div class="panel-heading panel-heading-custom1">
                                            <h4 class="panel-title">
                                                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse7"> <i class="fa fa-chevron-down"></i>  Melaksanakan Kegiatan Pengabdian kepada Masyarakat</a>
                                            </h4>
                                        </div>
                                    <div id="collapse7" class="panel-collapse collapse">
                                          <div class="panel-body">

                                            <?php if($arr_pkm[0]['Peran'] == '130200') {
                                                  echo '<label><input type ="radio" name = "id_katgiat" id ="id_katgiat" value ="130200" checked disabled> Melaksanakan pengembangan hasil pendidikan dan penelitian yang dapat dimanfaatkan oleh masyarakat/industri
                                                    </label>';
                                               } else {
                                                   echo '<label><input type ="radio" name = "id_katgiat" id ="id_katgiat" value ="130200" disabled> Melaksanakan pengembangan hasil pendidikan dan penelitian yang dapat dimanfaatkan oleh masyarakat/industri
                                                    </label>';
                                               }
                                            ?>
                                                    
                                                <div class="panel-group" id="accordion2">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading panel-heading-custom1">
                                                            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion2" href="#collapse66"> <i class="fa fa-chevron-down"></i> Memberi pelayanan kepada masyarakat atau kegiatan lain yang menunjang pelaksanaan tugas umum pemerintah dan pembangunan</a> 
                                                            </h4>
                                                        </div>
                                                        <div id="collapse66" class="panel-collapse collapse">
                                                              <div class="panel-body">  

                                                                <?php if($arr_pkm[0]['Peran'] == '130401') {
                                                                      echo '<label><input type ="radio" name = "id_katgiat" id ="id_katgiat" value ="130401" checked disabled> Berdasarkan bidang keahlian
                                                                        </label>';
                                                                   } else {
                                                                       echo '<label><input type ="radio" name = "id_katgiat" id ="id_katgiat" value ="130401" disabled> Berdasarkan bidang keahlian
                                                                        </label>';
                                                                   }
                                                                ?>

                                                                <?php if($arr_pkm[0]['Peran'] == '130402') {
                                                                      echo '<label><input type ="radio" name = "id_katgiat" id ="id_katgiat" value ="130402" checked disabled> Berdasarkan penugasan lembaga perguruan tinggi
                                                                        </label>';
                                                                   } else {
                                                                       echo '<label><input type ="radio" name = "id_katgiat" id ="id_katgiat" value ="130402" disabled> Berdasarkan penugasan lembaga perguruan tinggi
                                                                        </label>';
                                                                   }
                                                                ?>

                                                                <?php if($arr_pkm[0]['Peran'] == '130403') {
                                                                      echo '<label><input type ="radio" name = "id_katgiat" id ="id_katgiat" value ="130403" checked disabled> Berdasarkan fungsi/jabatan
                                                                        </label>';
                                                                   } else {
                                                                       echo '<label><input type ="radio" name = "id_katgiat" id ="id_katgiat" value ="130403" disabled> Berdasarkan fungsi/jabatan
                                                                        </label>';
                                                                   }
                                                                ?>

                                                              </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                      </div>
                                    </div>
                                </div>
                             </div>
                           </div>
                        </div>

                         <div class="form-group">
                          <label class="col-md-4 control-labels">Jenis Usulan <span style="color: red;">*</span></label>
                           <div class="col-md-8 inputGroupContainer">
                              <div class="input-group"><span class="input-group-addon"><i class="fa fa fa-book"></i></span>
                                 <select class="form-control" id="jenis_usulan">
                                   
                                    <option id="<?php echo $arr_pkm[0]['Jenis_usulan']; ?>" disabled selected> <?php echo $arr_pkm[0]['Jenis_usulan']; ?></option>
                                   
                                 </select>
                             </div>
                           </div>
                        </div>
                       
                        <div class="form-group">
                           <label class="col-md-4 control-labels">Judul Kegiatan <span style="color: red;">*</label>
                           <div class="col-md-8 inputGroupContainer">
                              <div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                <textarea style="resize: none;" id="judul_kegiatan" class="form-control" rows="2" disabled> <?php echo $arr_pkm[0]['Judul_PKM']; ?> 
                                </textarea> 
                             </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels">Afiliasi <span style="color: red;">*</span></label>
                           <div class="col-md-8 inputGroupContainer">
                              <div class="input-group"><span class="input-group-addon"><i class="fa fa-university"></i></span>
                                <select id="sumber_pembiayaan" class="form-control form-exam element-style"><option value="<?php echo $arr_pkm[0]['ID_lemb_iptek']; ?>" disabled selected> <?php echo $arr_pkm[0]['Name_University']; ?></option></select>
                             </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels"> Kelompok Bidang <span style="color: red;">*</span></label>
                           <div class="col-md-8 inputGroupContainer">
                              <div class="input-group"><span class="input-group-addon"><i class="fa fa-university"></i></span>
                                  <select id="sumber_pembiayaan" class="form-control form-exam element-style"><option value="<?php echo $arr_pkm[0]['ID_kel_bidang']; ?>" disabled selected> <?php echo $arr_pkm[0]['Nm_kel_bidang']; ?></option></select>
                             </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels">Litabmas Sebelumnya <span style="color: red;">*</span></label>
                           <div class="col-md-8 inputGroupContainer">
                              <div class="input-group"><span class="input-group-addon"><i class="fa fa-university"></i></span>
                                 <select id="sumber_pembiayaan" class="form-control form-exam element-style"><option value="<?php echo $arr_pkm[0]['ID_pengabdian_existing']; ?>" disabled selected> <?php echo $arr_pkm[0]['NamaPKM']; ?></option></select>
                             </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels">Jenis SKIM <span style="color: red;">*</span></label>
                           <div class="col-md-8 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="fa fa-book"></i></span>
                                 <select id="sumber_pembiayaan" class="form-control form-exam element-style"><option value="<?php echo $arr_pkm[0]['ID_skim']; ?>" disabled selected> <?php echo $arr_pkm[0]['Nm_skim']; ?></option></select>
                               </div>
                             </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels">Lokasi Kegiatan</label>
                           <div class="col-md-8 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                 <input id="lokasi_kegiatan" name="lokasi_kegiatan" placeholder="" class="form-control" required="true" value="<?php echo $arr_pkm[0]['Lokasi_kegiatan']; ?>" disabled>
                               </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels">Tahun Usulan <span style="color: red;">*</span></label>
                           <div class="col-md-8 inputGroupContainer">
                              <div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input id="thn_usulan" name="thn_usulan" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="4" value="<?php echo $arr_pkm[0]['ID_thn_usulan']; ?>" disabled>   
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels">Tahun Kegiatan <span style="color: red;">*</span></label>
                           <div class="col-md-8 inputGroupContainer">
                              <div class="input-group">
                                 <span class="input-group-addon" style="max-width: 100%;"><i class="fa fa-calendar"></i></span>
                                   <input id="thn_kegiatan" name="thn_kegiatan" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="4" value="<?php echo $arr_pkm[0]['ID_thn_kegiatan']; ?>" disabled>  
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels">Tahun Pelaksanaan <span style="color: red;">*</span></label>
                           <div class="col-md-8 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                  <input id="thn_pelaksanaan" name="thn_pelaksanaan" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="4" value="<?php echo $arr_pkm[0]['ID_thn_laks']; ?>" disabled> 
                               </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels">Lama Kegiatan<span style="color: red;">*</span></label>
                           <div class="col-md-4 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar-plus-o"></i></span>
                                 <input id="lama_kegiatan" name="lama_kegiatan" class="form-control" value="<?php echo $arr_pkm[0]['Lama_kegiatan']; ?>" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="2" disabled>
                               </div>
                           </div>
                           <div class="col-md-4 inputGroupContainer">
                              <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                      <select id="option_waktu" class="form-control form-exam element-style option_waktu">
                                          <option id="<?php echo $arr_pkm[0]['Lama_waktu']; ?>" disabled selected> <?php echo $arr_pkm[0]['Lama_waktu']; ?> </option>
                                      </select>
                                  </div>
                            </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels">Tahun Pelaksanaan Ke <span style="color: red;">*</span></label>
                           <div class="col-md-8 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar-plus-o"></i></span>
                                 <input id="thn_laks_ke" name="thn_laks_ke" class="form-control" value="<?php echo $arr_pkm[0]['Thn_laks_ke']; ?>" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="2" disabled>
                               </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels"> Sumber Pembiayaan <span style="color: red;">*</span></label>
                           <div class="col-md-8 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>
                                 <select id="sumber_pembiayaan" class="form-control form-exam element-style"><option id="<?php echo $arr_pkm[0]['ID_sumberdana']; ?>" disabled="disabled" selected> <?php echo $arr_pkm[0]['SumberDana']; ?> </option></select>
                               </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels">Dana dari Dikti (Rp.) <span style="color: red;">*</span></label>
                           <div class="col-md-8 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>
                                 <input id="dana_dikti" name="dana_dikti" class="form-control" value="<?php echo $arr_pkm[0]['Dana_dikti']; ?>" disabled>
                               </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels">Dana dari Perguruan Tinggi (Rp.) <span style="color: red;">*</span></label>
                           <div class="col-md-8 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>
                                  <input id="dana_pt" name="dana_pt" class="form-control" value="<?php echo $arr_pkm[0]['Dana_pt']; ?>" disabled>
                               </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels">Dana dari Institusi Lain (Rp.) <span style="color: red;">*</span></label>
                           <div class="col-md-8 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>
                                <input id="dana_institusi_lain" name="dana_institusi_lain" class="form-control" value="<?php echo $arr_pkm[0]['Dana_institusi_lain']; ?>" disabled>
                               </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-md-4 control-labels">Dana dari Lembaga Luar Negeri (Rp.) </label>
                           <div class="col-md-8 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>
                                 <input id="dana_luar_negeri" name="dana_luar_negeri" class="form-control" value="<?php echo $arr_pkm[0]['Dana_lembaga_luarnegeri']; ?>" disabled>
                               </div>
                           </div>
                        </div>
                        <div class="form-group"> 
                           <label class="col-md-4 control-labels">In Kind</label> 
                           <div class="col-md-8 inputGroupContainer"> 
                               <div class="input-group"><span class="input-group-addon"><i class="fa fa-comment"></i></span> 
                                   <input class="form-control" rows="5" id="inkind" value="<?php echo $arr_pkm[0]['Inkind']; ?>" disabled></input> 
                               </div> 
                           </div> 
                        </div>
                         <div class="form-group">
                           <label class="col-md-4 control-labels">No SK Penugasan </label>
                           <div class="col-md-8 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="fa fa fa-users"></i></span>
                                 <input id="no_sk_penugasan" name="no_sk_penugasan" class="form-control" value="<?php echo $arr_pkm[0]['No_SK_penugasan']; ?>" disabled>
                               </div>
                           </div>
                        </div>
                         <div class="form-group">
                           <label class="col-md-4 control-labels">Tanggal Penugasan </label>
                           <div class="col-md-8 inputGroupContainer">
                              <div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar-plus-o"></i></span>
                                                         <?php if ($arr_pkm[0]['Tgl_penugasan'] == "0000-00-00") {
                                                              $pet = "";
                                                              echo '<input id="tgl_penugasan" name="tgl_penugasan" class="form-control" value="'.$pet.'" disabled>';
                                                            } else {
                                                              $pet = $arr_pkm[0]['Tgl_penugasan'];
                                                              echo '<input id="tgl_penugasan" name="tgl_penugasan" class="form-control" value="'.date('Y-m-d', strtotime($pet)).'" disabled>';
                                                            }
                                                          ?>
                                </div>
                           </div>
                        </div>

                         <div class="form-group">
                           <label class="col-md-4 control-labels">Mitra Pengabdian Masyarakat </label>
                           <div class="col-md-8 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                 <input id="mitra_litabmas" name="mitra_litabmas" class="form-control" required="true" value="<?php echo $arr_pkm[0]['Mitra_litabmas']; ?>" disabled>
                               </div>
                           </div>
                        </div>

                         
                        <span style="color: red;">( ** ) Pengabdian Masyarakat yang terintegrasi dengan matakuliah</span>
                        <div class="form-group">
                           <label class="col-md-4 control-labels"> Tahun Kurikulum <span style="color: red;">**</span></label>
                           <div class="col-md-8 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar-check-o"></i></span>
                                 
                                 <select id="filterkurikulum" class="form-control form-exam element-style"><option id="<?php echo $arr_pkm[0]['SemesterID']; ?>" disabled selected> <?php echo $arr_pkm[0]['Name']; ?> </option></select>
                               </div>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-md-4 control-labels"> Nama Mata Kuliah <span style="color: red;">**</span></label>
                           <div class="col-md-8 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-file"></i></span>
                                  <select id="filterkurikulum" class="form-control form-exam element-style"><option id="<?php echo $arr_pkm[0]['MKCode']; ?>" disabled selected> <?php echo $arr_pkm[0]['NameEng']; ?> </option></select>
                               </div>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-md-4 control-labels"> Bentuk Integrasi <span style="color: red;">**</span></label>
                           <div class="col-md-8 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-retweet"></i></span>
                                   <textarea id="bentuk_integrasi" class="form-control" rows="3" disabled> <?php echo $arr_pkm[0]['Bentuk_integrasi']; ?> </textarea> 
                               </div>
                           </div>
                        </div>
                     </fieldset>
                  </form>
               </td>

               <td colspan="1">
                  <form class="well form-horizontal">
                     <fieldset>
                      <!-- table upload dokumen -->   
                        <div class="row" style="margin-top: 0px;">   
                            <div class="col-md-12">                                          
                               <div class="panel panel-default">                             
                                   <div class="panel-heading">                               
                                       <h4 class="panel-title"><i class="fa fa-upload"></i> <b> Upload Dokumen</b> 
                                          
                                       </h4>           
                                </div> 
                                  <div class="panel-body">
                                     <div class=""> 
                                       <table class="table table-bordered table-striped" id="tableversion">
                                           <thead>                                  
                                           <tr style="background: #3968c6;color: #FFFFFF;"> 
                                                <th style="width: 5%;text-align: center;">Nama Document</th>          
                                                <th style="width: 7%;text-align: center;">Jenis Document</th>   
                                                <th style="width: 15%;text-align: center;">Keterangan</th>             
                                                <th style="width: 10%;text-align: center;">Tautan Document</th>       
                                                <th style="text-align: center;width: 5%;">File</th>   
                                           </tr> 
                                           </thead> 
                                         <tbody id="dataRowUpload"></tbody>  
                                       </table> 
                                    </div>           
                                  </div> 
                               </div> 
                           </div>
                          </div> 
                          <!-- table upload dokumen --> 

                          <!-- table upload data laporan -->   
                        <div class="row" style="margin-top: 0px;">   
                            <div class="col-md-12">                                          
                               <div class="panel panel-default">                             
                                  <div class="panel-heading" style="background-color: #D5D8DC;">                               
                                       <h4 class="panel-title"><i class="fa fa-upload"></i> <b> Format Isi Laporan</b> 
                                           <div class="pull-right">                                                
                                               
                                           </div>
                                       </h4>           
                                  </div> 
                                  <div class="panel-body">
                                     <div class=""> 
                                       <table class="table table-bordered table-striped" id="tableversiffon">
                                           <thead>                                  
                                           <tr style="background: #3968c6;color: #FFFFFF;">        
                                                <th style="width: 7%;text-align: center;">Jenis Format</th>   
                                                <th style="width: 20%;text-align: center;">Isi File</th>   
                                                <th style="width: 10%;text-align: center;">Tanggal Isi</th>           
                                                <th style="text-align: center;width: 5%;">File</th>   
                                           </tr> 
                                           </thead> 
                                         <tbody id="dataRowFormatLaporan"></tbody>  
                                         
                                       </table> 
                                    </div>           
                                  </div> 
                               </div> 
                           </div>
                        </div> 

                        <!-- table anggaran biaya laporan -->   
                        <div class="row" style="margin-top: 0px;">   
                            <div class="col-md-12">                                          
                               <div class="panel panel-default">                             
                                  <div class="panel-heading" style="background-color: #D5D8DC;">                               
                                       <h4 class="panel-title"><i class="fa fa-money"></i> <b> Anggaran Biaya</b> 
                                           <div class="pull-right">                                                
                                               
                                           </div>
                                       </h4>           
                                  </div> 
                                  <div class="panel-body">
                                     <div class=""> 
                                       <table class="table table-bordered table-striped" id="tablebiaya">
                                           <thead>                                  
                                           <tr style="background: #3968c6;color: #FFFFFF;">        
                                                <th style="width: 12%;text-align: center;">Nama Barang</th>   
                                                <th style="width: 10%;text-align: center;">Harga Satuan</th>   
                                                <th style="width: 7%;text-align: center;">Qty</th>           
                                                <th style="text-align: center;width: 8%;">Total Harga</th>  
                                                <th style="text-align: center;width: 8%;">Realisasi</th>  
                                                <th style="text-align: center;width: 8%;"></th>  
                                           </tr> 
                                           </thead> 
                                         <tbody id="dataRowBiaya"></tbody>    
                                       </table> 
                                    </div>           
                                  </div> 
                               </div> 
                           </div>
                        </div> 
                        <!-- table anggaran biaya laporan -->     

                        <div class="row" style="margin-top: 0px;">  
                         <div class="col-md-12">                                          
                            <div class="panel panel-default">                             
                                <div class="panel-heading">                               
                                     <h4 class="panel-title"><i class="glyphicon glyphicon-user"></i> <b> Anggota Kegiatan (Dosen)</b> 
                                          
                                     </h4>      
                                </div> 
                                 <div class="panel-body"> 
                                   <div class=""> 
                                    <input type="hidden" id="YesNoDosen" value="N"><input type="hidden" id="checkKetuaDosen" value="0">
                                       <table class="table table-bordered table-striped" id="tablelistdosen"> 
                                           <thead>                                  
                                           <tr style="background: #3968c6;color: #FFFFFF;"> 
                                                <th style="width: 20%;text-align: center;">Nama Dosen</th>         
                                                <th style="width: 10%;text-align: center;">Peran</th>   
                                                <th style="width: 6%;text-align: center;">Status</th>          
                                                <th style="width: 8%;text-align: center;">Hapus</th>  
                                           </tr> 
                                           </thead> 
                                         <tbody id="dataRowdosen"></tbody>  
                                       </table> 
                                    
                                 </div>            
                                </div> 
                             </div> 
                         </div> 
                      </div> 
                     
                      <div class="row" style="margin-top: 0px;">   
                          <div class="col-md-12">                                          
                             <div class="panel panel-default">                             
                                 <div class="panel-heading">                               
                                     <h4 class="panel-title"><i class="glyphicon glyphicon-user"></i> <b> Anggota Kegiatan (Mahasiswa)</b> 
                                        
                                     </h4>             
                                 </div> 
                                 <div class="panel-body"> 
                                    <div class=""> 
                                      <input type="hidden" id="YesNomahasiswa" value="N"><input type="hidden" id="checkKetuaMahasiwa" value="0">
                                           <table class="table table-bordered table-striped" id="tablelistmahasiswa"> 
                                               <thead>                                  
                                               <tr style="background: #3968c6;color: #FFFFFF;"> 
                                                    <th style="width: 20%;text-align: center;">Nama Mahasiswa</th>       
                                                    <th style="width: 10%;text-align: center;">Peran</th>   
                                                    <th style="width: 6%;text-align: center;">Status</th>         
                                                    <th style="width: 8%;text-align: center;">Action</th>  
                                               </tr> 
                                               </thead> 
                                             <tbody id="dataRowmahasiswa"></tbody>  
                                           </table> 
                                          
                                    </div>            
                                </div> 
                           </div> 
                         </div> 
                      </div> 
                       
                      <div class="row" style="margin-top: 0px;">   
                          <div class="col-md-12">                                          
                             <div class="panel panel-default">                             
                                 <div class="panel-heading">                               
                                     <h4 class="panel-title"><i class="glyphicon glyphicon-user"></i> <b> Anggota Kegiatan (Kolaborator Eksternal)</b> 
                                        
                                     </h4>   
                              </div> 
                                 <div class="panel-body"> 
                                    <div class=""> 
                                         <input type="hidden" id="YesNoKolaborator" value="N"><input type="hidden" id="checkKetuaKolaborator" value="0">
                                             <table class="table table-bordered table-striped" id="tablelistkolaborator"> 
                                                 <thead>                                  
                                                 <tr style="background: #3968c6;color: #FFFFFF;"> 
                                                      <th style="width: 20%;text-align: center;">Nama Kolaborator</th>         
                                                      <th style="width: 10%;text-align: center;">Peran</th>   
                                                      <th style="width: 6%;text-align: center;">Status</th>         
                                                      <th style="width: 8%;text-align: center;">Action</th>  
                                                 </tr> 
                                                 </thead> 
                                               <tbody id="dataRowKolaborator"></tbody>  
                                             </table> 
                                       
                                    </div>            
                                 </div> 
                             </div> 
                          </div> 
                        </div>

                        <div class="row" style="margin-top: 0px;">   
                          <div class="col-md-12">                                          
                             <div class="panel panel-default">                             
                                  <div class="panel-heading" style="background-color: #D5D8DC;">                               
                                     <h4 class="panel-title"><i class="glyphicon glyphicon-user"></i> <b> Anggota Reviewer</b> 
                                         
                                     </h4>   
                                  </div> 
                                  <div class="panel-body"> 
                                    <div class=""> 
                                         <input type="hidden" id="YesNoReviewer" value="N"><input type="hidden" id="checkKetuaKolaboraggtor" value="0">
                                            <table class="table table-bordered table-striped" id="tablelistreviewer"> 
                                              <thead>                                  
                                                 <tr style="background: #3968c6;color: #FFFFFF;"> 
                                                      <th style="width: 15%;text-align: center;">Nama</th>         
                                                      <th style="width: 10%;text-align: center;">Reviewer</th>   
                                                      <th style="width: 8%;text-align: center;">Status</th>   
                                                      <th style="width: 7%;text-align: center;">Hapus</th>  
                                                 </tr> 
                                              </thead> 
                                              <tbody id="dataRowReviewer"></tbody>  
                                            </table>  
                                    </div>            
                                 </div> 
                             </div> 
                          </div> 
                        </div> 

                        <div class="form-group col-md-12 text-right">
                            <div class="btn-group">
                                <a type="button" class="btn btn-primary btn-round" href="<?php echo base_url('abdimas/monitoring-abdimas') ?>"> <i class="fa fa-arrow-circle-left"></i> Kembali</a>
                            </div>
                         </div>
                       
                     </fieldset>
                  </form>
               </td>
            </tr>
         </tbody>
      </table>
    </div>  
       
       <div></div>
    </div>
  </div>
</div>



<script>
  $(document).ready(function () {

    loadFileDokumen();
    loadanggotadosen();
    loadanggotamahasiswa();
    loadanggotkolaborator();
    loadanggotareviewer();
    load_isilaporan();
    loadDataAnggaranBiaya();
    
  });

</script>

<script>

  function load_isilaporan() {

      var id_pkm = "<?php echo $arr_pkm[0]['ID_PKM']; ?>";
      var url = base_url_js+'abdimas/__loadlist_pkm';
      var token = jwt_encode({
            action:'e_loadformat_isilaporan_PKM', 
            id_pkm : id_pkm
          },'UAP)(*');

      $.post(url,{token:token},function (data_json) {
            var response = JSON.parse(data_json);
             //$('#checkKetuaKolaborator').val('0');

            if(response.length > 0){
                ///$('#YesNoKolaborator').val('Y');
                var no = 1;
                var orbs=0;
                  for (var i = 0; i < response.length; i++) {

                    var dates = response[i]['Date_create'];
                    var usercreate = moment(dates).format('DD-MM-YYYY, h:mm');
                    
                    $("#dataRowFormatLaporan").append('<tr>                                          '+
                    '            <td> '+response[i]['Nama_format']+' </td>                           '+                             
                    '            <td style="text-align: center;"><a type="button" class="btn btn-primary btn-round btnformatlap" listID="'+response[i]['ID']+'" stat_id="1"><i class="fa fa-eye"></i> Preview Laporan</a></td>                              '+    
                    '            <td style="text-align: center;"> '+usercreate+'</td>                    '+                         
                    '            <td style="text-align: center;"> <a href="javascript:void(0);" id="btnDeleteLaporan" class="btn btn-sm btn-danger btn-circle" data-toggle="tooltip" data-placement="top" title="Delete" disabled><i class="fa fa-trash"></i></a></td>      '+     
                    '         </tr> ');
                  }
             }

            setTimeout(function () {
            },500)
        });
    }


    function loadDataAnggaranBiaya() {

      var id_pkm = "<?php echo $arr_pkm[0]['ID_PKM']; ?>";
      var url = base_url_js+'abdimas/__loadlist_pkm';
      var token = jwt_encode({action:'e_loadbiayaresearch_pkm', id_pkm : id_pkm},'UAP)(*');

        $.post(url,{token:token},function (data_json) {
            var response = JSON.parse(data_json);

            if(response.length > 0){
                var no = 1;
                var orbs=0;
                  for (var i = 0; i < response.length; i++) {

                  var hargasatuan = response[i]['Harga_satuan'];
                  var reverse_satuan = hargasatuan.toString().split('').reverse().join(''),
                  hargastuanx = reverse_satuan.match(/\d{1,3}/g);
                  harga_satuan = hargastuanx.join('.').split('').reverse().join('');
                  //----------------------------------------------------------------------
                  var totalharga = response[i]['Total_harga'];
                  var reverse_totalharga = totalharga.toString().split('').reverse().join(''),
                  totharga = reverse_totalharga.match(/\d{1,3}/g);
                  total_harga = totharga.join('.').split('').reverse().join('');

                   if(response[i]['Realisasi'] == null) {
                    var datarealisasi = ' - ';
                  }
                  else {
                    var reali_biaya = response[i]['Realisasi'];
                    var reverse_realisasi = reali_biaya.toString().split('').reverse().join(''),
                    tot_realisasi = reverse_realisasi.match(/\d{1,3}/g);
                    datarealisasi = tot_realisasi.join('.').split('').reverse().join('');
                  }

                  $("#dataRowBiaya").append('<tr>                                        '+
                    '       <td> '+response[i]['Nama_barang']+' </td>                    '+                             
                    '       <td style="text-align: center;"> '+harga_satuan+'</td>       '+    
                    '       <td style="text-align: center;">'+response[i]['Qty']+' '+response[i]['Tipe_satuan']+'</td> '+    
                    '       <td style="text-align: center;">'+total_harga+'</td>          '+      
                    '     <td style="text-align: center;">'+datarealisasi+'</td>          '+             
                    '       <td style="text-align: center;"><a href="javascript:void(0);" id="btnDeleteBiaya" class="btn btn-sm btn-danger btn-circle " data-toggle="tooltip" data-placement="top" title="Delete" disabled><i class="fa fa-trash"></i></a></td>      '+     
                    '   </tr> ');  
                }

                var tbl = $('#dataRowBiaya').closest('table');
                var isian = 0;
                var real = 0;

                for (var i = 0; i < response.length; i++) {
                      var isian = isian + parseInt(response[i]['Total_harga']);
                      var real = real + parseInt(response[i]['Realisasi']);
                }

                var angka = isian;
                var reverse = angka.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
                ribuan = ribuan.join('.').split('').reverse().join('');

                var angkareal = real;
                var reversereal = angkareal.toString().split('').reverse().join(''),
                ribuanreal = reversereal.match(/\d{1,3}/g);
                totreal = ribuanreal.join('.').split('').reverse().join('');
        
                tbl.append(
                   '<tr>'+
                          '<td style="text-align: center;" colspan = "3"><b>Total Biaya</b></td>'+
                          '<td style="text-align: center;"><b>'+ribuan+'</b></td>'+
                          '<td style="text-align: center;"><b>'+totreal+'</b></td>'+
                          '<td></td>'+
                    '</tr>'
                );
            }
        });
    }
  
  function loadFileDokumen() {
        var id_pkm = "<?php echo $arr_pkm[0]['ID_PKM']; ?>";
        
        var url = base_url_js+'abdimas/__loadlist_pkm';
        var token = jwt_encode({action:'detail_loadupload_pkm', id_pkm: id_pkm},'UAP)(*');

        $.post(url,{token:token},function (data_json) {
            var response = JSON.parse(data_json);

            if(response.length > 0){
                var no = 1;
                var orbs=0;
                  for (var i = 0; i < response.length; i++) {

                    var viewURL = (response[i]['Url'].length>50) ? response[i]['Url'].substring(0,50)+'.....' : response[i]['Url'];

                    $("#dataRowUpload").append('<tr>                                                          '+
                    '            <td> '+response[i]['Nm_dok']+' </td>                           '+                             
                    '            <td style="text-align: center;"> '+response[i]['Nm_jns_dok']+'</td>                                                '+    
                    '            <td>'+response[i]['Ket_dok']+'</td>                                              '+    
                    '            <td><a href="'+response[i]['Url']+'" target="_blank">'+viewURL+'</a></td>        '+                                   
                    '            <td style="text-align: center;"><a id="btnReviewDokumen" class="btn btn-sm btn-primary btn-circle btnReviewDokumen" data-toggle="tooltip" data-placement="top" title="Preview" target="_blank" href="'+base_url_js+'uploads/research/'+response[i]['File_name']+'"><i class="fa fa-eye"></i></a> <a href="javascript:void(0);" id="btnDeleteDokumen" class="btn btn-sm btn-danger btn-circle btnDeleteDokumen" data-toggle="tooltip" data-placement="top" title="Delete" id_dok="'+response[i]['ID_dok']+'" disabled><i class="fa fa-trash"></i></a></td>      '+     
                    '         </tr> ');
                }
                
             }
           
        });
      }


    function loadanggotadosen() {
        var id_pkm = "<?php echo $arr_pkm[0]['ID_PKM']; ?>";

        var url = base_url_js+'abdimas/__loadlist_pkm';
        var token = jwt_encode({action:'detail_loaddosen_pkm', id_pkm : id_pkm},'UAP)(*');

        $.post(url,{token:token},function (data_json) {
            var response = JSON.parse(data_json);
            $('#checkKetuaDosen').val('0');
            if(response.length > 0){
                $('#YesNoDosen').val('Y');
                var no = 1;
                var orbs=0;
                  for (var i = 0; i < response.length; i++) {

                    if (response[i]['Peran'] == "K") {
                        $('#checkKetuaDosen').val('1');
                        var perandosen = 'Ketua';
                    } else {
                        var perandosen = 'Anggota';
                    }

                     if(response[i]['Status_aktif'] == "1") {
                        var checked = '<input type="checkbox" class="radioOke" listID="'+response[i]['ID']+'" name="radios" id="radio2" disabled checked> <label for="radio2"> Aktif</label>';
                    } 
                    else {
                        var checked = '<input type="checkbox" class="radioOke" listID="'+response[i]['ID']+'" name="radios" id="radio2" disabled> <label for="radio2">Aktif</label>';
                    }


                    $("#dataRowdosen").append('<tr>                                                          '+
                    '            <td> '+response[i]['NIP']+' - '+response[i]['Nama']+' </td>                           '+                             
                    '            <td style="text-align: center;"> '+perandosen+'</td>                                                '+    
                    '            <td style="text-align: center;">'+checked+'</td>                                              '+                               
                    '            <td style="text-align: center;"> <a href="javascript:void(0);" id="btnDeleteDosen" class="btn btn-sm btn-danger btn-circle btnDeleteDosen" data-toggle="tooltip" data-placement="top" title="Delete" id_angdosen="'+response[i]['ID']+'" disabled><i class="fa fa-trash"></i></a></td>      '+     
                    '         </tr> ');
                }
                
             }
            setTimeout(function () {
                //$('#loadtablefiles1').html(resultJson);
            },500)
        });
    }


    function loadanggotamahasiswa() {

        var id_pkm = "<?php echo $arr_pkm[0]['ID_PKM']; ?>";

        var url = base_url_js+'abdimas/__loadlist_pkm';
        var token = jwt_encode({action:'detail_loadmahasiswa_pkm', id_pkm : id_pkm},'UAP)(*');

        $.post(url,{token:token},function (data_json) {
            var response = JSON.parse(data_json);
            $('#checkKetuaMahasiwa').val('0');
            
            if(response.length > 0){
                $('#YesNomahasiswa').val('Y');
                var no = 1;
                var orbs=0;
                  for (var i = 0; i < response.length; i++) {

                    if (response[i]['Peran'] == "K") {
                        $('#checkKetuaMahasiwa').val('1');
                        var peranmahasiswa = 'Ketua';
                    } else {
                        var peranmahasiswa = 'Anggota';
                    }

                    if(response[i]['Status_aktif'] == "1") {
                        var checked = '<input type="checkbox" class="radioMhs" listID="'+response[i]['ID']+'" name="radios" id="radiox" disabled checked> <label> Aktif</label>';
                    } 
                    else {
                        var checked = '<input type="checkbox" class="radioMhs" listID="'+response[i]['ID']+'" name="radios" id="radiox" disabled> <label> Aktif</label>';
                    }

                    $("#dataRowmahasiswa").append('<tr>                                                          '+
                    '            <td> '+response[i]['NIM']+' - '+response[i]['Nama']+' </td>                           '+                             
                    '            <td style="text-align: center;"> '+peranmahasiswa+'</td>                                                '+    
                    '            <td style="text-align: center;">'+checked+'</td>                                               '+                               
                    '            <td style="text-align: center;"> <a href="javascript:void(0);" id="btnDeleteMahasiswa" class="btn btn-sm btn-danger btn-circle btnDeleteMahasiswa" data-toggle="tooltip" data-placement="top" title="Delete" id_angmahasiswa="'+response[i]['ID']+'" disabled><i class="fa fa-trash"></i></a></td>      '+     
                    '         </tr> ');
                }
             }
            setTimeout(function () {
               
            },500)
        });
    }

    function loadanggotareviewer() {
        var id_pkm = "<?php echo $arr_pkm[0]['ID_PKM']; ?>";
        var url = base_url_js+'abdimas/__loadlist_pkm';
        var token = jwt_encode({action:'e_loadreviewer_pkm', id_pkm : id_pkm},'UAP)(*');

        $.post(url,{token:token},function (data_json) {
            var response = JSON.parse(data_json);
            
            if(response.length > 0){
                //$('#YesNoKolaborator').val('Y');
                var no = 1;
                var orbs=0;
                  for (var i = 0; i < response.length; i++) {
                    var email = (response[i]['Email'] != '' && response[i]['Email'] != null) ? response[i]['Email'] : '';

                    if(response[i]['Luar_internal'] == "1") {
                      var divisi = "Internal";
                    } 
                    else {
                      var divisi = "Eksternal";
                    }

                    $("#dataRowReviewer").append('<tr>                                                  '+
                    '            <td> '+response[i]['Nama']+' </td>                                     '+                             
                    '            <td style="text-align: center;"> '+divisi+' </td>            '+    
                    '            <td style="text-align: center;">'+''+'</td>                 '+   // dari portal eksternal
                    '            <td style="text-align: center;"><a href="javascript:void(0);" class="btn btn-sm btn-danger btn-circle btnDeleteReviewer" data-toggle="tooltip" data-placement="top" title="Delete" id_reviewer="'+response[i]['ID']+'" disabled><i class="fa fa-trash"></i></a></td>                                                            '+     
                    '         </tr> ');
                  }
             }

            setTimeout(function () {
            },500)
        });
    }

    function loadanggotkolaborator() {
        var id_pkm = "<?php echo $arr_pkm[0]['ID_PKM']; ?>";

        var url = base_url_js+'abdimas/__loadlist_pkm';
        var token = jwt_encode({action:'detail_loadkolaborator_pkm', id_pkm : id_pkm},'UAP)(*');

        $.post(url,{token:token},function (data_json) {
            var response = JSON.parse(data_json);
             $('#checkKetuaKolaborator').val('0');

            if(response.length > 0){
                $('#YesNoKolaborator').val('Y');
                var no = 1;
                var orbs=0;
                  for (var i = 0; i < response.length; i++) {
                    //----------------

                    if (response[i]['Peran'] == "K") {
                        $('#checkKetuaKolaborator').val('1');
                        var perankolab = 'Ketua';
                    } else if(response[i]['Peran'] == "A") {
                        var perankolab = 'Anggota';
                    }
                    else {
                       var perankolab = "";
                    }

                    if(response[i]['Status_aktif'] == "1") {
                        var checked = '<input type="checkbox" class="radioKBR" listID="'+response[i]['ID']+'" name="radios" id="radio3" checked disabled> <label for="radio3"> Aktif</label>';
                    } 
                    else {
                        var checked = '<input type="checkbox" class="radioKBR" listID="'+response[i]['ID']+'" name="radios" id="radio3" disabled> <label for="radio3">Aktif</label>';
                    }

                    $("#dataRowKolaborator").append('<tr>                                                          '+
                    '            <td> '+response[i]['Name_kolaborator']+' </td>                           '+                             
                    '            <td style="text-align: center;"> '+perankolab+'</td>               '+    
                    '            <td style="text-align: center;">'+checked+'</td>                         '+                         
                    '            <td style="text-align: center;"> <a href="javascript:void(0);" id="btnDeleteKolaborator" class="btn btn-sm btn-danger btn-circle btnDeleteKolaborator" data-toggle="tooltip" data-placement="top" title="Delete" id_kolaborator="'+response[i]['ID']+'" disabled><i class="fa fa-trash"></i></a></td>      '+     
                    '         </tr> ');
                  }
             }

            setTimeout(function () {

            },500)
        });
    }

</script>