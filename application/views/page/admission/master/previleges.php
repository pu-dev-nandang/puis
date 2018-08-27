<!-- <link href="<?php echo base_url('assets/custom/jquery-ui.css'); ?>" rel="stylesheet" type="text/css" /> -->
<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i>Add User Previleges</h4>
            </div>
            <div class="widget-content">
                <div class = "row">	
					<div class="col-xs-3" style="">
						Name
						<input class="form-control" id="Nama" placeholder="Input Name..." "="">
					</div>
                    <div class="col-xs-3">
                        Choice Group User Previleges :
                        <select class="full-width-fix" id="selectGroupuUser">
                            <option></option>
                        </select>
                    </div>
                    <div class="col-xs-4">
                        <br>
                        <button class="btn btn-primary btn-add" id="btn-save"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                        Save</button>
                    </div>    
				</div>
                <br>
                <!-- <div class = "row"> -->
                    <div id='LoadTblGroupUser'></div> 
                <!-- </div> -->
                <div class="row" id='LoadBtnSbmt'></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.temp;
	$(document).ready(function () {
		loadAutoCompleteUser();
        loadSelectGroupUser();
        loadGroupUser(loadDataGroupUser);
	});

  $(document).on('change','.grouPAuth', function () {
      var NIP = $(this).attr('NIP');
      var valuee = $(this).val();
      var url =base_url_js+'admission/edit_auth_user';
      var data = {NIP : NIP,valuee : valuee};
      var token = jwt_encode(data,'UAP)(*');
      $.post(url,{token:token},function (data_json) {
        
      }).done(function () {
        
      });
  });

	function loadSelectGroupUser()
    {
        var url = base_url_js+"admission/master-config/menu-previleges/getGroupPrevileges";
        $('#selectGroupuUser').empty()
        $.post(url,function (data_json) {
            var obj = JSON.parse(data_json);
            //$('#selectGroupuUser').append('<option value="'+'0'+'" '+''+'>'+'--Choice Group User --'+'</option>');
              for(var i=0;i<obj.length;i++){
                  var selected = (i==0) ? 'selected' : '';
                  //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
                  $('#selectGroupuUser').append('<option value="'+obj[i].ID+'" '+selected+'>'+obj[i].GroupAuth+'</option>');
                  // $('#selectGroupuUser2').append('<option value="'+obj[i].ID+'" '+selected+'>'+obj[i].GroupAuth+'</option>');
              }
              $('#selectGroupuUser').select2({
                 //allowClear: true
              });

        }).done(function () {
           
          // console.log('loadmenu success');
        });
    }


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
            //loadSubMenu();
            // console.log(temp);
          },
          /*select: function (event,  ui)
          {

          },*/
          source:
          function(req, add)
          {
            var url = base_url_js+'autocompleteAllUser';
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

    $(document).on('click','#btn-save', function () {
        loading_button('#btn-save');
        var NIP = temp;
        var GroupUser = $("#selectGroupuUser").val();
        result = Validation_required(NIP,'The Name');
        if (result['status'] == 0) {
            toastr.error(result['messages'], 'Failed!!');
            $('#btn-save').prop('disabled',false).html('<i class="fa fa-floppy-o" aria-hidden="true"></i> Save');
        }
        else
        {
            var url =base_url_js+'admission/add_auth_user';
            var data = {NIP : NIP,GroupUser : GroupUser};
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (data_json) {
              //var response = jQuery.parseJSON(data_json);
              loadGroupUser(loadDataGroupUser);
              $('#btn-save').prop('disabled',false).html('<i class="fa fa-floppy-o" aria-hidden="true"></i> Save');
            }).done(function () {
              $('#btn-save').prop('disabled',false).html('<i class="fa fa-floppy-o" aria-hidden="true"></i> Save');
            });;
        }
    });

    $(document).on('click','.btn-delete-group', function () {
      //loading_button('.btn-edit');
      NIP = $(this).attr('NIP');
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
          var url =base_url_js+'admission/delete_authUser';
          var data = {NIP : NIP};
          var token = jwt_encode(data,'UAP)(*');
          $.post(url,{token:token},function (data_json) {
            loadGroupUser(loadDataGroupUser);
            $('#NotificationModal').modal('hide');
          });
      })
    });  

    function loadGroupUser(callback)
    {
        // Some code
        // console.log('test');
        $("#LoadTblGroupUser").empty();
        var table = '<div class = "col-md-12"><div class="table-responsive"> <table class="table table-striped table-bordered table-hover table-checkable datatable" id ="IDTblGroupUser">'+
        '<thead>'+
            '<tr>'+
                '<th style="width: 106px;">NIP</th>'+
                '<th style="width: 106px;">Name</th>'+
                '<th style="width: 15px;" class = "btn-edit">Group Previleges</th>'+
                '<th style="width: 15px;" class = "btn-delete">Action</th>'+
            '</tr>'+
        '</thead>'+
        '<tbody>'+
        '</tbody>'+
        '</table></div></div>';
        //$("#loadtableNow").empty();
        $("#LoadTblGroupUser").html(table);

        /*if (typeof callback === 'function') { 
            callback(); 
        }*/
        callback();
    }

    function loadDataGroupUser()
    {
        $.fn.dataTable.ext.errMode = 'throw';
        //alert('hsdjad');
        $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
                  {
                      return {
                          "iStart": oSettings._iDisplayStart,
                          "iEnd": oSettings.fnDisplayEnd(),
                          "iLength": oSettings._iDisplayLength,
                          "iTotal": oSettings.fnRecordsTotal(),
                          "iFilteredTotal": oSettings.fnRecordsDisplay(),
                          "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                          "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                      };
                  };

        var dataTable = $('#IDTblGroupUser').DataTable( {
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"admission/config/getAuthDataTables", // json datasource
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                /*var no = 'row'+(dataIndex + 1);
                  $(row).attr('id', no);*/
            },
        } );          
    }

</script>
