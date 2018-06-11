<div class="row" style="margin-top: 30px;">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Master Sales Koordinator</h4>
				<div class="toolbar no-padding">
				    <div class="btn-group">
				      <span data-smt="" class="btn btn-xs btn-add-event">
				        <i class="icon-plus"></i> Add Sales Koordinator
				       </span>
				    </div>
				</div>
			</div>
			<div class="widget-content">
				<div class = "row">	
					<div class="col-xs-2" style="">
						Wilayah
						<select class="select2-select-00 col-md-4 full-width-fix" id="selectWilayah">]
							<option value= "%" selected>All</option>
						    <option></option>
						</select>
					</div>
					<div class="col-xs-2" style="">
						Sekolah
						<select class="select2-select-00 col-md-4 full-width-fix" id="selectSchool">
							<option value= "%" selected>All</option>
						    <option></option>
						</select>
					</div>
					<div class="col-xs-2" style="">
						Nama Sales
						<select class="select2-select-00 col-md-4 full-width-fix" id="selectSales">
							<option value= "%" selected>All</option>
						    <option></option>
						</select>
					</div>
					<div class="col-xs-2" style="">
						Status
						<select class="select2-select-00 col-md-4 full-width-fix" id="selectStatus">
						    <option value= "%" selected>All</option>
						    <option value= "0">Tidak Aktif</option>
						    <option value= "1">Aktif</option>
						</select>
					</div>
					<div  class="col-xs-4" align="right" id="pagination_link"></div>	
				</div>
				<br>	
				<div id= "sales_koordinator_table"></div>
			</div>
		</div>
	</div> <!-- /.col-md-6 -->
</div>

