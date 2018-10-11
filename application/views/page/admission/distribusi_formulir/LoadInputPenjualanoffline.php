<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
	        <div class="panel-heading clearfix">
	            <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Input Penjualan Formulir</h4>
	        </div>
	        <div class="panel-body">
	           <div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 10px">
	           		<div class="col-md-12">
	           			<div class="panel panel-primary">
	           			  <div class="panel-heading clearfix">
	           			      <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Data Formulir</h4>
	           			  </div>
	           			  <div class="panel-body">
           			  		<div class="form-group">
           			  			<div class="row" style="margin-left: 0px;margin-right: 0px;margin-top: 10px">
           			  				<div class="col-xs-2">
           			  					  <label>Formulir Code</label>
           			  				      <select class="select2-select-00 col-md-4 full-width-fix" id="selectFormulirCode">
           			  				          <option></option>
           			  				      </select>
           			  				</div>
           			  				<div class="col-xs-2">
           			  					  <label>Program Study 1</label>
           			  				      <select class="select2-select-00 col-md-4 full-width-fix" id="selectProgramStudy">
           			  				          <option></option>
           			  				      </select>
           			  				</div>
           			  				<div class="col-xs-2">
           			  				      <label>Program Study 2</label>
           			  				      <select class="select2-select-00 col-md-4 full-width-fix" id="selectProgramStudy2">
           			  				          <option></option>
           			  				      </select>
           			  				</div>
           			  				<div class="col-xs-2">
           			  				      <label>Tanggal</label>
           			  				      <input type="text" name="tanggal" id= "tanggal" data-date-format="yyyy-mm-dd" placeholder="Date..." class="form-control">
           			  				</div>
           			  				<div class="col-xs-4">
           			  					<div class="col-xs-3">
	           			  					<label>No Ref</label>
	           			  					<?php if ($action == "add"): ?>
	           			  						<div class="row">
	           			  						<label class="checkbox-inline">
							          					<input type="checkbox" class="RefCode" name="RefCode" value="0"> CodeManual
							          			</label>
	           			  					</div>
	           			  					<div class="row" style="margin-top: 5px">
	           			  						<label class="checkbox-inline">
					          					     <input type="checkbox" class="RefCode" name="RefCode" value="1"> CodeAuto
					          					</label>
	           			  					</div>
	           			  					<?php endif ?>
           			  					</div>
           			  					<div class="col-xs-9">
           			  						<div class="row <?php echo ($action == "add") ? "hide" : "" ?>" style="margin-top: 5px" id = "InputRef">
           			  							<input type="text" name="No_Ref" id= "No_Ref"  class="form-control">
           			  						</div>
           			  					</div>
           			  				</div>
           			  			</div>
           			  		</div>
	           			  </div>	
	           			</div>
	           		</div>
	           </div>
	           <div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 10px">
	           		<div class="col-md-12">
	           			<div class="panel panel-primary">
	           			  <div class="panel-heading clearfix">
	           			      <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Biodata Pembeli</h4>
	           			  </div>
	           			  <div class="panel-body">
	           			  	<div class="form-horizontal">
	           			  	      <div class="form-group">
	           			  	          <label class="col-sm-1 control-label">Nama </label>
	           			  	          <div class="col-md-3">
	           			  	            <input type="text" name="Name" id= "Name" placeholder="Input Nama Pembeli..." class="form-control">
	           			  	          </div>
	           			  	          <label class="col-sm-1 control-label">Jenis Kelamin </label>
	           			  	            
	           			  	      <select class="select2-select-00 col-md-3 " id="selectGender">
	           			  	          <option value = "L" selected>Laki - Laki</option>
	           			  	          <option value = "P">Perempuan</option>
	           			  	      </select>
	           			  	      </div>
	           			  	      <div class="form-group">
	           			  	            <label class="col-sm-1 control-label">Hp </label>
	           			  	            <div class="col-md-3">
	           			  	                  <input type="text" name="hp" id= "hp" placeholder="+62811111011" class="form-control">
	           			  	            </div>
	           			  	            <label class="col-sm-1 control-label">Telp Rumah </label>
	           			  	            <div class="col-md-3">
	           			  	                  <input type="text" name="telp_rmh" id= "telp_rmh" placeholder="+62211111011" class="form-control">
	           			  	            </div>            
	           			  	      </div>
	           			  	      <div class="form-group">
	           			  	            <label class="col-sm-1 control-label">Email </label>
	           			  	            <div class="col-md-3">
	           			  	              <input type="text" name="email" id= "email" placeholder="" class="form-control">
	           			  	            </div>
	           			  	      </div>
	           			  	      <div class="form-group">
	           			  	            <label class="col-sm-1 control-label">Sekolah </label>
	           			  	            <div class="col-md-3">
	           			  	              <input type="text" name="autoCompleteSchool" id= "autoCompleteSchool" placeholder="Autocomplete" class="form-control">
	           			  	            </div>
	           			  	      </div>
	           			  	      <div class="form-group">
	           			  	            <label class="col-sm-1 control-label">Sumber Iklan </label>
	           			  	            <div class="col-md-3">
	           			  	              <select class="select2-select-00 col-md-4 full-width-fix" id="selectSourceFrom">
	           			  	               <option></option>
	           			  	            </select>
	           			  	            </div>
	           			  	      </div>            
	           			  	</div>	
	           			  </div>
	           			</div>  
	           		</div>
	           </div>
	           <div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 10px">
	           		<div class="col-md-12">
	           			<div class="panel panel-primary">
	           			  <div class="panel-heading clearfix">
	           			      <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Channel</h4>
	           			  </div>
	           			  <div class="panel-body">
	           			      <div class="form-horizontal">
	           			            <div class="form-group">
	           			                <label class="col-sm-1 control-label">Tipe Channel </label>
	           			                <div class="col-md-3">
	           			                  <table>
	           			                        <tr>
	           			                              <td>
	           			                                    <label class="radio-inline">
	           			                                                      <input type="radio" name="tipeChannel" value = "Admission Office" class = "tipeChannel"> Admission Office
	           			                                                </label>
	           			                              </td>
	           			                        </tr>
	           			                        <tr>
	           			                              <td>
	           			                                    <label class="radio-inline">
	           			                                                      <input type="radio" name="tipeChannel" value = "Event" class = "tipeChannel"> Event
	           			                                                </label>
	           			                              </td>
	           			                              <td style="width: 223px;">
	           			                                      <select class="select2-select-00 col-md-4 full-width-fix" id="selectEvent">
	           			                                 <option></option>
	           			                              </select>
	           			                              </td>
	           			                        </tr>
	           			                        <tr>
	           			                              <td>&nbsp</td>
	           			                              <td>&nbsp</td>
	           			                        </tr>
	           			                        <tr>
	           			                              <td>
	           			                                    <label class="radio-inline">
	           			                                          <input type="radio" name="tipeChannel" value = "School" class = "tipeChannel"> School
	           			                                    </label>
	           			                              </td>
	           			                              <td>
	           			                                    <input type="text" name="autoCompleteSchoolChanel" id= "autoCompleteSchoolChanel" placeholder="Autocomplete" class="form-control">
	           			                              </td>
	           			                        </tr>
	           			                  </table>
	           			                </div>
	           			            </div>
	           			            <div class="form-group">
	           			                  <label class="col-sm-1 control-label">Harga Formulir </label>
	           			                  <div class="col-md-3">
	           			                        <input type="text" name="priceFormulir" id= "priceFormulir" placeholder="" class="form-control">
	           			                  </div>
	           			            </div>
	           			            <div class="form-group">
	           			                  <label class="col-sm-1 control-label">PIC </label>
	           			                  <div class="col-md-3">
	           			                        <select class="select2-select-00 col-md-4 full-width-fix" id="selectPIC">
	           			                                 <option></option>
	           			                        </select>
	           			                  </div>
	           			            </div>
	           			      </div>
	           			  </div>
	           			</div>
	           			<div class ="form-group">
	           			      <div align="right">
	           			            <button class="btn btn-inverse btn-notification" id="btn-proses" action = "<?php echo $action ?>" kode-unique = "<?php echo $CDID ?>">Save</button>
	           			      </div>
	           			</div>
	           		</div>	
	           </div>          
	        </div>
		</div>
	</div>
