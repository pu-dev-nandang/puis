<div class="row" style="margin-top: 30px;">
    <div class="col-md-3">
        <div class="thumbnail" style="min-height: 30px;padding: 10px;">
            <input type="text" name="" class="form-control" placeholder="Input VA Mahasiswa" id = "VA">
        </div>
    </div>
</div>
<br>
<div class="row">
  <div class="col-md-12" align="right">
    <button type="button" class="btn btn-default" id = 'idbtn-cari'><span class="glyphicon glyphicon-search"></span> Cari</button>
  </div>
</div>
<div class="row">
    <div class="col-md-12">
    	<hr/>
    	<div id = 'pageLoaddata'></div>
    </div>	
</div>
<script type="text/javascript">
	$(document).on('click','#idbtn-cari', function () {
	    var VA = $("#VA").val();
	    result = Validation_required(VA,'VA');
	    if (result['status'] == 0) {
	      toastr.error(result['messages'], 'Failed!!');
	    }
	    else
	    {
	      loadData(VA);
	    }
	});

	$(document).on('keypress','#VA', function ()
	{

	    if (event.keyCode == 10 || event.keyCode == 13) {
	      valuee = $(this).val();
	      loadData(valuee);
	    }
	}); // exit enter

	function loadData(VA)
	{
			/*$('#NotificationModal .modal-header').addClass('hide');
		    $('#NotificationModal .modal-body').html('<center>' +
		        '                    <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>' +
		        '                    <br/>' +
		        '                    Loading Data . . .' +
		        '                </center>');
		    $('#NotificationModal .modal-footer').addClass('hide');
		    $('#NotificationModal').modal({
		        'backdrop' : 'static',
		        'show' : true
		    });*/
		    var url = base_url_js+'finance/check-va-cari';
            var data = {
                VA : VA,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
            	var resultJson = jQuery.parseJSON(resultJson);
            	console.log(resultJson);
            	$("#pageLoaddata").empty()
            	var html = '';
            	var tblhead = '<table class="table table-bordered" id="datatable2">'+
					            '<thead>'+
					            '<tr style="background: #333;color: #fff;">'+
					                '<th style="width: 12%;">Program Study</th>'+
					                '<th style="width: 20%;">Nama,NPM &amp;  VA</th>'+
					                '<th style="width: 15%;">Payment Type</th>'+
					                '<th style="width: 15%;">Email PU</th>'+
					                '<th style="width: 10%;">Biling</th>'+
					                '<th style="width: 10%;">Invoice</th>'+
					                '<th style="width: 10%;">Status</th>'+
					                '<th style="width: 10%;">Expired</th>'+
					            '</tr>'+
					            '</thead>';

				html = tblhead;
				var tbody = '';
				tbody += '<tbody>';
            	if(resultJson.msg == '')
            	{
					var data = resultJson['rs'];
					tbody += '<tr>'+
									'<td>'+data['ProdiEng']+'<br>'+data['SemesterName']+'</td>'+
									'<td>'+data['Nama']+'<br>'+data['NPM']+'<br>'+data['VA']+'</td>'+
									'<td>'+data['PTIDDesc']+'</td>'+
									'<td>'+data['EmailPU']+'</td>'+
									'<td>'+data['BilingID']+'</td>'+
									'<td>'+formatRupiah(data['Invoice'])+'</td>'+
									'<td>'+data['Status']+'</td>'+
									'<td>'+data['Expired']+'</td>';
            	}
            	else
            	{
            		tbody += '<tr>'+
            					'<td colspan = "8" align = "center">'+resultJson.msg+'</td>';

            		;
            	}	
            	 tbody += '</tbody>';			
            	 $("#pageLoaddata").html(html+tbody);	   	        

		    }).fail(function() {
              toastr.info('No Result Data'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
                $('#NotificationModal').modal('hide');
            });
	}
</script>