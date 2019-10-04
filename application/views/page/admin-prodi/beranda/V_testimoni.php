
        <div class="row"> 
            <div class="col-md-12">

              <div class="widget box ">

                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> Data Testimoni Mahasiswa/Alumni</h4>
                    <div class="toolbar no-padding">
                        <div class="btn-group">
                          <span data-smt="" class="btn btn-xs btn-add">
                            <a href="" id="btn-tambah" data-toggle="modal" data-target="#form-modal">
                            <i class="icon-plus"></i> Add Testimoni
                            </a>
                           </span>
                        </div>
                    </div>
                </div>

                <div id="view" class="widget-content">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <tr>
                        <th class="text-center">NO</th>
                        <th>IMAGES</th>
                        <th>TESTIMONI</th>
                        <th>CREATED BY</th>
                        <th>CREATED AT</th>
                        <th colspan="2" class="text-center"><span class="glyphicon glyphicon-cog"></span></th>
                      </tr>
                      <tbody id="showTestimoni">                    
                      </tbody>
                        
                      
                    </table>
                  </div>

                </div>

            </div>
        </div>

        
<!-- ====== modal form slider =======-->
<!-- ======= tambah slide ======== -->
  <div class="modal fade in" id="form-modal" role="dialog" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content animated jackInTheBox">
            <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title"><span id="modal-title">Form Input</span></h4></div>
            <div class="modal-body">
              <div class="row"> 
              <!-- Beri id "pesan-error" untuk menampung pesan error -->
                    <!-- <div id="pesan-error" class="alert alert-danger"></div>   -->     
                <div class="col-md-12">            
                  <form class="form-horizontal row-border" id="submit" action="#" method="post" enctype = "multipart / form-data">
                    <div class="thumbnail">
                        <div id="imagePreview1" style="margin-bottom:7.5px;"></div>  
                        <div class="caption">
                          <label class="control-label">Title Slider:</label>

                          <input type="text"  id="formTitle1" class="form-control" placeholder="Title Slide Show"><br>

                         <div class="custom-file-input " style="position:relative; left:0px;">
                          <i class="fa fa-file-image-o"></i>&nbsp; &nbsp;Browse<input  id="uploadFile1"  type="file" value=""/>
                         </div>              
                        
                       
                         <label><br>
                         <input type="checkbox" id="formStatus1"> Show button registrasi
                         </label><br>
                          <div class="from-group" id="showCheck">
                          <label class="control-label">Name Button:</label>
                          <input type="text"  id="formButtonName1" class="form-control" placeholder="Name Button"><br>
                          <label class="control-label">Url:</label>
                          <input  type="text"  id="formUrl1" class="form-control" placeholder="http://example.com"><br>
                          </div>
                         
                        </div>
                      </div>
                    
  
                  <button type="submit" id="btn-simpan" class="btn btn-primary btnsave1" style="margin-top: 15px">Simpan</button>
                  <!-- <button type="submit" id="btn-ubah" class="btn btn-primary" style="margin-top: 15px">Ubah</button> -->
            </form>    
                </div>    
              </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<!-- ======= Hapus slide ======== -->
<div id="delete-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">
                        Konfirmasi
                    </h4>
                </div>
                <div class="modal-body">
                    Apakah anda yakin ingin menghapus data ini?
                </div>
                <div class="modal-footer">
                    <!-- Beri id "loading-hapus" untuk loading ketika klik tombol hapus -->
                    <div id="loading-hapus" class="pull-left">
                        <b>Sedang meghapus...</b>
                    </div>
                    <!-- Beri id "btn-hapus" untuk tombol hapus nya -->
                    <button type="button" class="btn btn-primary" id="btn-hapus">Ya</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>

    <script>
  
    $(document).ready(function(){
        viewTestimoni();
    });

// views data === ///
      function viewTestimoni(){

        var data = {action : 'viewDataTestimoni'};
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js + "api-prodi/__crudDataProdi"
        
        $.ajax({
                type  : 'POST',
                url:url,
                data: data,
                async : false,
                dataType : 'json',
                success : function(data){
                  var html = '';
                  var i;
                  for(i=0; i<data.length; i++){
                            html += '<tr id="'+data[i].ID+'">'+
                                '<td>'+data[i].Images+'</td>'+
                                '<td>'+data[i].Description+'</td>'+
                                '<td>'+data[i].CreatedBY+'</td>'+                            
                                '<td>'+data[i].CreatedAT+'</td>'+
                                '<td style="text-align:right;">'+
                                  '<a href="javascript:void(0);" class="btn btn-info btn-sm editRecord" data-id="'+data[i].ID+'" data-name="'+data[i].CreatedBY+'" data-createdat="'+data[i].CreatedAT+'" data-description="'+data[i].Description+'" data-images="'+data[i].Images+'" data-jurusan="'+data[i].Jurusan+'">Edit</a>'+' '+
                                  '<a href="javascript:void(0);" class="btn btn-danger btn-sm deleteRecord" data-id="'+data[i].ID+'">Delete</a>'+
                                '</td>'+
                                '</tr>';
                  }
                  $('#showTestimoni').html(html);         
                }
              });
      }

  </script>


