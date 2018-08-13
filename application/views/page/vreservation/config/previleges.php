<link href="<?php echo base_url('assets/custom/jquery-ui.css'); ?>" rel="stylesheet" type="text/css" />
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
				</div>
                <div class = "row">
                    <div id='LoadTblGroupUser'></div> 
                </div>
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
	});

	function loadSelectGroupUser()
    {
        var url = base_url_js+"vreservation/getGroupPrevileges";
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

    function loadGroupUser(callback)
    {
        // Some code
        // console.log('test');
        $("#LoadTblGroupUser").empty();
        var table = '<table class="table table-striped table-bordered table-hover table-checkable datatable" id ="IDTblGroupUser">'+
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
        '</table>';
        //$("#loadtableNow").empty();
        $("#LoadTblGroupUser").html(table);

        /*if (typeof callback === 'function') { 
            callback(); 
        }*/
        callback();
    }

    function loadDataGroupUser()
    {
        
    }

</script>
