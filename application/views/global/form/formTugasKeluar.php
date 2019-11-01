<!-- <?php 

$d = $dataEmp[0];
// print_r($dataEmp); 

?> -->


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
    margin-top: -6px;
    text-align: center;
    font-size: 12px;
    line-height: 1.42857;
}

#tableList tr td, #tableList tr th {
    font-size: 13px;
}

.dlk-radio input[type="radio"],
.dlk-radio input[type="checkbox"] 
{
    margin-left:-99999px;
    display:none;
}
.dlk-radio input[type="radio"] + .fa ,
.dlk-radio input[type="checkbox"] + .fa {
     opacity:0.15
}
.dlk-radio input[type="radio"]:checked + .fa,
.dlk-radio input[type="checkbox"]:checked + .fa{
    opacity:1
}

.checkbox.checkbox-circle label::before {
  border-radius: 50%;
}

.btn-round{
    border-radius: 17px;
    text-align: center;
}

.btn-group > .btn:first-child, .btn-group > .btn:last-child {
     border-radius: 17px;
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
					    <div class="panel-heading panel-heading-custom">Document Request Form
                            <span class="pull-right clickable"><button class="btn btn-default btn-sm btn-circle"><i class="glyphicon glyphicon-chevron-up"></i> </button> </span> 

                        </div>
					    	<div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="text-primary">Name : <?= $d['TitleAhead']," ",$d['Name']," ",$d['TitleBehind']," -- ", $d['NIP']; ?> </label>
                                        </div>
                                    </div>
                                </div>

					    		<div class="row">
				                    <div class="col-xs-6">
				                        <div class="form-group">
				                            <label>Type Form</label>
				                            	<select class="form-control filtertypedocument" id="filaddivisi"><option id="" disabled selected> --- Select Type Form --- </option></select>
				                        </div>
				                    </div>
				                </div>

					    		<div class="row">
				                    <div class="col-xs-12">
				                        <div class="form-group">
				                            <label>Description Request</label>
                                                <textarea rows="2" cols="5" name="to_event" id="to_event" class="form-control"></textarea>
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
				                    <div class="col-xs-4 form-group" id="timeend">
				                		<label>End Time</label>
			                            <div id="timepicker2" data-no="1" class="input-group input-append date datetimepicker">
			                                <input data-format="hh:mm" type="text" class="form-control" id="endTime" readonly>
			                                <span class="add-on input-group-addon">
			                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-time"></i>
			                                </span>
			                            </div>
                                        <span> 
                                            <label><input type="checkbox" name="checkselesai" id="checkselesai"> Selesai </label>
                                        </span>
		                        	</div>
				                </div>

				                <div class="row">
				                	<div class="col-xs-12">
				                		<label>Description Location </label>
				                		<textarea rows="4" cols="5" name="DescriptionVenue" id="DescriptionVenue" class="form-control"></textarea>
				                	</div>
				                </div>
                                 <br />

				                <div class="row">
				                	<div class="col-xs-12 form-group" style="text-align: right;">

				                		<button type="button" class="btn btn-success btn-round btnsaverequest">
                                            <span class="glyphicon glyphicon-floppy-disk"></span>  Save Request
				                		</button>
						            </div>
						        </div>

					    
							</div>
					</div>
			</div>

		</div>
	</div>
</div>


<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header" style="background: #20485A;color: #FFFFFF;">
                <h4 style="color: #FFFFFF;"><i class="icon-reorder"></i> List Data Request</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group"></div>
                </div>
            </div>
            <div class="widget-content col-md-12">
                <div class="table table-responsive">
                    <table class="table table-bordered table-striped" id="tablemodule">
                        <thead>
                        <tr style="background: #3968c6;color: #FFFFFF;">
                            <th style="width: 3%;text-align: center;">No</th>
                            <th style="width: 10%;text-align: center;">Name/ NIP</th>
                            <th style="width: 20%;text-align: center;">Description Request</th>
                            <th style="width: 15%;text-align: center;">Start & End Date</th>
                            <th style="width: 12%;text-align: center;">Description Location</th>
                            <th style="width: 8%;text-align: center;">Date Confirm</th>
                            <th style="width: 8%;text-align: center;">Status</th>
                            <th style="width: 8%;text-align: center;">Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>            
                <!-- <div id="loadPage"></div> -->
            </div>
        </div>
    </div>
</div>


<script>
    $(document).on('click', '.panel-heading span.clickable', function(e){
       var $this = $(this);
        if(!$this.hasClass('panel-collapsed')) {
            $this.parents('.panel').find('.panel-body').slideUp();
            $this.addClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else {
            $this.parents('.panel').find('.panel-body').slideDown();
            $this.removeClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
})
</script>

<script>
    
</script>

<script>
    $(document).ready(function () {
        loadDataModule('');
    });

    $('#filterStatusEmployees').change(function () {
        var s = $(this).val();
    });

    function loadDataModule(status) {
        var dataTable = $('#tablemodule').DataTable( {
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                //url : base_url_js+"api/__getrequestnip?s="+status, // json datasource group
                url : base_url_js+"api/__getrequestnip", // json datasource'
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );
    }
</script>


<script>
    
    $(document).on('click','.btndetailrequest', function () {

        var requestid = $(this).attr('requestid');
        var url = base_url_js+'api2/__crudrequestdoc?s='+requestid;                         
        var token = jwt_encode({
                action:'get_detailrequest'
            },'UAP)(*');

        $.post(url,{token:token},function (resultJson) {
            console.log(resultJson); 
            var response = resultJson;
                if(response.length>0){
                    var no = 1;
                    for (var i = 0; i < response.length; i++) {

                    var awal = response[i]['StartDate'];
                    var akhir = response[i]['EndDate'];
                    var datereq = response[i]['EndDate'];
                    var datecom = response[i]['EndDate'];

                    var startdatex = moment(awal).format('DD MMM YYYY HH:mm');
                    var enddatex = moment(akhir).format('DD MMM YYYY HH:mm');
                    var daterequest = moment(datereq).format('DD MMM YYYY HH:mm');
                    var daterconfirm = moment(datecom).format('DD MMM YYYY HH:mm');

                    var dayawal = moment(awal).format('dddd');
                    var dayakhir = moment(akhir).format('dddd');


                    if(response[i]['ConfirmStatus'] == 0) {
                        var confirmx = "Waiting Confirmation";

                    } else if(response[i]['ConfirmStatus'] == 1) {
                        var confirmx = "Approved";

                    } else {
                        var confirmx = "Rejected";
                    }

                    if(response[i]['UserConfirm'] == null && response[i]['UserConfirm'] == "") {
                        var namcomfirm = "-";
                    } 
                    else {
                        var namcomfirm = response[i]['namaconfirm'];
                    }

                    $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
                        ' <span aria-hidden="true">&times;</span></button> '+
                        ' <h4 class="modal-title">Detail Data Request</h4>');
                    $('#GlobalModal .modal-body').html('<span><b>Data Request</b></span>' +
                        '<table class="table table-striped">'+
                        '<tr>' +
                        '   <td style="width: 40%;">Type </td>' +
                        '   <td><b>'+response[i]['NameFiles']+' <b></td>' +
                        '</tr>' +
                        '</tr>' +
                        '   <td style="width: 40%;">Date Request</td>' +
                        '   <td><b>'+daterequest+'<b></td>' +
                        '</tr>' +
                        '<tr>' +
                        '   <td style="width: 40%;">Description Request</td>' +
                        '   <td><b>'+response[i]['ForTask']+'<b></td>' +
                        '</tr>' +
                        '<tr>' +
                        '   <td style="width: 40%;">Start Date</td>' +
                        '   <td><b>'+dayawal+', '+startdatex+' <b></td>' +
                        '</tr>' +
                         '<tr>' +
                        '   <td style="width: 40%;">End Date</td>' +
                        '   <td><b>'+dayakhir+', '+enddatex+'<b></td>' +
                        '</tr>' +
                        '<tr>' +
                        '   <td style="width: 40%;">Description Location</td>' +
                        '   <td><b>'+response[i]['DescriptionAddress']+' <b></td>' +
                        '<tr>' +
                        '<br/>' +
                        '</table> '+
                        '<span><b>Detail Confirmation</b></span>' +
                        '<table class="table table-striped">'+
                        '<tr>' +
                        '   <td style="width: 40%;">Status Confirmation</td>' +
                        '   <td style="color:blue;"><b>'+confirmx+' </b></td>' +
                        '</tr>' +
                        '<tr>' +
                        '   <td style="width: 40%;">User Confirmation</td>' +
                        '   <td style="color:blue;">'+namcomfirm+'</td>' +
                        '</tr>' +
                        '<tr>' +
                        '   <td style="width: 40%;">Date Confirmation</td>' +
                        '   <td style="color:blue;">'+daterconfirm+'</td>' +
                        '</tr>' +
                        '</table> ');
                    $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-danger btn-round" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>');
                
                    $('#GlobalModal').modal({
                        'backdrop' : 'static',
                        'show' : true
                    }); 
                        
                    } //end for
                } //end if
            }); //end json  
         //END IF
    });


    $(document).on('click','.btneditrequest', function () {

        var requestid = $(this).attr('requestid');
        var url = base_url_js+'api2/__crudrequestdoc?s='+requestid;                         
        var token = jwt_encode({
                action:'get_editrequest'
            },'UAP)(*');

        $.post(url,{token:token},function (resultJson) {
            console.log(resultJson); 
            var response = resultJson;
                if(response.length>0){
                    var no = 1;
                    for (var i = 0; i < response.length; i++) {

                    $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
                        ' <span aria-hidden="true">&times;</span></button> '+
                        ' <h4 class="modal-title">Detail Version Data</h4>');
                    $('#GlobalModal .modal-body').html('<table class="table">' +
                         '<tr>' +
                        '   <td style="width: 25%;">No. Version</td>' +
                        '   <td><b>'+response[i]['Version']+' </b></td>' +
                        '</tr>' +
                        '<tr>' +
                        '   <td style="width: 25%;">Name Division</td>' +
                        '   <td>'+response[i]['Division']+'</td>' +
                        '</tr>' +
                        '<tr>' +
                        '   <td style="width: 25%;">Name Module</td>' +
                        '   <td>'+response[i]['NameModule']+'</td>' +
                        '</tr>' +
                        '   <td style="width: 25%;">Date Update</td>' +
                        '   <td>'+response[i]['UpdateAt']+'</td>' +
                        '</tr>' +
                        '   <td style="width: 25%;">Name PIC</td>' +
                        '   <td>'+response[i]['NamePIC']+'</td>' +
                        '</tr>' +
                        '<tr>' +
                        '   <td style="width: 25%;">Description</td>' +
                        '   <td>'+response[i]['Description']+'</td>' +
                        '</tr>' +
                        '</table>');
                    $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-danger btn-round" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>');
                
                    $('#GlobalModal').modal({
                        'backdrop' : 'static',
                        'show' : true
                    }); 
                        
                    } //end for
                } //end if
            }); //end json  
         //END IF
    });


    $(document).on('click','.btnsaverequest',function () {
        $('#NotificationModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
                                ' <span aria-hidden="true">&times;</span></button> '+
                                ' <h4 class="modal-title">Confirmation All Approved </h4>');
        $('#NotificationModal .modal-body').html('<center><p><b>Apa Anda Yakin untuk simpan Permintaan Surat Tugas Keluar ini? Periksa kembali data Anda sebelum disimpan.</b></p>'+
            '<div class="btn-group"><button class="btn btn-sm btn-success btn-round btn-action saverequest_data"> <i class="glyphicon glyphicon-ok-sign"></i> Save </button> <button class="btn btn-sm btn-danger btn-round btn-addgroup" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Cancel</button></center></div></div>');

        $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
        }); 
     });


    $(document).on('click','.btndeleterequest',function () {

        var requestID = $(this).attr('requestid');

        $('#NotificationModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
                                ' <span aria-hidden="true">&times;</span></button> '+
                                ' <h4 class="modal-title">Confirmation Delete </h4>');
        $('#NotificationModal .modal-body').html('<center><p><b>Apa Anda Yakin untuk Hapus Permintaan Surat Tugas Keluar ini? </b></p>'+
            '<div class="btn-group "><button class="btn btn-sm btn-success btn-round btn-action reqdeleted" idrequestdel= "'+requestID+'"> <i class="glyphicon glyphicon-ok-sign"></i> Delete </button> <button class="btn btn-sm btn-danger btn-round btn-addgroup" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Cancel</button></center></div></div>');

        $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
        }); 
     });

     
    

    $(document).on('click','.reqdeleted',function () {
        var requestID = $(this).attr('idrequestdel');
        loading_button('.reqdeleted');
        $('.reqdeleted').prop('disabled',true);
        requestdeleted(requestID);
    });
    
    
    $(document).on('click','.saverequest_data',function () {
        loading_button('.saverequest_data');
        $('.saverequest_data').prop('disabled',true);
        savedatarequestdoc();
    });

    $('#checkselesai').change(function(){

        if($('#checkselesai').is(':checked')){
            $('#timepicker2').addClass('hide');
        } else {
            $('#timepicker2').removeClass('hide');
        }
    });

    function requestdeleted(requestID) {
        $('#NotificationModal').modal('hide');
        var data = {
                action : 'delete_request',
                requestID : requestID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api2/__crudrequestdoc';
            $.post(url,{token:token},function (result) {

                 if(result==0 || result=='0'){
                        //$('.btnsaverequest').prop('disabled',false).html('<span class="glyphicon glyphicon-floppy-disk"></span> Save');
                    } else {  
                        toastr.success('Success Delete Data!','Success');

                        $('#GlobalModal .modal-header').addClass('hide');
                        $('#GlobalModal .modal-footer').addClass('hide');
                        $('#GlobalModal .modal-dialog').removeClass('modal-sm modal-lg');
                        $('#GlobalModal .modal-dialog').addClass('modal-sm');
                        $('#GlobalModal .modal-body').html('<div class="container"> '+
                                ' <center>Mohon Menunggu... <br/><span class="fa fa-spinner fa-spin fa-3x"></span> </center> '+
                                '</div>');
                        $('#GlobalModal').modal({
                            'backdrop' : 'static',
                            'show' : true
                        });
                        
                        setTimeout(function () {
                            window.location.href = '';
                        },5000);
                   }
            });
    }


    function savedatarequestdoc() {
        $('#NotificationModal').modal('hide');
        var typerequest = $('.filtertypedocument option:selected').attr('id');
        var to_event = $('#to_event').val();

        var startDate = $('#startDate').val();
        var startTime = $('#startTime').val();
        var stardatetime = startDate+' '+startTime;

        var endDate = $('#endDate').val();
        
        if($('#checkselesai').is(':checked')){
            var endTime = "00:00";
        } else {
            var endTime = $('#endTime').val();
        }
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
                        //$('.btnsaverequest').prop('disabled',false).html('<span class="glyphicon glyphicon-floppy-disk"></span> Save');
                    } else {  
                        $('#GlobalModal .modal-header').addClass('hide');
                        $('#GlobalModal .modal-footer').addClass('hide');
                        $('#GlobalModal .modal-dialog').removeClass('modal-sm modal-lg');
                        $('#GlobalModal .modal-dialog').addClass('modal-sm');
                        $('#GlobalModal .modal-body').html('<div class="container"> '+
                                ' <center>Mohon Menunggu... <br/><span class="fa fa-spinner fa-spin fa-3x"></span> </center> '+
                                '</div>');
                        $('#GlobalModal').modal({
                            'backdrop' : 'static',
                            'show' : true
                        });

                        toastr.success('Request Document Saved','Success');
                        setTimeout(function () {
                            window.location.href = '';
                        },3000);
                    }
                });
        }
        else {
            toastr.error('The form is still empty!','Error');
            $('#NotificationModal').modal('hide');
           // $('.btnsaverequest').prop('disabled',false).html('<span class="glyphicon glyphicon-floppy-disk"></span> Save');
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
        //loadlistrequestdocument();
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

