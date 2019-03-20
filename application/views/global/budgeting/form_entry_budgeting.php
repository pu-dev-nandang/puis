<div class="row" style="margin-left: 10px;margin-right: 10px">
	<div class="col-md-3">
		<button class = "btn btn-default" id = "ChooseSubAccount">Choose Sub Account</button>
	</div>
	<div class="col-md-6">
		<div class="thumbnail" style="height: 100px">
			<div class="col-xs-6">
				<div class="form-group">
					<label>Departement</label>
					<select class="select2-select-00 full-width-fix" id="Departement">
						<?php for($i=0; $i < count($arr_department_pu); $i++): ?>
							<?php $selected = ($i == 0) ? 'selected' : '' ?>
							<option value="<?php echo $arr_department_pu[$i]['Code'] ?>" <?php echo $selected  ?> > <?php echo $arr_department_pu[$i]['Name2'] ?></option>
						<?php endfor ?>
					</select>	
				</div>	
			</div>
			<div class="col-xs-6">
				<div class="form-group">
					<label>Year</label>
					<select class="select2-select-00 full-width-fix" id="Year">
						<?php for($i=0; $i < count($arr_Year); $i++): ?>
							<?php $selected = ($arr_Year[$i]['Activated'] == 1) ? 'selected' : '' ?>
							<option value="<?php echo $arr_Year[$i]['Year']?>" <?php echo $selected  ?> > <?php echo $arr_Year[$i]['Year'] ?> - <?php echo ($arr_Year[$i]['Year'] + 1) ?></option>
						<?php endfor ?>
					</select>	
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3" id = "BudgetAllocation">
	</div>
</div>

<div class="row" style="margin-top: 10px;margin-left: 10px;margin-right: 10px">
	<div class="col-md-3">
		<p style="color: red;font-size: 20px">(.000)</p>
	</div>
	<div class="col-md-3 col-md-offset-2">
		<b>Status : </b><br><i class="fa fa-check-circle" style="color: green;"></i> On Budget limit || <i class="fa fa-minus-circle" style="color: red;"></i> Exceeded Budget Limit
	</div>
</div>
<div id = "G_Content">
	
</div>
<script type="text/javascript">
var ClassDt = {
	creator_budget_approval : [],
	creator_budget : [],
	arr_bulan : [],
	PostBudget : [],
	Approval : [],
	SelectedPostBudget : [],
	BudgetAllocation : [],
};
$(document).ready(function() {
	$('#Departement').select2({
	   //allowClear: true
	});

	$('#Year').select2({
	   //allowClear: true
	});

	LoadFirstLoad();
}); // exit document Function


function LoadFirstLoad(){
	// checkData pada table creator_budget_approval dan creator_budget
	var url = base_url_js+"budgeting/getCreatorBudget";
	var Year = $("#Year").val();
	var Departement = $("#Departement").val();
	var data = {
			    Year : Year,
				Departement : Departement,
			};
	var token = jwt_encode(data,'UAP)(*');
	$.post(url,{token:token},function (resultJson) {
		var response = jQuery.parseJSON(resultJson);
		ClassDt.creator_budget_approval = response['creator_budget_approval'];
		ClassDt.creator_budget = response['creator_budget'];
		ClassDt.arr_bulan = response['arr_bulan'];
		ClassDt.PostBudget = response['PostBudget'];
		ClassDt.Approval = response['Approval'];
		ClassDt.BudgetAllocation = response['PostBudget']['getPostDepartement'];

		var arr1 = ClassDt.creator_budget_approval;
		if(arr1.length > 0)
		{
			makeDomExisting(response);
		}
		else
		{
			makeDomAwal();
			
		}
	});		
}

function makeDomAwal()
{
	// Show Budget Allocation from finance
	makeHtmlBudgetAllocation();

	var htmlheader = makeHtmlHeader();
	// write header
	$("#G_Content").append(htmlheader);

}

function makeHtmlBudgetAllocation()
{
	loading_page('#BudgetAllocation');
	var dt = ClassDt.BudgetAllocation;
	setTimeout(function () {
		var html = '<div class = "row">'+
						'<div class = "col-md-12">'+
							'<table class="table table-bordered tableData" id ="tableData3">'+
							'<caption><b>Budget Allocation</b></caption>'+
								'<thead>'+
									'<tr>'+
										'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Head Account</th>'+
										'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Allocation</th>'+
										'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Remaining</th>'+
									'</tr>'+
								'</thead><tbody>';
		for (var i = 0; i < dt.length; i++) {
			html += '<tr>'+
						'<td>'+dt[i]['PostName']+'-'+dt[i]['NameHeadAccount']+'</td>'+
						'<td>'+formatRupiah(dt[i]['Budget'])+'</td>'+
						'<td>'+formatRupiah(dt[i]['Remaining'])+'</td>'+
					'</tr>';	

		}

		html += '</tbody></table></div></div>';
		$("#BudgetAllocation").html(html);
	},1000);
					
}

