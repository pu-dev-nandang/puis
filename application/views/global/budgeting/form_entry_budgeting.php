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
	Add_Department : [],
	m_type_user : []
};
$(document).ready(function() {
	// $('#Departement').select2({
	//    //allowClear: true
	// });

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
		ClassDt.m_type_user = response['m_type_user'];
		ClassDt.Add_Department = response['Add_department_IFCustom_approval'];

		// Add Department auth
			Add_Department_auth();

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

		loadingEnd(500);
	});		
}

function Add_Department_auth()
{
	var dt = ClassDt.Add_Department;
	var act = [0];
	for (var i = 0; i < dt.length; i++) {
		var aa = $("#Departement").find('option[value="'+dt[i].Code+'"]');
		if (aa.length) {
			// console.log('exist');
			act.push(0);// for action
		} else {
			$("#Departement").append(
					'<option value = "'+dt[i].Code+'">'+dt[i].Name2+'</option>'
				);
			act.push(1);// for action
		}

		
	}

	$('#Departement').select2({
	   //allowClear: true
	});

	// if just one department added
	var b = 0;
	for (var i = 0; i < act.length; i++) {
		if (act[i] == 1) {
			b = 1;
			break;
		}
	}
	if (!$(".ContentDataPostBudget").length &&  $('#Departement').find('option').length == 1 && b == 1) {
		LoadFirstLoad();
	}

	// for auth	
		showButton();
	
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
	// loading_page('#BudgetAllocation');
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
					'<div class = "col-md-1 Custom-PostBudget">'+
						'<label>Post Budget</label>'+
					'</div>'+
					'<div class = "col-md-1 Custom-UnitCost">'+
						'<label>Unit Cost</label>'+
					'</div>'+
					'<div class = "col-md-1 Custom-Freq">'+
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
		var d1 = data[i].CodePostRealisasi;
		var bool = false;
		for (var j = 0; j < dt2.length; j++) {
			var d2 = dt2[j].CodePostRealisasi;
			if (d1==d2) {
				bool = true;
				break;
			}
		}

		var checked = (bool) ? 'checked' : '';
		var No = i+1;
		html += '<tr>'+
					'<td>'+No+'&nbsp<input type="checkbox" class="uniform" CodeHeadAccount="'+data[i].CodeHeadAccount+'" CodePost="'+data[i].CodePost+'" CodePostRealisasi="'+data[i].CodePostRealisasi+'" NameHeadAccount="'+data[i].NameHeadAccount+'" PostName="'+data[i].PostName+'" RealisasiPostName = "'+data[i].RealisasiPostName+'" '+checked+' >'+
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
	// get last NO 
	var No = $(".numberNO:last").text();
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
			No = parseInt(No) +i + 1;
			html += '<div class = "col-md-1 Custom-PostBudget">'+
						'<span class = "numberNO">'+No+'</span>&nbsp'+
						'<select class="select2-select-00 full-width-fix PostBudget Custom-select2">'+
							'<option value ="'+dt[i]['CodePostRealisasi']+'" selected CodePost = "'+dt[i]['CodePost']+'" CodeHeadAccount="'+dt[i]['CodeHeadAccount']+'">'+dt[i]['RealisasiPostName']+'</option>'+
						 '</select>'+
					'</div>'+
					'<div class = "col-md-1 Custom-UnitCost">'+
						'<input type = "text" class = "form-control UnitCost" placeholder="Input Unit Cost..." value = "0">'+
					'</div>'+
					'<div class = "col-md-1 Custom-Freq">'+
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
			// $(".rowSubtotal").before(html);
			$("#PageSubAccount").append(html);
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
	var html = '<div style = "overflow : auto;max-height : 430px;" id = "PageSubAccount">';
	var OPFreq = '';
	for (var i = 0; i <= 1000; i++) {
		var selected = (i == 0) ? 'selected' : '';
		OPFreq += '<option value = "'+i+'" '+selected+'>'+i+'</option>';
	}
	for (var i = 0; i < dt.length; i++) {
		// check genap & ganjil untuk berikan style warna
		var c = ( (i % 2) == 0 ) ? 'background-color : #90c4e8;' : '';
		html += '<div class = "row ContentDataPostBudget" style = "margin-left : 10px;margin-right : 10px;margin-top : 10px; '+c+'">';
		var No = i + 1;
		html += '<div class = "col-md-1 Custom-PostBudget">'+
					'<span class = "numberNO">'+No+'</span>&nbsp'+
					'<select class="select2-select-00 full-width-fix PostBudget Custom-select2" style = "margin-top : 5px;">'+
						'<option value ="'+dt[i]['CodePostRealisasi']+'" selected CodePost = "'+dt[i]['CodePost']+'" CodeHeadAccount="'+dt[i]['CodeHeadAccount']+'">'+dt[i]['RealisasiPostName']+'</option>'+
					 '</select>'+
				'</div>'+
				'<div class = "col-md-1 Custom-UnitCost">'+
					'<input type = "text" class = "form-control UnitCost" placeholder="Input Unit Cost..." value = "0" style = "'+c+' color : #333">'+
				'</div>'+
				'<div class = "col-md-1 Custom-Freq">'+
					'<select class="select2-select-00 full-width-fix Freq" style = "'+c+' margin-top : 5px;color : #333">'+
						OPFreq+
					'</select>'+
				'</div>';

		var Month = ClassDt.arr_bulan;
		html += '<div class = "col-md-7">'+
					'<div class = "row">';
		for (var j = 0; j < Month.length; j++) {
			html += '<div class = "col-md-1">'+
						'<input type = "text" class = "form-control InputBulan" placeholder="Input Unit Cost..." value = "0" keyValue = "'+Month[j].keyValueFirst+'" style = "'+c+' color : #333">'+
					'</div>';	
		}

		html += '</div></div>';				
		// add sisa dan subtotal
		html += '<div class = "col-md-2">'+
					'<div class = "row">'+
						'<div class = "col-md-3 sisa">'+ // sisa
						'</div>'+
						'<div class = "col-md-9">'+
							'<p class = "Subtotal" style = "margin-top : 5px;">0</p>'+
						'</div>'+
					'</div>'+	
				'</div>';
		html += '</div>';			
	}

	html += '</div>';

	$(".ContentDataPostBudget").remove(); // hapus dahulu
	$(".rowSubtotal").remove(); // hapus dahulu
	var c_PageSubAccount = $("#G_Content").find('#PageSubAccount');
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
						'<div class = "col-md-6 col-md-offset-6" id = "content_button">'+
						'</div>'+
					'</div>'+
				'</div>'+
				'</div>';		
	$("#G_Content").append(html);

	// for existing / edit
	makeApproval();

	// show note if existing
		var arr1 = ClassDt.creator_budget_approval;
		if (arr1.length > 0) {
			$('#Note').val(arr1[0].Note);
		} 

}

