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
                    <label class="control-label">Pilih Wilayah:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectWilayahModal">
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
                    <label class="control-label">Pilih Sekolah:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectSekolahModal">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Pilih Sales :</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectSalesModal">
                        <option></option>
                    </select>
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
        loadSelectSales();
    });

    function loadSelectOptionWilayah_SMA()
    {
        $("#selectWilayahModal").empty();
        var url = base_url_js+'api/__getWilayahURLJson';
        $.get(url,function (data_json) {

            for(var i=0;i<data_json.length;i++){
                var selected = (i==0) ? 'selected' : '';
                //$('#selectWilayah').append('<option value="'+data_json['data'][i].kode_wilayah+'" '+selected+'>'+data_json['data'][i].nama+'</option>');
                $('#selectWilayahModal').append('<option value="'+data_json[i].RegionID+'" '+selected+'>'+data_json[i].RegionName+'</option>');
            }
            $('#selectWilayahModal').select2({
               allowClear: true
            });
            <?php if ($action == 'edit'): ?>
                <?php $query = $this->m_master->caribasedprimary('db_admission.school','ID',$getData[0]['SchoolID']); ?>
                   var Wilayah = '<?php echo $query[0]['CityID'] ?>';
                   $("#selectWilayahModal option").filter(function() {
                      //may want to use $.trim in here
                      return $(this).val() == Wilayah; 
                    }).prop("selected", true);
                   $('#selectWilayahModal').select2({
                      allowClear: true
                   });
            <?php endif ?>
        }).done(function () {
              loadSelectSma();
        });

    }

    function loadSelectSma()
    {
        var selectWilayah = $('#selectWilayahModal').find(':selected').val();
        var url = base_url_js+"api/__getSMAWilayah";
        var data = {
                  wilayah : selectWilayah
              };
        var token = jwt_encode(data,"UAP)(*");
        $('#selectSekolahModal').empty()
        $.post(url,{token:token},function (data_json) {
          <?php if ($action != 'edit'): ?>
              $('#selectSekolahModal').append('<option value="'+'All'+'" '+'selected'+'>'+'All'+'</option>');
          <?php endif ?>    
              for(var i=0;i<data_json.length;i++){
                  // var selected = (i==0) ? 'selected' : '';
                  //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
                  $('#selectSekolahModal').append('<option value="'+data_json[i].ID+'" '+''+'>'+data_json[i].SchoolName+'</option>');
              }
              $('#selectSekolahModal').select2({
                 //allowClear: true
              });

              <?php if ($action == 'edit'): ?>
                     var school = '<?php echo $getData[0]['SchoolID'] ?>';
                     $("#selectSekolahModal option").filter(function() {
                        //may want to use $.trim in here
                        return $(this).val() == school; 
                      }).prop("selected", true);
                     $('#selectSekolahModal').select2({
                        allowClear: true
                     });
              <?php endif ?>
        })
    }

    $(document).on('change','#selectWilayahModal',function () {
        loadSelectSma();
    });


    function loadSelectSales()
    {
        var divisiCode = 10;
        var position = 13;
        var encdivisiCode = jwt_encode(divisiCode,"UAP)(*");
        var encposition = jwt_encode(position,"UAP)(*");
        var url = base_url_js+"api/__getEmployees/"+encdivisiCode+"/"+encposition;
        $('#selectSalesModal').empty()
        $.post(url,function (data_json) {
              for(var i=0;i<data_json.length;i++){
                  var selected = (i==0) ? 'selected' : '';
                  //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
                  $('#selectSalesModal').append('<option value="'+data_json[i].NIP+'" '+selected+'>'+data_json[i].Name+'</option>');
              }
              $('#selectSalesModal').select2({
                 //allowClear: true
              });

              <?php if ($action == 'edit'): ?>
                 var sales = '<?php echo $getData[0]['SalesNIP'] ?>';
                 $("#selectSalesModal option").filter(function() {
                    //may want to use $.trim in here
                    return $(this).val() == sales; 
                  }).prop("selected", true);
                 $('#selectSalesModal').select2({
                    allowClear: true
                 });
              <?php endif ?>
        })
    }
</script>