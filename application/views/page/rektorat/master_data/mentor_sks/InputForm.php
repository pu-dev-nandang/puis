<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Input</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;">
        <div class="form-group">
            <label>Mentor Type</label>
            <input type="text" class="form-control input" name = "MentorType">
        </div>
        <div class="form-group">
            <label>SKS</label>
            <input type="text" class="form-control input" name = "SKS">
        </div>
    </div>
    <div class="panel-footer" style="text-align: right;">
        <button class="btn btn-success" action= "add" data-id ="" id="btnSave">Save</button>
    </div>
</div>
<script type="text/javascript">
    var AppForm_Mentor_Type_Sks = {
        setDefaultInput : function(){
            $('.input').val('');
            $('.input[name="SKS"]').maskMoney({thousands:'', decimal:'', precision:0,allowZero: true});
            $('.input[name="SKS"]').maskMoney('mask', '9894'); 
            $('#btnSave').attr('action','add');
            $('#btnSave').attr('data-id','');
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
            var validation = (action == 'delete') ? true : AppForm_Mentor_Type_Sks.validation(data);
            if (validation) {
                if (confirm('Are you sure ?')) {
                    loading_button2(selector);
                    var url = base_url_js+"rectorat/master_data/crud_mentor_type_sks";
                    var token = jwt_encode(dataform,'UAP)(*');
                    $.post(url,{ token:token },function (resultJson) {
                        AppForm_Mentor_Type_Sks.setDefaultInput();
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
                case  "NamaType" :
                      if (arr[key] != '') {
                        result = Validation_leastCharacter(3,arr[key],key);
                        if (result['status'] == 0) {
                          toatString += result['messages'] + "<br>";
                        }
                      }
                      break;
                case  "SKSPerMinutes" :
                      result = Validation_numeric(arr[key],key);
                      if (result['status'] == 0) {
                        toatString += result['messages'] + "<br>";
                      }
                      break;
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
            AppForm_Mentor_Type_Sks.setDefaultInput();
        },
    };

    $(document).ready(function() {
        AppForm_Mentor_Type_Sks.loaded();
    })

    $(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
       var selector = $(this);
       var action = selector.attr('action');
       var ID = selector.attr('data-id');
       AppForm_Mentor_Type_Sks.ActionData(selector,action,ID);
    })
    
</script>