<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Input</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;">
        <div class="form-group">
            <label>Jenis Publikasi</label>
            <input type="text" class="form-control input" name = "Nm_jns_pub" disabled>
        </div>
        <div class="form-group">
            <label>SKS</label>
            <input type="text" class="form-control input" name = "SKS">
        </div>
    </div>
    <div class="panel-footer" style="text-align: right;">
        <button class="btn btn-success" action= "edit" data-id ="" id="btnSave">Save</button>
    </div>
</div>
<script type="text/javascript">
    var AppForm_Research_Pkm_to_Sks = {
        setDefaultInput : function(){
            $('.input').val('');
            $('.input[name="SKS"]').val('0.0');
            $('#btnSave').attr('action','edit');
            $('#btnSave').attr('data-id','');
            $('#btnSave').prop('disabled',true);
        },
        ActionData : function(selector,action="add",ID=""){
            var data = {};
            $('.input').each(function(){
                var field = $(this).attr('name');
                data[field] = $(this).val();
            })
            var dataform = {
                action : action,
                data : data,
                ID : ID,
            };
            // cek validation jika tidak delete
            var validation = (action == 'delete') ? true : AppForm_Research_Pkm_to_Sks.validation(data);
            if (validation) {
                if (confirm('Are you sure ?')) {
                    loading_button2(selector);
                    var url = base_url_js+"rectorat/master_data/crud_research_pkm_to_sks";
                    var token = jwt_encode(dataform,'UAP)(*');
                    $.post(url,{ token:token },function (resultJson) {
                        AppForm_Research_Pkm_to_Sks.setDefaultInput();
                        end_loading_button2(selector);
                        oTable.ajax.reload( null, false );
                        toastr.success('Success');
                    }).fail(function() {
                        toastr.error("Connection Error, Please try again", 'Error!!');
                        end_loading_button2(selector); 
                    }).always(function() {
                         end_loading_button2(selector);              
                    }); 
                }
            }
        },
        validation : function(arr){
            var toatString = "";
            var result = "";
            for(key in arr){
               switch(key)
               {
                case  "Nm_jns_pub" :
                      if (arr[key] != '') {
                        result = Validation_leastCharacter(3,arr[key],key);
                        if (result['status'] == 0) {
                          toatString += result['messages'] + "<br>";
                        }
                      }
                      break;
                // case  "SKS" :
                //       result = Validation_decimal(arr[key],key);
                //       if (result['status'] == 0) {
                //         toatString += result['messages'] + "<br>";
                //       }
                // case  "SKSPendamping" :
                //       result = Validation_decimal(arr[key],key);
                //       if (result['status'] == 0) {
                //         toatString += result['messages'] + "<br>";
                //       }
                //       break;
                // default:
                //     result = Validation_leastCharacter(3,arr[key],key);
                //     if (result['status'] == 0) {
                //       toatString += result['messages'] + "<br>";
                //     }
               }
            }

            if (toatString != "") {
              toastr.error(toatString, 'Failed!!');
              return false;
            }
            return true
        },
        loaded : function(){
            AppForm_Research_Pkm_to_Sks.setDefaultInput();
        },

        AutoTextAllowed : function(selector){
           var str = selector.val();
           var nm = selector.attr('name');
           var bool = true;
           var rs = '';
            for (var i = 0; i < str.length; i++) {
                var v  = str.charAt(i);
                var chk = number_comma_dot(str,nm);
                if (chk.status == 1) {
                    rs += v;
                }
                else
                {
                    bool = false;
                    rs = ''
                    for (var j = 0; j < (str.length) - 1; j++) {
                         var z  = str.charAt(j);
                        rs += z;
                    }
                    break;
                }
            }

            selector.val(rs);
        },
    };

    $(document).ready(function() {
        AppForm_Research_Pkm_to_Sks.loaded();
    })

    $(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
       var selector = $(this);
       var action = selector.attr('action');
       var ID = selector.attr('data-id');
       AppForm_Research_Pkm_to_Sks.ActionData(selector,action,ID);
    })

    $(document).off('keyup','.input[name="SKS"]').on('keyup','.input[name="SKS"]',function(e){
        var selector = $(this);
        AppForm_Research_Pkm_to_Sks.AutoTextAllowed(selector);
    })

    $(document).off('keydown','.input[name="SKS"]').on('keydown','.input[name="SKS"]',function(e){
        var selector = $(this);
        AppForm_Research_Pkm_to_Sks.AutoTextAllowed(selector);
    })
    
</script>