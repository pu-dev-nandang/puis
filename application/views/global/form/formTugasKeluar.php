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
		<div class="widget-content col-md-6">
			<div class="thumbnail">
			
			 <div id="loadtablerequest"></div>  
			</div>
		</div>

		<div class="col-md-6">
			<div class="thumbnail" style="min-height: 100px;">
					<!-- <h3>Form Tugas Keluar</h3> -->
					<div class="panel panel-default">
					    <div class="panel-heading panel-heading-custom">Document Request Form</div>
					    	<div class="panel-body">

                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Name : <?= $d['TitleAhead']," ",$d['Name']," ",$d['TitleBehind']," -- ", $d['NIP']; ?> </label>
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
				                            <label>To attend event</label>
				                            	<input class="form-control" id="to_event">
				                        </div>
				                    </div>
				                </div>

				                <div class="row">
					                <div class="col-xs-6 form-group">
					                	<label>Start Date</label>
			                            <div id="datetimepicker1" data-no="1" class="input-group input-append date datetimepicker">
			                                <input data-format="yyyy-MM-dd hh:mm" type="text" class="form-control" id="startDate" readonly>
			                                <span class="add-on input-group-addon">
			                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
			                                </span>
			                            </div>
			                        </div>
				                    <div class="col-xs-6 form-group">
				                		<label>End Date</label>
			                            <div id="datetimepicker2" data-no="1" class="input-group input-append date datetimepicker">
			                                <input data-format="yyyy-MM-dd hh:mm" type="text" class="form-control" id="endDate" readonly>
			                                <span class="add-on input-group-addon">
			                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
			                                </span>
			                            </div>
		                        	</div>
				                </div>

				                <div class="row">
				                	<div class="col-xs-12">
				                		<label>Description Venue </label>
				                		<textarea rows="3" cols="5" name="DescriptionVenue" id="DescriptionVenue" class="form-control"></textarea>
				                	</div>
				                </div>

				                <div class="row">
				                	<div class="col-xs-12" style="text-align: right;">
				                		<button type="button" class="btn btn-success btn-round btnsaverequest" dataid="'+response[i]['IDVersion']+'"><span class="glyphicon glyphicon-floppy-disk"></span>  Save
				                		</button>
						            </div>
						        </div>

					    
							</div>
					</div>
			</div>


		</div>
	</div>
</div>

<!-- Small modal -->
<div class="modal" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm" style="width: 400px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h3> Log Out </h3>
      </div>
      <div class="modal-body" >
        <p><b> Are you sure you want to log-off system ? </b><br/></p>
        	<div style="text-align: center;">
        	 	<form>
                	<button class="btn btn-primary btn-round ActionLogOut" data-dismiss="modal">Yes</button>
	            	<button class="btn btn-danger btn-round" data-dismiss="modal">No</button>
            	</form>
            </div>
      </div>
    </div>
  </div>
</div>
<!-- Small modal -->

<script>
    
    $(document).on('click','.btnsaverequest',function () {
        loading_button('.btnsaverequest');
        savedatarequestdoc();
    });

    function savedatarequestdoc() {

        var typerequest = $('.filtertypedocument option:selected').attr('id');
        var to_event = $('#to_event').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var DescriptionVenue = $('#DescriptionVenue').val();

        if(typerequest!=null && typerequest!=''
                && to_event!='' && to_event!=null
                && startDate!='' && startDate!=null
                && endDate!='' && endDate!=null
                && DescriptionVenue!='' && DescriptionVenue!=null)
        { 
    
            var data = {
                action : 'AddRequest',
                formInsert : {
                    typerequest : typerequest,
                    to_event : to_event,
                    startDate : startDate,
                    endDate : endDate,
                    DescriptionVenue : DescriptionVenue
                }
            };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api2/__crudrequestdoc';
                $.post(url,{token:token},function (result) {
                        
                    if(result==0 || result=='0'){
                        //toastr.error('Name division or module already is exist!','Error');
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

                $("#loadtablerequest").append('<div>                                               '+
                    '     <table class="table table-striped table-responsive table-bordered" id="tableList">                        '+
                    '         <thead>                                                               '+
                    '         <tr style="background: #3968c6;color: #FFFFFF;">                      '+
                    '             <th style="width: 5%;text-align: center;">No.</th>           		'+
                     '            <th style="width: 8%;text-align: center;">Name/ NIP</th>   		'+
                    '             <th style="width: 8%;text-align: center;">Type Request</th>       '+
                     '            <th style="width: 10%;text-align: center;">For Task</th>       	'+
                    '             <th style="width: 10%;text-align: center;">Date Time</th>   '+
                     '            <th style="width: 20%;text-align: center;">Description</th>        '+
                    '             <th style="text-align: center;width: 5%;">Action</th>             '+
                    '         </tr>                                                                 '+
                    '         </thead>                                                              '+
                    '         <tbody id="dataRow"></tbody>                                          '+
                    '    </table>                                                                   '+
                    '</div> ');  

                
                var orbs=0;
                for (var i = 0; i < response.length; i++) {

                    var idrequest = response[i]['IDRequest'];
                    var nip = response[i]['NIP'];
                    var tgl1 = response[i]['StartDate'];
                    var tgl2 = response[i]['EndDate'];

                    var StartDate = moment(tgl1).format('DD MMM YYYY');
                    var EndDate = moment(tgl2).format('DD MMM YYYY');

                    var token = jwt_encode({NIP : nip, IDRequest : idrequest },'UAP)(*');
                    var linksurat = base_url_js+"save2pdf/suratTugasKeluar/"+token; 
                    var buttonlink = ('<a href="'+linksurat+'" class="btn btn-success btn-circle" target="_blank"><i class="fa fa-download"></i></a> ');

                   $("#dataRow").append('<tr>                                                       	'+
                    '            <td>'+(i+1)+'</td>            												'+   
                    '            <td>'+response[i]['NIP']+' - '+response[i]['Name']+'</td>            	'+   
                    '            <td>'+response[i]['TypeFiles']+'</td>            						'+     
                    '            <td>'+response[i]['ForTask']+'</td>            						'+     
                    '            <td><b>'+StartDate+' s/d '+EndDate+'</b></td>            '+     
                    '            <td>'+response[i]['DescriptionAddress']+'</td>            '+   
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
    	format: 'yyyy-MM-dd hh:mm',
    	pickTime: true,
    	startDate:'+1d',
		use24hours: true,
        orientation: "top left",
        todayHighlight: true,
        showClose : true, 
    	autoclose: true,
	}).on('changeDate', function (selected) {
	    var startDate = new Date(selected.date.valueOf());
	    $('#datetimepicker2').datetimepicker('setStartDate', startDate);
	}).on('clearDate', function (selected) {
	    $('#datetimepicker2').datetimepicker('setStartDate', null);
	});

$("#datetimepicker2").datetimepicker({
    	format: 'yyyy-MM-dd hh:mm',
    	pickTime: true,
		use24hours: true,
    	autoclose: true,
	}).on('changeDate', function (selected) {
	    var endDate = new Date(selected.date.valueOf());
	    $('#datetimepicker1').datetimepicker('setEndDate', endDate);
	}).on('clearDate', function (selected) {
	    $('#datetimepicker1').datetimepicker('setEndDate', null);
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