function makeHtmlHeader()
{
	var html = '<div class = "row" style = "margin-left : 10px;margin-right : 10px">'+
					'<div class = "col-md-1">'+
						'<label>Post Budget</label>'+
					'</div>'+
					'<div class = "col-md-1">'+
						'<label>Unit Cost</label>'+
					'</div>'+
					'<div class = "col-md-1">'+
						'<label>Freq</label>'+
					'</div>';


	// get Month
	var Month = ClassDt.arr_bulan;
	 html += '<div class = "col-md-7">'+
	 			'<div class = "row">';	
	for (var i = 0; i < Month.length; i++) {
		html += '<div class = "col-md-1">'+
					'<label>'+Month[i].MonthName+'</label>'+
				'</div>';	
	}
	html += '</div></div>';
	// add sisa dan subtotal
	html += '<div class = "col-md-2">'+
				'<div class = "row">'+
					'<div class = "col-md-3">'+ // sisa
					'</div>'+
					'<div class = "col-md-9">'+
						'<label>Subtotal</label>'+	
					'</div>'+
				'</div>'+	
			'</div>';
	html += '</div>';		
	return html;		
}


$(document).off('click', '#ChooseSubAccount').on('click', '#ChooseSubAccount',function(e) {
	var dt = ClassDt.PostBudget;
		var html ='<div class = "row">'+
					'<div class = "col-md-12">'+
						'<table id="example" class="table table-bordered display select" cellspacing="0" width="100%">'+
							'<thead>'+
								'<tr>'+
									'<th width = "15%">Choose &nbsp <input type="checkbox" name="select_all" value="1" id="example-select-all"></th>'+
									'<th>Sub Account</th>'+
								'</tr>'+
							'</thead>';
		html += '<tbody>';
	var data = dt['data'];
	for (var i = 0; i < data.length; i++) {
		html += '<tr>'+
					'<td><input type="checkbox" class="uniform" CodeHeadAccount="'+data[i].CodeHeadAccount+'" CodePost="'+data[i].CodePost+'" CodePostRealisasi="'+data[i].CodePostRealisasi+'" NameHeadAccount="'+data[i].NameHeadAccount+'" PostName="'+data[i].PostName+'" RealisasiPostName = "'+data[i].RealisasiPostName+'">'+
					'</td>'+
					'<td>'+data[i].PostName+'-'+data[i].NameHeadAccount+'-'+data[i].RealisasiPostName+'</td>'+
				'</tr>';	
	}

	html += '</tbody></table></div></div>';
	 var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
	     '<button type="button" id="ModalbtnSaveForm" class="btn btn-success">Save</button>';

	$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Choose Sub Account'+'</h4>');
	$('#GlobalModalLarge .modal-body').html(html);
	$('#GlobalModalLarge .modal-footer').html(footer);
	$('#GlobalModalLarge').modal({
	    'show' : true,
	    'backdrop' : 'static'
	});
							

})

