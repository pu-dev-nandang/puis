<legend>Pembayaran Formulir Verify By Sales & Admission</legend>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i>List Penjualan Formulir Offline
                </h4>
            </div>
            <div class="widget-content">
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
                                    <option value= "%" selected>All</option>
                                    <option value= "1">SoldOut</option>
                                    <option value= "0">In</option>
                                </select>
                          </div>
                    <div  class="col-xs-4" align="right" id="pagination_link"></div>    
                    <!-- <div class = "table-responsive" id= "register_document_table"></div> -->
                </div>
                <br>    
                <div id= "formulir_offline_table"></div>
            </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
        loadTahun();
	    loadDataFormulirOffline(1);
	});

	function loadDataFormulirOffline(page)
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
        var data = {
                    selectTahun : selectTahun,
                    NomorFormulir : NomorFormulir,
                    NamaStaffAdmisi : NamaStaffAdmisi,
                    selectStatus : selectStatus,
                      selectStatusJual : selectStatusJual,
                      action : 0                   
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
          toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {
          // $('#btn-dwnformulir').prop('disabled',false).html('Formulir');
        });
	}

    $(document).on("click", ".pagination li a", function(event){
      event.preventDefault();
      var page = $(this).data("ci-pagination-page");
      loadDataFormulirOffline(page)
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


    $(document).on('change','#selectStatus', function () {
         loadDataFormulirOffline(1);
    });

    $(document).on('change','#selectStatusJual', function () {
        loadDataFormulirOffline(1);
    });

    $(document).on('change','#selectTahun', function () {
        loadDataFormulirOffline(1);
    });

    $(document).on("keyup", "#NomorFormulir", function(event){
        var nama = $('#NomorFormulir').val();
        var n = nama.length;
        console.log(n);
        if( this.value.length < 3 && this.value.length != 0 ) return;
           /* code to run below */
         loadDataFormulirOffline(1);
      
     });

    $(document).on("keyup", "#NamaStaffAdmisi", function(event){
        var nama = $('#NamaStaffAdmisi').val();
        var n = nama.length;
        console.log(n);
        if( this.value.length < 3 && this.value.length != 0 ) return;
           /* code to run below */
         loadDataFormulirOffline(1);
      
     });

	
</script>
