<div class="row btn-read">
    <div class="col-md-6 col-md-offset-3">
      <div class="thumbnail">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Year</label>
              <select class="select2-select-00 full-width-fix" id="Years">
                   <!-- <option></option> -->
               </select>
            </div>  
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Month</label>
              <select class="select2-select-00 full-width-fix" id="Month">
                   <!-- <option></option> -->
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
$(document).ready(function() {
	LoadFirstLoad();
	loadingEnd(500);
}); // exit document Function

function LoadFirstLoad()
{
	load_table_activated_period_years();
	// LoadDataForTable();
}

function load_table_activated_period_years()
{
   // load Year
   $("#Years").empty();
   var url = base_url_js+'budgeting/table_all/cfg_dateperiod/1';
   var thisYear = (new Date()).getFullYear();
   $.post(url,function (resultJson) {
    var response = jQuery.parseJSON(resultJson);
    for(var i=0;i<response.length;i++){
        //var selected = (i==0) ? 'selected' : '';
        var selected = (response[i].Activated==1) ? 'selected' : '';
        $('#Years').append('<option value="'+response[i].Year+'" '+selected+'>'+response[i].Year+'</option>');
    }
    $('#Years').select2({
       //allowClear: true
    });

    // load bulan
    var S_bulan = $('#Month');
    SelectOptionloadBulan(S_bulan,'choice');
    LoadDataForTable();
   }); 
}

$(document).off('click', '#Years').on('click', '#Years',function(e) {
  LoadDataForTable();
})

$(document).off('click', '#Month').on('click', '#Month',function(e) {
  LoadDataForTable();
})

function LoadDataForTable()
{
	$("#DivTable").empty();
	var table_html = '<table class="table table-bordered" id = "tableData_payment" style="width: 100%;">'+
	            '<thead>'+
	            '<tr>'+
	                '<th  width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
                  '<th  style = "text-align: center;background: #20485A;color: #FFFFFF;">Payment</th>'+
	                '<th  style = "text-align: center;background: #20485A;color: #FFFFFF;">Department</th>'+
	                '<th  style = "text-align: center;background: #20485A;color: #FFFFFF;">Status</th>'+
	                '<th  style = "text-align: center;background: #20485A;color: #FFFFFF;">Realisasi</th>'+
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
   	var data = {
         Years : Years,
         Month : Month,
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
	    	      Payment += '<br>Created : '+data[parseInt(data.length) - 2];
	    	       
	    	       $( row ).find('td:eq(1)').html(Payment);
    		    	
    		    	$( row ).find('td:eq(2)').attr('align','center');
    		    	var st = (StatusPayFin == 2) ? '<i class="fa fa-check-circle" style="color: green;"></i>' : '';
    		    	$( row ).find('td:eq(3)').html(st);
    		    	$( row ).find('td:eq(3)').attr('align','center');
    		    	var htmlrealisasi = '';
    		    	if (RealisasiTotal > 0) {
    		    		htmlrealisasi = '<i class="fa fa-check" style="color: green;"></i>';
    		    	}
    		    	else
    		    	{
    		    		htmlrealisasi = '<i class="fa fa-minus-circle" style="color: red;"></i>';
    		    	}

    		    	if (RealisasiStatus == 2) {
    		    		htmlrealisasi += '<br><div style = "color:green;">Done</div>';
    		    	}
    		    	else
    		    	{
    		    		htmlrealisasi += '<br><div style = "color:red;">Realiasi Approval not yet</div>';
    		    	}
    		    	$( row ).find('td:eq(4)').html(htmlrealisasi);
    		    	$( row ).find('td:eq(4)').attr('align','center');
	    },
   	    "initComplete": function(settings, json) {
   	        def.resolve(json);
   	    }
   	});
   return def.promise();
}

</script>