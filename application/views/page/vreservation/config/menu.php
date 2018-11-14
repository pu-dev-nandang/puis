<link href="<?php echo base_url('assets/custom/jquery-ui.css'); ?>" rel="stylesheet" type="text/css" />
<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i>Daftar Menu</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                      <span data-smt="" class="btn btn-xs btn-add-menu btn-add btn-add-menu-auth">
                        <i class="icon-plus"></i> Add Menu
                       </span>
                    </div>
                </div>
            </div>
            <div class="widget-content">
                <!-- <div class = 'row'> -->
                	<div id= "loadtableMenu"></div>
                <!-- </div> -->
                <!-- -->
            </div>
        </div>
    </div>
</div>
<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i>Group Privileges</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                      <span data-smt="" class="btn btn-xs btn-add-groupP btn-add">
                        <i class="icon-plus"></i> Add Group Privileges
                       </span>
                    </div>
                </div>
            </div>
            <div class="widget-content">
                <div id= "loadtableGroupPrevileges"></div>
            </div>
        </div>
    </div>
</div>
<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i>Setting Group User Privileges</h4>
            </div>
            <div class="widget-content">
                <div class = "row"> 
                    <div class="col-xs-3">
                        Choose Group User Previleges :
                        <select class="full-width-fix" id="selectGroupuUser">
                            <option></option>
                        </select>
                    </div> 
                    <div class="col-xs-3">
                        Choose Menu :
                        <select class="full-width-fix" id="selectMenuUser">
                            <option></option>
                        </select>
                    </div>    
                </div>
                <div class = "row">
                    <div id='LoadSubMenu'></div> 
                </div>
                <div class="row" id='LoadBtnSbmt'></div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i>Edit & List Group User Privileges</h4>
            </div>
            <div class="widget-content">
                <div class = "row"> 
                    <div class="col-xs-3">
                        Choose Group User Privileges :
                        <select class="full-width-fix" id="selectGroupuUser2">
                            <option></option>
                        </select>
                    </div> 
                </div>
                <br>
                <div class = "row">
                    <div id='LoadTblGroupUserPrevileges' class="col-md-12"></div> 
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.temp;
	$(document).ready(function () {
        loadMenuPrevileges(loadDatamenuPrevileges);
        loadGroupPrevileges(loadDataGroupPrevileges);
		// loadAutoCompleteUser();
        loadSelectMenuUser();
        //loadSelectGroupUser();
        // loadAutoCompleteUser2();
	});

	$(document).on('click','.btn-add-menu', function () {
	   modal_generate('add','Add Menu');
	});

	function modal_generate(action,title) {
	    var url = base_url_js+"vreservation/config/modalform_previleges";
	    var data = {
	        Action : action,
	    };
	    var token = jwt_encode(data,"UAP)(*");
	    $.post(url,{ token:token }, function (html) {
	        $('#GlobalModal .modal-header').html('<h4 class="modal-title">'+title+'</h4>');
	        $('#GlobalModal .modal-body').html(html);
	        $('#GlobalModal .modal-footer').html(' ');
	        $('#GlobalModal').modal({
	            'show' : true,
	            'backdrop' : 'static'
	        });
	    })
	}

    $(document).on('click','#btnAddItemJenismenu', function () {
        // $.removeCookie('__tawkuuid', { path: '/' });
        loading_button('#btnAddItemJenismenu');
        var url = base_url_js+'vreservation/config/menu-previleges/get_menu/save';
        var InputJenisMenu = $("#InputJenisMenu").val();
        var data = {
                    InputJenisMenu : InputJenisMenu,
                    };
        var token = jwt_encode(data,"UAP)(*");          
        $.post(url,{token:token},function (data_json) {
            // jsonData = data_json;
            // var obj = JSON.parse(data_json); 
            // console.log(obj);
        }).done(function() {
          loadSelectMenu();
        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {
         $('#btnAddItemJenismenu').prop('disabled',false).html('<i class="fa fa-floppy-o" aria-hidden="true"></i>');
        });
    });

    $(document).on('click','#ModalbtnSaveForm', function () {
        // $.removeCookie('__tawkuuid', { path: '/' });
        loading_button('#ModalbtnSaveForm');
        var url = base_url_js+'vreservation/config/menu-previleges/get_submenu/save';
        var selectMenu = $('#selectMenu').val();
        var sub_menu1 = $("#sub_menu1").val();
        var sub_menu2 = $("#sub_menu2").val();
        var Slug = $("#Slug").val();
        var Controller = $("#Controller").val();
        var chkPrevileges = getAllCheckbox('chkPrevileges');
        var data = {
                    selectMenu : selectMenu,
                    sub_menu1 : sub_menu1,
                    sub_menu2 : sub_menu2,
                    chkPrevileges : chkPrevileges,
                    Slug : Slug,
                    Controller : Controller
                    };
        var token = jwt_encode(data,"UAP)(*");
        if (validation2(data)) {
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
            }).done(function() {
              loadMenuPrevileges(loadDatamenuPrevileges);
              $('#GlobalModal').modal('hide');
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
             $('#ModalbtnSaveForm').prop('disabled',false).html('Save');

            });
        }
        else
        {
            $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
        }          
        
    });

    function validation2(arr)
    {
      var toatString = "";
      var result = "";
      for(var key in arr) {
         switch(key)
         {
          case  "chkPrevileges" :
                if(arr[key].length == 0)
                {
                  toatString += 'Anda belum pilih otorisasi' + "<br>";  
                }
                break;
          case  "sub_menu1" :
                result = Validation_required(arr[key],key);
                if (result['status'] == 0) {
                  toatString += result['messages'] + "<br>";
                }
                break;
         }

      }
      if (toatString != "") {
        // toastr.error(toatString, 'Failed!!');
        $("#msgMENU").html(toatString);
        $("#msgMENU").removeClass("hide");
        return false;
      }

      return true;
    }

    function getAllCheckbox(name)
    {
        var allVals = [];
        $('input[name="'+name+'"]:checked').each(function() {
           allVals.push($(this).val());
        });

        return allVals;
    }

    function loadGroupPrevileges(callback)
    {
        // Some code
        // console.log('test');
        $("#loadtableGroupPrevileges").empty();
        var table = '<table class="table table-striped table-bordered table-hover table-checkable datatable2">'+
        '<thead>'+
            '<tr>'+
                '<th style="width: 10%;" class = "btn-edit">Group Name</th>'+
                '<th style="width: 4%;" class = "btn-delete">Action</th>'+
            '</tr>'+
        '</thead>'+
        '<tbody>'+
        '</tbody>'+
        '</table>';
        //$("#loadtableNow").empty();
        $("#loadtableGroupPrevileges").html(table);

        /*if (typeof callback === 'function') { 
            callback(); 
        }*/
        callback();
    }

    function loadDataGroupPrevileges()
    {
        var url = base_url_js+'vreservation/getGroupPrevileges'
    // loading_page('#loadtableNow');
        $.post(url,function (data_json) {
            var response = jQuery.parseJSON(data_json);
            // $("#loadingProcess").remove();
            for (var i = 0; i < response.length; i++) {
                $(".datatable2 tbody").append(
                    '<tr>'+
                        '<td><input type = "text" class = "form-control GroupPrevileges" value ="'+response[i]['GroupAuth']+'" id-key = "'+response[i]['ID']+'"><div class = "hide">'+response[i]['GroupAuth']+'</div></td>'+
                        '<td class = "btn-delete"><span class = "btn btn-xs btn-delete btn-delete-groupp btn-danger" id-key = "'+response[i]['ID']+'"><i class="fa fa-trash"></i></span>'+'</td>'+
                    '</tr>' 
                    );
            }
        }).done(function() {
            LoaddataTableStandard('.datatable2');
        })
    }
	

    function loadMenuPrevileges(callback)
    {
        // Some code
        // console.log('test');
        $("#loadtableMenu").empty();
        var table = '<table class="table table-striped table-bordered table-hover table-checkable datatable4">'+
        '<thead>'+
            '<tr>'+
                '<th style="width: 10%;">Menu</th>'+
                '<th style="width: 13%;">Sub Menu 1</th>'+
                '<th style="width: 13%;">Sub Menu 2</th>'+
                '<th style="width: 21%;" class = "btn-edit-menu-auth">URI</th>'+
                '<th style="width: 21%;" class = "btn-edit-menu-auth">Controler</th>'+
                '<th style="width: 4%;" class = "btn-edit-menu-auth">Read</th>'+
                '<th style="width: 4%;" class = "btn-edit-menu-auth">Write</th>'+
                '<th style="width: 4%;" class = "btn-edit-menu-auth">Update</th>'+
                '<th style="width: 4%;" class = "btn-edit-menu-auth">Delete</th>'+
                '<th style="width: 4%;" class = "btn-delete-menu-auth">Action</th>'+
            '</tr>'+
        '</thead>'+
        '<tbody>'+
        '</tbody>'+
        '</table>';
        //$("#loadtableNow").empty();
        $("#loadtableMenu").html(table);

        /*if (typeof callback === 'function') { 
            callback(); 
        }*/
        callback();
    }

    function loadDatamenuPrevileges()
    {
        var url = base_url_js+'vreservation/config/menu-previleges/get_submenu/show'
    // loading_page('#loadtableNow');
        $.post(url,function (data_json) {
            var response = jQuery.parseJSON(data_json);
            // $("#loadingProcess").remove();
            for (var i = 0; i < response.length; i++) {
                var read = (response[i]['read'] == 1) ? 'True' : 'False';
                var write = (response[i]['write'] == 1) ? 'True' : 'False';
                var update = (response[i]['update'] == 1) ? 'True' : 'False';
                var deletee = (response[i]['delete'] == 1) ? 'True' : 'False';
                $(".datatable4 tbody").append(
                    '<tr>'+
                        '<td><input type = "text" class = "form-control Menu" value ="'+response[i]['Menu']+'" id-key = "'+response[i]['ID_Menu']+'"><div class = "hide">'+response[i]['Menu']+'</div></td>'+
                        '<td><input type = "text" class = "form-control SubMenu1" value ="'+response[i]['SubMenu1']+'" id-key = "'+response[i]['ID']+'"><div class = "hide">'+response[i]['SubMenu1']+'</div></td>'+
                        '<td><input type = "text" class = "form-control SubMenu2" value ="'+response[i]['SubMenu2']+'" id-key = "'+response[i]['ID']+'"><div class = "hide">'+response[i]['SubMenu2']+'<div></td>'+
                        '<td class = "btn-edit-menu-auth"><input type = "text" class = "form-control Slug" value ="'+response[i]['Slug']+'" id-key = "'+response[i]['ID']+'"><div class = "hide">'+response[i]['Slug']+'<div></td>'+
                        '<td class = "btn-edit-menu-auth"><input type = "text" class = "form-control Controller" value ="'+response[i]['Controller']+'" id-key = "'+response[i]['ID']+'"><div class = "hide">'+response[i]['Controller']+'<div></td>'+
                        '<td class = "btn-edit-menu-auth"><select class = "read" id-key = "'+response[i]['ID']+'"><option value = "'+response[i]['read']+'">'+read+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td class = "btn-edit-menu-auth"><select class = "write" id-key = "'+response[i]['ID']+'"><option value = "'+response[i]['write']+'">'+write+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td class = "btn-edit-menu-auth"><select class = "update" id-key = "'+response[i]['ID']+'"><option value = "'+response[i]['update']+'">'+update+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td class = "btn-edit-menu-auth"><select class = "delete" id-key = "'+response[i]['ID']+'"><option value = "'+response[i]['delete']+'">'+deletee+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td class = "btn-delete-menu-auth"><span class = "btn btn-xs btn-delete btn-delete-menusub btn-danger" id-key = "'+response[i]['ID']+'"><i class="fa fa-trash"></i></span>'+'</td>'+
                    '</tr>' 
                    );
            }
        }).done(function() {
            LoaddataTableStandard('.datatable4');
        })
    }

    $(document).on('keypress','.Controller', function (e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            var ID_Menu = $(this).attr('id-key');
            var Controller = $(this).val();
            var url = base_url_js+'vreservation/config/menu-previleges/get_submenu/update';
            var data = {
                        ID_Menu : ID_Menu,
                        Controller : Controller,
                        };
            var token = jwt_encode(data,"UAP)(*");          
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
            }).done(function() {
              //loadMenuPrevileges(loadDatamenuPrevileges);
              //loadSubMenu();
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            });
        }
    });

    $(document).on('keypress','.Slug', function (e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            var ID_Menu = $(this).attr('id-key');
            var Slug = $(this).val();
            var url = base_url_js+'vreservation/config/menu-previleges/get_submenu/update';
            var data = {
                        ID_Menu : ID_Menu,
                        Slug : Slug,
                        };
            var token = jwt_encode(data,"UAP)(*");          
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
            }).done(function() {
              //loadMenuPrevileges(loadDatamenuPrevileges);
              //loadSubMenu();
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            });
        }
    });

    $(document).on('keypress','.Menu', function (e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            var ID_Menu = $(this).attr('id-key');
            var Menu = $(this).val();
            var url = base_url_js+'vreservation/config/menu-previleges/get_submenu/update';
            var data = {
                        ID_Menu : ID_Menu,
                        Menu : Menu,
                        };
            var token = jwt_encode(data,"UAP)(*");          
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
            }).done(function() {
              loadMenuPrevileges(loadDatamenuPrevileges);
              loadSubMenu();
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            });
        }
    });

    $(document).on('keypress','.SubMenu1', function (e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            var ID = $(this).attr('id-key');
            var SubMenu1 = $(this).val();
            var url = base_url_js+'vreservation/config/menu-previleges/get_submenu/update';
            var data = {
                        ID : ID,
                        SubMenu1 : SubMenu1,
                        };
            var token = jwt_encode(data,"UAP)(*");          
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
            }).done(function() {
              //loadMenuPrevileges(loadDatamenuPrevileges);
              //loadSubMenu();
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            });
        }
    });

    $(document).on('keypress','.SubMenu2', function (e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            var ID = $(this).attr('id-key');
            var SubMenu2 = $(this).val();
            var url = base_url_js+'vreservation/config/menu-previleges/get_submenu/update';
            var data = {
                        ID : ID,
                        SubMenu2 : SubMenu2,
                        };
            var token = jwt_encode(data,"UAP)(*");          
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
            }).done(function() {
              //loadMenuPrevileges(loadDatamenuPrevileges);
              //loadSubMenu();
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            });
        }
    });

    $(document).on('change','.read', function (e) {
        var ID = $(this).attr('id-key');
        var read = $(this).val();
        var url = base_url_js+'vreservation/config/menu-previleges/get_submenu/update';
        var data = {
                    ID : ID,
                    read : read,
                    };
        var token = jwt_encode(data,"UAP)(*");          
        $.post(url,{token:token},function (data_json) {
            // jsonData = data_json;
            // var obj = JSON.parse(data_json); 
            // console.log(obj);
        }).done(function() {
          //loadMenuPrevileges(loadDatamenuPrevileges);
          //loadSubMenu();
        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {

        });
    });

    $(document).on('change','.write', function (e) {
        var ID = $(this).attr('id-key');
        var write = $(this).val();
        var url = base_url_js+'vreservation/config/menu-previleges/get_submenu/update';
        var data = {
                    ID : ID,
                    write : write,
                    };
        var token = jwt_encode(data,"UAP)(*");          
        $.post(url,{token:token},function (data_json) {
            // jsonData = data_json;
            // var obj = JSON.parse(data_json); 
            // console.log(obj);
        }).done(function() {
          //loadMenuPrevileges(loadDatamenuPrevileges);
          //loadSubMenu();
        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {

        });
    });

    $(document).on('change','.update', function (e) {
        var ID = $(this).attr('id-key');
        var update = $(this).val();
        var url = base_url_js+'vreservation/config/menu-previleges/get_submenu/update';
        var data = {
                    ID : ID,
                    update : update,
                    };
        var token = jwt_encode(data,"UAP)(*");          
        $.post(url,{token:token},function (data_json) {
            // jsonData = data_json;
            // var obj = JSON.parse(data_json); 
            // console.log(obj);
        }).done(function() {
          //loadMenuPrevileges(loadDatamenuPrevileges);
          //loadSubMenu();
        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {

        });
    });

    $(document).on('change','.delete', function (e) {
        var ID = $(this).attr('id-key');
        var deletee = $(this).val();
        var url = base_url_js+'vreservation/config/menu-previleges/get_submenu/update';
        var data = {
                    ID : ID,
                    delete : deletee,
                    };
        var token = jwt_encode(data,"UAP)(*");          
        $.post(url,{token:token},function (data_json) {
            // jsonData = data_json;
            // var obj = JSON.parse(data_json); 
            // console.log(obj);
        }).done(function() {
          //loadMenuPrevileges(loadDatamenuPrevileges);
          //loadSubMenu();
        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {

        });
    });

    $(document).on('click','.btn-delete-menusub', function (e) {
        var ID = $(this).attr('id-key');
        var url = base_url_js+'vreservation/config/menu-previleges/get_submenu/delete';
        var data = {
                    ID : ID,
                    };
        var token = jwt_encode(data,"UAP)(*");
        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Are you sure ? </b> ' +
            '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
            '</div>');
        $('#NotificationModal').modal('show');

        $("#confirmYes").click(function(){
            $('#NotificationModal .modal-header').addClass('hide');
            $('#NotificationModal .modal-body').html('<center>' +
                '                    <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>' +
                '                    <br/>' +
                '                    Loading Data . . .' +
                '                </center>');
            $('#NotificationModal .modal-footer').addClass('hide');
            $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
            });
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
                //location.reload();
                $('#NotificationModal').modal('hide');
            }).done(function() {
              loadMenuPrevileges(loadDatamenuPrevileges);
              loadSubMenu();
              $('#NotificationModal').modal('hide');
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
              $('#NotificationModal').modal('hide');
            }).always(function() {
                $('#NotificationModal').modal('hide');
            });
        })          
    });

    function loadSelectMenuUser()
    {
        var url = base_url_js+"vreservation/getMenu";
        $('#selectMenuUser').empty()
        $.post(url,function (data_json) {
            var obj = JSON.parse(data_json);
              for(var i=0;i<obj.length;i++){
                  var selected = (i==0) ? 'selected' : '';
                  //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
                  $('#selectMenuUser').append('<option value="'+obj[i].ID+'" '+selected+'>'+obj[i].Menu+'</option>');
              }
              $('#selectMenuUser').select2({
                 //allowClear: true
              });
        }).done(function () {
           //loadSubMenu();
          // console.log('loadmenu success');
          loadSelectGroupUser();
        });
    }

    function loadSelectGroupUser()
    {
        var url = base_url_js+"vreservation/getGroupPrevileges";
        $('#selectGroupuUser').empty()
        $('#selectGroupuUser2').empty()
        $.post(url,function (data_json) {
            var obj = JSON.parse(data_json);
            //$('#selectGroupuUser').append('<option value="'+'0'+'" '+''+'>'+'--Choice Group User --'+'</option>');
              for(var i=0;i<obj.length;i++){
                  var selected = (i==0) ? 'selected' : '';
                  //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
                  $('#selectGroupuUser').append('<option value="'+obj[i].ID+'" '+selected+'>'+obj[i].GroupAuth+'</option>');
                  $('#selectGroupuUser2').append('<option value="'+obj[i].ID+'" '+selected+'>'+obj[i].GroupAuth+'</option>');
              }
              $('#selectGroupuUser').select2({
                 //allowClear: true
              });

              $('#selectGroupuUser2').select2({
                 //allowClear: true
              });
        }).done(function () {
           loadSubMenu();
           loadMenuPrevilegesGroupUser(loadDatamenuPrevilegesGroupUser)
          // console.log('loadmenu success');
        });
    }

    $(document).on('change','#selectMenuUser', function () {
        loadSubMenu();
    });

    function loadSubMenu()
    {
        $("#LoadSubMenu").empty();
        $("#LoadBtnSbmt").empty();
        var value = $("#selectMenuUser").val();
        var GroupUser = $("#selectGroupuUser").val();
        var url = base_url_js+"vreservation/config/menu-previleges/get_submenu_by_menu";
        var data = {
                    Menu : value,
                    GroupUser : GroupUser
                    };
        var token = jwt_encode(data,"UAP)(*");          
        $.post(url,{token:token},function (data_json) {
            var obj = JSON.parse(data_json);
            // console.log(obj);
            var tmp1 = '<br><div class="col-sm-8">';
            var tbl1 = '<table class="table" id ="tbl_set_submenu">';
            var isi = '';
            for (var i = 0; i < obj.length; i++) {
                var read = (obj[i]['read'] == 1) ? '<input type="checkbox" name="chkPrevilegesUser" class = "chkPrevilegesUser" value="Read" id-key= "'+obj[i]['ID']+'">&nbsp Read' : '';
                var write = (obj[i]['write'] == 1) ? '<input type="checkbox" name="chkPrevilegesUser" class = "chkPrevilegesUser" value="Write" id-key= "'+obj[i]['ID']+'">&nbsp Write' : '';
                var update = (obj[i]['update'] == 1) ? '<input type="checkbox" name="chkPrevilegesUser" class = "chkPrevilegesUser" value="Update" id-key= "'+obj[i]['ID']+'">&nbsp Update' : '';
                var deletee = (obj[i]['delete'] == 1) ? '<input type="checkbox" name="chkPrevilegesUser" class = "chkPrevilegesUser" value="Delete" id-key= "'+obj[i]['ID']+'">&nbsp Delete' : '';
                isi += '<tr>'+
                            '<td>Submenu 1 :'+obj[i].SubMenu1+'</td>'+
                            '<td>Submenu 2 :'+obj[i].SubMenu2+'</td>'+
                            '<td>'+read+'</td>'+
                            '<td>'+write+'</td>'+
                            '<td>'+update+'</td>'+
                            '<td>'+deletee+'</td>'+
                        '</tr>'    

            }
            var tbl2 = '</table>';
            var tmp2 = '</div>';
            $("#LoadSubMenu").html(tmp1+tbl1+isi+tbl2+tmp2);
            if (obj.length > 0) {
                $("#LoadBtnSbmt").html('<div class="col-xs-12" align = "right"><button class="btn btn-inverse btn-notification btn-approve" id="btn-sbmt-user">Submit</button></div>');
            }
            if (obj.length == 0) {
                $("#LoadSubMenu").html('<br><p align="center">---No Result Data---</p>');
            }
            
        })
        
    }

    $(document).on('click','#btn-sbmt-user', function () {
        loading_button('#btn-sbmt-user');
 
        var getData = getAllData();
        // console.log(data);
        var url = base_url_js+'vreservation/config/menu-previleges/groupuser/save';
        var ID_Menu = $("#selectMenuUser").val();
        var GroupUser = $("#selectGroupuUser").val();
        var data = {
                    checkbox : getData,
                    ID_Menu : ID_Menu,
                    ID_GroupUSer : GroupUser
                    };
        if (validationInput = validation(data)) {
            var token = jwt_encode(data,"UAP)(*");
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
            }).done(function() {
              $('#btn-sbmt-user').prop('disabled',false).html('Submit');
              loadSubMenu();
              loadMenuPrevilegesGroupUser(loadDatamenuPrevilegesGroupUser);
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
                $('#btn-sbmt-user').prop('disabled',false).html('Submit');
            });
        }
        else
        {
           $('#btn-sbmt-user').prop('disabled',false).html('Submit');
        }

    });

    function getAllData()
    {
        var allVals = [];
        var name = 'chkPrevilegesUser';
        $('input[name="'+name+'"]:checked').each(function() {
           var data = {
                value : $(this).val(),
                ID : $(this).attr('id-key')
           };
           allVals.push(data);
        });

        return allVals;

    }

    function validation(arr)
    {
      var toatString = "";
      var result = "";
      for(var key in arr) {
         switch(key)
         {
          case  "checkbox" :
                if(arr[key].length == 0)
                {
                  toatString += 'Anda belum pilih otorisasi' + "<br>";  
                }
                break;
          case  "ID_GroupUSer" :
                result = Validation_required(arr[key],key);
                if (result['status'] == 0) {
                  toatString += result['messages'] + "<br>";
                }
                break;
         }

      }
      if (toatString != "") {
        toastr.error(toatString, 'Failed!!');
        return false;
      }

      return true;
    }

    function loadMenuPrevilegesGroupUser(callback)
    {
        // Some code
        // console.log('test');
        $("#LoadTblGroupUserPrevileges").empty();
        var table = '<table class="table table-striped table-bordered table-hover table-checkable datatable" id ="MenuPrevilegesGroupUser">'+
        '<thead>'+
            '<tr>'+
                '<th style="width: 106px;">Group Name</th>'+
                '<th style="width: 106px;">Menu</th>'+
                '<th style="width: 15px;" class = "btn-edit">Sub Menu 1</th>'+
                '<th style="width: 15px;" class = "btn-edit">Sub Menu 2</th>'+
                '<th style="width: 15px;" class = "btn-edit">Read</th>'+
                '<th style="width: 15px;" class = "btn-edit">Write</th>'+
                '<th style="width: 15px;" class = "btn-edit">Update</th>'+
                '<th style="width: 15px;" class = "btn-edit">Delete</th>'+
                '<th style="width: 15px;" class = "btn-delete-menu-auth">Action</th>'+
            '</tr>'+
        '</thead>'+
        '<tbody>'+
        '</tbody>'+
        '</table>';
        //$("#loadtableNow").empty();
        $("#LoadTblGroupUserPrevileges").html(table);

        /*if (typeof callback === 'function') { 
            callback(); 
        }*/
        callback();
    }

    $(document).on('change','#selectGroupuUser2', function () {
        loadMenuPrevilegesGroupUser(loadDatamenuPrevilegesGroupUser);
    });

    function loadDatamenuPrevilegesGroupUser()
    {
        var url = base_url_js+'vreservation/config/menu-previleges/get_previleges_group/show';
        var Nama_search = $("#selectGroupuUser2").val();
        var data =  {
                        Nama_search : Nama_search,
                    };
        var token = jwt_encode(data,"UAP)(*");
    // loading_page('#loadtableNow');
        $.post(url,{token:token},function (data_json) {
            var response = jQuery.parseJSON(data_json);
            console.log(response);
            // $("#loadingProcess").remove();
            for (var i = 0; i < response.length; i++) {
                var read = (response[i]['read'] == 1) ? 'True' : 'False';
                var write = (response[i]['write'] == 1) ? 'True' : 'False';
                var update = (response[i]['update'] == 1) ? 'True' : 'False';
                var deletee = (response[i]['delete'] == 1) ? 'True' : 'False';
                $("#MenuPrevilegesGroupUser tbody").append(
                    '<tr>'+
                        '<td>'+response[i]['GroupAuth']+'</td>'+
                        '<td>'+response[i]['Menu']+'</td>'+
                        '<td>'+response[i]['SubMenu1']+'</td>'+
                        '<td>'+response[i]['SubMenu2']+'</td>'+
                        '<td class = "btn-edit"><select class = "readUser" id-key = "'+response[i]['ID_previleges']+'" auth-menu = "'+response[i]['readMenu']+'"><option value = "'+response[i]['read']+'">'+read+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td class = "btn-edit"><select class = "writeUser" id-key = "'+response[i]['ID_previleges']+'" auth-menu = "'+response[i]['writeMenu']+'"><option value = "'+response[i]['write']+'">'+write+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td class = "btn-edit"><select class = "updateUser" id-key = "'+response[i]['ID_previleges']+'" auth-menu = "'+response[i]['updateMenu']+'"><option value = "'+response[i]['update']+'">'+update+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td class = "btn-edit"><select class = "deleteUser" id-key = "'+response[i]['ID_previleges']+'" auth-menu = "'+response[i]['deleteMenu']+'"><option value = "'+response[i]['delete']+'">'+deletee+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td class = ""><span class = "btn btn-xs btn-delete-previleges btn-danger" id-key = "'+response[i]['ID_previleges']+'"><i class="fa fa-trash"></i></span>'+'</td>'+
                    '</tr>' 
                    );
            }
        }).done(function() {
            LoaddataTableStandard('#MenuPrevilegesGroupUser');
        })
    }

    $(document).on('change','.readUser', function (e) {
        var ID = $(this).attr('id-key');
        var read = $(this).val();
        var auth_menu = $(this).attr('auth-menu');
        if (auth_menu == 0) {
            toastr.error('Menu ini tidak memiliki akses read', 'Failed!!');
            loadMenuPrevilegesGroupUser(loadDatamenuPrevilegesGroupUser);
        }
        else
        {
            var url = base_url_js+'vreservation/config/menu-previleges/groupuser/update';
            var data = {
                        ID : ID,
                        read : read,
                        };
            var token = jwt_encode(data,"UAP)(*");          
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
            }).done(function() {
              //loadMenuPrevilegesUser(loadDatamenuPrevilegesUser);
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            });
        }
        
    });

    $(document).on('change','.writeUser', function (e) {
        var ID = $(this).attr('id-key');
        var write = $(this).val();
        var auth_menu = $(this).attr('auth-menu');
        if (auth_menu == 0) {
            toastr.error('Menu ini tidak memiliki akses write', 'Failed!!');
            loadMenuPrevilegesGroupUser(loadDatamenuPrevilegesGroupUser);
        }
        else
        {
            var url = base_url_js+'vreservation/config/menu-previleges/groupuser/update';
            var data = {
                        ID : ID,
                        write : write,
                        };
            var token = jwt_encode(data,"UAP)(*");          
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
            }).done(function() {
              //loadMenuPrevilegesUser(loadDatamenuPrevilegesUser);
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            });
        }
        
    });

    $(document).on('change','.updateUser', function (e) {
        var ID = $(this).attr('id-key');
        var update = $(this).val();
        var auth_menu = $(this).attr('auth-menu');
        if (auth_menu == 0) {
            toastr.error('Menu ini tidak memiliki akses update', 'Failed!!');
            loadMenuPrevilegesGroupUser(loadDatamenuPrevilegesGroupUser);
        }
        else
        {
            var url = base_url_js+'vreservation/config/menu-previleges/groupuser/update';
            var data = {
                        ID : ID,
                        update : update,
                        };
            var token = jwt_encode(data,"UAP)(*");          
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
            }).done(function() {
              //loadMenuPrevilegesUser(loadDatamenuPrevilegesUser);
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            });
        }
        
    });

    $(document).on('change','.deleteUser', function (e) {
        var ID = $(this).attr('id-key');
        var deletee = $(this).val();
        var auth_menu = $(this).attr('auth-menu');
        if (auth_menu == 0) {
            toastr.error('Menu ini tidak memiliki akses delete', 'Failed!!');
            loadMenuPrevilegesGroupUser(loadDatamenuPrevilegesGroupUser);
        }
        else
        {
            var url = base_url_js+'vreservation/config/menu-previleges/groupuser/update';
            var data = {
                        ID : ID,
                        delete : deletee,
                        };
            var token = jwt_encode(data,"UAP)(*");          
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
            }).done(function() {
              //loadMenuPrevilegesUser(loadDatamenuPrevilegesUser);
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            });
        }
        
    });

    $(document).on('click','.btn-delete-previleges', function (e) {
        var ID = $(this).attr('id-key');
        var url = base_url_js+'vreservation/config/menu-previleges/groupuser/delete';
        var data = {
                    ID : ID,
                    };
        var token = jwt_encode(data,"UAP)(*");
        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Are you sure ? </b> ' +
            '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
            '</div>');
        $('#NotificationModal').modal('show');

        $("#confirmYes").click(function(){
            $('#NotificationModal .modal-header').addClass('hide');
            $('#NotificationModal .modal-body').html('<center>' +
                '                    <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>' +
                '                    <br/>' +
                '                    Loading Data . . .' +
                '                </center>');
            $('#NotificationModal .modal-footer').addClass('hide');
            $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
            });
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
                $('#NotificationModal').modal('hide');
            }).done(function() {
              loadMenuPrevilegesGroupUser(loadDatamenuPrevilegesGroupUser);
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            });
        })          
        
    });

    $(document).on('click','.btn-add-groupP', function () {
       modal_generate2('add','Add Group User');
    });

    function modal_generate2(action,title) {
        var url = base_url_js+"vreservation/config/modalform_group_user";
        var data = {
            Action : action,
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token }, function (html) {
            $('#GlobalModal .modal-header').html('<h4 class="modal-title">'+title+'</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html(' ');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        })
    }

    $(document).on('click','#ModalbtnSaveForm2', function () {
        // $.removeCookie('__tawkuuid', { path: '/' });
        loading_button('#ModalbtnSaveForm2');
        var url = base_url_js+'vreservation/config/groupuser/save';
        var groupName = $('#groupName').val();
        var data = {
                    groupName : groupName,
                    };
        var token = jwt_encode(data,"UAP)(*");
        if (validation2(data)) {
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
            }).done(function() {
              loadGroupPrevileges(loadDataGroupPrevileges);
              $('#GlobalModal').modal('hide');
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
             $('#ModalbtnSaveForm2').prop('disabled',false).html('Save');

            });
        }
        else
        {
            $('#ModalbtnSaveForm2').prop('disabled',false).html('Save');
        }          
        
    });

    $(document).on('keypress','.GroupPrevileges', function (e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            var ID = $(this).attr('id-key');
            var GroupAuth = $(this).val();
            var url = base_url_js+'vreservation/config/groupuser/update';
            var data = {
                        ID : ID,
                        GroupAuth : GroupAuth,
                        };
            var token = jwt_encode(data,"UAP)(*");          
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
            }).done(function() {
              //loadMenuPrevileges(loadDatamenuPrevileges);
              //loadSubMenu();
              loadSelectGroupUser();
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            });
        }
    });

    $(document).on('click','.btn-delete-groupp', function (e) {
        var ID = $(this).attr('id-key');
        var url = base_url_js+'vreservation/config/groupuser/delete';
        var data = {
                    ID : ID,
                    };
        var token = jwt_encode(data,"UAP)(*");
        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Are you sure ? </b> ' +
            '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
            '</div>');
        $('#NotificationModal').modal('show');

        $("#confirmYes").click(function(){
            $('#NotificationModal .modal-header').addClass('hide');
            $('#NotificationModal .modal-body').html('<center>' +
                '                    <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>' +
                '                    <br/>' +
                '                    Loading Data . . .' +
                '                </center>');
            $('#NotificationModal .modal-footer').addClass('hide');
            $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
            });
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
                //location.reload();
                $('#NotificationModal').modal('hide');
            }).done(function() {
              loadGroupPrevileges(loadDataGroupPrevileges);
              loadSelectGroupUser();
              $('#NotificationModal').modal('hide');
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
              $('#NotificationModal').modal('hide');
            }).always(function() {
                $('#NotificationModal').modal('hide');
            });
        })          
    });
</script>
