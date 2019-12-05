<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Input Term Payment</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;">
        <div class="form-group">
            <label>Term Payment</label>
            <input type="text" class="form-control input" name = "Name">
        </div>
    </div>
    <div class="panel-footer" style="text-align: right;">
        <button class="btn btn-success" action= "add" data-id ="" id="btnSave">Save</button>
    </div>
</div>
<script type="text/javascript">
    var AppForm_Term_Payment = {
        setDefaultInput : function(){
            $('.input').val('');
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
            var validation = (action == 'delete') ? true : AppForm_Term_Payment.validation(data);
            if (validation) {
                if (confirm('Are you sure ?')) {
                    loading_button2(selector);
                    var url = base_url_js+"purchasing/master/crud_term_payment";
                    var token = jwt_encode(dataform,'UAP)(*');
                    $.post(url,{ token:token },function (resultJson) {
                        AppForm_Term_Payment.setDefaultInput();
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
                case  "TermPayment" :
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
            AppForm_Term_Payment.setDefaultInput();
        },

    };

    $(document).ready(function() {
        AppForm_Term_Payment.loaded();
    })

    $(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
       var selector = $(this);
       var action = selector.attr('action');
       var ID = selector.attr('data-id');
       AppForm_Term_Payment.ActionData(selector,action,ID);
    })

</script>