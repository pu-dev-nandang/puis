  
  <div class="col-md-6">
    <div class="widget box">
      <div class="widget-header">
        <h4><i class="icon-reorder"></i> Update Share Menu</h4>
      </div>
      
      <div class="widget-content">
              <div class="form-horizontal row-border">
                <input type="hidden" name="ID" value="<?php echo $sm_menu['ID'] ?>" />
                  <div class="form-group">
                      <label class="col-md-3 control-label">Name</label>
                      <div class="col-md-9">
                          <input type="text" name="name" id="name" class="form-control" value="<?php echo $sm_menu['Name']; ?>">
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-md-3 control-label">Icon</label>
                      <div class="col-md-9">
                          <input type="text" name="icon" id="icon" class="form-control" value="<?php echo $sm_menu['Icon']; ?>">
                      </div>
                  </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Route</label>
                  <div class="col-md-9">
                    <input type="text" name="route" id="route" class="form-control" value="<?php echo $sm_menu['Route']; ?>">
                    <input type="hidden" name="route" id="routehidden" class="form-control" value="<?php echo $sm_menu['Route']; ?>">
                  </div>
              </div>

            <?php
            $data = $this->m_sm_menu->getTotalSm_child($sm_menu['ID']);
            $child = $data->row();
            $childs = $child->total;
            ?>
            <?php if ($childs==0): ?>
             
            <div class="form-group">
              <label class="col-md-3 control-label">Child</label>
              <div class="col-md-9">
                <input type="checkbox" id="myCheck" name="child" onclick="myFunction()">
              </div>
            </div>

             <div class="form-group" id="myBtn">
              <div class="col-md-12" style="text-align: right; margin-bottom: 10px;" >
          <button onclick="update();" class="btn btn-success" data-color-format="hex" data-color="#fff">Update</button>
        </div>  
                
             
            </div>

            <?php else: ?>
               <div class="form-group">
                <label class="col-md-3 control-label">Child</label>
                <div class="col-md-9">
                    <input type="checkbox" id="myCheck" name="child" checked="checked" disabled="disabled">
                </div>
            </div>
            <?php endif ?>
        </div>
      </div> 

    <div class="col-md-12"  id="myDiv" style="display: none;">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> Update Child</h4>
                
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs" onclick="addChild();"><i class="icon-plus"></i> Add</span>
                    </div>
                </div>
                
            </div>
            <div class="widget-content">
                
                   
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="row">
                                    
                  <table class="table table-responsive table-bordered">
                    <tr>
                      <th>Name</th>
                      <th>Route</th>
                      <th>Action</th>
                    </tr>

                    <tbody id="appendBodySm">
                      <?php 
                        $data = $sm_child->num_rows();       
                      ?>
                 <?php if ($data==0): ?>
                    <tr>
                          <td>
                            <div class="form-group">
                              <div class="col-sm-12">
                                <input type="text" class="form-control inputNameChild">
                              </div>
                            </div>
                          </td>

                          <td>
                            <div class="form-group">
                              <div class="col-sm-12">
                                <input type="text" class="form-control inputRouteChild">
                              </div>
                            </div>
                          </td>
                          <td>
                          </td>

                        </tr>
                 <?php else: ?>
                  <?php 
                        foreach ($sm_child->result_array() as $key) :     
                      ?>
                    <tr id="existTableRow<?php echo $key['ID'];?>">
                        <td>
                          <div class="form-group">
                            <input type="text" class="form-control inputNameChild" id="InputNameID<?php echo $key['ID'];?>" value="<?php echo $key['Name'];?>">            
                          </div>
                        </td>
                        <td>
                          <div class="form-group">
                            <input type="text" class="form-control inputRouteChild" id="InputRouteID<?php echo $key['ID'];?>" value="<?php echo $key['Route'];?>">
                          </div>
                        </td>
                        <td>
                          <div class="form-group">
                    
                            <button class="btn btn-danger btn-sm" onclick="deleteExistChild(<?php echo $key['ID']; ?>);" title="Delete"> 
                              <i class="fa fa-trash-o"></i> 
                            </button>
                          </div>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                 <?php endif ?>
                     
                    
                   
                    </tbody> 
                  </table>
                  <br>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
        <div class="col-md-12" style="text-align: right; margin-bottom: 10px; display: none;" id="updateBtn">
          <button onclick="saveAll();" class="btn btn-success">Update</button>
        </div>                
    </div>
  </div>



<div class="modal" id="ModalDelete" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <input type="hidden" name="hiddenInputForDelete" id="hiddenInputForDelete">
      <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Delete Child</h4>
      </div>
      <div class="modal-body">
        <center>
          <h2>
            Are you sure you want to delete ?
          </h2>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-sm-12">
            <center>
              <button type="button" class="btn btn-success waves-effect text-left" onclick="confirmDeleteExistChild();">
                Yes
              </button>
              <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal" aria-hidden="true">
                No
              </button>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div> 
</div>  



<script>
var i = document.getElementById("myCheck").checked;
var x = document.getElementById("myDiv");
 var j = document.getElementById("updateBtn");

if(i===true){
  x.style.display="block";
  j.style.display="block";
  $("#route").attr("disabled", "true");
}

