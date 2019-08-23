<!-- <h1>lama_studi_mahasiswa</h1> -->
 

<style>
    #dataTablesPAM tr th, #dataTablesPAM tr td {
        text-align: center;
    }
</style>

<div class="well">

    <div class="row">

        <div class="col-md-3 form-data-edit" style="border-right: 1px solid #CCCCCC;">

            <div class="form-group">
                <label>Program Pendidikan</label>
                <input class="hide" id="formID">
                <select class="form-control" id="programpendidikan"></select>
            </div>
            <div class="form-group">
                <label>Tahun Lulusan</label>
                <select class="form-control" id="yearstudy"></select>
            </div>
            
            <div class="form-group">
                <label>Jumlah Lulusan</label>
                <input class="form-control" id="jumlah_lulusan">
            </div>

            <div class="form-group">
                <label>Jumlah Rata-rata Masa Studi</label>
                <input class="form-control" id="jumlah_ratastudy">
            </div>

            <div class="form-group" style="text-align: right;">
                <button class="btn btn-primary" id="btnSavePAM">Save</button>
            </div>
        </div>

        <div class="col-md-6 col-md-offset-3">
            <div class="form-group col-md-3">
            		<label>Awal</label>
                	<select class="form-control" id="filterAwaltahun"></select>
            </div>
           	<div class="form-group col-md-3">
            	<label>Akhir</label>
                <select class="form-control" id="filterAkhirtahun"></select>
           	</div>
        </div> 	

        <div class="col-md-9">
            <div id="viewTable"></div>
        </div>

    </div>
</div>

<script>
	
	$(document).on('change','#filterAwaltahun',function () {

		//alert('ABC');
		$("#filterAkhirtahun").empty();
        //var s = $(this).val();
        //$("#filtereditgroup").empty();
        //$("#filtereditmodule").empty();
        loadgetyearstudy();
    });

    function loadgetyearstudy() {

        var filterAwaltahun  = $('#filterAwaltahun option:selected').attr('id');
        
        if(filterAwaltahun !='' && filterAwaltahun !=null){
            var url = base_url_js+'api3/__crudAgregatorTB5';
            var token = jwt_encode({action : 'get_years', filterAwaltahun  : filterAwaltahun },'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {
                $('#filterAkhirtahun').append('<option disabled selected></option>');

                    for(var i=0;i<jsonResult.length;i++){
                        $('#filterAkhirtahun').append('<option id="'+jsonResult[i].ID+'"> '+jsonResult[i].YEAR+' </option>');
                    }
                });
            
            }
        }
</script>

