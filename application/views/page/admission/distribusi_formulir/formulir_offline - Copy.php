<!-- <link href="<?php echo base_url('assets/custom/jquery-ui.css'); ?>" rel="stylesheet" type="text/css" /> -->
<!-- <script type="text/javascript" src="<?php echo base_url();?>assets/datepicker/bootstrap-datepicker.js"></script>
<link href="<?php echo base_url();?>assets/datepicker/datepicker.css" rel="stylesheet" type="text/css"/> -->
<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<style type="text/css">
	.form-horizontal .control-label {
	    text-align: left;
	}
	.panel-primary>.panel-heading {
	    color: #2b1212;
	    background-color: #f9f9f9;
	    border-color: #bbce3b;
	}
</style>
<div class="row" style="margin-top: 30px;">      
      <div class="col-md-12">
            <div class="widget box">
                  <div class="widget-header">
                        <h4><i class="icon-reorder"></i>Penjualan Formulir Offline</h4>
                  </div>
                  <div class="widget-content">
                        <div class="panel with-nav-tabs panel-primary">
                            <div class="panel-heading">
                              <ul class="nav nav-tabs">
                                  <li class="active">
                                    <a href="#tab1primary" data-toggle="tab">Input Penjualan</a>
                                  </li>
                                  <li><a href="#tab2primary" data-toggle="tab">List Penjualan</a></li>
                              </ul>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <!-- tab 1-->
                                    <div class="tab-pane fade in active btn-add" id="tab1primary">
                                          <!-- <div class="panel panel-default"> -->
                                                <div class="panel panel-primary">
                                                  <div class="panel-heading clearfix">
                                                      <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Data Formulir</h4>
                                                  </div>
                                                  <div class="panel-body">
                                                      <div class = "row">     
                                                            <div class="col-xs-2" style="">
                                                                  Formulir Code
                                                                  <select class="select2-select-00 col-md-4 full-width-fix" id="selectFormulirCode">
                                                                      <option></option>
                                                                  </select>
                                                            </div>
                                                            <div class="col-xs-3" style="">
                                                                  Program Study 1
                                                                  <select class="select2-select-00 col-md-4 full-width-fix" id="selectProgramStudy">
                                                                      <option></option>
                                                                  </select>
                                                            </div>
                                                            <div class="col-xs-3" style="">
                                                                  Program Study 2
                                                                  <select class="select2-select-00 col-md-4 full-width-fix" id="selectProgramStudy2">
                                                                      <option></option>
                                                                  </select>
                                                            </div>
                                                            <div class="col-xs-2" style="">
                                                                  Tanggal
                                                                  <input type="text" name="tanggal" id= "tanggal" data-date-format="yyyy-mm-dd" placeholder="Date..." class="form-control">
                                                            </div>
                                                            <div class="col-xs-2" style="">
                                                                  No Ref
                                                                  <input type="text" name="No_Ref" id= "No_Ref" placeholder="Input No Ref..." class="form-control">
                                                            </div>
                                                      </div>
                                                      <br>
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
                                                                  <button class="btn btn-inverse btn-notification" id="btn-proses" action = "add" kode-unique = '1'>Save</button>
                                                            </div>
                                                      </div>
                                                  </div>
                                                </div>
                                                <!-- </div> -->
                                    </div>
                                    <!-- tab 2-->
                                    <div class="tab-pane fade btn-read" id="tab2primary">
                                          <div class = "row">     
                                                <div class="col-xs-2" style="">
                                                      Tahun
                                                      <select class="select2-select-00 col-md-4 full-width-fix" id="selectTahun">
                                                          <option></option>
                                                      </select>
                                                </div>
                                                <div class="col-xs-2" style="">
                                                      Nomor Formulir
                                                      <input class="form-control" id="NomorFormulir" placeholder="All..." "="">
                                                </div>
                                                <div class="col-xs-2" style="">
                                                      Nama Pendistribusi Formulir
                                                      <input class="form-control" id="NamaStaffAdmisi" placeholder="All..." "="">
                                                </div>
                                                <div class="col-xs-2" style="">
                                                      Status Activated by Candidate
                                                      <select class="select2-select-00 col-md-4 full-width-fix" id="selectStatus">
                                                          <option value= "%" selected>All</option>
                                                          <option value= "0">No</option>
                                                          <option value= "1">Yes</option>
                                                      </select>
                                                </div>
                                                <div class="col-xs-2" style="">
                                                      Status Jual
                                                      <select class="select2-select-00 col-md-4 full-width-fix" id="selectStatusJual">
                                                          <option value= "%">All</option>
                                                          <option value= "1" selected>SoldOut</option>
                                                          <option value= "0">In</option>
                                                      </select>
                                                </div>
                                                <div class="col-xs-2" style="">
                                                      Nomor Formulir Ref
                                                      <input class="form-control" id="NomorFormulirRef" placeholder="All..." "="">
                                                </div>
                                          </div>
                                          <div class="row">
                                                <div  class="col-xs-12" align="right" id="pagination_link"></div> 
                                          </div>
                                          <div class="row" style="margin-top: 10px;margin-left: 0px;margin-right: 0px">
                                                <div id= "formulir_offline_table"></div>  
                                          </div> 
                                             
                                                <!-- <div class = "table-responsive" id= "register_document_table"></div> -->
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
		var url = base_url_js+'api/__getFormulirOfflineAvailable';
            $.get(url,function (data_json) {
                if(data_json.length > 0)
                {
                  for(var i=0;i<data_json.length;i++){
                      var selected = (i==0) ? 'selected' : '';
                      $('#selectFormulirCode').append('<option value="'+data_json[i].FormulirCode+'" '+''+'>'+data_json[i].FormulirCode+'</option>');
                  }
                  $('#selectFormulirCode').select2({
                     allowClear: true
                  });
                }
                else
                {
                  toastr.error('Formulir Code Offline belum ada yang di print...<br>Silahkan di print dahulu.', 'Failed!!');
                }
                
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
	    	            })
		    	        break;
		    	    default:
		    	        // text = "I have never heard of that fruit...";
		    	}
		});
	}

	$(document).ready(function () {
		loadTahun();
	    loadData(1);
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

	});

	$(document).on('change','#selectEvent', function () {
		var option = $('option:selected', this).attr('price');
    	$('#priceFormulir').val(option);
    	$('#priceFormulir').maskMoney('mask', '9894');
    	// $("#btn-proses").attr('action','edit'); 
    });

    $(document).on('click','#btn-proses', function () {
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
    	            toastr.options.fadeOut = 10000;
    	            toastr.success('Data berhasil disimpan', 'Success!');
    	            $('#btn-proses').prop('disabled',false).html('Save');
    	            loadData(1);
                  $('a[href="#tab2primary"]').tab('show');
                  clearData();
    	         },2000);
    	     });
    	 }
    	 else
    	 {
    	    $('#btn-proses').prop('disabled',false).html('Save');
    	 }
    });

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

	$(document).on('change','#selectStatus', function () {
    	 loadData(1);
      });

      $(document).on('change','#selectStatusJual', function () {
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

      $(document).on("keyup", "#NomorFormulirRef", function(event){
            var nama = $('#NomorFormulirRef').val();
            var n = nama.length;
            console.log(n);
            if( this.value.length < 3 && this.value.length != 0 ) return;
               /* code to run below */
             loadData(1);
        
      });

    $(document).on("keyup", "#NamaStaffAdmisi", function(event){
    	var nama = $('#NamaStaffAdmisi').val();
    	var n = nama.length;
    	console.log(n);
    	if( this.value.length < 3 && this.value.length != 0 ) return;
    	   /* code to run below */
    	 loadData(1);
	  
	 });

	function loadData(page)
	{
		loading_page('#formulir_offline_table');
		var url = base_url_js+'admission/distribusi-formulir/formulir-offline/pagination/'+page;
            var selectTahun = $("#selectTahun").find(':selected').val();
		var selectStatusJual = $("#selectStatusJual").find(':selected').val();
		var NomorFormulir = $("#NomorFormulir").val();
		if (NomorFormulir == '') {NomorFormulir = '%'};
		var NamaStaffAdmisi = $("#NamaStaffAdmisi").val();
		if (NamaStaffAdmisi == '') {NamaStaffAdmisi = '%'};
		var selectStatus = $("#selectStatus").find(':selected').val();
            var NomorFormulirRef = $("#NomorFormulirRef").val();
            if (NomorFormulirRef == '') {NomorFormulirRef = '%'};

		var data = {
					selectTahun : selectTahun,
					NomorFormulir : NomorFormulir,
					NamaStaffAdmisi : NamaStaffAdmisi,
					selectStatus : selectStatus,
                              selectStatusJual : selectStatusJual,
                              NomorFormulirRef : NomorFormulirRef,					
					};
		var token = jwt_encode(data,"UAP)(*");			
		$.post(url,{token:token},function (data_json) {
		    // jsonData = data_json;
		    var obj = JSON.parse(data_json); 
		    // console.log(obj);
		    setTimeout(function () {
	       	    $("#formulir_offline_table").html(obj.tabel_formulir_offline);
	            $("#pagination_link").html(obj.pagination_link);
		    },500);
		}).done(function() {
	      
	    }).fail(function() {
	      //toastr.error('The Database connection error, please try again', 'Failed!!');
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
      var thisYear = (new Date()).getFullYear();
      var startTahun = parseInt(thisYear);
       var selisih = (2018 < parseInt(thisYear)) ? parseInt(1) + (parseInt(thisYear) - parseInt(2018)) : 1;
       for (var i = 0; i <= selisih; i++) {
            var selected = (i==1) ? 'selected' : '';
            $('#selectTahun').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
        }

       $('#selectTahun').select2({
         // allowClear: true
       });

        $('#selectStatus').select2({
          // allowClear: true
        });
    }

    $(document).on('click','.btn-delete', function () {
      var ID = $(this).attr('data-smt');
      // var element = $("#tab1primary a");
      // $(element).fadeIn();
      // $('a[href="#tab1primary"]').tab('show')
      // $('#btn-proses').attr('action','edit');
      // $('a[rel="tab1primary"]').trigger("click");
       $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Apakah anda yakin untuk melakukan request ini ?? </b> ' +
           '<button type="button" id="confirmYesDelete" class="btn btn-primary" style="margin-right: 5px;" data-smt = "'+ID+'">Yes</button>' +
           '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
           '</div>');
       $('#NotificationModal').modal('show');
    });

    $(document).on('click','#confirmYesDelete',function () {
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
          var url = base_url_js+'admission/distribusi-formulir/formulir-offline/save';
          var aksi = "delete";
          var ID = $(this).attr('data-smt');
          var data = {
              Action : aksi,
              CDID : ID,
          };
          var token = jwt_encode(data,"UAP)(*");
          $.post(url,{token:token},function (data_json) {
              setTimeout(function () {
                 toastr.options.fadeOut = 10000;
                 toastr.success('Data berhasil disimpan', 'Success!');
                 loadData(1)
                 $('#NotificationModal').modal('hide');
              },2000);
          });
    });

    $(document).on('click','.btn-print', function () {
      var NoKwitansi = $(this).attr('nokwitansi');
      var url = base_url_js+'admission/export_kwitansi_formuliroffline';
      var NoFormRef = $(this).attr('ref');
      var namalengkap = $(this).attr('namalengkap');
      var hp = $(this).attr('hp');
      var jurusan = $(this).attr('jurusan');
      var pembayaran = $(this).attr('pembayaran');
      var jenis = $(this).attr('jenis');
      var jumlah = $(this).attr('jumlah');
      var date = $(this).attr('date');
      var formulir = $(this).attr('formulir');
      NoFormRef = (NoFormRef != "" || NoFormRef != null) ? NoFormRef : formulir;

      if (NoKwitansi == "" || NoKwitansi == null) {
          $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Please Input Number Form ! </b> <br>' +
              '<input type = "number" class = "form-control" id ="NumForm"  maxlength="4" style="margin: 0px 0px 15px; height: 30px; width: 329px;"><br>'+
              '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
              '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
              '</div>');
          $('#NotificationModal').modal('show');
      } else {
        var NumForm = NoKwitansi;
        data = {
          NoFormRef : NoFormRef ,
          namalengkap : namalengkap ,
          hp : hp ,
          jurusan :  jurusan ,
          pembayaran :  pembayaran,
          jenis : jenis ,
          jumlah : jumlah ,
          date : date,
          NumForm : NumForm,
        }
        var token = jwt_encode(data,"UAP)(*");
        FormSubmitAuto(url, 'POST', [
            { name: 'token', value: token },
        ]);
      }

      $("#NumForm").keypress(function(event)
      {

           if (event.keyCode == 10 || event.keyCode == 13) {
             var NumForm = $('#NumForm').val();
             data = {
               NoFormRef : NoFormRef ,
               namalengkap : namalengkap ,
               hp : hp ,
               jurusan :  jurusan ,
               pembayaran :  pembayaran,
               jenis : jenis ,
               jumlah : jumlah ,
               date : date,
               NumForm : NumForm,
             }
             var token = jwt_encode(data,"UAP)(*");
             FormSubmitAuto(url, 'POST', [
                 { name: 'token', value: token },
             ]); 
           }
      }); // exit enter

      $("#confirmYes").click(function(){
          var NumForm = $('#NumForm').val();
          data = {
            NoFormRef : NoFormRef ,
            namalengkap : namalengkap ,
            hp : hp ,
            jurusan :  jurusan ,
            pembayaran :  pembayaran,
            jenis : jenis ,
            jumlah : jumlah ,
            date : date,
            NumForm : NumForm,
          }
          var token = jwt_encode(data,"UAP)(*");
          FormSubmitAuto(url, 'POST', [
              { name: 'token', value: token },
          ]);  
      })
      
    });
</script>
