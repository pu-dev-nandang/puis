<?php 

$d = $dataEmp[0];
// print_r($dataEmp); 

?>


<style>
    .btn-circle.btn-xl {
    width: 70px;
    height: 70px;
    padding: 10px 16px;
    border-radius: 35px;
    font-size: 24px;
    line-height: 1.33;
}

.btn-circle {
    width: 30px;
    height: 30px;
    padding: 6px 0px;
    border-radius: 15px;
    text-align: center;
    font-size: 12px;
    line-height: 1.42857;
}

.btn-round{
    border-radius: 17px;
}

#tableList tr td, #tableList tr th {
    font-size: 13px;
}


</style> 

<style>
	.panel-default > .panel-heading-custom {
    	background: #3968c6; color: #fff;
}
</style>

<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="thumbnail" style="min-height: 100px;">
					<!-- <h3>Form Tugas Keluar</h3> -->
					<div class="panel panel-default">
					    <div class="panel-heading panel-heading-custom">Document Request Form</div>
					    	<div class="panel-body">

                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="text-primary">Name : <?= $d['TitleAhead']," ",$d['Name']," ",$d['TitleBehind']," -- ", $d['NIP']; ?> </label>
                                        </div>
                                    </div>
                                </div>

					    		<div class="row">
				                    <div class="col-xs-12">
				                        <div class="form-group">
				                            <label>Type Form</label>
				                            	<select class="form-control filtertypedocument" id="filaddivisi"><option id="" disabled selected> --- Select Type Form --- </option></select>
				                        </div>
				                    </div>
				                </div>


					    		<div class="row">
				                    <div class="col-xs-12">
				                        <div class="form-group">
				                            <label>For Request</label>
				                            	<input class="form-control" id="to_event">
				                        </div>
				                    </div>
				                </div>

                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label>Start Date</label>
                                        <div id="datetimepicker1" data-no="1" class="input-group input-append date datetimepicker">
                                            <input data-format="yyyy-MM-dd" type="text" class="form-control" id="startDate" readonly>
                                            <span class="add-on input-group-addon">
                                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 form-group">
                                        <label>Start Time</label>
                                        <div id="timepicker1" data-no="1" class="input-group input-append date datetimepicker">
                                            <input data-format="hh:mm" type="text" class="form-control" id="startTime" readonly>
                                            <span class="add-on input-group-addon">
                                                <i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-time"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

				                <div class="row">
					                <div class="col-xs-6 form-group">
					                	<label>End Date</label>
			                            <div id="datetimepicker2" data-no="1" class="input-group input-append date datetimepicker">
			                                <input data-format="yyyy-MM-dd hh:mm" type="text" class="form-control" id="endDate" readonly>
			                                <span class="add-on input-group-addon">
			                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
			                                </span>
			                            </div>
			                        </div>
				                    <div class="col-xs-4 form-group">
				                		<label>End Time</label>
			                            <div id="timepicker2" data-no="1" class="input-group input-append date datetimepicker">
			                                <input data-format="hh:mm" type="text" class="form-control" id="endTime" readonly>
			                                <span class="add-on input-group-addon">
			                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-time"></i>
			                                </span>
			                            </div>
		                        	</div>
				                </div>

				                <div class="row">
				                	<div class="col-xs-12">
				                		<label>Description Request Location </label>
				                		<textarea rows="3" cols="5" name="DescriptionVenue" id="DescriptionVenue" class="form-control"></textarea>
				                	</div>
				                </div>
                                 <br />

				                <div class="row">
				                	<div class="col-xs-12 form-group" style="text-align: right;">

				                		<button type="button" class="btn btn-success btn-round btnsaverequest" dataid="'+response[i]['IDVersion']+'">
                                            <span class="glyphicon glyphicon-floppy-disk"></span>  Request
				                		</button>
						            </div>
						        </div>

					    
							</div>
					</div>
			</div>

		</div>
	</div>
</div>


<div class="container">
    <div class="row">
        <div class="widget-content col-md-12">
            <div class="thumbnail">
            
             <div id="loadtablerequest"></div>  
            </div>
        </div>
    </div>
</div>

