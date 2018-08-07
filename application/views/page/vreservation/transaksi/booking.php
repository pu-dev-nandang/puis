<div class = 'row'>
	<!--=== Calendar ===-->
	<div class="col-md-12">
		<div class="widget">
			<div class="widget-header">
					<h4><i class="icon-calendar"></i> Schedule</h4>
				</div>
			<div class="widget-content">
				<div class = "row">	
					<div class="col-xs-3">
						<div id="datetimepicker1" class="input-group input-append date datetimepicker">
								<input data-format="yyyy-MM-dd" class="form-control" id="datetime_deadline1" type="	text" readonly="" >
								<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
						</div>
					</div>
					<div class="col-xs-3">
					<button class="btn btn-success" id = "search"><span class="glyphicon glyphicon-search"></span> Search</button>
					</div>
				</div>
				<br>
				<!-- <div class = "row">	 -->
					<div id="schedule"></div>
				<!-- </div> -->
			</div>
		</div> <!-- /.widget box -->
	</div> <!-- /.col-md-6 -->
	<!-- /Calendar -->
</div>	

<script type="text/javascript">
	$(document).ready(function(){
		var divHtml = $("#schedule");
		loadDataSchedule(divHtml);

		Date.prototype.addDays = function(days) {
	          var date = new Date(this.valueOf());
	          date.setDate(date.getDate() + days);
	          return date;
	    }
          var date = new Date();

		$('#datetimepicker1').datetimepicker({
			format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
		 startDate: date.addDays(0),
		});

		$('#datetime_deadline1').prop('readonly',true);
	});

	$(document).on('click','.panel-blue', function () {
		var room = $(this).attr('room');
		var time =  $(this).attr('title');
		var tgl = $("#datetime_deadline1").val();
		modal_generate('add','Form Booking Reservation',room,time,tgl);
    });

    function modal_generate(action,title,room,time,tgl,user = '') {
        var url = base_url_js+"vreservation/modal_form";
        var data = {
            Action : action,
            room : room,
            time : time,
            user : user,
            tgl  : tgl
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token }, function (html) {
            $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+title+'</h4>');
            $('#GlobalModalLarge .modal-body').html(html);
            $('#GlobalModalLarge .modal-footer').html(' ');
            $('#GlobalModalLarge').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        })
    }

    $(document).on('click','.chk_e_additional', function () {
        $('input.chk_e_additional').prop('checked', false);
        $(this).prop('checked',true);
    });

    $(document).on('click','.chk_person_support', function () {
        $('input.chk_person_support').prop('checked', false);
        $(this).prop('checked',true);
    });

    $(document).on('click','.chk_e_multiple', function () {
        $('input.chk_e_multiple').prop('checked', false);
        $(this).prop('checked',true);
    });


    // event ya multiple belum
    $(document).on('change','#multipleYA', function () {
        if(this.checked) {
            //equipment_additional = [];
            $('#divE_multiple').remove();
            
            
            // get data m_equipment_additional
            /*var url = base_url_js+"api/__m_equipment_additional";
            $.post(url,function (data_json) {
              var response = data_json;
              var splitBagi = 3;
              var split = parseInt(response.length / splitBagi);
              var sisa = response.length % splitBagi;
              
              if (sisa > 0) {
                    split++;
              }
              var getRow = 0;
              var divE_additional = '<div class="col-md-6" id="divE_additional"><strong>Choices Equipment Additional</strong></div>';
              $('#e_additional').after(divE_additional);
              $('#divE_additional').append('<table class="table" id ="tablechk_e_additional">');
              for (var i = 0; i < split; i++) {
                if ((sisa > 0) && ((i + 1) == split) ) {
                                    splitBagi = sisa;    
                }
                $('#tablechk_e_additional').append('<tr id = "a'+i+'">');
                for (var k = 0; k < splitBagi; k++) {
                    $('#a'+i).append('<td>'+
                                        '<input type="checkbox" class = "chke_additional" name="chke_additional" value = "'+response[getRow].ID+'">&nbsp'+ response[getRow].Equipment+
                                     '</td>'
                                    );
                    getRow++;
                }
                $('#a'+i).append('</tr>');
              }
              $('#tablechk_e_additional').append('</table>');
            }).done(function () {
              //loadAlamatSekolah();
            });*/
        }

    });

    $(document).on('change','#e_additionalYA', function () {
        if(this.checked) {
            //equipment_additional = [];
            $('#divE_additional').remove();
            // get data m_equipment_additional
            var url = base_url_js+"api/__m_equipment_additional";
            $.post(url,function (data_json) {
              var response = data_json;
              var splitBagi = 3;
              var split = parseInt(response.length / splitBagi);
              var sisa = response.length % splitBagi;
              
              if (sisa > 0) {
                    split++;
              }
              var getRow = 0;
              var divE_additional = '<div class="col-md-6" id="divE_additional"><strong>Choices Equipment Additional</strong></div>';
              $('#e_additional').after(divE_additional);
              $('#divE_additional').append('<table class="table" id ="tablechk_e_additional">');
              for (var i = 0; i < split; i++) {
                if ((sisa > 0) && ((i + 1) == split) ) {
                                    splitBagi = sisa;    
                }
                $('#tablechk_e_additional').append('<tr id = "a'+i+'">');
                for (var k = 0; k < splitBagi; k++) {
                    $('#a'+i).append('<td>'+
                                        '<input type="checkbox" class = "chke_additional" name="chke_additional" value = "'+response[getRow].ID+'">&nbsp'+ response[getRow].Equipment+
                                     '</td>'
                                    );
                    getRow++;
                }
                $('#a'+i).append('</tr>');
              }
              $('#tablechk_e_additional').append('</table>');
            }).done(function () {
              //loadAlamatSekolah();
            });
        }

    });

    $(document).on('change','#person_supportTDK', function () {
        if(this.checked) {
            $('#divperson_support').remove();
        }

    });

    $(document).on('change','#e_additionalTDK', function () {
        if(this.checked) {
            $('#divE_additional').remove();
        }

    });

    $(document).on('change','#person_supportYA', function () {
        if(this.checked) {
            //equipment_additional = [];
            $('#divperson_support').remove();
            // get data m_equipment_additional
            var url = base_url_js+"api/__m_additional_personel";
            $.post(url,function (data_json) {
              var response = data_json;
              var splitBagi = 3;
              var split = parseInt(response.length / splitBagi);
              var sisa = response.length % splitBagi;
              
              if (sisa > 0) {
                    split++;
              }
              var getRow = 0;
              var divE_additional = '<div class="col-md-6" id="divperson_support"><strong>Choices Person Support</strong></div>';
              $('#person_support').after(divE_additional);
              $('#divperson_support').append('<table class="table" id ="tablechk_divperson_support">');
              for (var i = 0; i < split; i++) {
                if ((sisa > 0) && ((i + 1) == split) ) {
                                    splitBagi = sisa;    
                }
                $('#tablechk_divperson_support').append('<tr id = "psa'+i+'">');
                for (var k = 0; k < splitBagi; k++) {
                    $('#psa'+i).append('<td>'+
                                        '<input type="checkbox" class = "chk_person_support_td" name="chk_person_support_td" value = "'+response[getRow].ID+'">&nbsp'+ response[getRow].Division+
                                     '</td>'
                                    );
                    getRow++;
                }
                $('#psa'+i).append('</tr>');
              }
              $('#tablechk_divperson_support').append('</table>');
            }).done(function () {
              //loadAlamatSekolah();
            });
        }

    });

</script>
