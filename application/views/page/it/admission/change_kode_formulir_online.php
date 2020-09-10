<style type="text/css">
	.tableData tbody tr:hover {
	   background-color:#71d1eb !important;
	   cursor: pointer;
	}
</style>
<div class="row" style="margin-top: 30px;">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i><?php echo $NameMenu ?></h4>
			</div>
			<div class="widget-content">
				<div class = "row">	
					<div class="col-md-2" style="">
						Angkatan
						<select class="select2-select-00 col-md-4 full-width-fix" id="selectTahun">
						    <option></option>
						</select>
					</div>
					<div class="col-md-2" style="">
						Nomor Formulir
						<input class="form-control" id="NomorFormulir" placeholder="All...">
					</div>
					<div class="col-md-2" style="">
						Status Activated by Candidate
						<select class="select2-select-00 col-md-4 full-width-fix" id="selectStatus">
						    <option value= "%" selected>All</option>
						    <option value= "0">No</option>
						    <option value= "1">Yes</option>
						</select>
					</div>
					<div  class="col-md-4 col-md-offset-2" align="right" id="pagination_link"></div>	
					<!-- <div class = "table-responsive" id= "register_document_table"></div> -->
				</div>
				<div class="row" style="margin-top: 10px">
					<div class="col-md-12">
						<div id= "formulir_online_table"></div>
					</div>
				</div>
			</div>
		</div>
	</div> <!-- /.col-md-6 -->
</div>
<div id = "form-input">

</div>	

