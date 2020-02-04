<div class="row btn-read">
    <div class="col-md-6 col-md-offset-3">
      <div class="thumbnail">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Year</label>
              <select class="select2-select-00 full-width-fix" id="Years">
                   <!-- <option></option> -->
               </select>
            </div>  
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Month</label>
              <select class="select2-select-00 full-width-fix" id="Month">
                   <!-- <option></option> -->
               </select>
            </div>  
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Realisasi Status</label>
              <select class="form-control" id="RealisasiStatus">
                   <option value="%">All</option>
                   <option value="0" selected>Belum Realisasi</option>
                   <option value="1">Realisasi Belum Konfirmasi</option>
                   <option value="2">Realisasi Sudah Konfirmasi</option>
               </select>
            </div>  
          </div>
        </div>
        <div class="row">
          <div class="col-xs-6">
            <div class="form-group">
              <label>Type</label>
              <select class="form-control TypePaymentSelect">
                   <option value = "%" selected>All</option>
                   <option value = "Spb">SPB</option>
                   <option value = "Cash Advance">Cash Advance</option>
                   <option value = "Bank Advance">Bank Advance</option>
               </select>
            </div>
          </div>
          <div class="col-xs-6">
            <div class="form-group">
              <label>Event / Template</label>
              <select class="form-control SelectTemplate">
                   <option value = "%" selected>--No Selected--</option>
               </select>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="row btn-read">
	<div class="col-md-12">
		<div class="table-responsive" id = "DivTable">
			
		</div>
	</div>
</div>
<script type="text/javascript">
  // get Departmentpu
var IDDepartementPUBudget = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";

function __LoadTemplate()
{
  var def = jQuery.Deferred();
  var url = base_url_js+'rest2/__LoadTemplate';
  var data = {
      auth : 's3Cr3T-G4N',
      Active : 1,
  };
  var token = jwt_encode(data,"UAP)(*");
  $.post(url,{ token:token },function (resultJson) {
    
  }).done(function(resultJson) {
    def.resolve(resultJson);
  }).fail(function() {
    toastr.info('No Result Data');
    def.reject();  
  }).always(function() {
                  
  }); 
  return def.promise();
}  

$(document).ready(function() {
	LoadFirstLoad();
	loadingEnd(500);
}); // exit document Function

function LoadFirstLoad()
{
  if (IDDepartementPUBudget != 'NA.9') {
    $('#DivTable').html('<h2 align="center">Your are not authorize</h2>');
  }
  else
  {
    load_table_activated_period_years();
    // LoadTemplate
    __LoadTemplate().then(function(dataTemplate){
      var h = '';
          for (var i = 0; i < dataTemplate.length; i++) {
              h += '<option value = "'+dataTemplate[i].ID+'" '+''+' >'+dataTemplate[i].Name+'</option>';
          }
      $('.SelectTemplate').append(h);

    })
  }
	
	// LoadDataForTable();
}

function load_table_activated_period_years()
{
   // load Year
   $("#Years").empty();
   var url = base_url_js+'budgeting/table_all/cfg_dateperiod/1';
   var thisYear = (new Date()).getFullYear();
   for (var i = 2019; i <= thisYear; i++) {
     var selected = (i==0) ? 'selected' : '';
     $('#Years').append('<option value="'+i+'" '+selected+'>'+i+'</option>');
   }

   $('#Years').select2({
      //allowClear: true
   });

   // load bulan
   var S_bulan = $('#Month');
   SelectOptionloadBulan(S_bulan,'choice');
   LoadDataForTable();
   // $.post(url,function (resultJson) {
   //  var response = jQuery.parseJSON(resultJson);
   //  for(var i=0;i<response.length;i++){
   //      //var selected = (i==0) ? 'selected' : '';
   //      var selected = (response[i].Activated==1) ? 'selected' : '';
   //      $('#Years').append('<option value="'+response[i].Year+'" '+selected+'>'+response[i].Year+'</option>');
   //  }
   //  $('#Years').select2({
   //     //allowClear: true
   //  });

   //  // load bulan
   //  var S_bulan = $('#Month');
   //  SelectOptionloadBulan(S_bulan,'choice');
   //  LoadDataForTable();
   // }); 
}

$(document).off('change', '#Years').on('change', '#Years',function(e) {
  LoadDataForTable();
})

$(document).off('change', '#Month').on('change', '#Month',function(e) {
  LoadDataForTable();
})

