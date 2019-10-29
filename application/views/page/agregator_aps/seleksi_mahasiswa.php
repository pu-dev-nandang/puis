<h3 align="center">Seleksi Mahasiswa</h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<br/>
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
              var v = jsonResult[i];
           		$('#listData').append(
           			'<tr>'+
           				'<td>'+jsonResult[i].Year+'</td>'+
           				'<td>'+checkValue(v.Capacity)+'</td>'+
                  // '<td>'+jsonResult[i].Registrant+'</td>'+ // 0
           				'<td>'+'<a href = "javascript:void(0);" class = "datadetailPendaftar" data = "'+v.d_Registrant+'">'+checkValue(v.Registrant)+'</a>'+'</td>'+ // 0
           				'<td>'+'<a href = "javascript:void(0);" class = "datadetailPassSelection" data = "'+v.d_PassSelection+'">'+checkValue(v.PassSelection)+'</a>'+'</td>'+ // 1
           				'<td>'+'<a href = "javascript:void(0);" class = "datadetailPassSelection" data = "'+v.d_Regular+'">'+checkValue(v.Regular)+'</a>'+'</td>'+ // 2
           				'<td>'+checkValue(v.Transfer)+'</td>'+ // 3
           				'<td>'+'<a href = "javascript:void(0);" class = "datadetailPassSelection" data = "'+v.d_Regular2+'">'+checkValue(v.Regular2)+'</a>'+'</td>'+ // 4
           				'<td>'+checkValue(v.Transfer2)+'</td>'+ // 5
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

    $(document).off('click', '.datadetailPendaftar').on('click', '.datadetailPendaftar',function(e) {
        var v = parseInt($(this).html());
        if (v > 0) {
            var dt = $(this).attr('data');
            dt = jwt_decode(dt);
            // console.log(dt);
            var html =  '<div class = "row">'+
                            '<div class = "col-md-12">'+
                                '<table class = "table">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<td>No</td>'+
                                            '<td>Name</td>'+
                                            '<td>Formulir Code</td>'+
                                            '<td>Program Studi</td>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody>';
                    if (dt.length > 0) {
                        for (var i = 0; i < dt.length; i++) {
                            html += '<tr>'+
                                        '<td>'+ (parseInt(i)+1) + '</td>'+
                                        '<td>'+ dt[i].Name + '</td>'+
                                        '<td>'+ dt[i].FormulirCode+' / '+dt[i].No_ref + '</td>'+
                                        '<td>'+ dt[i].ProdiName+ '</td>'+
                                    '</tr>';
                        }
                    }
                    else
                    {
                        html += '<tr>'+
                                    '<td colspan="4"><label>No Data Detail</label></td>'+
                                '</tr>';
                    }


                    html  += '</tbody></table></div></div>';


            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Detail</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        }
    })

    $(document).off('click', '.datadetailPassSelection').on('click', '.datadetailPassSelection',function(e) {
        var v = parseInt($(this).html());
        if (v > 0) {
            var dt = $(this).attr('data');
            dt = jwt_decode(dt);
            // console.log(dt);
            var html =  '<div class = "row">'+
                            '<div class = "col-md-12">'+
                                '<table class = "table">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<td>No</td>'+
                                            '<td>NPM & Name</td>'+
                                            '<td>Formulir Code</td>'+
                                            '<td>Program Studi</td>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody>';
                    if (dt.length > 0) {
                        for (var i = 0; i < dt.length; i++) {
                            html += '<tr>'+
                                        '<td>'+ (parseInt(i)+1) + '</td>'+
                                        '<td>'+ dt[i].NPM + '<br/>'+dt[i].Name + '</td>'+
                                        '<td>'+ dt[i].FormulirCode+' / '+dt[i].No_ref + '</td>'+
                                        '<td>'+ dt[i].ProdiName+ '</td>'+
                                    '</tr>';
                        }
                    }
                    else
                    {
                        html += '<tr>'+
                                    '<td colspan="4"><label>No Data Detail</label></td>'+
                                '</tr>';
                    }


                    html  += '</tbody></table></div></div>';


            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Detail</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        }
    })
</script>