<script type="text/javascript">
	$(document).ready(function () {
		loadTahun();
	    loadData(1);
	});

	$(document).on('change','#selectStatus', function () {
    	loadData(1);
    });

    $(document).on('change','#selectTahun', function () {
    	loadData(1);
    });

    $(document).on("keyup", "#NomorFormulir", function(event){
    	var nama = $('#NomorFormulir').val();
    	var n = nama.length;
    	console.log(n);
    	if( this.value.length < 3 && this.value.length != 0 ) return;
    	   /* code to run below */
    	 loadData(1);
	  
	});



	function loadData(page)
	{
		loading_page('#formulir_online_table');
		var url = base_url_js+'admission/distribusi-formulir/formulir-online/pagination/'+page;
		var selectTahun = $("#selectTahun").find(':selected').val();
		var NomorFormulir = $("#NomorFormulir").val();
		if (NomorFormulir == '') {NomorFormulir = '%'};
		var NamaStaffAdmisi = $("#NamaStaffAdmisi").val();
		if (NamaStaffAdmisi == '') {NamaStaffAdmisi = '%'};
		var selectStatus = $("#selectStatus").find(':selected').val();
		var data = {
					selectTahun : selectTahun,
					NomorFormulir : NomorFormulir,
					NamaStaffAdmisi : NamaStaffAdmisi,
					selectStatus : selectStatus,					
					};
		var token = jwt_encode(data,"UAP)(*");			
		$.post(url,{token:token},function (data_json) {
		    // jsonData = data_json;
		    var obj = JSON.parse(data_json); 
		    // console.log(obj);
		    setTimeout(function () {
	       	    $("#formulir_online_table").html(obj.tabel_formulir_online);
	            $("#pagination_link").html(obj.pagination_link);
		    },500);
		}).done(function() {
	      
	    }).fail(function() {
	      toastr.error('The Database connection error, please try again', 'Failed!!');;
	    }).always(function() {
	      // $('#btn-dwnformulir').prop('disabled',false).html('Formulir');
	    });
	}

	$(document).on("click", ".pagination li a", function(event){
	  event.preventDefault();
	  var page = $(this).data("ci-pagination-page");
	  loadData(page)
	  // loadData_register_document(page);
	 });

	function loadTahun()
    {
    	var academic_year_admission = "<?php echo $academic_year_admission ?>"; 
    	var thisYear = (new Date()).getFullYear();
      	var startTahun = parseInt(thisYear) - 1;
     	 var selisih = (2018 < parseInt(thisYear)) ? parseInt(1) + (parseInt(thisYear) - parseInt(2018)) : 1;
     	 for (var i = 0; i <= selisih; i++) {
          var selected = (( parseInt(startTahun) + parseInt(i) )==academic_year_admission) ? 'selected' : '';
          $('#selectTahun').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
     	 }

     	 $('#selectTahun').select2({
     	   // allowClear: true
     	 });

        $('#selectStatus').select2({
          // allowClear: true
        });
    }

    $(document).on('click','.btnActionITAdmission',function(e){
        var ev = $(this).closest('tr');
        var td = $(this).html();
        var index = $(this).index();
        var data_id = ev.attr('data-id');
        var DataToken = ev.attr('token');
        var selector = $('#form-input');
        if (data_id != null && data_id != 'null' && data_id !== undefined && data_id != '') {
            loadingStart();
            ev_click_td(data_id,DataToken,selector);
            loadingEnd(500);
            $('html, body').animate({ scrollTop: $("#content-input-edit").offset().top }, 'slow');
        }
    })

    // $(document).off('dblclick', '.tableData tbody tr td').on('dblclick', '.tableData tbody tr td',function(e) {
    // 	var ev = $(this).closest('tr');
    // 	var td = $(this).html();
    // 	var index = $(this).index();
    // 	var data_id = ev.attr('data-id');
    // 	var DataToken = ev.attr('token');
    // 	var selector = $('#form-input');
    // 	//console.log(td);
    // 	//console.log(index);
    	
    // 	if (data_id != null && data_id != 'null') {
    // 		loadingStart();
    // 		ev_click_td(data_id,DataToken,selector);
    // 		loadingEnd(500);
    // 	}
    	

    // })


    function ev_click_td(data_id,DataToken,selector)
    {
    	var html = '';
    	html += '<div class = "row" style = "margin-top : 10px;">'+
    				'<div class = "col-xs-12">'+
    					'<div class = "well">'+	
    						'<div class = "row">'+
    							'<div class = "col-xs-6">'+
    								'<div class = "thumbnail">'+
    									'<div style = "padding:15px;">'+
    										'<h2>Edit Number Formulir</h2>'+
    									'</div>'+	
    									'<div id="content-input-edit" style = "padding:15px;"></div>'+
    								'</div>'+
    						    '</div>'+
        						'<div class = "col-xs-6">'+
        							'<div class = "thumbnail">'+
        								'<div style = "padding:15px;">'+
        									'<h2>Exchange Number Formulir</h2>'+
        								'</div>'+	
        								'<div id="content-input-exchange" style = "padding:15px;"></div>'+
        							'</div>'+
        						'</div>'+
    					    '</div>'+
                            '<div class = "row" style = "margin-top:10px;">'+
                                '<div class = "col-xs-8 col-md-offset-2">'+
                                    '<div class = "thumbnail">'+
                                        '<div style = "padding:15px;">'+
                                            '<h2>Unsell Number Formulir</h2>'+
                                        '</div>'+   
                                        '<div id="content-input-unsell" style = "padding:15px;"></div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
    				'</div>'+
    			'</div>';

    	selector.html(html);
    	var selector_edit = $('#content-input-edit');
    	form_edit(data_id,DataToken,selector_edit);
    	var selector_edit = $('#content-input-exchange');					
        form_exchange(data_id,DataToken,selector_edit);                 
    	formUnsell.writeHtml(data_id,DataToken);					
    }


    const formUnsell = {
        writeHtml : (data_id,DataToken) => {
            let html = '';
            var dt = jwt_decode(DataToken);
            console.log(dt)
            const selectorPage = $('#content-input-unsell');
            html = '<div class = "row">'+
                        '<div class = "col-md-12" >'+
                            '<div style="background: lightyellow; border: 1px solid #ccc;padding: 15px;color: green;margin-bottom: 20px;">'+
                                '<b>FormulirCode : '+dt['FormulirCode']+' / '+dt['No_Ref']+' , <span style = "color:blue;">Name : '+dt['NameCandidate']+'</span> , School : '+dt['SchoolName']+'</b>'+
                            '</div>'+
                        '</div>'+
                   '</div>'+
                   '<div class = "row">'+
                        '<div class = "col-md-12" >'+
                            '<button class = "btn btn-success btnUnsellFormulir" DataToken = "'+DataToken+'" style ="width:100%;" >Save</button>'+
                        '</div>'+
                   '</div>'+
                   '<div class = "row" style = "margin-top:10px">'+
                        '<div class = "col-md-12" >'+
                            '<div class = "thumbnail resultUnsell" style = "padding:10px;min-height:100px;border: 2px solid #2eb67deb;">'+
                                '<div class = "row">'+
                                    '<div class = "col-md-12">'+
                                        '<p style = "color:red;text-align:center;">No action</p>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                   '</div>';
            selectorPage.html(html);
        },

        saveUnsell : async(selector,formulirKodeArr) => {
            var url = base_url_js+"it/admission/submit-change-kode-formulir-online";
            const data = {
                action : 'Unsell',
                data : formulirKodeArr
            }

            var token = jwt_encode(data,"UAP)(*");
            if (confirm('Are you sure ?')) {
                loading_button2(selector);

                try{
                    const response = await AjaxSubmitFormPromises(url,token);
                    if (response.Status == 1) {
                        toastr.success(response.msg);
                        selector.remove();
                        loadData(1);
                    }
                    else
                    {
                        toastr.error(response.msg,'!!Failed');
                    }

                    const callbackData  = response.callback;
                    formUnsell.showResultAction(callbackData,response.msg)
                }
                catch(err){
                    toastr.info('something wrong');
                }

                end_loading_button2(selector);
            }
            
        },

        showResultAction : (dataResult,msg) => {
            const selectorPage = $('.resultUnsell').find('.col-md-12');
            let htmlTbody = '';
            for (var i = 0; i < dataResult.length; i++) {
                let rel = '';
                const d = dataResult[i].relationTbl;
                for (var z = 0; z < d.length; z++) {
                    rel += '<li>'+d[z]+'</li>';
                }
                htmlTbody += '<tr>'+
                                   '<td>'+ dataResult[i].tbl+'</td>'+
                                   '<td>'+ rel+'</td>'+
                                   '<td>'+ dataResult[i]['status']['msg']+'</td>'+
                                   '<td>'+ dataResult[i].action+'</td>'+
                             '</tr>'
            }
            let html = '<table class = " table">'+
                            '<caption><span style = "color:blue;">'+msg+'</span></caption>'+
                            '<thead>'+
                                '<tr>'+
                                    '<th>Table</th>'+
                                    '<th>Relation Table</th>'+
                                    '<th>Step Status</th>'+
                                    '<th>Action</th>'+
                                '</tr>'+
                            '</thead>'+
                            '<tbody>'+htmlTbody+'</tbody>'+
                       '</table>';
            selectorPage.html(html)
        }
    }

    $(document).on('click','.btnUnsellFormulir',function(e){
        const itsme = $(this);
        const dt  = jwt_decode($(this).attr('DataToken'));
        const dataFormulir = {
            FormulirCode : dt['FormulirCode'],
            No_Ref : dt['No_Ref'],
        }

        formUnsell.saveUnsell(itsme,dataFormulir);
    })

    function load_formulir_(Year,Status = '')
    {
    	var def = jQuery.Deferred();
    	var url = base_url_js+'rest2/__get_data_formulir_no_ref';
    	var data = {
    	    Status : Status,
    	    Year : Year,
            TypeFormulir : 'On',
    	};
    	var token = jwt_encode(data,"UAP)(*");
    	$.post(url,{ token:token },function (resultJson) {
    		
    	}).done(function(resultJson) {
    		def.resolve(resultJson);
    	}).fail(function() {
    	  toastr.info('No Result Data');
    	  def.reject();  
    	}).always(function() {
    	                
    	});	
    	return def.promise();
    }

    function form_exchange(data_id,DataToken,selector)
    {
    	var html = '';
    	var dt = jwt_decode(DataToken);
    	// console.log(dt);
    	html += '<div class = "row" style = "margin-top:10px;">'+
    				'<div class = "col-xs-4">'+
    					'<div class = "form-group">'+
    						'<label>No Ref Selected</label>'+
    						'<input type = "text" class = "form-control input_selected_ex" name="No_Ref" value = "'+dt['No_Ref']+'" disabled Years = "'+dt['Years']+'">'+
    					'</div>'+
    				'</div>'+
    				'<div class = "col-xs-4">'+
    					'<div class = "form-group">'+
    						'<label>Year No Ref</label>'+
    						'<select class="select2-select-00 full-width-fix selectTahun_input_ex" name = "Year_Replacement">'+
    						    '<option></option>'+
    						'</select>'+
    					'</div>'+
    				'</div>'+
    				'<div class = "col-xs-4">'+
    					'<div class = "form-group">'+
    						'<label>No Ref Replacement</label>'+
    						'<select class="select2-select-00 full-width-fix" id="No_Ref_Replacement_ex">'+
    						    '<option></option>'+
    						'</select>'+
    					'</div>'+
    				'</div>'+
    			'</div>'+
    			'<div class = "row">'+
    				'<div class = "col-xs-12">'+
    					'<div class = "form-group">'+
    						'<button class="btn btn-block btn-success" id="btnSave_edit_ex" data-id="'+data_id+'" token = "'+DataToken+'">Save</button>'+
    					'</div>'+
    				'</div>'		
    			'</div>';
    	selector.html(html);

    	var selector_tahun = $('.selectTahun_input_ex');
    	loadTahun2(selector_tahun);
    	var Year = $('.selectTahun_input_ex[name="Year_Replacement"] option:selected').val();
    	load_formulir_(Year,1).then(function(response2){
    		$('#No_Ref_Replacement_ex').empty();
    		for (var i = 0; i < response2.length; i++) {
    			$('#No_Ref_Replacement_ex').append('<option value = "'+response2[i].FormulirCodeGlobal+'">'+response2[i].FormulirCodeGlobal+'</option>')
    		}

    		$('#No_Ref_Replacement_ex').select2({
    		  // allowClear: true
    		});
    	})
    }

    function form_edit(data_id,DataToken,selector)
    {	
    	var html = '';
    	var dt = jwt_decode(DataToken);
    	// console.log(dt);
    	html += '<div class = "row" style = "margin-top:10px;">'+
    				'<div class = "col-xs-4">'+
    					'<div class = "form-group">'+
    						'<label>No Ref Selected</label>'+
    						'<input type = "text" class = "form-control input_selected" name="No_Ref" value = "'+dt['No_Ref']+'" disabled Years = "'+dt['Years']+'">'+
    					'</div>'+
    				'</div>'+
    				'<div class = "col-xs-4">'+
    					'<div class = "form-group">'+
    						'<label>Year No Ref</label>'+
    						'<select class="select2-select-00 full-width-fix selectTahun_input" name = "Year_Replacement">'+
    						    '<option></option>'+
    						'</select>'+
    					'</div>'+
    				'</div>'+
    				'<div class = "col-xs-4">'+
    					'<div class = "form-group">'+
    						'<label>No Ref Replacement</label>'+
    						'<select class="select2-select-00 full-width-fix" id="No_Ref_Replacement">'+
    						    '<option></option>'+
    						'</select>'+
    					'</div>'+
    				'</div>'+
    			'</div>'+
    			'<div class = "row">'+
    				'<div class = "col-xs-12">'+
    					'<div class = "form-group">'+
    						'<button class="btn btn-block btn-success" id="btnSave_edit_number" data-id="'+data_id+'" token = "'+DataToken+'">Save</button>'+
    					'</div>'+
    				'</div>'		
    			'</div>';
    	selector.html(html);

    	var selector_tahun = $('.selectTahun_input');
    	loadTahun2(selector_tahun);
    	var Year = $('.selectTahun_input[name="Year_Replacement"] option:selected').val();
    	load_formulir_(Year,0).then(function(response2){
    		$('#No_Ref_Replacement').empty();
    		for (var i = 0; i < response2.length; i++) {
    			$('#No_Ref_Replacement').append('<option value = "'+response2[i].FormulirCodeGlobal+'">'+response2[i].FormulirCodeGlobal+'</option>')
    		}

    		$('#No_Ref_Replacement').select2({
    		  // allowClear: true
    		});
    	})				
    }

    function loadTahun2(selector)
    {
    	var academic_year_admission = "<?php echo $academic_year_admission ?>"; 
    	var thisYear = (new Date()).getFullYear();
      	var startTahun = parseInt(thisYear);
     	 var selisih = (2018 < parseInt(thisYear)) ? parseInt(1) + (parseInt(thisYear) - parseInt(2018)) : 1;
     	 for (var i = 0; i <= selisih; i++) {
          var selected = (( parseInt(startTahun) + parseInt(i) )==academic_year_admission) ? 'selected' : '';
          selector.append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
     	 }

     	 selector.select2({
     	   // allowClear: true
     	 });
    }

    $(document).off('change', '.selectTahun_input[name="Year_Replacement"]').on('change', '.selectTahun_input[name="Year_Replacement"]',function(e) {
    	var y = $('.selectTahun_input[name="Year_Replacement"] option:selected').val();
    	load_formulir_(y,0).then(function(response2){
    		$('#No_Ref_Replacement').empty();
    		for (var i = 0; i < response2.length; i++) {
    			$('#No_Ref_Replacement').append('<option value = "'+response2[i].FormulirCodeGlobal+'">'+response2[i].FormulirCodeGlobal+'</option>')
    		}

    		$('#No_Ref_Replacement').select2({
    		  // allowClear: true
    		});
    	})
    })

    $(document).off('click', '#btnSave_edit_number').on('click', '#btnSave_edit_number',function(e) {
    	var ID = $(this).attr('data-id');
    	var DataToken = $(this).attr('token');
    	DataToken = jwt_decode(DataToken);
    	var FormulirCodeOnline =DataToken['FormulirCode'];
    	var No_Ref_Selected = $('.input_selected[name="No_Ref"]').val();
    	var Year_Selected = $('.input_selected[name="No_Ref"]').attr('years');
    	var change_set_ta = 0;
    	var Year_Replacement = $('.selectTahun_input[name="Year_Replacement"] option:selected').val();
    	var No_Ref_Replacement = $('#No_Ref_Replacement option:selected').val();
    	if (confirm('Are you sure ?') ) {
    		if (Year_Selected != Year_Replacement) {
    			if (confirm('Apakah Perubahan ini mengikuti Set TA')) {
    				change_set_ta = 1;
    			}
    		}
    		if (No_Ref_Selected != null && No_Ref_Selected != 'null') {
	    		var url = base_url_js+"it/admission/submit-change-kode-formulir-online";
				var data = {
					action : 'EditNumberFormulir',
					No_Ref_Selected : No_Ref_Selected,
					No_Ref_Replacement : No_Ref_Replacement,
					change_set_ta : change_set_ta,
					FormulirCodeOnline : FormulirCodeOnline,
					Year_Replacement : Year_Replacement,
					RegisterID : ID,
				};
	    		var token = jwt_encode(data,"UAP)(*");
	    		loading_button('#btnSave_edit_number');
	    		$.post(url,{ token:token },function (resultJson) {
	    			var response = jQuery.parseJSON(resultJson);
	    			if (response.Status == 1) {
	    				toastr.success('Saved');
	    				location.reload();
	    			}
	    			else
	    			{
	    				toastr.error('Error','!!Failed');
	    			}

	    			$('#btnSave_edit_number').prop('disabled',false).html('Save');	
	    		})
    		}
    	}
    })

    $(document).off('change', '.selectTahun_input_ex[name="Year_Replacement"]').on('change', '.selectTahun_input_ex[name="Year_Replacement"]',function(e) {
    	var y = $('.selectTahun_input_ex[name="Year_Replacement"] option:selected').val();
    	load_formulir_(y,1).then(function(response2){
    		$('#No_Ref_Replacement_ex').empty();
    		for (var i = 0; i < response2.length; i++) {
    			$('#No_Ref_Replacement_ex').append('<option value = "'+response2[i].FormulirCodeGlobal+'">'+response2[i].FormulirCodeGlobal+'</option>')
    		}

    		$('#No_Ref_Replacement_ex').select2({
    		  // allowClear: true
    		});
    	})
    })

    $(document).off('click', '#btnSave_edit_ex').on('click', '#btnSave_edit_ex',function(e) {
    	var ID = $(this).attr('data-id');
    	var DataToken = $(this).attr('token');
    	DataToken = jwt_decode(DataToken);
    	var FormulirCodeOnline =DataToken['FormulirCode'];
    	var No_Ref_Selected = $('.input_selected_ex[name="No_Ref"]').val();
    	var Year_Selected = $('.input_selected_ex[name="No_Ref"]').attr('years');
    	var change_set_ta = 0;
    	var Year_Replacement = $('.selectTahun_input_ex[name="Year_Replacement"] option:selected').val();
    	var No_Ref_Replacement = $('#No_Ref_Replacement_ex option:selected').val();
    	if (confirm('Are you sure ?') ) {
    		if (Year_Selected != Year_Replacement) {
    			if (confirm('Apakah Perubahan ini mengikuti Set TA')) {
    				change_set_ta = 1;
    			}
    		}
    		if (No_Ref_Selected != null && No_Ref_Selected != 'null') {
	    		var url = base_url_js+"it/admission/submit-change-kode-formulir-online";
				var data = {
					action : 'exchangeNumberFormulir',
					No_Ref_Selected : No_Ref_Selected,
					No_Ref_Replacement : No_Ref_Replacement,
					change_set_ta : change_set_ta,
					FormulirCodeOnline : FormulirCodeOnline,
					Year_Replacement : Year_Replacement,
					RegisterID : ID,
				};
	    		var token = jwt_encode(data,"UAP)(*");
	    		loading_button('#btnSave_edit_ex');
	    		$.post(url,{ token:token },function (resultJson) {
	    			var response = jQuery.parseJSON(resultJson);
	    			if (response.Status == 1) {
	    				toastr.success('Saved');
	    				location.reload();
	    			}
	    			else
	    			{
	    				toastr.error('Error','!!Failed');
	    			}

	    			$('#btnSave_edit_ex').prop('disabled',false).html('Save');	
	    		})
    		}
    	}
    })
    
    
</script>