</div>
<script type="text/javascript">
	window.temp1 = '';
	window.temp2 = '';
		function loadFormulirCode()
		{
			$("#selectFormulirCode").empty();
			var url = base_url_js+'api/__getFormulirOfflineAvailable/0';
			<?php if ($action == 'edit'): ?>
				url = base_url_js+'api/__getFormulirOfflineAvailable/1';
			<?php endif ?>
	            $.get(url,function (data_json) {
	                if(data_json.length > 0)
	                {
	                  for(var i=0;i<data_json.length;i++){
	                      var selected = (i==0) ? 'selected' : '';
	                      $('#selectFormulirCode').append('<option value="'+data_json[i].FormulirCode+'" '+''+'>'+data_json[i].FormulirCode+'</option>');
	                  }
	                }
	                else
	                {
	                  toastr.error('Formulir Code Offline belum ada yang di print...<br>Silahkan di print dahulu.', 'Failed!!');
	                }

	                $('#selectFormulirCode').select2({
	                   allowClear: true
	                });

	                <?php if ($action == 'edit'): ?>
	                   $("#selectFormulirCode option").filter(function() {
	                      //may want to use $.trim in here
	                      return $(this).val() == "<?php echo $get1[0]['FormulirCodeOffline'] ?>"; 
	                    }).prop("selected", true);
	                   $('#selectFormulirCode').select2({
	                      allowClear: true
	                   });
	                <?php endif ?>
	                
	            }).done(function () {
	                  // loadSelectSma1();
	            });
		}

		function loadPIC()
		{
			var url = base_url_js+"admission/distribusi-formulir/formulir-offline/selectPIC";
			var School = temp1;
			// console.log('aaa : ' + School);
			data = {
					School : School,		
			};
			$('#selectPIC').empty();

			var token = jwt_encode(data,"UAP)(*");          
			$.post(url,{token:token},function (data_json) {
			      for(var i=0;i<data_json.length;i++){
			          var selected = (i==0) ? 'selected' : '';
			          // var obj = JSON.parse(data_json);
			          // console.log(obj);
			          $('#selectPIC').append('<option value="'+data_json[i].NIP+'" '+selected+'>'+data_json[i].Name+'</option>');
			      }
			      $('#selectPIC').select2({
			         //allowClear: true
			      });

			      	<?php if ($action == 'edit'): ?>
			      	   $("#selectPIC option").filter(function() {
			      	      //may want to use $.trim in here
			      	      return $(this).val() == "<?php echo $get1[0]['PIC'] ?>"; 
			      	    }).prop("selected", true);
			      	   $('#selectPIC').select2({
			      	      allowClear: true
			      	   });
			      	<?php endif ?>


			}).done(function () {
			  
			});
		}

		function loadProgramStudy()
		{
			var url = base_url_js+"api/__getBaseProdiSelectOption";
	            $('#selectProgramStudy').empty();
			$('#selectProgramStudy2').empty();
	            $('#selectProgramStudy2').append('<option value="'+0+'" '+'selected'+'>'+'--No Choice--'+'</option>');
			$.post(url,function (data_json) {
			      for(var i=0;i<data_json.length;i++){
			          var selected = (i==0) ? 'selected' : '';
			          //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
	                      $('#selectProgramStudy').append('<option value="'+data_json[i].ID+'" '+selected+'>'+data_json[i].Name+'</option>');
			          $('#selectProgramStudy2').append('<option value="'+data_json[i].ID+'" '+''+'>'+data_json[i].Name+'</option>');
			      }
			      $('#selectProgramStudy').select2({
			         //allowClear: true
			      });
	                  $('#selectProgramStudy2').select2({
	                     //allowClear: true
	                  });

	                  <?php if ($action == 'edit'): ?>
	                     $("#selectProgramStudy option").filter(function() {
	                        //may want to use $.trim in here
	                        return $(this).val() == "<?php echo $get1[0]['ID_ProgramStudy'] ?>"; 
	                      }).prop("selected", true);
	                     $('#selectProgramStudy').select2({
	                        allowClear: true
	                     });
	                  <?php endif ?>

	                  <?php if ($action == 'edit'): ?>
	                     $("#selectProgramStudy2 option").filter(function() {
	                        //may want to use $.trim in here
	                        return $(this).val() == "<?php echo $get1[0]['ID_ProgramStudy2'] ?>"; 
	                      }).prop("selected", true);
	                     $('#selectProgramStudy2').select2({
	                        allowClear: true
	                     });
	                  <?php endif ?>
			}).done(function () {
			  
			});
		}

		function autoCompleteSchool(ID)
		{
			ID.autocomplete({
			  minLength: 4,
			  select: function (event, ui) {
			    event.preventDefault();
			    var selectedObj = ui.item;
			    // console.log(selectedObj);
			    // $("#Nama").appendTo(".foo");
			    // ID.val(selectedObj.value); 
			    ID.val(selectedObj.label); 
			    
			    var test = ID.attr('name');
			    if(test == 'autoCompleteSchoolChanel')
			    {
			    	temp2 = '';
			    	temp2 = selectedObj.value;
			    	// console.log(temp2);
			    }
			    else
			    {
			    	temp1 = '';
			    	temp1 =  selectedObj.value;
			    	loadPIC();
			    }
			    // loadSubMenu();
			    console.log(temp1);
			    console.log(temp2);
			  },
			  /*select: function (event,  ui)
			  {

			  },*/
			  source:
			  function(req, add)
			  {
			  	loadingStart();
			    var url = base_url_js+'api/__getAutoCompleteSchool';
			    var School = ID.val();
			    var data = {
			                School : School,
			                };
			    var token = jwt_encode(data,"UAP)(*");          
			    $.post(url,{token:token},function (data_json) {
			        // var obj = JSON.parse(data_json);
			        add(data_json.message); 
			        loadingEnd(1000);
			    })
			  } 
			})
		}

		function loadSumberIklan()
		{
			var url = base_url_js+"api/__getSumberIklan";
			$('#selectSourceFrom').empty()
			$.post(url,function (data_json) {
			      for(var i=0;i<data_json.length;i++){
			          var selected = (i==0) ? 'selected' : '';
			          //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
			          $('#selectSourceFrom').append('<option value="'+data_json[i].ID+'" '+selected+'>'+data_json[i].src_name+'</option>');
			      }
			      $('#selectSourceFrom').select2({
			         //allowClear: true
			      });

			      <?php if ($action == 'edit'): ?>
			         $("#selectSourceFrom option").filter(function() {
			            //may want to use $.trim in here
			            return $(this).val() == "<?php echo $get1[0]['source_from_event_ID'] ?>"; 
			          }).prop("selected", true);
			         $('#selectSourceFrom').select2({
			            allowClear: true
			         });
			      <?php endif ?>
			}).done(function () {
			  
			});
		}

		function DomRadioChannel()
		{
			$('input:radio[name="tipeChannel"]').change(
			    function(){
			    	var valuee = this.value;
			    	switch(valuee) {
			    	    case "Admission Office":
			    	    	$("#selectEvent").addClass("hide");
			    	    	$("#autoCompleteSchoolChanel").addClass("hide");
			    	    	var url = base_url_js+"api/__getPriceFormulirOffline";
			    	        $.post(url,function (data_json) {
			    	            $("#priceFormulir").val(data_json[0].PriceFormulir);
			    	            $('#priceFormulir').maskMoney('mask', '9894');
			    	            <?php if ($action == 'edit'): ?>
			    	            	$("#priceFormulir").val("<?php echo $get1[0]['Price_Form'] ?>");
			    	            	$('#priceFormulir').maskMoney('mask', '9894');
			    	            <?php endif ?>  
			    	        })
			    	        break;
			    	    case "Event":
			    	        var url = base_url_js+"api/__getEvent";
			    	        $("#autoCompleteSchoolChanel").addClass("hide");
			    	        $('#selectEvent').empty();
			    	        $.post(url,function (data_json) {
			    	        	  $("#selectEvent").removeClass("hide");
			    	              for(var i=0;i<data_json.length;i++){
			    	                  var selected = (i==0) ? 'selected' : '';
			    	                  //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
			    	                  $('#selectEvent').append('<option value="'+data_json[i].ID+'" '+selected+' price = "'+data_json[i].evn_price+'">'+data_json[i].evn_name+'</option>');
			    	              }
			    	              $('#selectEvent').select2({
			    	                 //allowClear: true
			    	              });

			    	              <?php if ($action == 'edit'): ?>
			    	              	$("#selectEvent option").filter(function() {
			    	              	   //may want to use $.trim in here
			    	              	   return $(this).val() == "<?php echo $get1[0]['price_event_ID'] ?>"; 
			    	              	 }).prop("selected", true);
			    	              	$('#selectEvent').select2({
			    	              	   allowClear: true
			    	              	});
			    	              <?php endif ?>
			    	        }).done(function () {
			    	          
			    	        });
			    	        break;
			    	    case "School":
			    	        $("#autoCompleteSchoolChanel").removeClass("hide");
			    	        $("#selectEvent").addClass("hide");
			    	        autoCompleteSchool($("#autoCompleteSchoolChanel"));
		    	        	var url = base_url_js+"api/__getPriceFormulirOffline";
		    	            $.post(url,function (data_json) {
		    	                $("#priceFormulir").val(data_json[0].PriceFormulir);
		    	                $('#priceFormulir').maskMoney('mask', '9894');   
		    	                <?php if ($action == 'edit'): ?>
		    	                	$("#priceFormulir").val("<?php echo $get1[0]['Price_Form'] ?>");
		    	                	$('#priceFormulir').maskMoney('mask', '9894');
		    	                <?php endif ?>  
		    	            })
			    	        break;
			    	    default:
			    	        // text = "I have never heard of that fruit...";
			    	}
			});
		}

		$(document).ready(function () {
		      $('#tanggal').prop('readonly',true);
		      $("#tanggal").datepicker({
				    dateFormat: 'yy-mm-dd',

			  });
			$("#selectEvent").addClass("hide");
			$("#autoCompleteSchoolChanel").addClass("hide");
			loadFormulirCode();
			loadProgramStudy();
			autoCompleteSchool($("#autoCompleteSchool"));
			loadSumberIklan();
			DomRadioChannel();
			loadPIC();
			$('#priceFormulir').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});

			FuncEventDom();

			<?php if ($action == 'edit'): ?>
			  $('#tanggal').val("<?php echo $get1[0]['DateSale'] ?>");
			  $("#No_Ref").val("<?php echo $get2[0]['No_Ref'] ?>");
			  $("#Name").val("<?php echo $get1[0]['FullName'] ?>");
			  $("#hp").val("<?php echo $get1[0]['PhoneNumber'] ?>");
			  $("#email").val("<?php echo $get1[0]['Email'] ?>");
			  $("#autoCompleteSchool").val("<?php echo $get3[0]['SchoolName'] ?>");
			  $("#autoCompleteSchoolChanel").val("<?php echo $get4[0]['SchoolName'] ?>");
			  $("#selectGender option").filter(function() {
			     //may want to use $.trim in here
			     return $(this).val() == "<?php echo $get1[0]['Gender'] ?>"; 
			   }).prop("selected", true);
			  $("#telp_rmh").val("<?php echo $get1[0]['HomeNumber'] ?>");

			  $('input:radio[name="tipeChannel"][value ="<?php echo $get1[0]['Channel'] ?>"]').prop("checked", true);
			  $('input:radio[name="tipeChannel"][value ="<?php echo $get1[0]['Channel'] ?>"]').trigger('change');
			  temp1 = "<?php echo $get1[0]['SchoolID'] ?>";
			  temp2 = "<?php echo $get1[0]['SchoolIDChanel'] ?>";
			  $("#priceFormulir").val("<?php echo $get1[0]['Price_Form'] ?>");
			  $('#priceFormulir').maskMoney('mask', '9894');
			<?php endif ?>

		});

		function FuncDisabledInput()
		{
			$("#InputRef").addClass('hide');
		}

		function FuncClickRefCode()
		{
			$(".RefCode").click(function(){
				$('input.RefCode').prop('checked', false);
				$(this).prop('checked',true);
				var valuee = $(this).val();
				$("#InputRef").addClass('hide');
				if (valuee == 0) {
					$("#InputRef").removeClass('hide');
				}
				
			})
		}

		function FuncEventDom()
		{
			FuncClickRefCode();

			$("#selectEvent").change(function(){
				var option = $('option:selected', this).attr('price');
		    	$('#priceFormulir').val(option);
		    	$('#priceFormulir').maskMoney('mask', '9894');
		    	// $("#btn-proses").attr('action','edit'); 
			})

			$('#btn-proses').click(function(){
				var cf = $(".RefCode:checked").val();
				var bool = (cf == '' || cf == null) ? false : true;
				<?php if ($action == 'edit'): ?>
					bool = true;
				<?php endif ?>
				if (!bool) {
					toastr.error('Mohon pilih No Ref','Failed!')
				} 
				else
				{
					loading_button('#btn-proses');
					 var selectFormulirCode = $("#selectFormulirCode").val();
				   var selectProgramStudy = $("#selectProgramStudy").val();
					 var selectProgramStudy2 = $("#selectProgramStudy2").val();
					 var Name = $("#Name").val().trim();
					 var hp = $("#hp").val().trim();
					 var email = $("#email").val().trim();
					 // var autoCompleteSchool = $("#autoCompleteSchool").val();
					 var autoCompleteSchool = temp1;
					 var selectSourceFrom = $("#selectSourceFrom").val();
					 var selectGender = $("#selectGender").val();
					 var telp_rmh = $("#telp_rmh").val().trim();
					 var tipeChannel = $('input[name=tipeChannel]:checked').val(); ;
					 var selectEvent = $("#selectEvent").val();
					 // var autoCompleteSchoolChanel = $("#autoCompleteSchoolChanel").val();
					 var autoCompleteSchoolChanel = temp2;
					 var aksi = $(this).attr('action');
					 var CDID = $(this).attr('kode-unique');
					 var url = base_url_js+'admission/distribusi-formulir/formulir-offline/save';
					 var PIC = $("#selectPIC").val();
					 var priceFormulir = $("#priceFormulir").val();
				   var tanggal = $("#tanggal").val(); 
				   var No_Ref = $("#No_Ref").val();
				   // var output_ok = $('#output_ok').val();
				    priceFormulir = priceFormulir.replace(".", "");
					 var data = {
					     Action : aksi,
					     selectFormulirCode : selectFormulirCode,
					     selectProgramStudy:selectProgramStudy,
				       selectProgramStudy2 : selectProgramStudy2,
					     Name : Name,
					     hp : hp,
					     email : email,
					     autoCompleteSchool : autoCompleteSchool,
					     selectSourceFrom : selectSourceFrom,
					     selectGender : selectGender,
					     telp_rmh : telp_rmh,
					     tipeChannel : tipeChannel,
					     selectEvent : selectEvent,
					     autoCompleteSchoolChanel : autoCompleteSchoolChanel,
					     CDID : CDID,
					     priceFormulir : priceFormulir,
					     PIC : PIC,
				       tanggal : tanggal,
				       No_Ref : No_Ref,
					 };

					 if (validationInput = validation2(data)) {
					     var token = jwt_encode(data,"UAP)(*");
					     $.post(url,{token:token},function (data_json) {
					         setTimeout(function () {
					         	// clearData();
					         	// LoadListPenjualan();
					         	 $('.pageAnchor[page = "ListPenjualan"]').trigger('click');
					            toastr.options.fadeOut = 10000;
					            toastr.success('Data berhasil disimpan', 'Success!');
					            $('#btn-proses').prop('disabled',false).html('Save');
				              // $('a[href="#tab2primary"]').tab('show');
					         },2000);
					     });
					 }
					 else
					 {
					    $('#btn-proses').prop('disabled',false).html('Save');
					 }
				}
				
			});


		}

		function clearData()
		{
		  $('.tipeChannel').prop('checked', false);
		  $("#selectEvent").addClass("hide");
		  $("#autoCompleteSchoolChanel").addClass("hide");
		  loadFormulirCode();
		  loadProgramStudy();
		  $('#Name').val('');
		  $('#hp').val('');
		  $('#email').val('');
		  $('#autoCompleteSchool').val('');
		  temp1 = '';
		  temp2 = ''
		  $('#telp_rmh').val('');
		  $('#priceFormulir').val('');

		}

		function validation2(arr)
		{
		  var toatString = "";
		  var result = "";
		  for(var key in arr) {
		     switch(key)
		     {
		      case "Name" :      
		      case "hp" :      
		      // case "email" :      
		      case "autoCompleteSchool" :      
		      case "selectSourceFrom" :      
		      case "selectGender" :      
		      // case "telp_rmh" :      
		      case "tipeChannel" :      
		      // case "selectEvent" :      
		      // case "autoCompleteSchoolChanel" :
		      case "PIC" :		
		      case "selectProgramStudy" :		
		      case  "selectFormulirCode" :
		      case  "tanggal" :
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
</script>