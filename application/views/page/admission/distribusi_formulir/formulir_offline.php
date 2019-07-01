<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<style type="text/css">
	/*.form-horizontal .control-label {
	    text-align: left;
	}
	.panel-primary>.panel-heading {
	    color: #2b1212;
	    background-color: #f9f9f9;
	    border-color: #bbce3b;
	}*/
  #tableData3.dataTable tbody tr:hover {
     background-color:#71d1eb !important;
     cursor: pointer;
  }
 
</style>
<div class="row" style="margin-top: 30px;">      
      <div class="col-md-12">
           <div class="tabbable tabbable-custom tabbable-full-width btn-read menu">
               <ul class="nav nav-tabs">
                   <li class="active">
                       <a href="javascript:void(0)" class="pageAnchor btn-read" page = "ListPenjualan">List Penjualan</a>
                   </li>
                   <li class="">
                       <a href="javascript:void(0)" class="pageAnchor btn-add" page = "InputPenjualan">Input Penjualan</a>
                   </li>
                   <?php 
                   $authIT =  $this->session->userdata('PositionMain');
                   $authIT =  $authIT['IDDivision'];
                   ?>
                  <?php if ($authIT == 12 ): ?>
                    <li class="">
                        <a href="javascript:void(0)" class="pageAnchor btn-add" page = "Import">Import</a>
                    </li>
                  <?php endif ?> 
               </ul>
               <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                   <div id = "pageContent">
                      
                   </div>
               </div>
           </div>
      </div>      
</div>            

<script type="text/javascript">
  $(document).ready(function() {
      LoadListPenjualan();
  }); // exit document Function

  function LoadListPenjualan()
  {
    loading_page("#pageContent");
    var url = base_url_js+'admission/distribusi-formulir/offline/LoadListPenjualan';
    $.post(url,function (resultJson) {
        var response = jQuery.parseJSON(resultJson);
        var html = response.html;
        var jsonPass = response.jsonPass;
        $("#pageContent").html(html);
    }); // exit spost
  }

  function LoadInputPenjualan(action = 'add',ID = '')
  {
    loading_page("#pageContent");
    var url = base_url_js+'admission/distribusi-formulir/offline/LoadInputPenjualan';
    data = {
      action : action ,
      ID : ID
    };
    var token = jwt_encode(data,"UAP)(*");
    $.post(url,{token:token},function (resultJson) {
        var response = jQuery.parseJSON(resultJson);
        var html = response.html;
        var jsonPass = response.jsonPass;
        $("#pageContent").html(html);
    }); // exit spost
  }

  function LoadImportInputPenjualan()
  {
    loading_page("#pageContent");
    var url = base_url_js+'admission/distribusi-formulir/offline/LoadImportInputPenjualan';
    $.post(url,function (resultJson) {
        var response = jQuery.parseJSON(resultJson);
        var html = response.html;
        var jsonPass = response.jsonPass;
        $("#pageContent").html(html);
    }); // exit spost
  }

  $(document).on('click','.pageAnchor', function () {
      var Page = $(this).attr('page');
      $(".menu li").removeClass('active');
      $(this).parent().addClass('active');
      $("#pageContent").empty();
      switch(Page) {
          case "ListPenjualan":
              LoadListPenjualan();
              break;
          case "InputPenjualan":
              LoadInputPenjualan();
              break;
          case "Import":
              LoadImportInputPenjualan();
              break;    
          default:
              text = "I have never heard of that fruit...";
      }
  });
	
</script>