<script>
    
    $(document).on('click','.btnsaverequest',function () {
        loading_button('.btnsaverequest');
        savedatarequestdoc();
    });

    function savedatarequestdoc() {

        var typerequest = $('.filtertypedocument option:selected').attr('id');
        var to_event = $('#to_event').val();

        var startDate = $('#startDate').val();
        var startTime = $('#startTime').val();
        var stardatetime = startDate+' '+startTime;

        var endDate = $('#endDate').val();
        var endTime = $('#endTime').val();
        var enddatetime = endDate+' '+endTime;
        var DescriptionVenue = $('#DescriptionVenue').val();

        if(typerequest!=null && typerequest!=''
                && to_event!='' && to_event!=null
                && startDate!='' && startDate!=null
                && endDate!='' && endDate!=null
                && startTime!='' && startTime!=null
                && endTime!='' && endTime!=null
                && DescriptionVenue!='' && DescriptionVenue!=null)
        { 
    
            var data = {
                action : 'AddRequest',
                formInsert : {
                    typerequest : typerequest,
                    to_event : to_event,
                    startDate : stardatetime,
                    endDate : enddatetime,
                    DescriptionVenue : DescriptionVenue
                }
            };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api2/__crudrequestdoc';
                $.post(url,{token:token},function (result) {
                        
                    if(result==0 || result=='0'){
                        $('.btnsaverequest').prop('disabled',false).html('<span class="glyphicon glyphicon-floppy-disk"></span> Save');
                    } else {  
                        toastr.success('Request Document Saved','Success');
                        setTimeout(function () {
                            $('.btnsaverequest').prop('disabled',false).html('<span class="glyphicon glyphicon-floppy-disk"></span> Save');
                            window.location.href = '';
                        },1000);
                    }
                });
        }
        else {
            toastr.error('The form is still empty!','Error');
            $('.btnsaverequest').prop('disabled',false).html('<span class="glyphicon glyphicon-floppy-disk"></span> Save');
            return;
        }
     }

</script>


<script>
    function loadfilterdocument() {
        var url = base_url_js+'api/__getloadtypedocument';
        $.getJSON(url,function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
               $('.filtertypedocument').append('<option id="'+jsonResult[i].ID+'"> '+jsonResult[i].NameFiles+' </option>');
            }
        });
    }
</script>


<script>
$(document).ready(function () {
        loadlistrequestdocument();
        loadfilterdocument();
    });

function loadlistrequestdocument() {
        
        var url = base_url_js+'api/__getrequestnip';
        var token = jwt_encode({NIP : <?= $d['NIP']; ?>},'UAP)(*');

        $.post(url,{token:token},function (resultJson) {
            var response = resultJson;
            console.log(response);

                $("#loadtablerequest").append('<div class="table-responsive">                        '+
                    '     <table class="table table-striped  table-bordered" id="tableList">        '+
                    '         <thead>                                                               '+
                    '         <tr style="background: #3968c6;color: #FFFFFF;">                      '+
                    '             <th style="width: 5%;text-align: center;">No.</th>           		'+
                    '             <th style="width: 11%;text-align: center;">Name/ NIP</th>   		'+
                    '             <th style="width: 8%;text-align: center;">Type Request</th>       '+
                     '            <th style="width: 18%;text-align: center;">For Request</th>        '+
                    '             <th style="width: 13%;text-align: center;">Date Time</th>         '+
                    '            <th style="width: 22%;text-align: center;">Description Request</th>  '+
                    '            <th style="width: 10%;text-align: center;">Date Confirm</th>       '+
                    '             <th style="text-align: center;width: 5%;">Action</th>             '+
                    '         </tr>                                                                 '+
                    '         </thead>                                                              '+
                    '         <tbody id="dataRow"></tbody>                                          '+
                    '    </table>                                                                   '+
                    '</div> ');  

                
                var orbs=0;
                for (var i = 0; i < response.length; i++) {

                    var tgl1 = response[i]['StartDate'];
                    var tgl2 = response[i]['EndDate'];
                    var StartDate = moment(tgl1).format('DD MMM YYYY');
                    var EndDate = moment(tgl2).format('DD MMM YYYY');
                   
                    if(response[i]['ConfirmStatus'] == 1) {
                        var idrequest = response[i]['IDRequest'];
                        var nip = response[i]['NIP'];

                        var token = jwt_encode({NIP : nip, IDRequest : idrequest },'UAP)(*');
                        var linksurat = base_url_js+"save2pdf/suratTugasKeluar/"+token; 
                        var buttonlink = ('<a href="'+linksurat+'" class="btn btn-success btn-circle" target="_blank"><i class="fa fa-download"></i></a> ');
                    } else if(response[i]['ConfirmStatus'] == -1) {
                        var buttonlink = '<p class="text-danger"> Rejected </p>';
                    } else {

                        var buttonlink = '<p class="text-primary"> Waiting Approved </p>';
                    }

                    if(response[i]['DateConfirm'] == '0000-00-00 00:00:00' ) {
                            var dateconfirms = '';
                    } else {
                            var datex = response[i]['DateConfirm'];
                            var dateconfirms = moment(datex).format('DD MMM YYYY HH:mm');
                    }

                   $("#dataRow").append('<tr>                                                       	'+
                    '            <td style="text-align: center;">'+(i+1)+'</td>            												'+   
                    '            <td>'+response[i]['NIP']+' - '+response[i]['Name']+'</td>            	'+   
                    '            <td style="text-align: center;">'+response[i]['TypeFiles']+'</td>            						'+     
                    '            <td>'+response[i]['ForTask']+'</td>            						'+     
                    '            <td style="text-align: center;"><b>'+StartDate+' s/d '+EndDate+'</b></td>            '+     
                    '            <td>'+response[i]['DescriptionAddress']+'</td>            '+   
                    '            <td style="text-align: center;">'+dateconfirms+'</td>            '+  
                    '            <td style="text-align: center;"> '+buttonlink+'</td>      '+     
                    '   </tr>');

                
                }
        });
   };
