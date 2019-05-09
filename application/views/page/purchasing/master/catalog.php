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
<style type="text/css">
  #datatablesServer thead th,#datatablesServer tfoot td {

      text-align: center;
      background: #20485A;
      color: #FFFFFF;

  }

  #datatablesServer>thead>tr>th, #datatablesServer>tbody>tr>th, #datatablesServer>tfoot>tr>th, #datatablesServer>thead>tr>td, #datatablesServer>tbody>tr>td, #datatablesServer>tfoot>tr>td {
      border: 1px solid #b7b7b7
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
                                <a href="javascript:void(0)" class="pageAnchorCatalog" page = "InputCatalog">Entry</a>
                            </li>
                            <li class="">
                                <a href="javascript:void(0)" class="pageAnchorCatalog" page = "ApprovalCatalog">Approval<b style="color: red;" id= "CountApproval"></b></a>
                            </li>
                            <li class="">
                                <a href="javascript:void(0)" class="pageAnchorCatalog" page = "allow_division">Allow Division</a>
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
      loading_button('#sbmtimportfile');
      var chkfile = file_validation('ImportFile');
      if (chkfile) {
        var form_data = new FormData();
        var url = base_url_js + "purchasing/page/catalog/import_data";
        var files = $('#ImportFile')[0].files;  
        form_data.append("fileData", files[0]);
        $.ajax({
          type:"POST",
          url:url,
          data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
          contentType: false,       // The content type used when sending data to the server.
          cache: false,             // To unable request pages to be cached
          processData:false,
          dataType: "json",
          success:function(data)
          {
            if(data.status == 1) {
              toastr.options.fadeOut = 100000;
              toastr.success(data.msg, 'Success!');
              $('.pageAnchor[page="FormInput"]').trigger('click');
                if (CountColapses2 == 0) {
                  $('.pageAnchor[page="DataIntable"]').trigger('click');
                  // LoadPageCatalog('DataIntable');
                  }
                else
                {
                  LoadPageCatalog('DataIntable');
                }
            }
            else
            {
              toastr.options.fadeOut = 100000;
              toastr.error(data.msg, 'Failed!!');
            }
          setTimeout(function () {
              toastr.clear();
          $('#sbmtimportfile').prop('disabled',false).html('Save');
            },1000);

          },
          error: function (data) {
            toastr.error(data.msg, 'Connection error, please try again!!');
            $('#sbmtimportfile').prop('disabled',false).html('Save');
          }
        })

      }
      else
      {
         $('#sbmtimportfile').prop('disabled',false).html('Save');
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
        if(jQuery.inArray(extension, ['xlsm','xlsx']) == -1)
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

    $(document).off('click', '.btn-delete-file').on('click', '.btn-delete-file',function(e) {
        var Sthis = $(this);
        var li = Sthis.closest('li');
        var filePath = Sthis.attr('filepath');
        var idtable = Sthis.attr('idtable');
        var fieldwhere = Sthis.attr('fieldwhere');
        var table = Sthis.attr('table');
        var field = Sthis.attr('field');
        var typefield = Sthis.attr('typefield');
        var delimiter = Sthis.attr('delimiter');
        var DeleteDb = {
            auth : 'Yes',
            detail : {
                idtable : idtable,
                fieldwhere : fieldwhere,
                table : table,
                field : field,
                typefield : typefield,
                delimiter : delimiter,
            },
        }
        if (confirm('Are you sure ?')) {
            Sthis.html('<i class="fa fa-refresh fa-spin fa-fw right-margin"></i> Loading...');
            Sthis.prop('disabled',true);
            var url = base_url_js + 'rest2/__remove_file';
            var data = {
                filePath : filePath,
                auth : 's3Cr3T-G4N',
                DeleteDb :DeleteDb,
            }

            var token = jwt_encode(data,"UAP)(*");
            $.post(url,{ token:token },function (resultJson) {
                if (resultJson == 1) {
                    li.remove();
                }
                else{
                    toastr.error('', '!!!Failed');
                }
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
                Sthis.prop('disabled',false).html('<i class="fa fa-trash" aria-hidden="true"></i>');
            });
        }
    })
</script>

<!-- Script js allow division -->
<?php $this->load->view('page/'.$this->data['department'].'/master/catalog/sc_allow_division_catalog') ?>