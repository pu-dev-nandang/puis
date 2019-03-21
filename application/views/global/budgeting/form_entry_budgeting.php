<div class="row" style="margin-left: 10px;margin-right: 10px">
	<div class="col-md-3">
		<button class = "btn btn-default" id = "ChooseSubAccount">Choose Sub Account</button>
		</br></br>
		<button class = "btn btn-warning" id = "Log" id_creator_budget_approval = "">Log</button>
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

$(document).off('change', '#Departement').on('change', '#Departement',function(e) {
    LoadFirstLoad();
});

$(document).off('change', '#Year').on('change', '#Year',function(e) {
    LoadFirstLoad();
});


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
			$("#Log").attr('id_creator_budget_approval',arr1[0].ID);
			makeDomExisting();
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
	$("#G_Content").html(htmlheader);

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
	var dt2 = ClassDt.creator_budget;
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
		// find if existing then checked
		var d1 = data[i].CodePostRealisasi
		var bool = false;
		for (var j = 0; j < dt2.length; j++) {
			var d2 = data[j].CodePostRealisasi
			if (d1==d2) {
				bool = true;
				break;
			}
		}

		var checked = (bool) ? 'checked' : '';

		html += '<tr>'+
					'<td><input type="checkbox" class="uniform" CodeHeadAccount="'+data[i].CodeHeadAccount+'" CodePost="'+data[i].CodePost+'" CodePostRealisasi="'+data[i].CodePostRealisasi+'" NameHeadAccount="'+data[i].NameHeadAccount+'" PostName="'+data[i].PostName+'" RealisasiPostName = "'+data[i].RealisasiPostName+'" '+checked+' >'+
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
	var arr1 = ClassDt.creator_budget_approval;
	if(arr1.length > 0)
	{
		// for edit
		makeRowAdd_del(checkbox);
		$('#GlobalModalLarge').modal('hide');		
	}
	else
	{
		ClassDt.SelectedPostBudget = checkbox;
		$('#GlobalModalLarge').modal('hide');
		// write html content
		makeContent();
		// write make footer(Note,Grand Total,button Approve,reject,excel,approver)
		makeFooter();
		// validation button
		showButton();
		
	}

	$('.UnitCost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
	$('.InputBulan').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
	
})

