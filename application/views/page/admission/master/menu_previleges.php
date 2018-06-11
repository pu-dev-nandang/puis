<link href="<?php echo base_url('assets/custom/jquery-ui.css'); ?>" rel="stylesheet" type="text/css" />
<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i>Daftar Menu Previleges</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                      <span data-smt="" class="btn btn-xs btn-add-menu">
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
                <h4 class="header"><i class="icon-reorder"></i>Add User Previleges</h4>
            </div>
            <div class="widget-content">
                <div class = "row">	
					<div class="col-xs-3" style="">
						Nama User
						<input class="form-control" id="Nama" placeholder="Input Nama..." "="">
					</div>
                    <div class="col-xs-3">
                        Pilih Menu :
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
                <h4 class="header"><i class="icon-reorder"></i>Edit & Daftar User Previleges</h4>
            </div>
            <div class="widget-content">
                <div class = "row"> 
                    <div class="col-xs-3" style="">
                        Nama User
                        <input class="form-control" id="Nama_search" placeholder="Input Nama..." "="">
                    </div>
                </div>
                <br>
                <div class = "row">
                    <div id='LoadTblUserPrevileges' class="col-md-12"></div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    window.temp;
	$(document).ready(function () {
        loadMenuPrevileges(loadDatamenuPrevileges);
		loadAutoCompleteUser();
        loadSelectMenuUser();
        loadAutoCompleteUser2();
	});

	$(document).on('click','.btn-add-menu', function () {
	   modal_generate('add','Add Menu');
	});

	function modal_generate(action,title) {
	    var url = base_url_js+"admission/master-config/modalform_previleges";
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
        var url = base_url_js+'admission/master-config/menu-previleges/get_menu/save';
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
        var url = base_url_js+'admission/master-config/menu-previleges/get_submenu/save';
        var selectMenu = $('#selectMenu').val();
        var sub_menu1 = $("#sub_menu1").val();
        var sub_menu2 = $("#sub_menu2").val();
        var chkPrevileges = getAllCheckbox('chkPrevileges');
        var data = {
                    selectMenu : selectMenu,
                    sub_menu1 : sub_menu1,
                    sub_menu2 : sub_menu2,
                    chkPrevileges : chkPrevileges,
                    };
        var token = jwt_encode(data,"UAP)(*");
        if (validation2(data)) {
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json); 
                // console.log(obj);
            }).done(function() {
              loadMenuPrevileges(loadDatamenuPrevileges);
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
	

    function loadMenuPrevileges(callback)
    {
        // Some code
        // console.log('test');
        $("#loadtableMenu").empty();
        var table = '<table class="table table-striped table-bordered table-hover table-checkable datatable">'+
        '<thead>'+
            '<tr>'+
                '<th style="width: 106px;">Menu</th>'+
                '<th style="width: 15px;">Sub Menu 1</th>'+
                '<th style="width: 15px;">Sub Menu 2</th>'+
                '<th style="width: 15px;">Read</th>'+
                '<th style="width: 15px;">Write</th>'+
                '<th style="width: 15px;">Update</th>'+
                '<th style="width: 15px;">Delete</th>'+
                '<th style="width: 15px;">Action</th>'+
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
        var url = base_url_js+'admission/master-config/menu-previleges/get_submenu/show'
    // loading_page('#loadtableNow');
        $.post(url,function (data_json) {
            var response = jQuery.parseJSON(data_json);
            // $("#loadingProcess").remove();
            for (var i = 0; i < response.length; i++) {
                var read = (response[i]['read'] == 1) ? 'True' : 'False';
                var write = (response[i]['write'] == 1) ? 'True' : 'False';
                var update = (response[i]['update'] == 1) ? 'True' : 'False';
                var deletee = (response[i]['delete'] == 1) ? 'True' : 'False';
                $(".datatable tbody").append(
                    '<tr>'+
                        '<td><input type = "text" class = "form-control Menu" value ="'+response[i]['Menu']+'" id-key = "'+response[i]['ID_Menu']+'"><div class = "hide">'+response[i]['Menu']+'</div></td>'+
                        '<td><input type = "text" class = "form-control SubMenu1" value ="'+response[i]['SubMenu1']+'" id-key = "'+response[i]['ID']+'"><div class = "hide">'+response[i]['SubMenu1']+'</div></td>'+
                        '<td><input type = "text" class = "form-control SubMenu2" value ="'+response[i]['SubMenu2']+'" id-key = "'+response[i]['ID']+'"><div class = "hide">'+response[i]['SubMenu2']+'<div></td>'+
                        '<td><select class = "read" id-key = "'+response[i]['ID']+'"><option value = "'+response[i]['read']+'">'+read+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td><select class = "write" id-key = "'+response[i]['ID']+'"><option value = "'+response[i]['write']+'">'+write+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td><select class = "update" id-key = "'+response[i]['ID']+'"><option value = "'+response[i]['update']+'">'+update+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td><select class = "delete" id-key = "'+response[i]['ID']+'"><option value = "'+response[i]['delete']+'">'+deletee+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td><span class = "btn btn-xs btn-delete" id-key = "'+response[i]['ID']+'"><i class="fa fa-trash"></i></span>'+'</td>'+
                    '</tr>' 
                    );
            }
        }).done(function() {
            LoaddataTableStandard('.datatable');
        })
    }

    $(document).on('keypress','.Menu', function (e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            var ID_Menu = $(this).attr('id-key');
            var Menu = $(this).val();
            var url = base_url_js+'admission/master-config/menu-previleges/get_submenu/update';
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
            var url = base_url_js+'admission/master-config/menu-previleges/get_submenu/update';
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
              loadMenuPrevileges(loadDatamenuPrevileges);
              loadSubMenu();
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
            var url = base_url_js+'admission/master-config/menu-previleges/get_submenu/update';
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
              loadMenuPrevileges(loadDatamenuPrevileges);
              loadSubMenu();
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            });
        }
    });

    $(document).on('change','.read', function (e) {
        var ID = $(this).attr('id-key');
        var read = $(this).val();
        var url = base_url_js+'admission/master-config/menu-previleges/get_submenu/update';
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
          loadMenuPrevileges(loadDatamenuPrevileges);
          loadSubMenu();
        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {

        });
    });

    $(document).on('change','.write', function (e) {
        var ID = $(this).attr('id-key');
        var write = $(this).val();
        var url = base_url_js+'admission/master-config/menu-previleges/get_submenu/update';
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
          loadMenuPrevileges(loadDatamenuPrevileges);
          loadSubMenu();
        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {

        });
    });

    $(document).on('change','.update', function (e) {
        var ID = $(this).attr('id-key');
        var update = $(this).val();
        var url = base_url_js+'admission/master-config/menu-previleges/get_submenu/update';
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
          loadMenuPrevileges(loadDatamenuPrevileges);
          loadSubMenu();
        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {

        });
    });

    $(document).on('change','.delete', function (e) {
        var ID = $(this).attr('id-key');
        var deletee = $(this).val();
        var url = base_url_js+'admission/master-config/menu-previleges/get_submenu/update';
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
          loadMenuPrevileges(loadDatamenuPrevileges);
          loadSubMenu();
        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {

        });
    });

    $(document).on('click','.btn-delete', function (e) {
        var ID = $(this).attr('id-key');
        var url = base_url_js+'admission/master-config/menu-previleges/get_submenu/delete';
        var data = {
                    ID : ID,
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
    });

    function loadAutoCompleteUser()
    {
        temp = '';
        $("#Nama").autocomplete({
          minLength: 3,
          select: function (event, ui) {
            event.preventDefault();
            var selectedObj = ui.item;
            // console.log(selectedObj);
            // $("#Nama").appendTo(".foo");
            $("#Nama").val(selectedObj.value); 
            temp =  selectedObj.value;
            loadSubMenu();
            // console.log(temp);
          },
          /*select: function (event,  ui)
          {

          },*/
          source:
          function(req, add)
          {
            var url = base_url_js+'admission/master-config/autocompleteuser';
            var Nama = $('#Nama').val();
            var data = {
                        Nama : Nama,
                        };
            var token = jwt_encode(data,"UAP)(*");          
            $.post(url,{token:token},function (data_json) {
                var obj = JSON.parse(data_json);
                add(obj.message) 
            })
          } 
        })

    }

    function loadAutoCompleteUser2()
    {
        $("#Nama_search").autocomplete({
          minLength: 3,
          select: function (event, ui) {
            event.preventDefault();
            var selectedObj = ui.item;
            $("#Nama_search").val(selectedObj.value); 
            loadMenuPrevilegesUser(loadDatamenuPrevilegesUser);
          },
          /*select: function (event,  ui)
          {

          },*/
          source:
          function(req, add)
          {
            var url = base_url_js+'admission/master-config/autocompleteuser';
            var Nama = $('#Nama_search').val();
            var data = {
                        Nama : Nama,
                        };
            var token = jwt_encode(data,"UAP)(*");          
            $.post(url,{token:token},function (data_json) {
                var obj = JSON.parse(data_json);
                add(obj.message) 
            })
          } 
        })
    }

    function loadSelectMenuUser()
    {
        var url = base_url_js+"admission/master-config/menu-previleges/get_menu";
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
           loadSubMenu();
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
        var url = base_url_js+"admission/master-config/menu-previleges/get_submenu_by_menu";
        var data = {
                    Menu : value,
                    NIP : temp
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
        var url = base_url_js+'admission/master-config/menu-previleges/user/save';
        var ID_Menu = $("#selectMenuUser").val();
        var data = {
                    checkbox : getData,
                    ID_Menu : ID_Menu,
                    NIP : temp
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
          case  "NIP" :
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

    function loadMenuPrevilegesUser(callback)
    {
        // Some code
        // console.log('test');
        $("#LoadTblUserPrevileges").empty();
        var table = '<table class="table table-striped table-bordered table-hover table-checkable datatable" id ="MenuPrevilegesUser">'+
        '<thead>'+
            '<tr>'+
                '<th style="width: 106px;">NIP</th>'+
                '<th style="width: 106px;">Nama</th>'+
                '<th style="width: 106px;">Menu</th>'+
                '<th style="width: 15px;">Sub Menu 1</th>'+
                '<th style="width: 15px;">Sub Menu 2</th>'+
                '<th style="width: 15px;">Read</th>'+
                '<th style="width: 15px;">Write</th>'+
                '<th style="width: 15px;">Update</th>'+
                '<th style="width: 15px;">Delete</th>'+
                '<th style="width: 15px;">Action</th>'+
            '</tr>'+
        '</thead>'+
        '<tbody>'+
        '</tbody>'+
        '</table>';
        //$("#loadtableNow").empty();
        $("#LoadTblUserPrevileges").html(table);

        /*if (typeof callback === 'function') { 
            callback(); 
        }*/
        callback();
    }

    function loadDatamenuPrevilegesUser()
    {
        var url = base_url_js+'admission/master-config/menu-previleges/get_previleges_user/show';
        var Nama_search = $("#Nama_search").val();
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
                $("#MenuPrevilegesUser tbody").append(
                    '<tr>'+
                        '<td>'+response[i]['NIP']+'</td>'+
                        '<td>'+response[i]['Name']+'</td>'+
                        '<td>'+response[i]['Menu']+'</td>'+
                        '<td>'+response[i]['SubMenu1']+'</td>'+
                        '<td>'+response[i]['SubMenu2']+'</td>'+
                        '<td><select class = "readUser" id-key = "'+response[i]['ID_previleges']+'" auth-menu = "'+response[i]['readMenu']+'"><option value = "'+response[i]['read']+'">'+read+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td><select class = "writeUser" id-key = "'+response[i]['ID_previleges']+'" auth-menu = "'+response[i]['writeMenu']+'"><option value = "'+response[i]['write']+'">'+write+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td><select class = "updateUser" id-key = "'+response[i]['ID_previleges']+'" auth-menu = "'+response[i]['updateMenu']+'"><option value = "'+response[i]['update']+'">'+update+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td><select class = "deleteUser" id-key = "'+response[i]['ID_previleges']+'" auth-menu = "'+response[i]['deleteMenu']+'"><option value = "'+response[i]['delete']+'">'+deletee+'</option><option value = "1">True</option><option value = "0">False</option></select>'+'</td>'+
                        '<td><span class = "btn btn-xs btn-delete-previleges" id-key = "'+response[i]['ID_previleges']+'"><i class="fa fa-trash"></i></span>'+'</td>'+
                    '</tr>' 
                    );
            }
        }).done(function() {
            LoaddataTableStandard('#MenuPrevilegesUser');
        })
    }

    $(document).on('change','.readUser', function (e) {
        var ID = $(this).attr('id-key');
        var read = $(this).val();
        var auth_menu = $(this).attr('auth-menu');
        if (auth_menu == 0) {
            toastr.error('Menu ini tidak memiliki akses read', 'Failed!!');
            loadMenuPrevilegesUser(loadDatamenuPrevilegesUser);
        }
        else
        {
            var url = base_url_js+'admission/master-config/menu-previleges/previleges_user/update';
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
              loadMenuPrevilegesUser(loadDatamenuPrevilegesUser);
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
            loadMenuPrevilegesUser(loadDatamenuPrevilegesUser);
        }
        else
        {
            var url = base_url_js+'admission/master-config/menu-previleges/previleges_user/update';
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
              loadMenuPrevilegesUser(loadDatamenuPrevilegesUser);
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
            loadMenuPrevilegesUser(loadDatamenuPrevilegesUser);
        }
        else
        {
            var url = base_url_js+'admission/master-config/menu-previleges/previleges_user/update';
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
              loadMenuPrevilegesUser(loadDatamenuPrevilegesUser);
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
            loadMenuPrevilegesUser(loadDatamenuPrevilegesUser);
        }
        else
        {
            var url = base_url_js+'admission/master-config/menu-previleges/previleges_user/update';
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
              loadMenuPrevilegesUser(loadDatamenuPrevilegesUser);
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            });
        }
        
    });

    $(document).on('click','.btn-delete-previleges', function (e) {
        var ID = $(this).attr('id-key');
        var url = base_url_js+'admission/master-config/menu-previleges/previleges_user/delete';
        var data = {
                    ID : ID,
                    };
        var token = jwt_encode(data,"UAP)(*");          
        $.post(url,{token:token},function (data_json) {
            // jsonData = data_json;
            // var obj = JSON.parse(data_json); 
            // console.log(obj);
        }).done(function() {
          loadMenuPrevilegesUser(loadDatamenuPrevilegesUser);
        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {

        });
    });
</script>