function makeApproval()
{
	var arr1 = ClassDt.creator_budget_approval;
	var dt = ClassDt.Approval;
	if (arr1.length > 0) {
		// get all employee to get name
		var url = base_url_js+'rest/__getEmployees/aktif';
		var data = {
				    auth : 's3Cr3T-G4N'
				};
		var token = jwt_encode(data,'UAP)(*');
		$.post(url,{token:token},function (resultJson) {
			var JsonStatus = jQuery.parseJSON(arr1[0].JsonStatus);
			// only admin to custom approval
				var html_add_approver = '';
				var NIP = '<?php echo $this->session->userdata('NIP') ?>';
				var bool = false;
				for (var i = 0; i < dt.length; i++) {
					if (NIP == dt[i].NIP && dt[i].ID_set_roleuser == 1) {
						bool = true;
						break;
					}
					
				}

				if (bool) {
					html_add_approver = '<a href = "javascript:void(0)"  class="btn btn-default btn-default-success" type="button" id = "add_approver" id_creator_budget_approval = "'+arr1[0].ID+'">'+
	                        			'<i class="fa fa-plus-circle" aria-hidden="true"></i>'+
	                    		'</a>';
				}

			var html = '<div class = "col-md-4 col-md-offset-8">'+
		    				html_add_approver+
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
			$('button:not(#Log):not(#btnBackToHome)').prop('disabled',true);
			$('input:not(.select2-input)').prop('disabled',true);
			$('select:not(#Departement):not(#Year):not(.PostBudget)').prop('disabled',true);
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
				if (NIP == dt[i].NIP && dt[i].ID == 1) {
					bool = true;
					break;
				}
				
			}

			if (!bool) {
				$('#SaveBudget,#SaveSubmit').prop('disabled',true);
				$('button:not(#Log):not(#btnBackToHome)').prop('disabled',true);
				$('input:not(.select2-input)').prop('disabled',true);
				$('select:not(#Departement):not(#Year):not(.PostBudget)').prop('disabled',true);
			}
			
		}

		if (Status == 1) { // only auth approval berdasarkan tingkatan Approval
			// lock input
				$('button:not(#Log):not(#btnBackToHome)').prop('disabled',true);
				$('input').prop('disabled',true);
				$('select:not(#Departement):not(#Year):not(.PostBudget)').prop('disabled',true);


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

			// disabled ChooseSubAccount while approval process
			$('#ChooseSubAccount').prop('disabled',true);
		}

		if (Status ==2) {
			$('button:not(#Log):not(#btnBackToHome)').prop('disabled',true);
			$('input:not(.select2-input)').prop('disabled',true);
			$('select:not(#Departement):not(#Year):not(.PostBudget)').prop('disabled',true);

			// show button export excel
				var filee = (arr1[0].FileUpload != '' && arr1[0].FileUpload != null && arr1[0].FileUpload != undefined) ? '<a href = "'+base_url_js+'fileGetAny/budgeting-'+arr1[0].FileUpload+'" target="_blank" class = "Fileexist">File '+'</a>&nbsp' : '';
				$('#content_button').attr('align','right');
				$('#content_button').html(filee+'<label class="btn btn-primary" style="color: #ffff;">Upload Budget File <input id="file-upload" type="file" style="display: none;" id_creator_budget_approval = "'+arr1[0].ID+'" accept="image/*,application/pdf"></label>&nbsp<button type="button" class="btn btn-default" id="ExportExcel" id_creator_budget_approval = "'+arr1[0].ID+'"> <i class="fa fa-file-excel-o"></i> Excel</button>');
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
   var keyvalue = $(this).attr('keyvalue');
   ProsesOneRow(row,keyvalue);
});

function ProsesOneRow(row,keyvalue = null)
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
				if (keyvalue == null) {
					row.find('.InputBulan').each(function(){
						$(this).val(0);
						$(this).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
						$(this).maskMoney('mask', '9894');
					})
				}
				else
				{
					row.find('.InputBulan[keyvalue="'+keyvalue+'"]').val(0);
					row.find('.InputBulan[keyvalue="'+keyvalue+'"]').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
					row.find('.InputBulan[keyvalue="'+keyvalue+'"]').maskMoney('mask', '9894');
				}
				
				row.find('.sisa').html('<div style="margin-top : 5px;"><i class="fa fa-check-circle" style="color: green;"></i> '+0+'</div>');
				toastr.info("Your Input Exceeded than Freq, The Input Was Reset");
			}
			else
			{
				var v = parseInt(Freq) - count;
				if (v == 0) {
					row.find('.sisa').html('<div style="margin-top : 5px;"><i class="fa fa-check-circle" style="color: green;"></i> '+0+'</div>');
				}
				else
				{
					row.find('.sisa').html('<div style="margin-top : 5px;"><i class="fa fa-minus-circle" style="color: red;"></i> '+v+'</div>');
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

$(document).off('click', '#SaveSubmit').on('click', '#SaveSubmit',function(e) {
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
  				Status : 1,
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
	var html = '<div style = "overflow : auto;max-height : 430px;" id = "PageSubAccount">';
	for (var i = 0; i < dt.length; i++) {
		var OPFreq = '';
		var Cmb_freq = dt[i]['Freq'];
		for (var ii = 0; ii <= 1000; ii++) {
			var selected = (Cmb_freq == ii) ? 'selected' : '';
			OPFreq += '<option value = "'+ii+'" '+selected+'>'+ii+'</option>';
		}

		var UnitCost = dt[i]['UnitCost'] / 1000;// ribuan
		var c = ( (i % 2) == 0 ) ? 'background-color : #90c4e8;' : '';
		html += '<div class = "row ContentDataPostBudget" style = "margin-left : 10px;margin-right : 10px;margin-top : 10px; '+c+'">';
		var No = i + 1;
		html += '<div class = "col-md-1 Custom-PostBudget">'+
					'<span class = "numberNO">'+No+'</span>&nbsp'+
					'<select class="select2-select-00 full-width-fix PostBudget Custom-select2" style = "margin-top : 5px;">'+
						'<option value ="'+dt[i]['CodePostRealisasi']+'" selected CodePost = "'+dt[i]['CodePost']+'" CodeHeadAccount="'+dt[i]['CodeHeadAccount']+'">'+dt[i]['RealisasiPostName']+'</option>'+
					 '</select>'+
				'</div>'+
				'<div class = "col-md-1 Custom-UnitCost">'+
					'<input type = "text" class = "form-control UnitCost" placeholder="Input Unit Cost..." value = "'+UnitCost+'" style = "'+c+' color : #333">'+
				'</div>'+
				'<div class = "col-md-1 Custom-Freq">'+
					'<select class="select2-select-00 full-width-fix Freq" style = "'+c+' margin-top : 5px;color : #333">'+
						OPFreq+
					'</select>'+
				'</div>';

		html += '<div class = "col-md-7">'+
					'<div class = "row">';
		var DetailMonth = dt[i]['DetailMonth'];
		DetailMonth = jQuery.parseJSON(DetailMonth);		
		for (var j = 0; j < DetailMonth.length; j++) {
			html += '<div class = "col-md-1">'+
						'<input type = "text" class = "form-control InputBulan" placeholder="Input Unit Cost..." value = "'+DetailMonth[j].value+'" keyValue = "'+DetailMonth[j].month+'" style = "'+c+' color : #333">'+
					'</div>';	
		}

		html += '</div></div>';	
		// add sisa dan subtotal
		html += '<div class = "col-md-2">'+
					'<div class = "row">'+
						'<div class = "col-md-3 sisa">'+ // sisa
						'</div>'+
						'<div class = "col-md-9">'+
							'<p class = "Subtotal" style = "margin-top : 5px;">0</p>'+
						'</div>'+
					'</div>'+	
				'</div>';
		html += '</div>';						
	}

	html += '</div>';

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

$(document).off('click', '#add_approver').on('click', '#add_approver',function(e) {
   var id_creator_budget_approval = $(this).attr('id_creator_budget_approval');
   var dt = ClassDt.creator_budget_approval;
   // only process while status draft and reject
   if (dt[0].Status == '0' || dt[0].Status == '3') {
	   var url = base_url_js+'rest/__getEmployees/aktif';
	   var data = {
	   		    auth : 's3Cr3T-G4N'
	   		};
	   var token = jwt_encode(data,'UAP)(*');
	   $.post(url,{token:token},function (resultJson) {
			var html = '<div class = "row"><div class="col-md-12">';
				html += '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
		         '<thead>'+
		             '<tr>'+
		                 '<th style="width: 2%;">Approval</th>'+
		                 '<th style="width: 55px;">Name</th>'+
		                 '<th style="width: 55px;">Status</th>'+
		                 '<th style="width: 55px;">Type User</th>'+
		                 '<th style="width: 55px;">Visible</th>'+
		                 '<th style="width: 55px;">Action</th>';
		        html += '</tr>' ;
		        html += '</thead>' ;
		        html += '<tbody>' ;

		    var JsonStatus = jQuery.parseJSON(dt[0].JsonStatus);   
		    var ke = 0; 
		    for (var i = 0; i < JsonStatus.length; i++) {
		    	ke = i + 1;
		    	// search Name
		    		var Name = '';
			    	for (var j = 0; j < resultJson.length; j++) {
			    		if (JsonStatus[i].NIP == resultJson[j].NIP) {
			    			Name = resultJson[j].Name;
			    			break;
			    		}
			    	}
		    	switch(JsonStatus[i]['Status']) {
		    	  case 0:
		    	  case '0':
		    	   var stjson = 'Not Approve';
		    	    break;
		    	  case 1:
		    	  case '1':
		    	    var stjson = 'Approve<br>'+JsonStatus[i]['ApproveAt'];
		    	    break;
		    	  case 2:
		    	  case '2':
		    	    var stjson =  'Reject';
		    	    break;  
		    	  default:
		    	    var stjson = '-';
		    	}
		    	var action = '';
		    	if (JsonStatus[i]['Status'] != 1) {
		    		action = '<button class="btn btn-default btn-default-success btn-edit-approver" data-action="edit" indexjson="'+i+'" id_creator_budget_approval = "'+id_creator_budget_approval+'"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
		    		action += '<button class="btn btn-default btn-default-danger btn-edit-approver" data-action="delete" indexjson="'+i+'" id_creator_budget_approval = "'+id_creator_budget_approval+'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
		    	}
		    	html += '<tr>'+
		    	      '<td>'+ ke + '</td>'+
		    	      '<td>'+ JsonStatus[i]['NIP'] +' || '+Name+ '</td>'+
		    	      '<td>'+ stjson + '</td>'+
		    	      '<td>'+ JsonStatus[i]['NameTypeDesc'] + '</td>'+
		    	      '<td>'+ JsonStatus[i]['Visible'] + '</td>'+
		    	      '<td>'+ action + '</td>'
		    	    '<tr>';	
		    }

		    // add sisa
		    ke = ke + 1;
		    for (var i = 0; i < 10 - JsonStatus.length; i++) {
		    	var action = '<button class="btn btn-default btn-default-primary btn-classroom btn-edit-approver" data-action="add" indexjson="'+(ke-1)+'" id_creator_budget_approval = "'+id_creator_budget_approval+'"><i class="fa fa-plus-circle fa-right" aria-hidden="true"></i></button>';
		    	html += '<tr>'+
		    	      '<td>'+ ke + '</td>'+
		    	      '<td>'+ '-'+ '</td>'+
		    	      '<td>'+ '-' + '</td>'+
		    	      '<td>'+ '-' + '</td>'+
		    	      '<td>'+ '-' + '</td>'+
		    	      '<td>'+ action + '</td>'+
		    	    '<tr>';
		    	ke++;	    	
		    }

		    html += '</tbody>' ;
		    html += '</table></div></div>' ;

		    var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
		        '';
		    $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Custom Approval'+'</h4>');
		    $('#GlobalModalLarge .modal-body').html(html);
		    $('#GlobalModalLarge .modal-footer').html(footer);
		    $('#GlobalModalLarge').modal({
		        'show' : true,
		        'backdrop' : 'static'
		    });
	   })	
   } else {
   		var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
   		    '';
   		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Custom Approval'+'</h4>');
   		$('#GlobalModalLarge .modal-body').html('<p>Your are not available to do this action.</p>'+'<p style = "color : green">Only Draft status and Reject Status could be do the action.</p>');
   		$('#GlobalModalLarge .modal-footer').html(footer);
   		$('#GlobalModalLarge').modal({
   		    'show' : true,
   		    'backdrop' : 'static'
   		});
   }
   
       
});

$(document).off('click', '.btn-edit-approver').on('click', '.btn-edit-approver',function(e) {
	var m_type_user = ClassDt.m_type_user;
	var id_creator_budget_approval = $(this).attr('id_creator_budget_approval');
	var action = $(this).attr('data-action');
	var evtd = $(this).closest('td');
	var evtr = $(this).closest('tr');
	var indexjson = $(this).attr('indexjson');
	switch(action) {
	  case 'add':
	  case 'edit':
	    var url = base_url_js + 'api/__crudEmployees';
	    var data = {
	    	action : 'read',
	    }
	    var token = jwt_encode(data,"UAP)(*");
	    $.post(url,{ token:token },function (data_json) {
	    	var OP = '';
	    		for (var i = 0; i < data_json.length; i++) {
	    			OP += '<option value="'+data_json[i].NIP+'" '+''+'>'+data_json[i].NIP+' | '+data_json[i].Name+'</option>';
	    		}
	    	var OP2 = '';
	    		for (var i = 1; i < m_type_user.length; i++) {
	    			OP2 += '<option value="'+m_type_user[i].ID+'" '+''+'>'+m_type_user[i].Name+'</option>';
	    		}	
	    	evtr.find('td:eq(1)').attr('style','width : 30%');	
	    	evtr.find('td:eq(1)').html('<select class=" form-control listemployees">'+
	    							'   <option value = "0" selected>-- No Selected --</option>'+OP+
	    						'</select>');
	    	evtr.find('td:eq(3)').attr('style','width : 20%');	
	    	evtr.find('td:eq(3)').html('<select class=" form-control listTypeUser">'+OP2+
	    						'</select>');
	    	evtr.find('td:eq(4)').attr('style','width : 10%');	
	    	evtr.find('td:eq(4)').html('<select class=" form-control listVisible">'+
	    							'<option value = "Yes" selected >Yes</option>'+
	    							'<option value = "No" selected >No</option>'+
	    						'</select>');

	    	evtd.html('<button class = "btn btn-primary saveapprover" id_creator_budget_approval = "'+id_creator_budget_approval+'" indexjson = "'+indexjson+'" action = "'+action+'">Save</button>'+
	    					'');
	    	$('.listemployees[tabindex!="-1"]').select2({
	    	    //allowClear: true
	    	});

	    	$('.listTypeUser[tabindex!="-1"]').select2({
	    	    //allowClear: true
	    	});

	    	$('.listVisible[tabindex!="-1"]').select2({
	    	    //allowClear: true
	    	});

	    });
	    break;
	  case 'delete':
	  	 if (confirm('Are you sure ?')) {
	  	 	loading_button('.btn-edit-approver[indexjson="'+indexjson+'"][action="'+action+'"]');
	  	 	var url = base_url_js + 'budgeting/update_approval_budgeting';
	 			var data = {
	 				id_creator_budget_approval : id_creator_budget_approval,
	 				action : action,
	 				indexjson : indexjson,
	 			}
	  	 	var token = jwt_encode(data,"UAP)(*");
	  	 	$.post(url,{ token:token },function (data_json) {
	  	 		var response = jQuery.parseJSON(data_json);
	  	 		if (response['msg'] == '') { // action success
	  	 			var dt = response['data'];
	  	 			var cc = ClassDt.creator_budget_approval;
	  	 			cc[0].JsonStatus = dt;
	  	 			ClassDt.creator_budget_approval = cc;
	  	 			makeApproval();

	  	 			evtr.find('td:eq(1)').html('-');
	  	 			evtr.find('td:eq(2)').html('-');
	  	 			evtr.find('td:eq(3)').html('-');
	  	 			evtr.find('td:eq(4)').html('-');
	  	 			var action = '<button class="btn btn-default btn-default-primary btn-classroom btn-edit-approver" data-action="add" indexjson="'+indexjson+'" id_creator_budget_approval = "'+id_creator_budget_approval+'"><i class="fa fa-plus-circle fa-right" aria-hidden="true"></i></button>';
	  	 			evtr.find('td:eq(5)').html(action);
	  	 		}
	  	 		else
	  	 		{
	  	 			toastr.error(response['msg'],'!!!Failed');
	  	 		}
	  	 	});
	  	 }
	     
	    break;
	  default:
	    // code block
	}
		
})

$(document).off('click', '.saveapprover').on('click', '.saveapprover',function(e) {
	var evtd = $(this).closest('td');
	var evtr = $(this).closest('tr');
	var NIP = evtr.find('td:eq(1)').find('.listemployees').val();
	var NameTypeDesc = evtr.find('td:eq(3)').find('.listTypeUser option:selected').text();
	var Visible = evtr.find('td:eq(4)').find('.listVisible').val();
	var id_creator_budget_approval = $(this).attr('id_creator_budget_approval');
	var action = $(this).attr('action');
	var indexjson = $(this).attr('indexjson');
	if (NIP != '' && NIP != undefined && NIP != null && NIP != 0) {
		loading_button('.saveapprover[indexjson="'+indexjson+'"]');
		var url = base_url_js + 'budgeting/update_approval_budgeting';
		var data = {
			NIP : NIP,
			id_creator_budget_approval : id_creator_budget_approval,
			NameTypeDesc : NameTypeDesc,
			Visible : Visible,
			action : action,
			indexjson : indexjson,
		}
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{ token:token },function (data_json) {
			var response = jQuery.parseJSON(data_json);
			if (response['msg'] == '') { // action success
				var dt = response['data'];
				var cc = ClassDt.creator_budget_approval;
				cc[0].JsonStatus = dt;
				ClassDt.creator_budget_approval = cc;
				makeApproval();
				var Nm = evtr.find('td:eq(1)').find('.listemployees option:selected').text();
				var st = 'Not Approve';
				var tu = NameTypeDesc;
				var vt = evtr.find('td:eq(4)').find('.listVisible option:selected').text();
				evtr.find('td:eq(1)').html(NIP + ' || '+Nm);
				evtr.find('td:eq(2)').html(st);
				evtr.find('td:eq(3)').html(tu);
				evtr.find('td:eq(4)').html(vt);

				action = '<button class="btn btn-default btn-default-success btn-edit-approver" data-action="edit" indexjson="'+indexjson+'" id_creator_budget_approval = "'+id_creator_budget_approval+'"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
				action += '<button class="btn btn-default btn-default-danger btn-edit-approver" data-action="delete" indexjson="'+indexjson+'" id_creator_budget_approval = "'+id_creator_budget_approval+'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
				evtr.find('td:eq(5)').html(action);
			}
			else
			{
				toastr.error(response['msg'],'!!!Failed');
			}
			$('.saveapprover[indexjson="'+indexjson+'"]').prop('disabled',false).html('Save');
		});
	} else {
		toastr.error('Please choose employees','!!!Failed');
	}
	
})	
$(document).off('click', '#Log').on('click', '#Log',function(e) {
	var id_creator_budget_approval = $(this).attr('id_creator_budget_approval');
	if (id_creator_budget_approval != '' && id_creator_budget_approval != undefined && id_creator_budget_approval != null) {
    	var url = base_url_js+'rest/__log_budgeting';
		var data = {
		    id_creator_budget_approval : id_creator_budget_approval,
		    auth : 's3Cr3T-G4N',
		};
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{ token:token },function (data_json) {
			var html = '<div class = "row"><div class="col-md-12">';
				html += '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                  '<thead>'+
                      '<tr>'+
                          '<th style="width: 5px;">No</th>'+
                          '<th style="width: 55px;">Desc</th>'+
                          '<th style="width: 55px;">Date</th>'+
                          '<th style="width: 55px;">By</th>';
	        html += '</tr>' ;
	        html += '</thead>' ;
	        html += '<tbody>' ;

	        for (var i = 0; i < data_json.length; i++) {
	        	var No = parseInt(i) + 1;
	        	html += '<tr>'+
	        	      '<td>'+ No + '</td>'+
	        	      '<td>'+ data_json[i]['Desc'] + '</td>'+
	        	      '<td>'+ data_json[i]['Date'] + '</td>'+
	        	      '<td>'+ data_json[i]['Name'] + '</td>'+
	        	    '<tr>';	
	        }

	        html += '</tbody>' ;
	        html += '</table></div></div>' ;	

			var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
			    '';
			$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Circulation Sheet'+'</h4>');
			$('#GlobalModalLarge .modal-body').html(html);
			$('#GlobalModalLarge .modal-footer').html(footer);
			$('#GlobalModalLarge').modal({
			    'show' : true,
			    'backdrop' : 'static'
			});
		});
	} else {
		toastr.info('The Budget has not create');
	}
    	
})

$(document).off('click', '#ApproveBudget').on('click', '#ApproveBudget',function(e) {
	var id_creator_budget_approval = $(this).attr('id_creator_budget_approval');
	var approval_number = $(this).attr('approval_number');
	if (confirm('Are you sure ?')) {
		loadingStart();
		// var url = base_url_js + 'rest/__approve_pr';
		var url = base_url_js + 'rest/__approve_budget';
		var data = {
			id_creator_budget_approval : id_creator_budget_approval,
			NIP : "<?php echo $this->session->userdata('NIP') ?>",
			action : 'approve',
			approval_number : approval_number,
			auth : 's3Cr3T-G4N',
		}

		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{ token:token },function (resultJson) {
			if (resultJson == '') {
				LoadFirstLoad();
			}
			else
			{
				
			}
			loadingEnd(2000);
		}).fail(function() {
		  // toastr.info('No Result Data');
		  toastr.error('The Database connection error, please try again', 'Failed!!');
		  loadingEnd(2000);
		}).always(function() {
		   loadingEnd(2000);
		});
	}

})

$(document).off('click', '#RejectBudget').on('click', '#RejectBudget',function(e) {
	var id_creator_budget_approval = $(this).attr('id_creator_budget_approval');
	var approval_number = $(this).attr('approval_number');
	if (confirm('Are you sure ?')) {
		// show modal insert reason
		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Input Reason'+'</h4>');
		$('#GlobalModalLarge .modal-body').html('<div class = "row"><div class = "col-md-12" style="text-align: center;"><b>Please Input Reason ! </b> <br>' +
		    '<textarea class = "form-group" id ="NoteDel" rows="4" cols="100"></textarea><br>'+
		    '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
		    '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
		    '</div></div>');
		$('#GlobalModalLarge .modal-footer').html('');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});

		$(document).off('click', '#confirmYes').on('click', '#confirmYes',function(e) {	
			var NoteDel = $("#NoteDel").val();
			if (NoteDel != '' && NoteDel != null && NoteDel != undefined) {
				$('#GlobalModalLarge').modal('hide');
				loadingStart();
				var url = base_url_js + 'rest/__approve_budget';
				var data = {
					id_creator_budget_approval : id_creator_budget_approval,
					NIP : "<?php echo $this->session->userdata('NIP') ?>",
					action : 'reject',
					auth : 's3Cr3T-G4N',
					NoteDel : NoteDel,
					approval_number : approval_number,
				}

				var token = jwt_encode(data,"UAP)(*");
				$.post(url,{ token:token },function (resultJson) {
					if (resultJson == '') {
						LoadFirstLoad();
					}
					else
					{
						
					}
					loadingEnd(2000);
				}).fail(function() {
				  toastr.error('The Database connection error, please try again', 'Failed!!');
				  loadingEnd(2000);
				}).always(function() {
				    loadingEnd(2000);
				});
			} else {
				toastr.info('Plase input the reason');
			}
			
		})	
	}
	

})

