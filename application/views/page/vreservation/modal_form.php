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
                <div class="col-sm-3">
                    <label class="control-label">Room</label>
                </div>    
                <div class="col-sm-6">
                   <input type="text" name="Start" id= "Room" placeholder="Input Room" class="form-control" value="<?php echo $room ?>" readonly>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 hide" align = 'center' id='msgMENU' style="color: red;">MSG</div>
            </div>   
            <div class="row">
                <div class="col-sm-3">
                    <label class="control-label">Start</label>
                </div>    
                <div class="col-sm-6">
                   <input type="text" name="Start" id= "Start" placeholder="Input Start" class="form-control" value="<?php echo $time ?>" readonly>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 hide" align = 'center' id='msgMENU' style="color: red;">MSG</div>
            </div>   
            <div class="row">
                <div class="col-sm-3">
                    <label class="control-label">End</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="End">
                        <!-- <option></option> -->
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-3">
                    <label class="control-label">Agenda :</label>
                </div>    
                <div class="col-sm-6">
                   <input type="text" name="Agenda" id= "Agenda" placeholder="Input Agenda" class="form-control">
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-3">
                    <label class="control-label">Equipment Additional :</label>
                </div>    
                <div class="col-sm-6">
                    <div class="col-md-3" id ="e_additional">
                                                                  
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-3">
                    <label class="control-label">Person Support :</label>
                </div>    
                <div class="col-sm-6">
                    <div class="col-md-3" id ="person_support">
                                                                  
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-3">
                    <label class="control-label">Multiple Days :</label>
                </div>    
                <div class="col-sm-6">
                    <div class="col-md-3" id ="multiplePage">
                                                                  
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-3">
                    <label class="control-label">Request Layout:</label>
                </div>    
                <div class="col-sm-6">
                    <input type="file" data-style="fileinput" id="ExFile">
                    <br>
                    <b>If no Upload then using Default Layout</b><br>
                    <a href="<?php echo base_url('fileGetAny/vreservation-aa.pdf'); ?>" target="_blank"></i>Click Default Layout</a>
                </div>
            </div>
        </div>
        <div style="text-align: center;">       
    		<div class="col-sm-12" id="BtnFooter">
                <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" id="ModalbtnSaveForm" class="btn btn-success" action = "<?php echo $action ?>">Save</button>
    		</div>
        </div>    
    </form>
<script type="text/javascript">
    //window.equipment_additional = [];
    $(document).ready(function () {
        load_e_additional();
        LoadEnd();
        load_person_support();
        load_multiple();
    });

    function load_multiple()
      {
        $('#multiplePage').append('<table class="table" id ="table_multiple">');
        for (var i = 0; i < 1; i++) {
            $('#table_multiple').append('<tr id = "multiple'+i+'">');
            for (var k = 0; k < 2; k++) {
                if (k == 0) {
                    $('#multiple'+i).append('<td>'+
                                        '<input type="checkbox" class = "chk_e_multiple" name="chk_multiple" value = "Tidak" id = "e_multipleTDK">&nbsp No' +
                                     '</td>'
                                    );
                }
                else
                {
                    $('#multiple'+i).append('<td>'+
                                        '<input type="checkbox" class = "chk_e_multiple" name="chk_multiple" value = "Ya" id = "multipleYA">&nbsp Yes' +
                                     '</td>'
                                    );
                }
                
            }
            $('#multiple'+i).append('</tr>');
        }
        $('#table_multiple').append('</table>');
      }

    function load_e_additional()
      {
        $('#e_additional').append('<table class="table" id ="table_e_additional">');
        for (var i = 0; i < 1; i++) {
            $('#table_e_additional').append('<tr id = "e_additional'+i+'">');
            for (var k = 0; k < 2; k++) {
                if (k == 0) {
                    $('#e_additional'+i).append('<td>'+
                                        '<input type="checkbox" class = "chk_e_additional" name="chk_e_additional" value = "Tidak" id = "e_additionalTDK">&nbsp No' +
                                     '</td>'
                                    );
                }
                else
                {
                    $('#e_additional'+i).append('<td>'+
                                        '<input type="checkbox" class = "chk_e_additional" name="chk_e_additional" value = "Ya" id = "e_additionalYA">&nbsp Yes' +
                                     '</td>'
                                    );
                }
                
            }
            $('#e_additional'+i).append('</tr>');
        }
        $('#table_e_additional').append('</table>');
      }

      function load_person_support()
      {
        $('#person_support').append('<table class="table" id ="table_person_support">');
        for (var i = 0; i < 1; i++) {
            $('#table_person_support').append('<tr id = "tr_person_support'+i+'">');
            for (var k = 0; k < 2; k++) {
                if (k == 0) {
                    $('#tr_person_support'+i).append('<td>'+
                                        '<input type="checkbox" class = "chk_person_support" name="chk_person_support" value = "Tidak" id = "person_supportTDK">&nbsp No' +
                                     '</td>'
                                    );
                }
                else
                {
                    $('#tr_person_support'+i).append('<td>'+
                                        '<input type="checkbox" class = "chk_person_support" name="chk_person_support" value = "Ya" id = "person_supportYA">&nbsp Yes' +
                                     '</td>'
                                    );
                }
                
            }
            $('#tr_person_support'+i).append('</tr>');
        }
        $('#table_person_support').append('</table>');
      }



      function LoadEnd()
      {
        var url = base_url_js+"api/get_time_opt_reservation";
        var time = "<?php echo $time ?>";
        var data = {
            time : time
        }
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token },function (data_json) {
          for (var i = 0; i < data_json.length; i++) {
              var selected = (i==0) ? 'selected' : '';
              $('#End').append('<option value="'+ data_json[i]  +'" '+selected+'>'+data_json[i]+'</option>');
          }
        }).done(function () {
          //loadAlamatSekolah();
        });
        

      }
</script>