function makeRowAdd_del(dt)
{
	var Month = ClassDt.arr_bulan;
	for (var i = 0; i < dt.length; i++) {
		var aa = $('.PostBudget').find('option[value="'+dt[i].CodePostRealisasi+'"]');
		if (!aa.length) {
			var html = '';
			var OPFreq = '';
			for (var ii = 0; ii <= 1000; ii++) {
				var selected = (ii == 0) ? 'selected' : '';
				OPFreq += '<option value = "'+ii+'" '+selected+'>'+ii+'</option>';
			}

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
			$(".rowSubtotal").before(html);
		}
	}

	// del post budget yang tidak ada pada dt
	$(".PostBudget").each(function(){
		var d1 = $(this).val();
		var bool = true;
		for (var i = 0; i < dt.length; i++) {
			var d2 = dt[i]['CodePostRealisasi'];
			if (d1 == d2) {
				bool = false;
				break;
			}
		}

		if (bool) {
			var row = $(this).closest('.ContentDataPostBudget');
			row.remove();
		}

	})

	var row = $('.ContentDataPostBudget:eq(0)');
	if (row.length) {
		ProsesOneRow(row);
	}
	
}

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
	// $('select[tabindex!="-1"]').select2({ // get value tidak jalan
	//     //allowClear: true
	// });			
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

	// for existing / edit
	var arr1 = ClassDt.creator_budget_approval;
	if (arr1.length > 0) {
		// get all employee to get name
		var url = base_url_js+'rest/__getEmployees/aktif';
		var data = {
				    auth : 's3Cr3T-G4N'
				};
		var token = jwt_encode(data,'UAP)(*');
		$.post(url,{token:token},function (resultJson) {
			var JsonStatus = jQuery.parseJSON(arr1[0].JsonStatus);
			var html = '<div class = "col-md-4 col-md-offset-8">'+
		    				'<a href = "javascript:void(0)"  class="btn btn-default btn-default-success" type="button" id = "add_approver" id_creator_budget_approval = "'+arr1[0].ID+'">'+
                        			'<i class="fa fa-plus-circle" aria-hidden="true"></i>'+
                    		'</a>'+
							'<table class = "table table-striped table-bordered table-hover table-checkable tableData" style = "margin-top : 5px">'+
								'<thead><tr>';

			// get requested by dari Approval	dengan ID_m_userrole = 1				
			   var u = ClassDt.Approval;
			   var Requester = '';
			   for (var i = 0; i < u.length; i++) {
			   	if (u[i].ID_set_roleuser == 1) {
			   		Requester = u[i].NamaUser;
			   		break;
			   	} 
			   }

			html += '<th>'+'Requested by'+'</th>';   
			for (var i = 0; i < JsonStatus.length; i++) {
				html += '<th>'+JsonStatus[i].NameTypeDesc+'</th>';
			}

			html +=	'</th></thead>'+'<tbody><tr style = "height : 51px">';
			html += '<td>'+'<i class="fa fa-check" style="color: green;"></i>'+'</td>';
			for (var i = 0; i < JsonStatus.length; i++) {
				var v = '-';
				if (JsonStatus[i].NameTypeDesc != 'Acknowledge by') {
					if (JsonStatus[i].Status == '2') {
						v = '<i class="fa fa-times" aria-hidden="true" style="color: red;"></i>';
					}
					else if(JsonStatus[i].Status == '1')
					{
						v = '<i class="fa fa-check" style="color: green;"></i>';
					}
					else
					{
						v = '-';
					}
				}
				html += '<td>'+v+'</td>';		
			}
			html += '</tr><tr>';
			html += '<td>'+Requester+'</td>';
			for (var i = 0; i < JsonStatus.length; i++) {
				// find Name in resultJson
				var Name = '';
				for (var j = 0; j < resultJson.length; j++) {
					if (JsonStatus[i].NIP == resultJson[j].NIP) {
						Name = resultJson[j].Name;
						break;
					}
				}
				html += '<td>'+Name+'</td>';		
			}

			html +=	'</tr></tbody>'+'</table></div>';
			var aa = $('.rowApproval').find('.col-md-12');
			if (aa.length) {
				aa.remove();
			}

			$('.rowApproval').html(html);				
		})				
	}


}

function showButton()
{
	var dt = ClassDt.Approval;
	var arr1 = ClassDt.creator_budget_approval;
	if(arr1.length == 0)
	{
		// show button Submit
		var html = '<div class = "row"><div class = "col-md-6 col-md-offset-6" align = "right">'+
						'<button class = "btn btn-success" id = "SaveBudget" action = "add" id_creator_budget_approval = "">Save To Draft</button>'+
						'&nbsp'+
						'<button class = "btn btn-primary" id = "SaveSubmit" action = "add" id_creator_budget_approval = "">Submit</button>'+
					'</div></div>';
		$("#content_button").html(html);

		// only admin to create per department
		var NIP = '<?php echo $this->session->userdata('NIP') ?>';
		var bool = false;
		for (var i = 0; i < dt.length; i++) {
			if (NIP == dt[i].NIP && dt[i].ID_set_roleuser == 1) {
				bool = true;
				break;
			}
			
		}

		if (!bool) {
			$('#SaveBudget,#SaveSubmit').prop('disabled',true);
		}
	}	
	else
	{
		//existing
		var Status = arr1[0]['Status'];
		if (Status == '0' || Status == '3') { // only authorize Admin
			// show button Submit
			var html = '<div class = "row"><div class = "col-md-6 col-md-offset-6" align = "right">'+
							'<button class = "btn btn-success" id = "SaveBudget" action = "edit" id_creator_budget_approval = "'+arr1[0].ID+'">Save To Draft</button>'+
							'&nbsp'+
							'<button class = "btn btn-primary" id = "SaveSubmit" action = "edit" id_creator_budget_approval = "'+arr1[0].ID+'">Submit</button>'+
						'</div></div>';
			$("#content_button").html(html);

			// only admin to create per department
			var NIP = '<?php echo $this->session->userdata('NIP') ?>';
			var bool = false;
			for (var i = 0; i < dt.length; i++) {
				if (NIP == dt[i].NIP && dt[i].ID_set_roleuser == 1) {
					bool = true;
					break;
				}
				
			}

			if (!bool) {
				$('#SaveBudget,#SaveSubmit').prop('disabled',true);
			}
		}

		if (Status == 1) { // only auth approval berdasarkan tingkatan Approval
			var NIP = '<?php echo $this->session->userdata('NIP') ?>';
			var JsonStatus = jQuery.parseJSON(arr1[0]['JsonStatus']);
			var bool = false;
			var HierarkiApproval = 0; // for check hierarki approval;
			var NumberOfApproval = 0; // for check hierarki approval;
			for (var i = 0; i < JsonStatus.length; i++) {
				NumberOfApproval++;
				if (JsonStatus[i]['Status'] == 0) {
					// check status before
					if (i > 0) {
						var ii = i - 1;
						if (JsonStatus[ii]['Status'] == 1) {
							HierarkiApproval++;
						}

						if (JsonStatus[ii]['NameTypeDesc'] != 'Approval by') {
							HierarkiApproval++;
						}
					}
					else
					{
						HierarkiApproval++;
					}
					
					if (NIP == JsonStatus[i]['NIP'] && JsonStatus[i]['NameTypeDesc'] == 'Approval by') {
						bool = true;
						break;
					}
				}
				else
				{
					HierarkiApproval++;
				}
			}

			if (bool && HierarkiApproval == NumberOfApproval) { // rule approval
				var html = '<div class = "row"><div class = "col-md-6 col-md-offset-6" align = "right">'+
								'<button class = "btn btn-primary" id = "ApproveBudget" action = "approve" id_creator_budget_approval = "'+arr1[0].ID+'" approval_number = "'+NumberOfApproval+'">Approve</button>'+
								'&nbsp'+
								'<button class = "btn btn-inverse" id = "RejectBudget" action = "reject" id_creator_budget_approval = "'+arr1[0].ID+'" approval_number = "'+NumberOfApproval+'">Reject</button>'+
							'</div></div>';
				$("#content_button").html(html);
			}

		}

	}
	
}

