
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-centre">
            <thead>
            <tr>
                <th>No</th>
                <th>Menu</th>
                <th>Child</th>
                <th>Route</th>
                <th><i class="fa fa-cog"></i></th>
                <th>Allow To</th> 

            </tr>
            </thead>
            <tbody>
                <?php 
                  $no=1;
                  foreach ($sm->result_array() as $row){
                  ?>
          
                <tr>
                  <?php 
                    $data = $this->m_sm_menu->getAllSm_menubyid($row['ID']);
                    $total_row = $data->num_rows();
                    $res = $data->result_array();
                  ?>
                  <td rowspan="<?php echo $total_row ?>" style="vertical-align: middle;"><?php echo $no; ?></td>
                  <td rowspan="<?php echo $total_row ?>" style="vertical-align: middle; text-align: left;"><?php echo '<i class="'.$row['Icon'].'" aria-hidden="true"></i>'.' '.$row['Name']; ?></td>
                
                  <?php foreach($res as $res){ ?>
                     <td style="text-align: left;"> <?php echo $res['namechild']; ?></td>


                     <?php if (empty($row['Route'])): ?>
                          <td style="text-align: left;"> <?php echo $res['routechild']; ?></td>
                     <?php else: ?>
                          <td style="text-align: left;"> <?php echo $row['Route']; ?></td>
                     <?php endif ?>
                    

                    <?php if ($res['namechild']==$row['namechild']): ?>
                      <td rowspan="<?php echo $total_row ?>" style="vertical-align: middle;">
                    <div class="btn-group">
                        <a href="<?php echo base_url('it/edit_share_menu/'.$row['ID']); ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-pencil-square-o"></i></a>
                        <a class="btn btn-danger btn-sm" title="Delete" onclick="deleteShareMenu(<?php echo $row['ID']; ?>);"><i class="fa fa-trash-o"></i></a>
                    </div>
                  </td>
                  <td rowspan="<?php echo $total_row ?>" style="vertical-align: middle;">
                    
                  <div class="btn-group">
                        <button class="btn btn-default btn-sm" title="View" data-toggle="modal" data-target="#modal_edit<?php echo $row['ID'];?>" data-backdrop="static" data-keyboard="false"><i class="fa fa-eye"></i></button>
                    </div>
                    <div>
                    <?php 

                      $data = $this->m_sm_menu->getAllDivision();
                      $no=1;
                      foreach ($data->result_array() as $division){
                       $sm_div = $this->m_sm_menu->getDivisionIDSM($division['ID'], $row['ID']);
                    ?>
                    <?php if (empty($sm_div['IDDivision'])): ?>
                    <?php else: ?>
                      <span style="border-radius: 3px;margin-right: 5px;background: #0c5fa1;color: #ffffff;padding: 5px;font-size: 10px;border: 1px solid #fff;line-height: 3;"><?= $sm_div['Division']; ?></span>
                    <?php endif ?>
                    <?php } ?>
                    </div>
                  </td>

                    <?php endif ?>
                  </tr>
                  <?php  } ?>
                  <?php  $no++;} ?>
                 
            </tbody>
        </table>
    </div>
<br><br>
    <div class="col-md-6">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> Create Share Menu</h4>
            </div>
            <div class="widget-content">
                <div class="form-horizontal row-border">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Name</label>
                        <div class="col-md-9">
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Icon</label>
                        <div class="col-md-9">
                            <input type="text" name="icon" id="icon" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Route</label>
                        <div class="col-md-9">
                            <input type="text" name="route" id="route" class="form-control">                        
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Child</label>
                        <div class="col-md-9">
                            <input type="checkbox" id="myCheck" name="child" onclick="myFunction()">
                        </div>
                    </div>
                    <div class="form-group" id="myBtn">
                       
                        <div class="col-md-12"  style="text-align: right; margin-bottom: 10px;">
                          <span class="btn btn-success" data-color-format="hex" onclick="create()" data-color="#fff">Create</span>
                        </div>
                        
                    </div>
                </div>
            </div> 
            <div class="col-md-12"  id="myDiv" style="display: none;">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> Child</h4>
                
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs" onclick="addSm();"><i class="icon-plus"></i> Add</span>
                    </div>
                </div>
                
            </div>

            <div class="widget-content">
                                
            <div class="form-group">
              <div class="col-md-12">
                  <div class="row">
                    
                      <table class="table table-responsive table-bordered">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Route</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody id="appendBodySm">
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
                        </tbody>
                        
                      </table>
                      
                      <table style="display: none">
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

                            <div class="form-group">
                              <span class="btn btn-danger btn-sm buttonDelete" title="Delete" onclick="confirmDeleteRowAR(this.id);">
                                <i class="fa fa-trash-o"></i>
                              </span>
                            </div>
                          </td>

                        </tr>
                      </table>

                    </div>

                </div>
            </div>
                    
              
            </div>

        </div>

           <div class="col-md-12"  style="text-align: right; margin-bottom: 10px;">
             <span class="btn btn-success" onclick="validateAndSaveReport();">Create</span>
           </div>
                 
    </div>
        </div>
    </div>
    
  
