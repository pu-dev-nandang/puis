<div class = 'row'>
	<!--=== Calendar ===-->
	<div class="col-md-12">
		<div class="widget">
      <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
        <div class="col-md-3">
          <b>Status : </b><br>
          <i class="fa fa-circle" style="color:#6ba5c1;"></i> Available || 
          <i class="fa fa-circle" style="color:#e98180;"></i> Booked ||
          <i class="fa fa-circle" style="color:#20c51b;"></i> Booked ||
          <i class="fa fa-circle" style="color:#ffb848;"></i> Requested 

        </div>
      </div>
			<div class="widget-header">
					<h4 id = 'schdate'><i class="icon-calendar"></i> Schedule Date : <?php echo $dateDay ?></h4>
				</div>
			<div class="widget-content">
				<div class = "row">	
					<div class="col-xs-3">
						<div id="datetimepicker1" class="input-group input-append date datetimepicker">
								<input data-format="yyyy-MM-dd" class="form-control" id="datetime_deadline1" type="	text" readonly="" value="<?php echo $dateDay ?>">
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
    // var url = base_url_js+'api/__cek_deadline_paymentNPM';
    // var data = {
    //     NPM : '12140015'
    // };
    // var token = jwt_encode(data,"UAP)(*");
    // $.post(url,{ token:token },function (data_json) {
    //     console.log(data_json);
    // });         

		var divHtml = $("#schedule");
		loadDataSchedule(divHtml,'<?php echo $dateDay ?>');
    socket_messages_tbooking();
		Date.prototype.addDays = function(days) {
	          var date = new Date(this.valueOf());
	          date.setDate(date.getDate() + days);
	          return date;
	    }
          var date = new Date();

		$('#datetimepicker1').datetimepicker({
			format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
     // startDate: date.addDays(0), // adding by policy booking minus 1
		 startDate: date.addDays(<?php echo $dayPolicy ?>), // adding by policy booking minus 1
		});

		$('#datetime_deadline1').prop('readonly',true);
	});

  function socket_messages_tbooking()
  {
      var socket = io.connect( 'http://'+window.location.hostname+':3000' );
      // var socket = io.connect( '<?php echo serverRoot ?>'+':3000' );
      socket.on( 'update_schedule_notifikasi', function( data ) {

          //$( "#new_count_message" ).html( data.new_count_message );
          //$('#notif_audio')[0].play();
          if (data.update_schedule_notifikasi == 1) {
              // action
              var getDate = data.date;
              if (getDate == '') {
                 getDate = "<?php echo date('Y-m-d') ?>";
              }
              // $("#CaptionTBL").html('<strong>'+getDate+'</strong>');
              var divHtml = $("#schedule");
              loadDataSchedule(divHtml,'<?php echo $dateDay ?>');
              // loadDataListApprove();
          }

      }); // exit socket
  }

  $(document).on('click','#search', function () {
    var get = $('#datetime_deadline1').val();
    var divHtml = $("#schedule");
    var OpCategory = $("#OpCategoryRoom").val();
    loadDataSchedule(divHtml,get,OpCategory);
    $("#schdate").html('<i class="icon-calendar"></i> Schedule Date : '+ get);
  });

  

  $(document).on('click','#ModalbtnSaveForm', function () {
    loading_button('#ModalbtnSaveForm');
    var Room = $("#Room").val();
    var Start = $("#Start").val();
    var End = $("#End").val();
    var Agenda = $("#Agenda").val();
    var chk_e_additional = '';
    if ($('#e_additionalYA').is(':checked')) {
      var chk_e_additional = [];
      $('.chke_additional').each(function() {
         if ($(this).is(':checked')) {
            var valuee = $(this).val();
            var Qty = $(".chke_additional"+valuee).val();
            var eeArr = {
              ID_equipment_add : valuee,
              Qty : Qty
            };
            chk_e_additional.push(eeArr);
         }
      });
    }

    // console.log(chk_e_additional);

    // var chk_person_support = '';
    // if ($('#person_supportYA').is(':checked')) {
    //   var chk_person_support = [];
    //   $('.chk_person_support_td').each(function() {
    //      if ($(this).is(':checked')) {
    //         var valuee = $(this).val();
    //         chk_person_support.push(valuee);
    //      }
    //   });
    // }

    var chk_person_support = '';
    if ($('#person_supportYA').is(':checked')) {
      var chk_person_support = $('.chk_person_support_td').val();
    }

    var chk_markom_support = '';
    if ($('#markom_supportYA').is(':checked')) {
      var chk_markom_support = [];
      $('.chk_markom_support_td').each(function() {
         if ($(this).is(':checked')) {
            var valuee = $(this).val();
            chk_markom_support.push(valuee);
         }
      });
      var chk_markom_support_td_add = $(".chk_markom_support_td_add").val();
      if (chk_markom_support_td_add != "" || chk_markom_support_td_add != null) {
        chk_markom_support.push('Note : '+chk_markom_support_td_add);
      }
    }

     // console.log(chk_person_support);

    // var chk_e_multiple = '';
    // if ($('#multipleYA').is(':checked')) {
    //   var chk_e_multiple = [];
    //   $('.datetime_deadlineMulti').each(function() {
    //      var valuee = $(this).val();
    //      chk_e_multiple.push(valuee);
    //   });
    // }
    var KetAdditional = {};
    var dataArr = {
      Participant_Type : $("#UserType").val(),
      VVIP :$(".VVIP").val(), 
      VIP :$(".VIP").val(), 
      PIC :$(".PIC").val(), 
      PIC_Info :$(".PICInfo").val(), 
      Note :$("#NoteDescAdd").val(), 
    };

    for(var key in dataArr) {
      if (dataArr[key] != "") {
        KetAdditional[key] = dataArr[key];
      }
    }

   var data = {
       Room : Room,
       Start : Start,
       End : End,
       Agenda : Agenda,
       chk_e_additional : chk_e_additional,
       chk_person_support : chk_person_support,
       //chk_e_multiple : chk_e_multiple,
       file : file_validation(),
       date : $('#datetime_deadline1').val(),
       Participant : $("#Participant").val(),
       chk_markom_support : chk_markom_support,
       KetAdditional : KetAdditional,
   };

   console.log(data);

   if (validationInput = validationModal(data)) {
          var form_data = new FormData();
          var fileData = document.getElementById("ExFile").files[0];
          var url = base_url_js + "vreservation/add_save_transaksi"
          var token = jwt_encode(data,"UAP)(*");
          form_data.append('token',token);
          form_data.append('fileData',fileData);
          if ( $( "#ExFileMarkomm" ).length ) {
              var filesMarkomm = $('#'+'ExFileMarkomm')[0].files;
              for(var count = 0; count<filesMarkomm.length; count++)
              {
               form_data.append("fileDataMarkomm[]", filesMarkomm[count]);
              }
           
          }
          
          $.ajax({
            type:"POST",
            url:url,
            data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,
            dataType: "json",
            success:function(data)
            {
              if(data.status == 1) {
                // toastr.options.fadeOut = 100000;
                toastr.success(data.msg, 'Success!');
                var divHtml = $("#schedule");
                loadDataSchedule(divHtml,'<?php echo $dateDay ?>');
                // send notification other school from client
                var socket = io.connect( 'http://'+window.location.hostname+':3000' );
                // var socket = io.connect( '<?php echo serverRoot ?>'+':3000' );
                  socket.emit('update_schedule_notifikasi', { 
                    update_schedule_notifikasi: '1',
                    date : $('#datetime_deadline1').val(),
                  });

              }
              else
              {
                // toastr.options.fadeOut = 100000;
                toastr.error(data.msg, 'Failed!!');
              }
              $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
              $('#GlobalModalLarge').modal('hide');

            },
            error: function (data) {
              toastr.error("Connection Error, Please try again", 'Error!!');
              $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
            }
          })
   }
   else
   {
    $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
   }

  });

  function file_validation()
  {
    try{
      var name = document.getElementById("ExFile").files[0].name;
      var ext = name.split('.').pop().toLowerCase();
      if(jQuery.inArray(ext, ['pdf','PDF']) == -1) 
      {
        toastr.error("Invalid File", 'Failed!!');
        return false;
      }
      var oFReader = new FileReader();
      oFReader.readAsDataURL(document.getElementById("ExFile").files[0]);
      var f = document.getElementById("ExFile").files[0];
      var fsize = f.size||f.fileSize;
      if(fsize > 2000000) // 2mb
      {
       toastr.error("Image File Size is very big", 'Failed!!');
       return false;
      }

    }
    catch(err)
    {
      // return false;
    }
      return true;
  }

  function validationModal(arr)
  {
    var toatString = "";
    var result = "";
    for(var key in arr) {
       switch(key)
       {
        case  "Room" :
        case  "Start" :
        case  "End" :
        case  "Agenda" :
              result = Validation_required(arr[key],key);
              if (result['status'] == 0) {
                toatString += result['messages'] + "<br>";
              }
              break;
        case  "file" :
              if (!arr[key]) {
                toatString += 'Invalid File' + "<br>";
              }
              break;
        case  "chk_e_additional" :
              if ($('#e_additionalYA').is(':checked')) {
                // check lenght lebih dari satu
                var aa = arr[key];
                if (aa.length == 0) {
                  toatString += 'Please check Equipment Additional' + "<br>";
                }
              }
              else
              {
                if($("#e_additionalTDK"). prop("checked") == false){
                  toatString += 'Please Choices Equipment Additional' + "<br>";
                }
              }
              break;
        case  "chk_person_support" :
              // if ($('#person_supportYA').is(':checked')) {
              //   // check lenght lebih dari satu
              //   var aa = arr[key];
              //   if (aa.length == 0) {
              //     toatString += 'Please check Person Support' + "<br>";
              //   }
              // }
              // else
              // {
              //   if($("#person_supportTDK"). prop("checked") == false){
              //     toatString += 'Please Choices Person Support' + "<br>";
              //   }
              // }
              if ($('#person_supportYA').is(':checked')) {
                result = Validation_required(arr[key],'Input Person Support');
                if (result['status'] == 0) {
                  toatString += result['messages'] + "<br>";
                }
              }
              else
              {
                if($("#person_supportTDK"). prop("checked") == false){
                  toatString += 'Please Choices Person Support' + "<br>";
                }
              }
              break;
        case  "chk_markom_support" :
              if ($('#markom_supportYA').is(':checked')) {
                // check lenght lebih dari satu
                var aa = arr[key];
                if (aa.length == 0) {
                  toatString += 'Please check Marcomm Support' + "<br>";
                }
                else
                {
                  // check upload markomm support
                  // Graphic Design
                  var bool = 0;
                  for (var x = 0; x < aa.length; x++) {
                    if (aa[x] == 'Graphic Design') {bool = 1; break;}
                  }
                  if (bool == 1) {
                    // try{
                    //   var name = document.getElementById("ExFileMarkomm").files[0].name;
                    //   var ext = name.split('.').pop().toLowerCase();
                    //   if(jQuery.inArray(ext, ['jpeg','jpeg','JPG','jpg','PNG','png','pdf','PDF']) == -1) 
                    //   {
                    //     toatString += 'Invalid File Marcomm' + "<br>";
                    //   }
                    //   var oFReader = new FileReader();
                    //   oFReader.readAsDataURL(document.getElementById("ExFileMarkomm").files[0]);
                    //   var f = document.getElementById("ExFileMarkomm").files[0];
                    //   var fsize = f.size||f.fileSize;
                    //   if(fsize > 2000000) // 2mb
                    //   {
                    //    toatString += 'Image File Size Marcomm is very big ' + "<br>";
                    //   }

                    // }
                    // catch(err)
                    // {
                    //   toatString += 'Invalid File Marcomm' + "<br>";
                    // }
                    var ID_element = 'ExFileMarkomm';
                    var files = $('#'+ID_element)[0].files;
                    var error = '';
                    var msgStr = '';
                    var max_upload_per_file = 4;
                    // console.log(files.length);
                    if (files.length == 0) {
                      toatString += 'File Graphic Design is Required<br>';
                    }
                    else
                    {
                      if (files.length > max_upload_per_file) {
                        toatString += '1 Document should not be more than 4 Files<br>';

                      }
                      else
                      {
                        for(var count = 0; count<files.length; count++)
                        {
                         var name = files[count].name;
                         // console.log(name);
                         var extension = name.split('.').pop().toLowerCase();
                         if(jQuery.inArray(extension, ['jpeg','jpeg','JPG','jpg','PNG','png','pdf','PDF']) == -1)
                         {
                          var no = parseInt(count) + 1;
                          toatString += 'Marcomm Number '+ no + ' Invalid Type File<br>';
                          //toastr.error("Invalid Image File", 'Failed!!');
                          // return false;
                         }

                         var oFReader = new FileReader();
                         oFReader.readAsDataURL(files[count]);
                         var f = files[count];
                         var fsize = f.size||f.fileSize;
                         // console.log(fsize);

                         if(fsize > 2000000) // 2mb
                         {
                          toatString += 'Marcomm Number '+ no + ' Image File Size is very big<br>';
                          //toastr.error("Image File Size is very big", 'Failed!!');
                          //return false;
                         }
                         
                        }
                      }
                    }// exit file lenght == 0
                  } // exit else bool = 1
                }
              }
              else
              {
                if($("#markom_supportTDK"). prop("checked") == false){
                  toatString += 'Please Choices Marcomm Support' + "<br>";
                }
              }

              break;      
        case  "chk_e_multiple" :
              if ($('#multipleYA').is(':checked')) {
                if ($("#countDays").val() == '') {
                  toatString += 'Please choices Days' + "<br>";
                }
                else
                {
                  // check lenght lebih dari satu
                  var ab = $("#countDays").val();
                  var aa = arr[key];
                  var bool = true;
                  for (var i = 0; i < aa.length; i++) {
                    if (aa[i] == '') {
                      bool = false;
                    }
                  }
                  if (!bool) {
                    toatString += 'Please check Multiple Days' + "<br>";
                  }
                }
              }
              else
              {
                if($("#e_multipleTDK"). prop("checked") == false){
                  toatString += 'Please Choices Multiple Days' + "<br>";
                }
              }
              break;            
       }

    }
    if (toatString != "") {
      toastr.error(toatString, 'Failed!!');
      return false;
    }

    return true;
  }

	$(document).on('click','.panel-blue', function () {
		var room = $(this).attr('room');
		var time =  $(this).attr('title');
		var tgl = $("#datetime_deadline1").val();
		modal_generate('add','Form Booking Reservation',room,time,tgl);
  });

    $(document).on('click','.chk_e_additional', function () {
        $('input.chk_e_additional').prop('checked', false);
        $(this).prop('checked',true);
    });

    $(document).on('click','.chk_layout', function () {
        $('input.chk_layout').prop('checked', false);
        $(this).prop('checked',true);
    });

    $(document).on('click','.chk_person_support', function () {
        $('input.chk_person_support').prop('checked', false);
        $(this).prop('checked',true);
    });

    $(document).on('click','.chk_markom_support', function () {
        $('input.chk_markom_support').prop('checked', false);
        $(this).prop('checked',true);
    });

    $(document).on('click','.chk_e_multiple', function () {
        $('input.chk_e_multiple').prop('checked', false);
        $(this).prop('checked',true);
    });

    // event ya multiple belum
    $(document).on('change','#layoutTDK', function () {
        if(this.checked) {
            $("#shwUploadFile").addClass('hide');
        }
    });

    // event ya multiple belum
    $(document).on('change','#layoutYA', function () {
        if(this.checked) {
            $("#shwUploadFile").removeClass('hide');
        }
    });


    // event ya multiple belum
    $(document).on('change','#e_multipleTDK', function () {
        if(this.checked) {
            $('#divE_multiple').remove();
            $('.divPageSelect').remove();
        }
    });

    $(document).on('change','#countDays', function () {
        getCountDays = $(this).val();
        $(".divPageSelect").remove();
        var input = '<div class="form-group col-md-6 divPageSelect"><br>';
        for (var i = 0; i < getCountDays; i++) {
           input += '<div id="datetimepickerMulti'+i+'" class="input-group input-append date datetimepicker">'+
                                       '<input data-format="yyyy-MM-dd hh:mm:ss" class="form-control datetime_deadlineMulti" id="datetime_deadlineMulti'+i+'" type="text"></input>'+
                                       '<span class="input-group-addon add-on">'+
                                         '<i data-time-icon="icon-time" data-date-icon="icon-calendar">'+
                                         '</i>'+
                                       '</span>'+
                                   '</div><br>';
         }
         input += '</div>';
         $('#divE_multiple').after(input); 

          Date.prototype.addDays = function(days) {
            var date = new Date(this.valueOf());
            date.setDate(date.getDate() + days);
            return date;
          }
          var date = new Date();

         for (var i = 0 ; i < getCountDays; i++) {
            $('#datetimepickerMulti'+i).datetimepicker({
             // startDate: today,
             // startDate: '+2d',
             // startDate: date.addDays(i),
              format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
              startDate: date.addDays(1),
            });

            $('#datetime_deadlineMulti'+i).prop('readonly',true);
         }

    });

    $(document).on('change','#multipleYA', function () {
        if(this.checked) {
            //equipment_additional = [];
            $('#divE_multiple').remove();
            var sss = '<select class = "full-width-fix" id = "countDays">'+
                      '<option value = "'+''+'">'+'--Select--'+'</option>';
            for (var l = 1; l <= 5; l++) {
                sss += ' <option value = "'+l+'">'+l+'</option>'
            }

            sss += '</select>';
            var divE_multiple = '<div class="col-md-6" id="divE_multiple"><strong>Choices Days</strong></div>';
            $('#multiplePage').after(divE_multiple);      
            $('#divE_multiple').append(sss);
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
              console.log(response);
              var splitBagi = 2;
              var split = parseInt(response.length / splitBagi);
              var sisa = response.length % splitBagi;
              
              if (sisa > 0) {
                    split++;
              }
              var getRow = 0;
              var divE_additional = '<div class="col-md-6" id="divE_additional" style="width: 500px;"><strong>Choices Equipment Additional</strong></div>';
              $('#e_additional').after(divE_additional);
              $('#divE_additional').append('<table class="table" id ="tablechk_e_additional">');
              for (var i = 0; i < split; i++) {
                if ((sisa > 0) && ((i + 1) == split) ) {
                                    splitBagi = sisa;    
                }
                $('#tablechk_e_additional').append('<tr id = "a'+i+'">');
                for (var k = 0; k < splitBagi; k++) {
                    $('#a'+i).append('<td>'+
                                        '<input type="checkbox" min = "1" class = "chke_additional" name="chke_additional" value = "'+response[getRow].ID_add+'">&nbsp'+ response[getRow].Equipment+' By '+response[getRow].Division+
                                     '</td>'+
                                     '<td>'+
                                        ' <input type="number" min = "1" class="form-control chke_additional'+response[getRow].ID_add+' hide"  value="1" id = "chke_additional'+response[getRow].ID_add+'">'+'</td>'
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

    $(document).on('change','#markom_supportTDK', function () {
        if(this.checked) {
            $('#divmarkom_support').remove();
        }
    });

    $(document).on('change','.chke_additional', function () {
      var aa = $(this).val();
        if(this.checked) {
            $('#chke_additional'+aa).removeClass('hide');
        }
        else
        {
          $('#chke_additional'+aa).addClass('hide');
        }

    });

    $(document).on('change','#e_additionalTDK', function () {
        if(this.checked) {
            $('#divE_additional').remove();
        }

    });

    $(document).on('change','#person_supportYA', function () {
        if(this.checked) {
            // //equipment_additional = [];
            // $('#divperson_support').remove();
            // // get data m_equipment_additional
            // var url = base_url_js+"api/__m_additional_personel";
            // $.post(url,function (data_json) {
            //   var response = data_json;
            //   var splitBagi = 3;
            //   var split = parseInt(response.length / splitBagi);
            //   var sisa = response.length % splitBagi;
              
            //   if (sisa > 0) {
            //         split++;
            //   }
            //   var getRow = 0;
            //   var divE_additional = '<div class="col-md-6" id="divperson_support" style="width: 500px;"><strong>Choices Person Support</strong></div>';
            //   $('#person_support').after(divE_additional);
            //   $('#divperson_support').append('<table class="table" id ="tablechk_divperson_support">');
            //   for (var i = 0; i < split; i++) {
            //     if ((sisa > 0) && ((i + 1) == split) ) {
            //                         splitBagi = sisa;    
            //     }
            //     $('#tablechk_divperson_support').append('<tr id = "psa'+i+'">');
            //     for (var k = 0; k < splitBagi; k++) {
            //         $('#psa'+i).append('<td>'+
            //                             '<input type="checkbox" class = "chk_person_support_td" name="chk_person_support_td" value = "'+response[getRow].ID+'">&nbsp'+ response[getRow].Division+
            //                          '</td>'
            //                         );
            //         getRow++;
            //     }
            //     $('#psa'+i).append('</tr>');
            //   }
            //   $('#tablechk_divperson_support').append('</table>');
            // }).done(function () {
            //   //loadAlamatSekolah();
            // });
            $('#divperson_support').remove();
              var divE_additional = '<div class="col-md-6" id="divperson_support" style="width: 500px;"><strong>Input Person Support</strong></div>';
              $('#person_support').after(divE_additional);
              $('#divperson_support').append('<input type = "text" class = "form-control chk_person_support_td" >');


        }

    });

    $(document).on('change','#markom_supportYA', function () {
        if(this.checked) {
            //equipment_additional = [];
            $('#divmarkom_support').remove();
            var response = ['Video','Photo','Full Duration','Graphic Design'];
            var splitBagi = 3;
            var split = parseInt(response.length / splitBagi);
            var sisa = response.length % splitBagi;
            
            if (sisa > 0) {
                  split++;
            }
            var getRow = 0;
            var divE_additional = '<div class="col-md-6" id="divmarkom_support" style="width: 500px;"><strong>Choices Support by Marcomm</strong></div>';
            $('#markom_support').after(divE_additional);
            $('#divmarkom_support').append('<table class="table" id ="tablechk_divmarkom_support">');
            for (var i = 0; i < split; i++) {
              if ((sisa > 0) && ((i + 1) == split) ) {
                                  splitBagi = sisa;    
              }
              $('#tablechk_divmarkom_support').append('<tr id = "msa'+i+'">');
              for (var k = 0; k < splitBagi; k++) {
                  $('#msa'+i).append('<td>'+
                                      '<input type="checkbox" class = "chk_markom_support_td" name="chk_markom_support_td" value = "'+response[getRow]+'">&nbsp'+ response[getRow]+
                                   '</td>'
                                  );
                  getRow++;
              }
              $('#msa'+i).append('</tr>');
            }
            $('#tablechk_divmarkom_support').append('</table>');
            $('#divmarkom_support').append('<label><strong>Input Note</strong></label><input type = "text" class = "form-control chk_markom_support_td_add"><p style = "color : red">* Please using comma(,) as delimiter');
        }

    });

    $(document).on('change','.chk_markom_support_td[value="Graphic Design"]', function () {
        if(this.checked) {
           // console.log('Graphic Design');
           var divE_Upload = '<div class="col-md-6" id="divmarkom_supportUpload" style="width: 500px;"><strong>Upload File</strong>';
           divE_Upload += '<input type="file" data-style="fileinput" id="ExFileMarkomm" multiple></div>';
           $('#tablechk_divmarkom_support').after(divE_Upload);

        }
        else
        {
          $('#divmarkom_supportUpload').remove();
        }
    });
</script>
