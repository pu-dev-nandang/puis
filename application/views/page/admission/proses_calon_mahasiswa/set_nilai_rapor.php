<style type="text/css">
	
</style>
<div class="row" style="margin-top: 30px;">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i><?php echo $NameMenu ?></h4>
			</div>
			<div class="widget-content">
				<div class = "row">	
					<div class="col-md-3" style="">
						Pilih Prodi
						<select class="select2-select-00 col-md-4 full-width-fix" id="selectPrody">
						    <option></option>
						</select>
					</div>
					<div class="col-md-3" style="">
						Formulir Code
						<input class="form-control" id="FormulirCode" placeholder="All...">
					</div>
					<!-- <div  class="col-md-4" align="right" id="pagination_link"></div>	 -->
					<!-- <div class = "table-responsive" id= "register_document_table"></div> -->
				</div>
				<br>	
				<div class = 'row'>
					<div  class="col-md-12" align="right" id="pagination_link"></div>
				</div>
				<div class = 'row'>
					<div id='loadTableData' class="col-md-12"></div>
				</div>
			</div>
		</div>
	</div> <!-- /.col-md-6 -->
</div>

<script type="text/javascript">
	window.processs=[];
	window.processs1=[];
	window.arr_fin=[];
	$(document).ready(function () {
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
	    loadDataPrody();
	});

	$(document).on('change','#selectPrody', function () {
		loadTableData(1);
	});

	$(document).on("click", ".pagination li a", function(event){
	  event.preventDefault();
	  var page = $(this).data("ci-pagination-page");
	  loadTableData(page)
	  // loadData_register_document(page);
	 });

	function loadDataPrody()
	{
	    var url = base_url_js+"api/__getBaseProdiSelectOption";
	    $('#selectPrody').empty();
	     $('#selectPrody').append('<option value="'+'0'+'" '+''+'>'+'--Select Prodi--'+'</option>');
	    $.post(url,function (data_json) {
	          for(var i=0;i<data_json.length;i++){
	              var selected = (i==0) ? 'selected' : '';
	              //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
	              $('#selectPrody').append('<option value="'+data_json[i].ID+'" '+''+'>'+data_json[i].Name+'</option>');
	          }
	          $('#selectPrody').select2({
	             //allowClear: true
	          });
	    }).done(function () {
	      loadTableData(1);
	      $('#NotificationModal').modal('hide');
	    });
	}

	$(document).on("keyup", "#FormulirCode", function(event){
    	var FormulirCode = $('#FormulirCode').val();
    	var n = FormulirCode.length;
    	// console.log(n);
    	if( this.value.length < 6 && this.value.length != 0 ) return;
    	   /* code to run below */
    	loadTableData(1);
	  
	});

	function loadTableData(page)
	{
		// $.removeCookie('__tawkuuid', { path: '/' });
		loading_page('#loadTableData');
		var url = base_url_js+'admission/proses-calon-mahasiswa/set-nilai-rapor/pagination/'+page;
		var selectPrody = $("#selectPrody").find(':selected').val();
		var FormulirCode = $("#FormulirCode").val();
		var data = {
					selectPrody : selectPrody,
					FormulirCode : FormulirCode,
					};
		var token = jwt_encode(data,"UAP)(*");			
		$.post(url,{token:token},function (data_json) {
		    // jsonData = data_json;
		    var obj = JSON.parse(data_json); 
		    //console.log(obj);
		    setTimeout(function () {
	       	    $("#loadTableData").html(obj.loadtable);
	            $("#pagination_link").html(obj.pagination_link);
		    },500);
		}).done(function() {
	      
	    }).fail(function() {
	      toastr.error('The Database connection error, please try again', 'Failed!!');;
	    }).always(function() {
	      // $('#btn-dwnformulir').prop('disabled',false).html('Formulir');
	    });
	}

	function CheckNilaiFinance()
	{
		var c = true;
		var bool = [];
		$('.PilihJurusan').each(function(){
			if ($(this).val() == 0) {
				bool.push(0);
			}
			else
			{
				bool.push(1);
			}
		})

		if ($('.inputmt_pelajaran').length) {
			bool.push(0);
		}

		$(".NilaiFin").each(function(){
			var v = $(this).val();
			if (v == 0) {
				bool.push(0);
			} else {
				bool.push(1);
			}
		})

		for (var i = 0; i < bool.length; i++) {
			if (bool[i] == 0) {
				c = false;
				break;
			}
		}

		return c;
	}

	function AverageNilaiFinance()
	{
		arr_fin =[];
		$(".NilaiFin").each(function(){
			var trrow = $(this).closest('tr');
			var ID_register_formulir = trrow.attr('register_formulir');
			var NmMtPel = $(this).attr('mataujian');
			var Value = $(this).val();

			var temp = {
				ID_register_formulir : ID_register_formulir,
				NmMtPel : NmMtPel,
				Value : Value,
			};

			arr_fin.push(temp);
		})

		for (var i = 0; i < arr_fin.length; i++) {
			var ID_register_formulir = arr_fin[i].ID_register_formulir;
			var tot = 0;
			var bg = 1;
			tot = parseInt(tot) + parseInt(arr_fin[i].Value)
			// count average
				for (var j = i+1; j < arr_fin.length; j++) {
					var ID_register_formulir2 = arr_fin[j].ID_register_formulir;
					if (ID_register_formulir == ID_register_formulir2) {
						tot = parseInt(tot) + parseInt(arr_fin[i].Value);
						bg++;
					} else {
						i = j - 1;
						break;
					}

					i = j;
				}

			var avg =0;
			avg = tot / bg;
			avg = parseFloat(avg).toFixed(2);
			var s = $('tr[register_formulir="'+ID_register_formulir+'"]').find('td:eq(9)');
			s.html('<div class = "form-group"><label>Average</label><br>'+avg+'</div>');
		}
	}

	function prosesData()
	{
		// check inputan Nilai finance ada atau tidak
		var bool = CheckNilaiFinance();
		if (bool) {
				// hitung average nilai ke finance
				AverageNilaiFinance();


				processs = [];
				processs1 = [];
				var no = 0;
				var arr = [];
				var arr_pass = [];
					$(".ID_ujian_perprody").each(function(index) {
					    arr[index] = {
					    	value : $(this).val(),
					    	id_mataujian : $(this).attr('id-mataujian'),
					    	id_formulir : $(this).attr('id-formulir'),
					    	bobot :$(this).attr('bobot'),
					    };
					});
				for(var key in arr) {
			       if (arr[key].value != '') {
			       		arr_pass[no] = arr[key]; 
			       		no++;
			       };
			       // console.log(arr[key].value);
				}
				// console.log(processs);

				for(var key in arr_pass) {
					var bobot_nilai = 0;
				   for(var a in arr_pass) {
				   	 if (arr_pass[key].id_formulir == arr_pass[a].id_formulir) {
				   	 	bobot_nilai += parseInt(arr_pass[a].value) * parseInt(arr_pass[a].bobot);
				   	 }
				   }
			       $("#bobot_nilai"+arr_pass[key].id_formulir).val(bobot_nilai);
			       var jml_bobot = $(".jml_bobot").val();
			       var Nilai_Indeks = bobot_nilai / jml_bobot;
			       // console.log(bobot_nilai);
			       var gradeIndeks = '';	
			       for(var b in grade) {
			       	if (Nilai_Indeks >= grade[b].StartRange && Nilai_Indeks <= grade[b].EndRange) {
			       		gradeIndeks = grade[b].Grade;
			       	}
			       }

			       $("#indeks"+arr_pass[key].id_formulir).val(gradeIndeks);

			       var dataPush = {
			       		value : arr_pass[key].value,
			       		id_mataujian : arr_pass[key].id_mataujian,
			       		id_formulir : arr_pass[key].id_formulir,
			       		bobot :arr_pass[key].bobot,
			       		jml_bobot:jml_bobot,
			       		Nilai_Indeks : Nilai_Indeks,
			       		gradeIndeks : gradeIndeks,
			       	}
			       processs.push(dataPush);
				}

				arr = [];
				$(".Rangking").each(function(index) {
				    arr[index] = {
				    	value : $(this).val(),
				    	id_formulir : $(this).attr('id-formulir'),
				    };
				});
				// console.log(arr);

				var arr2 = [];
				$(".FileRapor").each(function(index) {
				    arr2[index] = {
				    	value : $(this).val(),
				    	id_formulir : $(this).attr('id-formulir'),
				    };
				});

				var check = 1;
				for (var i = 0; i < arr.length; i++) {
					for (var j = 0; j < arr2.length; j++) {
						if (arr[i].id_formulir == arr2[j].id_formulir) {
							var dataPush = {
									rangking : arr[i].value,
									id_doc : arr2[i].value,
									id_formulir : arr[i].id_formulir,
							}
							processs1.push(dataPush);
						}
						
					}
				}

				if (check == 1) {
					$("#btn-Save").removeClass("hide");
				}
				else
				{
					$("#btn-Save").addClass("hide");
					toastr.error('File Rangking tidak boleh kosong jika calon memiliki rangking besar dari 0', 'Failed!!');
				}
		}
		else
		{
			toastr.error('Mohon Check inputan Nilai ke Finance','!!!Failed');
		}
	}

	$(document).on('change','.FileRapor', function () {
		var id_formulir = $(this).attr('id-formulir');
		var file__ = $(this).find(':selected').text();
		$("#show"+id_formulir).attr('filee',file__);
	});

	$(document).on('click','.show_a_href', function () {
		var file__  = $(this).attr('filee');
		var aaa = file__.split(",");
		if (aaa.length > 0) {
			var emaiil = $(this).attr('Email');
			for (var i = 0; i < aaa.length; i++) {
				window.open('<?php echo $url_registration ?>'+'document/'+emaiil+'/'+aaa[i],'_blank');
			}
			
		}
		else
		{
			window.open('<?php echo $url_registration ?>'+'document/'+emaiil+'/'+file__,'_blank');
		}
		
	});
</script>


