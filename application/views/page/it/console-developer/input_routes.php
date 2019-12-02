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

<b>Status : </b><i class="fa fa-circle" style="color:#d0af0c;"></i> Transfer To | <i class="fa fa-circle" style="color:lightgreen;"></i> Done <br/>
<div class="tracking-list">
        <div class="tracking-item">
            <div class="tracking-icon status-intransit">
                <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style = "color:lightgreen;" >
                    <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                </svg>
            </div>
            <div class="tracking-date">Dec 02, 2019<span>09:02</span></div>
            <div class="tracking-content">AC<span>GENERAL AFFAIR </span></div><table class = "table" style ="margin-top:15px;"><tr><td style="padding:4px;">Worker</td><td style="padding:4px;">DueDate</td><td style="padding:4px;">Status</td></tr><tr><td style="padding:4px;">Sarwanto</td><td style="padding:4px;"><span>Dec 02, 2019</span></td><td style="padding:4px;"><span style="color: green;"><i class="fa fa-check-circle" aria-hidden="true"></i> done</span></td></tr><tr><td style="padding:4px;"> M Subur</td><td style="padding:4px;"><span>Dec 02, 2019</span></td><td style="padding:4px;"><span style="color: red;"><i class="fa fa-minus-circle" aria-hidden="true"></i> withdrawn</span></td></tr></table><div class = "thumbnail input_form" tokenData = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJJRCI6IjEiLCJUaWNrZXRJRCI6IjEiLCJEZXBhcnRtZW50UmVjZWl2ZWRJRCI6Ik5BLjgiLCJEZXBhcnRtZW50VHJhbnNmZXJUb0lEIjoiTkEuMTIiLCJDYXRlZ29yeVJlY2VpdmVkSUQiOiI0IiwiTWVzc2FnZVJlY2VpdmVkIjoiTW9ob24gZGkgY2VrIHNlZ2VyYSB5YS5cbkthcmVuYSBQYWsgUmVrdG9yIGFrYW4ga2VydWFuZ2FuIHNheWEgdW50dWsgbWVldGluZy5cblxuVGhhbmtzIiwiRmxhZyI6IjAiLCJSZWNlaXZlZFN0YXR1cyI6IjEiLCJTZXRBY3Rpb24iOiIwIiwiUmVjZWl2ZWRCeSI6IjIwMTUwNzciLCJSZWNlaXZlZEF0IjoiMDIgRGVjIDIwMTkgMDk6MDUiLCJDcmVhdGVkQnkiOiIzMTE0MDE2IiwiQ3JlYXRlZEF0IjoiMjAxOS0xMi0wMiAwOTowMjo0MiIsIkNhdGVnb3J5RGVzY3JpcHRpb25zIjoiQUMiLCJEZXBhcnRtZW50SUREZXN0aW5hdGlvbiI6Ik5BLjgiLCJOYW1lRGVwYXJ0bWVudERlc3RpbmF0aW9uIjoiR0VORVJBTCBBRkZBSVIiLCJOYW1lUmVjZWl2ZWRCeSI6IkVyd2luIFNpbnVyYXQiLCJEYXRhUmVjZWl2ZWRfRGV0YWlscyI6W3siSUQiOiIxIiwiUmVjZWl2ZWRJRCI6IjEiLCJOSVAiOiIyMDE2MDY0IiwiRHVlRGF0ZSI6IjIwMTktMTItMDIiLCJTdGF0dXMiOiIyIiwiQXQiOiIyMDE5LTEyLTAyIDEwOjE0OjE1IiwiTmFtZVdvcmtlciI6IlNhcndhbnRvIiwiRHVlRGF0ZVNob3ciOiJEZWMgMDIsIDIwMTkifSx7IklEIjoiMiIsIlJlY2VpdmVkSUQiOiIxIiwiTklQIjoiMjAxOTAzOCIsIkR1ZURhdGUiOiIyMDE5LTEyLTAyIiwiU3RhdHVzIjoiLTEiLCJBdCI6IjIwMTktMTItMDIgMDk6MjA6MTEiLCJOYW1lV29ya2VyIjoiIE0gU3VidXIiLCJEdWVEYXRlU2hvdyI6IkRlYyAwMiwgMjAxOSJ9XSwiUmVjZWl2ZWRBdFRyYWNraW5nIjoiRGVjIDAyLCAyMDE5PHNwYW4-MDk6MDI8L3NwYW4-In0.a4jnqSPpOaUs_EaESPllYX76NEFqWTIEWfVH0gsuRew"><div class = "form-group"><label>Rate</label><select class = "form-control fieldInput" name = "Rate"><option value = "" selected disabled>--Choose Rate--</option><option value = "1">1</option><option value = "2">2</option><option value = "3">3</option><option value = "4">4</option><option value = "5">5</option></select></div><div class = "form-group"><label>Comment</label><textarea class="form-control fieldInput" rows="3" name="Comment"></textarea></div></div></div><div class="tracking-item"><div class="tracking-icon status-intransit"><svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style = "color:lightgreen;" >                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path></svg></div><div class="tracking-date">Dec 02, 2019<span>09:51</span></div><div class="tracking-content">PC<span>IT </span></div><table class = "table" style ="margin-top:15px;"><tr><td style="padding:4px;">Worker</td><td style="padding:4px;">DueDate</td><td style="padding:4px;">Status</td></tr><tr><td style="padding:4px;">Novita Riani Br Ginting</td><td style="padding:4px;"><span>Dec 02, 2019</span></td><td style="padding:4px;"><span style="color: green;"><i class="fa fa-check-circle" aria-hidden="true"></i> done</span></td></tr></table><div class = "thumbnail input_form" tokenData = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJJRCI6IjIiLCJUaWNrZXRJRCI6IjEiLCJEZXBhcnRtZW50UmVjZWl2ZWRJRCI6Ik5BLjEyIiwiRGVwYXJ0bWVudFRyYW5zZmVyVG9JRCI6IiIsIkNhdGVnb3J5UmVjZWl2ZWRJRCI6IjMiLCJNZXNzYWdlUmVjZWl2ZWQiOiJQQyBueWEgbWF0aSBrYXJlbmEgYWlyIGFjIHR1cnVuIiwiRmxhZyI6IjEiLCJSZWNlaXZlZFN0YXR1cyI6IjEiLCJTZXRBY3Rpb24iOiIwIiwiUmVjZWl2ZWRCeSI6IjIwMTgwMTgiLCJSZWNlaXZlZEF0IjoiMDIgRGVjIDIwMTkgMTA6MDkiLCJDcmVhdGVkQnkiOm51bGwsIkNyZWF0ZWRBdCI6IjIwMTktMTItMDIgMDk6NTE6MTgiLCJDYXRlZ29yeURlc2NyaXB0aW9ucyI6IlBDIiwiRGVwYXJ0bWVudElERGVzdGluYXRpb24iOiJOQS4xMiIsIk5hbWVEZXBhcnRtZW50RGVzdGluYXRpb24iOiJJVCIsIk5hbWVSZWNlaXZlZEJ5IjoiQWxoYWRpIFJhaG1hbiIsIkRhdGFSZWNlaXZlZF9EZXRhaWxzIjpbeyJJRCI6IjMiLCJSZWNlaXZlZElEIjoiMiIsIk5JUCI6IjIwMTYwNjUiLCJEdWVEYXRlIjoiMjAxOS0xMi0wMiIsIlN0YXR1cyI6IjIiLCJBdCI6IjIwMTktMTItMDIgMTA6MDk6MTEiLCJOYW1lV29ya2VyIjoiTm92aXRhIFJpYW5pIEJyIEdpbnRpbmciLCJEdWVEYXRlU2hvdyI6IkRlYyAwMiwgMjAxOSJ9XSwiUmVjZWl2ZWRBdFRyYWNraW5nIjoiRGVjIDAyLCAyMDE5PHNwYW4-MDk6NTE8L3NwYW4-In0.u7cSQnENzbRNa67WSAaA8iGe8HHtEm-aUWL8vfiIAOo"><div class = "form-group"><label>Rate</label><select class = "form-control fieldInput" name = "Rate"><option value = "" selected disabled>--Choose Rate--</option><option value = "1">1</option><option value = "2">2</option><option value = "3">3</option><option value = "4">4</option><option value = "5">5</option></select></div><div class = "form-group"><label>Comment</label><textarea class="form-control fieldInput" rows="3" name="Comment"></textarea></div></div></div></div">

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
                    data[field] = $(this).val(); 
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
                    App_input_routes.LoadSetDefault();
                    end_loading_button2(selector);
                    if (server == 'local') {
                        oTable.ajax.reload( null, false );
                    }
                    else
                    {
                        oTable2.ajax.reload( null, false );
                    }
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