</script>

<script>
    $(document).on('click','.btnviewlistsrata',function () {
        var filesub = $(this).attr('filesub');
       
            $('#NotificationModal .modal-header').addClass('hide');
            $('#NotificationModal .modal-body').html('<center> '+
                '<iframe src="'+base_url_js+'uploads/files/'+filesub+'" frameborder="0" style="width:745px; height:550px;"></iframe> '+
                '<br/><br/><button type="button" id="btnRemoveNoEditSc" class="btn btn-primary btn-round" data-dismiss="modal"><span class="fa fa-remove"></span> Close</button><button type="button" filesublix ="'+filesub+'" class="btn btn-primary btn-circle pull-right filesublink" data-toggle="tooltip" data-placement="top" title="Full Review"><span class="fa fa-external-link"></span></button>' +
            '</center>');
            $('#NotificationModal .modal-footer').addClass('hide');
            $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
            });
    });

    $(document).on('click','.filesublink',function () {
        var filesubx = $(this).attr('filesublix');
        var url = base_url_js+'uploads/files/'+filesubx;
        window.open(url, '_blank',);
    });

</script>

<script>
$("#datetimepicker1").datetimepicker({
    	format: 'yyyy-MM-dd',
    	pickTime: false,
        todayHighlight: true,
    	autoclose: true,
        changeMonth : true,
        changeYear : true
	}).on('changeDate', function (selected) {
	    var startDate = new Date(selected.date.valueOf());
	    $('#datetimepicker2').datetimepicker('setStartDate', startDate);
	}).on('clearDate', function (selected) {
	    $('#datetimepicker2').datetimepicker('setStartDate', null);
	});

$("#datetimepicker2").datetimepicker({
    	format: 'yyyy-MM-dd',
        pickTime: false,
        todayHighlight: true,
        autoclose: true,
        changeMonth : true,
        changeYear : true
	}).on('changeDate', function (selected) {
	    var endDate = new Date(selected.date.valueOf());
	    $('#datetimepicker1').datetimepicker('setEndDate', endDate);
	}).on('clearDate', function (selected) {
	    $('#datetimepicker1').datetimepicker('setEndDate', null);
	});


$("#timepicker1").datetimepicker({
        pickDate: false,
        pickSeconds : false

    }).on('changeDate', function (selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#timepicker2').datetimepicker('setStartDate', startDate);
    }).on('clearDate', function (selected) {
        $('#timepicker2').datetimepicker('setStartDate', null);
    });

$("#timepicker2").datetimepicker({
        pickDate: false,
        pickSeconds : false
    }).on('changeDate', function (selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#timepicker1').datetimepicker('setStartDate', startDate);
    }).on('clearDate', function (selected) {
        $('#timepicker1').datetimepicker('setStartDate', null);
    });

</script>


<script>
	 $(document).on('click','#useLogOut',function () {
        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Log Me Out </b><hr/> ' +
            '<button type="button" class="btn btn-primary btnActionLogOut" style="margin-right: 5px;">Yes</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
            '</div>');
        $('#NotificationModal').modal('show');
    });

$(document).on('click','.btnActionLogOut',function () {
        var url = base_url_js+"auth/logMeOut";
        loading_page('#NotificationModal .modal-body');
        $.post(url,function (result) {
            setTimeout(function () {
                window.location.href = base_url_sign_out;
            },500);
        });
    });
</script>