</div>



<div class="modal" id="ModalDelete" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <input type="hidden" name="hiddenInputForDelete" id="hiddenInputForDelete">
      <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Delete Share Menu</h4>
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
              <button type="button" class="btn btn-primary waves-effect text-left" onclick="confirmDeleteShareMenu();">
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


<?php 
foreach ($sm->result_array() as $row){
  $user = $this->m_sm_menu->getsm_user($row['ID']);
?>
        <div class="modal fade" id="modal_edit<?php echo $row['ID'];?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                      <h2 class="modal-title">Allow To</h2>
                    </div>
                    
                    <div class="modal-body">
                      <table id="example2" class="table table-bordered datatable">
                       <thead>
                         <tr>
                          <th>No</th>
                          <th>Division</th>
                        
                          <th>Action</th>
                         </tr>
                       </thead>
                       <tbody>
                    <?php 
                      $data = $this->m_sm_menu->getAllDivision();
                      $no=1;
                      foreach ($data->result_array() as $division){
                        $sm_div = $this->m_sm_menu->getDivisionIDSM($division['ID'], $row['ID']);
                       
                    ?>
                <tr>
                  <td>
                   <?php echo $no++; ?>

                  </td>
                  <td> 
                    <?php echo $division['Division']; ?>
                  </td>
                  <td>
                    <div class="btn-group">
                      <?php if ($sm_div['idsm']==$row['ID']): ?>
                        <button class="btn btn-info btn-sm" onclick="selectbtn(<?php echo $division['ID']; ?>,<?php echo $row['ID'];?> );" id="select<?php echo $division['ID'];?><?php echo $row['ID'];?>" title="Select" style="display: none;">Select</button>
                         <button class="btn btn-danger btn-sm" onclick="cancelbtn(<?php echo $division['ID']; ?>, <?php echo $row['ID'];?>);" id="cancel<?php echo $division['ID'];?><?php echo $row['ID'];?>" title="cancel" >Cancel</button>
                      <?php else: ?>
                        <button class="btn btn-info btn-sm" onclick="selectbtn(<?php echo $division['ID']; ?>, <?php echo $row['ID'];?>);" id="select<?php echo $division['ID'];?><?php echo $row['ID'];?>" title="Select">Select</button>
                        <button class="btn btn-danger btn-sm" onclick="cancelbtn(<?php echo $division['ID']; ?>, <?php echo $row['ID'];?>);" id="cancel<?php echo $division['ID'];?><?php echo $row['ID'];?>" title="cancel" style="display: none;">Cancel</button>
                      <?php endif ?>
                      
                     
                    </div>
                  </td>
                </tr>
                <?php } ?>
               
                </tbody>
              </table>
            
            </div>
            <div class="modal-footer">
                        <button type="button" class="btn btn-default" onclick="window.location.reload();">Close</button>
                    </div>
                </div>
            </div>
        </div>
<?php } ?>

<script>
  
function selectbtn(id,idsm) {
     $.ajax({
         type: "POST",
         url: "<?php echo base_url('it/selectDivision'); ?>",
         data: "id=" + id + "&idsm=" + idsm,
         success: function (res) {
           $("#select"+id+""+idsm+"").css("display", "none");
           $("#cancel"+id+""+idsm+"").css("display", "");
         }
     })
 }