$(document).off('click', '#ModalbtnSaveForm').on('click', '#ModalbtnSaveForm',function(e) {
	var checkbox = [];
	$(".uniform:checked").each(function(){
		var CodeHeadAccount = $(this).attr('codeheadaccount');
		var CodePost = $(this).attr('codepost');
		var CodePostRealisasi = $(this).attr('codepostrealisasi');
		var NameHeadAccount = $(this).attr('nameheadaccount');
		var PostName = $(this).attr('postname');
		var RealisasiPostName = $(this).attr('realisasipostname');
		var temp = {
			CodeHeadAccount : CodeHeadAccount,
			CodePost : CodePost,
			CodePostRealisasi : CodePostRealisasi,
			NameHeadAccount : NameHeadAccount,
			PostName : PostName,
			RealisasiPostName : RealisasiPostName,
		};

		checkbox.push(temp);
	})
	ClassDt.SelectedPostBudget = checkbox;
	$('#GlobalModalLarge').modal('hide');
	// write html content
	makeContent();
	// write make footer(Note,Grand Total,button Approve,reject,excel,approver)
	makeFooter();
	// validation button
	showButton();

	$('.UnitCost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
	$('.InputBulan').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
})

$(document).off('click', '#example-select-all').on('click', '#example-select-all',function(e) {
    $('input.uniform').not(this).prop('checked', this.checked);
});

function makeContent()
{
	var dt = ClassDt.SelectedPostBudget;
	var html = '';
	var OPFreq = '';
	for (var i = 0; i <= 1000; i++) {
		var selected = (i == 0) ? 'selected' : '';
		OPFreq += '<option value = "'+i+'" '+selected+'>'+i+'</option>';
	}
	for (var i = 0; i < dt.length; i++) {
		html += '<div class = "row ContentDataPostBudget" style = "margin-left : 10px;margin-right : 10px;margin-top : 10px">';
		html += '<div class = "col-md-1">'+
					'<select class="select2-select-00 full-width-fix PostBudget">'+
						'<option value ="'+dt[i]['CodePostRealisasi']+'" selected CodePost = "'+dt[i]['CodePost']+'" CodeHeadAccount="'+dt[i]['CodeHeadAccount']+'">'+dt[i]['PostName']+'-'+dt[i]['NameHeadAccount']+'-'+dt[i]['RealisasiPostName']+'</option>'+
					 '</select>'+
				'</div>'+
				'<div class = "col-md-1">'+
					'<input type = "text" class = "form-control UnitCost" placeholder="Input Unit Cost..." value = "0">'+
				'</div>'+
				'<div class = "col-md-1">'+
					'<select class="select2-select-00 full-width-fix Freq">'+
						OPFreq+
					'</select>'+
				'</div>';

		var Month = ClassDt.arr_bulan;
		html += '<div class = "col-md-7">'+
					'<div class = "row">';
		for (var j = 0; j < Month.length; j++) {
			html += '<div class = "col-md-1">'+
						'<input type = "text" class = "form-control InputBulan" placeholder="Input Unit Cost..." value = "0" keyValue = "'+Month[j].keyValueFirst+'">'+
					'</div>';	
		}

		html += '</div></div>';				
		// add sisa dan subtotal
		html += '<div class = "col-md-2">'+
					'<div class = "row">'+
						'<div class = "col-md-3 sisa">'+ // sisa
						'</div>'+
						'<div class = "col-md-9">'+
							'<p class = "Subtotal">0</p>'+
						'</div>'+
					'</div>'+	
				'</div>';
		html += '</div>';			
	}
	$(".ContentDataPostBudget").remove(); // hapus dahulu
	$(".rowSubtotal").remove(); // hapus dahulu
	$("#G_Content").append(html);

	// write html total perbulan
	html = '';
	html += '<div class = "row rowSubtotal" style = "margin-left : 10px;margin-right : 10px;margin-top : 10px">'+
				'<div class = "col-md-7 col-md-offset-3">'+
				'<div class = "row">';
	for (var j = 0; j < Month.length; j++) {
		html += '<div class = "col-md-1">'+
					'<div class="form-group subTotalPermonth" keyvalue="'+Month[j].keyValueFirst+'" style="font-size: 12px;"></div>'+
				'</div>';	
	}	

	html += '</div></div></div>';
	$("#G_Content").append(html);			
}

function makeFooter(){
	$(".rowFooter").remove(); // hapus dahulu
	var html = '<div class = "row rowFooter" style = "margin-left : 10px;margin-right : 10px;margin-top : 20px">'+
				'<div class = "col-md-12">'+
					'<div class = "row">'+
						'<div class = "col-md-4">'+
							'<div class = "form-group">'+
								'<label>Note</label>'+
								'<input type="text" class="form-control" id="Note" placeholder="Input Note...">'+
							'</div>'+
						'</div>'+	
					'</div>'+
					'<div class = "row">'+
						'<div class="col-md-2 col-md-offset-10" id="GrandTotal">'+
						'</div>'+
					'</div>'+
					'<div class = "row rowApproval">'+
					'</div>'+
					'<div class = "row">'+
						'<div class = "col-md-4 col-md-offset-8" id = "content_button">'+
						'</div>'+
					'</div>'+
				'</div>'+
				'</div>';		
	$("#G_Content").append(html);				

}

function showButton()
{
	var dt = ClassDt.Approval;
	var arr1 = ClassDt.creator_budget_approval;
	if(arr1.length == 0)
	{
		// show button Submit
		var html = '<div class = "row"><div class = "col-md-2 col-md-offset-10">'+
						'<button class = "btn btn-success" id = "SaveBudget">Submit</button>'+
					'</div></div>';
		$("#content_button").html(html);			
	}	
	else
	{
		//existing
	}
	
}

$(document).off('keyup', '.UnitCost').on('keyup', '.UnitCost',function(e) {
   var row = $(this).closest('.ContentDataPostBudget');
   ProsesOneRow(row);

});

function ProsesOneRow(row)
{
	var UnitCost = row.find('.col-md-1:eq(1)').find('.UnitCost').val();
	UnitCost = findAndReplace(UnitCost,".","");
	var Freq = row.find('.col-md-1:eq(2)').find('.Freq').val();
	var Total = parseInt(UnitCost * Freq);
	// pengurangan remaining
		var dt = ClassDt.BudgetAllocation;
		for (var i = 0; i < dt.length; i++) {
			var CodeHeadAccount = dt[i].CodeHeadAccount;
			var rr = dt[i].Budget;
			
			// get each function
			$('.PostBudget').each(function(){
				var cc = $(this).find('option').attr('codeheadaccount');
				if (CodeHeadAccount == cc) {
					// get unit cost
					var r = $(this).closest('.ContentDataPostBudget');
					var U = r.find('.col-md-1:eq(1)').find('.UnitCost').val();
					U = findAndReplace(UnitCost,".","");
					var F = r.find('.col-md-1:eq(2)').find('.Freq').val();
					var T = parseInt(U * F);
					rr = rr - T;
				}
			})

			dt[i].Remaining = rr;
		}
		ClassDt.BudgetAllocation = dt;
		makeHtmlBudgetAllocation();
	row.find('.row').find('.col-md-9').find('.Subtotal').html(formatDigitNumber(Total));

	
}

// existing
function makeDomExisting(response)
{

}
</script>