<script type="text/javascript">
	$(document).ready(function () {
		loadSelectOptionWilayah_SMA1();
		loadSelectSales1();
		loadData(1);
	});

	$(document).on('change','#selectWilayah',function () {
	    loadSelectSma1();
	    loadData(1);
	});

	$(document).on('change','#selectSchool',function () {
	    loadData(1);
	});

	$(document).on('change','#selectSales',function () {
	    loadData(1);
	});

	$(document).on('change','#selectStatus',function () {
	    loadData(1);
	});

	function loadSelectOptionWilayah_SMA1()
	{
	    $("#selectWilayah").empty();
	    var url = base_url_js+'api/__getWilayahURLJson';
	    $.get(url,function (data_json) {
	    	$('#selectWilayah').append('<option value="'+'%'+'" '+'selected'+'>'+'All'+'</option>');
	        for(var i=0;i<data_json.length;i++){
	            // var selected = (i==0) ? 'selected' : '';
	            //$('#selectWilayah').append('<option value="'+data_json['data'][i].kode_wilayah+'" '+selected+'>'+data_json['data'][i].nama+'</option>');
	            $('#selectWilayah').append('<option value="'+data_json[i].RegionID+'" '+''+'>'+data_json[i].RegionName+'</option>');
	        }
	        $('#selectWilayah').select2({
	           allowClear: true
	        });

	    }).done(function () {
	          loadSelectSma1();
	    });

	}

	function loadSelectSma1()
	{
	    var selectWilayah = $('#selectWilayah').find(':selected').val();
	    var url = base_url_js+"api/__getSMAWilayah";
	    var data = {
	              wilayah : selectWilayah
	          };
	    var token = jwt_encode(data,"UAP)(*");
	    $('#selectSchool').empty()
	    $.post(url,{token:token},function (data_json) {
	          $('#selectSchool').append('<option value="'+'%'+'" '+'selected'+'>'+'All'+'</option>');
	          for(var i=0;i<data_json.length;i++){
	              // var selected = (i==0) ? 'selected' : '';
	              //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
	              $('#selectSchool').append('<option value="'+data_json[i].ID+'" '+''+'>'+data_json[i].SchoolName+'</option>');
	          }
	          $('#selectSchool').select2({
	             //allowClear: true
	          });
	    })
	}

	function loadSelectSales1()
	{
	    var divisiCode = 10;
	    var position = 13;
	    var encdivisiCode = jwt_encode(divisiCode,"UAP)(*");
	    var encposition = jwt_encode(position,"UAP)(*");
	    var url = base_url_js+"api/__getEmployees/"+encdivisiCode+"/"+encposition;
	    $('#selectSales').empty()
	    $.post(url,function (data_json) {
	    	  $('#selectSales').append('<option value="'+'%'+'" '+'selected'+'>'+'All'+'</option>');
	          for(var i=0;i<data_json.length;i++){
	              // var selected = (i==0) ? 'selected' : '';
	              //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
	              $('#selectSales').append('<option value="'+data_json[i].NIP+'" '+''+'>'+data_json[i].Name+'</option>');
	          }
	          $('#selectSales').select2({
	             //allowClear: true
	          });
	    })
	}

	$(document).on('click','.btn-add-event', function () {
	   modal_generate('add','Add Sales Koordinator');
	});

	$(document).on('click','.btn-edit', function () {
	  var ID = $(this).attr('data-smt');
	   modal_generate('edit','Edit Sales Koordinator',ID);
	});

	function modal_generate(action,title,ID='') {
	    var url = base_url_js+"admission/master-registration/modalform_sales_koordinator";
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
	    })
	}

	$(document).on('click','#ModalbtnSaveForm', function () {
       loading_button('#ModalbtnSaveForm');
       var url = base_url_js+'admission/master-registration/modalform_sales_koordinator/save';
       var selectWilayah = $("#selectWilayahModal").val();
       var selectSekolah = $("#selectSekolahModal").val();
       var selectSales = $("#selectSalesModal").val();
       var action = $(this).attr('action');
       var id = $("#ModalbtnSaveForm").attr('kodeuniq');
       var data = {
       			   selectWilayah : selectWilayah,
                   selectSekolah : selectSekolah,
                   selectSales : selectSales,
                   Action : action,
                   CDID : id
                   };
       var token = jwt_encode(data,"UAP)(*");
       if (validation2(data)) {
           $.post(url,{token:token},function (data_json) {
               // jsonData = data_json;
               // var obj = JSON.parse(data_json); 
               // console.log(obj);

           }).done(function() {
             loadData(1);
           }).fail(function() {
             toastr.error('The Database connection error, please try again', 'Failed!!');
           }).always(function() {
            $('#ModalbtnSaveForm').prop('disabled',false).html('Save');

           });
       }
       else
       {
           $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
       }          
	       
	});

	function validation2(arr)
	{
	  var toatString = "";
	  var result = "";
	  for(var key in arr) {
	     switch(key)
	     {
	      case  "selectWilayah" :
	      case  "selectSekolah" :
	      case  "selectSales" :
	             result = Validation_required(arr[key],key);
	             if (result['status'] == 0) {
	               toatString += result['messages'] + "<br>";
	             }
	             break;     
	     }

	  }
	  if (toatString != "") {
	    // toastr.error(toatString, 'Failed!!');
	    $("#msgMENU").html(toatString);
	    $("#msgMENU").removeClass("hide");
	    return false;
	  }

	  return true;
	}

	function loadData(page)
	{
		loading_page('#sales_koordinator_table');
		var url = base_url_js+'admission/master-registration/sales_koordinator/pagination/'+page;
		var selectWilayah = $("#selectWilayah").val();
		var selectSchool = $("#selectSchool").val();
		var selectSales = $("#selectSales").val();
		var selectStatus = $("#selectStatus").val();
		var data = {
					selectWilayah : selectWilayah,
					selectSchool : selectSchool,
					selectSales : selectSales,
					selectStatus : selectStatus,					
					};
		var token = jwt_encode(data,"UAP)(*");			
		$.post(url,{token:token},function (data_json) {
		    // jsonData = data_json;
		    var obj = JSON.parse(data_json); 
		    // console.log(obj);
		    setTimeout(function () {
	       	    $("#sales_koordinator_table").html(obj.sales_koordinator_pagination);
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

	$(document).on('click','.btn-Active', function () {
	    var ID = $(this).attr('data-smt');
	    var Active = $(this).attr('data-active');
	     $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Apakah anda yakin untuk melakukan request ini ?? </b> ' +
	         '<button type="button" id="confirmYesActive" class="btn btn-primary" style="margin-right: 5px;" data-smt = "'+ID+'" data-active = "'+Active+'">Yes</button>' +
	         '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
	         '</div>');
	     $('#NotificationModal').modal('show');
	  });
	$(document).on('click','#confirmYesActive',function () {
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
	        var url = base_url_js+'admission/master-registration/modalform_sales_koordinator/save';
	        var aksi = "getactive";
	        var ID = $(this).attr('data-smt');
	        var Active = $(this).attr('data-active');
	        var data = {
	            Action : aksi,
	            CDID : ID,
	            Active:Active,
	        };
	        var token = jwt_encode(data,"UAP)(*");
	        $.post(url,{token:token},function (data_json) {
	            setTimeout(function () {
	               toastr.options.fadeOut = 10000;
	               toastr.success('Data berhasil disimpan', 'Success!');
	               loadData(1);
	               $('#NotificationModal').modal('hide');
	            },2000);
	        });
	  });

	$(document).on('click','.btn-delete', function () {
	    var ID = $(this).attr('data-smt');
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
	        var url = base_url_js+'admission/master-registration/modalform_sales_koordinator/save';
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
	               loadData(1);
	               $('#NotificationModal').modal('hide');
	            },2000);
	        });
	  });

</script>
