<style type="text/css">
	#tableData thead th,#tableData tfoot td {

	    text-align: center;
	    background: #20485A;
	    color: #FFFFFF;

	}
</style>
<div class="col-xs-12" >
	<div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Time Period Budgeting</h4>
            <div class="toolbar no-padding pull-right">
                <span data-smt="" class="btn btn-add">
                    <i class="icon-plus"></i> Add
               </span>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive" id = "loadTable">

            </div>	
        </div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    loadTable();

    $(".btn-add").click(function(){
	    modal_generate('add','Add');
    });
    
}); // exit document Function

function modal_generate(action,title,ID='') {
    var url = base_url_js+"budgeting/time_period/modalform";
    var data = {
        Action : action,
        CDID : ID,
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

        $('#ModalbtnSaveForm').click(function(){
        	if (confirm("Are you sure?") == true) {
        	    loading_button('#ModalbtnSaveForm');
        	    var url = base_url_js+'budgeting/time_period/modalform/save';

        	    var Year = $("#Year").val();
        	    var MonthStart = $("#MonthStart").val();
        	    var MonthEnd = $("#MonthEnd").val();
        	    var action = $(this).attr('action');
        	    var id = $("#ModalbtnSaveForm").attr('kodeuniq');
        	    var data = {
        	    			Year : Year,
        	                MonthStart : MonthStart,
        	                MonthEnd : MonthEnd,
        	                Action : action,
        	                CDID : id
        	                };
        	    var token = jwt_encode(data,"UAP)(*");
        	    $.post(url,{token:token},function (data_json) {
                	var response = jQuery.parseJSON(data_json);
                	if (response == '') {
                		toastr.success('Data berhasil disimpan', 'Success!');
                	}
                	else
                	{
                		toastr.error(response, 'Failed!!');
                	}
                	loadTable();
                	$('#GlobalModal').modal('hide');
                }).done(function() {
                  // loadTable();
                }).fail(function() {
                  toastr.error('The Database connection error, please try again', 'Failed!!');
                }).always(function() {
                 $('#ModalbtnSaveForm').prop('disabled',false).html('Save');

                });

        	  } 
        	  else {
        	    return false;
        	  }
               
        });
    })

}

function loadTable()
{
	$("#loadTable").empty();
	var TableGenerate = '<table class="table table-bordered" id ="tableData">'+
						'<thead>'+
						'<tr>'+
							'<th width = "3%">No</th>'+
							'<th>Year</th>'+
							'<th>Start Period</th>'+
							'<th>End Period</th>'+
							'<th>Action</th>'+
						'</tr></thead>'	
						;
	TableGenerate += '<tbody>';

	var dataForTable = [];
	var url = base_url_js+'budgeting/table_all/cfg_dateperiod/1';
	$.post(url,function (resultJson) {
	    var response = jQuery.parseJSON(resultJson);
	    dataForTable = response;
	    // console.log(dataForTable);
	    for (var i = 0; i < dataForTable.length; i++) {
	    	var btn_edit = '<button type="button" class="btn btn-warning btn-edit" Year = "'+dataForTable[i].Year+'"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>';
	    	var btn_del = ' <button type="button" class="btn btn-danger btn-delete"  Year = "'+dataForTable[i].Year+'"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
	    	TableGenerate += '<tr>'+
	    						'<td width = "3%">'+ (parseInt(i) + 1)+'</td>'+
	    						'<td>'+ dataForTable[i].Year+'</td>'+
	    						'<td>'+ getMonth(dataForTable[i].StartPeriod)+'</td>'+
	    						'<td>'+ getMonth(dataForTable[i].EndPeriod)+'</td>'+
	    						'<td>'+ btn_edit + ' '+' &nbsp' + btn_del+'</td>'+
	    					 '</tr>'	
	    }

	    TableGenerate += '</tbody></table>';
	    $("#loadTable").html(TableGenerate);
	    LoaddataTableStandard("#tableData");

        $(".btn-edit").click(function(){
    	    var ID = $(this).attr('year');
    	     modal_generate('edit','Edit',ID);
        });

        $(".btn-delete").click(function(){	
            var ID = $(this).attr('year');
             $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Are you sure ? </b> ' +
                 '<button type="button" id="confirmYesDelete" class="btn btn-primary" style="margin-right: 5px;" data-smt = "'+ID+'">Yes</button>' +
                 '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
                 '</div>');
             $('#NotificationModal').modal('show');

            $("#confirmYesDelete").click(function(){
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
                 var url = base_url_js+'budgeting/time_period/modalform/save';
                 var aksi = "delete";
                 var ID = $(this).attr('data-smt');
                 var data = {
                     Action : aksi,
                     CDID : ID,
                 };
                 var token = jwt_encode(data,"UAP)(*");
                 $.post(url,{token:token},function (data_json) {
                     setTimeout(function () {
                        // toastr.options.fadeOut = 10000;
                        // toastr.success('Data berhasil disimpan', 'Success!');
                        var response = jQuery.parseJSON(data_json);
                        if (response == '') {
                            toastr.success('Data berhasil disimpan', 'Success!');
                        }
                        else
                        {
                            toastr.error(response, 'Failed!!');
                        }
                        loadTable();
                        $('#NotificationModal').modal('hide');
                     },500);
                 });
            });

        });
	}); 
					
}

function getMonth(datee)
{
	var month = [
	         'January',
	         'February',
	         'March',
	         'April',
	         'May',
	         'June',
	         'July',
	         'August',
	         'September',
	         'October',
	         'November',
	         'December'
	];

	var aa = datee.split('-');
	var ab = aa[1];
	ab = parseInt(ab) - 1;
	return month[ab]+' '+aa[0];
}

</script>