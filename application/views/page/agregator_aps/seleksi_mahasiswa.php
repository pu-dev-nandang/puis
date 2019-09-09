<br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<div id="content_data">
	
</div>
<script>
	var passToExcel = [];
    $(document).ready(function () {

        var firstLoad = setInterval(function () {
            var filterProdi = $('#filterProdi').val();

            if(filterProdi!='' && filterProdi!=null){
                loadPage();
                clearInterval(firstLoad);
            }

        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);

    });

    $('#filterProdi').change(function () {
        var filterProdi = $('#filterProdi').val();

        if(filterProdi!='' && filterProdi!=null){
            loadPage();
        }

    });

    function loadPage() {
    	passToExcel = [];
        var filterProdi = $('#filterProdi').val();
        var filterProdiName = $('#filterProdi option:selected').text();
        if(filterProdi!='' && filterProdi!=null){
            $('#viewProdiID').html(filterProdi);
            $('#viewProdiName').html(filterProdiName);

            var htmltable = '<div class = "row"><div class = "col-md-12">'+
            	'<div style="text-align: right"> <b>Download File : </b><button class="btn btn-success btn-circle" id="btndownloaadExcel" title="Dowload Excel"><i class="fa fa-file-excel-o"></i> </button></div>'+
            	'<table class="table table-striped table-bordered" id="tableData">'+
                '<thead>'+
                '<tr style="background: #20485A;color: #FFFFFF;">'+
                    '<th rowspan="2" style="vertical-align : middle;text-align:center;width: 5%;">Tahun Akademik</th>'+
                    '<th rowspan="2" style="vertical-align : middle;text-align:center;width: 7%;">Daya Tampung</th>'+
                    '<th colspan="2">Jumlah Calon Mahasiswa</th>'+
                    '<th colspan="2">Jumlah Mahasiswa Baru</th>'+
                    '<th colspan="2">Jumlah Mahasiswa</th>'+
                '</tr>'+
               '<tr style="background: #20485A;color: #FFFFFF;">'+
                    '<th style="width: 7%;">Pendaftar</th>'+
                    '<th style="width: 7%;">Lulus Seleksi</th>'+
                    '<th style="width: 7%;">Reguler</th>'+
                    '<th style="width: 7%;">Transfer</th>'+
                    '<th style="width: 7%;">Reguler</th>'+
                    '<th style="width: 7%;">Transfer</th>'+
                '</tr>'+
                '</thead>'+
                '<tbody id="listData"></tbody>'+
            '</table></div></div>';
           $('#content_data').html(htmltable);

            var data = {
               action : 'readDataMHSBaruByProdi',
               filterProdi : filterProdi,
               filterProdiName : filterProdiName,
           };
           var token = jwt_encode(data,'UAP)(*');
           var url = base_url_js+'api3/__crudAgregatorTB2';

           $.post(url,{token:token},function (jsonResult) {
           	var arr_total = [0,0,0,0,0,0];
           	for (var i = 0; i < jsonResult.length; i++) {
           		$('#listData').append(
           			'<tr>'+
           				'<td>'+jsonResult[i].Year+'</td>'+
           				'<td>'+jsonResult[i].Capacity+'</td>'+
           				'<td>'+jsonResult[i].Registrant+'</td>'+ // 0
           				'<td>'+jsonResult[i].PassSelection+'</td>'+ // 1
           				'<td>'+jsonResult[i].Regular+'</td>'+ // 2
           				'<td>'+jsonResult[i].Transfer+'</td>'+ // 3
           				'<td>'+jsonResult[i].Regular2+'</td>'+ // 4
           				'<td>'+jsonResult[i].Transfer2+'</td>'+ // 5
           			'</tr>'	
           		);

           		arr_total[0] += parseInt(jsonResult[i].Registrant);
           		arr_total[1] += parseInt(jsonResult[i].PassSelection);
           		arr_total[2] += parseInt(jsonResult[i].Regular);
           		arr_total[3] += parseInt(jsonResult[i].Transfer);
           		arr_total[4] += parseInt(jsonResult[i].Regular2);
           		arr_total[5] += parseInt(jsonResult[i].Transfer2);
           	}

           	passToExcel['body'] = jsonResult;
           	passToExcel['footer'] = arr_total;

           	// make footer
           	$('#tableData').append(
           			'<tfoot>'+
           				'<tr>'+
           					'<td colspan = "2">'+
           						'Jumlah'+
           					'</td>'+	
           					'<td>'+
           						arr_total[0]+
           					'</td>'+
           					'<td>'+
           						arr_total[1]+
           					'</td>'+
           					'<td>'+
           						arr_total[2]+
           					'</td>'+
           					'<td>'+
           						arr_total[3]+
           					'</td>'+
           					'<td>'+
           						arr_total[4]+
           					'</td>'+
           					'<td>'+
           						arr_total[5]+
           					'</td>'+
           				'</tr>'+
           			'</tfoot>'		
           		)

           })

        }
    }

    $(document).off('click', '#btndownloaadExcel').on('click', '#btndownloaadExcel',function(e) {
    	if (passToExcel["body"] != undefined) {
    		var t = passToExcel['body'];
    		var dt = passToExcel['footer'];
    		if (t.length > 0) {
    			console.log(passToExcel);
    			var url = base_url_js+'agregator/excel-seleksi-mahasiswa-Prodi';
    			data = {
    			  body : t,
    			  footer : dt,
    			}
    			var token = jwt_encode(data,"UAP)(*");
    			FormSubmitAuto(url, 'POST', [
    			    { name: 'token', value: token },
    			]);
    		}
    	}
    	
    })

</script>