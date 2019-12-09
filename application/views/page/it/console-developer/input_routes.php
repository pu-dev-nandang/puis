<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Input</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;">
        <div class="form-group">
            <label>Slug</label>
            <input type="text" class="form-control input" name = "Slug">
        </div>
        <div class="form-group">
            <label>Controller</label>
            <input type="text" class="form-control input" name = "Controller">
        </div>
        <div class="form-group">
            <label>Type</label>
            <select name="Type" id="" class="form-control input">
            	<option value="pcam" selected>Pcam</option>
            	<option value="lecturer">Lecturer</option>
            	<option value="student">Student</option>
            	<option value="parent">Parent</option>
            </select>
        </div>
        <div class="form-group">
            <label>Department</label>
            <select name="Department" id="" class="input">
            </select>
        </div>
    </div>
    <div class="panel-footer" style="text-align: right;">
        <button class="btn btn-success" action= "add" data-id ="" id="btnSave" server = "local">Save</button>
    </div>
</div>

<script type="text/javascript">
 var App_input_routes = {
    LoadSetDefault : function(){
        $('input').not('.input[name="Type"]').val('');
        $('.input[name="Expired"]').val('<?php echo date('Y-m-d') ?>');
        $('#btnSave').attr('action','add');
        $('#btnSave').attr('data-id','');
        $('#btnSave').attr('server','local');
        $('.input[name="Slug"]').focus();
        App_input_routes.LoadSelectDepartment();
    },
    LoadSelectDepartment : function(){
        var selector = $('.input[name="Department"]');
        selector.empty();
        var url = base_url_js + "api/__getAllDepartementPU";
        $.post(url,{ Show:'all' },function (resultJson) {
                
        }).done(function(resultJson) {
            for (var i = 0; i < resultJson.length; i++) {
               selector.append('<option value = "'+resultJson[i].Code+'" >'+resultJson[i].Name2+'</option>');
            }
            selector.select2({

            });
        }).fail(function() {
            toastr.error("Connection Error, Please try again", 'Error!!');
            
        });

    },
    SubmitData : function(action='add',ID='',selector,server='local'){
        var data = {};
        $('.input').each(function(){
            var field = $(this).attr('name');
            if (field == 'Type') {
               data.Type = $(this).find('option:selected').val();
            }
            else if (field == 'Department') {
               data.Department = $(this).find('option:selected').val();
            }
            else
            {
                if (field != undefined) {
                    data[field] = $(this).val().trim(); 
                }
               
            }
        })

        // validation 
        var validation =  (action == 'delete') ? true : App_input_routes.validation(data);
        if (validation) {
            if (confirm('Are you sure ?')) {
                var dataform = {
                    ID : ID,
                    data : data,
                    action : action,
                    server : server,
                };
                var token = jwt_encode(dataform,"UAP)(*");
                loading_button2(selector);
                var url = base_url_js + "it/console-developer/routes/submit";
                $.post(url,{ token:token },function (resultJson) {
                        
                }).done(function(resultJson) {
                    if (resultJson == 1) {
                        App_input_routes.LoadSetDefault();
                        if (server == 'local') {
                            oTable.ajax.reload( null, false );
                        }
                        else
                        {
                            oTable2.ajax.reload( null, false );
                        }
                        toastr.success('Success');
                    }
                    else
                    {
                        toastr.error(resultJson, 'Error!!');
                    }

                    end_loading_button2(selector);
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
            for(var key in arr) {
               switch(key)
               {
                default:
                    result = Validation_required(arr[key],key);
                    if (result['status'] == 0) {
                      toatString += result['messages'] + "<br>";
                    }
               }
            }
            if (toatString != "") {
              toastr.error(toatString, 'Failed!!');
              return false;
            }
            return true
    },

    loaded : function(){
        App_input_routes.LoadSetDefault();
    },

 };

$(document).ready(function() {
 App_input_routes.loaded();
})  
$(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
    var ID = $(this).attr('data-id');
    var selector = $(this);
    var action = $(this).attr('action');
    var server = $(this).attr('server');
    App_input_routes.SubmitData(action,ID,selector,server);
})
</script>