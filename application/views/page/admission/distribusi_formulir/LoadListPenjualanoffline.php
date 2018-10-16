<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
	        <div class="panel-heading clearfix">
	            <h4 class="panel-title pull-left" style="padding-top: 7.5px;">List Penjualan Formulir</h4>
	        </div>
	        <div class="panel-body">
	           <div class = "row" style="margin-left: 0px;margin-right: 0px">
	           		<div class="col-xs-6 col-md-offset-3">
	           			<div class="thumbnail" style="height: 100px">
	           				<div class="form-group">
	           					<div class="row">
	           						<div class="col-xs-4 col-md-offset-2">
	           						      <label>Tahun</label>
	           						      <select class="select2-select-00 full-width-fix" id="selectTahun">
	           						          <option></option>
	           						      </select>
	           						</div>
	           						<div class="col-xs-4" style="">
	           							  <label>Status Jual</label>
	           						      <select class="select2-select-00 full-width-fix" id="selectStatusJual">
	           						          <option value= "%">All</option>
	           						          <option value= "1" selected>SoldOut</option>
	           						          <option value= "0">In</option>
	           						      </select>
	           						</div>
	           					</div>
	           				</div>
	           			</div>   
	           		</div>
	           </div>
	           <div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 10px" id = "pageSubContent">

	           </div>          
	        </div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		LoadFirst();

		$('#selectStatusJual').change(function(){
			LoadTablesServerSide();
		})

		$('#selectTahun').change(function(){
			LoadTablesServerSide();
		})
	}); // exit document Function


	function LoadFirst()
	{
		loadTahun();
		LoadTablesServerSide();
	}

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

        $('#selectStatusJual').select2({
          // allowClear: true
        });
    }

    function LoadTablesServerSide()
    {
    	$("#pageSubContent").empty();
    	var div = '<div class="col-md-12">'+
					'<div class="table-responsive" id = "DivTable">'+
					'</div>'+
				'</div>';
		$("#pageSubContent").html(div);
		var table = '<table class="table table-bordered datatable2" id = "tableData4">'+
		            '<thead>'+
		            '<tr style="background: #333;color: #fff;">'+
		                '<th>No</th>'+
		                '<th>Code</th>'+
		                '<th>Ref</th>'+
		                '<th>Kwitansi</th>'+
		                '<th>Prodi</th>'+
		                '<th>Activated</th>'+
		                '<th>Status</th>'+
		                '<th>Sales</th>'+
		                '<th>Harga</th>'+
		                '<th>Tanggal</th>'+
		                '<th>Pembeli</th>'+
		                '<th>Iklan</th>'+
		                '<th>Action</th>'+
		            '</tr>'+
		            '</thead>'+
		            '<tbody id="dataRow"></tbody>'+
		        '</table>';
		$("#DivTable").html(table);

		$.fn.dataTable.ext.errMode = 'throw';
		//alert('hsdjad');
		$.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
		{
		    return {
		        "iStart": oSettings._iDisplayStart,
		        "iEnd": oSettings.fnDisplayEnd(),
		        "iLength": oSettings._iDisplayLength,
		        "iTotal": oSettings.fnRecordsTotal(),
		        "iFilteredTotal": oSettings.fnRecordsDisplay(),
		        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
		        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
		    };
		};

		var table = $('#tableData4').DataTable( {
		    "processing": true,
		    "destroy": true,
		    "serverSide": true,
		    "iDisplayLength" : 5,
		    "ordering" : false,
		    "ajax":{
		        url : base_url_js+"admission/distribusi-formulir/offline/LoadListPenjualan/serverSide", // json datasource
		        ordering : false,
		        type: "post",  // method  , by default get
		        data : {tahun : $("#selectTahun").val(),StatusJual : $("#selectStatusJual").val() },
		        error: function(){  // error handling
		            $(".employee-grid-error").html("");
		            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
		            $("#employee-grid_processing").css("display","none");
		        }
		    },
		    'createdRow': function( row, data, dataIndex ) {
		          // if(data[6] == 'Lunas')
		          // {
		          //   $(row).attr('style', 'background-color: #8ED6EA; color: black;');
		          // }
		    },
		} );

		$('#tableData4 tbody').on('click', '.btn-delete', function () {
			var ID = $(this).attr('data-smt');
			 $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Apakah anda yakin untuk melakukan request ini ?? </b> ' +
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
			 	       LoadTablesServerSide();
			 	       $('#NotificationModal').modal('hide');
			 	    },2000);
			 	});
			 })
		});

		$('#tableData4 tbody').on('click', '.btn-print', function () {
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

		$('#tableData4 tbody').on('click', '.btn-edit', function () {
			$(".menu li").removeClass('active');
			$('.pageAnchor[page = "InputPenjualan"]').parent().addClass('active');
			LoadInputPenjualan(action = 'edit',ID = $(this).attr('data-smt') );
		});		

    }
</script>