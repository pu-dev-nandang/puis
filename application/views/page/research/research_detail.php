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
</style>

<div class="container">
  <div class="panel panel-default">
    <div class="panel-heading panel-heading-custom">Detail data Penelitian</div>
    <div class="table-responsive">
      <table class="table table-striped">
         <tbody>
            <tr>
               <td colspan="1" style="width: 40%;">
                  <form class="well form-horizontal">
                     <fieldset>

                      <div class="form-group">
                          <label class="col-md-4 control-labels">Jenis Usulan <span style="color: red;">*</span></label>
                           <div class="col-md-8 inputGroupContainer">
                              <div class="input-group"><span class="input-group-addon"><i class="fa fa fa-book"></i></span>
                                 <select class="form-control" id="jenis_usulan">
                                    <option id="<?php echo $arr_research[0]['Jenis_usulan']; ?>" disabled selected> <?php echo $arr_research[0]['Jenis_usulan']; ?></option>
                                 </select>
                             </div>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-md-4 control-labels">Kategori Kegiatan</label>
                           <div class="col-md-8 inputGroupContainer">
                              <div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                               <?php if($arr_research[0]['Peran'] == '120601') {
                                  echo '<input class="form-control" value = "Melaksanakan aktivitas penelitian sebagai Ketua" disabled></input>';
                               } else {
                                   echo '<input class="form-control" value="Melaksanakan aktivitas penelitian sebagai Anggota" disabled></input>';
                               }
                               ?>
                             </div>
                           </div>
                        </div>
                    <div class="form-group">
                      <label class="col-md-4 control-labels">Judul Kegiatan <span style="color: red;">*</label>
                      <div class="col-md-8 inputGroupContainer">
                        <div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                          
                          <textarea style="resize: none;" id="bentuk_integrasi" class="form-control" rows="3" disabled> <?php echo $arr_research[0]['Judul_litabmas']; ?></textarea> 

                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-4 control-labels">Afiliasi <span style="color: red;">*</span></label>
                      <div class="col-md-8 inputGroupContainer">
                         <div class="input-group"><span class="input-group-addon"><i class="fa fa-university"></i></span>
                            <select class="form-control form-exam element-style">
                                <option value="<?php echo $arr_research[0]['ID_lemb_iptek']; ?>" disabled selected> <?php echo $arr_research[0]['Name_University']; ?></option>
                            </select>
                        </div>
                      </div>
                   </div>
                    <div class="form-group">
                      <label class="col-md-4 control-labels">Kelompok Bidang</label>
                      <div class="col-md-8 inputGroupContainer">
                         <div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                            <select class="form-control form-exam element-style">
                                <option value="<?php echo $arr_research[0]['ID_kel_bidang']; ?>" disabled="disabled" selected> <?php echo $arr_research[0]['Nm_kel_bidang']; ?></option>
                            </select>
                        </div> 
                      </div>
                   </div>
                   <div class="form-group">
                      <label class="col-md-4 control-labels">Litabmas Sebelumnya</label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-book"></i></span>
                              <select class="form-control form-exam element-style">
                                <option value="<?php echo $arr_research[0]['ID_lanjutan_litabmas']; ?>" disabled="disabled" selected> <?php echo $arr_research[0]['judul_lanjutan']; ?>
                                  
                                </option>
                              </select>
                          </div>
                      </div>
                   </div>

                  <div class="form-group">
                    <label class="col-md-4 control-labels">Jenis SKIM </label>
                      <div class="col-md-8 inputGroupContainer">
                         <div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                            <select class="form-control form-exam element-style">
                                <option value="<?php echo $arr_research[0]['ID_skim']; ?>" disabled="disabled" selected> <?php echo $arr_research[0]['Nm_skim']; ?></option>
                            </select>
                        </div> 
                      </div>
                   </div>
                   <div class="form-group">
                      <label class="col-md-4 control-labels">Lokasi Kegiatan</label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                            <input id="lokasi_kegiatan" name="lokasi_kegiatan" class="form-control" value="<?php echo $arr_research[0]['Lokasi_kegiatan']; ?>" disabled>
                          </div>
                      </div>
                   </div>
                   <div class="form-group">
                      <label class="col-md-4 control-labels"> Tahun Akademik <span style="color: red;"> * </span> </label>
                          <div class="col-md-8 inputGroupContainer">
                              <div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar-check-o"></i></span>
                                <select id="filter_tahunakademik" class="form-control form-exam element-style filter_tahunakademik">
                                  <option id="<?php echo $arr_research[0]['NmThn_akademik']; ?>" disabled selected> 
                                      <?php echo $arr_research[0]['NmThn_akademik']; ?> 
                                  </option> 
                                </select>
                              </div>
                      </div>
                   </div>
                    <div class="form-group">
                      <label class="col-md-4 control-labels">Tahun Usulan <span style="color: red;">*</span></label>
                      <div class="col-md-8 inputGroupContainer">
                         <div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                           <select class="form-control element-style">
                              <option id="<?php echo $arr_research[0]['ID_thn_usulan']; ?>" selected disabled> <?php echo $arr_research[0]['ID_thn_usulan']; ?></option>
                           </select>  
                         </div>
                      </div>
                   </div>
                   <div class="form-group">
                      <label class="col-md-4 control-labels">Tahun Kegiatan <span style="color: red;">*</span></label>
                      <div class="col-md-8 inputGroupContainer">
                         <div class="input-group">
                          <span class="input-group-addon" style="max-width: 100%;"><i class="fa fa-calendar"></i></span>
                           <select class="form-control form-exam element-style">
                              <option id="<?php echo $arr_research[0]['ID_thn_kegiatan']; ?>" selected disabled> <?php echo $arr_research[0]['ID_thn_kegiatan']; ?> </option>
                           </select> 
                         </div>
                      </div>
                   </div>
                   <div class="form-group">
                      <label class="col-md-4 control-labels">Tahun Pelaksanaan <span style="color: red;">*</span></label>
                      <div class="col-md-8 inputGroupContainer">
                         <div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                           <select id="thn_pelaksanaan" class="form-control form-exam element-style">
                              <option id="<?php echo $arr_research[0]['ID_thn_laks']; ?>" selected disabled> <?php echo $arr_research[0]['ID_thn_laks']; ?></option>
                           </select> 
                          </div>
                      </div>
                   </div>
                        <div class="form-group">
                      <label class="col-md-4 control-labels">Lama Kegiatan<span style="color: red;">*</span></label>
                      <div class="col-md-4 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar-plus-o"></i></span>
                           <input id="lama_kegiatan" name="lama_kegiatan" class="form-control" value="<?php echo $arr_research[0]['Lama_kegiatan']; ?>" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="2" disabled>
                          </div>
                      </div>
                      <div class="col-md-4 inputGroupContainer">
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <select id="option_waktu" class="form-control form-exam element-style option_waktu">
                                    <option id="<?php echo $arr_research[0]['Lama_waktu']; ?>" disabled selected> <?php echo $arr_research[0]['Lama_waktu']; ?> </option>
                                </select>
                          </div>
                      </div>

                   </div>
                   <div class="form-group">
                      <label class="col-md-4 control-labels">Tahun Pelaksanaan Ke <span style="color: red;">*</span></label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar-plus-o"></i></span>
                            <input id="thn_laks_ke" name="thn_laks_ke" class="form-control" required="true" value="<?php echo $arr_research[0]['Thn_laks_ke']; ?>" onkeypress="return event.charCode >= 48 && event.charCode <= 57" disabled>
                          </div>
                      </div>
                   </div>
                   <div class="form-group">
                      <label class="col-md-4 control-labels"> Sumber Pembiayaan <span style="color: red;">*</span></label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>
                            <select id="sumber_pembiayaan" class="form-control form-exam element-style">
                            <option id="<?php echo $arr_research[0]['ID_sumberdana']; ?>" disabled selected> <?php echo $arr_research[0]['SumberDana']; ?> </option></select>
                          </div>
                      </div>
                   </div>
                        <div class="form-group">
                      <label class="col-md-4 control-labels">Dana dari Dikti (Rp.) <span style="color: red;">*</span></label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>
                            <input id="dana_dikti" name="dana_dikti" class="form-control" value="<?php echo number_format($arr_research[0]['Dana_dikti'], 0, ".", "."); ?>" disabled>
                          </div>
                      </div>
                   </div>
                   <div class="form-group">
                      <label class="col-md-4 control-labels">Dana dari Perguruan Tinggi (Rp.) <span style="color: red;">*</span></label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>
                            <input id="dana_pt" name="dana_pt" class="form-control" value="<?php echo number_format($arr_research[0]['Dana_pt'], 0, ".", "."); ?>" disabled>
                          </div>
                      </div>
                   </div>
                   <div class="form-group">
                      <label class="col-md-4 control-labels">Dana dari Institusi Lain (Rp.) <span style="color: red;">*</span></label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>
                            <input id="dana_institusi_lain" name="dana_institusi_lain" class="form-control" value="<?php echo number_format($arr_research[0]['Dana_institusi_lain'], 0, ".", "."); ?>" disabled>
                          </div>
                      </div>
                   </div>
                        <div class="form-group">
                      <label class="col-md-4 control-labels">Dana dari Lembaga Luar Negeri (Rp.) </label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-money"></i></span>
                            <input id="dana_luar_negeri" name="dana_luar_negeri" class="form-control" value="<?php echo number_format($arr_research[0]['Dana_lembaga_luarnegeri'], 0, ".", "."); ?>" disabled>
                          </div>
                      </div>
                   </div>
                   <div class="form-group">
                      <label class="col-md-4 control-labels">In Kind </label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-cube"></i></span>
                            <input id="in_kind" name="in_kind" class="form-control" value="<?php echo $arr_research[0]['In_kind']; ?>" disabled>
                          </div>
                      </div>
                   </div>
                   <div class="form-group">
                      <label class="col-md-4 control-labels">Nomor SK Penugasan</label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
                            <input id="sk_tugas" name="sk_tugas" class="form-control" value="<?php echo $arr_research[0]['Sk_tugas']; ?>" disabled>
                          </div>
                      </div>
                   </div>
                        <div class="form-group">
                      <label class="col-md-4 control-labels">Tanggal SK Penugasan </label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar-plus-o"></i></span>
                              <?php if($arr_research[0]['Tgl_sk_tugas'] == '0000-00-00'){ ?>
                                <input class="form-control" id="tgl_sktugas" name="tgl_sktugas" class="form-control" disabled>
                              <?php }else{ ?>
                                <option class="form-control" id="tgl_sktugas" selected="true" disabled="disabled"> <?php echo $arr_research[0]['Tgl_sk_tugas']; ?></option>
                              <?php } ?>
                          </div>
                      </div>
                   </div>
                   <div class="form-group">
                      <label class="col-md-4 control-labels">Mitra Litabmas </label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                            <input id="mitra_litabmas" name="mitra_litabmas" class="form-control" value="<?php echo $arr_research[0]['Mitra_litabmas']; ?>" disabled>
                          </div>
                      </div>
                   </div>
                 <span style="color: red;">( ** ) Penelitian yang terintegrasi dengan matakuliah</span>
                   <div class="form-group">
                      <label class="col-md-4 control-labels"> Tahun Kurikulum <span style="color: red;">**</span> </label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar-check-o"></i></span>
                            <select id="filterkurikulum" class="form-control form-exam element-style"><option id="<?php echo $arr_research[0]['SemesterID']; ?>" selected disabled><?php echo $arr_research[0]['NamaSemester']; ?></option></select>
                          </div>
                      </div>
                   </div>

                   <div class="form-group">
                      <label class="col-md-4 control-labels"> Nama Mata Kuliah <span style="color: red;">**</span> </label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-file"></i></span>
                            <select id="filtermatakuliah" class="form-control form-exam element-style"><option value="<?php echo $arr_research[0]['MKCode']; ?>" disabled selected><?php echo $arr_research[0]['NameEng']; ?></option></select>
                          </div>
                      </div>
                   </div>

                   <div class="form-group">
                      <label class="col-md-4 control-labels"> Bentuk Integrasi <span style="color: red;">**</span></label>
                      <div class="col-md-8 inputGroupContainer">
                          <div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-retweet"></i></span>
                            <textarea id="bentuk_integrasi" class="form-control" rows="3"> <?php echo $arr_research[0]['Bentuk_integrasi']; ?></textarea> 
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
                                 <div class="panel-heading" style="background-color: #D5D8DC;">                               
                                     <h4 class="panel-title"><i class="fa fa-upload"></i> <b> Upload Dokumen</b> </h4>           
                                 </div> 
                                 <div class="panel-body">
                                    <div class=""> 
                                       <table class="table table-bordered table-striped" id="tableversion">
                                           <thead>                                  
                                           <tr style="background: #3968c6;color: #FFFFFF;"> 
                                                <th style="width: 10%;text-align: center;">Nama Document</th>          
                                                <th style="width: 10%;text-align: center;">Jenis Document</th>   
                                                <th style="width: 15%;text-align: center;">Keterangan</th>             
                                                <th style="width: 15%;text-align: center;">Tautan Document</th>       
                                                <th style="width: 8%;text-align: center;">File</th>   
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
                                           <div class="pull-right"></div>
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
                                                <th style="text-align: center;width: 8%;">Realisasi Biaya</th> 
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

                      <!-- table Anggota Dosen --> 
                      <div class="row" style="margin-top: 0px;">  
                         <div class="col-md-12">                                          
                            <div class="panel panel-default">                             
                                 <div class="panel-heading" style="background-color: #D5D8DC;">                               
                                     <h4 class="panel-title"><i class="fa fa-upload"></i> <b> Anggota Kegiatan (Dosen)</b> 
                                         
                                     </h4>      
                              </div> 
                                 <div class="panel-body"> 
                                   <div class=""> 
                                 <table class="table table-bordered table-striped" id="tablelistdosen"> 
                                     <thead>                                  
                                     <tr style="background: #3968c6;color: #FFFFFF;"> 
                                          <th style="width: 20%;text-align: center;">Nama Dosen</th>         
                                          <th style="width: 10%;text-align: center;">Peran</th>   
                                          <th style="width: 6%;text-align: center;">Status</th>          
                                          <th style="width: 8%;text-align: center;">Action</th>  
                                     </tr> 
                                     </thead> 
                                   <tbody id="dataRowdosen"></tbody>  
                                 </table> 
                              
                             </div>            
                                 </div> 
                             </div> 
                         </div> 
                        </div> 
                      <!-- table Anggota Dosen --> 
                      <div class="row" style="margin-top: 0px;">   
                          <div class="col-md-12">                                          
                             <div class="panel panel-default">                             
                                 <div class="panel-heading" style="background-color: #D5D8DC;">                               
                                     <h4 class="panel-title"><i class="fa fa-upload"></i> <b> Anggota Kegiatan (Mahasiswa)</b> </h4>             
                              </div> 
                                 <div class="panel-body"> 
                                 <div class=""> 
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
                        <!-- table list anggota Mahasiswa -->  

                      <!-- table list anggota Kolaborator -->
                      <div class="row" style="margin-top: 0px;">   
                          <div class="col-md-12">                                          
                             <div class="panel panel-default">                             
                                 <div class="panel-heading" style="background-color: #D5D8DC;">                               
                                     <h4 class="panel-title"><i class="fa fa-upload"></i> <b> Anggota Kegiatan (Kolaborator Eksternal)</b> </h4>   
                              </div> 
                                 <div class="panel-body"> 
                                   <div class=""> 
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
                        <!-- table list anggota Kolaborator -->

                        <div class="row" style="margin-top: 0px;">   
                          <div class="col-md-12">                                          
                             <div class="panel panel-default">                             
                                  <div class="panel-heading" style="background-color: #D5D8DC;">                               
                                     <h4 class="panel-title"><i class="glyphicon glyphicon-user"></i> <b> Anggota Reviewer</b> 
                                         <div class="pull-right">                                                
                                            
                                         </div> 
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
                                                      <th style="width: 10%;text-align: center;">Tanggal Persetujuan</th>         
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
                            <a type="button" class="btn btn-primary btn-round" href="<?php echo base_url('research/monitoring-research') ?>"> <i class="fa fa-close"></i> Kembali</a>
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

	  loadTahunLahir();
	  loadFileDokumen();
	  loadanggotadosen();
	  loadanggotamahasiswa();
	  loadanggotkolaborator();
	  loadanggotareviewer();
	  load_isilaporan();
	  loadDataAnggaranBiaya();

	});


	$( "#tgl_sktugas" )
	    .datepicker({
	        dateFormat: 'yyyy-MM-dd',
	        changeYear: true,
	        changeMonth: true
	    });
	

	function loadTahunLahir() {
	  var thisYear = (new Date()).getFullYear();
	  var startTahun = parseInt(thisYear) - parseInt(5);
	  var selisih =  parseInt(thisYear) - parseInt(startTahun) + parseInt(5);
	
	  for (var i = 0; i <= selisih; i++) {
	      //var selected = (i==0) ? 'selected' : '';
	      $('#thn_usulan').append('<option id="'+ ( parseInt(startTahun) + parseInt(i) ) +'" >'+( parseInt(startTahun) + parseInt(i) )+'</option>');
	      $('#thn_kegiatan').append('<option id="'+ ( parseInt(startTahun) + parseInt(i) ) +'" >'+( parseInt(startTahun) + parseInt(i) )+'</option>');
	      $('#thn_pelaksanaan').append('<option id="'+ ( parseInt(startTahun) + parseInt(i) ) +'" >'+( parseInt(startTahun) + parseInt(i) )+'</option>');
	  }
	}


	function loadFileDokumen() {

	     var id = "<?php echo $arr_research[0]['ID_litabmas']; ?>";

	     var data = {
	             action : 'edit_loadupload',
	             id : id
	         };
	     var url = base_url_js+'research/__loadResearch';
	     var token = jwt_encode(data,'UAP)(*');

	     $.post(url,{token:token},function (data_json) {
	         var response = JSON.parse(data_json);

	         if(response.length > 0){
	             var no = 1;
	             var orbs=0;
	               for (var i = 0; i < response.length; i++) {

	                 var viewURL = (response[i]['Url'].length>50) ? response[i]['Url'].substring(0,50)+'.....' : response[i]['Url'];

	                 $("#dataRowUpload").append('<tr>                                                          '+
	                 '            <td> '+response[i]['Nm_dok']+' </td>                           '+                             
	                 '            <td style="text-align: center;"> '+response[i]['Nm_jns_dok']+'</td>                   '+    
	                 '            <td>'+response[i]['Ket_dok']+'</td>                                              '+    
	                 '           <td><a href="'+response[i]['Url']+'" target="_blank">'+viewURL+'</a></td>         '+                              
	                 '            <td style="text-align: center;"><a id="btnReviewDokumen" class="btn btn-sm btn-primary btn-circle btnReviewDokumen" data-toggle="tooltip" data-placement="top" title="Preview" target="_blank" href="'+base_url_js+'uploads/research/'+response[i]['File_name']+'"><i class="fa fa-eye"></i></a> <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-circle" data-toggle="tooltip" data-placement="top" title="Delete" id_dok="'+response[i]['ID_dok']+'" disabled><i class="fa fa-trash"></i></a></td>      '+     
	                 '         </tr> ');
	             }
	             
	          }
	        
	     });
	   }


   	function loadanggotadosen() {

       var id = "<?php echo $arr_research[0]['ID_litabmas']; ?>";
       var data = {
               action : 'edit_loaddosen',
               id : id
           };
       var url = base_url_js+'research/__loadResearch';
       var token = jwt_encode(data,'UAP)(*');


       $.post(url,{token:token},function (data_json) {
           var response = JSON.parse(data_json);
           
           if(response.length > 0){
               var no = 1;
               var orbs=0;
                 for (var i = 0; i < response.length; i++) {

                   if (response[i]['Peran'] == "K") {
                       var perandos = 'Ketua';
                   } else if(response[i]['Peran'] == "A") {
                       var perandos = 'Anggota';
                   }
                   else {
                      var perandos = "";
                   }

                   var perandosen = '<select data-no="'+i+'" class="form-control table_peranKBR_pkm" id="table_peranKBR_pkm" listID="'+response[i]['ID']+'">' +
                     '                       <option id=""'+response[i]['Peran']+'"" disabled selected> '+perandos+' </option>    ' +
                     '               </select> ';

                   if(response[i]['Status_aktif'] == "1") {
                       var checked = '<input type="checkbox" name="radios" id="radio3" checked disabled> <label for="radio3"> Aktif</label>';
                   } 
                   else {
                       var checked = '<input type="checkbox" name="radios" id="radio3" disabled> <label for="radio3">Aktif</label>';
                   }

                   $("#dataRowdosen").append('<tr>                                                          '+
                   '            <td> '+response[i]['NIP']+' - '+response[i]['Nama']+' </td>                           '+                             
                   '            <td style="text-align: center;"> '+perandosen+'</td>                                                '+    
                   '            <td style="text-align: center;">'+checked+'</td>                                              '+                               
                   '            <td style="text-align: center;"> <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-circle" data-toggle="tooltip" data-placement="top" title="Delete" id_angdosen="'+response[i]['ID']+'" disabled><i class="fa fa-trash"></i></a></td>      '+     
                   '         </tr> ');
               }
            }
           setTimeout(function () {
               //$('#loadtablefiles1').html(resultJson);
           },500)
       });
   	}


	function loadanggotamahasiswa() {

        var id = "<?php echo $arr_research[0]['ID_litabmas']; ?>";

        var data = {
                action : 'edit_loadmahasiswa',
                id : id
            };
        var url = base_url_js+'research/__loadResearch';
        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (data_json) {
            var response = JSON.parse(data_json);
            
            if(response.length > 0){
                var no = 1;
                var orbs=0;
                  for (var i = 0; i < response.length; i++) {

                    if (response[i]['Peran'] == "K") {
                        var peranmhs = 'Ketua';
                    } else if(response[i]['Peran'] == "A") {
                        var peranmhs = 'Anggota';
                    }
                    else {
                       var peranmhs = "";
                    }

                    var peranmahasiswa = '<select data-no="'+i+'" class="form-control table_peranKBR_pkm" id="table_peranKBR_pkm" listID="'+response[i]['ID']+'">' +
                      '                       <option id=""'+response[i]['Peran']+'"" disabled selected> '+peranmhs+' </option>    ' +
                      '               </select> ';

                    if(response[i]['Status_aktif'] == "1") {
                        var checked = '<input type="checkbox" name="radios" id="radio3" checked disabled> <label for="radio3"> Aktif</label>';
                    } 
                    else {
                        var checked = '<input type="checkbox" name="radios" id="radio3" disabled> <label for="radio3">Aktif</label>';
                    }

                    $("#dataRowmahasiswa").append('<tr>                                                          '+
                    '            <td>  '+response[i]['NIM']+' - '+response[i]['Nama']+' </td>                           '+                             
                    '            <td style="text-align: center;"> '+peranmahasiswa+'</td>                                                '+    
                    '            <td style="text-align: center;">'+checked+'</td>                                               '+                      
                    '            <td style="text-align: center;"> <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-circle" data-toggle="tooltip" data-placement="top" title="Delete" id_angmahasiswa="'+response[i]['ID']+'" disabled><i class="fa fa-trash"></i></a></td>      '+     
                    '         </tr> ');
                }
                
             }

            setTimeout(function () {
                //$('#loadtablefiles1').html(resultJson);
            },500)
        });
    }


    function loadanggotkolaborator() {
        var id = "<?php echo $arr_research[0]['ID_litabmas']; ?>";
        var data = {
                action : 'edit_loadkolaborator',
                id_litabmas : id
            };
        var url = base_url_js+'research/__loadResearch';
        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (data_json) {
            var response = JSON.parse(data_json);

            if(response.length > 0){
                var no = 1;
                var orbs=0;
                  for (var i = 0; i < response.length; i++) {

                    if (response[i]['Peran'] == "K") {
                        var perankolab = 'Ketua';
                      } 
                        else if(response[i]['Peran'] == "A") {
                          var perankolab = 'Anggota';
                    }
                    else {
                       var perankolab = "";
                    }

                    var perankolaborator = '<select data-no="'+i+'" class="form-control table_peranKBR_pkm" id="table_peranKBR_pkm" listID="'+response[i]['ID']+'">' +
                      '                       <option id=""'+response[i]['Peran']+'"" disabled selected> '+perankolab+' </option>    ' +
                      '               </select> ';

                    if(response[i]['Status_aktif'] == "1") {
                        var checked = '<input type="checkbox" name="radios" id="radio3" checked disabled> <label for="radio3"> Aktif</label>';
                    } 
                    else {
                        var checked = '<input type="checkbox" name="radios" id="radio3" disabled> <label for="radio3">Aktif</label>';
                    }

                    $("#dataRowKolaborator").append('<tr>                                                          '+
                    '            <td> '+response[i]['Name_kolaborator']+' </td>                           '+                             
                    '            <td style="text-align: center;"> '+perankolaborator+'</td>                                                '+    
                    '            <td style="text-align: center;">'+checked+'</td>       '+                         
                    '            <td style="text-align: center;"> <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-circle" data-toggle="tooltip" data-placement="top" title="Delete" id_kolaborator="'+response[i]['ID']+'" disabled><i class="fa fa-trash"></i></a></td>      '+     
                    '         </tr> ');
                  }
             }
            setTimeout(function () {
            },500)
        });
    }


    function loadanggotareviewer() {

        var id_litabmas = "<?php echo $arr_research[0]['ID_litabmas']; ?>";
        var url = base_url_js+'research/__loadResearch';

        var token = jwt_encode({action:'e_loadreviewer_research',  
                                id_litabmas : id_litabmas 
                            },'UAP)(*');

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
                    '            <td style="text-align: center;"> '+divisi+' </td>        '+    
                    '            <td style="text-align: center;">'+''+'</td>                 '+   // dari portal eksternal
                    '            <td style="text-align: center;"> '+''+' </td>               '+    // dari portal eksternal                    
                    '            <td style="text-align: center;"><a href="javascript:void(0);" id="btnDeleteReviewer" class="btn btn-sm btn-danger btn-circle" data-toggle="tooltip" data-placement="top" title="Delete" id_reviewer="'+response[i]['ID']+'" disabled><i class="fa fa-trash"></i></a></td>                                                            '+     
                    '         </tr> ');
                  }
             }

            setTimeout(function () {

            },500)
        });

    }


    function load_isilaporan() {

      var id_litabmas = "<?php echo $arr_research[0]['ID_litabmas']; ?>";
      var url = base_url_js+'research/__loadResearch';
      var token = jwt_encode({
            action:'e_loadformat_isilaporan', 
            id_litabmas : id_litabmas
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
                    '            <td style="text-align: center;"><a type="button" class="btn btn-primary btn-round btnformatlap" listID="'+response[i]['ID']+'" stat_id="1" ><i class="fa fa-eye"></i> Preview Laporan</a></td>                              '+    
                    '            <td style="text-align: center;"> '+usercreate+'</td>                    '+                         
                    '            <td style="text-align: center;"> <a href="javascript:void(0);" id="btnDeleteLaporan" class="btn btn-sm btn-danger btn-circle" data-toggle="tooltip" data-placement="top" title="Delete" id_laporan="'+response[i]['ID']+'" disabled><i class="fa fa-trash"></i></a></td>      '+     
                    '         </tr> ');
                  }
             }

            setTimeout(function () {
            },500)
        });
    }


    function loadDataAnggaranBiaya() {

      var id_litabmas = "<?php echo $arr_research[0]['ID_litabmas']; ?>";
      var url = base_url_js+'research/__loadResearch';
      var token = jwt_encode({action:'e_loadbiayaresearch', id_litabmas : id_litabmas}, 'UAP)(*');

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
                    '       <td style="text-align: center;">'+datarealisasi+'</td>          '+  
                    '       <td style="text-align: center;"><a href="javascript:void(0);" id="btnDeleteBiaya" class="btn btn-sm btn-danger btn-circle" data-toggle="tooltip" data-placement="top" title="Delete" id_biaya="'+response[i]['ID']+'" disabled><i class="fa fa-trash"></i></a></td>      '+     
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
                  '<tfoot id="tfootbiaya">'+
                      '<tr>'+
                          '<td style="text-align: center;" colspan = "3"><b>Total Biaya</b></td>'+
                          '<td style="text-align: center;"><b>'+ribuan+'</b></td>'+
                          '<td style="text-align: center;"><b>'+totreal+'</b></td>'+
                          '<td></td>'+
                      '</tr>'+
                  '</tfoot>'
                );
            }
        });
    }


    $(document).on('click','.btnformatlap',function() {

          var listID = $(this).attr('listID'); 
          var stat_id = $(this).attr('stat_id');
          
          var data = {
                listID : listID,
                stat_id : stat_id
          };
          var token = jwt_encode(data,'UAP)(*');

          window.open(base_url_js_server_ws+'save2pdf/format-laporan-view/'+token);
       });


</script>