function cancelbtn(id,idsm) {
  $.ajax({
      type: "POST",
      url: "<?php echo base_url('it/cancelDivision'); ?>",
      data: "id=" + id + "&idsm=" + idsm,
      success: function (res) {
        $("#cancel"+id+""+idsm+"").css("display", "none");
        $("#select"+id+""+idsm+"").css("display", "");
        
      }
  })
}


async function create(){
    var data = [];
    var boolValidation = true;

    if(boolValidation){
      var dataShareMenu = {
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
      action : 'createShareMenu',
      dataShareMenu : dataShareMenu,
    }

    var token = jwt_encode(dataAjax,'UAP)(*');
    var url = "<?php echo base_url('it/ShareMenuCRUD'); ?>";
    try{
      var ajax = await AjaxSubmitFormPromises(url,token);
      if(ajax['status'] == 1){
        window.location = "";
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

function myFunction() {
  var i = document.getElementById("myCheck").checked;
  var x = document.getElementById("myDiv");
  var v = document.getElementById("myBtn");
  if(i===true){
    x.style.display="block";
    v.style.display="none";
     $("#route").attr("disabled", "true");
  }else{
   x.style.display="none";
    v.style.display="block";
    $("#route").removeAttr("disabled");
  }
    
}


 function addSm() {
  var html = '';
  html += '<tr id="ReportTableRow1">'+
          '<td>'+
              '<div class="form-group">'+
                '<div class="col-sm-12">'+
                '<input type="text" class="form-control inputNameChild">'+
            '</div>'+
             ' </div>'+
          '</td>'+
          '<td>'+
             ' <div class="form-group">'+
               ' <div class="col-sm-12">'+
               ' <input type="text" class="form-control inputRouteChild">'+
           ' </div>'+
           ' </div>'+
         ' </td>'+
          '<td>'+
            '<div class="form-group">'+
             ' <span class="btn btn-danger btn-sm buttonDelete" title="Delete">'+
                '<i class="fa fa-trash-o"></i>'+
              '</span>'+
           ' </div>'+
         ' </td>'+
        '</tr>';
    $('#appendBodySm').append(html);

 }


 $(document).on('click','.buttonDelete',function(e){
  $(this).closest('tr').remove();
 })


 async function validateAndSaveReport(){
  var data = [];
  var boolValidation = true;
  $('#appendBodySm').find('tr').each(function(e){
    const itsme = $(this);
    var Name = itsme.find('.inputNameChild').val();
    var Route = itsme.find('.inputRouteChild').val();

    if(Name == '' || Route == ''){
      boolValidation =  false;
      return;
    }
    
    var temp = {
      Name : Name,
      Route : Route,
    }

    data.push(temp);

  })

  if(boolValidation){
    var dataShareMenu = {
      Name : $("#name").val(),
      Icon : $("#icon").val(),
      Route : $("#route").val(),
    };

     var validasi = {
      Name : $("#name").val(),
      Icon : $("#icon").val(),
      
    };

    for(key in validasi){
      if(validasi[key] == ''){
        toastr.info(key+' cannot be empty!');
        boolValidation = false;
        break;
      }
    }

    if(!boolValidation){
      return;
    }
    
    var dataAjax = {
      action : 'createAllData',
      dataShareMenu : dataShareMenu,
      dataChild : data,
    }

    var token = jwt_encode(dataAjax,'UAP)(*');
    var url = "<?php echo base_url('it/ShareMenuCRUD'); ?>";
    try{
      var ajax = await AjaxSubmitFormPromises(url,token);
      if(ajax['status'] == 1){
        window.location = "";
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


 function deleteShareMenu(exist_row_id)
  {
    $("#ModalDelete").modal("show");
    $("#hiddenInputForDelete").val(exist_row_id);
  }


  async function confirmDeleteShareMenu()
  {
    var data = [];
    var boolValidation = true;
    var dataShareMenu = {
      ID : $("#hiddenInputForDelete").val(),
    };

    var dataAjax = {
      action : 'deleteShareMenu',
      dataShareMenu : dataShareMenu,
    }
    var token = jwt_encode(dataAjax,'UAP)(*');
    var url = "<?php echo base_url('it/ShareMenuCRUD'); ?>";
    try{
      var ajax = await AjaxSubmitFormPromises(url,token);
     
      if(ajax['status'] == 1){
        $('#ModalDelete').modal('hide');
        window.location = '';
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