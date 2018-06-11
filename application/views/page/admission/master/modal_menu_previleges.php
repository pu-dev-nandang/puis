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
                    <label class="control-label">Pilih Menu:</label>
                </div>    
                <div class="col-sm-4">
                    <select class="full-width-fix" id="selectMenu">
                        <option></option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-default btn-default-success" type="button" data-toggle="collapse" data-target="#addJenisMenu" aria-expanded="false" aria-controls="addJenisMenu">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="collapse" id="addJenisMenu" style="margin-top: 10px;">
                        <div class="well">
                            <div class="row">
                                <div class="col-xs-9">
                                    <input class="form-control" id="InputJenisMenu" placeholder="Input jenis menu...">
                                </div>
                                <label class="col-xs-2">
                                    <a href="javascript:void(0)" id="btnAddItemJenismenu" class="btn btn-default btn-block btn-default-success"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Isi Nama Sub Menu 1:</label>
                </div>    
                <div class="col-sm-6">
                    <input type="text" id="sub_menu1"  class="form-control" placeholder="">
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Isi Nama Sub Menu 2:</label>
                </div>    
                <div class="col-sm-6">
                    <input type="text" id="sub_menu2"  class="form-control" placeholder="">
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Checklist Previleges:</label>
                </div>    
                <div class="col-sm-8">
                    <table class="table" id ="tbl_checklist_previleges">
                        <tr>
                            <td>
                                <input type="checkbox" name="chkPrevileges" class = "chkPrevileges" value="Read">&nbsp Read
                            </td>
                            <td>
                                <input type="checkbox" name="chkPrevileges" class = "chkPrevileges" value="Write">&nbsp Write
                            </td>
                            <td>
                                <input type="checkbox" name="chkPrevileges" class = "chkPrevileges" value="Update">&nbsp Update
                            </td>
                            <td>
                                <input type="checkbox" name="chkPrevileges" class = "chkPrevileges" value="Delete">&nbsp Delete
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div style="text-align: center;">       
    		<div class="col-sm-12" id="BtnFooter">
                <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" id="ModalbtnSaveForm" class="btn btn-success">Save</button>
    		</div>
        </div>    
    </form>
<script type="text/javascript">
    $(document).ready(function () {
         loadSelectMenu();
    });

    function loadSelectMenu()
    {
        var url = base_url_js+"admission/master-config/menu-previleges/get_menu";
        $('#selectMenu').empty()
        $.post(url,function (data_json) {
            var obj = JSON.parse(data_json);
              for(var i=0;i<obj.length;i++){
                  var selected = (i==0) ? 'selected' : '';
                  //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
                  $('#selectMenu').append('<option value="'+obj[i].ID+'" '+selected+'>'+obj[i].Menu+'</option>');
              }
              $('#selectMenu').select2({
                 //allowClear: true
              });
        }).done(function () {
          console.log('loadmenu success');
        });
    }
</script>