$(document).off('keyup', '.UnitCost').on('keyup', '.UnitCost',function(e) {
   var row = $(this).closest('.ContentDataPostBudget');
   ProsesOneRow(row);

});

$(document).off('change', '.Freq').on('change', '.Freq',function(e) {
   var row = $(this).closest('.ContentDataPostBudget');
   ProsesOneRow(row);
});

$(document).off('keyup', '.InputBulan').on('keyup', '.InputBulan',function(e) {
   var row = $(this).closest('.ContentDataPostBudget');
   ProsesOneRow(row);
});

function ProsesOneRow(row)
{
	var UnitCost = row.find('.col-md-1:eq(1)').find('.UnitCost').val();
	UnitCost = findAndReplace(UnitCost,".","");
	var Freq = row.find('.col-md-1:eq(2)').find('.Freq').val();
	var Total = parseInt(UnitCost * Freq);
	// Write subtotal per baris
	row.find('.row').find('.col-md-9').find('.Subtotal').html(formatDigitNumber(Total));
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
					U = findAndReplace(U,".","");
					U = parseInt(U) * 1000; // for ribuan
					var F = r.find('.col-md-1:eq(2)').find('.Freq').val();
					var T = parseInt(U * F);
					rr = rr - T;
					// show Status Budget
					if (rr < 0) {
						if (r.find('.col-md-9').find('.Subtotal').find('i').length) {
							r.find('.col-md-9').find('.Subtotal').find('i').remove();
						}
						r.find('.col-md-9').find('.Subtotal').append(' <i class="fa fa-minus-circle" style="color: red;"></i>');
					}
					else
					{
						if (r.find('.col-md-9').find('.Subtotal').find('i').length) {
							r.find('.col-md-9').find('.Subtotal').find('i').remove();
						}
						r.find('.col-md-9').find('.Subtotal').append(' <i class="fa fa-check-circle" style="color: green;"></i>');
					}
				}
			})

			dt[i].Remaining = rr;
		}
		ClassDt.BudgetAllocation = dt; // save data in variable
		makeHtmlBudgetAllocation(); // show data after updates
	// check freq dengan total inputan bulan
		// get input bulan dalam satu baris
			var count = 0;
			row.find('.InputBulan').each(function(){
				var v = $(this).val();
				count = parseInt(count) + parseInt(v);
			})


			if (count > Freq) {
				row.find('.InputBulan').each(function(){
					$(this).val(0);
					$(this).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
					$(this).maskMoney('mask', '9894');
				})
				row.find('.sisa').html('<i class="fa fa-check-circle" style="color: green;"></i> '+0);
				toastr.info("Your Input Exceeded than Freq, The Input Was Reset");
			}
			else
			{
				var v = parseInt(Freq) - count;
				if (v == 0) {
					row.find('.sisa').html('<i class="fa fa-check-circle" style="color: green;"></i> '+0);
				}
				else
				{
					row.find('.sisa').html('<i class="fa fa-minus-circle" style="color: red;"></i> '+v);
				}
				
			}
	// proses subtotal per month
		ProsesSubtotalPerMonth();
	// proses Grand Total
		ProsesGrandTotal();
}