function myFunction() {
   var i = document.getElementById("myCheck").checked;
   var x = document.getElementById("myDiv");
   var v = document.getElementById("myBtn");
    var j = document.getElementById("updateBtn");

   if(i===true){
    var route = $("#route").val();

    document.getElementById("route").value = "";
     x.style.display="block";
      v.style.display="none";
      j.style.display="block";
       $("#route").attr("disabled", "true");
   }else{
    var route = $("#routehidden").val();
    x.style.display="none";
    v.style.display="block";
    j.style.display="none";
    document.getElementById("route").value = route;
    $("#route").removeAttr("disabled");
   }
}

 async function update(){
    var id = "<?php echo $sm_menu['ID'] ?>";
    var name = $("#name").val();
    var icon = $("#icon").val();
    var route = $("#route").val();
    var data = [];
    var boolValidation = true;

    if(boolValidation){
      var dataShareMenu = {
        ID : "<?php echo $sm_menu['ID'] ?>",
        Name : $("#name").val(),
        Icon : $("#icon").val(),
        Route : $("#route").val(),
      };

      for(key in dataShareMenu){
        if(dataShareMenu[key] == ''){
           toastr.info(key+' cannot be empty!');
          boolValidation = false;
          break;
        }
      }

      if(!boolValidation){
        return;
      }
    }

    var dataAjax = {
      action : 'updateShareMenu',
      dataShareMenu : dataShareMenu,
    }

    var token = jwt_encode(dataAjax,'UAP)(*');
    var url = "<?php echo base_url('it/ShareMenuCRUD'); ?>";
    try{
      var ajax = await AjaxSubmitFormPromises(url,token);
      if(ajax['status'] == 1){
        window.location = "<?php echo base_url('it/console-developer/share-menu'); ?>";
      }
      else
      {
        alert(ajax['msg']);
      }
    }
    catch(err){
      toastr.info('something error');
    }    
}

function addChild() {
  var html = '';
  html += '<tr>'+
    '<td>'+
      '<div class="form-group">'+
        '<input type="text" class="form-control inputNameChild">'+
        
   ' </td>'+
  ' <td>'+
   '   <div class="form-group">'+
    '    <input type="text" class="form-control inputRouteChild">'+
    
    '</td>'+
    '<td>'+
    '  <div class="form-group">'+
        '<button class="btn btn-danger btn-sm newDeleteButton" title="Delete">'+ 
         ' <i class="fa fa-trash-o"></i> '+
       '</button>'+
      '</div>'+
   ' </td>'+
 ' </tr>';
    $('#appendBodySm').append(html);

}

 $(document).on('click','.newDeleteButton',function(e){
  $(this).closest('tr').remove();
 })


 async function saveAll(){
  var data = [];
  var boolValidation = true;
  $('#appendBodySm').find('tr').each(function(e){
    const itsme = $(this);
    var IDSM = "<?php echo $sm_menu['ID'] ?>";
    var Name = itsme.find('.inputNameChild').val();
    var Route = itsme.find('.inputRouteChild').val();

    if(Name == '' || Route == ''){
      boolValidation =  false;
      return;
    }
    
    var temp = {
      IDSM : IDSM,
      Name : Name,
      Route : Route,
    }
    data.push(temp);
  })

  if(boolValidation){
    var dataShareMenu = {
      ID : "<?php echo $sm_menu['ID'] ?>",
      Name : $("#name").val(),
      Icon : $("#icon").val(),
      Route : $("#route").val(),
    };

    var dataNotif = {
      Name : $("#name").val(),
      Icon : $("#icon").val(),
    };


    for(key in dataNotif){
      if(dataNotif[key] == ''){
        toastr.info(key+' cannot be empty!');
        boolValidation = false;
        break;
      }
    }

    if(!boolValidation){
      return;
    }
    
    var dataAjax = {
      action : 'updateAllData',
      dataShareMenu : dataShareMenu,
      dataChild : data,
    }

    var token = jwt_encode(dataAjax,'UAP)(*');
    var url = "<?php echo base_url('it/ShareMenuCRUD'); ?>";
    try{
      var ajax = await AjaxSubmitFormPromises(url,token);
      if(ajax['status'] == 1){
        window.location = "<?php echo base_url('it/console-developer/share-menu'); ?>";
      }
      else
      {
        alert(ajax['msg']);
      }
    }
    catch(err){
      toastr.info('something error');
    }
  }
  else
  {
    toastr.info('Child data cannot be empty!');
  }
 }

  function deleteExistChild(exist_row_id)
  {
    $("#ModalDelete").modal("show");

    $("#hiddenInputForDelete").val(exist_row_id);
  }

  async function confirmDeleteExistChild()
  {
    var data = [];
    var boolValidation = true;
    var dataShareMenu = {
      ID : $("#hiddenInputForDelete").val(),
    };

    var dataAjax = {
      action : 'deleteChild',
      dataShareMenu : dataShareMenu,
    }
    var token = jwt_encode(dataAjax,'UAP)(*');
    var url = "<?php echo base_url('it/ShareMenuCRUD'); ?>";
    try{
      var ajax = await AjaxSubmitFormPromises(url,token);
     
      if(ajax['status'] == 1){
        $("#existTableRow"+$("#hiddenInputForDelete").val()+"").remove();
        $('#ModalDelete').modal('hide');
      }
      else
      {
        alert(ajax['msg']);
      }
    }
    catch(err){
      toastr.info('something error');
    }
  }
 </script>