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
                    <i class="icon-plus"></i> Add Period
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
}); // exit document Function

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
	var dataForTable = <?php echo $loadData ?>;
	for (var i = 0; i < dataForTable.length; i++) {
		var btn_edit = '<button type="button" class="btn btn-warning btn-edit" Year = "'+dataForTable[i].Year+'"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>';
		var btn_del = ' <button type="button" class="btn btn-danger"  Year = "'+dataForTable[i].Year+'"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
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