function ProsesSubtotalPerMonth()
{
	var Month = ClassDt.arr_bulan;
	for (var i = 0; i < Month.length; i++) {
		var keyvalue = Month[i].keyValueFirst;
		var total = 0;
		$('.InputBulan[keyvalue="'+keyvalue+'"]').each(function(){
			var row = $(this).closest('.ContentDataPostBudget');
			var v = $(this).val();
			var UnitCost = row.find('.col-md-1:eq(1)').find('.UnitCost').val();
			UnitCost = findAndReplace(UnitCost,".","");
			total = parseInt(total) + ( parseInt(UnitCost * v) );

		})
		total = formatDigitNumber(total);
		// write html
		$('.subTotalPermonth[keyvalue="'+keyvalue+'"]').html(total);
	}
}

function ProsesGrandTotal()
{
	var total = 0;
	$('.PostBudget').each(function(){
		var r = $(this).closest('.ContentDataPostBudget');
		var U = r.find('.col-md-1:eq(1)').find('.UnitCost').val();
		U = findAndReplace(U,".","");
		U = parseInt(U) * 1000; // for ribuan
		var F = r.find('.col-md-1:eq(2)').find('.Freq').val();
		var T = parseInt(U * F);
		total = parseInt(total) + parseInt(T);
	})
	$("#GrandTotal").html('<p style = "color : green">'+'Grand Total : <br>'+formatRupiah(total)+'</p>');
}

$(document).off('click', '#SaveBudget').on('click', '#SaveBudget',function(e) {
	var ID = $(this).attr('id_creator_budget_approval');
	var action = $(this).attr('action');
  if (confirm("Are you sure?") == true) {
  	loadingStart();
  	// check total input month harus sama dengan freq
  		var arrBool = [];
  		var arr_pass = [];
  		$('.PostBudget').each(function(){
  			var row = $(this).closest('.ContentDataPostBudget');
  			var CodePostRealisasi = row.find('.col-md-1:eq(0)').find('.PostBudget').val();
  			var UnitCost = row.find('.col-md-1:eq(1)').find('.UnitCost').val();
  			UnitCost = findAndReplace(UnitCost,".","");
  			UnitCost = parseInt(UnitCost) * 1000; // for ribuan
  			var Freq = row.find('.col-md-1:eq(2)').find('.Freq').val();
  			var Subtotal = parseInt(UnitCost * Freq)
  			var count = 0;
  			var arr = [];
  			row.find('.InputBulan').each(function(){
  				var v = $(this).val();
  				v = findAndReplace(v,".","");
  				var keyvalue = $(this).attr('keyvalue');
  				count = parseInt(count) + parseInt(v);
  				arr.push({month : keyvalue,value : v,});
  			})

  			if (Freq != count) {
  				arrBool.push(0);
  			}
  			else
  			{
  				arrBool.push(1);
  			}

  			var creator_budget = {
  				CodePostRealisasi : CodePostRealisasi,
  				UnitCost : UnitCost,
  				Freq : Freq,
  				DetailMonth : arr,
  				SubTotal : Subtotal,
  			};

  			arr_pass.push(creator_budget);

  		})

  		// arr creator_budget_approval
  			var creator_budget_approval = {
  				Departement : $('#Departement').val(),
  				Year : $('#Year').val(),
  				Note : $("#Note").val(),
  				Status : 0,
  			};

  		var bool =true;
  		for (var i = 0; i < arrBool.length; i++) {
  			if (arrBool[i] == 0) {
  				bool = false;
  				break;
  			}
  		}

  		if(!bool)
  		{
	  		toastr.info("Your Month Input is not same with Freq, Please check");
	  		loadingEnd(1000);
  		}
  		else
  		{
  			var url = base_url_js+"budgeting/saveCreatorbudget";
  			var data = {
  				creator_budget :arr_pass,
  				creator_budget_approval : creator_budget_approval,
  				ID : ID,
  				action : action,
  				};
  			// console.log(data);loadingEnd(1000);return;	
  			var token = jwt_encode(data,'UAP)(*');
  			$.post(url,{token:token},function (resultJson) {
  				var response = jQuery.parseJSON(resultJson);
  				if(response.Status == 1)
  				{
  					// $("#SaveBudget").attr('action','edit');
  					// $("#SaveBudget").attr('id_creator_budget_approval',response.msg);

  					// $("#SaveSubmit").attr('action','edit');
  					// $("#SaveSubmit").attr('id_creator_budget_approval',response.msg);
  					LoadFirstLoad();
  					// $("#Log").attr('id_creator_budget_approval',response.msg);
  				}
  				else
  				{
  					toastr.error(response.msg,'!Failed');
  				}
  				loadingEnd(2000);

  			}).fail(function() {
  			  $('.pageAnchor[page="EntryBudget"]').trigger('click');	
  			  toastr.error('The Database connection error, please try again', 'Failed!!');
  			  loadingEnd(3000);
  			}).always(function() {
  			    // $('#NotificationModal').modal('hide');
  			}); 
  		}	

  }
});	