$(document).off('change', '#RealisasiStatus,.TypePaymentSelect,.SelectTemplate').on('change', '#RealisasiStatus,.TypePaymentSelect,.SelectTemplate',function(e) {
  LoadDataForTable();
})

function LoadDataForTable()
{
	$("#DivTable").empty();
	var table_html = '<table class="table table-bordered" id = "tableData_payment" style="width: 100%;">'+
	            '<thead>'+
	            '<tr>'+
	                '<th  rowspan = "2" width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
                  '<th  rowspan = "2" style = "text-align: center;background: #20485A;color: #FFFFFF;">Payment</th>'+
	                '<th  rowspan = "2" style = "text-align: center;background: #20485A;color: #FFFFFF;">Department</th>'+
	                '<th  rowspan = "2" style = "text-align: center;background: #20485A;color: #FFFFFF;">Status</th>'+
                  '<th  colspan ="2" style = "text-align: center;background: #20485A;color: #FFFFFF;">Realisasi</th>'+
                  '<th  rowspan ="2" style = "text-align: center;background: #20485A;color: #FFFFFF;">Vendor & Paid Date</th>'+
              '</tr>'+
              '<tr>'+
                '<td style = "text-align: center;background: #20485A;color: #FFFFFF;">Status</td>'+
                '<td style = "text-align: center;background: #20485A;color: #FFFFFF;">Reminder</td>'+
              '</tr>'+  
	            '</thead>'+
	            '<tbody id="dataRow"></tbody>'+
	        '</table>';
	$("#DivTable").html(table_html);

	Get_data_payment().then(function(data){
		
	})
}

