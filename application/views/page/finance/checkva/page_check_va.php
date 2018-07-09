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
		    }).fail(function() {
              toastr.info('No Result Data'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
                $('#NotificationModal').modal('hide');
            });
	}
</script>