// existing
function makeDomExisting()
{
	var htmlheader = makeHtmlHeader();
	// write header
	$("#G_Content").html(htmlheader);

	// fill content
	makeContent_existing();
	// write make footer(Note,Grand Total,button Approve,reject,excel,approver)
	makeFooter()

	// validation button
	showButton();

	$('.UnitCost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
	$('.UnitCost').maskMoney('mask', '9894');
	$('.InputBulan').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
	$('.InputBulan').maskMoney('mask', '9894');

	$('.PostBudget').each(function(){
		var row = $(this).closest('.ContentDataPostBudget');
		ProsesOneRow(row);
	})	
}


function makeContent_existing()
{
	var dt = ClassDt.creator_budget;
	var Month = ClassDt.arr_bulan;
	var html = '';
	for (var i = 0; i < dt.length; i++) {
		var OPFreq = '';
		var Cmb_freq = dt[i]['Freq'];
		for (var ii = 0; ii <= 1000; ii++) {
			var selected = (Cmb_freq == ii) ? 'selected' : '';
			OPFreq += '<option value = "'+ii+'" '+selected+'>'+ii+'</option>';
		}

		var UnitCost = dt[i]['UnitCost'] / 1000;// ribuan

		html += '<div class = "row ContentDataPostBudget" style = "margin-left : 10px;margin-right : 10px;margin-top : 10px">';
		html += '<div class = "col-md-1">'+
					'<select class="select2-select-00 full-width-fix PostBudget">'+
						'<option value ="'+dt[i]['CodePostRealisasi']+'" selected CodePost = "'+dt[i]['CodePost']+'" CodeHeadAccount="'+dt[i]['CodeHeadAccount']+'">'+dt[i]['PostName']+'-'+dt[i]['NameHeadAccount']+'-'+dt[i]['RealisasiPostName']+'</option>'+
					 '</select>'+
				'</div>'+
				'<div class = "col-md-1">'+
					'<input type = "text" class = "form-control UnitCost" placeholder="Input Unit Cost..." value = "'+UnitCost+'">'+
				'</div>'+
				'<div class = "col-md-1">'+
					'<select class="select2-select-00 full-width-fix Freq">'+
						OPFreq+
					'</select>'+
				'</div>';

		html += '<div class = "col-md-7">'+
					'<div class = "row">';
		var DetailMonth = dt[i]['DetailMonth'];
		DetailMonth = jQuery.parseJSON(DetailMonth);		
		for (var j = 0; j < DetailMonth.length; j++) {
			html += '<div class = "col-md-1">'+
						'<input type = "text" class = "form-control InputBulan" placeholder="Input Unit Cost..." value = "'+DetailMonth[j].value+'" keyValue = "'+DetailMonth[j].month+'">'+
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
</script>