<script>
    $(document).ready(function () {

        loadDataPAM();
        selectprogrampendidikan();
        selectyearstudy();

        $("#formWaktuPenyelenggaraan")
            .datepicker({
                showOtherMonths:true,
                autoSize: true,
                dateFormat: 'dd MM yy',
                // minDate: new Date(moment().year(),moment().month(),moment().date()),
                onSelect : function () {
                    // var data_date = $(this).val().split(' ');
                    // var nextelement = $(this).attr('nextelement');
                    // nextDatePick(data_date,nextelement);
                }
            });
    });

    function selectprogrampendidikan() {

        var url = base_url_js+'api3/__crudAgregatorTB5';
        var token = jwt_encode({action : 'getprogrampendik'},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            $('#programpendidikan').append('<option disabled selected></option>');
                for(var i=0;i<jsonResult.length;i++){
                   $('#programpendidikan').append('<option id="'+jsonResult[i].ID+'"> '+jsonResult[i].NamaProgramPendidikan+' </option>');
                }
            });
      }

     function selectyearstudy() {

        var url = base_url_js+'api3/__crudAgregatorTB5';
        var token = jwt_encode({action : 'yearstudy'},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            $('#filterAwaltahun').append('<option disabled selected></option>');
                for(var i=0;i<jsonResult.length;i++){
                   $('#filterAwaltahun').append('<option id="'+jsonResult[i].Year+'"> '+jsonResult[i].Year+' </option>');
                }
            });
      }

     function selectyearfilter() {

        var url = base_url_js+'api3/__crudAgregatorTB5';
        var token = jwt_encode({action : 'yearstudy'},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            $('#yearstudy').append('<option disabled selected></option>');
                for(var i=0;i<jsonResult.length;i++){
                   $('#yearstudy').append('<option id="'+jsonResult[i].Year+'"> '+jsonResult[i].Year+' </option>');
                }
            });
      }

      
      $(document).on('click','.btnActionUpdate',function () {

     	var programpendidikan = $(this).attr('programpendidikan');
     	var yearstudy = $(this).attr('yearstudy');
     	var jumlah_lulusan = $(this).attr('jumlah_lulusan');
     	var jumlah_ratastudy = $(this).attr('jumlah_ratastudy');
     	
     	var data = {
                action : 'update_study',
                dataForm : {
                    ID_programpendik : programpendidikan,
                    Year : yearstudy,
                    Jumlah_lulusan : jumlah_lulusan,
                    Jumlah_masa_studi : jumlah_ratastudy
                }
            };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB5';

        $.post(url,{token:token},function (result) {

            	if(result==0 || result=='0') {
                  toastr.error('Maaf, Gagal Simpan Data !','Error');
                  $('#NotificationModal').modal('hide');
                } 
                else {  
                	loadDataPAM();
	                toastr.success('Data saved','Success');

	                $('#programpendidikan').val('');
	                $('#yearstudy').val('');
	                $('#jumlah_lulusan').val('');
	                $('#jumlah_ratastudy').val('');
	                $('#NotificationModal').modal('hide');
                }

                setTimeout(function (args) {
                    $('#btnSavePAM').html('Save').prop('disabled',false);
                },500);
         });
     });


    $('#btnSavePAM').click(function () {

        var formID = $('#formID').val();
        var programpendidikan = $('#programpendidikan option:selected').attr('id');
        var yearstudy = $('#yearstudy option:selected').attr('id');
        var jumlah_lulusan = $('#jumlah_lulusan').val();
        var jumlah_ratastudy = $('#jumlah_ratastudy').val();

        if(programpendidikan!='' && programpendidikan!=null &&
            yearstudy!='' && yearstudy!=null &&
        	jumlah_lulusan!='' && jumlah_lulusan!=null &&
        	jumlah_ratastudy!='' && jumlah_ratastudy!=null){

            // loading_buttonSm('#btnSavePAM');

            var data = {
                action : 'updateLamaStudy',
                ID : (formID!='' && formID!=null) ? formID : '',
                dataForm : {
                    ID_programpendik : programpendidikan,
                    Year : yearstudy,
                    Jumlah_lulusan : jumlah_lulusan,
                    Jumlah_masa_studi : jumlah_ratastudy
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB5';

            $.post(url,{token:token},function (result) {

            	if(result==0 || result=='0'){
                  //toastr.error('Maaf, Data Sudah Ada !','Error');
                  $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Data sudah ada! Apakah Data Mau di Update ? </b><hr/> ' +
		            '<button type="button" class="btn btn-primary btnActionUpdate" style="margin-right: 5px;" programpendidikan="'+programpendidikan+'" yearstudy="'+yearstudy+'" jumlah_lulusan="'+jumlah_lulusan+'" jumlah_ratastudy="'+jumlah_ratastudy+'">Yes</button>' +
		            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
		            '</div>');
		          $('#NotificationModal').modal('show');

                } 
                else {  
                	loadDataPAM();
	                toastr.success('Data saved','Success');

	                $('#programpendidikan').val('');
	                $('#yearstudy').val('');
	                $('#jumlah_lulusan').val('');
	                $('#jumlah_ratastudy').val('');
                }

                setTimeout(function (args) {
                    $('#btnSavePAM').html('Save').prop('disabled',false);
                },500);
            });

        } else {
            toastr.error('All form required','Error');
        }

    });
    
    function loadDataPAM() {
    	
        var thisYear = (new Date()).getFullYear();
		var startTahun = parseInt(thisYear) - parseInt(3);
		var selisih =  parseInt(thisYear) - parseInt(startTahun);

		var arr_years =[];
		for (var i = 0; i < 3; i++) {
			var y = parseInt(thisYear) - parseInt(i);
			arr_years.push(y); 
		}

		var thYear = '';
		for (var i = 0; i < arr_years.length; i++) {
			thYear += '<th>'+arr_years[i]+'</th>';
		}
		// console.log(arr_years);
        $('#viewTable').html(' <table class="table" id="dataTablesPAM">' +
            '                <thead>' +
			'                <tr>    ' +
			'                    <th colspan="2" style="border-right: 1px solid #ccc;"></th> ' +
			'                    <th colspan="3" style="border-right: 1px solid #ccc;">Jumlah Lulusan pada</th> ' +
			'                    <th colspan="3" style="border-right: 1px solid #ccc;">Rata-rata Masa Studi Lulusan pada</th>  ' +
			'                    <th style="border-right: 1px solid #ccc;"></th>  ' +
			'                </tr>  ' +
            '                <tr>' +
            '                    <th style="width: 1%;">No</th>' +
            '                    <th>Program Pendidikan </th>' +
           						thYear+	
            					thYear+
            //'                    <th style="width: 10%;"><i class="fa fa-cog"></i></th>' +
            '                </tr>' +
            '                </thead>' +
            '                <tbody id="listData"></tbody>' +
            '            </table>');

        var data = {
            action : 'viewLamaStudy',
            Type : '1'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB5';

        $.post(url,{token:token},function (jsonResult) {
        	console.log(jsonResult); 
            if(jsonResult.length > 0){

            		for (var i = 0; i < jsonResult.length; i++) {
            			var html_Jumlah_lulusan = '';
            			var html_Jumlah_masa_studi = ''; 
            			var v = jsonResult[i];
            			var v_ID_programpendik = v.ID_programpendik;

					    var btn = '<div class="btn-group">' +
	                        '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
	                        '    <i class="fa fa-edit"></i> <span class="caret"></span>' +
	                        '  </button>' +
	                        '  <ul class="dropdown-menu">' +
	                        '    <li><a href="javascript:void(0);" data-id="'+v_ID_programpendik+'" class="btnEditMAP">Edit</a></li>' +
	                        '    <li role="separator" class="divider"></li>' +
	                        '    <li><a href="javascript:void(0);" data-id="'+v_ID_programpendik+'" class="btnRemoveMAP">Remove</a></li>' +
	                        '  </ul>' +
	                        '</div>' +
	                        '<textarea id="viewData_'+v_ID_programpendik+'" class="hide">'+JSON.stringify(v)+'</textarea>';

	                    for (var l = 0; l < arr_years.length; l++) {
	                    	html_Jumlah_lulusan += '<td>'+v['GraduationYear_'+arr_years[l]]+'</td>';
	                    	//html_Jumlah_masa_studi += '<td>'+v['Jumlah_masa_studi_'+arr_years[l]]+'</td>';
	                    }

					 $('#listData').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(parseInt(i)+1 )+'</td>' +
                        '<td style="text-align: left;">'+v.Description+'</td>' +
                        html_Jumlah_lulusan+
                        //html_Jumlah_masa_studi+
                        //'<td style="border-left: 1px solid #ccc;">'+btn+'</td>' +
                        '</tr>');

            		}
            }

            // $('#dataTablesPAM').dataTable();
        });
    }


    $(document).on('click','.btnEditMAP',function () {

        var ID = $(this).attr('data-id');
        var viewData = $('#viewData_'+ID).val();
        var d = JSON.parse(viewData);


        $('#formID').val(d.ID);
        $('#formKegiatan').val(d.Kegiatan);

        $('#formTingkat').val(d.Tingkat);
        $('#formPrestasi').val(d.Prestasi);

        $('#formWaktuPenyelenggaraan').datepicker('setDate',new Date(d.WaktuPenyelenggaraan));

    });

    $(document).on('click','.btnRemoveMAP',function () {

        if(confirm('Hapus data?')){
            var ID = $(this).attr('data-id');
            var data = {
                action : 'removePAM',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB5';

            $.post(url,{token:token},function (jsonResult) {

                loadDataPAM();
                toastr.success('Data removed','Success');

            });

        }
    });

</script>