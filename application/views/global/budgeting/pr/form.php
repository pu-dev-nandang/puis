<div class="row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "dtContent">
	
</div>
<script type="text/javascript">
	var ClassDt = {
		BudgetRemaining : [],
		PRCodeVal : "<?php echo $PRCodeVal ?>",
		Year : "<?php echo $Year ?>",
		Departement : "<?php echo $Departement ?>",
		NmDepartement_Existing : '',
		RuleAccess : [],
		PostBudgetDepartment : [],
		DtExisting : [],
		//PostBudgetDepartment_awal : [],
	};

	var S_Table_example_budget = '';
	var S_Table_example_catalog = '';
	var S_Table_example_combine = '';

	$(document).ready(function() {
		LoadFirstLoad();
	})

	function LoadFirstLoad()
	{
		// check Rule for Input
		var url = base_url_js+"budgeting/checkruleinput";
		var data = {
			NIP : NIP,
		};
		if (ClassDt.PRCodeVal != '') {
			data = {
				NIP : NIP,
				Departement : ClassDt.Departement,
				PRCodeVal : ClassDt.PRCodeVal,
			};
		}
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{ token:token },function (resultJson) {
			
		}).done(function(resultJson) {
		  var response = jQuery.parseJSON(resultJson);

		  var access = response['access'];
		  if (access.length > 0) {
		  	ClassDt.RuleAccess = response;
		  	load_htmlPR();
		  }
		  else
		  {
		  	$("#pageContent").empty();
		  	$("#pageContent").html('<h2 align = "center">Your not authorize these modul</h2>');
		  }
		});
	}


	function load_data_pr()
	{
		var def = jQuery.Deferred();
		var PRCode = ClassDt.PRCodeVal;
		var url = base_url_js+'budgeting/GetDataPR';
		var data = {
		    PRCode : PRCode,
		};
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{ token:token },function (resultJson) {
			
		}).done(function(resultJson) {
			var response = jQuery.parseJSON(resultJson);
			def.resolve(response);
		}).fail(function() {
		  toastr.info('No Result Data');
		  def.reject();  
		}).always(function() {
		                
		});	
		return def.promise();
	}


	function load_budget_remaining__(Year,Departement)
	{
		var def = jQuery.Deferred();
		var url = base_url_js+"budgeting/detail_budgeting_remaining";
		var data = {
				    Year : Year,
					Departement : Departement,
				};
		var token = jwt_encode(data,'UAP)(*');
		$.post(url,{token:token},function (resultJson) {
			
		}).done(function(resultJson) {
			var response2 = jQuery.parseJSON(resultJson);
			def.resolve(response2);
		}).fail(function() {
		  toastr.info('No Result Data'); 
		  def.reject();
		}).always(function() {
		                
		});
		return def.promise();
	}

	function load_htmlPR()
	{
		// check data edit or new
		if (ClassDt.PRCodeVal != '') {
			// edit
			load_data_pr().then(function(response){
				ClassDt.DtExisting = response;
				var arr_pr_create = response['pr_create'];
				var Year = arr_pr_create[0]['Year'];
				ClassDt.NmDepartement_Existing =  arr_pr_create[0]['NameDepartement'];
				var Departement = arr_pr_create[0]['Departement'];

				load_budget_remaining__(Year,Departement).then(function(response2){
					var dt = response2.data;
					ClassDt.PostBudgetDepartment_awal = dt;
					localStorage.setItem("PostBudgetDepartment_awal", JSON.stringify(ClassDt.PostBudgetDepartment_awal));
				})

				var bool = 0;
				var urlInarray = [base_url_js+'budgeting/detail_budgeting_remaining'];

				$( document ).ajaxSuccess(function( event, xhr, settings ) {
				   if (jQuery.inArray( settings.url, urlInarray )) {
				       bool++;
				       if (bool == 1) {
				           setTimeout(function(){
				               Make_PostBudgetDepartment_existing();
				               // // new
				               makeDomExisting(); 

				            }, 500);
				          
				       }
				   }
				});

			})
		}
		else
		{
			// Load Budget Department
			var Year = ClassDt.Year;
			var Departement = ClassDt.Departement;
			var url = base_url_js+"budgeting/detail_budgeting_remaining";
			var data = {
					    Year : Year,
						Departement : Departement,
					};
			var token = jwt_encode(data,'UAP)(*');
			$.post(url,{token:token},function (resultJson) {
				var response = jQuery.parseJSON(resultJson);
				ClassDt.PostBudgetDepartment = response.data;
				ClassDt.PostBudgetDepartment_awal = response.data;
				localStorage.setItem("PostBudgetDepartment", JSON.stringify(ClassDt.PostBudgetDepartment));
				localStorage.setItem("PostBudgetDepartment_awal", JSON.stringify(ClassDt.PostBudgetDepartment_awal));
				// new
				makeDomAwal();
			}).fail(function() {
			  toastr.info('No Result Data'); 
			}).always(function() {
			                
			});
			
		}
	}

	function Make_PostBudgetDepartment_existing()
	{
		/*
			Note : 
			Pengembalian Post Budget using ke awal sebelum pr tercreate
		*/
		arr_budget_departement = JSON.parse(localStorage.getItem("PostBudgetDepartment_awal"));
		var arr = [];
		var DtExisting = ClassDt.DtExisting;
		var arr_pr_detail = DtExisting['pr_detail'];
		for (var i = 0; i < arr_budget_departement.length; i++) {
			var CodePostRealisasi = arr_budget_departement[i]['CodePostRealisasi'];
			var Using = arr_budget_departement[i]['Using'];
			var Value = arr_budget_departement[i]['Value'];
			// console.log(arr_budget_departement[i]);	
			// console.log(arr_pr_detail);	
			// console.log(Using+' Start');	

			for (var j = 0; j < arr_pr_detail.length; j++) {
				var CodePostRealisasi_ = arr_pr_detail[j].CodePostRealisasi;
				if (CodePostRealisasi_ == CodePostRealisasi) {
					var SubTotal = parseInt(arr_pr_detail[j].SubTotal);
					var Cost1 = SubTotal;
					// console.log(Cost1 + ' -- '+CodePostRealisasi);
					// check combine
						var arr_Combine = arr_pr_detail[j].Combine;
						if (arr_Combine.length > 0) {
							for (var k = 0; k < arr_Combine.length; k++) {
								var Cost_Combine = parseInt(arr_Combine[k].Cost_Combine);
								Cost1 = Cost1 - Cost_Combine;
								var CodePostBudget_Combine = arr_Combine[k].CodePostBudget_Combine;
								for (var l = 0; l < arr_budget_departement.length; l++) {
									var CodePostBudget_Combine_ = arr_budget_departement[l]['CodePostRealisasi'];
									if (CodePostBudget_Combine == CodePostBudget_Combine_) {
										// update
										var Using2 = parseInt(arr_budget_departement[l]['Using']);
										Using2 = Using2 - Cost_Combine;
										arr_budget_departement[l]['Using'] = Using2;
										break;
									}
								}
							}

						}

					// console.log(Using+' Before');	
					Using = parseInt(Using) - Cost1;
					// console.log(Using+' After');	
					arr_budget_departement[i]['Using'] = Using;

					//break;
				}
			}

		}
		ClassDt.PostBudgetDepartment = arr_budget_departement;
		localStorage.setItem("PostBudgetDepartment", JSON.stringify(ClassDt.PostBudgetDepartment));
		
	}

	function makeDomExisting()
	{
		var DtExisting = ClassDt.DtExisting;
		var pr_create = DtExisting.pr_create;
		var pr_detail = DtExisting.pr_detail;
		var StatusName = pr_create[0]['StatusName'];
		var html = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;">';
		html += '<div class="col-md-4">'+
					'<p id = "labelPeriod">Period : <label>'+ClassDt.Year+'/'+(parseInt(ClassDt.Year)+1 )+'</label></p>'+
					'<p id = "labelDepartment">Department : '+ClassDt.NmDepartement_Existing+'</p>'+
					'<p id = "labelPrcode">PR Code : '+ClassDt.PRCodeVal+'</p>'+
					'<p id = "Status">Status : '+StatusName+'<br>'+btn_see_pass+'</p>'+
				'</div>'+
				'<div class="col-md-4">'+
					'<div class="well">'+
						'<div style="margin-top: -15px">'+
							'<label>Budget Remaining</label>'+
						'</div>'+
						'<div id = "Page_Budget_Remaining">'+
							''+
						'</div>'+
					'</div>'+
				'</div>';
		html += '</div>';

		var htmlBtnAdd = '<div class = "row" style = "margin-left : 0px">'+
							'<div class = "col-md-3">'+
								'<button type="button" class="btn btn-default btn-add-pr"> <i class="icon-plus"></i> Add</button>'+
							'</div>'+
						'</div>';

		var IsiInputPR = AddingTable_existing();

		var htmlInputPR = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_PR">'+
							'<div class = "col-md-12">'+
								'<div class="table-responsive">'+
									'<table class="table table-bordered tableData" id ="table_input_pr" style = "min-width: 1200px;">'+
									'<thead>'+
									'<tr>'+
										'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 150px;">Budget</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 150px;">Catalog</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Detail</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Spec+</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Desc</th>'+
			                            '<th width = "4%" style = "text-align: center;background: #20485A;color: #FFFFFF;width : 78px;">Qty</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 150px;">Cost</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 78px;">PPN(%)</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 150px;">Sub Total</th>'+
			                            '<th width = "150px" style = "text-align: center;background: #20485A;color: #FFFFFF;">Date Needed</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">File</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Combine Budget</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Action</th>'+
									'</tr></thead>'+
									'<tbody>'+IsiInputPR+'</tbody></table>'+
								'</div>'+
							'</div>'+
						  '</div>';
		var Notes = 	'<div class = "row" style = "margin-top : 10px;margin-left : 0px;margin-right : 0px">'+
							'<div class = "col-md-6">'+
								'<div class = "form-group">'+
									'<label>Note</label>'+
									'<textarea id= "Notes" class = "form-control" rows = "4">'+pr_create[0]['Notes']+'</textarea>'+
								'</div>'+
							'</div>'+
							'<div class = "col-md-6">'+
								'<h3 id = "phtmltotal" align = "right"> Total : '+formatRupiah(0)+'</h3>'+
							'</div>'+
						'</div>';

		var Supporting_documents = '<div class = "row" style = "margin-top : 10px;margin-left : 0px;margin-right : 0px">'+
							'<div class = "col-md-6">'+
								'<div class = "form-group">'+
									'<label>Supporting documents</label>'+
									'<input type="file" data-style="fileinput" class="BrowseFileSD" id="BrowseFileSD" multiple="" accept="image/*,application/pdf">'+
								'</div>'+
							'</div>'+
						'</div>';					  

		var htmlInputFooter = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Footer">'+
							Notes+Supporting_documents+
						  '</div>';

		var htmlApproval = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Approval">'+
						  '</div>';	

		var htmlButton = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Button">'+
						  '</div>';

		$('#dtContent').html(html+htmlBtnAdd+htmlInputPR+htmlInputFooter+htmlApproval+htmlButton);
		$(".SubTotal").maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		$(".SubTotal").maskMoney('mask', '9894');
		$(".UnitCost").maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		$(".UnitCost").maskMoney('mask', '9894');

		$('.datetimepicker').datetimepicker({
			format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
		});
		__BudgetRemaining();

		// Show Supporting_documents if exist
			var Supporting_documents = jQuery.parseJSON(pr_create[0]['Supporting_documents']);
			// console.log(Supporting_documents);
			var htmlSupporting_documents = '';
			if (Supporting_documents != null) {
				if (Supporting_documents.length > 0) {
					for (var i = 0; i < Supporting_documents.length; i++) {
						htmlSupporting_documents += '<li style = "margin-top : 4px;"><a href = "'+base_url_js+'fileGetAny/budgeting-pr-'+Supporting_documents[i]+'" target="_blank" class = "Fileexist">File '+(i+1)+'</a>&nbsp<button class="btn-xs btn-default btn-delete btn-default-warning btn-custom btn-delete-file" filepath = "budgeting-pr-'+Supporting_documents[i]+'" type="button" idtable = "'+pr_create[0]['PRCode']+'" table = "db_budgeting.pr_create" field = "Supporting_documents" typefield = "1" delimiter = "" fieldwhere = "PRCode"><i class="fa fa-trash" aria-hidden="true"></i></button></li>';
					}
				}
			}

			$('#BrowseFileSD').closest('.col-md-6').append(htmlSupporting_documents);

			// disabled tr kecuali last tergantung status, status 3 = reject
				if (pr_create[0]['Status'] == 3) {
					var row = $('#table_input_pr tbody tr:not(:last)');
					row.find('td').find('input,select,button:not(.Detail),textarea').prop('disabled',true);
				}
				else
				{
					$('button:not(#Log):not(#btnBackToHome):not(.Detail)').prop('disabled',true);
					$('input,textarea').prop('disabled',true);
				}
				

		// make kolom approval
			makeApproval();	
		MakeButton();
	}

	function AddingTable_existing()
	{
		// console.log(ClassDt);
		var DtExisting = ClassDt.DtExisting;
		var pr_create = DtExisting.pr_create;
		var pr_detail = DtExisting.pr_detail;
		var Budget =  JSON.parse(localStorage.getItem("PostBudgetDepartment"));
		action = '<td><button type="button" class="btn btn-danger btn-delete btn-delete-item"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>';
		var html = '';
		for (var i = 0; i < pr_detail.length; i++) {
			var ID_budget_left = pr_detail[i]['ID_budget_left'];
			var remaining = 0;
			for (var j = 0; j < Budget.length; j++) {
				var ID_budget_left_ = Budget[j].ID;
				if (ID_budget_left == ID_budget_left_) {
					remaining = parseInt(Budget[j].Value) - parseInt(Budget[j].Using);
					break;
				}
			}

			// for detail catalog
				var Desc = pr_detail[i]['Desc'];
				var EstimaValue = pr_detail[i]['UnitCost'];
				var arr_Photo = pr_detail[i]['Photo'];
				htmlPhoto = '';
				if (arr_Photo != undefined && arr_Photo != null && arr_Photo != '') {
					arr_Photo = arr_Photo.split(',');
					htmlPhoto = '<ul>';
					for (var j = 0; j < arr_Photo.length; j++) {
						htmlPhoto += '<li><a href = "'+base_url_js+'fileGetAny/budgeting-catalog-'+arr_Photo[j]+'" target="_blank">'+
											arr_Photo[j]+'</a></li>';
					}
					htmlPhoto += '</ul>';
				}
				
				var DetailCatalog = jQuery.parseJSON(pr_detail[i]['DetailCatalog']);
				var htmlDetailCatalog = '';
				for (var prop in DetailCatalog) {
					htmlDetailCatalog += prop + ' :  '+DetailCatalog[prop]+'<br>';
				}
				var Item = pr_detail[i]['Item'];
				var arr = Item+'@@'+Desc+'@@'+formatRupiah(EstimaValue)+'@@'+htmlPhoto+'@@'+htmlDetailCatalog;
				arr = findAndReplace(arr, "\"","'");

			var SpecAdd = (pr_detail[i]['Spec_add'] == '' || pr_detail[i]['Spec_add'] == null || pr_detail[i]['Spec_add'] == 'null') ? '' : pr_detail[i]['Spec_add'];
			var Need = (pr_detail[i]['Need'] == '' || pr_detail[i]['Need'] == null || pr_detail[i]['Need'] == 'null') ? '' : pr_detail[i]['Need'];

			var htmlUploadFile = '';
			UploadFile = jQuery.parseJSON(pr_detail[i]['UploadFile']);
			if (UploadFile != null) {
				if (UploadFile.length > 0) {
					for (var j = 0; j < UploadFile.length; j++) {
						htmlUploadFile += '<li style = "margin-top : 4px;"><a href = "'+base_url_js+'fileGetAny/budgeting-pr-'+UploadFile[j]+'" target="_blank" class = "Fileexist">File '+(j+1)+'</a>&nbsp<button class="btn-xs btn-default btn-delete btn-default-warning btn-custom btn-delete-file"  filepath = "budgeting-pr-'+UploadFile[j]+'" type="button" idtable = "'+pr_detail[i]['ID']+'" table = "db_budgeting.pr_detail" field = "UploadFile" typefield = "1" delimiter = "" fieldwhere = "ID"><i class="fa fa-trash" aria-hidden="true"></i></button></li>';
					}
				}
			}

			var Combine = pr_detail[i]['Combine'];
			var HtmlCombine = 'No';
			if (Combine.length > 0) {
				var less = parseInt(remaining) - parseInt(pr_detail[i]['SubTotal']); 
				HtmlCombine = '<button class="btn btn-default SearchPostBudget_Combine" type="button" less="'+less+'"><i class="fa fa-search" aria-hidden="true"></i></button>'+
						'<ul class="liCombine" style="margin-left : -21px;">';

					for (var j = 0; j < Combine.length; j++) {
							HtmlCombine += '<li id_budget_left = "'+Combine[j].ID_budget_left_Combine+'" money = "'+Combine[j].Cost_Combine+'" subsidi = "'+Combine[j].Cost_Combine+'">'+Combine[j].RealisasiPostName_Combine+'</li>';
					}

				HtmlCombine += '</ul>';	

			}


			html += '<tr>'+
						'<td>'+(i+1)+'</td>'+
						'<td>'+
							'<div class="input-group">'+
								'<input type="text" class="form-control PostBudgetItem" readonly id_budget_left = "'+ID_budget_left+'" remaining = "'+remaining+'" value = "'+pr_detail[i]['RealisasiPostName']+'">'+
								'<span class="input-group-btn">'+
									'<button class="btn btn-default SearchPostBudget" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
								'</span>'+
							'</div>'+
							'<label class = "lblBudget">'+pr_detail[i]['RealisasiPostName']+'</label>'+
						'</td>'+
						'<td>'+
							'<div class="input-group">'+
								'<input type="text" class="form-control Item" readonly id_m_catalog = "'+pr_detail[i]['ID_m_catalog']+'" estprice = "'+pr_detail[i]['EstimaValue']+'" value = "'+pr_detail[i]['Item']+'">'+
								'<span class="input-group-btn">'+
									'<button class="btn btn-default SearchItem" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
								'</span>'+
							'</div>'+
							'<label class = "lblCatalog">'+pr_detail[i]['Item']+'</label>'+
						'</td>'+
						'<td><button class = "btn btn-primary Detail" data = "'+arr+'">Detail</button></td>'+
						'<td>'+
							'<textarea class = "form-control SpecAdd" rows = "2">'+SpecAdd+'</textarea>'+
						'</td>'+
						'<td>'+
							'<textarea class = "form-control Need" rows = "2">'+Need+'</textarea>'+
						'</td>'+
						'<td><input type="number" min = "1" class="form-control qty"  value="'+pr_detail[i]['Qty']+'"></td>'+
						'<td><input type="text" class="form-control UnitCost" value="'+parseInt(pr_detail[i]['UnitCost'])+'" disabled></td>'+
						'<td><input type="number" class="form-control PPH" value = "'+parseInt(pr_detail[i]['PPH'])+'"></td>'+
						'<td><input type="text" class="form-control SubTotal" disabled value = "'+parseInt(pr_detail[i]['SubTotal'])+'"></td>'+
						'<td>'+
							'<div class="input-group input-append date datetimepicker">'+
	                            '<input data-format="yyyy-MM-dd" class="form-control" type=" text" readonly="" value = "'+pr_detail[i]['DateNeeded']+'">'+
	                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
	                		'</div>'+
	                	'</td>'+
	                	'<td><input type="file" data-style="fileinput" class = "BrowseFile" multiple accept="image/*,application/pdf" style = "width : 97px;">'+htmlUploadFile+'</td>'+
	                	'<td>'+HtmlCombine+'</td>'+
	                	action
	                '</tr>';
		}
		
        return html;
	}

	function makeDomAwal()
	{
		var html = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;">';
			html += '<div class="col-md-4">'+
						'<p id = "labelPeriod">Period : <label>'+ClassDt.Year+'/'+(parseInt(ClassDt.Year)+1 )+'</label></p>'+
						'<p id = "labelDepartment">Department : '+DivSessionName+'</p>'+
						'<p id = "labelPrcode"></p>'+
						'<p id = "Status"></p>'+
					'</div>'+
					'<div class="col-md-4">'+
						'<div class="well">'+
							'<div style="margin-top: -15px">'+
								'<label>Budget Remaining</label>'+
							'</div>'+
							'<div id = "Page_Budget_Remaining">'+
								''+
							'</div>'+
						'</div>'+
					'</div>';
			html += '</div>';

        var htmlBtnAdd = '<div class = "row" style = "margin-left : 0px">'+
							'<div class = "col-md-3">'+
								'<button type="button" class="btn btn-default btn-add-pr"> <i class="icon-plus"></i> Add</button>'+
							'</div>'+
						'</div>';
		var htmlInputPR = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_PR">'+
							'<div class = "col-md-12">'+
								'<div class="table-responsive">'+
									'<table class="table table-bordered tableData" id ="table_input_pr">'+
									'<thead>'+
									'<tr>'+
										'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 150px;">Budget</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 150px;">Catalog</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Detail</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Spec+</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Desc</th>'+
			                            '<th width = "4%" style = "text-align: center;background: #20485A;color: #FFFFFF;width : 78px;">Qty</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 150px;">Cost</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 78px;">PPN(%)</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 150px;">Sub Total</th>'+
			                            '<th width = "150px" style = "text-align: center;background: #20485A;color: #FFFFFF;">Date Needed</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">File</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Combine Budget</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Action</th>'+
									'</tr></thead>'+
									'<tbody></tbody></table>'+
								'</div>'+
							'</div>'+
						  '</div>';
		var Notes = 	'<div class = "row" style = "margin-top : 10px;margin-left : 0px;margin-right : 0px">'+
							'<div class = "col-md-6">'+
								'<div class = "form-group">'+
									'<label>Note</label>'+
									'<textarea id= "Notes" class = "form-control" rows = "4"></textarea>'+
								'</div>'+
							'</div>'+
							'<div class = "col-md-6">'+
								'<h3 id = "phtmltotal" align = "right"> Total : '+formatRupiah(0)+'</h3>'+
							'</div>'+
						'</div>';

		var Supporting_documents = '<div class = "row" style = "margin-top : 10px;margin-left : 0px;margin-right : 0px">'+
							'<div class = "col-md-6">'+
								'<div class = "form-group">'+
									'<label>Supporting documents</label>'+
									'<input type="file" data-style="fileinput" class="BrowseFileSD" id="BrowseFileSD" multiple="" accept="image/*,application/pdf">'+
								'</div>'+
							'</div>'+
						'</div>';					  

		var htmlInputFooter = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Footer">'+
							Notes+Supporting_documents+
						  '</div>';

		var htmlApproval = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Approval">'+
						  '</div>';	

		var htmlButton = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Button">'+
						  '</div>';

		$('#dtContent').html(html+htmlBtnAdd+htmlInputPR+htmlInputFooter+htmlApproval+htmlButton);	
		MakeButton();
	}

	function makeApproval()
	{
		var DtExisting = ClassDt.DtExisting;
		var pr_create = DtExisting['pr_create'];
		var JsonStatus = jQuery.parseJSON(pr_create[0].JsonStatus);

		/* Page_Approval */
		// only admin & Finance to custom approval
			// console.log(JsonStatus);
			var html_add_approver = '';
			var bool = false;
			if (pr_create[0].CreatedBy == NIP) {
				bool = true;
			}

			if (bool || DivSession == 'NA.9') { // NA.9 Finance
				html_add_approver = '<a href = "javascript:void(0)"  class="btn btn-default btn-default-success" type="button" id = "add_approver" prcode = "'+pr_create[0].PRCode+'">'+
                        			'<i class="fa fa-plus-circle" aria-hidden="true"></i>'+
                    		'</a>';
			}

			var html = '<div class = "col-md-6 col-md-offset-6"><div class = "table-responsive">'+
		    				html_add_approver+
							'<table class = "table table-striped table-bordered table-hover table-checkable tableApproval" style = "margin-top : 5px">'+
								'<thead><tr>';

				// html += '<th>'+'Created by'+'</th>';
				for (var i = 0; i < JsonStatus.length; i++) {
					html += '<th>'+JsonStatus[i].NameTypeDesc+'</th>';
				}
				html +=	'</th></thead>'+'<tbody><tr style = "height : 51px">';

				// html += '<td>'+'<i class="fa fa-check" style="color: green;"></i>'+'</td>'
				for (var i = 0; i < JsonStatus.length; i++) {
					var v = '-';
					if (JsonStatus[i].Status == '2' || JsonStatus[i].Status == 2) {
						v = '<i class="fa fa-times" aria-hidden="true" style="color: red;"></i>';
					}
					else if(JsonStatus[i].Status == '1' || JsonStatus[i].Status == 1 )
					{
						v = '<i class="fa fa-check" style="color: green;"></i>';
					}
					else
					{
						v = '-';
					}
					html += '<td>'+v+'</td>';		
				}
				html += '</tr><tr>';
				// html += '<td>'+pr_create[0].NameCreatedBy+'</td>';
				for (var i = 0; i < JsonStatus.length; i++) {
					html += '<td>'+JsonStatus[i].NameAprrovedBy+'</td>';		
				}
				html +=	'</tr></tbody>'+'</table></div></div>';

				$('#Page_Approval').html(html);

	}

	function MakeButton()
	{
		var dt = ClassDt.RuleAccess;
		if (ClassDt.PRCodeVal != '') { 
			var DtExisting = ClassDt.DtExisting;
			var pr_create = DtExisting.pr_create;
			if (pr_create[0].Status == 3) {
				var html = '<div class = "col-md-6 col-md-offset-6" align = "right">'+
							'<button class = "btn btn-success" id = "SaveSubmit" prcode = "'+ClassDt.PRCodeVal+'" action = "1">Submit</button>'+
						   '</div>';
				var r_access = dt['access'];
				var rule = dt['rule'];
				// allow access dengan ID_m_userrole: "1"
				var bool = false;
				for (var i = 0; i < r_access.length; i++) {
					var ID_m_userrole = r_access[i].ID_m_userrole;
					// search rule Entry = 1
					for (var j = 0; j < rule.length; j++) {
						var ID_m_userrole_ = rule[j].ID_m_userrole;
						if (ID_m_userrole == ID_m_userrole_) {
							var Entry = rule[j].Entry
							if (Entry == 1) {
								bool = true;
								break;
							}
						}
					}
				}

				if (bool) {
					$('#Page_Button').html(html);
				}
				else
				{
					// check rule entry
					$('.btn-add-pr,input[type="file"],.btn-delete-file').prop('disabled',true);
					$('button:not(#Log):not(#btnBackToHome):not(.Detail)').prop('disabled',true);
					$('input,textarea').prop('disabled',true);
				}
			}
			else if(pr_create[0].Status == 1)
			{
				var btn_edit = '';
				var html = '';
				// after submit dan sebelum approval bisa melakukan edit
					var booledit = false;
					var r_access = dt['access'];
					var rule = dt['rule'];
					for (var i = 0; i < r_access.length; i++) {
						var ID_m_userrole = r_access[i].ID_m_userrole;
						// search rule Entry = 1
						for (var j = 0; j < rule.length; j++) {
							var ID_m_userrole_ = rule[j].ID_m_userrole;
							if (ID_m_userrole == ID_m_userrole_) {
								var Entry = rule[j].Entry
								if (Entry == 1) {
									booledit = true;
									break;
								}
							}
						}
					}

					if (booledit) {
						var JsonStatus = jQuery.parseJSON(pr_create[0].JsonStatus);
						var booledit2 = false;
						for (var i = 1; i < JsonStatus.length; i++) {
							if (JsonStatus[i].Status == 1 || JsonStatus[i].Status == '1') {
								booledit2 = true;
								break;
							}
						}

						if (!booledit2) {
							btn_edit = '<button class = "btn btn-primary" id = "btnEditInput"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>&nbsp<button class = "btn btn-success" id = "SaveSubmit" prcode = "'+ClassDt.PRCodeVal+'" action = "1" disabled>Submit</button>&nbsp';
						}
					}

				var JsonStatus = jQuery.parseJSON(pr_create[0].JsonStatus);
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

							// if (JsonStatus[ii]['NameTypeDesc'] != 'Approval by') {
							// 	HierarkiApproval++;
							// }
							// HierarkiApproval++;
						}
						else
						{
							HierarkiApproval++;
						}
						
						// if (NIP == JsonStatus[i]['NIP'] && JsonStatus[i]['NameTypeDesc'] == 'Approval by') {
						if (NIP == JsonStatus[i]['NIP']) {
							bool = true;
							break;
						}
					}
					else
					{
						HierarkiApproval++;
					}
				}

				html = '<div class = "col-md-6 col-md-offset-6" align = "right">'+btn_edit;

				if (bool && HierarkiApproval == NumberOfApproval) { // rule approval
					html += '<button class = "btn btn-primary" id = "Approve" action = "approve" prcode = "'+ClassDt.PRCodeVal+'" approval_number = "'+NumberOfApproval+'">Approve</button>'+
									'&nbsp'+
									'<button class = "btn btn-inverse" id = "Reject" action = "reject" prcode = "'+ClassDt.PRCodeVal+'" approval_number = "'+NumberOfApproval+'">Reject</button>'+
							'</div>';
					
				}

				$("#Page_Button").html(html);
			}
			else
			{
				if (pr_create[0].Status == 2) {
					var html = '<div class = "col-md-12">'+
				   							'<div class = "pull-right">'+
				   								'<button class="btn btn-default" id="pdfprint" PRCode = "'+ClassDt.PRCodeVal+'"> <i class = "fa fa-file-pdf-o"></i> Print PDF</button>'+
				   							'</div>'+
				   						'</div>';
				   	$("#Page_Button").html(html);					
				}
			}

		}
		else
		{
			var html = '<div class = "col-md-6 col-md-offset-6" align = "right">'+
						'<button class = "btn btn-success" id = "SaveSubmit" id_pr_create = "" prcode = "" action = "1">Submit</button>'+
					   '</div>';
			var r_access = dt['access'];
			var rule = dt['rule'];
			// allow access dengan ID_m_userrole: "1"
			var bool = false;
			for (var i = 0; i < r_access.length; i++) {
				var ID_m_userrole = r_access[i].ID_m_userrole;
				// search rule Entry = 1
				for (var j = 0; j < rule.length; j++) {
					var ID_m_userrole_ = rule[j].ID_m_userrole;
					if (ID_m_userrole == ID_m_userrole_) {
						var Entry = rule[j].Entry
						if (Entry == 1) {
							bool = true;
							break;
						}
					}
				}
			}

			if (bool) {
				$('#Page_Button').html(html);
			}
			else
			{
				// check rule entry
				$('.btn-add-pr,input[type="file"],.btn-delete-file').prop('disabled',true);
			}		   
		}
		
	}

	$(document).off('click', '#btnEditInput').on('click', '#btnEditInput',function(e) {
		var row = $('#table_input_pr tbody tr:last');
		row.find('td').find('input:not(.UnitCost):not(.SubTotal),select,button,textarea').prop('disabled',false);
		// if value estprice = 0 then can be edit unit cost
		if (parseInt(row.find('.Item').attr('estprice')) == 0 ) {
			row.find('input.UnitCost').prop('disabled',false);
		}

		$('textarea').prop('disabled',false);
		$('#SaveSubmit').prop('disabled',false);
		$('.btn-add-pr,input[type="file"],.btn-delete-file').prop('disabled',false);
		$(this).remove();
	})

	$(document).off('keydown', '.qty,.PPH').on('keydown', '.qty,.PPH',function(e) {
		if (e.keyCode === 190) {
		    e.preventDefault();
		}
	})

	$(document).off('click', '.btn-add-pr').on('click', '.btn-add-pr',function(e) {
		// before adding row lock all input in last tr
		var row = $('#table_input_pr tbody tr:last');
		row.find('td').find('input,select,button:not(.Detail),textarea').prop('disabled',true);
		row.find('td:eq(13)').find('button').prop('disabled',false);
		AddingTable();
	})

	$(document).off('click', '.btn-delete-item').on('click', '.btn-delete-item',function(e) {
		var tr = $(this).closest('tr');
		tr.remove();
		MakeAutoNumbering();
		var row = $('#table_input_pr tbody tr:last');
		row.find('td').find('input:not(.UnitCost):not(.SubTotal),select,button,textarea').prop('disabled',false);
		row.find('td:eq(13)').find('button').prop('disabled',false);
		__BudgetRemaining(); 
	})	

	function AddingTable()
	{
		action = '<td><button type="button" class="btn btn-danger btn-delete btn-delete-item"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>';
		var html = '<tr>'+
					'<td></td>'+
					'<td>'+
						'<div class="input-group">'+
							'<input type="text" class="form-control PostBudgetItem" readonly>'+
							'<span class="input-group-btn">'+
								'<button class="btn btn-default SearchPostBudget" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
							'</span>'+
						'</div>'+
						'<label class = "lblBudget"></label>'+
					'</td>'+
					'<td>'+
						'<div class="input-group">'+
							'<input type="text" class="form-control Item" readonly>'+
							'<span class="input-group-btn">'+
								'<button class="btn btn-default SearchItem" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
							'</span>'+
						'</div>'+
						'<label class = "lblCatalog"></label>'+
					'</td>'+
					'<td><button class = "btn btn-primary Detail">Detail</button></td>'+
					'<td>'+
						'<textarea class = "form-control SpecAdd" rows = "2"></textarea>'+
					'</td>'+
					'<td>'+
						'<textarea class = "form-control Need" rows = "2"></textarea>'+
					'</td>'+
					'<td><input type="number" min = "1" class="form-control qty"  value="1" disabled></td>'+
					'<td><input type="text" class="form-control UnitCost" disabled></td>'+
					'<td><input type="number" class="form-control PPH" value = "10"></td>'+
					'<td><input type="text" class="form-control SubTotal" disabled value = "0"></td>'+
					'<td>'+
						'<div class="input-group input-append date datetimepicker">'+
                            '<input data-format="yyyy-MM-dd" class="form-control" type=" text" readonly="" value = "<?php echo date('Y-m-d') ?>">'+
                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
                		'</div>'+
                	'</td>'+
                	'<td><input type="file" data-style="fileinput" class = "BrowseFile" multiple accept="image/*,application/pdf" style = "width : 97px;"></td>'+
                	'<td>No</td>'+
                	action
                '</tr>';
        $('#table_input_pr tbody').append(html);
        $('.datetimepicker').datetimepicker({
        	format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
        });
        MakeAutoNumbering();        	
	}

	function MakeAutoNumbering()
	{
		var no = 1;
		$("#table_input_pr tbody tr").each(function(){
			var a = $(this);
			a.find('td:eq(0)').html(no);
			no++;
		})
	}

	$(document).off('click', '.SearchPostBudget').on('click', '.SearchPostBudget',function(e) {
		var ev = $(this);
		var dt = ClassDt.PostBudgetDepartment;
		// show all Budget yang memiliki nilai besar dari 0
		dt = __Selection_BudgetDepartment(dt);	
		var html = '';
		html ='<div class = "row">'+
				'<div class = "col-md-12">'+
					'<table id="example_budget" class="table table-bordered display select" cellspacing="0" width="100%">'+
           '<thead>'+
              '<tr>'+
                 '<th>No</th>'+
                 '<th>Post Budget Item</th>'+
                 '<th>Remaining</th>'+
              '</tr>'+
           '</thead>'+
      '</table></div></div>';

		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Select Budget'+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});

		var table = $('#example_budget').DataTable({
		      "data" : dt,
		      'columnDefs': [
			      {
			         'targets': 0,
			         'searchable': false,
			         'orderable': false,
			         'className': 'dt-body-center',
			         'render': function (data, type, full, meta){
			             return '';
			         }
			      },
			      {
			         'targets': 1,
			         'render': function (data, type, full, meta){
			             return full.NameHeadAccount+'-'+full.RealisasiPostName;
			         }
			      },
			      {
			         'targets': 2,
			         'render': function (data, type, full, meta){
			             return formatRupiah(full.Value-full.Using);
			         }
			      },
		      ],
		      'createdRow': function( row, data, dataIndex ) {
		      		$(row).attr('CodePost', data.CodePost);
		      		$(row).attr('CodeHeadAccount', data.CodeHeadAccount);
		      		$(row).attr('CodePostRealisasi', data.CodePostRealisasi);
		      		$(row).attr('money', (data.Value - data.Using) );
		      		$(row).attr('id_budget_left', data.ID);
		      		$(row).attr('NameHeadAccount', data.NameHeadAccount);
		      		$(row).attr('RealisasiPostName', data.RealisasiPostName);
		      },
		      // 'order': [[1, 'asc']]
		});

		table.on( 'order.dt search.dt', function () {
		        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		            cell.innerHTML = i+1;
		        } );
		    } ).draw();

		S_Table_example_budget = table;

		S_Table_example_budget.on( 'click', 'tr', function (e) {
			var row = $(this);
			var CodePost = row.attr('CodePost');
			var CodeHeadAccount = row.attr('CodeHeadAccount');
			var CodePostRealisasi = row.attr('CodePostRealisasi');
			var money = row.attr('money');
			var id_budget_left = row.attr('id_budget_left');
			var NameHeadAccount = row.attr('NameHeadAccount');
			var RealisasiPostName = row.attr('RealisasiPostName');
			var fillItem = ev.closest('tr');
			fillItem.find('td:eq(1)').find('.PostBudgetItem').val(RealisasiPostName);
			fillItem.find('td:eq(1)').find('.lblBudget').html(RealisasiPostName);
			fillItem.find('td:eq(1)').find('.PostBudgetItem').attr('id_budget_left',id_budget_left);
			fillItem.find('td:eq(1)').find('.PostBudgetItem').attr('remaining',money);
			fillItem.find('td:eq(6)').find('.qty').trigger('change');
			$('#GlobalModalLarge').modal('hide');
		} );
	})

	function __Selection_BudgetDepartment(dt,Min = 0)
	{
		var arr =[];
		for (var i = 0; i < dt.length; i++) {
			var v = parseInt(dt[i].Value) - parseInt(dt[i].Using);
			if (v > Min) {
				arr.push(dt[i]);
			}
		}
		return arr;
	}

	function __Selection_OneHeadAccount(dt,G_PostBudgetItem)
	{

		var arr =[];
		var id_budget_left = G_PostBudgetItem;
		for (var i = 0; i < dt.length; i++) {
			var id_budget_left_ = dt[i].ID;
			if (id_budget_left == id_budget_left_) {
				var CodeHeadAccount = dt[i].CodeHeadAccount;
				for (var j = 0; j < dt.length; j++) {
					var CodeHeadAccount_ = dt[j].CodeHeadAccount;
					if (CodeHeadAccount == CodeHeadAccount_) {
						arr.push(dt[j]);
					}
				}
				break;
			}
		}

		return arr;
	}

	$(document).off('click', '.SearchItem').on('click', '.SearchItem',function(e) {
		var ev = $(this);
		var html = '';
			html ='<div class = "row">'+
					'<div class = "col-md-12">'+
						'<table id="example_catalog" class="table table-bordered display select" cellspacing="0" width="100%">'+
               '<thead>'+
                  '<tr>'+
                     '<th>No</th>'+
                     '<th>Item</th>'+
                     '<th>Desc</th>'+
                     '<th>Estimate Value</th>'+
                     '<th>Photo</th>'+
                     '<th>DetailCatalog</th>'+
                  '</tr>'+
               '</thead>'+
          '</table></div></div>';
			$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Catalog'+'</h4>');
			$('#GlobalModalLarge .modal-body').html(html);
			$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
			$('#GlobalModalLarge').modal({
			    'show' : true,
			    'backdrop' : 'static'
			});

			var dtGetCatalogChoice = [];
			$('.Item').each(function(){
				if ($(this).val() != '') {
					var id_m_catalog = $(this).attr('id_m_catalog');
					dtGetCatalogChoice.push(id_m_catalog);
				}
			})

			var url = base_url_js+'rest/Catalog/__Get_Item';
			var data = {
				action : 'choices',
				auth : 's3Cr3T-G4N',
				department : DivSession,
				approval : 1,
				dtGetCatalogChoice : dtGetCatalogChoice,
			};
		    var token = jwt_encode(data,"UAP)(*");
			var table = $('#example_catalog').DataTable({
			      'ajax': {
			         'url': url,
			         'type' : 'POST',
			         'data'	: {
			         	token : token,
			         },
			         dataType: 'json'
			      },
			      'columnDefs': [{
			         'targets': 0,
			         'searchable': false,
			         'orderable': false,
			         'className': 'dt-body-center',
			         'render': function (data, type, full, meta){
			             return '';
			         }
			      }],
			      // 'columnDefs': [{
			      //    'targets': 3,
			      //    'className': 'dt-body-center',
			      //    'render': function (data, type, full, meta){
			      //    	 // console.log(full);
			      //        return full[3]+'<br>'+'<span style = "color : red">Last Updated<br>'+full[10]+'</span>';
			      //    }
			      // }],
			      'createdRow': function( row, data, dataIndex ) {
			      		$(row).attr('id_m_catalog', data[6]);
			      		$(row).attr('estprice', data[7]);
			      	
			      },
			      // 'order': [[1, 'asc']]
			   });

		table.on( 'order.dt search.dt', function () {
		        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		            cell.innerHTML = i+1;
		        } );
		    } ).draw();
		S_Table_example_catalog = table;

		S_Table_example_catalog.on( 'click', 'tr', function (e) {
			var row = $(this);
			var fillItem = ev.closest('tr');
			var id_m_catalog = row.attr('id_m_catalog');
			var estprice = row.attr('estprice');
			var n = estprice.indexOf(".");
			estprice = estprice.substring(0, n);

			var Item = row.find('td:eq(1)').text();
			var Desc = row.find('td:eq(2)').text();
			var Est = row.find('td:eq(3)').html();
			var Photo = row.find('td:eq(4)').html();
			var DetailCatalog =  row.find('td:eq(5)').html();
			var arr = Item+'@@'+Desc+'@@'+Est+'@@'+Photo+'@@'+DetailCatalog;
			
			fillItem.find('td:eq(2)').find('.Item').val(Item);
			fillItem.find('td:eq(2)').find('.lblCatalog').html(Item);
			fillItem.find('td:eq(2)').find('.Item').attr('id_m_catalog',id_m_catalog);
			fillItem.find('td:eq(2)').find('.Item').attr('estprice',estprice);
			fillItem.find('td:eq(3)').find('.Detail').attr('data',arr);
			fillItem.find('td:eq(7)').find('.UnitCost').val(estprice);
			fillItem.find('td:eq(7)').find('.UnitCost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
			fillItem.find('td:eq(7)').find('.UnitCost').maskMoney('mask', '9894');
			fillItem.find('td:eq(6)').find('.qty').prop('disabled', false);
			if (estprice == 0) {
				fillItem.find('td:eq(7)').find('.UnitCost').prop('disabled', false);
			}

			fillItem.find('td:eq(6)').find('.qty').trigger('change');
			$('#GlobalModalLarge').modal('hide');
		} );    	
	})


	$(document).off('change', '.BrowseFile').on('change', '.BrowseFile',function(e) {
		var ev = $(this);
		var td = $(this).closest('td');
		var ul = td.find('.ulUpload');
		if (ul.length) {
			ul.remove();
		}
		var files = ev[0].files;
		var htmlLi = '';
		for(var count = 0; count<files.length; count++){
			htmlLi += '<li>'+files[count].name+'</li>'
		}
		td.append('<ul class = "ulUpload">'+htmlLi+'</ul>');

	})

	$(document).off('change', '.BrowseFileSD').on('change', '.BrowseFileSD',function(e) {
		var ev = $(this);
		var td = $(this).closest('.col-md-6');
		var ul = td.find('.ulUpload');
		if (ul.length) {
			ul.remove();
		}
		var files = ev[0].files;
		var htmlLi = '';
		for(var count = 0; count<files.length; count++){
			htmlLi += '<li>'+files[count].name+'</li>'
		}
		td.append('<ul class = "ulUpload">'+htmlLi+'</ul>');
	})

	$(document).off('click', '.Detail').on('click', '.Detail',function(e) {
		var data = $(this).attr('data');
		var arr = data.split('@@');
		var html = '';
			html ='<div class = "row">'+
					'<div class = "col-md-12">'+
						'<table id="example" class="table table-bordered display select" cellspacing="0" width="100%">'+
               '<thead>'+
                  '<tr>'+
                     '<th>Item</th>'+
                     '<th>Desc</th>'+
                     '<th>Estimate Value</th>'+
                     '<th>Photo</th>'+
                     '<th>DetailCatalog</th>'+
                  '</tr>'+
               '</thead>'+
               '<tbody><tr>';
               		for (var i = 0; i < arr.length; i++) {
               			html += '<td>'+arr[i]+'</td>';
               		}
               		html += '</tr></tbody>';
         html += '</table></div></div>';
		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Catalog'+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});
	})

	$(document).off('keyup', '.UnitCost').on('keyup', '.UnitCost',function(e) {
		var tr = $(this).closest('tr');
		CountSubTotal_table(tr);
	})	

	$(document).off('change', '.qty').on('change', '.qty',function(e) {
		var tr = $(this).closest('tr');
		CountSubTotal_table(tr);
	})

	$(document).off('change', '.PPH').on('change', '.PPH',function(e) {
		var tr = $(this).closest('tr');
		CountSubTotal_table(tr);
	})

	function CountSubTotal_table(tr)
	{
		var qty = tr.find('.qty').val();
		qty = findAndReplace(qty, ".","");
		var UnitCost = tr.find('.UnitCost').val();
		UnitCost = findAndReplace(UnitCost, ".","");
		var hitung = qty * UnitCost;
		var PPH = ((tr.find('.PPH').val() / 100 ) * hitung).toFixed(2);
		// console.log(PPH);
		hitung = parseInt(hitung) + parseInt(PPH);
		// console.log(hitung);
		tr.find('.SubTotal').val(hitung);
		tr.find('.SubTotal').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		tr.find('.SubTotal').maskMoney('mask', '9894');
		// check jika Combine maka remove li
			if (tr.find('.liCombine').length) {
				tr.find('.liCombine').remove();
			}
		__BudgetRemaining(); 
	}

	function __BudgetRemaining()
	{
		ClassDt.BudgetRemaining = [];
		var Budget = [];
		var Budget =  JSON.parse(localStorage.getItem("PostBudgetDepartment"));
		var GrandTotal = 0;
		
		var BudgetRemaining_arr = [];
		$('.PostBudgetItem').each(function(){
			var arr = [];
			var tr = $(this).closest('tr');
			var id_budget_left =  $(this).attr('id_budget_left');
			var SubTotal = tr.find('.SubTotal').val();
			SubTotal = findAndReplace(SubTotal, ".","");
			GrandTotal = parseInt(GrandTotal) + parseInt(SubTotal);
			
			// check Combine
			var LiCombine = tr.find('.liCombine').find('li');
			if (LiCombine.length) {
				var less = tr.find('.SearchPostBudget_Combine').attr('less');
				less = Math.abs(less);
				LiCombine.each(function(){
					var id_budget_left_com = $(this).attr('id_budget_left');
					for (var i = 0; i < Budget.length; i++) {
						var id_budget_left_ = Budget[i].ID;
						if (id_budget_left_com == id_budget_left_) {
							var Remaining = Budget[i].Value - Budget[i].Using;
							if (less > Remaining) {
								var Using = Remaining;
							}
							else
							{
								var Using = less;
							}
							less = parseInt(less) - parseInt(Remaining);
							var temp = {
								id_budget_left : id_budget_left_com,
								Using : Using,
							}
							arr.push(temp);
							break;
						}
						
					}
				})

				for (var i = 0; i < Budget.length; i++) {
					var id_budget_left_ = Budget[i].ID;
					if (id_budget_left_ == id_budget_left) {
						var Remaining = Budget[i].Value - Budget[i].Using;
						SubTotal = Remaining;
						break;
					}
				}
			}
			else
			{
				for (var i = 0; i < Budget.length; i++) {
					var id_budget_left_ = Budget[i].ID;
					if (id_budget_left_ == id_budget_left) {
						var Remaining = Budget[i].Value - Budget[i].Using;
						// show search combine seperti Post Budget Department
						var less = parseInt(Remaining) - parseInt(SubTotal);
						if (SubTotal > Remaining) {
							var InputCombine = '<button class="btn btn-default SearchPostBudget_Combine" type="button" less = "'+less+'"><i class="fa fa-search" aria-hidden="true"></i></button>';
							tr.find('td:eq(12)').html(InputCombine);
						}
						else
						{
							tr.find('td:eq(12)').html('No');
						}
						break;
					}
				}
			}


			var temp = {
				id_budget_left : id_budget_left,
				Using : SubTotal,
			}
			arr.push(temp);

			for (var i = 0; i < Budget.length; i++) {
				var id_budget_left_ = Budget[i].ID;
				var Using = Budget[i].Using;
				var bool = false;
				for (var j = 0; j < arr.length; j++) {
					var id_budget_left = arr[j].id_budget_left;
					if (id_budget_left == id_budget_left_) {
						Using = parseInt(Using) + parseInt(arr[j].Using);
						bool = true;
					}
				}
				Budget[i].Using = Using;
				if (bool) { // jika Post Budget selected
					// check Budget Remaining already exist
					var bool2 = true;
					for (var j = 0; j < BudgetRemaining_arr.length; j++) {
						id_budget_left_re = BudgetRemaining_arr[j].ID;
						if (id_budget_left_ == id_budget_left_re) {
							// Update Using
							BudgetRemaining_arr[j].Using = Budget[i].Using;
							bool2 = false;
							break;
						}
					}

					if (bool2) {
						BudgetRemaining_arr.push(Budget[i]);
					}
					
				}
			}
		})
		
		ClassDt.PostBudgetDepartment = Budget;
		// localStorage.setItem("PostBudgetDepartment", JSON.stringify(ClassDt.PostBudgetDepartment));
		ClassDt.BudgetRemaining = BudgetRemaining_arr;
		MakeTableRemaining();

		// write Grand total
		$('#phtmltotal').html('Total : '+formatRupiah(GrandTotal));
	}

	function MakeTableRemaining()
	{
		$("#Page_Budget_Remaining").empty();
		var BudgetRemaining = ClassDt.BudgetRemaining;
		var html = '<div class = "row">'+
						'<div class = "col-md-12">'+
						'<div style="overflow : auto;max-height : 200px;">'+
							'<table class="table table-bordered tableData" id ="tableData3">'+
								'<thead>'+
									'<tr>'+
										'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
										'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Budget</th>'+
										'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Remaining</th>'+
									'</tr>'+
								'</thead><tbody>';
									
		for (var i = 0; i < BudgetRemaining.length; i++) {
			var No = i + 1;
			html += '<tr>'+
						'<td>'+No+'</td>'+
						'<td>'+BudgetRemaining[i].NameHeadAccount+'-'+BudgetRemaining[i].RealisasiPostName+'</td>'+
						'<td>'+formatRupiah(BudgetRemaining[i].Value - BudgetRemaining[i].Using)+'</td>'+
					'</tr>';	
		}

		html += '</tbody>'+
				'</table>'+
				'</div>'+
				'</div></div>';		

		$("#Page_Budget_Remaining").html(html);
	}

	$(document).off('click', '.SearchPostBudget_Combine').on('click', '.SearchPostBudget_Combine',function(e) {
		var ev = $(this);
		var dt = ClassDt.PostBudgetDepartment;
		var less = $(this).attr('less');
		less = Math.abs(less);

		// combine in one head account
			var tr = $(this).closest('tr');
			var G_PostBudgetItem = tr.find('.PostBudgetItem').attr('id_budget_left');
			dt = __Selection_OneHeadAccount(dt,G_PostBudgetItem);

		dt = __Selection_BudgetDepartment(dt);
		var html = '';
		html ='<div class = "row">'+
				'<div class = "col-md-12">'+
					'<table id="example_budget_combine" class="table table-bordered display select" cellspacing="0" width="100%">'+
           '<thead>'+
              '<tr>'+
                 '<th>No</th>'+
                 '<th>Post Budget Item</th>'+
                 '<th>Remaining</th>'+
              '</tr>'+
           '</thead>'+
      '</table></div></div>';

		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Select Budget'+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>'+
		              '<button type="button" id="ModalbtnSaveForm_Combine" class="btn btn-success">Save</button>');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});

		var table = $('#example_budget_combine').DataTable({
		      "data" : dt,
		      'columnDefs': [
			      {
			         'targets': 0,
			         'searchable': false,
			         'orderable': false,
			         'className': 'dt-body-center',
			         'render': function (data, type, full, meta){
			             return '<input type="checkbox" name="id[]" value="' + full.ID + '" estvalue="' + (full.Value-full.Using)+'">';
			         }
			      },
			      {
			         'targets': 1,
			         'render': function (data, type, full, meta){
			             return full.NameHeadAccount+'-'+full.RealisasiPostName;
			         }
			      },
			      {
			         'targets': 2,
			         'render': function (data, type, full, meta){
			             return formatRupiah(full.Value-full.Using);
			         }
			      },
		      ],
		      'createdRow': function( row, data, dataIndex ) {
		      		$(row).attr('CodePost', data.CodePost);
		      		$(row).attr('CodeHeadAccount', data.CodeHeadAccount);
		      		$(row).attr('CodePostRealisasi', data.CodePostRealisasi);
		      		$(row).attr('money', (data.Value - data.Using) );
		      		$(row).attr('id_budget_left', data.ID);
		      		$(row).attr('NameHeadAccount', data.NameHeadAccount);
		      		$(row).attr('RealisasiPostName', data.RealisasiPostName);
		      },
		      // 'order': [[1, 'asc']]
		});

		S_Table_example_combine = table;

		$(document).off('click', '#ModalbtnSaveForm_Combine').on('click', '#ModalbtnSaveForm_Combine',function(e) {
			var checkboxArr = [];
			var tot_combine = 0;
			var bool = true;
			S_Table_example_combine.$('input[type="checkbox"]').each(function(){
			  if(this.checked){
			     var tr = $(this).closest('tr');
			     var CodePost = tr.attr('CodePost');
			     var CodeHeadAccount = tr.attr('CodeHeadAccount');
			     var CodePostRealisasi = tr.attr('CodePostRealisasi');
			     var money = tr.attr('money');
			     var id_budget_left = tr.attr('id_budget_left');
			     var NameHeadAccount = tr.attr('NameHeadAccount');
			     var RealisasiPostName = tr.attr('RealisasiPostName');
			     var temp = {
			     	RealisasiPostName : RealisasiPostName,
			     	id_budget_left : id_budget_left,
			     	money : money,
			     }

			     checkboxArr.push(temp);
			     tot_combine = parseInt(tot_combine)+parseInt(money);
			     if (tot_combine >= less && bool === true ) {
			     	bool = false;
			     }
			     else
			     {
			     	bool = true;
			     }

			  }

			}); // exit each function
			
			if (!bool) {
				  var td = ev.closest('td');
				  // check div exist
				  var aa = td.find('.liCombine');
				  if (aa.length) {
				  	aa.remove();
				  }

				 var InputLi = '<ul class = "liCombine" style = "margin-left : -21px;">';
				 var less_ = less;
				 for (var i = 0; i < checkboxArr.length; i++) {
				 	var mon = checkboxArr[i].money;
				 	var Remaining_ = mon;
				 	if (less_ > Remaining_) {
				 		var Subsidi = mon;
				 	}
				 	else
				 	{
				 		var Subsidi = less_;
				 	}
				 	less_ = parseInt(less_) - parseInt(Remaining_);
				 	InputLi += '<li id_budget_left = "'+checkboxArr[i].id_budget_left+'" money = "'+checkboxArr[i].money+'" subsidi = "'+Subsidi+'">'+checkboxArr[i].RealisasiPostName+'</li>';
				  }
					 InputLi += '</ul>';
					 td.append(InputLi);
					 td.attr('style','width : 150px;');

				$('#GlobalModalLarge').modal('hide');
				__BudgetRemaining();	
			}
			else
			{
				if (tot_combine > less) {
					toastr.error('Excess Budget','!!!Failed');
				}
				else
				{
					toastr.error('Insufficient Budget','!!!Failed');
				}
				
			}

		})
	})
	
	function __CekBudgetRemaining()
	{
		var bool = true;
		var dt = ClassDt.BudgetRemaining;
		for (var i = 0; i < dt.length; i++) {
			var v = parseInt(dt[i].Value) - parseInt(dt[i].Using);
			if (v < 0) {
				bool = false;
				toastr.error("Budget Remaining cannot be less than 0",'!!!Error');
				break;
			}
		}

		return bool;
	}
	
	$(document).off('click', '#SaveSubmit').on('click', '#SaveSubmit',function(e) {
		var htmltext = $(this).text();
		if (confirm("Are you sure ?") == true) {
			loading_button('#SaveSubmit');
			/*
				1.Cek Budget Remaining tidak boleh ada yang kurang dari 0
				2.Validation Inputan
				3.Validation Auth Max Limit
				4.Validation File Upload
			*/

			var CekBudgetRemaining = __CekBudgetRemaining();
			var validation = validation_input();
			var PRCode = $(this).attr('prcode');
			var id_pr_create = $(this).attr('id_pr_create');
			var action = $(this).attr('action');
			if (validation && CekBudgetRemaining) {
				SubmitPR(PRCode,id_pr_create,action,'#SaveSubmit');
				// $('#SaveSubmit').prop('disabled',false).html(htmltext);
			}
			else
			{
				$('#SaveSubmit').prop('disabled',false).html(htmltext);
			}
		}

	})

	function __GetMaxLimit()
	{
		var MaxLimit = 0;
		var dt = ClassDt.RuleAccess;
		var access = dt.access;
		var rule = dt.rule;
		console.log(rule);
		for (var i = 0; i < access.length; i++) {
			var NIP_ = access[i].NIP;
			if (NIP_ == NIP) {
				var ID_m_userrole = access[i].ID_m_userrole;
				// get BudgetRemaining
				var dt2 = ClassDt.BudgetRemaining;
				var temp = [];
				for (var j = 0; j < dt2.length; j++) {
					var CodePost = dt2[j].CodePost;
					// hitung paling panjang approval jika ada 2 atau lebih dari Budget Category
						var C_ = 0;
						var IndexID = 0;
						for (var k = 0; k < rule.length; k++) {
							var CodePost_ = rule[k].CodePost;
							var ID_m_userrole_ = rule[k].ID_m_userrole;
							var Approved = rule[k].Approved;
							if (CodePost == CodePost_ && ID_m_userrole == ID_m_userrole_) {
								C_++;
								IndexID = k;
							}
						}

						var temp2 = {
							CodePost : CodePost,
							Count : C_,
							MaxLimit : rule[IndexID].MaxLimit,
						}

						temp.push(temp2);
				}

				// var temp = [
				// 	{
				// 		MaxLimit : 20000,
				// 	},

				// 	{
				// 		MaxLimit : 100000,
				// 	},
				// ]
				// console.log(temp);

				// ambil nilai temp paling tinggi
				MaxLimit = 0;
				for (var j = 0; j < temp.length; j++) {
					// var Count = temp[j].Count;
					var MaxLimit_ = parseInt(temp[j].MaxLimit);
					for (var k = j+1; k < temp.length; k++) {
						// var Count_ = temp[k].Count;
						var MaxLimit__ = parseInt(temp[k].MaxLimit);
						if (MaxLimit__ >= MaxLimit_) {
							// j = k-1;
							break;
						}
						else
						{
							// j = k - 1;
						}

						j = k;
					}

					MaxLimit = MaxLimit_;
				}
				break;
			}
		}
		return MaxLimit;
	}

	function validation_input()
	{
		var find = true;
		var Total = 0
		var aa = $(".PostBudgetItem").length;
		if (aa == 0) {
			toastr.error("Post Budget Item is required",'!!!Error');
		}
		else
		{
			$(".PostBudgetItem").each(function(){
				var fillItem = $(this).closest('tr');
				var PostBudgetItem = $(this).val();
				if (PostBudgetItem == '') {
					find = false;
					toastr.error("Post Budget Item is required",'!!!Error');
					return false;
				}

				var Item = fillItem.find('td:eq(2)').find('.Item').val();
				if (Item == '') {
					find = false;
					toastr.error("Item is required",'!!!Error');
					return false;
				}

				// find subtotal to check maxlimit
					var SubTotal = fillItem.find('.SubTotal').val();
					SubTotal = findAndReplace(SubTotal, ".","");
					SubTotal = parseInt(SubTotal);
					Total += parseInt(SubTotal);


			})

			var MaxLimit = __GetMaxLimit();

			if (Total > MaxLimit) {
				toastr.error("You have authorize Max Limit : "+ formatRupiah(MaxLimit),'!!!Error');
				find = false;
				return false;
			}

			$(".BrowseFile").each(function(){
				var IDFile = $(this).attr('id');
				var ev = $(this);
				if (!file_validation2(ev) ) {
				  $("#SaveSubmit").prop('disabled',true);
				  find = false;
				  return false;
				}
			})

			$(".BrowseFileSD").each(function(){
				var IDFile = $(this).attr('id');
				var ev = $(this);
				if (!file_validation2(ev) ) {
				  $("#SaveSubmit").prop('disabled',true);
				  find = false;
				  return false;
				}
			})
		}
		return find;

	}

	function file_validation2(ev)
	{
	    var files = ev[0].files;
	    var error = '';
	    var msgStr = '';
	    var max_upload_per_file = 4;
	    if (files.length > max_upload_per_file) {
	      msgStr += '1 Document should not be more than 4 Files<br>';

	    }
	    else
	    {
	      for(var count = 0; count<files.length; count++)
	      {
	       var no = parseInt(count) + 1;
	       var name = files[count].name;
	       var extension = name.split('.').pop().toLowerCase();
	       if(jQuery.inArray(extension, ['jpg' ,'png','jpeg','pdf','doc','docx']) == -1)
	       {
	        msgStr += 'File Number '+ no + ' Invalid Type File<br>';
	        //toastr.error("Invalid Image File", 'Failed!!');
	        // return false;
	       }

	       var oFReader = new FileReader();
	       oFReader.readAsDataURL(files[count]);
	       var f = files[count];
	       var fsize = f.size||f.fileSize;
	       // console.log(fsize);

	       if(fsize > 2000000) // 2mb
	       {
	        msgStr += 'File Number '+ no + ' Image File Size is very big<br>';
	        //toastr.error("Image File Size is very big", 'Failed!!');
	        //return false;
	       }
	       
	      }
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

	function SubmitPR(PRCode,id_pr_create,Action,ID_element)
	{
		var Year = ClassDt.Year;
		var Departement = ClassDt.Departement;
		var Notes = $("#Notes").val();
		var FormInsertDetail = [];
		var form_data = new FormData();
		var PassNumber = 0;
		$(".PostBudgetItem").each(function(){
			var FormInsertCombine = [];
			var ID_budget_left = $(this).attr('id_budget_left');
				var fillItem = $(this).closest('tr');
			var ID_m_catalog = fillItem.find('.Item').attr('id_m_catalog');
			var Spec_add = fillItem.find('.SpecAdd').val();
			var Need = fillItem.find('.Need').val();
			var Qty = fillItem.find('.qty').val();
			var UnitCost = fillItem.find('.UnitCost').val();
			UnitCost = findAndReplace(UnitCost, ".","");
			var PPH = fillItem.find('.PPH').val();
			var SubTotal = fillItem.find('.SubTotal').val();
			SubTotal = findAndReplace(SubTotal, ".","");
			var DateNeeded = fillItem.find('.datetimepicker').find('input').val();

			if ( fillItem.find('.BrowseFile').length ) {
				var UploadFile = fillItem.find('.BrowseFile')[0].files;
				for(var count = 0; count<UploadFile.length; count++)
				{
				 form_data.append("UploadFile"+PassNumber+"[]", UploadFile[count]);
				}
			}

			// get combine
				fillItem.find('.liCombine').find('li').each(function(){
					var ID_budget_left_com = $(this).attr('id_budget_left');
					var Cost = $(this).attr('subsidi');

					var temp = {
						ID_budget_left : ID_budget_left_com,
						Cost : Cost,
					};
					FormInsertCombine.push(temp);	
				})

			 var data = {
			 	ID_budget_left : ID_budget_left,
			 	ID_m_catalog : ID_m_catalog,
			 	Spec_add : Spec_add,
			 	Need : Need,
			 	Qty : Qty,
			 	UnitCost : UnitCost,
			 	PPH : PPH,
			 	SubTotal : SubTotal,
			 	DateNeeded : DateNeeded,
			 	FormInsertCombine : FormInsertCombine,
			 	PassNumber : PassNumber,
			 }
			 var token = jwt_encode(data,"UAP)(*");
			 FormInsertDetail.push(token);
			 PassNumber++
		})
		// console.log(form_data);

		if ( $( '#'+'BrowseFileSD').length ) {
			var UploadFile = $('#'+'BrowseFileSD')[0].files;
			for(var count = 0; count<UploadFile.length; count++)
			{
			 form_data.append("Supporting_documents[]", UploadFile[count]);
			}
		}
		
		// return;

		var token = jwt_encode(FormInsertDetail,"UAP)(*");
		form_data.append('token',token);

		form_data.append('Action',Action);

		token = jwt_encode(PRCode,"UAP)(*");
		form_data.append('PRCode',token);

		token = jwt_encode(Year,"UAP)(*");
		form_data.append('Year',token);

		token = jwt_encode(Departement,"UAP)(*");
		form_data.append('Departement',token);

		token = jwt_encode(Notes,"UAP)(*");
		form_data.append('Notes',token);

		token = jwt_encode(ClassDt.BudgetRemaining,"UAP)(*");
		form_data.append('BudgetRemaining',token);

		// var BudgetLeft_awal = JSON.parse(localStorage.getItem("PostBudgetDepartment"));
		// token = jwt_encode(BudgetLeft_awal,"UAP)(*");
		// form_data.append('BudgetLeft_awal',token);

		var BudgetLeft_awal = JSON.parse(localStorage.getItem("PostBudgetDepartment_awal"));
		token = jwt_encode(BudgetLeft_awal,"UAP)(*");
		form_data.append('BudgetLeft_awal',token);

		var url = base_url_js + "budgeting/submitpr"
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
		    switch (Action)
		    {
		       case "1":
		       		var St_error = data['St_error'];
		       		var msg = data['msg'];
		       		if (St_error == 0) {
		       			if (data['BudgetChange'] == 1) {
		       				ClassDt.PRCodeVal = data['PRCode'];
		       				LoadFirstLoad();
		       			}
		       			toastr.error(msg,'!!!Failed');
		       		}
		       		else
		       		{
		       			if (data['BudgetChange'] == 1) { // alert Budget Remaining telah di update oleh transaksi lain
		       				toastr.info('Budget Remaining already have updated by another person.Please check !!!');
		       				loadingStart();
		       				if (ClassDt.PRCodeVal != '') {
		       					// load lagi Budget remaining
		       						var PRCode = ClassDt.PRCodeVal;
		       						var url = base_url_js+'budgeting/GetDataPR';
		       						var data = {
		       						    PRCode : PRCode,
		       						};
		       						var token = jwt_encode(data,"UAP)(*");
		       						$.post(url,{ token:token },function (resultJson) {
		       							var response = jQuery.parseJSON(resultJson);
		       							// Load Budget Department
		       							var arr_pr_create = response['pr_create'];
		       							var Year = arr_pr_create[0]['Year'];
		       							ClassDt.NmDepartement_Existing =  arr_pr_create[0]['NameDepartement'];
		       							var Departement = arr_pr_create[0]['Departement'];
		       							var url = base_url_js+"budgeting/detail_budgeting_remaining";
		       							var data = {
		       									    Year : Year,
		       										Departement : Departement,
		       									};
		       							var token = jwt_encode(data,'UAP)(*');
		       							$.post(url,{token:token},function (resultJson) {
		       								var response2 = jQuery.parseJSON(resultJson);
		       								Make_PostBudgetDepartment_existing(response2.data);
		       								ClassDt.PostBudgetDepartment_awal = response2.data;
		       								localStorage.setItem("PostBudgetDepartment_awal", JSON.stringify(ClassDt.PostBudgetDepartment_awal));
		       								$(".qty").each(function(){
		       									var tr = $(this).closest('tr');
		       									CountSubTotal_table(tr);
		       								})
		       								loadingEnd(1500);
		       							}).fail(function() {
		       							  toastr.info('No Result Data'); 
		       							}).always(function() {
		       							                
		       							});

		       						}).fail(function() {
		       						  toastr.info('No Result Data'); 
		       						}).always(function() {
		       						                
		       						});			       					
		       				}
		       				else
		       				{
		       					// load lagi Budget remaining
		       					var Year = ClassDt.Year;
		       					var Departement = ClassDt.Departement;
		       					var url = base_url_js+"budgeting/detail_budgeting_remaining";
		       					var data = {
		       							    Year : Year,
		       								Departement : Departement,
		       							};
		       					var token = jwt_encode(data,'UAP)(*');
		       					$.post(url,{token:token},function (resultJson) {
		       						var response = jQuery.parseJSON(resultJson);
		       						ClassDt.PostBudgetDepartment = response.data;
		       						ClassDt.PostBudgetDepartment_awal = response.data;
		       						localStorage.setItem("PostBudgetDepartment", JSON.stringify(ClassDt.PostBudgetDepartment));
		       						localStorage.setItem("PostBudgetDepartment_awal", JSON.stringify(ClassDt.PostBudgetDepartment_awal));
		       						$(".qty").each(function(){
		       							var tr = $(this).closest('tr');
		       							CountSubTotal_table(tr);
		       						})
		       						loadingEnd(1500);
		       					}).fail(function() {
		       					  toastr.info('No Result Data'); 
		       					}).always(function() {
		       					                
		       					});
		       				}

		       			}
		       			else
		       			{
		       				// success
		       				$('#labelPrcode').html('PR Code : '+data['PRCode']);
		       				var Status = NameStatus(data['StatusPR']);
		       				$('#Status').html('Status : '+Status);
		       				// Update Variable ClassDt
		       				ClassDt.PRCodeVal = data['PRCode'];
		       				btn_see_pass = '<a href="javascript:void(0)" class = "btn btn-info btn_circulation_sheet" prcode = "'+ClassDt.PRCodeVal+'">Info</a>';
		       				LoadFirstLoad();
		       			}

		       		}
		       		$('#SaveSubmit').prop('disabled',false).html('Submit');

		       		break;
		       case "larry": 
		           alert('Hey');
		       default: 
		           alert('Default case');
		    }

		  },
		  error: function (data) {
		    toastr.error("Connection Error, Please try again", 'Error!!');
		    var nmbtn = '';
		    if (ID_element == '#SaveSubmit') {
		    	nmbtn = 'Submit';
		    }
		    else if(ID_element == '#SaveSubmit')
		    {
		    	nmbtn = 'Submit';
		    }
		    $(ID_element).prop('disabled',false).html(nmbtn);
		  }
		})

	}

	function NameStatus(Status)
	{
		switch (Status)
	    {
	       case "1":
	       case 1:
	       	Status = 'Awaiting Approval';
	       break;
	       case "2":
	       case 2:
	       	Status = 'Done';
	       break;
	       case "3":
	       case 3:
	       	Status = 'Reject';
	       break;
	       default: 
	           alert('No Status');
	    }

	    return Status;
	}

	$(document).off('click', '#Approve').on('click', '#Approve',function(e) {
		if (confirm('Are you sure ?')) {
			loading_button('#Approve');
			var PRCode = $(this).attr('prcode');
			var approval_number = $(this).attr('approval_number');
			var url = base_url_js + 'rest/__approve_pr';
			var data = {
				PRCode : PRCode,
				approval_number : approval_number,
				NIP : NIP,
				action : 'approve',
				auth : 's3Cr3T-G4N',
				DtExisting : ClassDt.DtExisting,
			}

			var token = jwt_encode(data,"UAP)(*");
			$.post(url,{ token:token },function (resultJson) {
				if (resultJson['Reload'] == 1) {
					toastr.info(resultJson['msg']);
					LoadFirstLoad();
				}
				else
				{
					LoadFirstLoad();
					toastr.success('Approve Successful');
					$('#Approve').prop('disabled',false).html('<i class="fa fa-handshake-o"> </i> Approve');
				}
			}).fail(function() {

			  // toastr.info('No Result Data');
			  toastr.error('The Database connection error, please try again', 'Failed!!');
			}).always(function() {
			    //$('#Approve').prop('disabled',false).html('<i class="fa fa-handshake-o"> </i> Approve');
			});
		}

	})

	$(document).off('click', '#Reject').on('click', '#Reject',function(e) {
		if (confirm('Are you sure ?')) {
			var PRCode = $(this).attr('prcode');
			var approval_number = $(this).attr('approval_number');
			// show modal insert reason
			$('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Please Input Reason ! </b> <br>' +
			    '<input type = "text" class = "form-group" id ="NoteDel" style="margin: 0px 0px 15px; height: 30px; width: 329px;" maxlength="100"><br>'+
			    '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
			    '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
			    '</div>');
			$('#NotificationModal').modal('show');

			$("#confirmYes").click(function(){
				var NoteDel = $("#NoteDel").val();
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

				var url = base_url_js + 'rest/__approve_pr';
				var data = {
					PRCode : PRCode,
					approval_number : approval_number,
					NIP : NIP,
					action : 'reject',
					auth : 's3Cr3T-G4N',
					NoteDel : NoteDel,
					DtExisting : ClassDt.DtExisting,
				}

				var token = jwt_encode(data,"UAP)(*");
				$.post(url,{ token:token },function (resultJson) {
					// if (resultJson == '') {
					// 	LoadFirstLoad();
					// }
					// else
					// {
					// 	// $('#reject').prop('disabled',false).html('<i class="fa fa-handshake-o"> </i> Approve');
					// }
					if (resultJson['Reload'] == 1) {
						toastr.info(resultJson['msg']);
						LoadFirstLoad();
					}
					else
					{
						LoadFirstLoad();
						toastr.success('Reject Successful');
						$('#Approve').prop('disabled',false).html('<i class="fa fa-handshake-o"> </i> Approve');
					}
					$('#NotificationModal').modal('hide');
				}).fail(function() {
				  // toastr.info('No Result Data');
				  toastr.error('The Database connection error, please try again', 'Failed!!');
				  $('#NotificationModal').modal('hide');
				}).always(function() {
				    // $('#reject').prop('disabled',false).html('<i class="fa fa-handshake-o"> </i> Approve');
				    //$('#NotificationModal').modal('hide');
				});
			})	
		}

	})

	$(document).off('click', '#pdfprint').on('click', '#pdfprint',function(e) {
		var url = base_url_js+'save2pdf/print/prdeparment';
		var PRCode = $(this).attr('prcode');
		data = {
		  PRCode : PRCode,
		}
		var token = jwt_encode(data,"UAP)(*");
		FormSubmitAuto(url, 'POST', [
		    { name: 'token', value: token },
		]);
	})

	$(document).off('click', '#add_approver').on('click', '#add_approver',function(e) {
	   var prcode = $(this).attr('prcode');
	   var DtExisting = ClassDt.DtExisting;
	   var pr_create = DtExisting['pr_create'];
	   var JsonStatus = jQuery.parseJSON(pr_create[0].JsonStatus);
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
	   	    	if (i == 0) {
	   	    		action = '<button class="btn btn-default btn-default-success btn-edit-approver" data-action="edit" indexjson="'+i+'" prcode = "'+prcode+'"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
	   	    	}
	   	    	else
	   	    	{
	   	    		action = '<button class="btn btn-default btn-default-success btn-edit-approver" data-action="edit" indexjson="'+i+'" prcode = "'+prcode+'"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
	   	    		if (JsonStatus[i]['Status'] != 1) {
	   	    			// action += '<button class="btn btn-default btn-default-danger btn-edit-approver" data-action="delete" indexjson="'+i+'" prcode = "'+prcode+'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
	   	    			action += '';
	   	    		}
	   	    	}
	   	    	
	   	    	html += '<tr>'+
	   	    	      '<td>'+ ke + '</td>'+
	   	    	      '<td NIP = "'+JsonStatus[i]['NIP']+'">'+ JsonStatus[i]['NIP'] +' || '+Name+ '</td>'+
	   	    	      '<td>'+ stjson + '</td>'+
	   	    	      '<td>'+ JsonStatus[i]['NameTypeDesc'] + '</td>'+
	   	    	      '<td>'+ JsonStatus[i]['Visible'] + '</td>'+
	   	    	      '<td>'+ action + '</td>'
	   	    	    '<tr>';	
	   	    }

	   	    // add sisa
	   	    ke = ke + 1;
	   	    for (var i = 0; i < 10 - JsonStatus.length; i++) {
	   	    	if (pr_create[0].Status == '1' || pr_create[0].Status == '3') {
	   	    		var action = '<button class="btn btn-default btn-default-primary btn-classroom btn-edit-approver" data-action="add" indexjson="'+(ke-1)+'" prcode = "'+prcode+'"><i class="fa fa-plus-circle fa-right" aria-hidden="true"></i></button>';
	   	    	}
	   	    	else
	   	    	{
	   	    		var action = '';
	   	    	}
	   	    	
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

	   	    var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>'+
	   	        '';
	   	    $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Custom Approval'+'</h4>');
	   	    $('#GlobalModalLarge .modal-body').html(html);
	   	    $('#GlobalModalLarge .modal-footer').html(footer);
	   	    $('#GlobalModalLarge').modal({
	   	        'show' : true,
	   	        'backdrop' : 'static'
	   	    });
	      })
	});

	$(document).off('click', '.btn-edit-approver').on('click', '.btn-edit-approver',function(e) {
		var prcode = $(this).attr('prcode');
		var action = $(this).attr('data-action');
		var evtd = $(this).closest('td');
		var evtr = $(this).closest('tr');
		var indexjson = $(this).attr('indexjson');
		var DtExisting = ClassDt.DtExisting;
		var pr_create = DtExisting['pr_create'];
		var dtApproval = pr_create;
		switch(action) {
		  case 'add':
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
		    		for (var i = 0; i < m_type_user.length; i++) {
		    			OP2 += '<option value="'+m_type_user[i].ID+'" '+''+'>'+m_type_user[i].Name+'</option>';
		    		}
		    	if (dtApproval[0].Status == '1' || dtApproval[0].Status == '3') {
		    		if (indexjson == 0) {
		    			evtr.find('td:eq(4)').html('<select class=" form-control listVisible">'+
		    									'<option value = "Yes" selected >Yes</option>'+
		    									'<option value = "No" selected >No</option>'+
		    								'</select>');
		    		}
		    		else
		    		{
		    			evtr.find('td:eq(1)').attr('style','width : 30%');	
		    			evtr.find('td:eq(1)').html('<select class=" form-control listemployees">'+
		    									'   <option value = "0" selected>-- No Selected --</option>'+OP+
		    								'</select>');
		    			evtr.find('td:eq(3)').attr('style','width : 20%');	
		    			evtr.find('td:eq(3)').html('<select class=" form-control listTypeUser">'+OP2+
		    								'</select>');
		    			// evtr.find('td:eq(4)').attr('style','width : 10%');	
		    			evtr.find('td:eq(4)').html('<select class=" form-control listVisible">'+
		    									'<option value = "Yes" selected >Yes</option>'+
		    									'<option value = "No" selected >No</option>'+
		    								'</select>');
		    		}		
		    	}
		    	else
		    	{
		    		evtr.find('td:eq(4)').html('<select class=" form-control listVisible">'+
		    								'<option value = "Yes" selected >Yes</option>'+
		    								'<option value = "No" selected >No</option>'+
		    							'</select>');
		    	}

		    	evtd.html('<button class = "btn btn-primary saveapprover" prcode = "'+prcode+'" indexjson = "'+indexjson+'" action = "'+action+'">Save</button>'+
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

		    	evtr.find('td:eq(4)').find('.select2-container').attr('style','width: 94px !important;');	

		    });
		    break;
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
		  			for (var i = 0; i < m_type_user.length; i++) {
		  				OP2 += '<option value="'+m_type_user[i].ID+'" '+''+'>'+m_type_user[i].Name+'</option>';
		  			}
		  		
		  		evtr.find('td:eq(3)').attr('style','width : 20%');	
		  		evtr.find('td:eq(3)').html('<select class=" form-control listTypeUser">'+OP2+
		  							'</select>');
		  		evtr.find('td:eq(4)').html('<select class=" form-control listVisible">'+
		  								'<option value = "Yes" selected >Yes</option>'+
		  								'<option value = "No" selected >No</option>'+
		  							'</select>');
		  		
		  		evtd.html('<button class = "btn btn-primary saveapprover" prcode = "'+prcode+'" indexjson = "'+indexjson+'" action = "'+action+'">Save</button>'+
		  						'');
		  		$('.listemployees[tabindex!="-1"]').select2({
		  		    //allowClear: true
		  		});

		  		evtr.find('td:eq(4)').find('.select2-container').attr('style','width: 94px !important;');	

		  	});	
		  	break; 
		  case 'delete':
		  	 if (confirm('Are you sure ?')) {
		  	 	loading_button('.btn-edit-approver[indexjson="'+indexjson+'"][action="'+action+'"]');
		  	 	// var url = base_url_js + 'budgeting/update_approval_budgeting';
		  	 	var url = base_url_js + 'budgeting/update_approval_pr';
		 			var data = {
		 				prcode : prcode,
		 				action : action,
		 				indexjson : indexjson,
		 			}
		  	 	var token = jwt_encode(data,"UAP)(*");
		  	 	$.post(url,{ token:token },function (data_json) {
		  	 		var response = jQuery.parseJSON(data_json);
		  	 		if (response['msg'] == '') { // action success
		  	 			var dt = JSON.stringify(response['data']);
		  	 			var cc = pr_create;
		  	 			cc[0].JsonStatus = dt;
		  	 			ClassDt.DtExisting['pr_create'] = cc
		  	 			makeApproval();

		  	 			evtr.find('td:eq(1)').html('-');
		  	 			evtr.find('td:eq(2)').html('-');
		  	 			evtr.find('td:eq(3)').html('-');
		  	 			evtr.find('td:eq(4)').html('-');
		  	 			var action = '<button class="btn btn-default btn-default-primary btn-classroom btn-edit-approver" data-action="add" indexjson="'+indexjson+'" prcode = "'+prcode+'"><i class="fa fa-plus-circle fa-right" aria-hidden="true"></i></button>';
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
		var NIP_ = evtr.find('td:eq(1)').find('.listemployees').val();
		var NameTypeDesc = evtr.find('td:eq(3)').find('.listTypeUser option:selected').text();
		var Visible = evtr.find('td:eq(4)').find('.listVisible').val();
		var vt = evtr.find('td:eq(4)').find('.listVisible option:selected').text();
		var prcode = $(this).attr('prcode');
		var action = $(this).attr('action');
		var indexjson = $(this).attr('indexjson');
		var DtExisting = ClassDt.DtExisting;
		var pr_create = DtExisting['pr_create'];
		var dtApproval = pr_create;
		if (indexjson == 0) {
			NIP_ = evtr.find('td:eq(1)').attr('nip');
			loading_button('.saveapprover[indexjson="'+indexjson+'"]');
			// var url = base_url_js + 'budgeting/update_approval_budgeting';
			var url = base_url_js + 'budgeting/update_approval_pr';
			var data = {
				NIP : NIP_,
				prcode : prcode,
				Visible : Visible,
				action : action,
				indexjson : indexjson,
			}
			var token = jwt_encode(data,"UAP)(*");
			$.post(url,{ token:token },function (data_json) {
				var response = jQuery.parseJSON(data_json);
				if (response['msg'] == '') { // action success
					var dt = JSON.stringify(response['data']);
					var cc = pr_create;
					cc[0].JsonStatus = dt;
					ClassDt.DtExisting['pr_create'] = cc;
					makeApproval();
					var st = 'Approve';
					evtr.find('td:eq(2)').html(st);
					evtr.find('td:eq(4)').html(vt);

					action = '<button class="btn btn-default btn-default-success btn-edit-approver" data-action="edit" indexjson="'+indexjson+'" prcode = "'+prcode+'"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
					evtr.find('td:eq(5)').html(action);
				}
				else
				{
					toastr.error(response['msg'],'!!!Failed');
				}
				$('.saveapprover[indexjson="'+indexjson+'"]').prop('disabled',false).html('Save');
			});
		}
		else
		{
			if (action == 'add') {
				if (NIP_ != '' && NIP_ != undefined && NIP_ != null && NIP_ != 0) {
					loading_button('.saveapprover[indexjson="'+indexjson+'"]');
					// var url = base_url_js + 'budgeting/update_approval_budgeting';
					var url = base_url_js + 'budgeting/update_approval_pr';
					var data = {
						NIP : NIP_,
						prcode : prcode,
						NameTypeDesc : NameTypeDesc,
						Visible : Visible,
						action : action,
						indexjson : indexjson,
					}
					var token = jwt_encode(data,"UAP)(*");
					$.post(url,{ token:token },function (data_json) {
						var response = jQuery.parseJSON(data_json);
						if (response['msg'] == '') { // action success
							var dt = JSON.stringify(response['data']);
							var cc = pr_create;
							cc[0].JsonStatus = dt;
							ClassDt.DtExisting['pr_create'] = cc;
							makeApproval();
							var Nm = evtr.find('td:eq(1)').find('.listemployees option:selected').text();
							var st = 'Not Approve';
							var tu = NameTypeDesc;
							var vt = evtr.find('td:eq(4)').find('.listVisible option:selected').text();
							// console.log(NIP_)
							// console.log(Nm) // ex = 1014026 | Sovie Liestiyani
							var rrr = Nm.split(' | ');
							// console.log(rrr);
							evtr.find('td:eq(1)').html(NIP_ + ' || '+rrr[1]);
							evtr.find('td:eq(2)').html(st);
							evtr.find('td:eq(3)').html(tu);
							evtr.find('td:eq(4)').html(vt);

							action = '<button class="btn btn-default btn-default-success btn-edit-approver" data-action="edit" indexjson="'+indexjson+'" prcode = "'+prcode+'"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
							// action += '<button class="btn btn-default btn-default-danger btn-edit-approver" data-action="delete" indexjson="'+indexjson+'" prcode = "'+prcode+'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
							action += '';
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
			}
			else
			{
				NIP_ = evtr.find('td:eq(1)').attr('nip');
				loading_button('.saveapprover[indexjson="'+indexjson+'"]');
				// var url = base_url_js + 'budgeting/update_approval_budgeting';
				var url = base_url_js + 'budgeting/update_approval_pr';
				var data = {
					NIP : NIP_,
					prcode : prcode,
					NameTypeDesc : NameTypeDesc,
					Visible : Visible,
					action : action,
					indexjson : indexjson,
				}
				var token = jwt_encode(data,"UAP)(*");
				$.post(url,{ token:token },function (data_json) {
					var response = jQuery.parseJSON(data_json);
					if (response['msg'] == '') { // action success
						var dt = JSON.stringify(response['data']);
						var cc = pr_create;
						cc[0].JsonStatus = dt;
						ClassDt.DtExisting['pr_create'] = cc;
						makeApproval();
						var st = 'Approve';
						evtr.find('td:eq(2)').html(st);
						evtr.find('td:eq(4)').html(vt);
						var tu = NameTypeDesc;
						evtr.find('td:eq(3)').html(tu);

						action = '<button class="btn btn-default btn-default-success btn-edit-approver" data-action="edit" indexjson="'+indexjson+'" prcode = "'+prcode+'"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
						evtr.find('td:eq(5)').html(action);
					}
					else
					{
						toastr.error(response['msg'],'!!!Failed');
					}
					$('.saveapprover[indexjson="'+indexjson+'"]').prop('disabled',false).html('Save');
				});	
			}
			
		} // exit if indexjson
		
	}) 
</script>