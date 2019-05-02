
<div class="container">    
            
    <div id="signupbox" style=" margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">Add Request Document</div>
            </div>  
            <div class="panel-body" >
                <form method="post" action=".">

                    <form class="form-horizontal" method="post" >
                        
                        <div class="form-group">
                            <label for="id_username" class="control-label col-md-4"> Name Event </label>
                            <div class="controls col-md-8 ">
                                <input class="input-md  textinput textInput form-control" id="name_event" maxlength="30" name="username" style="margin-bottom: 10px" type="text" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_email" class="control-label col-md-4"> Date Event </label>
                            <div class="controls col-md-8 ">
                                <input class="input-md frmdatepicker form-control" id="id_email" name="date_event" style="margin-bottom: 10px" type="email" />
                            </div>     
                        </div>
                       
                        <div class="form-group">
                          <div id="datetimepicker3" class="input-append">
                            <label class="control-label col-md-4">Event Time </label>
                                <div class="input-group col-md-8">
                                    <input class="input-md textinput form-control" data-format="hh:mm" type="text"></input>
                                        <span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
                                </div>
                          </div>
                        </div>

                        <div class="form-group">
                             <label for="id_password2" class="control-label col-md-4"> Event Location </label>
                             <div class="controls col-md-8 ">
                                <textarea rows="3" cols="5" name="DescriptionFile" id="event_location" style="margin-bottom: 10px" class="form-control"></textarea>
                            </div>
                        </div>
                    
                        <div class="form-group"> 
                            <div style="text-align: right;"></div>
                            <div class="col-md-8 ">
                                <button class="btn btn-danger btn-round btncancel"><span class="fa fa-remove"></span> Cancel</button>
                                <button class="btn btn-success btn-round btnSave"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
                            </div>
                        </div> 
                            
                    </form>

                </form>
            </div>
        </div>
    </div> 
</div>
    
<script>

var defaults = {
    //calendarWeeks: true,
    autoclose: true,
    showClear: true,
    showClose: true,
    allowInputToggle: true,
    useCurrent: false,
    ignoreReadonly: true,
    //minDate: new Date(),
    toolbarPlacement: 'top',
    locale: 'id',
    icons: {
        time: 'fa fa-clock-o',
        date: 'fa fa-calendar',
        up: 'fa fa-angle-up',
        down: 'fa fa-angle-down',
        previous: 'fa fa-angle-left',
        next: 'fa fa-angle-right',
        //today: 'fa fa-dot-circle-o',
        clear: 'fa fa-trash',
        close: 'fa fa-times'
    }
};

  $(function() {
    var optionsTime = $.extend({}, defaults, {format:'HH:mm'});

    $('#datetimepicker3').datetimepicker(optionsTime);

    //$('#datetimepicker3').datetimepicker({
    //  pickDate: false
    //});
  });
</script>

<script>
    $(document).ready(function () {
    $('.frmdatepicker').datepicker({
      dateFormat : 'yy-mm-dd',
      changeMonth : true,
      changeYear : true,
      autoclose: true,
      todayHighlight: true,
      uiLibrary: 'bootstrap'
    });
});
</script>

<script>
   var defaults = {
    calendarWeeks: true,
    showClear: true,
    showClose: true,
    allowInputToggle: true,
    useCurrent: false,
    ignoreReadonly: true,
    minDate: new Date(),
    toolbarPlacement: 'top',
    locale: 'nl',
    icons: {
        time: 'fa fa-clock-o',
        date: 'fa fa-calendar',
        up: 'fa fa-angle-up',
        down: 'fa fa-angle-down',
        previous: 'fa fa-angle-left',
        next: 'fa fa-angle-right',
        today: 'fa fa-dot-circle-o',
        clear: 'fa fa-trash',
        close: 'fa fa-times'
    }
};

$(function() {
    var optionsDatetime = $.extend({}, defaults, {format:'DD-MM-YYYY HH:mm'});
    var optionsDate = $.extend({}, defaults, {format:'DD-MM-YYYY'});
    var optionsTime = $.extend({}, defaults, {format:'HH:mm'});
    
    $('.datepicker').datetimepicker(optionsDate);
    $('.timepicker').datetimepicker(optionsTime);
    $('.datetimepicker').datetimepicker(optionsDatetime);
});

</script>


<script>
    $(document).on('click','.btnSave',function () {
        saverequest();
    });

    function saverequest() {
        
        var idnameegroup = $('#filtereditgroupmodule option:selected').attr('id');
        var idmodule = $('#filtereditgroupname option:selected').attr('id');
        var idmodule = $('#filtereditgroupname').val();
        var Descriptiongroup = $('#editdescriptiongroup').val();
        var IDGroupedit = $('#IDModuledit').val();
        
        if(idnameegroup!=null && idnameegroup!=''
            && idmodule!='' && idmodule!=null
            && Descriptiongroup!='' && Descriptiongroup!=null
            && IDGroupedit!='' && IDGroupedit!=null
            )
        { 
    
            var data = {
                action : 'EditGroupModule',
                formInsert : {
                    idnameegroup : idnameegroup,
                    idmodule : idmodule,
                    Descriptiongroup : Descriptiongroup,
                    IDGroupedit : IDGroupedit
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudGroupModule';
            $.post(url,{token:token},function (result) {
                    
                if(result==0 || result=='0'){
            
                } else { 
                    toastr.success('Edit Group Module Saved','Success');
                    setTimeout(function () {
                    $('#GlobalModal').modal('hide');
                        window.location.href = '';
                    },1000);
                }
            });
        }
        else {
            toastr.error('The form is still empty!','Error');
            $('#GlobalModal').modal('show');
            return;
        }
     }
</script>