function Get_data_payment(){
   var def = jQuery.Deferred();

   var Years = $('#Years option:selected').val();
   var Month = $('#Month option:selected').val();
   var RealisasiStatus = $('#RealisasiStatus option:selected').val();
   var TypePaymentSelect = $('.TypePaymentSelect option:selected').val();
   var SelectTemplate = $('.SelectTemplate option:selected').val();
   	var data = {
         Years : Years,
         Month : Month,
         RealisasiStatus : RealisasiStatus,
         TypePaymentSelect : TypePaymentSelect,
         SelectTemplate : SelectTemplate,
   	};
   	var token = jwt_encode(data,"UAP)(*");

   	var table = $('#tableData_payment').DataTable({
   		"fixedHeader": true,
   	    "processing": true,
   	    "destroy": true,
   	    "serverSide": true,
   	    "lengthMenu": [[5], [5]],
   	    "iDisplayLength" : 5,
   	    "language": {
   	        "searchPlaceholder": "PRCode, PO Code & SPB Code",
   	    },
   	    "ordering" : false,
   	    "ajax":{
   	        url : base_url_js+"finance_ap/list_server_side", // json datasource
   	        ordering : false,
   	        type: "post",  // method  , by default get
   	        data : {token : token},
   	        error: function(){  // error handling
   	            $(".employee-grid-error").html("");
   	            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
   	            $("#employee-grid_processing").css("display","none");
   	            def.reject();

   	        },
   	    },
	    'createdRow': function( row, data, dataIndex ) {
	    	       var ListPR = data[parseInt(data.length) - 1];
	    	       var PRHTML = '';
	    	       PRHTML += ListPR[0];
	    	       var ID_payment = ListPR[1].ID_payment;
	    	       var ID_payment_fin = ListPR[1].ID_payment_fin;
	    	       var StatusPayFin = ListPR[1].StatusPayFin;
	    	       var RealisasiStatus = ListPR[1].RealisasiStatus;
               var RealisasiTotal = ListPR[1].RealisasiTotal;
               var ReminderTotal = ListPR[1].ReminderTotal;
               var NamaSupplier = (ListPR[1].NamaSupplier != null && ListPR[1].NamaSupplier != '') ? ListPR[1].NamaSupplier : '-';
	    	       var PostingDatePaid = NamaSupplier+'<br>'+ListPR[1].PostingDatePaid;
               
	    	       var CodeSPB = ListPR[1].CodeSPB;
	    	       var TypePay = ListPR[1].TypePay;
	    	       var Perihal = ListPR[1].Perihal;
	    	       var Code_po_create = '';
	    	       if (data[1] != null && data[1] != '') {
	    	       	var Code_po_create = data[1];
	    	       }

	    	       var token = jwt_encode(ID_payment_fin,"UAP)(*");
	    	       var Payment = '<a href="'+base_url_js+'finance_ap/global/'+token+'">Perihal : '+Perihal+'</a>';
  	    	        Payment += '<p> Type : '+TypePay+'</p>';
	    	       if (TypePay == 'Spb') {
	    	       	Payment += '<p>Code : '+CodeSPB+'</p>';
	    	       }
	    	      if (Code_po_create != '') {
	    	      	 Payment += '<label> PO/SPK Code : '+Code_po_create+'</label>';
	    	      }
	    	      if (Code_po_create != '') {
	    	      	 Payment += '<br>PR Code : '+PRHTML;
	    	      }
              // Payment += '<br>Created : '+data[parseInt(data.length) - 2];
	    	      Payment += '<br>Created : '+ListPR[1].PayNameCreatedBy;
	    	       
	    	       $( row ).find('td:eq(1)').html(Payment);
    		    	
    		    	$( row ).find('td:eq(2)').attr('align','center');
    		    	var st = (StatusPayFin == 2) ? '<i class="fa fa-check-circle" style="color: green;"></i>' : '';
    		    	$( row ).find('td:eq(3)').html(st);
    		    	$( row ).find('td:eq(3)').attr('align','center');
    		    	var htmlrealisasi = '';
              var htmlreminder = '';
    		    	if (RealisasiTotal > 0) {
    		    		htmlrealisasi = '<i class="fa fa-check" style="color: green;"></i>';
                htmlreminder = '<div class="TotReminder" tot = "'+ReminderTotal+'" align="center">Total Reminder : '+ReminderTotal+'</div>'
    		    	}
    		    	else
    		    	{
    		    		htmlrealisasi = '<i class="fa fa-minus-circle" style="color: red;"></i>';
                htmlreminder = '<div class="TotReminder" tot = "'+ReminderTotal+'" align="center">Total Reminder : '+ReminderTotal+'</div>'+
                                  '<div align = "center"><button class = "btn btn-primary btnSendReminder" ID_payment = "'+ID_payment+'"><i class="fa fa-envelope-open" aria-hidden="true"></i> Send Reminder</button></div>';
    		    	}

    		    	if (RealisasiStatus == 2) {
    		    		htmlrealisasi += '<br><div style = "color:green;">Done</div>';
                htmlreminder = '<div class="TotReminder" tot = "'+ReminderTotal+'" align="center">Total Reminder : '+ReminderTotal+'</div>';
    		    	}
    		    	else
    		    	{
    		    		htmlrealisasi += '<br><div style = "color:red;">Realiasi Approval not yet</div>';
    		    	}
    		    	$( row ).find('td:eq(4)').html(htmlrealisasi);
              $( row ).find('td:eq(4)').attr('align','center');
    		    	$( row ).find('td:eq(6)').attr('align','center');
              $(row).find('td:eq(6)').html(PostingDatePaid);
              // $(row).find('td:eq(7)').html(NamaSupplier);
              $( row ).find('td:eq(5)').html(htmlreminder);
	    },
   	    "initComplete": function(settings, json) {
   	        def.resolve(json);
   	    }
   	});
   return def.promise();
}

$(document).off('click', '.btnSendReminder').on('click', '.btnSendReminder',function(e) {
  var ev = $(this).closest('td');
  if (confirm('Are you sure ?')) {
    var ID_payment = $(this).attr('ID_payment');
    var url = base_url_js+"finance_ap/send_reminder_realisasi";
      data = {
        ID_payment : ID_payment,
      };
    var token = jwt_encode(data,"UAP)(*");
    ev.find('.btnSendReminder').html('<i class="fa fa-refresh fa-spin fa-fw right-margin"></i> Loading...');
    ev.find('.btnSendReminder').prop('disabled',true);
    $.post(url,{ token:token },function (resultJson) {
      
    }).done(function(resultJson) {
      var response = jQuery.parseJSON(resultJson);
      var tot = response.total;
      ev.find('.TotReminder').attr('tot',tot);
      ev.find('.TotReminder').html('Total Reminder : '+tot);
      ev.find('.btnSendReminder').html('<i class="fa fa-envelope-open" aria-hidden="true"></i> Send Reminder');
      ev.find('.btnSendReminder').prop('disabled',false);
      toastr.success('Reminder send');
    });
  }
});

</script>


