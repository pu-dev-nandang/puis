<style type="text/css">
  /* FANCY COLLAPSE PANEL STYLES */
  .fancy-collapse-panel .panel-default > .panel-heading {
  padding: 0;

  }
  .fancy-collapse-panel .panel-heading a {
  padding: 12px 35px 12px 15px;
  display: inline-block;
  width: 100%;
  background-color: #EE556C;
  color: #ffffff;
  position: relative;
  text-decoration: none;
  }
  .fancy-collapse-panel .panel-heading a:after {
  font-family: "FontAwesome";
  content: "\f147";
  position: absolute;
  right: 20px;
  font-size: 20px;
  font-weight: 400;
  top: 50%;
  line-height: 1;
  margin-top: -10px;
  }

  .fancy-collapse-panel .panel-heading a.collapsed:after {
  content: "\f196";
  }
</style>
<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div style="padding-top: 30px;border-top: 1px solid #cccccc">
    <div class="row">
       <div class="col-xs-12" >
        <div class="panel panel-primary">
               <div class="panel-heading clearfix">
                   <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Catalog</h4>
               </div>
               <div class="panel-body" id = "pageContentCatalog">
                    <div class="tabbable tabbable-custom tabbable-full-width btn-read MenuCatalog">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="javascript:void(0)" class="pageAnchorCatalog" page = "InputCatalog">Input</a>
                            </li>
                            <li class="">
                                <a href="javascript:void(0)" class="pageAnchorCatalog" page = "ApprovalCatalog">Approve<b style="color: red;" id= "CountApproval"></b></a>
                            </li>
                        </ul>
                        <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                            <div id = "pageCatalog">
                               
                            </div>
                        </div>
                    </div>
               </div>
        </div>
       </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        LoadPage('InputCatalog');

        $(".pageAnchorCatalog").click(function(){
            var Page = $(this).attr('page');
            $(".MenuCatalog li").removeClass('active');
            $(this).parent().addClass('active');
            LoadPage(Page)
        });
    }); // exit document Function

    function LoadPage(page)
    {
      loading_page("#pageCatalog");
      var url = base_url_js+'purchasing/page/catalog/'+page;
      $.post(url,function (resultJson) {
          var response = jQuery.parseJSON(resultJson);
          var html = response.html;
          var jsonPass = response.jsonPass;
          $("#pageCatalog").html(html);
      }); // exit spost
    }

    $(document).on('click','#sbmtimportfile', function () {
      var chkfile = file_validation('ImportFile');
      if (chkfile) {
        
      }
    });

    function file_validation(ID_element)
    {
        var files = $('#'+ID_element)[0].files;
        var error = '';
        var msgStr = '';
       var name = files[0].name;
        console.log(name);
        var extension = name.split('.').pop().toLowerCase();
        if(jQuery.inArray(extension, ['xlsx']) == -1)
        {
         msgStr += 'Invalid Type File<br>';
        }

        var oFReader = new FileReader();
        oFReader.readAsDataURL(files[0]);
        var f = files[0];
        var fsize = f.size||f.fileSize;
        console.log(fsize);

        if(fsize > 2000000) // 2mb
        {
         msgStr += 'File Size is very big<br>';
        }

        if (msgStr != '') {
          toastr.error(msgStr, 'Failed!!');
          return false;
        }
        else
        {
          return true;
        }
    }
</script>