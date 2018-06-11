<style type="text/css">
    button, input, select, textarea {
        /*margin: 7px;*/
        font-family: inherit;
        font-size: 100%;
    }
</style>
    <form class="form-horizontal" id="formModal">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 hide" align = 'center' id='msgMENU' style="color: red;">MSG</div>
            </div>   
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Pilih Provinsi:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectProvinsi">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 hide" align = 'center' id='msgMENU' style="color: red;">MSG</div>
            </div>   
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Pilih Region:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectRegion">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 hide" align = 'center' id='msgMENU' style="color: red;">MSG</div>
            </div>   
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Pilih District:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectDistrict">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 hide" align = 'center' id='msgMENU' style="color: red;">MSG</div>
            </div>   
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Pilih Type Sekolah:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectTypeSekolah">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 hide" align = 'center' id='msgMENU' style="color: red;">MSG</div>
            </div>   
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Nama Sekolah:</label>
                </div>    
                <div class="col-sm-6">
                   <input type="text" id="nm_sekolah"  class="form-control" placeholder="SMA N 1 Name">
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Alamat Sekolah:</label>
                </div>    
                <div class="col-sm-6">
                   <input type="text" id="alamat"  class="form-control" placeholder="Alamat Sekolah">
                </div>
            </div>
        </div>
        <div style="text-align: center;">       
    		<div class="col-sm-12" id="BtnFooter">
                <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" id="ModalbtnSaveForm" class="btn btn-success" action = "<?php echo $action ?>" kodeuniq = "<?php echo $id ?>">Save</button>
    		</div>
        </div>    
    </form>
<script type="text/javascript">
    $(document).ready(function () {
        loadSelectOptionWilayah_SMA();
        loadTypeSekolah();
        <?php if ($action == 'edit'): ?>
          $("#nm_sekolah").val('<?php echo $getData[0]['SchoolName'] ?>');
          $("#alamat").val('<?php echo $getData[0]['SchoolAddress'] ?>');
        <?php endif ?>
    });

    function loadSelectOptionWilayah_SMA()
    {
        $("#selectProvinsi").empty();
        var url = base_url_js+'api/__getProvinsi';
        $.get(url,function (data_json) {

            for(var i=0;i<data_json.length;i++){
                var selected = (i==0) ? 'selected' : '';
                //$('#selectWilayah').append('<option value="'+data_json['data'][i].kode_wilayah+'" '+selected+'>'+data_json['data'][i].nama+'</option>');
                $('#selectProvinsi').append('<option value="'+data_json[i].ProvinceID+'" '+selected+'>'+data_json[i].ProvinceName+'</option>');
            }
            $('#selectProvinsi').select2({
               allowClear: true
            });
            <?php if ($action == 'edit'): ?>
               $("#selectProvinsi option").filter(function() {
                  //may want to use $.trim in here
                  return $(this).val() == "<?php echo $getData[0]['ProvinceID'] ?>"; 
                }).prop("selected", true);
               $('#selectProvinsi').select2({
                  allowClear: true
               });
            <?php endif ?>
        }).done(function () {
              loadSelectSRegion();
        });

    }

    function loadSelectSRegion()
    {
        var selectProvinsi = $('#selectProvinsi').find(':selected').val();
        var url = base_url_js+"api/__getRegionByProv";
        var data = {
                  selectProvinsi : selectProvinsi
              };
        var token = jwt_encode(data,"UAP)(*");
        $('#selectRegion').empty()
        $.post(url,{token:token},function (data_json) {
              for(var i=0;i<data_json.length;i++){
                  // var selected = (i==0) ? 'selected' : '';
                  //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
                  $('#selectRegion').append('<option value="'+data_json[i].RegionID+'" '+''+'>'+data_json[i].RegionName+'</option>');
              }
              $('#selectRegion').select2({
                 //allowClear: true
              });

              <?php if ($action == 'edit'): ?>
                     var get = '<?php echo $getData[0]['CityID'] ?>';
                     $("#selectRegion option").filter(function() {
                        //may want to use $.trim in here
                        return $(this).val() == get; 
                      }).prop("selected", true);
                     $('#selectRegion').select2({
                        allowClear: true
                     });
              <?php endif ?>
        }).done(function () {
              loadSelectDistrict();
        });
    }

    function loadSelectDistrict()
    {
      var selectRegion = $('#selectRegion').find(':selected').val();
      var url = base_url_js+"api/__getDistrictByRegion";
      var data = {
                selectRegion : selectRegion
            };
      var token = jwt_encode(data,"UAP)(*");
      $('#selectDistrict').empty()
      $.post(url,{token:token},function (data_json) {
            for(var i=0;i<data_json.length;i++){
                // var selected = (i==0) ? 'selected' : '';
                //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
                $('#selectDistrict').append('<option value="'+data_json[i].DistrictID+'" '+''+'>'+data_json[i].DistrictName+'</option>');
            }
            $('#selectDistrict').select2({
               //allowClear: true
            });

            <?php if ($action == 'edit'): ?>
                   var get = '<?php echo $getData[0]['DistrictID'] ?>';
                   $("#selectDistrict option").filter(function() {
                      //may want to use $.trim in here
                      return $(this).val() == get; 
                    }).prop("selected", true);
                   $('#selectDistrict').select2({
                      allowClear: true
                   });
            <?php endif ?>
      }).done(function () {
            // loadSelectDistrict();
      });
    }

    function loadTypeSekolah()
    {
      $('#selectTypeSekolah').empty()
      var url = base_url_js+"api/__getTypeSekolah";
      $.post(url,function (data_json) {
            for(var i=0;i<data_json.length;i++){
                // var selected = (i==0) ? 'selected' : '';
                //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
                $('#selectTypeSekolah').append('<option value="'+data_json[i].sct_code+'" '+''+'>'+data_json[i].sct_name_id+'</option>');
            }
            $('#selectTypeSekolah').select2({
               //allowClear: true
            });

            <?php if ($action == 'edit'): ?>
                   var get = '<?php echo $getData[0]['SchoolType'] ?>';
                   $("#selectTypeSekolah option").filter(function() {
                      //may want to use $.trim in here
                      return $(this).text() == get; 
                    }).prop("selected", true);
                   $('#selectTypeSekolah').select2({
                      allowClear: true
                   });
            <?php endif ?>
      })
    }

    $(document).on('change','#selectProvinsi',function () {
        loadSelectSRegion();
    });

    $(document).on('change','#selectRegion',function () {
        loadSelectDistrict();
    });
</script>