$(document).off('click', '#ExportExcel').on('click', '#ExportExcel',function(e) {
	var id_creator_budget_approval = $(this).attr('id_creator_budget_approval');

	var url = base_url_js+'budgeting/export_excel_budget_creator';
	data = {
	  id_creator_budget_approval : id_creator_budget_approval,
	}
	var token = jwt_encode(data,"UAP)(*");
	FormSubmitAuto(url, 'POST', [
	    { name: 'token', value: token },
	]);
})

$(document).off('change', '#file-upload').on('change', '#file-upload',function(e) {
	var id_creator_budget_approval = $(this).attr('id_creator_budget_approval');
	var ID_element = $(this).attr('id');
	var attachName = 'FileBudgeting__'+id_creator_budget_approval;
	if (file_validation(ID_element)) {
	  UploadFile_approve(ID_element,id_creator_budget_approval,attachName);
	}
})

function UploadFile_approve(ID_element,id_creator_budget_approval,attachName)
{
	var form_data = new FormData();
	//var fileData = document.getElementById(ID_element).files[0];
	var url = base_url_js + "budgeting/Upload_File_Creatorbudget";
	var files = $('#'+ID_element)[0].files;
	    var nm = files[0].name;
		var extension = nm.split('.').pop().toLowerCase();
	var DataArr = {
	                id_creator_budget_approval : id_creator_budget_approval,
	                attachName : attachName,
	                extension : extension,
	              };
	var token = jwt_encode(DataArr,"UAP)(*");
	form_data.append('token',token);

	form_data.append("fileData", files[0]);
	$.ajax({
	  type:"POST",
	  url:url,
	  data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
	  contentType: false,       // The content type used when sending data to the server.
	  cache: false,             // To unable request pages to be cached
	  processData:false,
	  dataType: "json",
	  success:function(data)
	  {
	    if(data.status == 1) {
	      // show file in html before content_button find btn btn-primary
	      $('.Fileexist').remove();
	      var filee = '<a href = "'+base_url_js+'fileGetAny/budgeting-'+data.filename +'" target="_blank" class = "Fileexist">File '+'</a>';
	      $('#content_button').find('.btn-primary').before(filee);
	      toastr.options.fadeOut = 100000;
	      toastr.success(data.msg, 'Success!');
	    }
	    else
	    {
	      toastr.options.fadeOut = 100000;
	      toastr.error(data.msg, 'Failed!!');
	    }
	  setTimeout(function () {
	      toastr.clear();
	    },1000);

	  },
	  error: function (data) {
	    toastr.error(data.msg, 'Connection error, please try again!!');
	  }
	})
}

function file_validation(ID_element)
{
    var files = $('#'+ID_element)[0].files;
    var error = '';
    var msgStr = '';
    var name = files[0].name;
	  // console.log(name);
	  var extension = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(extension, ['pdf','jpg','png','jpeg']) == -1)
	  {
	   msgStr += 'Invalid Type File<br>';
	  }

	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(files[0]);
	  var f = files[0];
	  var fsize = f.size||f.fileSize;
	  // console.log(fsize);

	  if(fsize > 5000000) // 5mb
	  {
	   msgStr += 'Image File Size is very big<br>';
	   //toastr.error("Image File Size is very big", 'Failed!!');
	   //return false;
	  }

    if (msgStr != '') {
      toastr.error(msgStr, 'Failed!!');
      return false;
    }
    else
    {
      return true;
    }
}
</script>

