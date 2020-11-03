
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Input Privileges Monthly Report</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;">
        <div class="form-group">
            <label>NIP</label>
            <br>
            <select id = "NIP" style="width: 100%" class="input" name = "NIP"> </select> 
        </div>
        <div class="form-group">
            <label>Department Access</label>
            <select class="form-control input" id="DivisionID" name = "DivisionID">
            <?php for($i = 0; $i < count($G_division); $i++): ?>
              <option value="<?php echo $G_division[$i]['Code'] ?>" > <?php echo $G_division[$i]['Name2'] ?> </option>
            <?php endfor ?>
           </select>
        </div>
        <div class="form-group">
           <label>Access</label>
           <br>
           <select class="form-control input" id = "Access" name = "Access">
           <option value="Read">Read</option>
           <option value="Write">Write</option>
           </select>
        </div>
        
    </div>
    <div class="panel-footer" style="text-align: right;">
        <button class="btn btn-success" action= "add" data-id ="" id="btnSave">Save</button>
    </div>
</div>
<script type="text/javascript">
   var AppForm_Setting_Monthly_Report = {
        setDefaultInput : function(){
            // $('.input').val('');
            $('#btnSave').attr('action','add');
            $('#btnSave').attr('data-id','');
        },
        ActionData : function(selector,action="add",ID=""){
            var htmlbtn = selector.html();
            var form_data = new FormData();
            var data = {};
            $('.input').each(function(){
                var field = $(this).attr('name');
                if (field !== undefined) {
                    data[field] = $(this).val();
                }
                
            })  
            var dataform = {
                action : action,
                data : data,
                ID : ID,
            };
            var token = jwt_encode(dataform,"UAP)(*");
            form_data.append('token',token);

            if (confirm('Are you sure ?')) {
                loading_button2(selector);
                var url = base_url_js + "rektorat/crud_setting_monthly_report";
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
                            AppForm_Setting_Monthly_Report.setDefaultInput();
                            end_loading_button2(selector,htmlbtn);
                            oTable.ajax.reload( null, false );
                          },
                          error: function (data) {
                            toastr.error("Connection Error, Please try again", 'Error!!');
                            end_loading_button2(selector,htmlbtn);
                            
                          }
                        })
            }
        },

        loaded : function LoadNama() {
        var selector =$('#NIP');
        var url = base_url_js+'rest3/__Config_Jabatan_SKS';
        var data = {
            auth : 's3Cr3T-G4N',
            mode : 'showDataDosen'
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token },function (resultJson) {
            
        }).done(function(resultJson) {
            //var response = jQuery.parseJSON(resultJson);
            selector.empty()
            for (var i = 0; i < resultJson.length; i++) {
                var NIP = resultJson[i].NIP
                var Name = resultJson[i].Name
                selector.append(
                    ' <option value="'+NIP+'">'+NIP+'|'+Name+'</option>'
                    )

            }
           selector.select2({
               //allowClear: true
           }); 
            
        }).fail(function() {
          toastr.info('No Result Data');
        }).always(function() {
                        
        }); 

    
            AppForm_Setting_Monthly_Report.setDefaultInput();
        },

    };

    $(document).ready(function() {
        AppForm_Setting_Monthly_Report.loaded();
    })

    $(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
       var selector = $(this);
       var action = selector.attr('action');
       var ID = selector.attr('data-id'); 
        AppForm_Setting_Monthly_Report.ActionData(selector,action,ID);
       
    })
</script>